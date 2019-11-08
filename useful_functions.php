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
 * Explode a string into an array, with keys
 */

function kexplode($innerGlue, $outerGlue, $whole) {
	if (!is_string($whole) || empty($whole)) {
		return null;
	}

	$rawPieces = explode($outerGlue, $whole);
	$pieces = array();

	foreach ($rawPieces as $rawPiece) {
		// The first part of the piece is the key.  There should only be one other part, the value.  However, some data may be technically incorrect, using the innerGlue more than once within the outerGlue.  Obviously, in such an instance, we should assume that only the first instance of the innerGlue is intended to separate the key from the value, and the rest should be taken literally.
		$subPieces = explode($innerGlue, $rawPiece);
		$key = $subPieces[0];
		unset($subPieces[0]);
		$value = implode($innerGlue, $subPieces);
		$pieces[$key] = $value;
	}

	return $pieces;
}

/**
 * Implode an array into a string, with keys
 */

function kimplode($innerGlue, $outerGlue, $pieces, $innerQuotes = false) {
	if (!is_array($pieces) || empty($pieces)) {
		return null;
	}

	$numberOfPieces = count($pieces);
	$imploded = '';
	$i = 0;

	foreach ($pieces as $pieceKey => $pieceValue) {
		$i++;

		if ($innerQuotes == true) {
			$imploded .= $pieceKey.$innerGlue.'"'.$pieceValue.'"';
		} else {
			$imploded .= $pieceKey.$innerGlue.$pieceValue;
		}

		if ($i != $numberOfPieces) {
			$imploded .= $outerGlue;
		}
	}

	return $imploded;
}

?>
