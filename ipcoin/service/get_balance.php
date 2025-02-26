<?php
session_start(); // 세션 시작
require_once dirname(__DIR__) . '/config.php';

// 세션에서 사용자 ID 가져오기
$user_id = isset($_SESSION['user_idx']) ? intval($_SESSION['user_idx']) : 0;

// 고정된 코인 이름 설정
$name = 'IP';

// 유효성 검사
if ($user_id <= 0) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

// SQL 쿼리 작성: 조건에 맞는 total_amount와 locked_amount의 합 계산
$sql = "SELECT 
            SUM(total_amount) AS total_amount_sum, 
            SUM(locked_amount) AS locked_amount_sum 
        FROM coin 
        WHERE name = ? AND user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $name, $user_id); // name은 문자열, user_id는 정수
$stmt->execute();
$result = $stmt->get_result();

// 결과 확인 및 JSON 반환
$response = ["success" => false];

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response = [
        "success" => true,
        "total_balance" => (float) $row['total_amount_sum'], // 합계로 반환
        "locked_balance" => (float) $row['locked_amount_sum'] // 합계로 반환
    ];
} else {
    $response["message"] = "No data found";
}

$stmt->close();
$conn->close();

// JSON 형식으로 응답
header('Content-Type: application/json');
echo json_encode($response);
?>
