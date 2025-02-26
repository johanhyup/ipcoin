<?php
session_start();
require_once dirname(__DIR__) . '/config.php'; // DB 연결 파일

header('Content-Type: application/json');

try {
    // 세션 확인
    if (!isset($_SESSION['user_idx'])) {
        throw new Exception("세션이 만료되었습니다. 다시 로그인 해주세요.");
    }

    $userId = intval($_SESSION['user_idx']); // 세션에서 사용자 ID 가져오기

    // wallet 테이블에서 locked_balance 가져오기
    $walletQuery = $conn->prepare("
        SELECT locked_balance 
        FROM wallet 
        WHERE user_id = ?
    ");
    $walletQuery->bind_param("i", $userId);
    $walletQuery->execute();
    $walletResult = $walletQuery->get_result();

    $lockedAmount = 0; // 기본값
    if ($walletResult->num_rows > 0) {
        $walletData = $walletResult->fetch_assoc();
        $lockedAmount = $walletData['locked_balance']; // 변수명을 변경
    }

    // coin_lockup 테이블에서 end_date 가져오기
    $lockupQuery = $conn->prepare("
        SELECT end_date 
        FROM coin_lockup 
        WHERE user_id = ? AND status = 'active'
        ORDER BY end_date DESC LIMIT 1
    ");
    $lockupQuery->bind_param("i", $userId);
    $lockupQuery->execute();
    $lockupResult = $lockupQuery->get_result();

    $endDate = null; // 기본값
    if ($lockupResult->num_rows > 0) {
        $lockupData = $lockupResult->fetch_assoc();
        $endDate = $lockupData['end_date'];
    }

    // JSON 응답
    echo json_encode([
        "success" => true,
        "locked_amount" => $lockedAmount, // 변수명을 변경하여 반환
        "end_date" => $endDate
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
} finally {
    if (isset($walletQuery)) $walletQuery->close();
    if (isset($lockupQuery)) $lockupQuery->close();
    if (isset($conn)) $conn->close();
}
?>
