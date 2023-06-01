<?php
if (filter_input(INPUT_POST, 'homepageup', FILTER_SANITIZE_NUMBER_INT)) {
    $idhomeup = filter_input(INPUT_POST, 'homepageup', FILTER_SANITIZE_NUMBER_INT);
    $titlehomeup = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $texthomeup = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);
    if ($idhomeup == "") {
        $stmt = $db_conn->prepare("INSERT INTO home VALUES" . "(NULL, ?, ?)");
        $stmt->execute(array($titlehomeup,$texthomeup));
        echo "The page has been uploaded...";
    } else {
        $stmt = $db_conn->prepare("UPDATE home SET title=?, text=? WHERE id=?");
        $stmt->execute(array($titlehomeup,$texthomeup,$idhomeup));
        echo "The page has been updated...";
    }
}

$stmt = $db_conn->prepare("SELECT * FROM home");
$stmt->execute();
$row = $stmt->fetch();
$idhome = $row[0];
$titlehome = $row[1];
$texthome = $row[2];
echo "<form method='post' action='index.php?page=alladmin&choice=homepage'>
Title:<br />
<input type='text' name='title' value='$titlehome' size='40' maxlength='40' /><br /><br />
Text:<br />
<textarea name='text' cols='60' rows='10' maxlength='2000'>$texthome</textarea><br /><br />
<input type='hidden' name='homepageup' value='$idhome' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' />
</form><br /><br />";
?>
