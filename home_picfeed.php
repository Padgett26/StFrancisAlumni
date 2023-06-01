<?php
echo "<div style='display:block; float:right; width:200px; margin:30px 0px 0px 30px;'><table cellpadding='10px' cellspacing='0px' width='100%' style='border:2px solid #000000; border-radius:25px; -moz-border-radius:25px;'><tr><td style='text-align:center; font-size:1.em;'>Recently added pictures</td></tr>";
$stmt = $db_conn->prepare("SELECT * FROM pictures ORDER BY created DESC LIMIT 6");
$stmt->execute();
while ($rowpics = $stmt->fetch()) {
    $idpics = $rowpics[0];
    $yearpics = $rowpics[1];
    $captionpics = $rowpics[2];
    $fromuserpics = $rowpics[3];
    $createdpics = $rowpics[4];
    $substmt = $db_conn->prepare("SELECT firstname, miname, lastname, COUNT(*) FROM users WHERE id=?");
    $substmt->execute(array($fromuserpics));
    $subrowpics = $substmt->fetch();
    $countpics = $subrowpics[3];
    if ($countpics == "1") {
    $namepics = $subrowpics[0];
    if ($subrowpics[1])
        $namepics .= " " . $subrowpics[1];
    $namepics .= " " . $subrowpics[2];
    } else {
        $namepics = "System Admin";
    }
    echo "<tr><td style='border-top:2px solid #000000; vertcal-align:top;'><div style='margin:10px;text-align:center;'>$yearpics<br /><a href='index.php?page=pictures&selectyear=$yearpics'><img src='pics/$fromuserpics/$createdpics.jpg' alt='' style='max-width:175px;' /></a><div style='text-align:center;font-size:.75em'>";
    printf("%.35s", $captionpics);
    echo "<br />by $namepics</div>";
    echo "</td></tr>";
}
echo "</table></div>";
?>
