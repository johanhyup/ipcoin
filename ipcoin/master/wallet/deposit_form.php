<?php
require_once dirname(__DIR__) . '/../config.php';

require_once __DIR__ . '/frames/header.php'; 
?>

<div class="container-fluid">
    <h1>입금하기</h1>
    <form id="depositForm">
        <div class="mb-3">
            <label for="coinName" class="form-label">코인 이름:</label>
            <input type="text" id="coinName" name="coinName" class="form-control" value="IP" readonly>
        </div>
        <div class="mb-3">
            <label for="depositAddress" class="form-label">입금 주소:</label>
            <input type="text" id="depositAddress" name="depositAddress" class="form-control" readonly>
        </div>
        <div class="mb-3">
            <label for="depositAmount" class="form-label">입금 금액:</label>
            <input type="number" step="0.00000001" id="depositAmount" name="depositAmount" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">입금하기</button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const userId = params.get("user_id");

    if (!userId) {
        alert("잘못된 이유: user_id가 전달되지 않았습니다.");
        return;
    }

    // 사용자 정보 검색
    fetch(`/master/service/get_users.php`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.users.find(u => u.user_id == userId);
                if (user && user.wallet_address) {
                    document.getElementById("depositAddress").value = user.wallet_address;
                } else {
                    alert("사용자의 지갑 주소를 검색할 수 없습니다.");
                }
            } else {
                alert("사용자 목록을 불러올 수 없습니다.");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
            alert("서버 요청 중 오류가 발생했습니다.");
        });

    // 입금 요청 제출
    document.getElementById("depositForm").addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = {
            user_id: userId,
            coin_name: document.getElementById("coinName").value,
            deposit_address: document.getElementById("depositAddress").value,
            amount: parseFloat(document.getElementById("depositAmount").value),
        };

        fetch("/master/wallet/deposit.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("입금이 성공적으로 진행되었습니다.");
                window.opener.location.reload();
                window.close();
            } else {
                alert("입금 실패: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error processing deposit:", error);
            alert("입금 처리 중 오류가 발생했습니다.");
        });
    });
});
</script>

<?php // 페이지 끝: footer.php 불러오기
require_once __DIR__ . '/frames/footer.php';
?>
