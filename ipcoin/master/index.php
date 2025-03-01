

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 대시보드 | IPcoin Wallet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        .container {
            width: 90%;
            margin: 20px auto;
        }
        .card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 10px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #777;
        }
    </style>
</head>
<?php
// 현재 파일의 디렉토리를 기준으로 frames/nav.php 포함
require_once dirname(__DIR__) . '/master/frames/nav.php';
require_once dirname(__DIR__) . '/master/frames/top_nav.php';
// 추가 코드 작성 가능

?>

<?php



// 데이터베이스 연결
require_once dirname(__DIR__) . '/config.php';

// 세션에서 로그인한 관리자 정보 가져오기
$master_name = $_SESSION['master_name'];
$rank = $_SESSION['rank'];

// 간단한 통계 데이터 조회 (예제)
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// 사용자 수 가져오기
$user_count_result = $conn->query("SELECT COUNT(*) AS user_count FROM users");
$user_count = $user_count_result->fetch_assoc()['user_count'];

// 출금 요청 수 가져오기
$withdraw_pending_result = $conn->query("SELECT COUNT(*) AS pending_count FROM withdraw_requests WHERE status = '대기중'");
$pending_withdrawals = $withdraw_pending_result->fetch_assoc()['pending_count'];

// 데이터베이스 연결 종료
$conn->close();
?>
<body>




</body>
</html>
