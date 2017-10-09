<?php

class DateTimeView {

	public function show() {
		date_default_timezone_set('Europe/Stockholm');

		// Ex. Monday, the 11th of September 2017, The time is 08:44:37
		$timeString = date('l') . ', the ' . date('d') . 'th of ' . date('F') . ' ' . date('Y') . ', The time is ' . date('H:i:s');

		return '<p>' . $timeString . '</p>';
	}
}