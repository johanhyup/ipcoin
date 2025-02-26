<?php
// PHP 파일 시작 시 공백이나 HTML 태그 없이 작성해야 합니다
require_once dirname(__DIR__) . '/../config.php';

// 데이터 가져오는 SQL 쿼리
$query = "
    SELECT 
        u.id AS user_id,
        u.mb_id,
        u.mb_name,
        u.managed_by,
        u.mb_email,
        u.mb_tel,
        u.grade,
        u.approved,
        DATE(u.created_at) AS signup_date,
        w.wallet_address,
        w.total_balance,
        w.available_balance,
        w.locked_balance,
        c.name AS coin_name,
        c.total_amount AS coin_total_amount,
        c.locked_amount AS coin_locked_amount
    FROM users u
    LEFT JOIN wallet w ON u.id = w.user_id
    LEFT JOIN coin c ON u.id = c.user_id
    ORDER BY u.id
";

// SQL 실행
$result = $conn->query($query);

// 결과를 배열로 변환
$users = $result->fetch_all(MYSQLI_ASSOC);

// JSON 응답 설정
header('Content-Type: application/json');
echo json_encode($users);

// 연결 종료
$conn->close();
