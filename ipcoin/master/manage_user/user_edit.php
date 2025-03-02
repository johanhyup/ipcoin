<?php
require_once dirname(__DIR__) . '/../config.php';

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 사용자 정보 가져오기
$stmt = $conn->prepare("SELECT mb_id, mb_name, mb_email, mb_tel, grade, wallet_address, total_balance, available_balance, locked_balance FROM users LEFT JOIN wallet ON users.id = wallet.user_id WHERE users.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "사용자를 찾을 수 없습니다.";
    exit;
}

// 페이지 시작: header.php 불러오기
require_once dirname(__DIR__) . '/../frames/header.php';
?>

<!-- 여기에 페이지별 내용 (본문) -->
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card-header">사용자 정보 수정</div>
        <div class="card-body">
            <form id="update-form" action="/master/manage_user/update_user.php" method="POST">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
                <div class="form-group">
                    <label>아이디:</label>
                    <input type="text" name="mb_id" id="mb_id" class="form-control" value="<?= htmlspecialchars($user['mb_id']) ?>" required>
                    <span id="id-error" class="error"></span>
                </div>
                <div class="form-group">
                    <label>이름:</label>
                    <input type="text" name="mb_name" id="mb_name" class="form-control" value="<?= htmlspecialchars($user['mb_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>이메일:</label>
                    <input type="email" name="mb_email" id="mb_email" class="form-control" value="<?= htmlspecialchars($user['mb_email']) ?>" required>
                    <span id="email-error" class="error"></span>
                </div>
                <div class="form-group">
                    <label>연락처:</label>
                    <input type="text" name="mb_tel" id="mb_tel" class="form-control" value="<?= htmlspecialchars($user['mb_tel']) ?>" required>
                    <span id="tel-error" class="error"></span>
                </div>
                <div class="form-group">
                    <label>등급:</label>
                    <input type="text" name="grade" id="grade" class="form-control" value="<?= htmlspecialchars($user['grade']) ?>" required>
                </div>
                <div class="form-group">
                    <label>지갑 주소:</label>
                    <input type="text" name="wallet_address" id="wallet_address" class="form-control" value="<?= htmlspecialchars($user['wallet_address']) ?>">
                </div>
                <div class="form-group">
                    <label>총 잔액:</label>
                    <input type="text" name="total_balance" id="total_balance" class="form-control" value="<?= htmlspecialchars($user['total_balance']) ?>" required>
                    <span id="balance-error" class="error"></span>
                </div>
                <div class="form-group">
                    <label>사용 가능 잔액:</label>
                    <input type="text" name="available_balance" id="available_balance" class="form-control" value="<?= htmlspecialchars($user['available_balance']) ?>" required>
                </div>
                <div class="form-group">
                    <label>잠금 잔액:</label>
                    <input type="text" name="locked_balance" id="locked_balance" class="form-control" value="<?= htmlspecialchars($user['locked_balance']) ?>" required>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button type="submit" class="btn btn-primary">저장</button>
                    <div class="reset-section">
                        <button type="button" class="btn btn-secondary" id="reset-button">비밀번호 초기화</button>
                        <span id="reset-error" class="error"></span>
                    </div>
                </div>
            </form>
        </div><!-- card-body -->
    </div><!-- card -->
</div><!-- container-fluid -->

<script>
document.getElementById('reset-button').addEventListener('click', () => {
    const mbId = document.getElementById('mb_id').value.trim();
    const errorSpan = document.getElementById('reset-error');
    if (!mbId) {
        errorSpan.textContent = '아이디를 입력해주세요.';
        return;
    }
    errorSpan.textContent = ''; // 초기화
    fetch('/master/manage_user/reset_password.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `mb_id=${mbId}`,
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('1111로 비밀번호가 초기화되었습니다.');
        } else {
            alert(result.message || '비밀번호 초기화에 실패했습니다.');
        }
    })
    .catch(error => {
        console.error('Error resetting password:', error);
        alert('비밀번호 초기화 중 오류가 발생했습니다.');
    });
});

const form = document.getElementById('update-form');
form.addEventListener('submit', function(event) {
    const idInput = document.getElementById('mb_id');
    const emailInput = document.getElementById('mb_email');
    const telInput = document.getElementById('mb_tel');
    const totalBalanceInput = parseFloat(document.getElementById('total_balance').value || 0);
    const availableBalanceInput = parseFloat(document.getElementById('available_balance').value || 0);
    const lockedBalanceInput = parseFloat(document.getElementById('locked_balance').value || 0);
    let isValid = true;
    // 아이디 유효성 검사
    const idPattern = /^[a-zA-Z0-9]+$/;
    if (!idPattern.test(idInput.value)) {
        document.getElementById('id-error').textContent = '아이디는 영문 및 숫자만 가능합니다.';
        isValid = false;
    } else {
        document.getElementById('id-error').textContent = '';
    }
    // 이메일 유효성 검사
    if (!emailInput.checkValidity()) {
        document.getElementById('email-error').textContent = '유효한 이메일 형식이 아닙니다.';
        isValid = false;
    } else {
        document.getElementById('email-error').textContent = '';
    }
    // 연락처 유효성 검사
    const telPattern = /^0\d{9,10}$/;
    if (!telPattern.test(telInput.value)) {
        document.getElementById('tel-error').textContent = '연락처는 0으로 시작하는 10~11자리 숫자여야 합니다.';
        isValid = false;
    } else {
        document.getElementById('tel-error').textContent = '';
    }
    // 잔액 유효성 검사
    if (totalBalanceInput !== availableBalanceInput + lockedBalanceInput) {
        document.getElementById('balance-error').textContent = '총 잔액은 사용 가능 잔액과 잠금 잔액의 합이어야 합니다.';
        isValid = false;
    } else {
        document.getElementById('balance-error').textContent = '';
    }
    if (!isValid) {
        event.preventDefault();
    }
});
</script>

<?php
// 페이지 끝: footer.php 불러오기
require_once dirname(__DIR__) . '/../frames/footer.php';
?>
