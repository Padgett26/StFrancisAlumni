<?php
if ($_SESSION['user'])
    $userlvl = $_SESSION['userlvl'];
else
    $userlvl = "0";

$stmt = $db_conn->prepare("SELECT * FROM sitesettings WHERE id='1'");
$stmt->execute();
$rowsettings = $stmt->fetch();
$colorlight = $rowsettings['schoolColorLight'];
$colordark = $rowsettings['schoolColorDark'];
?>

<a href="#" onclick="showFeed('bboard')" style="text-decoration:none; color:<?php echo $colordark ?>;"><div style="display:block; background-color:<?php echo $colorlight ?>; width:264px; height:49px; border:1px solid <?php echo $colordark ?>; border-radius:25px 25px 0px 0px; -moz-border-radius:25px 25px 0px 0px; text-align:center; font-size:1.5em; font-weight:bold; color:<?php echo $colordark ?>; float:left; margin:0px;"><div style="margin-top:10px;">Global Bulletin Board</div></div></a>
<div style="display:block; background-color:#ffffff; width:264px; height:50px; border:1px solid <?php echo $colordark ?>; border-bottom-style:none; border-radius:25px 25px 0px 0px; -moz-border-radius:25px 25px 0px 0px; text-align:center; font-size:1.5em; font-weight:bold; color:<?php echo $colordark ?>; float:left; margin:0px;"><div style="margin-top:10px;">News Feed</div></div>
<div style="display:block; position:relative; top:41px; width:510px; border:1px solid <?php echo $colordark ?>; border-top-style:none; border-radius:0px 0px 25px 25px; -moz-border-radius:0px 0px 25px 25px; padding:10px;">
<?php
$stmt = $db_conn->prepare("SELECT * FROM newsfeed ORDER BY created DESC LIMIT 5");
$stmt->execute();
while ($row = $stmt->fetch()) {
    $idnews = $row[0];
    $titlenews = $row[1];
    $creatednews = $row[3];
    $date = date("M j, Y, g:i a", $creatednews);
    echo "<table cellpadding='10px' cellspacing='0px' width='100%' style='border:2px solid $colordark; border-radius:25px; -moz-border-radius:25px;";
    if ($l != "0")
        echo "margin-top:10px;";
    echo "'><tr><td style='border-right:2px solid $colordark; vertcal-align:top; width:25%;'><div style='margin-top:0px;'>$date<br />";
    if ($userlvl == "3") {
        echo "<a href='index.php?page=newsfeed&delete=$idnews'>Delete post</a><br />";
    }

    echo "</td><td style='vertical-align:top; width:75%;'><div style='text-align:center;'><a href='index.php?page=newsfeed' style='font-size:1.5; font-weight:bold;'>$titlenews</a></div>";
    echo "</td></tr></table>";
}
?>
</div>