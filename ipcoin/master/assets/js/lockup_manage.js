document.addEventListener("DOMContentLoaded", () => {
    // 락업하기 폼 제출 이벤트
    const lockupForm = document.getElementById("lockupForm");
    if (lockupForm) {
        lockupForm.addEventListener("submit", (e) => {
            e.preventDefault();

            const formData = {
                user_id: document.getElementById("lockupUserId").value,
                amount: document.getElementById("lockupAmount").value,
                end_date: document.getElementById("lockupEndDate").value
            };

            fetch("/service/lockup_coin.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("코인이 성공적으로 락업되었습니다.");
                } else {
                    alert(`오류: ${data.message}`);
                }
            })
            .catch(err => {
                alert("서버와의 통신 중 오류가 발생했습니다.");
                console.error("Error:", err);
            });
        });
    }

    // 락업 해제 폼 제출 이벤트
    const unlockForm = document.getElementById("unlockForm");
    if (unlockForm) {
        unlockForm.addEventListener("submit", (e) => {
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
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("코인의 락업이 성공적으로 해제되었습니다.");
                } else {
                    alert(`오류: ${data.message}`);
                }
            })
            .catch(err => {
                alert("서버와의 통신 중 오류가 발생했습니다.");
                console.error("Error:", err);
            });
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const userTableBody2 = document.getElementById("userTableBody2");

    function fetchLockupData() {
        fetch("/master/service/get_coinlockup.php")
            .then((response) => {
                if (!response.ok) throw new Error("Network response was not ok");
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    renderLockupTable(data.data);
                } else {
                    userTableBody2.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center text-danger">데이터를 불러오는 데 실패했습니다: ${data.message}</td>
                        </tr>
                    `;
                }
            })
            .catch((error) => {
                console.error("Error fetching lockup data:", error);
                userTableBody2.innerHTML = `
                    <tr>
                        <td colspan="9" class="text-center text-danger">서버 오류가 발생했습니다.</td>
                    </tr>
                `;
            });
    }

    function renderLockupTable(lockupData) {
        userTableBody2.innerHTML = "";
        lockupData.forEach((item) => {
            const row = `
                <tr>
                    <td>${item.coin_id}</td>
                    <td>${item.user_id}</td>
                    <td>${item.coin_name}</td>
                    <td>${item.user_id_name}</td>
                    <td>${item.user_name}</td>
                    <td>${Number(item.locked_amount).toLocaleString()} IP</td>
                    <td>${item.start_date}</td>
                    <td>${item.end_date}</td>
                    <td>${item.status}</td>
                </tr>
            `;
            userTableBody2.insertAdjacentHTML("beforeend", row);
        });
    }

    fetchLockupData();
});


document.addEventListener("DOMContentLoaded", () => {
    // 락업 기간 변경 버튼 이벤트
    document.getElementById("updateLockupButton").addEventListener("click", () => {
        const periodInput = document.getElementById("lockupPeriodInput").value;

        if (!periodInput || periodInput <= 0) {
            alert("올바른 기간을 입력해주세요 (1 이상의 숫자).");
            return;
        }

        // 서버에 요청 보내기
        fetch("/master/service/update_lockup_period.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ new_period: periodInput })
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert("락업 기간이 성공적으로 변경되었습니다.");
                location.reload(); // 페이지 새로고침
            } else {
                alert("락업 기간 변경 실패: " + data.message);
            }
        })
        .catch((error) => {
            console.error("Error updating lockup period:", error);
            alert("서버 오류가 발생했습니다.");
        });
    });
});
