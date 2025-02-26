<?php
require_once dirname(__DIR__) . '/../config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $depositId = isset($data['deposit_id']) ? intval($data['deposit_id']) : 0;

    if ($depositId <= 0) {
        throw new Exception("Invalid deposit ID");
    }

    // 트랜잭션 시작
    $conn->begin_transaction();

    // 1. 입금 요청 정보 가져오기
    $depositSql = "SELECT user_id, amount, coin_name FROM deposit_requests WHERE id = ? AND status = 'pending'";
    $stmt = $conn->prepare($depositSql);
    $stmt->bind_param("i", $depositId);
    $stmt->execute();
    $result = $stmt->get_result();
    $deposit = $result->fetch_assoc();

    if (!$deposit) {
        throw new Exception("Deposit request not found or already processed");
    }

    $userId = $deposit['user_id'];
    $amount = (float)$deposit['amount'];
    $coinName = $deposit['coin_name'];

    // 2. wallet 테이블 업데이트 (locked_amount와 total_amount 증가)
    $walletUpdateSql = "
        UPDATE wallet 
        SET total_balance = total_balance + ?, 
            locked_balance = locked_balance + ?, 
            updated_at = NOW()
        WHERE user_id = ?
    ";
    $stmt = $conn->prepare($walletUpdateSql);
    $stmt->bind_param("ddi", $amount, $amount, $userId);
    $stmt->execute();

    // 3. coin 테이블 업데이트 (locked_amount와 total_amount 증가)
    $coinUpdateSql = "
        UPDATE coin 
        SET total_amount = total_amount + ?, 
            locked_amount = locked_amount + ?, 
            updated_at = NOW()
        WHERE user_id = ? AND name = ?
    ";
    $stmt = $conn->prepare($coinUpdateSql);
    $stmt->bind_param("ddis", $amount, $amount, $userId, $coinName);
    $stmt->execute();

    // 4. 입금 요청 상태를 'approved'로 업데이트
    $approveDepositSql = "UPDATE deposit_requests SET status = 'approved', updated_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($approveDepositSql);
    $stmt->bind_param("i", $depositId);
    $stmt->execute();

    // 트랜잭션 커밋
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Deposit approved successfully']);

} catch (Exception $e) {
    // 롤백
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
?>
