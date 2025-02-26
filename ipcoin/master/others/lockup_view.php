<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>코인 락업 관리</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/master/assets/js/lockup_manage.js" defer></script>
    
<style>
.lockup_user{
    top: 20%;
    left: 20%;
    transform: translate(0%, 10%);
    position: relative;
    translate: ();
    padding: 20px;
    width: calc(90% - 250px);
    overflow-y: auto;
}

</style>
</head>
    <body>
<?php
require_once dirname(__DIR__) . '/frames/nav.php';
require_once dirname(__DIR__) . '/frames/top_nav.php';

?>



<div class="main2">
   
</div>
<div class="lockup_user">
<h1>코인 락업 기록</h1>
<br/>
    <h2>락업 목록</h2>
    <table>
        <thead>
            <tr>
                <th>코인 ID</th>
                <th>유저 ID</th>
                <th>코인 이름</th>
                <th>사용자 계정</th>
                <th>사용자 이름</th>
                <th>락업 수량</th>
                <th>시작 날짜</th>
                <th>종료 날짜</th>
                <th>상태</th>
            </tr>
        </thead>
        <tbody id="userTableBody2">
            <!-- 데이터가 여기에 동적으로 들어옵니다 -->
        </tbody>
    </table>
</div>

</body>

<script>

</script>
</html>