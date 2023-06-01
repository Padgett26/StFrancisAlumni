<?php include 'functions.php';

$stmt = $db_conn->prepare("SELECT firstname, miname, lastname, maidenname, address, cityst, zip, email, phone, year FROM users ORDER BY lastname");
$stmt->execute();
$output = fopen('php://output','w') or die("Cant open php://output");
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="UserList.csv"');
fputcsv($output,array('firstname', 'miname', 'lastname', 'maidenname', 'address', 'cityst', 'zip', 'email', 'phone', 'year'));
while ($csv_line = $stmt->fetch())
{
    fputcsv($output, $csv_line);
}
fclose($output) or die("Cant close php://output");
?>