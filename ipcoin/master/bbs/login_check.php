<?php
session_start();
require_once dirname(__DIR__) . '/../config.php';
require_once dirname(__DIR__) . '/../frames/asset.php';

// POST 데이터 받기
$master_id = isset($_POST['master_id']) ? trim($_POST['master_id']) : '';
$master_password = isset($_POST['master_password']) ? trim($_POST['master_password']) : '';

// 유효성 검사
if (empty($master_id) || empty($master_password)) {
    echo "<script>alert('아이디와 비밀번호를 모두 입력해주세요.'); history.back();</script>";
    exit;
}

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 오류 검사
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

try {
    // 아이디로 master 테이블 조회
    $sql = "SELECT * FROM master WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $master_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $master = $result->fetch_assoc();

        // 비밀번호 검증
        if (password_verify($master_password, $master['password'])) {
            // 세션에 로그인 정보 저장
            $_SESSION['master_id'] = $master['id'];
            $_SESSION['master_name'] = $master['master_name'];
            $_SESSION['rank'] = $master['rank'];

            // 최근 로그인 시간 업데이트
            $current_time = date('Y-m-d H:i:s');
            $update_sql = "UPDATE master SET recent_login = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $current_time, $master['id']);
            $update_stmt->execute();

            echo "<script>alert('로그인 성공!'); window.location.href = '/master/index.php';</script>";
            exit;
        } else {
            throw new Exception('비밀번호가 일치하지 않습니다.');
        }
    } else {
        throw new Exception('아이디가 존재하지 않습니다.');
    }
} catch (Exception $e) {
    echo "<script>alert('로그인 실패: " . $e->getMessage() . "'); history.back();</script>";
} finally {
    $stmt->close();
    $conn->close();
}
?>
