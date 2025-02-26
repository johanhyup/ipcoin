let closingPrice = 0; // 전역 변수 선언
let totalWithdraw = 0; // withdraw_requests 테이블의 총 출금량
function aboutcoin() {
    $.ajax({
        type: "get",
        dataType: "html",
        url: '/service/aboutcoin.php',
        success: function (listdata) {
            console.log("Aboutcoin.php Response:", listdata); // 디버깅용

            // Fetch the Bithumb API data
            const options = { method: 'GET', headers: { accept: 'application/json' } };

            fetch('https://api.bithumb.com/public/ticker/RAY_KRW', options)
                .then(res => res.json())
                .then(res => {
                    console.log("Bithumb API Response:", res); // 응답 확인

                    // closing_price 값 가져와 전역 변수에 저장
                    if (res && res.data && res.data.closing_price) {
                        closingPrice = parseFloat(res.data.closing_price);
                        console.log("Closing Price (Exchange Rate):", closingPrice);

                        // #aboutcoin에 closingPrice 업데이트
                        $("#aboutcoin").text(closingPrice.toLocaleString() + "");

                        // closingPrice를 가져온 후 getbalance 함수 호출
                        getbalance();
                    } else {
                        console.error("Closing Price not found in API response.");
                        $("#aboutcoin").text("Error");
                    }
                })
                .catch(err => {
                    console.error("Error fetching closing price:", err);
                    $("#aboutcoin").text("Error");
                });
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
            $("#aboutcoin").text("Error");
        }
    });
}

// getbalance 함수: balance 값 가져오기 및 KRW 환산
function getbalance() {
    $.ajax({
        url: "/service/get_balance.php",
        method: "GET",
        dataType: "json",
        success: function (response) {
            if (response.success) {
                // 서버에서 받은 값을 숫자로 변환
                const totalBalance = parseFloat(response.total_balance) || 0;
                const lockedBalance = parseFloat(response.locked_balance) || 0;

                // 값 업데이트
                $("#total_balance").text(totalBalance.toFixed(2));
                $("#locked_balance").text(lockedBalance.toFixed(8));

                // closingPrice를 이용해 KRW 환산
                const totalKrw = (totalBalance * closingPrice).toFixed(2);
                const lockedKrw = (lockedBalance * closingPrice).toFixed(2);

                // KRW 값에 콤마 추가
                const totalKrwFormatted = Number(totalKrw).toLocaleString();
                const lockedKrwFormatted = Number(lockedKrw).toLocaleString();

                // 화면에 업데이트
                $("#total_krw").text("= " + totalKrwFormatted + " KRW");
                $("#locked_krw").text("= " + lockedKrwFormatted + " KRW");

                console.log("Total Balance:", totalBalance, "Locked Balance:", lockedBalance, "Closing Price:", closingPrice);
            } else {
                console.error("Failed to fetch balance data");
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
        }
    });
}



function transaction_view(user_idx) {
    $.ajax({
        type: "POST",
        url: "/service/transaction_view.php",
        data: { user_idx: user_idx },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                let rows = "";
                response.transactions.forEach((transaction, index) => {
                    rows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>
                                <span id="tid_${index}" style="display:none;">${transaction.id}</span>
                                <button class="btn btn-link p-0 text-primary" onclick="showTid(${index})">TID 보기</button>
                            </td>
                            <td>${transaction.transaction_date}</td>
                            <td>${Number(transaction.amount).toLocaleString()}</td>
                            <td>
                                ${transaction.status === "Completed"
                                    ? '<span class="badge bg-success">Completed</span>'
                                    : '<span class="badge bg-danger">Cancelled</span>'}
                            </td>
                        </tr>
                    `;
                });
                $("#transaction_view tbody").html(rows);
            } else {
                console.error(response.message);
                $("#transaction_view tbody").html(
                    `<tr><td colspan="5" class="text-center">No transactions found</td></tr>`
                );
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
            $("#transaction_view tbody").html(
                `<tr><td colspan="5" class="text-center text-danger">Error loading transactions</td></tr>`
            );
        }
    });
}

// TID 표시 함수
function showTid(index) {
    const tidElement = document.getElementById(`tid_${index}`);
    if (tidElement.style.display === "none") {
        tidElement.style.display = "inline"; // TID 보이기
    } else {
        tidElement.style.display = "none"; // TID 숨기기
    }
}



function wallet_view() {
    $.ajax({
        type: "GET",
        url: "/service/wallet_balance.php", // 수정된 PHP 파일 경로
        dataType: "json",
        success: function (response) {
            if (response.success) {
                // 데이터 가져오기 성공
                console.log("Wallet Data:", response);

                // 데이터를 변수에 저장
                const walletData = {
                    userName: response.user_name,
                    totalBalance: response.total_balance,
                    lockedBalance: response.locked_balance,
                    withdrawableBalance: response.withdrawable_balance,
                    coins: response.coins,
                    lastUpdated: response.last_updated
                };

                // HTML 요소에 매핑 (jQuery 활용)
                $("#userName").text(walletData.userName); // 사용자 이름 업데이트
                $("#totalBalance2").text(walletData.totalBalance.toLocaleString()); // 총 잔액
                $("#lockedBalance2").text(walletData.lockedBalance.toLocaleString()); // 잠금된 잔액
                $("#withdrawableBalance").text(walletData.withdrawableBalance.toLocaleString()); // 출금 가능 잔액
                $("#coins").text(walletData.coins.join(", ")); // 보유 코인 목록
                $("#lastUpdated").text(walletData.lastUpdated); // 마지막 업데이트 시간

                // 필요하면 다른 함수 호출
                handleWalletData(walletData);
            } else {
                // 데이터 가져오기 실패
                console.error("Error:", response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
        }
    });
}





$(document).ready(function () {
    /**
     * create_wallet 함수
     * 지갑 생성 요청을 서버로 보내고 결과를 처리
     */
    function create_wallet() {
        $.ajax({
            url: "service/create_wallet.php", // PHP 파일 경로
            type: "POST", // HTTP 요청 메서드
            dataType: "json", // 서버 응답 타입 (JSON)
            success: function (response) {
                if (response.success) {
                    // 성공 시 화면에 메시지 표시
                    alert("지갑이 성공적으로 생성되었습니다.\n지갑 주소: " + response.wallet_address);
                    $("#wallet_address_display").text(response.wallet_address); // 화면에 지갑 주소 표시
                } else {
                    // 실패 시 에러 메시지 표시
                    alert("오류: " + response.message);
                }
            },
            error: function () {
                alert("서버 요청 중 오류가 발생했습니다.");
            }
        });
    }

    // 버튼 클릭 이벤트: create_wallet 함수 호출
    $("#create_wallet_button").click(function (e) {
        e.preventDefault(); // 기본 동작 방지
        create_wallet(); // 함수 호출
    });
});



/**
 * wallet.js - 지갑 주소 불러오기 및 복사 기능
 */
$(document).ready(function () {
    /**
     * 서버에서 지갑 주소를 불러오는 함수
     */
    function fetchWalletAddress() {
        return $.ajax({
            url: "/service/get_wallet_address.php", // 서버에서 지갑 주소를 반환하는 PHP 파일
            type: "GET",
            dataType: "json"
        });
    }

    /**
     * 지갑 주소를 가져와 화면에 표시하고 클립보드에 복사하는 함수
     */
    function showAddress() {
        fetchWalletAddress()
            .done(function (response) {
                if (response.success && response.wallet_address) {
                    const walletAddress = response.wallet_address;

                    // 화면에 지갑 주소 표시
                    $("#wallet_address_display").text("지갑 주소: " + walletAddress);

                    // Alert로 지갑 주소 출력
                    alert("지갑 주소: " + walletAddress);

                    // 클립보드에 지갑 주소 복사
                    navigator.clipboard.writeText(walletAddress)
                        .then(function () {
                            alert("지갑 주소가 클립보드에 복사되었습니다.");
                        })
                        .catch(function (err) {
                            console.error("클립보드 복사 실패:", err);
                            alert("클립보드 복사에 실패했습니다.");
                        });
                } else {
                    $("#wallet_address_display").text("지갑 주소를 불러오지 못했습니다.");
                    alert("지갑 주소를 불러오지 못했습니다.");
                }
            })
            .fail(function () {
                $("#wallet_address_display").text("서버 요청 중 오류가 발생했습니다.");
                alert("서버 요청 중 오류가 발생했습니다.");
            });
    }

    // 버튼 클릭 이벤트
    $("#show_address_btn").on("click", function (e) {
        e.preventDefault(); // 기본 동작 방지
        showAddress();
    });
});



$(document).ready(function () {
    let availableBalance = 0;
    // 출금 가능 금액 불러오기
    const fee = 0; // 수수료 20 RAY
    function loadAvailableBalance() {
        $.ajax({
            url: "/service/get_available_balance.php", // 출금 가능 금액 확인 PHP 파일
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    availableBalance = parseFloat(response.available_balance);
                    $("#inner_withdraw_balance").text("출금 가능 금액: " + response.available_balance + "IP");
                } else {
                    $("#inner_withdraw_balance").text("잔액을 불러오지 못했습니다.");
                }
            },
            error: function () {
                $("#inner_withdraw_balance").text("서버 오류가 발생했습니다.");
            }
        });
    }

    loadAvailableBalance();
 
    // 비율 클릭 시 출금 수량 및 총 출금 업데이트
    window.setWithdrawAmount = function (percentage) {
        const withdrawAmount = (availableBalance * percentage).toFixed(8); // 비율 계산
        $("#inner_withdraw_amount").val(withdrawAmount); // 출금 수량 입력 필드 업데이트

        const totalWithdraw = parseFloat(withdrawAmount) + fee; // 총 출금 금액 = 수량 + 수수료
        $("#inner_withdraw_total").text(totalWithdraw.toFixed(8)); // 총 출금 필드 업데이트
    };

    // 수량 입력 시 총 출금 업데이트
    $("#inner_withdraw_amount").on("input", function () {
        const withdrawAmount = parseFloat($(this).val()) || 0;
        const totalWithdraw = withdrawAmount + fee;
        $("#inner_withdraw_total").text(totalWithdraw.toFixed(8));
    });

 
        
    // 출금 신청 처리
    $("#inner_withdraw_submit").on("click", function (e) {
        e.preventDefault();

        const withdrawAddress = $("#inner_withdraw_address").val();
        const withdrawAmount = parseFloat($("#inner_withdraw_amount").val());

        if (!withdrawAddress || withdrawAmount <= 0) {
            alert("출금 주소와 출금 수량을 입력해주세요.");
            return;
        }

        $.ajax({
            url: "/service/inner_withdraw.php",
            type: "POST",
            dataType: "json",
            data: {
                withdraw_address: withdrawAddress,
                withdraw_amount: withdrawAmount
            },
            success: function (response) {
                if (response.success) {
                    alert("출금 신청이 완료되었습니다.");
                    location.reload(); // 페이지 새로고침
                } else {
                    alert("출금 실패: " + response.message); // 서버 메시지 출력
                }
            },
            error: function (xhr, status, error) {
                console.log("AJAX Error:", xhr.responseText);
                alert("서버 오류가 발생했습니다: " + error);
            }
        });
        
    });

    
});


//외부전송


$(document).ready(function () {
    let outerAvailableBalance = 0; // 출금 가능 금액
    const fee = 20; // 출금 수수료

    // 출금 가능 금액 불러오기
    function loadOuterAvailableBalance() {
        $.ajax({
            url: "/service/get_available_balance.php", // 외부 출금 가능 금액 확인 PHP 파일
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    outerAvailableBalance = parseFloat(response.available_balance);
                    $("#outer_withdraw_balance").text("출금 가능 금액: " + outerAvailableBalance.toFixed(8) + "IP");
                } else {
                    $("#outer_withdraw_balance").text("잔액을 불러오지 못했습니다.");
                }
            },
            error: function () {
                $("#outer_withdraw_balance").text("서버 오류가 발생했습니다.");
            }
        });
    }

    loadOuterAvailableBalance();

    // 비율 버튼 클릭 시 출금 수량 및 총 출금 업데이트
    window.outer_pricetimes = function (percentage) {
        const withdrawAmount = (outerAvailableBalance * percentage).toFixed(8); // 비율 계산
        $("#outer_withdraw_amount").val(withdrawAmount); // 출금 수량 입력 필드 업데이트

        const totalWithdraw = parseFloat(withdrawAmount) + fee; // 총 출금 금액 = 수량 + 수수료
        $("#outer_withdraw_total").text(totalWithdraw.toFixed(8)); // 총 출금 필드 업데이트
    };

    // 수량 입력 시 총 출금 금액 업데이트
    $("#outer_withdraw_amount").on("input", function () {
        const withdrawAmount = parseFloat($(this).val()) || 0; // 입력 값이 숫자 아니면 0 처리
        const totalWithdraw = withdrawAmount + fee; // 총 출금 = 입력된 값 + 수수료
        $("#outer_withdraw_total").text(totalWithdraw.toFixed(8));
    });

    // 출금 신청 처리
    $("#outer_withdraw_submit").on("click", function (e) {
        e.preventDefault();

        const withdrawAddress = $("#outer_withdraw_address").val();
        const withdrawAmount = parseFloat($("#outer_withdraw_amount").val());

        // 유효성 검사
        if (!withdrawAddress) {
            alert("출금 주소를 입력해주세요.");
            return;
        }
        if (isNaN(withdrawAmount) || withdrawAmount <= 0) {
            alert("출금 수량을 올바르게 입력해주세요.");
            return;
        }
   

        // 출금 요청 AJAX
        $.ajax({
            url: "/service/outer_withdraw.php",
            type: "POST",
            dataType: "json",
            data: {
                withdraw_address: withdrawAddress,
                withdraw_amount: withdrawAmount
            },
            success: function (response) {
                if (response.success) {
                    alert("출금 신청이 완료되었습니다.");
                    location.reload(); // 페이지 새로고침
                } else {
                    alert("출금 실패: " + response.message); // 실패 메시지
                }
            },
            error: function (xhr, status, error) {
                console.log("AJAX Error:", xhr.responseText);
                alert("서버 오류가 발생했습니다: " + error);
            }
        });
    });
});

$(document).ready(function () {
    function fetchLockupData() {
        $.ajax({
            url: "/service/lockup_data.php", // PHP 파일 경로
            method: "GET",
            dataType: "json",
            success: function (data) {
                if (data.success) {
                    displayLockupData(data.locked_amount, data.end_date);
                } else {
                    alert(`오류: ${data.message}`);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching lockup data:", error);
                alert("락업 데이터를 불러오는 중 오류가 발생했습니다.");
            }
        });
    }

    function displayLockupData(lockedAmount, endDate) {
        // 락업된 양 표시
        $("#lockedAmount").text(`${lockedAmount} IP`);

        // 남은 시간 표시
        if (endDate) {
            const updateRemainingTime = () => {
                const now = new Date();
                const endTime = new Date(endDate);
                const diffInSeconds = Math.floor((endTime - now) / 1000);

                if (diffInSeconds <= 0) {
                    $("#remainingTime").text("락업 해제됨");
                    clearInterval(interval);
                } else {
                    const days = Math.floor(diffInSeconds / (3600 * 24));
                    const hours = Math.floor((diffInSeconds % (3600 * 24)) / 3600);
                    const minutes = Math.floor((diffInSeconds % 3600) / 60);
                    const seconds = diffInSeconds % 60;

                    $("#remainingTime").text(`${days}일 ${hours}시간 ${minutes}분 ${seconds}초`);
                }
            };

            // 1초마다 시간 업데이트
            updateRemainingTime();
            const interval = setInterval(updateRemainingTime, 1000);
        } else {
            $("#remainingTime").text("락업 정보 없음");
        }
    }

    // 페이지 로드 시 락업 데이터 가져오기
    fetchLockupData();
});




