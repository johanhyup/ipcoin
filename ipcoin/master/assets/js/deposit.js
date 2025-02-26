document.addEventListener("DOMContentLoaded", () => {
    const depositButton = document.getElementById("depositButton");
    const depositTableBody = document.getElementById("depositTableBody");


 
    function renderDepositTable(deposits) {
        depositTableBody.innerHTML = ""; // 기존 데이터 초기화
        deposits.forEach((deposit, index) => {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${deposit.user_id_name}</td>
                    <td>${deposit.nickname}</td>
                    <td>${deposit.coin_name}</td>
                    <td>${deposit.deposit_address || "N/A"}</td>
                    <td>${Number(deposit.amount).toLocaleString()}</td>
                    <td>${deposit.status}</td>
                    <td>${deposit.created_at}</td>
                    <td>
                        ${
                            deposit.status === "pending"
                                ? `<button class="approve-btn" data-id="${deposit.id}">승인</button>`
                                : "완료"
                        }
                    </td>
                </tr>
            `;
            depositTableBody.insertAdjacentHTML("beforeend", row);
        });

        // 승인 버튼 이벤트 추가
        document.querySelectorAll(".approve-btn").forEach((button) => {
            button.addEventListener("click", () => {
                const depositId = button.dataset.id;
                approveDeposit(depositId);
            });
        });
    }

    function approveDeposit(depositId) {
        if (!confirm("해당 입금을 승인하시겠습니까?")) return;

        fetch("/master/wallet/approve_deposit.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ deposit_id: depositId }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("입금이 승인되었습니다.");
                 //   fetchDepositList(); // 테이블 새로고침
                } else {
                    alert(`입금 승인 실패: ${data.message}`);
                }
            })
            .catch((error) => {
                console.error("Error approving deposit:", error);
                alert("입금 승인 중 오류가 발생했습니다.");
            });
    }
});


