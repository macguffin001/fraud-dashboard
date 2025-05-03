<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("172.31.80.189", "dbgage", "v(X_!#<?", "fraud_detection");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM Action_Log";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaction Data</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        ul { list-style-type: none; padding: 0; }
        li { background: #f4f4f4; margin: 5px 0; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Transaction List</h1>

    <?php
    if ($result && $result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>ID: " . htmlspecialchars($row["id"]) . " - Name: " . htmlspecialchars($row["name"]) . "</li>";
        }
        echo "</ul>";
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

