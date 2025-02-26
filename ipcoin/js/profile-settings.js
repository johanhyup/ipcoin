//이벤트리스너

document.addEventListener("DOMContentLoaded", function () {
    // 사용자 정보 로드 버튼 이벤트
    const userInfoEndpoint = "/service/fetch_user_info.php";
    loadUserInfo(userInfoEndpoint);

    // 정보 수정 폼 제출 이벤트
    document.getElementById("submit-user-info").addEventListener("click", function (e) {
        e.preventDefault(); // 기본 동작 방지
        submitUserInfo("form[name='modifyForm']");
    });

    // 지갑 주소 버튼 이벤트
    const walletEndpoint = "/service/fetch_wallet_address.php";
    document.getElementById("show_address_btn").addEventListener("click", function (e) {
        e.preventDefault(); // 기본 동작 방지
        showWalletAddress(walletEndpoint, "#wallet_address_display");
    });
});


/**
 * 사용자 정보를 가져와 화면에 표시하는 함수
 * @param {string} endpoint - 사용자 정보를 가져올 API 경로
 */function loadUserInfo(endpoint) {
    fetch(endpoint)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error("API Error:", data.error);
                alert("사용자 정보를 가져오는 중 문제가 발생했습니다: " + data.error);
                return;
            }

            // UI에 데이터 표시
            const userNameElem = document.getElementById("user-name");
            if (userNameElem) {
                userNameElem.textContent = data.mb_name || "정보 없음";
            } else {
                console.error("Element with ID 'user-name' not found in the DOM.");
            }

            const userNickElem = document.getElementById("usernick");
            if (userNickElem) {
                userNickElem.value = data.mb_name || "";
            }

            const userEmailElem = document.getElementById("useremail");
            if (userEmailElem) {
                userEmailElem.value = data.mb_email || "";
            }

            const hpElem = document.getElementById("hp");
            if (hpElem) {
                hpElem.value = data.mb_tel || "";
            }

            const birthdayElem = document.getElementById("birthday");
            if (birthdayElem) {
                birthdayElem.value = data.birth_date || "";
            }
        })
        .catch(error => {
            console.error("Fetch Error:", error);
            alert("사용자 정보를 가져오는 중 문제가 발생했습니다. 자세한 내용은 콘솔을 확인하세요.");
        });
}

/**
 * 사용자 정보 폼을 제출하는 함수
 * @param {string} formSelector - 제출할 폼의 셀렉터
 */
function submitUserInfo(formSelector) {
    const form = document.querySelector(formSelector);
    const formData = new FormData(form);

    fetch(form.action, {
        method: form.method,
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("사용자 정보가 성공적으로 업데이트되었습니다.");
            } else {
                alert("사용자 정보 업데이트 중 문제가 발생했습니다.");
            }
        })
        .catch(error => {
            console.error("Error submitting user info:", error);
            alert("사용자 정보를 제출하는 중 문제가 발생했습니다.");
        });
}
