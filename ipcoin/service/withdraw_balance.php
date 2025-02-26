<?php
session_start();
require_once dirname(__DIR__) . '/config.php';


// 세션에서 사용자 ID 가져오기
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

// 유효성 검사
if (empty($user_id)) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

// 요청으로 받은 출금 주소 및 출금 수량
$withdraw_address = isset($_POST['withdraw_address']) ? trim($_POST['withdraw_address']) : '';
$withdraw_amount = isset($_POST['withdraw_amount']) ? floatval($_POST['withdraw_amount']) : 0.0;

// 최소 출금 수량 설정
$minimum_withdraw_amount = 20.0;

// 입력 데이터 검증
if (empty($withdraw_address)) {
    echo json_encode(["success" => false, "message" => "Withdraw address is required"]);
    exit;
}

if ($withdraw_amount < $minimum_withdraw_amount) {
    echo json_encode(["success" => false, "message" => "Minimum withdraw amount is {$minimum_withdraw_amount} RAY"]);
    exit;
}

// SQL 쿼리: 출금 가능 잔액 조회
$sql = "SELECT 
            (SUM(total_amount) - SUM(locked_amount)) AS withdrawable_balance 
        FROM coin 
        WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $withdrawable_balance = $row['withdrawable_balance'] ?? 0.0;

    // 출금 가능 잔액 확인
    if ($withdraw_amount > $withdrawable_balance) {
        echo json_encode(["success" => false, "message" => "Insufficient withdrawable balance"]);
        exit;
    }

    // 출금 성공 처리: 잔액 차감
    $update_sql = "UPDATE coin 
                   SET total_amount = total_amount - ? 
                   WHERE user_id = ? AND name = 'ray'";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ds", $withdraw_amount, $user_id);
    $update_stmt->execute();

    // 출금 성공: transactions 테이블에 기록 추가
    $transaction_sql = "INSERT INTO transactions (user_id, coin_name, transaction_date, amount, status) 
                        VALUES (?, 'ray', NOW(), ?, 'completed')";
    $transaction_stmt = $conn->prepare($transaction_sql);
    $transaction_stmt->bind_param("sd", $user_id, $withdraw_amount);
    $transaction_stmt->execute();

    echo json_encode([
        "success" => true,
        "message" => "Withdraw request submitted successfully",
        "remaining_balance" => number_format($withdrawable_balance - $withdraw_amount, 8)
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to fetch balance data"]);
}

$stmt->close();
$conn->close();
?>
