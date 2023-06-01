<div style="position:absolute; top:640px; left:15px;"><img src="img/home1.jpg" style="opacity:0.2;filter:alpha(opacity=20); z-index:-1;" /></div>
<div style="position:absolute; top:1000px; right:220px;"><img src="img/home2.jpg" style="opacity:0.2;filter:alpha(opacity=20); z-index:-1;" /></div>
<?php
echo "<div style='margin:30px 20px 20px 20px; position:absolute; top:0px; right:0px; z-index:1;'>";
$stmt = $db_conn->prepare("SELECT * FROM home");
$stmt->execute();
$rowhome = $stmt->fetch();
$titlehome = $rowhome['title'];
$texthome = nl2br($rowhome['text']);
echo "<div style='text-align:center; font-size:3em;'>$titlehome</div>";
include "home_picfeed.php";
if ((date("zY")) <= "1722014") {
    echo "<div style='font-weight:bold; font-size:1.25em; margin:30px 0px;'>​The following Articles of Incorporation and By-Laws were voted on and approved at the 2014 Reunion.<br /><br />";
    echo "<a href='pdfs/articlesOfIncorporation2014.pdf' target='_blank'>Articles of Incorporation</a><br /><br />";
    echo "<a href='pdfs/byLaws2014.pdf' target='_blank'>By-laws</a><br /><br />";
}
include "home_bbfeed.php";
echo "<div style='text-align:justify; font-size:1.5em; margin-top:80px;'>$texthome<br /><br /></div></div>";
?>