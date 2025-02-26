<?php
// 현재 파일의 상위 디렉토리의 config.php를 불러옴
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/service/update_wallet_balance.php';
$current_page = $_SERVER['REQUEST_URI']; // 현재 페이지의 URI 가져오기
//require_once dirname(__DIR__) . '/service/update_lockup.php';
?>


<!--
<base href="/">
-->
<?php
// 세션 시작
// 세션 시작 및 세션 만료 시간 설정
session_start([
    'cookie_lifetime' => 6000, // 쿠키 유효 시간: 600초 (10분)
]);

// 세션 만료 시간 확인
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 600)) { 
    // 마지막 활동 이후 10분 경과 시 세션 파기
    session_unset();
    session_destroy();

    echo "<script>
        alert('세션이 만료되었습니다. 다시 로그인 해주세요.');
        window.location.href = '/bbs/login.php';
    </script>";
    exit;
}

$_SESSION['last_activity'] = time(); // 마지막 활동 시간 갱신
// 세션에 로그인 정보가 없는 경우 로그인 페이지로 리다이렉션
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name']) || !isset($_SESSION['last_login'])) {

	echo "<script>
	alert('로그인이 필요한 페이지입니다.');
	window.location.href = '/bbs/login.php';
  </script>";
exit; // 더 이상 실행되지 않도록 종료
}



// 세션에서 값 가져오기
$id= $_SESSION['user_idx']; // 세션에서 사용자 ID
$user_id = $_SESSION['user_id']; // 세션에서 사용자 ID
$mb_name = $_SESSION['user_name']; // 세션에서 사용자 이름
$last_login = $_SESSION['last_login']; // 세션에서 마지막 로그인 시간
$approved = $_SESSION['approved']; // 세션에서 
$user_ip = $_SERVER['REMOTE_ADDR']; // 사용자의 IP 주소
$created_at =  $_SESSION['created_at'];

// 승인 여부 확인
if ($approved != 1) {
    echo "<script>alert('관리자 승인이 필요한 아이디입니다.'); window.location.href = '/bbs/logout.php';</script>";
    exit; // 더 이상 실행되지 않도록 종료
}
?>

<link rel="stylesheet" href="/theme/fx/css/default.css?ver=22060532">
<link rel="stylesheet" href="/js/font-awesome/css/font-awesome.min.css?ver=22060532">
<!--[if lte IE 8]>
<script src="/js/html5.js"></script>
<![endif]-->
<script>
// 자바스크립트에서 사용하는 전역변수 선언
var g5_url       ="https://www.deepsee-service.com/filecoin/";
var g5_bbs_url   ="/bbs";
var g5_is_member ="1";
var g5_is_admin  ="";
var g5_is_mobile ="";
var g5_bo_table  ="";
var g5_sca       ="";
var g5_editor    ="";
var g5_cookie_domain ="";
</script>


<!-- google fonts -->
<link href="//fonts.googleapis.com/css?family=Nanum+Gothic:400,700,800|Noto+Sans+KR:100,300,400,500,700,900|Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i|Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">

<link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">

<!-- Bootstrap core CSS -->
<link href="/theme/fx/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- fontawesome -->
<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
<!-- owl Carousel -->
<link rel="stylesheet" href="/theme/fx/assets/owlcarousel/css/owl.carousel.min.css">
<link rel="stylesheet" href="/theme/fx/assets/owlcarousel/css/owl.theme.default.min.css">

<!-- countdown -->
<link href="/theme/fx/assets/countdown/css/demo.css" rel="stylesheet">
<!-- bootstrap-social icon -->
<link href="/theme/fx/assets/bootstrap-social/bootstrap-social.css" rel="stylesheet">
<link href="/theme/fx/css/animate.css" rel="stylesheet">
<link href="/theme/fx/css/bootstrap-dropdownhover.css" rel="stylesheet">
<!-- Custom & ety -->
<link href="/theme/fx/css/modern-business.css?ver=22060532" rel="stylesheet">
<link href="/theme/fx/css/ety.css?ver=22060532" rel="stylesheet">

<link rel="icon" href="/img/rdo/ip.png"/>
<link rel="apple-touch-icon" href="/img/rdo/ip.png"/>
<script src="/js/jquery-1.12.4.min.js?ver=22060532"></script>
<script src="/js/jquery-migrate-1.4.1.min.js?ver=22060532"></script>
<script src="/js/jquery.menu.js?ver=22060532"></script>
<script src="/js/common.js?ver=22060532"></script>
<script src="/js/wrest.js?ver=22060532"></script>
<script src="/js/placeholders.min.js?ver=22060532"></script>
<script src="/js/main.js"> </script>

</head>
<body>
<div id="hd_login_msg"><?php echo htmlspecialchars($mb_name); ?>님 로그인 중 <a href="/bbs/logout.php">로그아웃</a></div>
<!-- 팝업레이어 시작 { -->
<div id="hd_pop">
    <h2>팝업레이어 알림</h2>

<span class="sound_only">팝업레이어 알림이 없습니다.</span></div>

<script>
$(function() {
    $(".hd_pops_reject").click(function() {
        var id = $(this).attr('class').split(' ');
        var ck_name = id[1];
        var exp_time = parseInt(id[2]);
        $("#"+id[1]).css("display","none");
        set_cookie(ck_name, 1, exp_time, g5_cookie_domain);
    });
    $('.hd_pops_close').click(function() {
        var idb = $(this).attr('class').split(' ');
        $('#'+idb[1]).css('display','none');
    });
    $("#hd").css("z-index", 1000);
});
</script>
<!-- } 팝업레이어 끝 --><meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-------------------------- 네비게이션 -------------------------->
<style>
.collapse.in{
    -webkit-transition-delay: 4s;
    transition-delay: 5s;
    visibility: visible;
}
</style>

<header>
	<div class="header_wrap">
		<div class="header_logo">
						<h4 id ="active_name">IPCOIN</h4>
					</div>
		<ul class="header_menu">
			<li class="search">
				<div class="search_line">
		
										<p><b>출금 가능액</b></p>
										<p class="text-uppercase"><b><span id="withdrawableBalance">0</span> IP</b></p>
				
					<p><span>USER ID :</span> <?php echo htmlspecialchars($user_id); ?> &nbsp;
					<span>Last login :</span> <?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($last_login))); ?> &nbsp;
					<span>IP :</span> <?php echo htmlspecialchars($user_ip); ?></p>
				</div>
			</li>
			<div class="footer">
		<a href="/bbs/logout.php">
			<img src="/img/rdo/logout.svg"  class="svgh-23" alt="로그아웃">
		</a>
	</div>
			<li class="dropdown profile">
				<a href="#" class="dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
				 aria-expanded="false">
					<img src="/img/rdo/avatar.svg">
				</a>
				<div class="dropdown-menu dropdown-menu-end mt-4">
					<ul>
						<li>
							<a class="dropdown-item d-flex align-items-center" href="/service/profile-settings.php">
								<img src="/img/rdo/setting.svg"  class="svgh-16" alt="설정">
								<span class="ms-2">설정</span>
							</a>
						</li>
							<!--
						<li>
						
							<a class="dropdown-item d-flex align-items-center" href="/service/security-settings">
								<img src="/img/rdo/security.svg"  class="svgh-16" alt="보안">
								<span class="ms-2">보안</span>
							</a>
						</li>
-->
						<li>
							<a class="dropdown-item text-danger d-flex align-items-center" href="/bbs/logout.php">
								<img src="/img/rdo/logout.svg" class="svgh-16" alt="로그아웃">
								<span class="ms-2">로그아웃</span>
							</a>
						</li>
					</ul>
				</div>
			</li>
		</ul>
	</div>
</header>
<nav class="nav-left">
    <div class="nav-header">
        <a href="/" class="brand-logo">
            <img src="/img/rdo/IP.png?1708068343">
        </a>
    </div>
	<div>
    <ul id="menu" class="sidebar">
        <li class="<?= ($current_page === '/' ? 'mm-active' : '') ?>">
            <a href="/">
                <img src="/img/rdo/wallet.svg" class="svgh-20" alt="입출금지갑">
            </a>
        </li>
        <li class="<?= ($current_page === '/service/lockup_list.php' ? 'mm-active' : '') ?>">
            <a href="/service/lockup_list.php">
                <img src="/img/rdo/padlock.svg" class="svgh-21" alt="락업리스트">
            </a>
        </li>
        <li class="<?= (strpos($current_page, 'upbit.com') !== false ? 'mm-active' : '') ?>">
            <a href="https://coinone.co.kr/exchange/trade/ip/krw" target="_blank">
                <img src="/img/rdo/market.svg" class="svgh-20" alt="마켓">
            </a>
        </li>
        <li id="setting" class="<?= ($current_page === '/service/profile-settings.php' ? 'mm-active' : '') ?>">
            <a href="/service/profile-settings.php">
                <img src="/img/rdo/setting.svg" class="svgh-23" alt="설정">
            </a>
        </li>
		</div>
		<!--
        <li class=" dropdown2 profile <?= ($current_page === '/service/security-settings' ? 'mm-active' : '') ?>">
            <a href="#" class="dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="/img/rdo/avatar.svg">
            </a>
            <div class="dropdown-menu dropdown-menu-end mt-4">
                <ul>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="/service/profile-settings">
                            <img src="/img/rdo/setting.svg" class="svgh-16" alt="설정">
                            <span class="ms-2">설정</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="/service/security-settings">
                            <img src="/img/rdo/security.svg" class="svgh-16" alt="보안">
                            <span class="ms-2">보안</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-danger d-flex align-items-center" href="/bbs/logout.php">
                            <img src="/img/rdo/logout.svg" class="svgh-16" alt="로그아웃">
                            <span class="ms-2">로그아웃</span>
                        </a>
                    </li>
-->
                </ul>
            </div>
        </li>
    </ul>

	
	

	<div class="footer2">
		<a href="/bbs/logout.php">
			<img src="/img/rdo/logout.svg"  class="svgh-23" alt="로그아웃">
		</a>
	</div>
	<div class="dropdown-menu dropdown-menu-end mt-4">
					<ul>
						<li>
							<a class="dropdown-item d-flex align-items-center" href="/service/profile-settings">
								<img src="/img/rdo/setting.svg"  class="svgh-16" alt="설정">
								<span class="ms-2">설정</span>
							</a>
						</li>
						<li>
							<a class="dropdown-item d-flex align-items-center" href="/service/security-settings">
								<img src="/img/rdo/security.svg"  class="svgh-16" alt="보안">
								<span class="ms-2">보안</span>
							</a>
						</li>
			<li class="dropdown profile">
				<a href="#" class="dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<img src="/img/rdo/avatar.svg">
				</a>
				<div class="dropdown-menu dropdown-menu-end mt-4">
					<ul>
						<li>
							<a class="dropdown-item d-flex align-items-center" href="/service/profile-settings">
								<img src="/img/rdo/setting.svg"  class="svgh-16" alt="설정">
								<span class="ms-2">설정</span>
							</a>
						</li>
						<li>
							<a class="dropdown-item d-flex align-items-center" href="/service/security-settings">
								<img src="/img/rdo/security.svg"  class="svgh-16" alt="보안">
								<span class="ms-2">보안</span>
							</a>
						</li>

						<li>
							<a class="dropdown-item text-danger d-flex align-items-center" href="/bbs/logout.php">
								<img src="/img/rdo/logout.svg" class="svgh-16" alt="로그아웃">
								<span class="ms-2">로그아웃</span>
							</a>
						</li>
					</ul>
				</div>
			</li>
						<li>
							<a class="dropdown-item text-danger d-flex align-items-center" href="/bbs/logout.php">
								<img src="/img/rdo/logout.svg" class="svgh-16" alt="로그아웃">
								<span class="ms-2">로그아웃</span>
							</a>
						</li>
					</ul>
				</div>
</nav>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script>
	$(function(){
		$('.memo').click(function(){
			var url ="/ft/memo";
			$('#memo_modal .modal-content').load(url, function(){
				$('#memo_modal').modal('show');
			});
		});
	});
  </script>
