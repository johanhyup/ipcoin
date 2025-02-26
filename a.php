<?php
// 원하는 비밀번호를 넣으세요
$plainPassword = '1234';

// password_hash()로 안전한 해시 생성
$hashed = password_hash($plainPassword, PASSWORD_DEFAULT);

echo $hashed . "\n";
