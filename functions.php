<?php
session_start ();

include "../globalFunctions.php";

$db_conn = db_alumni();
$db_ccdc = db_ccdc();

$salt1 = "mk&*";
$salt2 = "^&gh";
$time = time ();
function send_email($to, $subject, $body) {
	if (! $to)
		$to = "jcs@padgett-online.com";
	if (! $subject)
		$subject = "An email from your website.";
	if (! $body)
		$body = "The body information wasnt sent.";
	mail ( $to, $subject, $body );
}
function destroySession() {
	$_SESSION = array ();

	if (ini_get ( "session.use_cookies" )) {
		$params = session_get_cookie_params ();
		setcookie ( session_name (), '', time () - 42000, $params ["path"], $params ["domain"], $params ["secure"], $params ["httponly"] );
	}

	session_destroy ();
}
function make_links_clickable($text) {
	return preg_replace ( '!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', "<a href='$1' target='_blank'>$1</a>", $text );
}
function money($amt) {
	settype ( $amt, "float" );
	$fmt = new NumberFormatter ( 'en_US', NumberFormatter::CURRENCY );
	return $fmt->formatCurrency ( $amt, "USD" );
}