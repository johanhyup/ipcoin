<?php
// 페이지 시작: header.php 불러오기
require_once __DIR__ . '/frames/header.php';
?>

<!-- 여기에 페이지별 내용 (본문) -->
<div class="container-fluid mt-5">
  <div class="card">
    <div class="card-header">코인 락업 기록</div>
    <div class="card-body">
      <h2>락업 목록</h2>
      <table style="width:100%; border-collapse:collapse;">
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
    </div><!-- card-body -->
  </div><!-- card -->
</div><!-- container-fluid -->

<?php
// 페이지 끝: footer.php 불러오기
require_once __DIR__ . '/frames/footer.php';
?>