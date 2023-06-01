<div style="text-align:center; font-weight:bold; font-size:2em; margin-bottom:80px;">Vision St Francis answers</div><br /><br />
<?php
$stmt = $db_conn->prepare("SELECT * FROM visionquestions ORDER BY created");
$stmt->execute();
while ($row = $stmt->fetch()) {
    $answer1 = nl2br($row['answer1']);
    $answer2 = nl2br($row['answer2']);
    $answer3 = nl2br($row['answer3']);
    $answer4 = nl2br($row['answer4']);
    $created = date("F j, Y", $row['created']);
    echo "<div style='border:1px solid black; margin:20px; padding:10px;'>";
    echo "<div style='font-weight:bold'>Posted on $created</div><br /><br />";
    echo "<div style='text-align:justify;'>";
    echo "<span style='font-weight:bold;'>1. If you were considering moving back to St. Francis, what would you need to see in this community?</span><br /><br />$answer1<br /><br />";
    echo "<span style='font-weight:bold;'>2. How can we who love St. Francis keep it alive and vital?</span><br /><br />$answer2<br /><br />";
    echo "<span style='font-weight:bold;'>3. If our success were completely guaranteed, what bold steps might St. Francis take as a community?</span><br /><br />$answer3<br /><br />";
    echo "<span style='font-weight:bold;'>4. What part could you play in this process?</span><br /><br />$answer4<br /><br />";
    echo "</div></div>";
}
?>