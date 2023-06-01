<?php
if (filter_input ( INPUT_POST, 'addlink', FILTER_SANITIZE_STRING )) {
	$addlink = filter_input ( INPUT_POST, 'addlink', FILTER_SANITIZE_STRING );
	$linktitle = filter_input ( INPUT_POST, 'linktitle', FILTER_SANITIZE_STRING );
	$linktext = filter_input ( INPUT_POST, 'linktext', FILTER_SANITIZE_STRING );
	$linkaddress = filter_input ( INPUT_POST, 'linkaddress', FILTER_SANITIZE_URL );
	$linkemail = filter_input ( INPUT_POST, 'linkemail', FILTER_SANITIZE_EMAIL );
	$linkphone = filter_input ( INPUT_POST, 'linkphone', FILTER_SANITIZE_STRING );
	$linkaddr1 = filter_input ( INPUT_POST, 'linkaddr1', FILTER_SANITIZE_STRING );
	$linkaddr2 = filter_input ( INPUT_POST, 'linkaddr2', FILTER_SANITIZE_STRING );
	$linkaddr3 = filter_input ( INPUT_POST, 'linkaddr3', FILTER_SANITIZE_STRING );
	if ($addlink == "new") {
		$stmt = $db_conn->prepare ( "INSERT INTO links VALUES" . "(NULL, ?, ?, ?, ?, ?, ?, ?, ?)" );
		$stmt->execute ( array (
				$linktitle,
				$linktext,
				$linkaddress,
				$linkemail,
				$linkphone,
				$linkaddr1,
				$linkaddr2,
				$linkaddr3
		) );
		echo "Link Added...<br />";
	} else {
		$stmt = $db_conn->prepare ( "UPDATE links SET title=?,text=?,website=?,email=?,phone=?,address1=?,address2=?,address3=? WHERE id=?" );
		if ($linktitle)
			$stmt->execute ( array (
					$linktitle,
					$linktext,
					$linkaddress,
					$linkemail,
					$linkphone,
					$linkaddr1,
					$linkaddr2,
					$linkaddr3,
					$addlink
			) );
	}
}

if (filter_input ( INPUT_GET, 'editlink', FILTER_SANITIZE_STRING )) {
	$editlink = filter_input ( INPUT_GET, 'editlink', FILTER_SANITIZE_STRING );
} else {
	$editlink = "x";
}

if ($editlink == "new") {
	echo "Add a new link...<br /><form method='post' action='index.php?page=links'>";
	echo "Title...<br /><input type='text' name='linktitle' value='' size='60' maxlength='60' /><br />";
	echo "Description...<br /><textarea name='linktext' cols='60' rows='6' maxlength='500' /></textarea><br />";
	echo "Website address...<br /><input type='text' name='linkaddress' value='http://' size='60' maxlength='60' /><br />";
	echo "Email Address...<br /><input type='text' name='linkemail' value='' size='60' maxlength='60' /><br />";
	echo "Phone Number...<br /><input type='text' name='linkphone' value='' size='60' maxlength='13' /><br />";
	echo "Physical Address Line 1...<br /><input type='text' name='linkaddr1' value='' size='60' maxlength='60' /><br />";
	echo "Physical Address Line 2...<br /><input type='text' name='linkaddr2' value='' size='60' maxlength='60' /><br />";
	echo "Physical Address Line 3...<br /><input type='text' name='linkaddr3' value='' size='60' maxlength='60' /><br />";
	echo "<input type='hidden' name='addlink' value='new' /><input type='submit' value=' -Add Link- ' style='border-radius:8px; -moz-border-radius:8px;' /></form>";
} elseif ($userlvl == "3")
	echo "<div style='text-align:center; font-size:1.5em;'><a href='index.php?page=links&editlink=new'>...Add a new link...</a></div><br />";

$stmt = $db_conn->prepare ( "SELECT * FROM links" );
$stmt->execute ();
while ( $rowlink = $stmt->fetch () ) {
	$idlink = $rowlink [0];
	$titlelink = $rowlink [1];
	$text = $rowlink [2];
	$textlink = nl2br ( $text );
	$weblink = $rowlink [3];
	$emaillink = $rowlink [4];
	$phonelink = $rowlink [5];
	$addr1link = $rowlink [6];
	$addr2link = $rowlink [7];
	$addr3link = $rowlink [8];
	if ($editlink == $idlink) {
		echo "<a name='idlink' style='text-decoration:none;'>Edit this link...</a><br /><form method='post' action='index.php?page=links'>";
		echo "Title...<br /><input type='text' name='linktitle' value='$titlelink' size='60' maxlength='60' /><br />";
		echo "Description...<br /><textarea name='linktext' cols='60' rows='6' maxlength='500' />$text</textarea><br />";
		echo "Website address...<br /><input type='text' name='linkaddress' value='$weblink' size='60' maxlength='60' /><br />";
		echo "Email Address...<br /><input type='text' name='linkemail' value='$emaillink' size='60' maxlength='60' /><br />";
		echo "Phone Number...<br /><input type='text' name='linkphone' value='$phonelink' size='60' maxlength='13' /><br />";
		echo "Physical Address Line 1...<br /><input type='text' name='linkaddr1' value='$addr1link' size='60' maxlength='60' /><br />";
		echo "Physical Address Line 2...<br /><input type='text' name='linkaddr2' value='$addr2link' size='60' maxlength='60' /><br />";
		echo "Physical Address Line 3...<br /><input type='text' name='linkaddr3' value='$addr3link' size='60' maxlength='60' /><br />";
		echo "<input type='hidden' name='addlink' value='$idlink' /><input type='submit' value=' -Edit Link- ' style='border-radius:8px; -moz-border-radius:8px;' /></form>";
		echo "<br /><div style='width:500px; height:5px; background-color:green; margin-left:140px; z-index:0; border-radius:5px; -moz-border-radius:5px;'></div><br /><br />";
	} else {
		if ($titlelink)
			echo "<div style='text-align:center; font-size:1.5em;'>$titlelink</div>";
		if ($weblink)
			echo "<div style='text-align:center; font-size:1em;'><a href='$weblink' target='_blank'>$weblink</a></div>";
		if ($emaillink)
			echo "<div style='text-align:center; font-size:1em;'><a href='mailto:$emaillink'>$emaillink</a></div>";
		if ($phonelink)
			echo "<div style='text-align:center; font-size:1em;'>$phonelink</div>";
		if ($addr1link)
			echo "<div style='text-align:center; font-size:1em;'>$addr1link</div>";
		if ($addr2link)
			echo "<div style='text-align:center; font-size:1em;'>$addr2link</div>";
		if ($addr3link)
			echo "<div style='text-align:center; font-size:1em;'>$addr3link</div>";
		if ($textlink)
			echo "<div style='text-align:justify; font-size:1em; margin-left:40px; margin-right:40px;'>$textlink</div>";
		if ($userlvl == "3")
			echo "<br /><div style='text-align:center; font-size:1em;'><a href='index.php?page=links&editlink=$idlink'>...Edit this link...</a></div>";
		echo "<br /><div style='width:500px; height:5px; background-color:green; margin-left:140px; z-index:0; border-radius:5px; -moz-border-radius:5px;'></div><br /><br />";
	}
}
?>
