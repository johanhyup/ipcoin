<?php
require_once dirname(__DIR__) . '/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $userId = intval($data['user_id']);
    $amount = floatval($data['amount']);

    if (!$userId || !$amount) {
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

    // wallet 테이블에서 락업된 양 확인
    $walletQuery = $conn->prepare("SELECT locked_balance FROM wallet WHERE user_id = ?");
    $walletQuery->bind_param("i", $userId);
    $walletQuery->execute();
    $walletResult = $walletQuery->get_result();

    if ($walletResult->num_rows === 0) throw new Exception("지갑이 존재하지 않습니다.");
    $wallet = $walletResult->fetch_assoc();

    if ($wallet['locked_balance'] < $amount) throw new Exception("해제할 락업 양이 부족합니다.");

    // wallet 테이블 업데이트
    $updateWallet = $conn->prepare("
        UPDATE wallet 
        SET locked_balance = locked_balance - ?, total_balance = total_balance + ?
        WHERE user_id = ?
    ");
    $updateWallet->bind_param("ddi", $amount, $amount, $userId);
    $updateWallet->execute();

    // coin 테이블 업데이트
    $updateCoin = $conn->prepare("
        UPDATE coin 
        SET locked_amount = locked_amount - ?
        WHERE id = ?
    ");
    $updateCoin->bind_param("di", $amount, $coinId);
    $updateCoin->execute();

    // coin_lockup 테이블 업데이트 (양 감소 및 상태 변경)
    $updateLockup = $conn->prepare("
        UPDATE coin_lockup 
        SET locked_amount = locked_amount - ?
        WHERE coin_id = ? AND user_id = ? AND status = 'active'
    ");
    $updateLockup->bind_param("dii", $amount, $coinId, $userId);
    $updateLockup->execute();

    // 상태 변경 (locked_amount가 0인 경우)
    $completeLockup = $conn->prepare("
        UPDATE coin_lockup 
        SET status = 'completed' 
        WHERE coin_id = ? AND user_id = ? AND locked_amount <= 0
    ");
    $completeLockup->bind_param("ii", $coinId, $userId);
    $completeLockup->execute();

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
} finally {
    $conn->close();
}
?>
