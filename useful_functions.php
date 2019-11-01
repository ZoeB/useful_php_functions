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

?>
