<?php
session_start();
require_once dirname(__DIR__) . '/config.php';

header('Content-Type: application/json');

$user_id = isset($_SESSION['user_idx']) ? intval($_SESSION['user_idx']) : 0;

if ($user_id <= 0) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

try {
    $sql = "SELECT available_balance FROM wallet WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(["success" => true, "available_balance" => $row['available_balance']]);
    } else {
        echo json_encode(["success" => false, "message" => "Wallet not found"]);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>
