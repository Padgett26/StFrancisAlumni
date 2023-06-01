<div style="position:absolute; top:50px; left:10px;"><img src="img/history/hist1.jpg" style="opacity:0.2;filter:alpha(opacity=20); z-index:-1; max-width:400px;" /></div>
<a href="img/history/hist1.jpg" target="_blank" style="text-decoration:none;"><div class="viewpicl" style="position:absolute; top:80px; left:0px;">v<br />i<br />e<br />w<br /><br />p<br />i<br />c<br />t<br />u<br />r<br />e</div></a>
<div style="position:absolute; top:400px; left:10px;"><img src="img/history/hist2.jpg" style="opacity:0.2;filter:alpha(opacity=20); z-index:-1; max-width:400px;" /></div>
<a href="img/history/hist2.jpg" target="_blank" style="text-decoration:none;"><div class="viewpicl" style="position:absolute; top:430px; left:0px;">v<br />i<br />e<br />w<br /><br />p<br />i<br />c<br />t<br />u<br />r<br />e</div></a>
<div style="position:absolute; top:750px; right:10px;"><img src="img/history/hist3.jpg" style="opacity:0.2;filter:alpha(opacity=20); z-index:-1; max-width:400px;" /></div>
<a href="img/history/hist3.jpg" target="_blank" style="text-decoration:none;"><div class="viewpicr" style="position:absolute; top:780px; right:0px;">v<br />i<br />e<br />w<br /><br />p<br />i<br />c<br />t<br />u<br />r<br />e</div></a>
<div style="position:absolute; top:1100px; left:10px;"><img src="img/history/hist4.jpg" style="opacity:0.2;filter:alpha(opacity=20); z-index:-1; max-width:400px;" /></div>
<a href="img/history/hist4.jpg" target="_blank" style="text-decoration:none;"><div class="viewpicl" style="position:absolute; top:1130px; left:0px;">v<br />i<br />e<br />w<br /><br />p<br />i<br />c<br />t<br />u<br />r<br />e</div></a>
<div style="position:absolute; top:0px; left:0px; margin:50px 20px 20px 20px; z-index:1;">
    <?php
				$stmt = $db_conn->prepare ( "SELECT id,title FROM history ORDER BY title" );
				$stmt->execute ();
				while ( $row = $stmt->fetch () ) {
					$histid = $row [0];
					$histtitle = $row [1];
					echo "<div style='margin-left:50px;'><a href='index.php?page=history&histid=$histid' style='font-size:1.5em; color:#000000; text-decoration:none;'>$histtitle</a></div>";
				}
				if (filter_input ( INPUT_GET, 'histid', FILTER_SANITIZE_NUMBER_INT )) {
					$historyid = filter_input ( INPUT_GET, 'histid', FILTER_SANITIZE_NUMBER_INT );
				} else {
					$substmt = $db_conn->prepare ( "SELECT id FROM history ORDER BY RAND() LIMIT 1" );
					$substmt->execute ();
					$row = $substmt->fetch ();
					if ($row) {
						$historyid = $row ['id'];
					}
				}
				$subsubstmt = $db_conn->prepare ( "SELECT * FROM history WHERE id = ?" );
				$subsubstmt->execute ( array (
						$historyid
				) );
				$rowhist = $subsubstmt->fetch ();
				if ($rowhist) {
					$titlehist = $rowhist [1];
					$texthist = nl2br ( $rowhist [2] );
					$texth = str_split ( $texthist, 7000 );
				}
				echo "<div style='text-align:center; font-size:3em; margin-top:40px;'>$titlehist</div>";
				if (file_exists ( "img/history/" . $historyid . "1.jpg" ))
					echo "<div style='float:right; margin:30px 0px 20px 20px;'><img src='img/history/" . $historyid . "1.jpg' alt='' style='max-width:300px;' /></div>";
				echo "<div style='text-align:justify; font-size:1.5em; margin:30px 10px 0px 10px;'>$texth[0]";
				if (file_exists ( "img/history/" . $historyid . "2.jpg" ))
					echo "<div style='float:right; margin:20px 0px 20px 20px;'><img src='img/history/" . $historyid . "2.jpg' alt='' style='max-width:300px;' /></div>";
				echo (isset ( $texth [1] )) ? $texth [1] : "";
				echo "</div><br /><br />";
				echo "<div style='text-align:center;'>While looking for pictures for this site, we had the help of Sally Wieck at The Cheyenne County Museum, and Kent Kechter at USD297.<br />";
				echo "And the some of the pictures on this site are from the Cheyenne County Museum, and the USD 297 website.<br />Used with their permission.<br /><br /></div>";
				?>
</div>
