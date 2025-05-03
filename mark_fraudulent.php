<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection details
$servername = "172.31.80.189"; // Database host
$username = "dbgage";
$password = "v(X_!#<?";
$dbname = "fraud_detection";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Get POST data
$transaction_id = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : null;
$fraud_reason = isset($_POST['fraud_reason']) ? $_POST['fraud_reason'] : null;

// Debugging: Log incoming data
error_log("Transaction ID: $transaction_id, Fraud Reason: $fraud_reason");

// Check if transaction_id and fraud_reason are provided
if ($transaction_id && $fraud_reason) {
    // Prepare and bind SQL query to insert data into the marked_as_fraudulent table
    $stmt = $conn->prepare("INSERT INTO marked_as_fraudulent (transaction_id, fraud_reason) VALUES (?, ?)");
    if ($stmt === false) {
        // If there’s an error preparing the statement, log it
        error_log("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("is", $transaction_id, $fraud_reason);  // "i" for integer, "s" for string

    // Execute the query
    if ($stmt->execute()) {
        // Success
        echo json_encode(['message' => 'Transaction marked as fraudulent successfully.']);
    } else {
        // Error in execution
        http_response_code(500);
        error_log("Failed to execute statement: " . $stmt->error); // Log the error
        echo json_encode(['error' => 'Failed to mark transaction as fraudulent.']);
    }

    // Close statement
    $stmt->close();
} else {
    // Missing transaction_id or fraud_reason
    http_response_code(400);
    error_log('Invalid input. Missing transaction_id or fraud_reason.');
    echo json_encode(['error' => 'Invalid input. Please provide both transaction_id and fraud_reason.']);
}

// Close connection
$conn->close();
?>
