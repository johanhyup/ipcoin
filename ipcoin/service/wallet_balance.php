<?php

// 현재 파일의 상위 디렉토리의 config.php를 불러옴
require_once dirname(__DIR__) . '/config.php';


session_start();
// 세션에서 사용자 정보 가져오기
$user_id = isset($_SESSION['user_idx']) ? intval($_SESSION['user_idx']) : 0;
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

// 유효성 검사
if ($user_id <= 0 || empty($user_name)) {
    echo json_encode(["success" => false, "message" => "Invalid user session. Please log in again."]);
    exit;
}

// 코인 합계 및 잠긴 코인 합계 계산
$sql = "SELECT 
            SUM(total_amount) AS total_amount_sum, 
            SUM(locked_amount) AS locked_amount_sum 
        FROM coin 
        WHERE user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_amount = 0;
$locked_amount = 0;
$withdrawable_amount = 0;

if ($row = $result->fetch_assoc()) {
    $total_amount = $row['total_amount_sum'] ?? 0;
    $locked_amount = $row['locked_amount_sum'] ?? 0;
    $withdrawable_amount = $total_amount - $locked_amount; // 출금 가능 금액
}

$stmt->close();

// 코인별 상세 내역 조회
$sql_details = "SELECT 
                    name AS coin_name, 
                    total_amount, 
                    locked_amount, 
                    (total_amount - locked_amount) AS withdrawable_amount 
                FROM coin 
                WHERE user_id = ?";

$stmt_details = $conn->prepare($sql_details);
$stmt_details->bind_param("i", $user_id);
$stmt_details->execute();
$result_details = $stmt_details->get_result();

$coins = [];
while ($row = $result_details->fetch_assoc()) {
    $coins[] = $row;
}

$stmt_details->close();
$conn->close();

// JSON 응답
echo json_encode([
    "success" => true,
    "user_name" => $user_name,
    "total_balance" => number_format($total_amount, 8),
    "locked_balance" => number_format($locked_amount, 8),
    "withdrawable_balance" => number_format($withdrawable_amount, 8),
    "coins" => $coins,
    "last_updated" => date("Y-m-d H:i:s")
]);
?>
