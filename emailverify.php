<?php

$firstnameemail = filter_input(INPUT_GET, 'firstname', FILTER_SANITIZE_STRING);
$minameemail = filter_input(INPUT_GET, 'miname', FILTER_SANITIZE_STRING);
$lastnameemail = filter_input(INPUT_GET, 'lastname', FILTER_SANITIZE_STRING);
$maidennameemail = filter_input(INPUT_GET, 'maidenname', FILTER_SANITIZE_STRING);
$emailemail = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);
$yearemail = filter_input(INPUT_GET, 'year', FILTER_SANITIZE_NUMBER_INT);
$now = filter_input(INPUT_GET, 'n', FILTER_SANITIZE_NUMBER_INT);

$stmt = $db_conn->prepare("SELECT id FROM users WHERE email=? AND email_verify=?");
$stmt->execute(array($emailemail,$now));
$rowemail = $stmt->fetch();
$idemail = $rowemail[0];
if ($idemail) {
    echo "Your email has already been verified.  There is no need to reverify it.";
} else {
    echo "<div style='text-align:justify; font-size:1.25em;'>Thank you for verifying your email address.  A moderator will now review your application.  You will receive and email informing you if you have (or don't have) access to the site.<br /><br />If you have not received your email fairly soon, check your junk or spam folder.</div>";
    $substmt = $db_conn->prepare("INSERT INTO users VALUES" . "(NULL, NULL, NULL, ?, ?, ?, ?, NULL, NULL, NULL, ?, NULL, '1', ?, '0', NULL, '0', '1', '0', '0', ?)");
    $substmt->execute(array($firstnameemail,$minameemail,$lastnameemail,$maidennameemail,$emailemail,$yearemail,$now));
    $subsubstmt = $db_conn->prepare("SELECT firstname,email FROM users WHERE year=? AND userlvl='2'");
    $subsubstmt->execute(array($yearemail));
    while ($row = $subsubstmt->fetch()) {
        $firstname = $row[0];
        $email = $row[1];
        $message = "
                <html><head></head><body>
                Hello $firstname,<br />
                This is just a note to tell you that $firstnameemail $lastnameemail has just submitted and application to join the alumni site.<br /><br />
                <a href='index.php'>index.php</a>
                </body></html>";
        // In case any of our lines are larger than 70 characters, we should use wordwrap()
        $message = wordwrap($message, 70);
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: Saint Francis Alumni <support@stfrancisalumni.org>' . "\r\n";
        // Send
        mail($email, 'New application received on stfrancisalumni.com', $message, $headers);
    }
}
?>
