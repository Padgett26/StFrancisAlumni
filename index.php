<?php
include 'functions.php';
if (filter_input(INPUT_GET, 'logout', FILTER_SANITIZE_STRING)) {
    destroySession();
    $userlvl = "0";
    $username = "";
    $userid = "";
}

$stmt = $db_conn->prepare("SELECT * FROM sitesettings WHERE id=?");
$stmt->execute(array("1"));
$rowsettings = $stmt->fetch();
$colorlight = $rowsettings[1];
$colordark = $rowsettings[2];
$headtext = $rowsettings[3];
$headfontsize = $rowsettings[4];

$barsDate = date("d m");

switch ($barsDate) {
    case "14 02":
        $bar1 = "pink";
        $bar2 = "red";
        $bar3 = "pink";
        break;
    case "04 07":
        $bar1 = "red";
        $bar2 = "blue";
        $bar3 = "red";
        break;
    case "24 12":
        $bar1 = "green";
        $bar2 = "red";
        $bar3 = "green";
        break;
    case "25 12":
        $bar1 = "green";
        $bar2 = "red";
        $bar3 = "green";
        break;
    default:
        $bar1 = $colorlight;
        $bar2 = $colordark;
        $bar3 = $colordark;
}

include "head.php";

echo "<body>";

include "config.php";

echo "$headpic";
echo <<<_END
<div style="border:0px; padding:0px; margin:0px; position:absolute; top:5px; left:5px;"><img src="img/logo.jpg" alt="" /></div>
<div id='slideshow'><img src='img/round.gif' alt='' /></div>
_END;
echo "<div class='title1' style='font-size:$headfontsize" . "em; color:$colorlight;'>$headtext</div>";
echo "<div class='title2' style='font-size:$headfontsize" . "em; color:$colordark;'>$headtext</div>";
echo <<<_END
<div class="lines" style="width:810px; height:5px; background-color:$bar1; top:160px; left:40px; z-index:1; border-radius:5px; -moz-border-radius:5px;"></div>
<div class="lines" style="width:890px; height:5px; background-color:$bar2; top:170px; left:30px; z-index:0; border-radius:5px; -moz-border-radius:5px;"></div>
<div class="lines" style="width:970px; height:5px; background-color:$bar3; top:180px; left:20px; z-index:0; border-radius:5px; -moz-border-radius:5px;"></div>
<div class="lines" style="width:5px; height:660px; background-color:$bar1; top:40px; left:150px; z-index:1; border-radius:5px; -moz-border-radius:5px;"></div>
<div class="lines" style="width:5px; height:730px; background-color:$bar2; top:30px; left:160px; z-index:0; border-radius:5px; -moz-border-radius:5px;"></div>
<div class="lines" style="width:5px; height:800px; background-color:$bar3; top:20px; left:170px; z-index:0; border-radius:5px; -moz-border-radius:5px;"></div>
_END;
echo "<div class='menu'>";
include "menu$userlvl.php";
echo "<br /><a href='https://www.facebook.com/groups/2243894149' target='_blank'><img src='img/fb2.png' alt='Our Facebook page' style='max-width:50px; max-height:50px; margin:-10px 0px 0px 10px;' /></a><br />";
echo "</div>";
echo "<div class='mainpage'>";
include('./' . $page . '.php');
echo "</div>";
?>
<script>
    $(document).ready(function() {
        $('.showtext').hide();
        $('.showtitle').toggle(
        function() {
            $(this).next('.showtext').slideDown();
        },
        function() {
            $(this).next('.showtext').slideUp();
        }
    );
    });
</script>
</body>
</html>
