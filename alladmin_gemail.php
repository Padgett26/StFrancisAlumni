<?php
if (filter_input(INPUT_POST, 'sendgemail', FILTER_SANITIZE_NUMBER_INT)) {
    $gemailmsg = nl2br(filter_input(INPUT_POST, 'gemail', FILTER_SANITIZE_STRING));
    $stmt = $db_conn->prepare("SELECT t1.email, t1.firstname, t1.miname, t1.lastname FROM users AS t1 INNER JOIN usersettings AS t2 ON t2.userid=t1.id WHERE t2.notassoc='1'");
    $stmt->execute();
    while ($rownot = $stmt->fetch()) {
        $emailnot = $rownot[0];
        $firstnamenot = $rownot[1];
        $minamenot = $rownot[2];
        $lastnamenot = $rownot[3];
        $gemailname = $firstnamenot;
        if ($miname) {
            $gemailname .= " " . $minamenot;
        }
        $gemailname .= " " . $lastnamenot;
        $message = "
        <html><head></head><body>
        To $gemailname:<br />$gemailmsg<br /><br />Thank you,<br />St Francis Kansas Alumni Website Administration<br /><br />
        If you do not want to receive emails from your St Francis Alumni Website, click the following link and change your notification settings on your profile.<br /><br />
        <a href='http://stfrancisalumni.org/'>http://stfrancisalumni.org/</a>
        </body></html>";
        // In case any of our lines are larger than 70 characters, we should use wordwrap()
        $message = wordwrap($message, 70);
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: Saint Francis Alumni <support@stfrancisalumni.org>' . "\r\n";
        // Send
        mail($emailnot, 'A message from the St Francis Alumni Website', $message, $headers);
    }
    echo "Message sent...<br />";
}

echo "This will send an email to every user that has email notifications allowed:<br /><br />
    To [user name]:<br />
    <form method='post' action='index.php?page=alladmin&choice=gemail'>
    <textarea cols='80' rows='10' name='gemail'></textarea><br /><br />
    Thank you,<br />
    St Francis Kansas Alumni Website Administration<br /><br />
    <input type='hidden' name='sendgemail' value='1' /><input type='submit' value=' -Send- ' style='border-radius:8px; -moz-border-radius:8px;' />
    </form><br /><br />";
?>
