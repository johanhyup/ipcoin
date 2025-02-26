<?php
require_once dirname(__DIR__) . '/../config.php'; // 데이터베이스 연결 설정
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);

    $userId = intval($data['user_id']);
    $coinName = trim($data['coin_name']);
    $amount = floatval($data['amount']);
    $depositAddress = trim($data['deposit_address']); // 입금 주소

    if (!$userId || !$coinName || !$amount || !$depositAddress) {
        throw new Exception("모든 필드를 올바르게 입력해주세요.");
    }

    // `wallet` 테이블에서 해당 유저의 지갑 주소 가져오기
    $walletQuery = $conn->prepare("
        SELECT wallet_address 
        FROM wallet 
        WHERE user_id = (SELECT id FROM users WHERE id = ?)
    ");
    $walletQuery->bind_param("i", $userId);
    $walletQuery->execute();
    $walletResult = $walletQuery->get_result();

    if ($walletResult->num_rows === 0) {
        throw new Exception("해당 유저의 지갑 정보가 존재하지 않습니다.");
    }

    $walletData = $walletResult->fetch_assoc();
    $userWalletAddress = trim($walletData['wallet_address']);

    // 입력된 주소와 사용자의 지갑 주소 비교
    if ($userWalletAddress !== $depositAddress) {
        throw new Exception("입력된 지갑 주소가 사용자의 등록된 지갑 주소와 일치하지 않습니다.");
    }



    // `coin` 테이블에서 해당 유저의 코인 확인
    $coinQuery = $conn->prepare("SELECT id FROM coin WHERE user_id = ? AND name = ?");
    $coinQuery->bind_param("is", $userId, $coinName);
    $coinQuery->execute();
    $coinResult = $coinQuery->get_result();

    if ($coinResult->num_rows === 0) {
        // 코인이 없는 경우 새로 생성
        $insertCoin = $conn->prepare("
            INSERT INTO coin (user_id, name, total_amount, locked_amount) 
            VALUES (?, ?, 0, 0)
        ");
        $insertCoin->bind_param("is", $userId, $coinName);
        $insertCoin->execute();
        $coinId = $insertCoin->insert_id;
    } else {
        $coin = $coinResult->fetch_assoc();
        $coinId = $coin['id'];
    }

    // `deposit_requests` 테이블에 입금 기록 추가
    $insertDeposit = $conn->prepare("
        INSERT INTO deposit_requests (user_id, coin_name, amount, deposit_address, status)
        VALUES (?, ?, ?, ?, 'approved')
    ");
    $insertDeposit->bind_param("isds", $userId, $coinName, $amount, $depositAddress);
    $insertDeposit->execute();

    // `wallet` 테이블 업데이트 (total_balance, locked_balance 업데이트)
    $updateWallet = $conn->prepare("
        UPDATE wallet 
        SET total_balance = total_balance + ?, 
            locked_balance = locked_balance + ? 
        WHERE user_id = ?
    ");
    $updateWallet->bind_param("ddi", $amount, $amount, $userId);
    $updateWallet->execute();

    // `coin` 테이블 업데이트
    $updateCoin = $conn->prepare("
        UPDATE coin 
        SET total_amount = total_amount + ?, locked_amount = locked_amount + ? 
        WHERE id = ?
    ");
    $updateCoin->bind_param("ddi", $amount, $amount, $coinId);
    $updateCoin->execute();

    // `coin_lockup` 테이블에 60일 락업 추가
    $endDate = date('Y-m-d H:i:s', strtotime('+60 days'));
    $insertLockup = $conn->prepare("
        INSERT INTO coin_lockup (coin_id, user_id, locked_amount, start_date, end_date, status)
        VALUES (?, ?, ?, NOW(), ?, 'active')
    ");
    $insertLockup->bind_param("iids", $coinId, $userId, $amount, $endDate);
    $insertLockup->execute();

    echo json_encode(['success' => true, 'message' => '입금 및 락업 처리가 완료되었습니다.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
?>
