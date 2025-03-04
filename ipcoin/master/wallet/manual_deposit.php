<?php
require_once dirname(__DIR__) . '/../../config.php';
header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $userId = intval($input['user_id'] ?? 0);
    $amount = floatval($input['amount'] ?? 0);

    if ($userId <= 0 || $amount <= 0) {
        throw new Exception("유효하지 않은 파라미터");
    }

    $conn->begin_transaction();

    // 코인 테이블 확인
    $stmt = $conn->prepare("SELECT id FROM coin WHERE user_id=? AND name='IP'");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        // 코인 레코드 없으면 생성
        $ins = $conn->prepare("
          INSERT INTO coin (user_id, name, total_amount, locked_amount)
          VALUES (?, 'IP', 0, 0)
        ");
        $ins->bind_param("i", $userId);
        $ins->execute();
        $coinId = $ins->insert_id;
        $ins->close();
    } else {
        $row = $res->fetch_assoc();
        $coinId = $row['id'];
    }
    $stmt->close();

    // deposit_requests 기록
    $ins2 = $conn->prepare("
      INSERT INTO deposit_requests (user_id, coin_name, amount, deposit_address, status)
      VALUES (?, 'IP', ?, 'manual', 'approved')
    ");
    $ins2->bind_param("id", $userId, $amount);
    $ins2->execute();
    $ins2->close();

    // coin.total_amount += amount
    $updCoin = $conn->prepare("UPDATE coin SET total_amount=total_amount+? WHERE id=?");
    $updCoin->bind_param("di", $amount, $coinId);
    $updCoin->execute();
    $updCoin->close();

    // wallet.total_balance += amount
    $updWallet = $conn->prepare("UPDATE wallet SET total_balance=total_balance+? WHERE user_id=?");
    $updWallet->bind_param("di", $amount, $userId);
    $updWallet->execute();
    $updWallet->close();

    $conn->commit();
    echo json_encode(['success'=>true,'message'=>"코인 {$amount}개를 수동입금했습니다."]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
} finally {
    $conn->close();
}