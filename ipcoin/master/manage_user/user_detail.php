<?php
// user_detail.php
// : userlist_view.php에서 Ajax로 불러오는 상세정보 페이지 (HTML 조각)

// DB 연결이 필요한 경우, 상단에 require_once... (이미 userlist_view.php에서 연결했다면, 
//  여기도 같은 conn을 쓸 수 있도록 전역화하거나, 별도 연결)

// 일단 이 예시에선 간단히 다음 형태로 가정:
require_once dirname(__DIR__) . '/../config.php';
$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
if (!$userId) {
    echo "<p>유효하지 않은 회원.</p>";
    exit;
}

// 유저 정보 조회 (예시)
$stmt = $conn->prepare("SELECT mb_id, mb_name, mb_email, mb_tel FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "<p>존재하지 않는 회원.</p>";
    exit;
}
?>

<!-- === 상세정보 UI === -->
<div>
  <h4>회원상세: <?=htmlspecialchars($user['mb_name'])?> (ID: <?=htmlspecialchars($user['mb_id'])?>)</h4>
  <ul>
    <li>이메일: <?=htmlspecialchars($user['mb_email'])?></li>
    <li>전화번호: <?=htmlspecialchars($user['mb_tel'])?></li>
  </ul>
</div>

<!-- 하단 모달 footer 대신: 여기서 코인입금 버튼 배치 -->
<div class="mt-3 text-right">
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#depositModal">
    코인 입금
  </button>
  <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
</div>

<!-- ========== (두 번째) 입금 모달 ========== -->
<div class="modal fade" id="depositModal" tabindex="-1" role="dialog" 
     aria-labelledby="depositModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">코인 입금</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- 입금수량 입력 -->
        <div class="form-group">
          <label for="depositAmount">입금 수량</label>
          <input type="number" class="form-control" id="depositAmount" step="0.00000001" placeholder="0.00000000" />
        </div>
        <!-- 회원 id를 숨김으로 보관 -->
        <input type="hidden" id="depositUserId" value="<?=$userId?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
        <button type="button" class="btn btn-success" onclick="doDeposit()">확인</button>
      </div>
    </div>
  </div>
</div>

<script>
function doDeposit() {
  const userId = document.getElementById('depositUserId').value;
  const amount = parseFloat(document.getElementById('depositAmount').value);
  if (!amount || amount <= 0) {
    alert('입금 수량을 올바르게 입력하세요.');
    return;
  }

  // AJAX POST -> /master/wallet/manual_deposit.php (예시)
  fetch('/master/wallet/manual_deposit.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ user_id: userId, amount: amount })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('입금 완료: ' + data.message);
      // 모달 닫기
      $('#depositModal').modal('hide');
      // 필요하다면, 상세 모달 전체도 닫거나, 목록 리프레시
      // $('#userDetailModal').modal('hide');
      // loadUserList(1);
    } else {
      alert('입금 실패: ' + data.message);
    }
  })
  .catch(err => {
    console.error(err);
    alert('입금 처리 중 오류 발생');
  });
}
</script>