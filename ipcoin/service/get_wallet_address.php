<?php
session_start();
require_once dirname(__DIR__) . '/config.php';

header('Content-Type: application/json');

// 세션에서 user_idx 가져오기
$user_idx = isset($_SESSION['user_idx']) ? $_SESSION['user_idx'] : null;

// 세션 유효성 검사
if (!$user_idx) {
    echo json_encode(["success" => false, "message" => "User not logged in or session expired"]);
    exit;
}

try {
    // 데이터베이스에서 user_idx와 일치하는 지갑 주소 가져오기
    $sql = "SELECT wallet_address FROM wallet WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_idx); // user_idx를 바인딩
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // 지갑 주소 반환
        echo json_encode(["success" => true, "wallet_address" => $row['wallet_address']]);
    } else {
        // 지갑이 존재하지 않을 경우
        echo json_encode(["success" => false, "message" => "Wallet address not found"]);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    // 예외 처리
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
