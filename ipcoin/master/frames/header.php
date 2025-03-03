<?php
// 오류 메시지 표시 활성화
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
// 세션 시작
session_start();

// config.php 및 필요한 파일 불러오기
require_once __DIR__ . '/../../config.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>관리자 페이지</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="/master/AdminLte/dist/css/adminlte.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/master/AdminLte/plugins/fontawesome-free/css/all.min.css">
  <!-- 커스텀 CSS -->
  <link rel="stylesheet" href="/master/assets/css/main.css">
</head>

<!-- body 태그에 AdminLTE 기본 클래스 지정 -->
<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper"><!-- AdminLTE 래퍼 시작 -->

  <!-- 상단 네비게이션 -->
  <?php require_once __DIR__ . '/top_nav.php'; ?>

  <!-- 왼쪽 사이드바 -->
  <?php require_once __DIR__ . '/nav.php'; ?>

  <!-- 메인 콘텐츠 WRAPPER -->
  <div class="content-wrapper">