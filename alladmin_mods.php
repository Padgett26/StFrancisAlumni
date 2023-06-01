<?php
if (filter_input(INPUT_POST, 'modall', FILTER_SANITIZE_STRING)) {
    $modall = filter_input(INPUT_POST, 'modall', FILTER_SANITIZE_STRING);
    $emailmod = filter_input(INPUT_POST, 'emailmod', FILTER_SANITIZE_NUMBER_INT);
    if ($modall == "all")
        $tomod = "all";
    else
        $tomod = "$emailmod";
    echo "Compose your email:<br />
    <form method='post' action='index.php?page=alladmin'>
    <textarea name='modemail' cols='60' rows='10'></textarea><br />
    <input type='hidden' name='tomod' value='$tomod' />
    <input type='submit' value=' -Send Email- ' style='border-radius:8px; -moz-border-radius:8px;' />
    </form><br /><br />";
}

if (filter_input(INPUT_POST, 'modemail', FILTER_SANITIZE_STRING)) {
    $modemail = nl2br(filter_input(INPUT_POST, 'modemail', FILTER_SANITIZE_STRING));
    $tomod = filter_input(INPUT_POST, 'tomod', FILTER_SANITIZE_STRING);
    if ($tomod == "all") {
        $stmt = $db_conn->prepare("SELECT email FROM users WHERE userlvl='2'");
        $stmt->execute();
    } else {
        $stmt = $db_conn->prepare("SELECT email FROM users WHERE id=?");
        $stmt->execute(array($tomod));
    }
    while ($rowmod = $stmt->fetch()) {
        $emailmod = $rowmod[0];
        $message = "
        <html><head></head><body>
        $modemail
        </body></html>";
        // In case any of our lines are larger than 70 characters, we should use wordwrap()
        $message = wordwrap($message, 70);
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: Saint Francis Alumni <support@stfrancisalumni.org>' . "\r\n";
        // Send
        mail($emailmod, 'A message from the St Francis Alumni website Administration', $message, $headers);
    }
    echo "Message sent...<br />";
}

$sql = "SELECT id, firstname, miname, lastname, year FROM users WHERE userlvl='2' ORDER BY year";
echo "<form method='post' action='index.php?page=alladmin&choice=mods'><input type='radio' name='modall' value='all' id='all' rel='none' checked='checked' /><label for='all'>Email all moderators</label><br /><input type='radio' name='modall' value='one' id='one' rel='emailone' /><label for='one'>Email an individual moderator</label><br />";
foreach ($db_conn->query($sql) as $row) {
    $id = $row['id'];
    $first = $row['firstname'];
    $mi = $row['miname'];
    $last = $row['lastname'];
    $name = $first;
    if ($mi)
        $name .= " " . $mi;
    $name .= " " . $last;
    $year = $row['year'];
    echo "<table cellpadding='10px' cellspacing='0px' width='100%' style='margin-top:10px; border:2px solid #000000; border-radius:10px; -moz-border-radius:10px;'><tr><td style='border-right:2px solid #000000; vertcal-align:top; width:50%;'>$year - $name</td><td style='width:50%;'><div rel='emailone'><input type='radio' name='emailmod' value='$id' id='$id' /><label for='$id'>Email this moderator</label></div></td></tr></table>";
}
echo "<br /><input type='submit' value=' -Write your email- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />";
?>
