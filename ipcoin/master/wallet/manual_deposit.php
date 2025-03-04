<?php
require_once dirname(__DIR__) . '/../config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);

    $userId = intval($data['user_id']);
    $coinName = trim($data['coin_name']);
    $amount = floatval($data['amount']);
    $depositAddress = trim($data['deposit_address']);

    if (!$userId || !$coinName || !$amount || !$depositAddress) {
        throw new Exception("모든 필드를 올바르게 입력해주세요.");
    }

    // wallet 테이블에서 사용자 검증
    $stmt = $conn->prepare("SELECT id FROM wallet WHERE user_id = ? AND wallet_address = ?");
    $stmt->bind_param("is", $userId, $depositAddress);
    $stmt->execute();
    $walletRes = $stmt->get_result();

    if ($walletRes->num_rows === 0) {
        throw new Exception("지갑 주소가 일치하지 않습니다.");
    }

    $stmt->close();

    // coin 테이블 업데이트
    $stmt = $conn->prepare("
        INSERT INTO coin (user_id, name, total_amount, locked_amount)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            total_amount = total_amount + VALUES(total_amount),
            locked_amount = locked_amount + VALUES(locked_amount)
    ");
    $stmt->bind_param("isdd", $userId, $coinName, $amount, $amount);
    $stmt->execute();
    $stmt->close();

    // wallet 테이블 업데이트
    $stmt = $conn->prepare("
        UPDATE wallet 
        SET total_balance = total_balance + ?, locked_balance = locked_balance + ?
        WHERE user_id = ?
    ");
    $stmt->bind_param("ddi", $amount, $amount, $userId);
    $stmt->execute();
    $stmt->close();

    // deposit_requests 기록 추가 (옵션)
    $stmt = $conn->prepare("
        INSERT INTO deposit_requests (user_id, coin_name, amount, deposit_address, status)
        VALUES (?, ?, ?, ?, 'approved')
    ");
    $stmt->bind_param("isds", $userId, $coinName, $amount, $depositAddress);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['success' => true, 'message' => '입금이 성공적으로 처리되었습니다.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}