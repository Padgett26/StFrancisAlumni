<?php

if (filter_input(INPUT_POST, 'sendmess', FILTER_SANITIZE_NUMBER_INT)) {
    $messtoid = filter_input(INPUT_POST, 'sendmess', FILTER_SANITIZE_NUMBER_INT);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $created = time() + (2 * 60 * 60); //adjust to centeral time
    $datemsg = date("F j, Y, g:i a", $created);
    $stmt = $db_conn->prepare("INSERT INTO messages VALUES" . "(NULL,?,?,?,?,'0')");
    $stmt->execute(array($messtoid,$created,$userid,$message));
    $stmt = $db_conn->prepare("SELECT t1.email FROM users AS t1 INNER JOIN usersettings AS t2 ON t2.userid=t1.id WHERE t2.notmsg='1' AND t1.id=?");
    $stmt->execute(array($messtoid));
    $rownot = $stmt->fetch();
    $emailnot = $rownot[0];
    if (filter_var($emailnot, FILTER_VALIDATE_EMAIL)) {
        $message = nl2br(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING));
        $emailmessage = "
        <html><head></head><body>
        You received a new private message on the St Francis Alumni site from $username<br /><br />$datemsg<br /><br >$message<br /><br />
        If you do not want to receive an email when someone sends you a private message, click the following link and change your notification settings on your profile.<br /><br />
        <a href='index.php'>index.php</a>
        </body></html>";
        // In case any of our lines are larger than 70 characters, we should use wordwrap()
        $emailmessage = wordwrap($emailmessage, 70);
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: Saint Francis Alumni <support@stfrancisalumni.org>' . "\r\n";
        // Send
        mail($emailnot, 'A new private message for you on the St Francis Alumni site', $emailmessage, $headers);
    }
    echo "Message sent...<br />";
}

if (filter_input(INPUT_GET, 'to', FILTER_SANITIZE_NUMBER_INT)) {
    $messto = filter_input(INPUT_GET, 'to', FILTER_SANITIZE_NUMBER_INT);
    $stmt = $db_conn->prepare("SELECT firstname, miname, lastname FROM users WHERE id=?");
    $stmt->execute(array($messto));
    $rowmess = $stmt->fetch();
    $firstnamemess = $rowmess['firstname'];
    $minamemess = $rowmess['miname'];
    $lastnamemess = $rowmess['lastname'];
    echo "Private message to: $firstnamemess";
    if ($minamemess)
        echo " $minamemess";
    echo " $lastnamemess<br /><form method='post' action='index.php?page=messages'><textarea cols='60' rows='10' name='message' maxlength='500'></textarea><br /><input type='hidden' name='sendmess' value='$messto' /><input type='submit' value=' -Send- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />";
}

if (filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING) == "Delete Selected") {
    $toDelete = array();
    foreach ($_POST as $key => $val) {
        $key = filter_var($key, FILTER_SANITIZE_STRING);
        $var = filter_var($var, FILTER_SANITIZE_NUMBER_INT);
        if ($val == "1" && preg_match("/^checkbox([1-9][0-9]*)$/", $key, $match)) {
            $toDelete[] = $match[1];
        }
    }
    if (count($toDelete) != 0) {
        foreach ($toDelete as $j) {
            $stmt = $db_conn->prepare("DELETE FROM messages WHERE id=?");
            $stmt->execute(array($j));
        }
        echo "Deleted Messages.<br />";
    }
    unset($toDelete);
}

$substmt = $db_conn->prepare("UPDATE messages SET isread='1' WHERE userid=?");
$substmt->execute(array($userid));

$stmt = $db_conn->query("SELECT COUNT(id) FROM messages WHERE userid='$userid'");
$nummess1 = $stmt->fetchColumn();
$stmt->closeCursor();
if ($nummess1 >= 1) {
    echo "<form method='post' action='index.php?page=messages'><input type='submit' value='Delete Selected' name='act' style='border-radius:8px; -moz-border-radius:8px;' /><br /><br />";
    $stmt = $db_conn->prepare("SELECT * FROM messages WHERE userid=? ORDER BY created");
    $stmt->execute(array($userid));
    while ($rowmess1 = $stmt->fetch()) {
        $idmess1 = $rowmess1[0];
        $createdmess1 = $rowmess1[2];
        $sentmess1 = date("F j, Y, g:i a", $createdmess1);
        $fromidmess1 = $rowmess1[3];
        $messagemess1 = nl2br($rowmess1[4]);
        $isreadmess1 = $rowmess1[5];
        $substmt = $db_conn->prepare("SELECT firstname, miname, lastname FROM users WHERE id=?");
        $substmt->execute(array($fromidmess1));
        $subrowmess1 = $substmt->fetch();
        $fromfirstname = $subrowmess1['firstname'];
        $fromminame = $subrowmess1['miname'];
        $fromlastname = $subrowmess1['lastname'];
        if ($isreadmess1 == "0")
            echo "<span style='font-weight:bold font-size:1.5em;'>**NEW**</span><br />";
        echo "From: $fromfirstname";
        if ($fromminame)
            echo " $fromminame";
        echo " $fromlastname<br />Sent: $sentmess1<br /><br /><div style='text-align:justify;'>$messagemess1</div><br />";
        echo "<a href='index.php?page=messages&to=$fromidmess1'>-Reply to this message-</a><br /><br />";
        echo "Delete this message? <input type='checkbox' name='checkbox$idmess1' value='1' /><br /><br />";
        echo "<a href='index.php?page=report&repuserid=$fromidmess1&repmessid=$idmess1&reptext=$rowmess1[4]'>Click here to report this message.</a><br /><hr width='75%'><br />";
    }
    echo "<input type='submit' value='Delete Selected' name='act' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />";
}
else echo "There are no messages in your inbox.";
?>
