<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connect to database
$conn = new mysqli("172.31.80.189", "dbgage", "v(X_!#<?", "fraud_detection");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Run a query
$sql = "SELECT * FROM Transactions"; // Or any table you want
$result = $conn->query($sql);

// Output JSON
if ($result && $result->num_rows > 0) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_PRETTY_PRINT);
} else {
    echo json_encode(["message" => "No data found"]);
}

$conn->close();
?>
