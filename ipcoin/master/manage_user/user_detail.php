<?php
/**
 * user_detail.php
 * - 특정 user_id에 대한 상세 정보를 HTML 형태로 반환
 * - 모달 내부에 표시
 */
session_start();
require_once dirname(__DIR__, 2) . '/config.php';

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
if($user_id <= 0) {
  echo "<p>잘못된 접근입니다.</p>";
  exit;
}

// DB 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}

// 유저 정보 가져오기
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
if(!$result || $result->num_rows === 0) {
  echo "<p>존재하지 않는 회원입니다.</p>";
  $conn->close();
  exit;
}
$user = $result->fetch_assoc();
$conn->close();

// HTML 출력
?>
<div>
  <p><label>아이디: </label> <?php echo htmlspecialchars($user['mb_id']); ?></p>
  <p><label>이름: </label> <?php echo htmlspecialchars($user['mb_name']); ?></p>
  <p><label>이메일: </label> <?php echo htmlspecialchars($user['mb_email']); ?></p>
  <p><label>전화번호: </label> <?php echo htmlspecialchars($user['mb_tel']); ?></p>
  <p><label>가입일: </label> <?php echo htmlspecialchars($user['created_at']); ?></p>
  <p><label>승인여부: </label> <?php echo $user['approved'] ? '승인됨' : '미승인'; ?></p>
  <!-- 필요하다면 코인 보유량, 주소, 기타 정보도 추가 -->

  <hr>
  <!-- 비밀번호 초기화 버튼 -->
  <button class="btn btn-warning btn-sm" onclick="resetPassword(<?php echo $user_id; ?>)">비밀번호 초기화</button>
  <!-- 수정 버튼 -->
  <button class="btn btn-info btn-sm" onclick="editUser(<?php echo $user_id; ?>)">회원 정보 수정</button>
</div>

<script>
// 비밀번호 초기화
function resetPassword(userId) {
  if(!confirm('정말 비밀번호를 초기화하시겠습니까?')) return;
  
  $.ajax({
    url: '/master/manage_user/reset_password.php',
    method: 'POST',
    data: { user_id: userId },
    dataType: 'json',
    success: function(res) {
      if(res.success) {
        alert('비밀번호가 초기화되었습니다.');
      } else {
        alert('실패: ' + res.message);
      }
    },
    error: function(err) {
      console.error(err);
      alert('비밀번호 초기화 중 오류가 발생했습니다.');
    }
  });
}

// 회원 정보 수정
function editUser(userId) {
  // 수정 폼으로 이동하거나, 추가 모달을 띄우는 등
  window.open('/master/manage_user/user_edit.php?user_id=' + userId,
    'editUser',
    'width=600,height=600,resizable=yes,scrollbars=yes'
  );
}
</script>