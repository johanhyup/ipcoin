<?php
require_once dirname(__DIR__) . '/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $userId = intval($data['user_id']);
    $amount = floatval($data['amount']);
    $endDate = $data['end_date'];

    if (!$userId || !$amount || !$endDate) {
        throw new Exception("모든 필드를 입력해주세요.");
    }

    // coin 테이블에서 user_id를 기준으로 데이터 확인
    $coinQuery = $conn->prepare("SELECT id FROM coin WHERE user_id = ?");
    $coinQuery->bind_param("i", $userId);
    $coinQuery->execute();
    $coinResult = $coinQuery->get_result();

    if ($coinResult->num_rows === 0) throw new Exception("해당 유저의 코인이 존재하지 않습니다.");
    $coin = $coinResult->fetch_assoc();
    $coinId = $coin['id'];

    // wallet 테이블에서 보유 코인 양 확인
    $walletQuery = $conn->prepare("SELECT total_balance, locked_balance FROM wallet WHERE user_id = ?");
    $walletQuery->bind_param("i", $userId);
    $walletQuery->execute();
    $walletResult = $walletQuery->get_result();

    if ($walletResult->num_rows === 0) throw new Exception("지갑이 존재하지 않습니다.");
    $wallet = $walletResult->fetch_assoc();

    if ($wallet['total_balance'] < $amount) throw new Exception("보유 코인 양이 부족합니다.");

    // 락업 테이블에 데이터 삽입
    $insertLockup = $conn->prepare("
        INSERT INTO coin_lockup (coin_id, user_id, locked_amount, end_date) 
        VALUES (?, ?, ?, ?)
    ");
    $insertLockup->bind_param("iids", $coinId, $userId, $amount, $endDate);
    $insertLockup->execute();

    // wallet 테이블 업데이트
    $updateWallet = $conn->prepare("
        UPDATE wallet 
        SET total_balance = total_balance - ?, locked_balance = locked_balance + ?
        WHERE user_id = ?
    ");
    $updateWallet->bind_param("ddi", $amount, $amount, $userId);
    $updateWallet->execute();

    // coin 테이블 업데이트
    $updateCoin = $conn->prepare("
        UPDATE coin 
        SET locked_amount = locked_amount + ?
        WHERE id = ?
    ");
    $updateCoin->bind_param("di", $amount, $coinId);
    $updateCoin->execute();

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
} finally {
    $conn->close();
}
?>
