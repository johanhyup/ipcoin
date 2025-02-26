<?php
require_once dirname(__DIR__) . '/../config.php'; // 데이터베이스 연결

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $mb_id = trim($_POST['mb_id']);
    $mb_name = trim($_POST['mb_name']);
    $mb_email = trim($_POST['mb_email']);
    $mb_tel = trim($_POST['mb_tel']);
    $grade = trim($_POST['grade']);
    $wallet_address = trim($_POST['wallet_address']);
    $total_balance = floatval($_POST['total_balance']);
    $available_balance = floatval($_POST['available_balance']);
    $locked_balance = floatval($_POST['locked_balance']);

    // 유효성 검사: 금액 음수 확인
    if ($total_balance < 0 || $available_balance < 0 || $locked_balance < 0) {
        echo "<script>alert('금액은 음수일 수 없습니다.'); window.close();</script>";
        exit;
    }

    // 유효성 검사: 총 금액 = 사용 가능 금액 + 락업 금액 확인
    if ($total_balance !== ($available_balance + $locked_balance)) {
        echo "<script>alert('총 금액은 사용 가능 금액과 락업 금액의 합이어야 합니다.'); window.close();</script>";
        exit;
    }

    // 유효성 검사: mb_id 중복 확인
    $stmt = $conn->prepare("SELECT id FROM users WHERE mb_id = ? AND id != ?");
    $stmt->bind_param("si", $mb_id, $user_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "<script>alert('아이디가 이미 사용 중입니다.'); window.close();</script>";
        $stmt->close();
        exit;
    }
    $stmt->close();

    // 유효성 검사: 지갑 주소 중복 확인
    if (!empty($wallet_address)) {
        $stmt = $conn->prepare("SELECT id FROM wallet WHERE wallet_address = ? AND user_id != ?");
        $stmt->bind_param("si", $wallet_address, $user_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            echo "<script>alert('지갑 주소가 이미 사용 중입니다.'); window.close();</script>";
            $stmt->close();
            exit;
        }
        $stmt->close();
    }

    // 사용자 정보 업데이트
    $stmt = $conn->prepare("UPDATE users u LEFT JOIN wallet w ON u.id = w.user_id
        SET u.mb_id = ?, u.mb_name = ?, u.mb_email = ?, u.mb_tel = ?, u.grade = ?, 
        w.wallet_address = ?, w.total_balance = ?, w.available_balance = ?, w.locked_balance = ? 
        WHERE u.id = ?");
    $stmt->bind_param(
        "sssssssssi",
        $mb_id, $mb_name, $mb_email, $mb_tel, $grade, $wallet_address,
        $total_balance, $available_balance, $locked_balance, $user_id
    );

    if ($stmt->execute()) {
        echo "<script>alert('정보가 성공적으로 업데이트되었습니다.'); window.close(); window.opener.location.reload();</script>";
    } else {
        echo "<script>alert('정보 업데이트에 실패했습니다: " . $stmt->error . "'); window.close();</script>";
    }
    $stmt->close();
}

$conn->close();
