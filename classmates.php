<?php

echo "<div style='text-align:center; font-size:3em;'>$textyear";
if ($textyear == "Faculty")
    echo "-mates";
else
    echo " Classmates";
echo "</div><br /><br /><br />";
$stmt = $db_conn->prepare("SELECT id, firstname, miname, lastname, maidenname, cityst, descpara, deceased, usercreated, userlvl FROM users WHERE year=? AND approved='1' ORDER BY lastname");
$stmt->execute(array($selectyear));
while ($rowyear = $stmt->fetch()) {
    $idyear = $rowyear['id'];
    $firstnameyear = $rowyear['firstname'];
    $minameyear = $rowyear['miname'];
    $lastnameyear = $rowyear['lastname'];
    $maidennameyear = $rowyear['maidenname'];
    $citystyear = $rowyear['cityst'];
    $descparayear = nl2br($rowyear['descpara']);
    $deceasedyear = $rowyear['deceased'];
    $usercreated = $rowyear['usercreated'];
    $userlvlyear = $rowyear['userlvl'];

    echo "<table cellpadding='0px' cellspacing='0' border='0' width='805px'><tr><td style='width:80px; vertical-align:top;'>";
    if (file_exists("pics/thenpic/$idyear.jpg")) {
       echo "<img src='pics/thenpic/$idyear.jpg' alt='' />";
    } else {
        if ($idyear == $userid) {
            echo "<a href='index.php?page=profile#profilepics'><img src='img/facefillthen.gif' style='z-index:1;' alt='' /><div style='text-align:center; position:relative; top:-80px; left:5px; display:block; padding:5px; width:58px; background-color:#ffffff; border:1px solid #000000; border-radius:5px; -moz-border-radius:5px;'>Upload<br />School<br />Picture</div></a>";
        } else {
            echo "<img src='img/face.jpg' alt='' /><div style='position:relative; top:-60px; left:17px; display:block; padding:5px; width:33px; background-color:#ffffff; border:1px solid #000000; border-radius:5px; -moz-border-radius:5px;'>Then</div>";
        }
    }
    echo "</td><td style='padding:10px;'><div style='text-align:center; font-size:2em; font-weight:bold;'>$firstnameyear";
    if ($minameyear)
        echo " $minameyear";
    echo " $lastnameyear";
    if ($maidennameyear)
        echo " ($maidennameyear)";
    echo "</div>";
    if ($userlvlyear == "2")
        echo "<div style='text-align:center; font-size:1.25em;'>Moderator for $textyear</div>";
    if ($userlvlyear == "3")
        echo "<div style='text-align:center; font-size:1.25em;'>Website Administrator</div>";
    if ($deceasedyear == "1")
        echo "<div style='text-align:center; font-size:1.5em;'>Deceased</div>";
    else
        echo "<div style='text-align:center; font-size:1.5em;'>$citystyear</div>";
    if ($userlvl != "0" && $usercreated == "1" && $deceasedyear == "0") {
        if ($idyear == $userid)
            echo "";
        else
            echo "<div style='text-align:center; font-size:1em;'><a href='index.php?page=messages&to=$idyear'>Click here to send a private message.</a></div>";
    }
    echo "<div style='text-align:justify; font-size:1em; margin-top:20px; margin-bottom:20px;'>$descparayear</div></td>";
    echo "<td style='width:80px; vertical-align:top;'>";
    if (file_exists("pics/nowpic/$idyear.jpg")) {
       echo "<img src='pics/nowpic/$idyear.jpg' alt='' />";
    } else {
        if ($idyear == $userid) {
            echo "<a href='index.php?page=profile#profilepics'><img src='img/face.jpg' style='z-index:1;' alt='' /><div style='text-align:center; position:relative; top:-80px; left:5px; display:block; padding:5px; width:58px; background-color:#ffffff; border:1px solid #000000; border-radius:5px; -moz-border-radius:5px;'>Upload<br />Current<br />Picture</div></a>";
        } else {
            echo "<img src='img/face.jpg' alt='' /><div style='position:relative; top:-60px; left:18px; display:block; padding:5px; width:31px; background-color:#ffffff; border:1px solid #000000; border-radius:5px; -moz-border-radius:5px;'>Now</div>";
        }
    }
    echo "</td></tr></table>";
    echo "<hr width='75%'><br /><br />";
}
?>
