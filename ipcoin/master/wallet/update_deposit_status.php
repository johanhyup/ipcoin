<?php
require_once dirname(__DIR__) . '/../config.php';

header('Content-Type: application/json');

try {
    // JSON 데이터를 받아옵니다.
    $data = json_decode(file_get_contents("php://input"), true);
    $depositId = intval($data['deposit_id']);
    $action = trim($data['action']);

    if (!$depositId || $action !== 'approve') {
        throw new Exception("잘못된 요청입니다.");
    }

    // 입금 요청 정보 가져오기
    $stmt = $conn->prepare("SELECT user_id, coin_name, amount, deposit_address FROM deposit_requests WHERE id = ?");
    $stmt->bind_param("i", $depositId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("해당 입금 요청을 찾을 수 없습니다.");
    }

    $deposit = $result->fetch_assoc();
    $userId = intval($deposit['user_id']);
    $coinName = $deposit['coin_name'];
    $amount = floatval($deposit['amount']);
    $depositAddress = trim($deposit['deposit_address']);
    $stmt->close();

    // `wallet_address` 유효성 검사
    $stmt = $conn->prepare("SELECT id, user_id FROM wallet WHERE wallet_address = ?");
    $stmt->bind_param("s", $depositAddress);
    $stmt->execute();
    $walletResult = $stmt->get_result();

    if ($walletResult->num_rows === 0) {
        throw new Exception("지갑 주소가 유효하지 않습니다.");
    }

    $wallet = $walletResult->fetch_assoc();
    $walletUserId = intval($wallet['user_id']);
    $stmt->close();

    if ($walletUserId !== $userId) {
        throw new Exception("지갑 주소가 해당 사용자와 일치하지 않습니다.");
    }

    // 승인 상태로 설정
    $status = 'approved';

    // `deposit_requests` 테이블 업데이트
    $stmt = $conn->prepare("UPDATE deposit_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $depositId);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception("입금 요청 상태 업데이트에 실패했습니다.");
    }
    $stmt->close();

    // `coin` 테이블 업데이트
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

    // `wallet` 테이블 업데이트
    $stmt = $conn->prepare("
        UPDATE wallet 
        SET total_balance = total_balance + ?, 
            locked_balance = locked_balance + ?
        WHERE wallet_address = ?
    ");
    $stmt->bind_param("dds", $amount, $amount, $depositAddress);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception("지갑 정보 업데이트에 실패했습니다.");
    }
    $stmt->close();

    // 성공 응답 반환
    echo json_encode(['success' => true, 'message' => "입금 요청이 성공적으로 {$status} 상태로 업데이트되었습니다."]);
} catch (Exception $e) {
    // 실패 응답 반환
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
?>
