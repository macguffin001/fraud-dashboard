<?php
// proxy_html_view.php
header('Content-Type: application/json');

// Fetch the data from the intermediate server
$data = file_get_contents('http://3.211.48.192/html_view.php');

// Output it directly
echo $data;
?>
