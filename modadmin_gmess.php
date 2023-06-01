<?php
if (filter_input(INPUT_POST, 'sendmess', FILTER_SANITIZE_NUMBER_INT)) {
    $message = htmlentities(stripslashes($_POST['message']), ENT_QUOTES);
    $stmt = $db_conn->prepare("SELECT id FROM users WHERE year=?");
    $stmt->execute(array($useryear));
    while ($rowgmess = $stmt->fetch()) {
        $messtoid = $rowgmess[0];
        $created = time();
        $substmt = $db_conn->prepare("INSERT INTO messages VALUES" . "(NULL,?,?,?,?,'0')");
        $substmt->execute(array($messtoid,$created,$userid,$message));
    }
    echo "Message was sent to every user...<br />";
}

echo "This will send a message to every $useryear user:<br />
<form method='post' action='index.php?page=modadmin&choice=gmess'>
<textarea cols='50' rowa='10' name='message' maxlength='500'></textarea><br />
<input type='hidden' name='sendmess' value='1' />
<input type='submit' value=' -Send- ' style='border-radius:8px; -moz-border-radius:8px;' />
</form><br /><br />";
?>
