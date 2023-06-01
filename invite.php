<?php
if (filter_input(INPUT_POST, 'invitesendmail', FILTER_SANITIZE_NUMBER_INT) == "1") {
    $inviteemail = filter_input(INPUT_POST, 'inviteemail', FILTER_SANITIZE_EMAIL);
    $invitename = filter_input(INPUT_POST, 'invitename', FILTER_SANITIZE_STRING);
    $invitetext = filter_input(INPUT_POST, 'invitetext', FILTER_SANITIZE_STRING);
    if (filter_input(INPUT_POST, 'inviteemail', FILTER_SANITIZE_EMAIL)) {
        $message = "
        <html><head></head><body>
        Hello $invitename<br /><br />
        We would like to invite you to join your classmates and faculty on the only Alumni site put up and maintained by your Saint Francis Community Foundation.<br /><br />
        In it you will be able to keep in contact with your classmates and faculty through the year specific news feed which anyone from your year can add to.  You will also be able to upload pictures and view pictures from any year.  Also you can look up any year and see the graduates with pictures and descriptive paragraphs. And you can send and receive private messages from any classmate or faculty from any year.<br /><br />
        So come and join us on the St Francis Alumni site.<br /><br />
        $invitetext<br /><br />
        <a href='index.php'>Click here to be taken to index.php</a>
        <br /><br />See you there,<br />
        $username<br />
        and<br />
        The St Francis Community Foundation<br /><br />
        </body></html>";
        // In case any of our lines are larger than 70 characters, we should use wordwrap()
        $message = wordwrap($message, 70);
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: Saint Francis Alumni <support@stfrancisalumni.org>' . "\r\n";
        // Send
        mail($inviteemail, "$username invites you to join stfrancisalumni.org", $message, $headers);
        echo "Your invitation to $invitename was sent successfully sent.";
    } else {
        echo "The email address you entered came up as invalid, please check the address and try again.";
    }
}

echo <<<_END
<div style='text-align:center; font-size:2em;'>Invite your classmates to join</div>
<div style="margin:40px;">
    <form method='post' action='index.php?page=invite'>
        Enter your classmate's email address:<br />
        <input type="text" name="inviteemail" size="60" /><br /><br />
        Hello (their name)<input type="text" name="invitename" size="40" /><br /><br />
        We would like to invite you to join your classmates and faculty on the only Alumni site put up and maintained by your Saint Francis Community Foundation.<br /><br />
        In it you will be able to keep in contact with your classmates and faculty through the year specific news feed which anyone from your year can add to.  You will also be able to upload pictures and view pictures from any year.  Also you can look up any year and see the graduates with pictures and descriptive paragraphs. And you can send and receive private messages from any classmate or faculty from any year.<br /><br />
        So come and join us on the St Francis Alumni site.<br /><br />
        <textarea cols="60" rows="5" name="invitetext">$invitetext</textarea><br /><br />
        See you there,<br />
        $username<br />
        and<br />
        The St Francis Community Foundation<br /><br />
        <input type="hidden" name="invitesendmail" value="1" /><input type="submit" value=" -Send Email- " style='border-radius:8px; -moz-border-radius:8px;' />
    </form>
</div>
_END;
?>