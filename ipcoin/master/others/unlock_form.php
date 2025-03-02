<?php
require_once dirname(__DIR__) . '/../config.php';

// 페이지 시작: header.php 불러오기
require_once __DIR__ . '/frame/header.php';
?>

<!-- 여기에 페이지별 내용 (본문) -->
<div class="container-fluid mt-5">
  <div class="card">
    <div class="card-header">코인 락업 해제</div>
    <div class="card-body">
      <form id="unlockForm">
        <label for="unlockUserId">유저 (ID):</label>
        <select id="unlockUserId" name="unlockUserId" required>
          <option value="">유저를 선택하세요</option>
          <?php
          $result = $conn->query("SELECT id, mb_id FROM users");
          while ($user = $result->fetch_assoc()) {
              echo "<option value='{$user['id']}'>{$user['mb_id']}</option>";
          }
          ?>
        </select>

        <label for="unlockAmount">락업 해제할 코인 양:</label>
        <input type="number" step="0.00000001" id="unlockAmount" name="unlockAmount" required>

        <button type="submit">락업 해제</button>
      </form>
    </div><!-- card-body -->
  </div><!-- card -->
</div><!-- container-fluid -->

<script>
document.getElementById("unlockForm").addEventListener("submit", (e) => {
    e.preventDefault();

    const formData = {
        user_id: document.getElementById("unlockUserId").value,
        amount: document.getElementById("unlockAmount").value
    };

    fetch("/service/unlock_coin.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData)
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            alert("코인의 락업이 성공적으로 해제되었습니다.");
            window.opener.location.reload(); // 부모 페이지 새로고침
            window.close(); // 성공 후 창 닫기
        } else {
            alert(`락업 해제 실패: ${data.message}`);
        }
    })
    .catch((error) => console.error("Error:", error));
});
</script>

<?php
// 페이지 끝: footer.php 불러오기
require_once __DIR__ . '/frame/footer.php';
?>
