<?php
$stmt = $db_ccdc->prepare ( "SELECT * FROM jobs ORDER BY RAND()" );
$stmt->execute ();
while ( $row = $stmt->fetch () ) {
	$id = $row ['id'];
	$title = $row ['title'];
	$content = nl2br ( make_links_clickable ( html_entity_decode ( $row ['content'], ENT_QUOTES ) ) );
	$pic1 = $row ['pic1'];
	$pic2 = $row ['pic2'];
	$updated = $row ['updated'];
	echo "<div class='showtitle'>\n";
	echo "<div style='color:#000000; text-align:center; font-size:1.5em; padding:10px; font-family:sans-serif; text-decoration:underline; cursor:pointer;'>$title</div></div>\n";
	echo "<div class='showtext'>";
	if (file_exists ( "http://www.ccdcks.com/images/jobs/$pic1.jpg" )) {
		echo "<img src='http://www.ccdcks.com/images/jobs/$pic1.jpg' alt='' style='float:right; margin:10px; max-width:300px;' />";
	}
	echo "<div style='color:#000000; text-align:justify; font-family:sans-serif; padding:10px 30px; margin-top:20px;'>$content</div>";
	if (file_exists ( "http://www.ccdcks.com/images/jobs/$pic2.jpg" )) {
		echo "<img src='http://www.ccdcks.com/images/jobs/$pic2.jpg' alt='' style='margin:10px 0px;' />";
	}
	echo "</div><br /><hr /><br />\n";
}
?>
<script>
    $(document).ready(function () {
        $('.showtext').hide();
        $('.showtitle').toggle(
                function () {
                    $(this).next('.showtext').slideDown();
                },
                function () {
                    $(this).next('.showtext').slideUp();
                }
        );
    });
</script>