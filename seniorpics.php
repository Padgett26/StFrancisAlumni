<?php
if (filter_input(INPUT_POST, 'mkprofile', FILTER_SANITIZE_NUMBER_INT)) {
    $picid = filter_input(INPUT_POST, 'mkprofile', FILTER_SANITIZE_NUMBER_INT);
    $file = "pics/seniorpics/$useryear/$picid.jpg";
    $newfile = "pics/thenpic/$userid.jpg";

    if (!copy($file, $newfile)) {
        echo "failed to copy $file...\n";
    }
}
echo "<div style='text-align:center; font-size:3em;'>$textyear Senior Pictures</div>";
$stmt = $db_conn->prepare("SELECT * FROM seniorpics WHERE year=? ORDER BY caption");
$stmt->execute(array($selectyear));
while ($rowsenior = $stmt->fetch()) {
    $idsenior = $rowsenior[0];
    $captionsenior = $rowsenior[2];
    if ($selectyear == $useryear)
        echo "<form method='post' action='index.php?page=seniorpics'><div style='border:1px solid #000000; padding:5px; margin:5px; float:left;'><center><img src='pics/seniorpics/$selectyear/$idsenior.jpg' alt='' /></center><div style='text-align:center;'>$captionsenior<br /><input type='checkbox' name='mkprofile' value='$idsenior' id='mkprofile' /><label for='mkprofile'>Make this my profile picture.</label><br /><input type='submit' value='This is me' /></div></div></form>";
    else
        echo "<div style='border:1px solid #000000; padding:5px; margin:5px; float:left;'><center><img src='pics/seniorpics/$selectyear/$idsenior.jpg' alt='' /></center><div style='text-align:center;'>$captionsenior</div></div>";
}
?>