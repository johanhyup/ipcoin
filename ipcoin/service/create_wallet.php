<?php
session_start();
require_once dirname(__DIR__) . '/config.php';

header('Content-Type: application/json');

// 세션에서 값 가져오기
$mb_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null; // 사용자 이름
$user_id = isset($_SESSION['user_idx']) ? $_SESSION['user_idx'] : null;  // 사용자 ID

// 세션 유효성 검사
if (!$mb_name || !$user_id) {
    echo json_encode(["success" => false, "message" => "User not logged in or session expired"]);
    exit;
}

try {
    // 이미 지갑이 존재하는지 확인
    $check_wallet_sql = "SELECT id FROM wallet WHERE user_id = ?";
    $stmt = $conn->prepare($check_wallet_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Wallet already exists for this user"]);
        exit;
    }

    // wallet_address 생성: SHA256 해시를 사용해 앞 16자리만 가져옴 (r 접두사 추가)
    $timestamp = time();
    $raw_address = $mb_name . '_' . $timestamp; // 이름 + 타임스탬프 조합
    $hashed_address = hash('sha256', $raw_address); // SHA256 해시 생성
    $wallet_address = 'r' . substr($hashed_address, 0, 16); // 'r'을 앞에 추가하고 앞 16자리만 사용

    // wallet 테이블에 삽입
    $insert_wallet_sql = "INSERT INTO wallet (user_id, total_balance, wallet_address) VALUES (?, 0, ?)";
    $insert_stmt = $conn->prepare($insert_wallet_sql);
    $insert_stmt->bind_param("is", $user_id, $wallet_address);
    $insert_stmt->execute();

    if ($insert_stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Wallet created successfully", "wallet_address" => $wallet_address]);
    } else {
        throw new Exception("Failed to create wallet");
    }

    $stmt->close();
    $insert_stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
