
<?php
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/frames/nav.php';

?>
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

<?php
// 현재 파일의 디렉토리를 기준으로 frames/nav.php 포함


// 추가 코드 작성 가능
echo "Nav file has been imported!";
?>
  <div class="main">
	<div class="main_wrapper">
	<script>
    // JavaScript로 동적으로 텍스트 변경
    document.addEventListener("DOMContentLoaded", function() {
        const activeNameElement = document.getElementById("active_name");
        if (activeNameElement) {
            activeNameElement.textContent = "LOCKUP INFO.";
        }
    });
</script>
<!-------------------------- 구글 아이콘 -------------------------->

<div class="lockup_list">
	<div class="card">
		<div class="card-header d-flex justify-content-between align-items-xsm-center">
			<h4 class="card-title">회원님의 락업 정보를 확인할 수 있습니다.</h4>
			<div class="d-block d-xsm-flex"></div>
		</div>
		<div class="card-body">
			<div class="d-flex align-items-xsm-center mt-3">
				<div class="op-primary rounded-3 cw-60 d-flex align-items-center justify-content-center ch-60 mt-1 mt-xsm-0">
					<img class="svg svgh-50 text-primary fill-primary replaced-svg" src="/img/rdo/IP.png">
				</div>
				<div class="w-100">
					<div class="d-xsm-flex align-items-center justify-content-between ms-3">
						<div>
							<p class="fw-bold">
								Story IP
							</p>
							<p class="sub-text">
								<span class="text-uppercase">IP</span>
							</p>
							<p class="sub-text bold">
								락업된 양: <span id="lockedAmount">0 IP</span>
							</p>
							<p class="sub-text m2">
								남은 시간: <span id="remainingTime">로드 중...</span>
							</p>
							<p class="text-success sub-text">
								Locked until
							</p>
						</div>
						<!--<a href="#" class="btn btn-outline-primary mt-2 mt-xsm-0">Manage</a>-->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

    

</div>
    </div>
</div>
	<div id="check"></div>
	
	</div>
</div>

	<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<script>
	var jQuery = $.noConflict(true);
	</script>
    <script src="../theme/fx/assets/bootstrap../js/bootstrap.bundle.min.js"></script>
	<script src="../theme/fx/assets/parallax../js/parallax.min.js"></script>
	<script src="../theme/fx/assets/owlcarousel../js/owl.carousel.min.js"></script>
	<!-- countdown -->
	<script type="text/javascript" src="../theme/fx/assets/countdown../js/kinetic.js"></script>
	<script type="text/javascript" src="../theme/fx/assets/countdown../js/jquery.final-countdown.js"></script>
	<script type="text/javascript" src="../theme/fx../js/bootstrap-dropdownhover.js"></script>
	<script type="text/javascript" src="../theme/fx../js/custom.js"></script>

	
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

<script>    // 초기 계산 및 1초마다 업데이트
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
