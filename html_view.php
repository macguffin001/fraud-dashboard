<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("172.31.80.189", "dbgage", "v(X_!#<?", "fraud_detection");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$sql = "
SELECT 
    t.Transaction_ID AS Action_Transaction_ID,
    c.Name AS Customer_Name,
    t.Amount AS Transaction_Amount,
    t.Fraud_Flag AS Transaction_Fraud_Flag,
    ff.Flag_Reason,
    al.Action_Type,
    al.Notes AS Action_Notes
FROM 
    Transactions t
JOIN 
    Customers c ON t.Customer_ID = c.Customer_ID
LEFT JOIN 
    Fraud_Flags ff ON t.Transaction_ID = ff.Transaction_ID
LEFT JOIN 
    Action_Log al ON t.Transaction_ID = al.Transaction_ID;

";

$result = $conn->query($sql);

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'No data found']);
}

$conn->close();
?>
