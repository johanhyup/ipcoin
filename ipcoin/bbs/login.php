<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="referrer" content="no-referrer">
<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>로그인 | Story(IP) Wallet</title>
<?php
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/frames/asset.php';
?>
</head>
<body>
<main>

<?php
session_start(); // 세션 시작

// 세션에 로그인 정보가 있는지 확인
if (isset($_SESSION['user_id']) && isset($_SESSION['user_name']) && isset($_SESSION['last_login'])) {
    // 이미 로그인된 경우
    echo "<script>alert('이미 로그인되었습니다.'); window.location.href = '/';</script>"; // 알림 창 출력 후 메인 페이지로 이동
    exit; // 더 이상 실행되지 않도록 종료
}
// 로그인되지 않은 경우, 로그인 폼을 표시하거나 다른 처리를 하세요.
?>
<div class="container">
	<div class="row justify-content-center auth">
		<div class="col-xl-5 col-lg-6 col-md-8 col-sm-10">
			<div class="card p-0">
				<div class="card-body p-0">
				<form name="flogin" action="/bbs/login_check.php"
                 onsubmit="return flogin_submit(this);" class="p-4" method="post" autocomplete="off">
					<input type="hidden" name="url" value="https%3A%2F%2Ffilcoin-wallet.com">
					<div class="text-center mb-4">
						<div style="padding-top:10px;padding-bottom:20px;">
							<img src="..\img\rdo\logo.png" width="240">
						</div>
						<h4>Login your account</h4>
						<p class="text-muted">
						  회원로그인하기
						</p>
				    </div>
					 <div class="row g-3">
            <div class="col-12" style="margin-top:1rem;">
              <div class="input-group p-2">
                <span class="input-group-text p-2 bg-transparent">
                  <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 -32 512.016 512" class="svg svgh-15 cw-20 replaced-svg"><path d="m192 213.339844c-58.816406 0-106.667969-47.847656-106.667969-106.664063 0-58.816406 47.851563-106.6679685 106.667969-106.6679685s106.667969 47.8515625 106.667969 106.6679685c0 58.816407-47.851563 106.664063-106.667969 106.664063zm0-181.332032c-41.171875 0-74.667969 33.492188-74.667969 74.667969 0 41.171875 33.496094 74.664063 74.667969 74.664063s74.667969-33.492188 74.667969-74.664063c0-41.175781-33.496094-74.667969-74.667969-74.667969zm0 0"></path><path d="m368 448.007812h-352c-8.832031 0-16-7.167968-16-16v-74.667968c0-55.871094 45.460938-101.332032 101.332031-101.332032h181.335938c55.871093 0 101.332031 45.460938 101.332031 101.332032v74.667968c0 8.832032-7.167969 16-16 16zm-336-32h320v-58.667968c0-38.226563-31.105469-69.332032-69.332031-69.332032h-181.335938c-38.226562 0-69.332031 31.105469-69.332031 69.332032zm0 0"></path><path d="m496 218.675781h-181.332031c-8.832031 0-16-7.167969-16-16s7.167969-16 16-16h181.332031c8.832031 0 16 7.167969 16 16s-7.167969 16-16 16zm0 0"></path><path d="m410.667969 304.007812c-4.097657 0-8.191407-1.558593-11.308594-4.691406-6.25-6.253906-6.25-16.386718 0-22.636718l74.027344-74.027344-74.027344-74.027344c-6.25-6.25-6.25-16.382812 0-22.632812s16.382813-6.25 22.636719 0l85.332031 85.332031c6.25 6.25 6.25 16.386719 0 22.636719l-85.332031 85.332031c-3.136719 3.15625-7.234375 4.714843-11.328125 4.714843zm0 0"></path></svg>
                </span>
                <input type="text" name="mb_id" id="login_id" class="form-control" placeholder="아이디" value="">
              </div>
            </div>
            <div class="col-12" style="margin-top:1rem;">
              <div class="input-group p-2">
                <span class="input-group-text p-2 bg-transparent">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" x="0" y="0" viewbox="0 0 34 34" style="enable-background:new 0 0 512 512" xml:space="preserve" class="svg svgh-20 cw-20 replaced-svg"><g><g xmlns="http://www.w3.org/2000/svg"><path d="m17 1c-5 0-9 4-9 9v4c-1.7 0-3 1.3-3 3v13c0 1.7 1.3 3 3 3h18c1.7 0 3-1.3 3-3v-13c0-1.7-1.3-3-3-3v-4c0-5-4-9-9-9zm10 16v13c0 .6-.4 1-1 1h-18c-.6 0-1-.4-1-1v-13c0-.6.4-1 1-1h1 16 1c.6 0 1 .4 1 1zm-17-3v-4c0-3.9 3.1-7 7-7s7 3.1 7 7v4z" data-original="#000000"></path><path d="m17 19c-1.7 0-3 1.3-3 3 0 1.3.8 2.4 2 2.8v2.2c0 .6.4 1 1 1s1-.4 1-1v-2.2c1.2-.4 2-1.5 2-2.8 0-1.7-1.3-3-3-3zm0 4c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z" data-original="#000000"></path></g></g></svg>
                </span>

                <input type="password" name="mb_password" id="login_pw" class="form-control" placeholder="비밀번호">
              </div>
            </div>
            <div class="col-12" style="margin-top:1rem;">
              <button id="login" class="btn btn-primary p-2 cfs-16 fw-500 border-0 d-block w-100 rounded-0">로그인하기</button>
            </div>
          </div>
        </form>
        <div class="text-center border-top p-3">
          <p>
            아직 회원이 아니십니까?
            <a href="register_form.php" class="text-primary">회원가입</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
</div></main>
<script>
jQuery(function($){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });
});

function flogin_submit(f)
{
    if( $( document.body ).triggerHandler( 'login_sumit', [f, 'flogin'] ) !== false ){
        return true;
    }
    return false;
}
</script>


<!-- <div style='float:left; text-align:center;'>RUN TIME : 0.0025579929351807<br></div> -->
<!-- ie6,7에서 사이드뷰가 게시판 목록에서 아래 사이드뷰에 가려지는 현상 수정 -->
<!--[if lte IE 7]>
<script>
$(function() {
    var $sv_use = $(".sv_use");
    var count = $sv_use.length;

    $sv_use.each(function() {
        $(this).css("z-index", count);
        $(this).css("position", "relative");
        count = count - 1;
    });
});
</script>
<![endif]-->


</body>
</html>
