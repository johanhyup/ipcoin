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
    // 1. 내 지갑 주소와 출금 가능 금액 확인
    $wallet_sql = "SELECT wallet_address, available_balance, total_balance FROM wallet WHERE user_id = ?";
    $wallet_stmt = $conn->prepare($wallet_sql);
    $wallet_stmt->bind_param("i", $user_id);
    $wallet_stmt->execute();
    $wallet_result = $wallet_stmt->get_result();

    if (!$wallet_row = $wallet_result->fetch_assoc()) {
        throw new Exception("Wallet not found");
    }

    $wallet_address = $wallet_row['wallet_address'];
    $available_balance = (float)$wallet_row['available_balance'];
    $total_balance = (float)$wallet_row['total_balance'];

    // 내 주소와 보낼 주소가 같으면 오류
    if ($wallet_address === $withdraw_address) {
        throw new Exception("You cannot withdraw to your own wallet address");
    }

    // 출금 가능 금액 확인
    if ($withdraw_amount > $available_balance) {
        throw new Exception("출금액이 부족합니다!");
    }
    $wallet_stmt->close();

    // 2. 수신자의 user_id 확인
    $receiver_sql = "SELECT user_id FROM wallet WHERE wallet_address = ?";
    $receiver_stmt = $conn->prepare($receiver_sql);
    $receiver_stmt->bind_param("s", $withdraw_address);
    $receiver_stmt->execute();
    $receiver_result = $receiver_stmt->get_result();

    if (!$receiver_row = $receiver_result->fetch_assoc()) {
        throw new Exception("The specified withdraw address does not exist");
    }

    $receiver_id = $receiver_row['user_id'];
    $receiver_stmt->close();
    // 2. withdraw_requests 테이블에 요청 기록 추가
    $insert_request_sql = "INSERT INTO withdraw_requests (user_id, coin_name, amount, withdraw_address, withdraw_type, status) 
                           VALUES (?, ?, ?, ?, 'inner', 'pending')";
    $insert_request_stmt = $conn->prepare($insert_request_sql);
    $insert_request_stmt->bind_param("isds", $user_id, $coin_name, $withdraw_amount, $withdraw_address);
    if (!$insert_request_stmt->execute()) {
        throw new Exception("Failed to insert withdraw request: " . $insert_request_stmt->error);
    }
    $insert_request_stmt->close();
    // 3. 출금 및 입금 처리 (트랜잭션 시작)
    $conn->begin_transaction();

    // 송금자: wallet 테이블 업데이트
    $update_wallet_sql = "UPDATE wallet SET total_balance = total_balance - ?, available_balance = available_balance - ? WHERE user_id = ?";
    $update_wallet_stmt = $conn->prepare($update_wallet_sql);
    $update_wallet_stmt->bind_param("ddi", $withdraw_amount, $withdraw_amount, $user_id);
    if (!$update_wallet_stmt->execute()) {
        throw new Exception("Failed to update sender wallet: " . $update_wallet_stmt->error);
    }
    $update_wallet_stmt->close();

    // 송금자: coin 테이블에서 차감
    $update_coin_sql = "UPDATE coin SET total_amount = total_amount - ? WHERE user_id = ? AND name = ?";
    $update_coin_stmt = $conn->prepare($update_coin_sql);
    $update_coin_stmt->bind_param("dis", $withdraw_amount, $user_id, $coin_name);
    $update_coin_stmt->execute();
    $update_coin_stmt->close();

    // 수신자: wallet 테이블 업데이트
    $update_receiver_wallet_sql = "UPDATE wallet SET total_balance = total_balance + ?, available_balance = available_balance + ? WHERE user_id = ?";
    $update_receiver_wallet_stmt = $conn->prepare($update_receiver_wallet_sql);
    $update_receiver_wallet_stmt->bind_param("ddi", $withdraw_amount, $withdraw_amount, $receiver_id);
    $update_receiver_wallet_stmt->execute();
    $update_receiver_wallet_stmt->close();

    // 수신자: coin 테이블에서 추가
    $update_receiver_coin_sql = "INSERT INTO coin (user_id, name, total_amount) 
                                 VALUES (?, ?, ?) 
                                 ON DUPLICATE KEY UPDATE total_amount = total_amount + ?";
    $amount = $withdraw_amount;
    $update_receiver_coin_stmt = $conn->prepare($update_receiver_coin_sql);
    $update_receiver_coin_stmt->bind_param("isdd", $receiver_id, $coin_name, $amount, $amount);
    $update_receiver_coin_stmt->execute();
    $update_receiver_coin_stmt->close();

    // 트랜잭션 기록 (송금자와 수신자)
    $insert_tx_sql = "INSERT INTO transactions (user_id, coin_name, amount, status) VALUES (?, ?, ?, 1)";
    $insert_tx_stmt = $conn->prepare($insert_tx_sql);

    // 송금자 기록
    $negative_amount = -$withdraw_amount;
    $insert_tx_stmt->bind_param("isd", $user_id, $coin_name, $negative_amount);
    $insert_tx_stmt->execute();

    // 수신자 기록
    $insert_tx_stmt->bind_param("isd", $receiver_id, $coin_name, $withdraw_amount);
    $insert_tx_stmt->execute();

    $insert_tx_stmt->close();

    // 트랜잭션 커밋
    $conn->commit();
    echo json_encode(["success" => true, "message" => "Withdraw and deposit successful"]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Transaction failed: " . $e->getMessage()]);
} finally {
    $conn->close();
}
?>
