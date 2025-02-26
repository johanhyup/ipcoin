<?php
require_once dirname(__DIR__) . '/config.php';
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);

    $userId = isset($data['user_id']) ? intval($data['user_id']) : 0;
    $coinId = isset($data['coin_id']) ? intval($data['coin_id']) : 0;
    $lockedAmount = isset($data['locked_amount']) ? floatval($data['locked_amount']) : 0;
    $endDate = isset($data['end_date']) ? $data['end_date'] : null;

    if (!$userId || !$coinId || $lockedAmount <= 0 || !$endDate) {
        throw new Exception("모든 필드를 입력해 주세요.");
    }

    // 사용자 지갑 잔액 확인
    $walletQuery = $conn->prepare("SELECT total_balance FROM wallet WHERE user_id = ?");
    $walletQuery->bind_param("i", $userId);
    $walletQuery->execute();
    $walletResult = $walletQuery->get_result();
    $wallet = $walletResult->fetch_assoc();

    if (!$wallet || $wallet['total_balance'] < $lockedAmount) {
        throw new Exception("보유량을 초과하는 락업은 불가능합니다.");
    }

    // `coin_lockup`에 삽입
    $insertLockup = $conn->prepare("
        INSERT INTO coin_lockup (user_id, coin_id, locked_amount, end_date) 
        VALUES (?, ?, ?, ?)
    ");
    $insertLockup->bind_param("iids", $userId, $coinId, $lockedAmount, $endDate);
    $insertLockup->execute();

    // `wallet` 테이블에서 락업 반영
    $updateWallet = $conn->prepare("
        UPDATE wallet 
        SET total_balance = total_balance - ?, locked_amount = locked_amount + ?
        WHERE user_id = ?
    ");
    $updateWallet->bind_param("ddi", $lockedAmount, $lockedAmount, $userId);
    $updateWallet->execute();

    echo json_encode(['success' => true, 'message' => '락업이 설정되었습니다.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
?>
