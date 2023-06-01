<?php
if (filter_input(INPUT_POST, 'sendmess', FILTER_SANITIZE_NUMBER_INT)) {
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $stmt = $db_conn->prepare("SELECT id FROM users");
    $stmt->execute();
    while ($rowgmess = $stmt->fetch()) {
        $messtoid = $rowgmess['id'];
        $created = time();
        $substmt = $db_conn->prepare("INSERT INTO messages VALUES" . "(NULL,?,?,?,?,'0')");
        $substmt->execute(array($messtoid,$created,$userid,$message));
    }
    echo "Message was sent to every user...<br />";
}

echo "This will send a message to every user:<br />
    <form method='post' action='index.php?page=alladmin&choice=gmess'>
    <textarea cols='50' rowa='10' name='message' maxlength='500'></textarea><br />
    <input type='hidden' name='sendmess' value='1' /><input type='submit' value=' -Send- ' style='border-radius:8px; -moz-border-radius:8px;' />
    </form><br /><br />";
?>
