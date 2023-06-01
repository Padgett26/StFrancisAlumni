<?php

if (filter_input(INPUT_GET, 'limit', FILTER_SANITIZE_NUMBER_INT)) {
    $limitbboard = filter_input(INPUT_GET, 'limit', FILTER_SANITIZE_NUMBER_INT);
}
else
    $limitbboard = "10";

if (filter_input(INPUT_POST, 'newmessage', FILTER_SANITIZE_STRING)) {
    $newmessage = filter_input(INPUT_POST, 'newmessage', FILTER_SANITIZE_STRING);
    $bblink = filter_input(INPUT_POST, 'bblink', FILTER_SANITIZE_URL);
    $gbb = (filter_input(INPUT_POST, 'gbb', FILTER_SANITIZE_NUMBER_INT) == '1')? '1' : '0';
    $lbb = (filter_input(INPUT_POST, 'lbb', FILTER_SANITIZE_NUMBER_INT) == '1')? '1' : '0';
    $created = time() + (2 * 60 * 60); //adjust to centeral time
    $datebb = date("M j, Y, g:i a", $created);
    $bbyear = ($userlvl == "3")? $selectyear: $useryear;
    $stmt = $db_conn->prepare("INSERT INTO bboard VALUES" . "(NULL,?,?,?,?,?,?,?,'0')");
    $stmt->execute(array($bbyear,$created,$userid,$newmessage,$gbb,$lbb,$bblink));
    if ($lbb == "1") {
        $substmt = $db_conn->prepare("SELECT t1.email FROM users AS t1 INNER JOIN usersettings AS t2 ON t2.userid=t1.id WHERE t2.notbb='1' AND t1.year=?");
        $substmt->execute(array($bbyear));
        while ($rownot = $substmt->fetch()) {
            $emailnot = $rownot[0];
            if (filter_var($emailnot, FILTER_VALIDATE_EMAIL)) {
                $newmessage = nl2br($newmessage);
                $message = "
                <html><head></head><body>
                There has been a new posting on the St Francis Alumni bulletin board:<br /><br />$datebb<br /><br >$newmessage<br /><br />
                If you do not want to receive an email when someone submits a new post on the bulletin board, click the following link and change your notification settings on your profile.<br /><br />
                <a href='index.php'>index.php</a>
                </body></html>";
                // In case any of our lines are larger than 70 characters, we should use wordwrap()
                $message = wordwrap($message, 70);
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: Saint Francis Alumni <support@stfrancisalumni.org>' . "\r\n";
                // Send
                mail($emailnot, 'A new bulletin board post on the St Francis Alumni site', $message, $headers);
            }
        }
    }
    echo "Message uploaded...";
}

if (filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_NUMBER_INT)) {
    $delete = filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_NUMBER_INT);
    $item = filter_input(INPUT_GET, 'item', FILTER_SANITIZE_NUMBER_INT);
    if ($item == $userid || $userlvl == "3" || $userlvl == "2") {
        echo "<div style='color:red; font-size:1.5em; float:left;'>Are you sure you want to delete this post?</div><div style='float:left; margin-left:10px;'><form method='post' action='index.php?page=globalbb'><input type='submit' value=' -No- ' /></form></div><div style='float:left; margin-left:10px;'><form method='post' action='index.php?page=globalbb'><input type='hidden' name='delete2' value='$delete' /><input type='hidden' name='item' value='$item' /><input type='submit' value=' -Delete- ' /></form></div><br /><br />";
    }
    else
        echo "Nothing changed";
}

if (filter_input(INPUT_POST, 'delete2', FILTER_SANITIZE_NUMBER_INT)) {
    $delete = filter_input(INPUT_POST, 'delete2', FILTER_SANITIZE_NUMBER_INT);
    $item = filter_input(INPUT_POST, 'item', FILTER_SANITIZE_NUMBER_INT);
    if ($item == $userid || $userlvl == "3" || $userlvl == "2") {
        $stmt = $db_conn->prepare("DELETE FROM bboard WHERE id=?");
        $stmt->execute(array($delete));
    }
    else
        echo "Nothing changed";
}
if ($userlvl != "0") {
    if ($userlvl == "3") {
        $bbyear = $selectyear;
    } else {
        $bbyear = $useryear;
    }
    echo "Post a new message:<br />
    <form method='post' action='index.php?page=globalbb'>
    <textarea name='newmessage' cols='80' rows='6' maxlength='975'></textarea><br />
    Add a link to an external site: <input type='text' name='bblink' value='http://' /><br />
    Post on:<br />
    <input type='checkbox' name='gbb' value='1' id='gbb' /><label for='gbb'>Global Bulletin Board</label><br />
    <input type='checkbox' name='lbb' value='1' id='lbb' /><label for='lbb'>$bbyear&#39;s Bulletin Board</label><br />
    <input type='submit' value=' -Submit- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />";
}

echo "<div style='margin-right:10px;'>Display: ";
if ($limitbboard == '10')
    echo "10 | ";
else
    echo "<a href='index.php?page=globalbb&limit=10'>10</a> | ";
if ($limitbboard == '25')
    echo "25 | ";
else
    echo "<a href='index.php?page=globalbb&limit=25'>25</a> | ";
if ($limitbboard == '50')
    echo "50 | ";
else
    echo "<a href='index.php?page=globalbb&limit=50'>50</a> | ";
if ($limitbboard == '100')
    echo "100";
else
    echo "<a href='index.php?page=globalbb&limit=100'>100</a>";
echo "</div>";

$stmt = $db_conn->prepare("SELECT * FROM bboard WHERE globalbb='1' ORDER BY created DESC LIMIT $limitbboard");
$stmt->execute();
while ($rowbboard = $stmt->fetch()) {
    $idbboard = $rowbboard['id'];
    $yearbboard = $rowbboard['year'];
    $createdbboard = $rowbboard['created'];
    $datebboard = date("M j, Y, g:i a", $createdbboard);
    $useridbboard = $rowbboard['userid'];
    $messagebboard = nl2br($rowbboard['message']);
    $bblinkbboard = $rowbboard['bblink'];
    $substmt = $db_conn->prepare("SELECT firstname, miname, lastname FROM users WHERE id=$useridbboard");
    $substmt->execute();
    $subrowbboard = $substmt->fetch();
    $namebboard = $subrowbboard['firstname'];
    if ($subrowbboard['miname'])
        $namebboard .= " " . $subrowbboard['miname'];
    $namebboard .= " " . $subrowbboard['lastname'];
    echo <<<_END
   <table cellpadding="10px" cellspacing="0px" width="100%" style="margin-top:10px; border:2px solid #000000; border-radius:25px; -moz-border-radius:25px;">
       <tr>
       <td style=" border-right:2px solid #000000; vertcal-align:top; width:25%;">
_END;
           
    echo <<<_END
           <div style="margin-top:0px;">$namebboard<br />$datebboard<br />
_END;
    if ($userlvl != "0") {
        if ($useridbboard == $userid) {
            echo "<a href='index.php?page=globalbb&delete=$idbboard&item=$useridbboard'>Delete post</a><br />";
        } elseif ($userlvl == "3") {
            echo "<a href='index.php?page=globalbb&delete=$idbboard&item=$useridbboard'>Delete post</a><br />";
            if ($useridbboard != "1")
                echo "<a href='index.php?page=messages&to=$useridbboard'>Send private message</a><br />";
        } else {
            if ($useridbboard != "1")
                echo "<a href='index.php?page=messages&to=$useridbboard'>Send private message</a><br /><a href='index.php?page=report&repuserid=$useridbboard&repbbid=$idbboard&reptext=$rowbboard[4]'>Report post</a><br />";
        }
    }
    echo "</td><td style='vertical-align:top; width:75%;'>";
    echo "$messagebboard";
    if ($bblinkbboard && $bblinkbboard != "http://")
        echo "<br /><div style='text-align:center; text-decoration:underline;'><a href='$bblinkbboard'>$bblinkbboard</a></div>";
    echo "</td></tr></table>";
}
echo "<br /><br />";
?>
