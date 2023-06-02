<?php
session_start();

include "../globalFunctions.php";

$db_conn = db_alumni();
$db_ccdc = db_ccdc();

$salt1 = "mk&*";
$salt2 = "^&gh";
$time = time();
$domain = "stfrancisalumni.org";

function send_email ($to, $subject, $body)
{
    if (! $to)
        $to = "jcs@padgett-online.com";
    if (! $subject)
        $subject = "An email from your website.";
    if (! $body)
        $body = "The body information wasnt sent.";
    mail($to, $subject, $body);
}