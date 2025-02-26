<?php
session_start();
require_once dirname(__DIR__) . '/config.php';


header('Content-Type: application/json');

// 세션에서 사용자 ID 가져오기
$user_id = isset($_SESSION['user_idx']) ? intval($_SESSION['user_idx']) : 0;

// POST 데이터 받기
$withdraw_address = isset($_POST['withdraw_address']) ? trim($_POST['withdraw_address']) : null;
$withdraw_amount = isset($_POST['withdraw_amount']) ? floatval($_POST['withdraw_amount']) : 0;
$coin_name = "RAY";

// 유효성 검사
if ($user_id <= 0) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

if (!$withdraw_address || $withdraw_amount <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit;
}

try {
    // 1. 출금 가능 금액 확인
    $wallet_sql = "SELECT available_balance FROM wallet WHERE user_id = ?";
    $wallet_stmt = $conn->prepare($wallet_sql);
    $wallet_stmt->bind_param("i", $user_id);
    $wallet_stmt->execute();
    $wallet_result = $wallet_stmt->get_result();

    if (!$wallet_row = $wallet_result->fetch_assoc()) {
        throw new Exception("Wallet not found");
    }

    $available_balance = (float)$wallet_row['available_balance'];

    // 출금 가능 금액 확인
    if ($withdraw_amount > $available_balance) {
        throw new Exception("출금액이 부족합니다!");
    }
    $wallet_stmt->close();

    // 2. 출금 요청 처리 시작 (트랜잭션)
    $conn->begin_transaction();

    // 송금자: wallet 테이블에서 잔액 차감
    $update_wallet_sql = "UPDATE wallet SET available_balance = available_balance - ? WHERE user_id = ?";
    $update_wallet_stmt = $conn->prepare($update_wallet_sql);
    $update_wallet_stmt->bind_param("di", $withdraw_amount, $user_id);
    if (!$update_wallet_stmt->execute()) {
        throw new Exception("Failed to update wallet balance: " . $update_wallet_stmt->error);
    }
    $update_wallet_stmt->close();

    // 3. withdraw_requests 테이블에 출금 요청 저장 (상태: pending)
    $insert_request_sql = "INSERT INTO withdraw_requests (user_id, coin_name, amount, withdraw_address, withdraw_type, status)
                           VALUES (?, ?, ?, ?, 'outer', 'pending')";
    $insert_request_stmt = $conn->prepare($insert_request_sql);
    $insert_request_stmt->bind_param("isds", $user_id, $coin_name, $withdraw_amount, $withdraw_address);
    if (!$insert_request_stmt->execute()) {
        throw new Exception("Failed to insert withdraw request: " . $insert_request_stmt->error);
    }
    $insert_request_stmt->close();

    // 4. transactions 테이블에 트랜잭션 기록 (status: 0 - pending)
    $insert_tx_sql = "INSERT INTO transactions (user_id, coin_name, amount, status) VALUES (?, ?, ?, 0)";
    $insert_tx_stmt = $conn->prepare($insert_tx_sql);
    $negative_amount = -$withdraw_amount; // 출금은 음수
    $insert_tx_stmt->bind_param("isd", $user_id, $coin_name, $negative_amount);
    if (!$insert_tx_stmt->execute()) {
        throw new Exception("Failed to insert transaction record: " . $insert_tx_stmt->error);
    }
    $insert_tx_stmt->close();

    // 트랜잭션 커밋
    $conn->commit();

    echo json_encode(["success" => true, "message" => "Withdraw request submitted successfully and is pending approval."]);
} catch (Exception $e) {
    // 오류 발생 시 롤백
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Transaction failed: " . $e->getMessage()]);
} finally {
    $conn->close();
}
?>
