<?php
// manual_deposit.php
require_once dirname(__DIR__, 2) . '/config.php';
header('Content-Type: application/json');

try {
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    if (!isset($data['user_id']) || !isset($data['amount'])) {
        throw new Exception("user_id, amount 값이 필요합니다.");
    }
    $userId = (int)$data['user_id'];
    $amount = (float)$data['amount'];

    if ($userId <= 0 || $amount <= 0) {
        throw new Exception("잘못된 파라미터");
    }

    // 트랜잭션 시작
    $conn->begin_transaction();

    // 1) users 테이블에서 존재 여부 확인
    $check = $conn->prepare("SELECT id FROM users WHERE id=? LIMIT 1");
    $check->bind_param("i", $userId);
    $check->execute();
    $res = $check->get_result();
    if ($res->num_rows === 0) {
        throw new Exception("존재하지 않는 사용자입니다.");
    }

    // 2) wallet 테이블에서 해당 user_id 존재하는지 확인
    //    없으면 새로 만들어도 됨 (지갑 주소가 필요하다면 추가 로직)
    $checkW = $conn->prepare("SELECT id FROM wallet WHERE user_id=? LIMIT 1");
    $checkW->bind_param("i", $userId);
    $checkW->execute();
    $resW = $checkW->get_result();
    if ($resW->num_rows === 0) {
        // wallet 레코드가 없으면 생성
        $insertW = $conn->prepare("
            INSERT INTO wallet (user_id, total_balance, locked_balance, available_balance)
            VALUES (?, 0, 0, 0)
        ");
        $insertW->bind_param("i", $userId);
        $insertW->execute();
    }

    // 3) 입금 로그 기록 (예: deposit_logs 라는 테이블 가정)
    //    만약 deposit_requests 테이블 쓸거면 status='approved' 등등 업데이트
    $insLog = $conn->prepare("
        INSERT INTO deposit_logs (user_id, amount, deposit_date, memo)
        VALUES (?, ?, NOW(), '관리자 수동입금')
    ");
    $insLog->bind_param("id", $userId, $amount);
    $insLog->execute();

    // 4) wallet 테이블 업데이트 (total_balance, available_balance 증가)
    $updW = $conn->prepare("
        UPDATE wallet
           SET total_balance = total_balance + ?,
               available_balance = available_balance + ?
         WHERE user_id = ?
    ");
    $updW->bind_param("ddi", $amount, $amount, $userId);
    $updW->execute();

    // 트랜잭션 커밋
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => "수동 입금 완료 ($amount)"
    ]);
} catch(Exception $e) {
    // 롤백
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}