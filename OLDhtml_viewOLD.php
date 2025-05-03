<?php
header('Content-Type: application/json');
$conn = new mysqli("172.31.80.189", "dbgage", "v(X_!#<?", "fraud_detection");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed"]);
    exit();
}

$sql = "SELECT 
            al.Action_ID,
            al.Transaction_ID,
            al.Action_Type,
            al.Timestamp,
            al.Notes,
            c.Name AS Customer_Name,
            t.Amount,
            t.Transaction_Status,
            ff.Flag_Reason
        FROM Action_Log al
        LEFT JOIN Transactions t ON al.Transaction_ID = t.Transaction_ID
        LEFT JOIN Customers c ON t.Customer_ID = c.Customer_ID
        LEFT JOIN Fraud_Flags ff ON al.Transaction_ID = ff.Transaction_ID";

$result = $conn->query($sql);
$data = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    http_response_code(500);
    echo json_encode(["error" => $conn->error]);
    exit();
}

echo json_encode($data);
$conn->close();
