<?php

/*
 * Useful PHP functions.  By Zoë Blade.  Public domain / CC0 1.0
 */

/**
 * Convert an unformatted UK date to ISO 2014 format
 *
 * Avoid false positives by not using strtotime().
 *
 * @param string $data An unformatted UK date
 * @return string The same date, where possible (ISO 2014 format), else null
 */

function convertUnformattedUkDateToIsoDate($date) {
	$date = strtolower($date);

	$date = preg_replace('/jan[a-z]*/', '01', $date);
	$date = preg_replace('/feb[a-z]*/', '02', $date);
	$date = preg_replace('/mar[a-z]*/', '03', $date);
	$date = preg_replace('/apr[a-z]*/', '04', $date);
	$date = preg_replace('/may[a-z]*/', '05', $date);
	$date = preg_replace('/jun[a-z]*/', '06', $date);
	$date = preg_replace('/jul[a-z]*/', '07', $date);
	$date = preg_replace('/aug[a-z]*/', '08', $date);
	$date = preg_replace('/sep[a-z]*/', '09', $date);
	$date = preg_replace('/oct[a-z]*/', '10', $date);
	$date = preg_replace('/nov[a-z]*/', '11', $date);
	$date = preg_replace('/dev[a-z]*/', '12', $date);

	if (preg_match('/^([0-9]{1,2})(st)?(nd)?(rd)?(th)?[.\/ -]([0-9]{1,2})[.\/ -]([0-9]{4})$/', $date, $matches) != false) {
		return $matches[7].'-'.str_pad($matches[6], 2, '0', STR_PAD_LEFT).'-'.str_pad($matches[1], 2, '0', STR_PAD_LEFT);
	}

	if (preg_match('/^([0-9]{1,2})(st)?(nd)?(rd)?(th)?[.\/ -]([0-9]{1,2})[.\/ -]([0-9]{2})$/', $date, $matches) != false) {
		return '20'.$matches[7].'-'.str_pad($matches[6], 2, '0', STR_PAD_LEFT).'-'.str_pad($matches[1], 2, '0', STR_PAD_LEFT); // Assume the 21st century for 2-digit years
	}

	return null;
}

/**
 * Explode a string into an indexed array
 *
 * @param string $whole The string to be converted into an array
 * @param string $innerDelimiter The string separating each pair's key from its value
 * @param string $outerDelimiter The string separating each full pair from its neighbours
 * @return array The converted array
 */

function explodeWithKey($whole, $innerDelimiter = ':', $outerDelimiter = ',') {
	if (!is_string($whole) || empty($whole)) {
		return null;
	}

	$unindexedArray = explode($outerDelimiter, $whole);
	$indexedArray = array();

	foreach ($unindexedArray as $pair) {

		/*
		 * The first part of the pair is the key.  There should
		 * only be one other part, the value.  However, some
		 * data may use the $innerDelimiter more than once
		 * within the $outerDelimiter's data.  In such an
		 * instance, we should assume that only the first
		 * instance of the $innerDelimiter is intended to
		 * separate the key from the value, and the rest should
		 * be taken literally.
		 */

		$pair = explode($innerDelimiter, $pair);
		$key = $pair[0];
		unset($pair[0]);
		$value = implode($innerDelimiter, $pair);
		$indexedArray[$key] = $value;
	}

	return $indexedArray;
}

/**
 * Implode an indexed array into a string
 *
 * @param array $indexedArray The array to be converted into a string
 * @param string $innerDelimiter The string separating each pair's key from its value
 * @param string $outerDelimiter The string separating each full pair from its neighbours
 * @param string $innerQuote What, if anything, to wrap around each value
 * @return string The converted array
 */

function implodeWithKey($indexedArray, $innerDelimiter = ':', $outerDelimiter = ',', $innerQuote = '') {
	if (!is_array($indexedArray) || empty($indexedArray)) {
		return null;
	}

	$unindexedArray = array();

	foreach ($indexedArray as $key => $value) {
		$unindexedArray[] = $key.$innerDelimiter.$innerQuote.$value.$innerQuote;
	}

	return implode($outerDelimiter, $unindexedArray);
}

/**
 * Verify whether a variable is empty or not, recursively examining the whole array if it is an array
 *
 * @param mixed $data The variable to verify the emptiness of
 * @return boolean Whether the variable is empty
 */

function isAllEmpty($data = array()) {
	if (!is_array($data)) {
		return empty($data);
	}

	foreach ($data as $value) {
		if (!isAllEmpty($value)) {
			return false;
		}
	}

	return true;
}

?>
