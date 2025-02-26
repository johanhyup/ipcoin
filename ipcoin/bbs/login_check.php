<?php
session_start(); // 세션 시작

// 데이터베이스 연결
require_once dirname(__DIR__) . '/config.php';

// POST로 전달된 값 받기
$mb_id = isset($_POST['mb_id']) ? $_POST['mb_id'] : '';
$mb_password = isset($_POST['mb_password']) ? $_POST['mb_password'] : '';

// 유효성 검사
if (empty($mb_id) || empty($mb_password)) {
    echo "<script>alert('아이디와 비밀번호를 입력해주세요.'); history.back();</script>";
    exit;
}

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("연결 실패: " . $conn->connect_error);
}

// 아이디와 비밀번호가 맞는지 확인
$sql = "SELECT * FROM users WHERE mb_id = ?"; // 사용자가 입력한 아이디로 검색
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $mb_id); // 아이디를 쿼리에 바인딩
$stmt->execute();
$result = $stmt->get_result();

// 아이디가 존재하는지 확인
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // 비밀번호 검증
    if (password_verify($mb_password, $user['mb_password'])) {
        // 승인 여부 확인
        if ($user['approved'] != 1) {
            echo "<script>alert('관리자 승인이 필요합니다. 로그인할 수 없습니다.'); history.back();</script>";
            exit; // 로그인 절차 중단
        }

        // 로그인 성공: 세션에 사용자 정보 저장
        $_SESSION['user_idx'] = $user['id'];
        $_SESSION['user_id'] = $user['mb_id'];
        $_SESSION['user_email'] = $user['mb_email'];
        $_SESSION['user_tel'] = $user['mb_tel'];
        $_SESSION['user_name'] = $user['mb_name'];
        $_SESSION['last_login'] = $user['last_login'];
        $_SESSION['approved'] = $user['approved'];
        $_SESSION['created_at'] = $user['created_at'];
        $_SESSION['birth_date'] = $user['birth_date'];

        // 마지막 로그인 시간 갱신
        $current_time = date('Y-m-d H:i:s'); // 현재 시간
        $update_sql = "UPDATE users SET last_login = ? WHERE mb_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $current_time, $mb_id);
        $update_stmt->execute();
        $update_stmt->close();

        // 접속 로그 기록
        $user_id = $user['id'];
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $insert_log_sql = "INSERT INTO user_access_logs (user_id, last_login, user_ip) VALUES (?, ?, ?)";
        $log_stmt = $conn->prepare($insert_log_sql);
        $log_stmt->bind_param("iss", $user_id, $current_time, $user_ip);
        $log_stmt->execute();
        $log_stmt->close();

        echo "<script>alert('로그인에 성공하였습니다.');</script>";
        // 로그인 성공 후 '/' 페이지로 리다이렉션
        header("Location: /");
        exit;
    } else {
        // 비밀번호 불일치
        echo "<script>alert('비밀번호가 틀렸습니다.'); history.back();</script>";
        exit;
    }
} else {
    // 아이디가 존재하지 않는 경우
    echo "<script>alert('아이디가 존재하지 않습니다.'); history.back();</script>";
    exit;
}

// 데이터베이스 연결 종료
$stmt->close();
$conn->close();
?>
