<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="referrer" content="no-referrer">
<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>회원 가입 | Raycoin Wallet</title>
<?php
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/frames/asset.php';
?>
</head>
<body>



<!-- 회원정보 입력/수정 시작 { -->
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<script src="..\js\jquery.register_form.js"></script>
<script src="./valid.js"></script>
<script>

</script>
<main>
<div class="container">
	<div class="row justify-content-center auth">
		<div class="col-xl-5 col-lg-6 col-md-8 col-sm-10">
			<div class="card p-0">
				<div class="card-body p-0">
<form id="fregisterform" name="fregisterform" class="p-4" action="/bbs/register_form_update.php" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="w" value="">
<input type="hidden" name="url" value="%2Fbbs%2Fregister_form.php">
<input type="hidden" name="agree" value="">
<input type="hidden" name="agree2" value="">
<input type="hidden" name="cert_type" value="">
<input type="hidden" name="cert_no" value="">
<input type="hidden" name="mb_4" id="mb_4" value="">
<input type="hidden" name="agyChkComplete" id="agyChkComplete" value="n">
<input type="hidden" name="mb_sex" value="">
<div class="text-center mb-4">
			<div style="padding-top:10px;padding-bottom:20px;">
				<img src="..\img\rdo\logo.png" width="240">
			</div>
			<h4>Create your account</h4>
			<p class="text-muted">
			  회원가입하기
			</p>
		</div>
		<div class="row g-3">

			<div class="col-12" style="margin-top:1rem;">
			  <div class="input-group p-2">
				<span class="input-group-text p-2 bg-transparent">
				<svg xmlns="http://www.w3.org/2000/svg" viewbox="0 -32 512.016 512" class="svg svgh-15 cw-20 replaced-svg"><path d="m192 213.339844c-58.816406 0-106.667969-47.847656-106.667969-106.664063 0-58.816406 47.851563-106.6679685 106.667969-106.6679685s106.667969 47.8515625 106.667969 106.6679685c0 58.816407-47.851563 106.664063-106.667969 106.664063zm0-181.332032c-41.171875 0-74.667969 33.492188-74.667969 74.667969 0 41.171875 33.496094 74.664063 74.667969 74.664063s74.667969-33.492188 74.667969-74.664063c0-41.175781-33.496094-74.667969-74.667969-74.667969zm0 0"></path><path d="m368 448.007812h-352c-8.832031 0-16-7.167968-16-16v-74.667968c0-55.871094 45.460938-101.332032 101.332031-101.332032h181.335938c55.871093 0 101.332031 45.460938 101.332031 101.332032v74.667968c0 8.832032-7.167969 16-16 16zm-336-32h320v-58.667968c0-38.226563-31.105469-69.332032-69.332031-69.332032h-181.335938c-38.226562 0-69.332031 31.105469-69.332031 69.332032zm0 0"></path><path d="m496 218.675781h-181.332031c-8.832031 0-16-7.167969-16-16s7.167969-16 16-16h181.332031c8.832031 0 16 7.167969 16 16s-7.167969 16-16 16zm0 0"></path><path d="m410.667969 304.007812c-4.097657 0-8.191407-1.558593-11.308594-4.691406-6.25-6.253906-6.25-16.386718 0-22.636718l74.027344-74.027344-74.027344-74.027344c-6.25-6.25-6.25-16.382812 0-22.632812s16.382813-6.25 22.636719 0l85.332031 85.332031c6.25 6.25 6.25 16.386719 0 22.636719l-85.332031 85.332031c-3.136719 3.15625-7.234375 4.714843-11.328125 4.714843zm0 0"></path></svg>
				</span>
				<input type="text" class="form-control" placeholder="아이디(4글자 이상 입력해주세요.)" name="mb_id" id="reg_mb_id">
			  </div>
			</div>
			<div class="col-12" style="margin-top:1rem;">
			  <div class="input-group p-2">
				<span class="input-group-text p-2 bg-transparent">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" x="0" y="0" viewbox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="svg svgh-15 cw-20 replaced-svg"><g>
<g xmlns="http://www.w3.org/2000/svg">
	<g>
		<path d="M437.02,330.98c-27.883-27.882-61.071-48.523-97.281-61.018C378.521,243.251,404,198.548,404,148    C404,66.393,337.607,0,256,0S108,66.393,108,148c0,50.548,25.479,95.251,64.262,121.962    c-36.21,12.495-69.398,33.136-97.281,61.018C26.629,379.333,0,443.62,0,512h40c0-119.103,96.897-216,216-216s216,96.897,216,216    h40C512,443.62,485.371,379.333,437.02,330.98z M256,256c-59.551,0-108-48.448-108-108S196.449,40,256,40    c59.551,0,108,48.448,108,108S315.551,256,256,256z" data-original="#000000" class=""></path>
	</g>
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
</g></svg>
				</span>
				<input type="text" class="form-control" placeholder="이름" name="mb_name" id="mb_name" autocomplete="off">
			  </div>
			</div>
			<div class="col-12" style="margin-top:1rem;">
			  <div class="input-group p-2">
				<span class="input-group-text p-2 bg-transparent">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" x="0" y="0" viewbox="0 0 35 35" style="enable-background:new 0 0 512 512" xml:space="preserve" class="svg svgh-15 cw-20 replaced-svg"><g>
<g xmlns="http://www.w3.org/2000/svg">
	<path d="M25.302,0H9.698c-1.3,0-2.364,1.063-2.364,2.364v30.271C7.334,33.936,8.398,35,9.698,35h15.604   c1.3,0,2.364-1.062,2.364-2.364V2.364C27.666,1.063,26.602,0,25.302,0z M15.004,1.704h4.992c0.158,0,0.286,0.128,0.286,0.287   c0,0.158-0.128,0.286-0.286,0.286h-4.992c-0.158,0-0.286-0.128-0.286-0.286C14.718,1.832,14.846,1.704,15.004,1.704z M17.5,33.818   c-0.653,0-1.182-0.529-1.182-1.183s0.529-1.182,1.182-1.182s1.182,0.528,1.182,1.182S18.153,33.818,17.5,33.818z M26.021,30.625   H8.979V3.749h17.042V30.625z" data-original="#000000" class=""></path>
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
<g xmlns="http://www.w3.org/2000/svg">
</g>
</g></svg>
				</span>
				<input type="text" class="form-control" placeholder="휴대폰번호" name="mb_tel" id="mb_tel" onkeyup="formatPhoneNumber(this);">
			  </div>
			</div>	 
			<div class="col-12" style="margin-top:1rem;">
			  <div class="input-group p-2">
				<span class="input-group-text p-2 bg-transparent">
				<img src="..\img\rdo\cake.svg" class="svg svgh-15 cw-20">
				</span>
				<input type="date" class="form-control" placeholder="생년월일" name="birth_date" id="birth_date">
			  </div>
			</div> 
			 <div class="col-12" style="margin-top:1rem;">
			  <div class="input-group p-2">
				<span class="input-group-text p-2 bg-transparent">
				<img src="..\img\rdo\envelope.svg" class="svg svgh-15 cw-20">
				</span>
				<input type="email" class="form-control" placeholder="이메일" name="mb_email" id="mb_email">
			  </div>
			</div> 
			<div class="col-12" style="margin-top:1rem;">
			  <div class="input-group p-2">
				<span class="input-group-text p-2 bg-transparent">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" x="0" y="0" viewbox="0 0 34 34" style="enable-background:new 0 0 512 512" xml:space="preserve" class="svg svgh-20 cw-20 replaced-svg"><g><g xmlns="http://www.w3.org/2000/svg"><path d="m17 1c-5 0-9 4-9 9v4c-1.7 0-3 1.3-3 3v13c0 1.7 1.3 3 3 3h18c1.7 0 3-1.3 3-3v-13c0-1.7-1.3-3-3-3v-4c0-5-4-9-9-9zm10 16v13c0 .6-.4 1-1 1h-18c-.6 0-1-.4-1-1v-13c0-.6.4-1 1-1h1 16 1c.6 0 1 .4 1 1zm-17-3v-4c0-3.9 3.1-7 7-7s7 3.1 7 7v4z" data-original="#000000"></path><path d="m17 19c-1.7 0-3 1.3-3 3 0 1.3.8 2.4 2 2.8v2.2c0 .6.4 1 1 1s1-.4 1-1v-2.2c1.2-.4 2-1.5 2-2.8 0-1.7-1.3-3-3-3zm0 4c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z" data-original="#000000"></path></g></g></svg>
				</span>
				<input type="password" name="mb_password" class="form-control" id="reg_mb_password" placeholder="비밀번호">
			  </div>
			</div>
			<div class="col-12" style="margin-top:1rem;">
			  <div class="input-group p-2">
				<span class="input-group-text p-2 bg-transparent">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" x="0" y="0" viewbox="0 0 34 34" style="enable-background:new 0 0 512 512" xml:space="preserve" class="svg svgh-20 cw-20 replaced-svg"><g><g xmlns="http://www.w3.org/2000/svg"><path d="m17 1c-5 0-9 4-9 9v4c-1.7 0-3 1.3-3 3v13c0 1.7 1.3 3 3 3h18c1.7 0 3-1.3 3-3v-13c0-1.7-1.3-3-3-3v-4c0-5-4-9-9-9zm10 16v13c0 .6-.4 1-1 1h-18c-.6 0-1-.4-1-1v-13c0-.6.4-1 1-1h1 16 1c.6 0 1 .4 1 1zm-17-3v-4c0-3.9 3.1-7 7-7s7 3.1 7 7v4z" data-original="#000000"></path><path d="m17 19c-1.7 0-3 1.3-3 3 0 1.3.8 2.4 2 2.8v2.2c0 .6.4 1 1 1s1-.4 1-1v-2.2c1.2-.4 2-1.5 2-2.8 0-1.7-1.3-3-3-3zm0 4c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z" data-original="#000000"></path></g></g></svg>
				</span>
				<input type="password" name="mb_password_re" class="form-control" id="reg_mb_password_re" placeholder="비밀번호확인">
			  </div>
			</div>
			<div class="col-12 " style="margin-top:1rem;">
			  <button id="submit" class="btn btn-primary p-2 cfs-16 fw-500 border-0 d-block w-100 rounded-0">회원가입</button>

			</div>

		  </div>
		
      
</form>
        <div class="text-center border-top p-3" style="margin-top:1rem;">
          <p>
            이미 회원이십니까?
            <a href="login.php" class="text-primary">로그인하기</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
</div></main>

<!-- } 회원정보 입력/수정 끝 -->
<!-- <div style='float:left; text-align:center;'>RUN TIME : 0.0014541149139404<br></div> -->
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
