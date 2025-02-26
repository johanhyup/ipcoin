<?php
// Include necessary configuration and database connection files
require_once dirname(__DIR__) . '/config.php'; // db_connect.php 파일로 데이터베이스 연결

// Sanitize and validate input data
$mb_id = isset($_POST['mb_id']) ? trim($_POST['mb_id']) : '';
$mb_name = isset($_POST['mb_name']) ? trim($_POST['mb_name']) : '';
$mb_tel = isset($_POST['mb_tel']) ? trim($_POST['mb_tel']) : '';
$mb_email = isset($_POST['mb_email']) ? trim($_POST['mb_email']) : '';
$mb_password = isset($_POST['mb_password']) ? trim($_POST['mb_password']) : '';
$birth_date = isset($_POST['birth_date']) ? trim($_POST['birth_date']) : '';

// Simple validation
if (empty($mb_id) || empty($mb_name) || empty($mb_tel) || empty($mb_password) || empty($birth_date)) {
    echo "<script>alert('모든 항목을 입력해주세요.'); history.back();</script>";
    exit;
}

// Hash the password for security
$hashed_password = password_hash($mb_password, PASSWORD_DEFAULT);

// Prepare SQL query to insert or update the user data with approved=1
$query = "INSERT INTO users (mb_id, mb_name, mb_tel, mb_email, mb_password, birth_date, approved) 
          VALUES (?, ?, ?, ?, ?, ?, 1)
          ON DUPLICATE KEY UPDATE 
          mb_name = ?, mb_tel = ?, mb_email = ?, mb_password = ?, birth_date = ?";

// Prepare the statement using mysqli
$stmt = $conn->prepare($query);

// Check if the statement was prepared successfully
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Bind parameters (bind_param expects types: 's' for string, 'i' for integer, etc.)
$stmt->bind_param(
    'sssssssssss',  // Types for the parameters: 10 strings
    $mb_id, $mb_name, $mb_tel, $mb_email, $hashed_password, $birth_date, 
    $mb_name, $mb_tel, $mb_email, $hashed_password, $birth_date
);

// Execute the query
if ($stmt->execute()) {
    // Redirect to the login page or a success message
    echo "<script>alert('회원가입이 완료되었습니다.'); window.location.href = 'login.php';</script>";
    exit;
} else {
    echo "An error occurred during registration: " . $stmt->error;
}

// Close the statement and the connection
$stmt->close();
$conn->close();
?>
