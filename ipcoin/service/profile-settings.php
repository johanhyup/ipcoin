
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="referrer" content="no-referrer">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>RAYCOIN Wallet</title>
<script src="/js/profile-settings.js"> </script>
<script>
    // JavaScript로 동적으로 텍스트 변경
    document.addEventListener("DOMContentLoaded", function() {
        const activeNameElement = document.getElementById("active_name");
        if (activeNameElement) {
            activeNameElement.textContent = "User INFO.";
        }
    });
</script>
<?php
require_once('../config.php');
require_once __DIR__ . '/../frames/nav.php';
?>
<?php
// 세션 값 가져오기
$nickname = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') : '';
$email = isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email'], ENT_QUOTES, 'UTF-8') : '';
$phone = isset($_SESSION['user_tel']) ? htmlspecialchars($_SESSION['user_tel'], ENT_QUOTES, 'UTF-8') : '';
$birth_date = isset($_SESSION['birth_date']) ? htmlspecialchars($_SESSION['birth_date'], ENT_QUOTES, 'UTF-8') : '';
?>
  <div class="main">
	<div class="main_wrapper">

<!-------------------------- 구글 아이콘 -------------------------->

<div class="col-lg-4">
	<div class="row">
		<div class="col-12">
			<div class="bg-primary rounded-3 p-2 shadow">
				<div class="d-flex">
					<div id="radialChart" style="min-height:130px; width:100px;"> </div>
					<div class="ms-2 mt-2">
						<h5 style="padding-top:18px;"><b><?php echo htmlspecialchars($user_id); ?></b></h5>
						<p><b><?php echo htmlspecialchars($mb_name); ?></b>님의 보안 등급은 '정상'단계 입니다.</p>
					</div>
				</div>
				<a href="profile-settings.php" class="mt-2 btn btn-success border-0 rounded-pill m-auto d-block">Complete My Profile</a>
			</div>
		</div>
		<div class="col-12">
			<div class="card mt-3">
				<<div class="card-body pb-0">
    <div id="setting-nav">
        <h4 class="card-title mb-2 border-bottom pb-2">Settings</h4>
        <a href="profile-settings" class="d-flex btn btn-light align-items-center justify-content-between fw-bold rounded-3 border-0 p-2" id="profile-settings">
            <div class="d-flex align-items-center">
                <div class="op-primary cw-35 ch-35 d-flex align-items-center justify-content-center rounded-2">
                    <img src="../img/rdo/avatar.svg" class="svg svgh-16 cw-16 text-primary fill-primary" alt="정보수정">
                </div>
                <p class="ms-3">정보수정</p>
            </div>
            <i class="ri-arrow-right-s-line"></i>
        </a>
        <a href="login_history" class="d-flex btn btn-light align-items-center justify-content-between fw-bold rounded-3 mt-2 border-0 p-2" id="login-history">
            <div class="d-flex align-items-center">
                <div class="op-info cw-35 ch-35 d-flex align-items-center justify-content-center rounded-2">
                    <img src="../img/rdo/enter.svg" class="svg svgh-16 cw-16 text-info fill-info" alt="로그인내역">
                </div>
                <p class="ms-3">로그인내역</p>
            </div>
            <i class="ri-arrow-right-s-line"></i>
        </a>

		<a href="change-password" class="d-flex btn btn-light align-items-center justify-content-between fw-bold rounded-3 mt-2 border-0 p-2" id="change-password">
            <div class="d-flex align-items-center">
                <div class="op-info cw-35 ch-35 d-flex align-items-center justify-content-center rounded-2">
                    <img src="../img/rdo/security.svg" class="svg svgh-16 cw-16 text-info fill-info" alt="비밀번호변경">
                </div>
                <p class="ms-3">비밀번호 변경</p>
            </div>
            <i class="ri-arrow-right-s-line"></i>
        </a>
    </div>
</div>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-8">
	<div class="row">
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">User Informations</h4>
				</div>
				<div class="card-body">
				<div class="form-group mb-1">
                        <span class="d-block fw-bold mb-2">아이디 : <b><?php echo htmlspecialchars($user_id); ?></b></span> 
                    </div>
                    <div class="form-group mb-1">
                    <span class="d-block fw-bold mb-2">이름 : <b id="user-name"></b></span>
                    </div>
                    <div class="form-group mb-3">
                        <span class="d-block fw-bold mb-2">가입일시 : <b><?php echo htmlspecialchars($created_at); ?></b></span>
                    </div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">VERIFY & UPGRADE</h4>
				</div>
				<div class="card-body">
					<h6>Account Status : <span class="text-success">정상 <img class="svg ch-15 fill-success" src="../img/rdo/check.svg" alt=""/></span></h6>
					<p class="mt-2 sub-text">Your account is unverified. Get verified to enable funding, trading, and withdrawal.</p>
					<!--<a href="security-settings" class="btn btn-primary border-0 mt-3 px-4 py-2">Get Verified</a>-->
				</div>
			</div>
		</div>
		<div class="col-xl-12">
		<div id="content-container" class="mt-3">
    <!-- 동적으로 내용이 표시될 영역 -->
</div>
<!--
    <div class="card">
        <div class="card-header">
            <h4 class="card-title" id="actice_name2">Personal Informations</h4>
        </div>
        <div class="card-body">
		<div id="content-container" class="card-body">

</div>
            <form name="modifyForm" action="/service/profile_update.php" method="POST" id="modifyForm">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group mb-3">
                            <label for="usernick" class="d-block fw-bold mb-2">닉네임</label>
                            <div class="input-group">
                                <input type="text" placeholder="닉네임을 입력하세요." class="form-control" name="mb_name" id="usernick" required/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group mb-3">
                            <label for="useremail" class="d-block fw-bold mb-2">Email</label>
                            <div class="input-group">
                                <input type="email" placeholder="이메일주소를 입력하세요." class="form-control" name="mb_email" id="useremail" required/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group mb-3">
                            <label for="hp" class="d-block fw-bold mb-2">휴대폰번호</label>
                            <div class="input-group">
                                <input type="text" placeholder="휴대폰번호를 입력하세요." class="form-control" name="mb_tel" id="hp" required/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group mb-3">
                            <label for="birthday" class="d-block fw-bold mb-2">생년월일</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="birth_date" id="birthday" required/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button id="submit" class="btn btn-primary cw-150 border-0 mt-2">정보수정</button>
                </div>
            </form>
        </div>
    </div>
</div>
-->



</script>
<script>
	setInterval(function () {
	fetchLockupData();
	}, 1000); 

		wallet_view();
	inner_withdraw_balance();
	aboutcoin();
	transaction_view();
	fetchWalletAddress();
	setInterval(function () {
		wallet_view();
		inner_withdraw_balance();
		aboutcoin();
		transaction_view();
		getbalance();
	}, 3000);
	$(function(){
		$("#submit").click(function(){ 
			var theForm = document.modifyForm;

			if(!theForm.usernick.value) {
				window.alert("닉네임을 입력하세요.");
				theForm.usernick.focus();
				return false;
			}
			if(theForm.usernick.value.length < 2 || theForm.usernick.value.length > 20) {
				window.alert("닉네임은 최소 3자의 문자열로 입력해 주세요.");
				theForm.usernick.focus();
				return false;
			}


			if(!theForm.useremail.value) {
				window.alert("이메일을 입력하여주세요.");
				theForm.useremail.focus();
				return false;
			}

			if(!theForm.hp.value) {
				window.alert("휴대폰번호를 입력하여주세요.");
				theForm.hp.focus();
				return false;
			}
			if(!numericExamination(theForm.hp.value)) {
				window.alert("휴대폰번호는 숫자만 입력가능합니다.");
				theForm.hp.focus();
				return false;

			}
			if(!theForm.birthday.value) {
				window.alert("생년월일을 입력하세요.");
				theForm.birthday.focus();
				return false;
			}

			$("#modifyForm").submit();
		});
	});
</script>
	<div id="check"></div>
	
	</div>
</div>

	<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<script>
	var jQuery = $.noConflict(true);
	</script>
    <script src="/theme/fx/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="/theme/fx/assets/parallax/js/parallax.min.js"></script>
	<script src="theme/fx/assets/owlcarousel/js/owl.carousel.min.js"></script>
	<!-- countdown -->
	<script type="text/javascript" src="/theme/fx/assets/countdown/js/kinetic.js"></script>
	<script type="text/javascript" src="/theme/fx/assets/countdown/js/jquery.final-countdown.js"></script>
	<script type="text/javascript" src="/theme/fx/js/bootstrap-dropdownhover.js"></script>
	<script type="text/javascript" src="/theme/fx/js/custom.js"></script>

	
	<script>
		$(document).ready(function () {
			//owl
			jQuery(".owl-carousel").owlCarousel({
				autoplay:true,
				autoplayTimeout:3000,// 1000 -> 1초
				autoplayHoverPause:true,
				loop:true,
				margin:10,//이미지 사이의 간격
				nav:true,
				responsive:{
					0:{
						items:2 // 모바일
					},
					600:{
						items:3 // 브라우저 600px 이하
					},
					1000:{
						items:5 // 브라우저 100px 이하
					}
				}
			});

			// countdown
			'use strict';			
			jQuery('.countdown').final_countdown({
				'start': 1362139200,
				'end': 1388461320,
				'now': 1387461319        
			});
		});
	</script>
	<script>
		function ajax_update_toastr()
			{
				$.ajax({
					url:"/plugin/toastr/toastr",
					type:"POST",
					success: function(response, status) {
							$('#check') (response);
					},
					eror:function(request, status, error){
						
					},
					complete:function ()
					{
						
					}
				});
			}

			$( document ).ready(function() {
				ajax_update_toastr();
				setInterval("ajax_update_toastr()", 20000);
			});
	</script>

    	
</div>


<!-- } 하단 끝 -->

<script>
$(function() {
    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
});
</script>
<script>
		document.getElementById("star").addEventListener("click", () => {
			const body = document.body;

			if (body.classList.contains("dark")) {
				body.classList.remove("dark");
				localStorage.setItem("darkTheme","false");
			} else {
				body.classList.add("dark");
				localStorage.setItem("darkTheme","true");
			}
		});
		const storedTheme = localStorage.getItem("darkTheme");

		if (storedTheme !== null) {
			if (storedTheme ==="true") {
				document.body.classList.add("dark");
			}
		} else if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
			document.body.classList.add("dark");
		}
	</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const contentContainer = document.getElementById("content-container");

        // 정보수정
        document.getElementById("profile-settings").addEventListener("click", function(event) {
            event.preventDefault();
            loadContent("/service/fetch_user_info.php", renderProfileSettings);
        });

        // 로그인 내역
        document.getElementById("login-history").addEventListener("click", function(event) {
			fetchLoginHistory() ;
            event.preventDefault();
            loadContent("/service/login_history.php", renderLoginHistory);
        });

        // 비밀번호 변경
        document.getElementById("change-password").addEventListener("click", function(event) {
            event.preventDefault();
            renderChangePasswordForm();
        });
    });

    /**
     * 서버에서 데이터를 가져오고 콜백 함수에 전달
     * @param {string} endpoint - API 경로
     * @param {function} callback - 데이터를 처리할 콜백 함수
     */
    function loadContent(endpoint, callback) {
        fetch(endpoint)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(data => callback(data))
            .catch(error => {
                console.error("Error loading content:", error);
             //   alert("콘텐츠를 로드하는 중 문제가 발생했습니다.");
            });
    }

    /**
     * 프로필 수정 UI 렌더링
     * @param {string} data - 서버에서 가져온 사용자 정보
     */
    function renderProfileSettings(data) {
        const userData = JSON.parse(data);
        const contentContainer = document.getElementById("content-container");
        contentContainer.innerHTML = `
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Personal Informations</h4>
                </div>
                <div class="card-body">
                    <form name="modifyForm" action="/service/profile_update.php" method="POST" id="modifyForm">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="usernick" class="d-block fw-bold mb-2">닉네임</label>
                                    <input type="text" placeholder="닉네임을 입력하세요." class="form-control" name="mb_name" id="usernick" value="${userData.mb_name}" required />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="useremail" class="d-block fw-bold mb-2">Email</label>
                                    <input type="email" placeholder="이메일주소를 입력하세요." class="form-control" name="mb_email" id="useremail" value="${userData.mb_email}" required />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="hp" class="d-block fw-bold mb-2">휴대폰번호</label>
                                    <input type="text" placeholder="휴대폰번호를 입력하세요." class="form-control" name="mb_tel" id="hp" value="${userData.mb_tel}" required />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="birthday" class="d-block fw-bold mb-2">생년월일</label>
                                    <input type="date" class="form-control" name="birth_date" id="birthday" value="${userData.birth_date}" required />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">정보수정</button>
                        </div>
                    </form>
                </div>
            </div>
        `;
    }

    /**
 * 로그인 내역 UI 렌더링
 * @param {Array} logs - 서버에서 가져온 로그인 내역 데이터 배열
 */
function renderLoginHistory(logs) {

    const contentContainer = document.getElementById("content-container");

    if (!logs || logs.length === 0) {
        contentContainer.innerHTML = `
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Login Logs</h4>
                </div>
                <div class="card-body">
                    <p>No login history found.</p>
                </div>
            </div>
        `;
        return;
    }

    let tableHtml = `
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Login Logs</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Log ID</th>
                            <th>Last Login</th>
                            <th>User IP</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
    `;

    logs.forEach(log => {
        tableHtml += `
            <tr>
                <td>${log.log_id}</td>
                <td>${log.last_login}</td>
                <td>${log.user_ip}</td>
                <td>${log.created_at}</td>
            </tr>
        `;
    });

    tableHtml += `
                    </tbody>
                </table>
            </div>
        </div>
    `;

    contentContainer.innerHTML = tableHtml;
}

/**
 * 로그인 내역 요청 및 렌더링
 */
function fetchLoginHistory() {
	fetch("/service/fetch_login_history.php")
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            alert(data.error);
        } else {
            console.log(data); // Inspect the data to verify its correctness
            renderLoginHistory(data); // Use the correct rendering function
        }
    })


}
    /**
     * 비밀번호 변경 UI 렌더링
     */
    function renderChangePasswordForm() {
        const contentContainer = document.getElementById("content-container");
        contentContainer.innerHTML = `
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Change Password</h4>
                </div>
                <div class="card-body">
                    <form id="change-password-form">
                        <div class="mb-3">
                            <label for="current-password" class="form-label">현재 비밀번호</label>
                            <input type="password" id="current-password" name="current_password" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="new-password" class="form-label">새 비밀번호</label>
                            <input type="password" id="new-password" name="new_password" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="confirm-password" class="form-label">새 비밀번호 확인</label>
                            <input type="password" id="confirm-password" name="confirm_password" class="form-control" required />
                        </div>
                        <button type="submit" class="btn btn-primary">비밀번호 변경</button>
                    </form>
                </div>
            </div>
        `;

        document.getElementById("change-password-form").addEventListener("submit", function(event) {
            event.preventDefault();
            submitPasswordChange(this);
        });
    }

    /**
     * 비밀번호 변경 요청
     * @param {HTMLFormElement} form - 제출할 폼
     */
    function submitPasswordChange(form) {
        const formData = new FormData(form);

        fetch("/service/change_password.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    form.reset();
                }
            })
            .catch(error => {
                console.error("Error changing password:", error);
                alert("비밀번호 변경 중 문제가 발생했습니다.");
            });
    }
</script>

<!-- ie6,7에서 사이드뷰가 게시판 목록에서 아래 사이드뷰에 가려지는 현상 수정 -->
<!--[if lte IE 7]>
<script>
$(function() {
    var $sv_use = $(".sv_use");
    var count = $sv_use.length;

    $sv_use.each(function() {
        $(this).css("z-index", count);
        $(this).css("position","relative");
        count = count - 1;
    });
});
</script>
<![endif]-->


</body>
</html>
