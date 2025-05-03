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
        al.Action_ID,
        al.Transaction_ID AS Action_Transaction_ID,
        al.Employee_ID,
        al.Action_Type,
        al.Timestamp AS Action_Timestamp,
        al.Notes AS Action_Notes,
        c.Customer_ID,
        c.Name AS Customer_Name,
        c.Email AS Customer_Email,
        c.Phone AS Customer_Phone,
        c.Address AS Customer_Address,
        t.Amount AS Transaction_Amount,
        t.Time AS Transaction_Time,
        t.Transaction_Status,
        t.Fraud_Flag AS Transaction_Fraud_Flag,
        ff.Flag_ID,
        ff.Flag_Type,
        ff.Flag_Reason,
        ff.Date_Resolved AS Fraud_Flag_Resolved
    FROM 
        Action_Log al
    LEFT JOIN 
        Transactions t ON al.Transaction_ID = t.Transaction_ID
    LEFT JOIN 
        Customers c ON t.Customer_ID = c.Customer_ID
    LEFT JOIN 
        Fraud_Flags ff ON al.Transaction_ID = ff.Transaction_ID
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
