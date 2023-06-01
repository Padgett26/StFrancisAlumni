<?php

if (isset($_FILES['replacepdf']['name']) && filter_input(INPUT_POST, 'delpdf', FILTER_SANITIZE_NUMBER_INT) != "1") {
    $pdfName = (filter_input(INPUT_POST, 'pdfupdate', FILTER_SANITIZE_STRING) == "newpdf") ? filter_input(INPUT_POST, 'pdfname', FILTER_SANITIZE_STRING): filter_input(INPUT_POST, 'pdfupdate', FILTER_SANITIZE_STRING);
    if ($pdfName != "" && $pdfName != " ") {
    $saveto = "pdfs/$pdfName.pdf";
    move_uploaded_file($_FILES['replacepdf']['tmp_name'], $saveto);
    echo "<br /><br />The uploaded PDF address (for use in links) is:<br /><span style='font-weight:bold;'>http://stfrancisalumni.org/pdfs/$pdfName.pdf</span><br /><br />";
    }
}

if (filter_input(INPUT_POST, 'delpdf', FILTER_SANITIZE_NUMBER_INT) == "1") {
    $pdfName = filter_input(INPUT_POST, 'pdfupdate', FILTER_SANITIZE_STRING);
    if (file_exists("pdfs/$pdfName.pdf"))
        unlink("pdfs/$pdfName.pdf");
}

echo <<<_END
    <form method='post' action='index.php?page=alladmin&choice=pdf' enctype='multipart/form-data'>
    <div style='border:1px solid #000000; padding:5px; margin:5px; float:left;'>
        <div style='text-align:center;'><span style='font-weight:bold;'>New PDF</span><br /><br />
            <input type='file' name='replacepdf' size='20' /><br /><br />
            Name of pdf<br />Only letters and numbers, please.<br /><input type='text' name='pdfname' /><br /><br />
            <input type='hidden' name='pdfupdate' value='newpdf' />
            <input type='submit' value='Add pdf' />
        </div>
    </div>
    </form>
_END;

$pages = array();
foreach (new DirectoryIterator("/pdfs") as $j) {
    if (!$j->isDot()) {
        $pages[] = "$j";
    }
}
sort($pages, SORT_NUMERIC);
foreach ($pages as $j) {
    if (stripos($j, ".pdf") !== FALSE) {
        $name = str_split($j, stripos($j, ".pdf"));
        echo <<<_END
    <form method='post' action='index.php?page=alladmin&choice=pdf' enctype='multipart/form-data'>
    <div style='border:1px solid #000000; padding:5px; margin:5px; float:left;'>
        <div style='text-align:center;'><span style='font-weight:bold;'>$name[0]</span><br /><br />
            Replace the PDF with a newer version-<br /><input type='file' name='replacepdf' size='20' /><br /><br />
            <input type='checkbox' name='delpdf' value='1' id='delpdf'/><label for='delpdf'>Delete this pdf.</label><br /><br />
            <input type='hidden' name='pdfupdate' value='$name[0]' />
            <input type='submit' value='Edit pdf' />
        </div>
    </div>
    </form>
_END;
    }
}