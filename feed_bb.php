<?php
if (isset ( $_SESSION ['user'] ))
	$userlvl = $_SESSION ['userlvl'];
else
	$userlvl = "0";

$stmt = $db_conn->prepare ( "SELECT * FROM sitesettings WHERE id='1'" );
$stmt->execute ();
$rowsettings = $stmt->fetch ();
$colorlight = $rowsettings ['schoolColorLight'];
$colordark = $rowsettings ['schoolColorDark'];
?>

<div style="display:block; background-color:#ffffff; width:264px; height:50px; border:1px solid <?php

echo $colordark?>; border-bottom-style:none; border-radius:25px 25px 0px 0px; -moz-border-radius:25px 25px 0px 0px; text-align:center; font-size:1.5em; font-weight:bold; color:<?php

echo $colordark?>; float:left; margin:0px;"><div style="margin-top:10px;">Bulletin Board</div></div>
<a href="#" onclick="showFeed('news')" style="text-decoration:none; color:<?php

echo $colordark?>;"><div style="display:block; background-color:<?php

echo $colorlight?>; width:264px; height:49px; border:1px solid <?php

echo $colordark?>; border-radius:25px 25px 0px 0px; -moz-border-radius:25px 25px 0px 0px; text-align:center; font-size:1.5em; font-weight:bold; color:<?php

echo $colordark?>; float:left; margin:0px;"><div style="margin-top:10px;">News Feed</div></div></a>
<div style="display:block; position:relative; top:41px; width:510px; border:1px solid <?php

echo $colordark?>; border-top-style:none; border-radius:0px 0px 25px 25px; -moz-border-radius:0px 0px 25px 25px; padding:10px;">
<?php
$stmt = $db_conn->prepare ( "SELECT * FROM bboard WHERE globalbb='1' ORDER BY created DESC LIMIT 5" );
$stmt->execute ();
while ( $rowbboard = $stmt->fetch () ) {
	$idbboard = $rowbboard [0];
	$yearbboard = $rowbboard [1];
	$createdbboard = $rowbboard [2];
	$datebboard = date ( "M j, Y, g:i a", $createdbboard );
	$useridbboard = $rowbboard [3];
	$messagebboard = nl2br ( $rowbboard [4] );
	$substmt = $db_conn->prepare ( "SELECT firstname, miname, lastname FROM users WHERE id=?" );
	$substmt->execute ( array (
			$useridbboard
	) );
	$subrowbboard = $substmt->fetch ();
	$namebboard = $subrowbboard [0];
	if ($subrowbboard [1])
		$namebboard .= " " . $subrowbboard [1];
	$namebboard .= " " . $subrowbboard [2];
	echo "<table cellpadding='10px' cellspacing='0px' width='100%' style='border:2px solid $colordark; border-radius:25px; -moz-border-radius:25px; margin-top:10px;'><tr><td style='border-right:2px solid $colordark; vertcal-align:top; width:25%;'>";
	echo "<div style='margin-top:0px;'>$namebboard<br />$datebboard<br />";
	if ($useridbboard == $userid || $userlvl == "3") {
		echo "<a href='index.php?page=bboard&delete=$idbboard&item=$useridbboard'>Delete post</a><br />";
	} else {
		if ($useridbboard != "1" && $userlvl != "0")
			echo "<a href='index.php?page=report&repuserid=$useridbboard&repbbid=$idbboard&reptext=$rowbboard[4]'>Report post</a><br />";
	}

	echo "</td><td style='vertical-align:top; width:75%;'>";
	printf ( "%.150s <a href='index.php?page=globalbb'>...See More</a>", $messagebboard );
	echo "</td></tr></table>";
}
?>
</div>