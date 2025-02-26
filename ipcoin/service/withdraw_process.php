<?php
session_start();
require_once dirname(__DIR__) . '/config.php';


header('Content-Type: application/json');

// 세션에서 사용자 ID 가져오기
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

// 요청 데이터
$type = isset($_POST['type']) ? $_POST['type'] : ''; // 'inner' 또는 'outer'
$withdraw_address = isset($_POST['withdraw_address']) ? trim($_POST['withdraw_address']) : '';
$withdraw_amount = isset($_POST['withdraw_amount']) ? floatval($_POST['withdraw_amount']) : 0.0;

// 최소 출금 수량 설정
$minimum_withdraw_amount = 20.0;

// 입력 데이터 검증
if (empty($withdraw_address) || $withdraw_amount < $minimum_withdraw_amount) {
    echo json_encode(["success" => false, "message" => "Invalid withdraw details"]);
    exit;
}

$conn->begin_transaction();
try {
    // 출금 가능 잔액 확인
    $sql = "SELECT (total_amount - locked_amount) AS withdrawable_balance FROM coin WHERE user_id = ? AND name = 'RAY'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $balance = $result->fetch_assoc()['withdrawable_balance'] ?? 0.0;

    if ($withdraw_amount > $balance) {
        throw new Exception("Insufficient balance");
    }

    // 출금 처리
    $update_sql = "UPDATE coin SET total_amount = total_amount - ? WHERE user_id = ? AND name = 'RAY'";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("di", $withdraw_amount, $user_id);
    $update_stmt->execute();

    // 내부 전송 처리
    if ($type === 'inner') {
        $recipient_id = (int)$withdraw_address; // 수신자 ID로 가정
        $update_recipient_sql = "UPDATE coin SET total_amount = total_amount + ? WHERE user_id = ? AND name = 'RAY'";
        $update_recipient_stmt = $conn->prepare($update_recipient_sql);
        $update_recipient_stmt->bind_param("di", $withdraw_amount, $recipient_id);
        $update_recipient_stmt->execute();
    }

    // 트랜잭션 기록
    $transaction_sql = "INSERT INTO transactions (user_id, coin_name, transaction_date, amount, status) VALUES (?, 'RAY', NOW(), ?, 'completed')";
    $transaction_stmt = $conn->prepare($transaction_sql);
    $transaction_stmt->bind_param("id", $user_id, $withdraw_amount);
    $transaction_stmt->execute();

    $conn->commit();
    echo json_encode(["success" => true, "message" => ucfirst($type) . " transfer completed successfully"]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
?>
