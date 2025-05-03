<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("172.31.80.189", "dbgage", "v(X_!#<?", "fraud_detection");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fraud Detection Data</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9; }
        .entry { background: #fff; margin-bottom: 20px; padding: 15px; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .section-title { font-weight: bold; margin-top: 10px; }
        .row { margin: 5px 0; }
    </style>
</head>
<body>
    <h1>Fraud Detection and Transaction Details</h1>

    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='entry'>";

            echo "<div class='section-title'>Action Info</div>";
            echo "<div class='row'>Action ID: " . htmlspecialchars($row["Action_ID"]) . "</div>";
            echo "<div class='row'>Transaction ID: " . htmlspecialchars($row["Action_Transaction_ID"]) . "</div>";
            echo "<div class='row'>Employee ID: " . htmlspecialchars($row["Employee_ID"]) . "</div>";
            echo "<div class='row'>Action Type: " . htmlspecialchars($row["Action_Type"]) . "</div>";
            echo "<div class='row'>Timestamp: " . htmlspecialchars($row["Action_Timestamp"]) . "</div>";
            echo "<div class='row'>Notes: " . htmlspecialchars($row["Action_Notes"]) . "</div>";

            echo "<div class='section-title'>Customer Info</div>";
            echo "<div class='row'>Customer ID: " . htmlspecialchars($row["Customer_ID"]) . "</div>";
            echo "<div class='row'>Name: " . htmlspecialchars($row["Customer_Name"]) . "</div>";
            echo "<div class='row'>Email: " . htmlspecialchars($row["Customer_Email"]) . "</div>";
            echo "<div class='row'>Phone: " . htmlspecialchars($row["Customer_Phone"]) . "</div>";
            echo "<div class='row'>Address: " . htmlspecialchars($row["Customer_Address"]) . "</div>";

            echo "<div class='section-title'>Transaction Info</div>";
            echo "<div class='row'>Amount: $" . htmlspecialchars($row["Transaction_Amount"]) . "</div>";
            echo "<div class='row'>Time: " . htmlspecialchars($row["Transaction_Time"]) . "</div>";
            echo "<div class='row'>Status: " . htmlspecialchars($row["Transaction_Status"]) . "</div>";
            echo "<div class='row'>Fraud Flag: " . htmlspecialchars($row["Transaction_Fraud_Flag"]) . "</div>";

            echo "<div class='section-title'>Fraud Flag Info</div>";
            echo "<div class='row'>Flag ID: " . htmlspecialchars($row["Flag_ID"]) . "</div>";
            echo "<div class='row'>Flag Type: " . htmlspecialchars($row["Flag_Type"]) . "</div>";
            echo "<div class='row'>Flag Reason: " . htmlspecialchars($row["Flag_Reason"]) . "</div>";
            echo "<div class='row'>Date Resolved: " . htmlspecialchars($row["Fraud_Flag_Resolved"]) . "</div>";

            echo "</div>";
        }
    } else {
        echo "<p>No data found or query failed.</p>";
        if (!$result) {
            echo "<p>Query error: " . $conn->error . "</p>";
        }
    }

    $conn->close();
    ?>
</body>
</html>
