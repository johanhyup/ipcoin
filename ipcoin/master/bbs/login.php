<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="referrer" content="no-referrer">
<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>관리자 로그인 | Story(IP) Wallet</title>

<?php
require_once dirname(__DIR__) . '/../config.php';
require_once dirname(__DIR__) . '/../frames/asset.php';
session_start();
if (isset($_SESSION['master_id'])) {
    echo "<script>alert('이미 로그인되었습니다.'); window.location.href = '/master/index.php';</script>";
    exit;
}
?>
<style>
    /* 페이지 전체 화면 정렬 */
    body, html {
        margin: 0;
        padding: 0;
        height: 100vh;
        overflow: hidden;
    }
    main {
        display: flex;
        justify-content: center; /* 수평 가운데 정렬 */
        align-items: center;     /* 수직 가운데 정렬 */
    }
    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .card-header {
        background-color: #f7f7f7;
        font-weight: bold;
        text-align: center;
        padding: 1rem;
        border-bottom: 1px solid #ddd;
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>
<main>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-header">
                        관리자 로그인
                    </div>
                    <div class="card-body">
                        <form action="login_check.php" method="post" autocomplete="off">
                            <div class="mb-3">
                                <label for="login_id">아이디</label>
                                <input type="text" name="master_id" id="login_id" class="form-control" placeholder="아이디를 입력하세요" required>
                            </div>
                            <div class="mb-3">
                                <label for="login_pw">비밀번호</label>
                                <input type="password" name="master_password" id="login_pw" class="form-control" placeholder="비밀번호를 입력하세요" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-100">로그인</button>
                            </div>
                        </form>
                    </div>
                </div><!-- card -->
            </div><!-- col -->
        </div><!-- row -->
    </div><!-- container -->
</main>
</body>
</html>
