<?php
$stmt = $db_conn->prepare("SELECT userid FROM usersettings");
$stmt->execute();
while ($rowclean = $stmt->fetch()) {
    $useridclean = $rowclean[0];
    $substmt = $db_conn->prepare("SELECT count(*) FROM users WHERE id=?");
    $substmt->execute(array($useridclean));
    $subrowclean = $substmt->fetch();
    $existsclean = $subrowclean[0];
    if ($existsclean == 0) {
        $subsubstmt = $db_conn->prepare("DELETE FROM usersettings WHERE userid=?");
        $subsubstmt->execute(array($useridclean));
    }
}
echo "The usersettings table has been cleaned up.";
?>
