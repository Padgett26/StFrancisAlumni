<?php

if (filter_input(INPUT_POST, 'uploadid', FILTER_SANITIZE_NUMBER_INT)) {
    $uploadid = filter_input(INPUT_POST, 'uploadid', FILTER_SANITIZE_NUMBER_INT);
    $reporttext = filter_input(INPUT_POST, 'reporttext', FILTER_SANITIZE_STRING);
    $reptext = filter_input(INPUT_POST, 'reptext', FILTER_SANITIZE_STRING);
    $uptext = "Reported text ->" . "$reptext" . "<- Reported text -- From reporter ->" . "$reporttext";
    $stmt = $db_conn->prepare("UPDATE reports SET text=? WHERE id=?");
    $stmt->execute(array($uptext,$uploadid));
} else {
    $repuserid = filter_input(INPUT_GET, 'repuserid', FILTER_SANITIZE_NUMBER_INT);
    $reptext = filter_input(INPUT_GET, 'reptext', FILTER_SANITIZE_STRING);
    $repbbid = filter_input(INPUT_GET, 'repbbid', FILTER_SANITIZE_NUMBER_INT);
    $reppicid = filter_input(INPUT_GET, 'reppicid', FILTER_SANITIZE_NUMBER_INT);
    $repmessid = filter_input(INPUT_GET, 'repmessid', FILTER_SANITIZE_NUMBER_INT);

    $stmt = $db_conn->prepare("SELECT id FROM reports ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $reprow = $stmt->fetch();
    $repid = $reprow[0];
    $repid = $repid +1;

    $substmt = $db_conn->prepare("SELECT year FROM users WHERE id=?");
    $substmt->execute(array($repuserid));
    $reprow2 = $substmt->fetch();
    $repyear = $reprow2[0];

    $subsubstmt = $db_conn->prepare("INSERT INTO reports VALUES" . "(?, ?, ?, ?, ?, ?, '0', ?)");
    $subsubstmt->execute(array($repid,$repuserid,$reptext,$repbbid,$reppicid,$repmessid,$repyear));
    echo "<form method='post' action='index.php?page=report'>Please explain why you are reporting this person:<br /><textarea cols='80' rows='10' maxlength='1000' name='reporttext'></textarea><br />";
    echo "<input type='hidden' name='uploadid' value='$repid' /><input type='hidden' name='reptext' value='$reptext' /><input type='submit' value=' -Send Report- ' style='border-radius:8px; -moz-border-radius:8px;' /></form>";
}
?>
