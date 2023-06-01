<?php
$q = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);
if ($q == "bboard") {
    include 'functions.php';
    include "feed_bb.php";
}
if ($q == "news") {
    include 'functions.php';
    include "feed_news.php";
}
?>
