document.addEventListener("DOMContentLoaded", () => {
    // DOM이 완전히 로드된 후 실행
    const closingPriceElement = document.getElementById("closing_price_display");
    const totalWithdrawElement = document.getElementById("total_withdraw_display");

    if (!closingPriceElement || !totalWithdrawElement) {
        console.error("One or more required elements are missing in the DOM.");
        return; // 요소가 없으면 실행 중단
    }

    // 총 출금액 업데이트 함수 실행
    updateTotalWithdrawKRW();
    updateTotalDepositKRW()
    fetchUserStats();
    fetchUserList();
    fetchUsers();
});
function fetchTotalDeposit() {
    fetch('/service/total_deposit.php')
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            totalDeposit = parseFloat(res.total_deposit);
        } else {
            throw new Error(res.message);
        }
    });

}
function fetchTotalWithdraw() {
    return fetch('/service/total_withdraw.php')
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                totalWithdraw = parseFloat(res.total_withdraw);
            } else {
                throw new Error(res.message);
            }
        });
}
function updateTotalDepositKRW() {
    Promise.all([fetchClosingPrice(), fetchTotalDeposit()])
        .then(([ ]) => {
            const totalDepositKRW = totalDeposit * closingPrice; // 입금액 x Closing Price
            document.getElementById("total_deposit_display").innerHTML = 
                `총 입금액<br>${totalDepositKRW.toLocaleString()} KRW`;
        })

}


function updateTotalWithdrawKRW() {
    Promise.all([fetchClosingPrice(), fetchTotalDeposit()])
        .then(() => {
            const totalWithdrawKRW = totalWithdraw * closingPrice; // 총 출금량 x closing price
            document.getElementById("total_withdraw_display").innerHTML = 
                `총 출금액<br>${totalWithdrawKRW.toLocaleString()} KRW`;
        });
}


function updateTotalWithdrawKRW() {
    Promise.all([fetchClosingPrice(), fetchTotalWithdraw()])
        .then(() => {
            const totalWithdrawKRW = totalWithdraw * closingPrice; // 총 출금량 x closing price
            document.getElementById("total_withdraw_display").innerHTML = 
                `총 출금액<br>${totalWithdrawKRW.toLocaleString()} KRW`;
        });
}

function fetchClosingPrice() {
    const options = { method: 'GET', headers: { accept: 'application/json' } };

    return fetch('https://api.bithumb.com/public/ticker/IP_KRW', options)
        .then(res => res.json())
        .then(res => {
            if (res && res.data && res.data.closing_price) {
                closingPrice = parseFloat(res.data.closing_price);
                document.getElementById("closing_price_display").innerHTML = 
                    `현재 IP가격 <br/> ${closingPrice.toLocaleString()} KRW`;
            } else {
                throw new Error("Closing price not found in response");
            }
        });
}




// stats.php에서 총 회원, 관리자, 신규회원 가져오기
function fetchUserStats() {
    fetch('/service/stats.php')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // 각 요소에 값 업데이트
                document.querySelector("#total_users").innerHTML = `총 회원<br>${data.total_users}`;
                document.querySelector("#total_admins").innerHTML = `총 관리자<br>${data.total_admins}`;
                document.querySelector("#new_users").innerHTML = `신규회원<br>${data.new_users}`;
            } else {
                console.error("Error fetching stats:", data.message);
            }
        })
        .catch(err => console.error("Error:", err));
}





function fetchUserList() {
    fetch('/master/manage_user/user_list.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderUserList(data.data);
            } else {
                console.error("Error:", data.message);
            }
        })
        .catch(error => console.error("AJAX Error:", error));
}

function renderUserList(users) {
    const tableBody = document.querySelector("#userTableBody");
    tableBody.innerHTML = ""; // 초기화

    users.forEach((user, index) => {
        const row = `
            <tr>
                <td>${index + 1}</td>
                <td>${user.manager_name || 'N/A'}</td>
                <td>${user.user_id_name}</td>
                <td>${user.nickname || 'N/A'}</td>
                <td>${user.email || 'N/A'}</td>
                <td>${user.phone || 'N/A'}</td>
                <td>${user.wallet_address || 'N/A'}</td>
                <td>${Number(user.total_balance).toLocaleString()} RAY</td>
                <td>${Number(user.locked_balance).toLocaleString()} RAY</td>
                <td>${Number(user.withdrawable_balance).toLocaleString()} RAY</td>
                <td>${user.created_at}</td>
                <td><button class="block-btn" data-id="${user.user_id}">블락</button></td>
            </tr>
        `;
        tableBody.insertAdjacentHTML("beforeend", row);
    });

    // 이벤트 추가 (블락 버튼)
    document.querySelectorAll(".block-btn").forEach(button => {
        button.addEventListener("click", function () {
            alert(`사용자 ID ${this.dataset.id}를 블락 처리합니다.`);
        });
    });
}


document.addEventListener("DOMContentLoaded", () => {
    const searchButton = document.getElementById("searchButton");
    const searchInput = document.getElementById("searchInput");
    const userTableBody = document.getElementById("userTableBody");
    

    // 검색 버튼 클릭 이벤트
    searchButton.addEventListener("click", () => {
        const searchValue = searchInput.value.trim();
        fetchUserList(searchValue);
    });

    // 사용자 목록 불러오기
    function fetchUserList(search = "") {
        fetch(`/master/manage_user/user_list.php?search=${encodeURIComponent(search)}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    let rows = "";
                    data.data.forEach((user, index) => {
                        rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${user.manager_name || "-"}</td>
                                <td>${user.user_id_name}</td>
                                <td>${user.nickname}</td>
                                <td>${user.email}</td>
                                <td>${user.phone}</td>
                                <td>${user.wallet_address || "-"}</td>
                                <td>${user.total_balance}</td>
                                <td>${user.locked_balance}</td>
                                <td>${user.withdrawable_balance}</td>
                                <td>${user.created_at}</td>
                                <td><button>블락</button></td>
                            </tr>
                        `;
                    });
                    userTableBody.innerHTML = rows;
                } else {
                    userTableBody.innerHTML = `<tr><td colspan="12" class="text-center">No results found</td></tr>`;
                }
            })
            .catch((error) => console.error("Error:", error));
    }

    // 페이지 로드 시 전체 목록 불러오기
    fetchUserList();
});





    function fetchUsers() {
        fetch("/master/service/get_users.php")
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateUserDropdown(data.users);
                } else {
                    alert("유저 목록을 가져오는 데 실패했습니다.");
                }
            })
            .catch(error => console.error("Error fetching user list:", error));
    }

    function populateUserDropdown(users) {
        const lockupUserSelect = document.getElementById("lockupUserId");
        const unlockUserSelect = document.getElementById("unlockUserId");

        users.forEach(user => {
            const option = document.createElement("option");
            option.value = user.id; // 실제 user_id
            option.textContent = `${user.mb_id} (${user.mb_name})`; // 아이디와 이름 표시
            lockupUserSelect.appendChild(option.cloneNode(true)); // 락업용
            unlockUserSelect.appendChild(option); // 해제용
        });
    }


    // 사용자 존재 여부 확인
function checkUserExists(mbId, callback) {
    fetch('/master/manage_user/check_user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `mb_id=${mbId}`,
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('네트워크 응답에 문제가 있습니다.');
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                callback(); // 유효하면 콜백 실행
            } else {
                alert(result.message); // 사용자 없음
            }
        })
        .catch(error => {
            console.error('Error checking user existence:', error);
            alert('사용자 유효성 검사 중 오류가 발생했습니다.');
        });
}

// 승인/블락 상태 업데이트
function updateApprovalStatus(mbId, approved) {
    fetch('/master/manage_user/update_approval.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `mb_id=${mbId}&approved=${approved}`,
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('네트워크 응답에 문제가 있습니다.');
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                alert(approved === 0 ? '사용자가 블락되었습니다.' : '사용자가 승인되었습니다.');
                location.reload(); // 새로고침
            } else {
                alert(result.message); // 에러 메시지
            }
        })
        .catch(error => {
            console.error('Error updating approval status:', error);
            alert('사용자 상태 변경 중 오류가 발생했습니다.');
        });
}