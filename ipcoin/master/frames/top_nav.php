<?php
require_once dirname(__DIR__) . '/../config.php';
require_once dirname(__DIR__) . '/../frames/asset.php';
?>

<?php // 페이지 시작: header.php 불러오기
require_once __DIR__ . '/frame/header.php'; ?>

<!-- 여기에 페이지별 내용 (본문) -->
<div class="container-fluid mt-5">
  <!-- Top Navigation Bar -->
  <div class="top-nav">
    <h2 style="left:30px; position:relative;">관리자 메뉴</h2>
    <div class="info">
      <div class="red" id="closing_price_display">현재 ray가격: Loading...</div>
      <div class="red" id="total_deposit_display">총 입금액<br>1,000,000,000</div>
      <div class="red" id="total_withdraw_display">총 출금액<br>0</div>
      <div class="blank"></div>
      <div class="blue" id="total_users">총 회원<br>72</div>
      <div class="blue" id="total_admins">총 관리자<br>4</div>
      <div class="blue" id="new_users">신규회원<br>4</div>
    </div>
    <div class="logout" style="margin-right:30px;">
      <a href="/master/bbs/logout.php">로그아웃</a>
    </div>
  </div>
</div>

<?php // 페이지 끝: footer.php 불러오기
require_once __DIR__ . '/frame/footer.php'; ?>

