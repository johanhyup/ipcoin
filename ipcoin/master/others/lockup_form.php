<?php
require_once dirname(__DIR__) . '/../config.php';
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>코인 락업</title>
    <link rel="stylesheet" href="/master/assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #555;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            border: none;
        }
        button:hover {
            background-color: #45a049;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <h2>코인 락업</h2>
    <form id="lockupForm">
        <label for="lockupUserId">유저 (ID):</label>
        <select id="lockupUserId" name="lockupUserId" required>
            <option value="">유저를 선택하세요</option>
            <?php
            $result = $conn->query("SELECT id, mb_id FROM users");
            while ($user = $result->fetch_assoc()) {
                echo "<option value='{$user['id']}'>{$user['mb_id']}</option>";
            }
            ?>
        </select>
        <br>

        <label for="lockupAmount">락업할 코인 양:</label>
        <input type="number" step="0.00000001" id="lockupAmount" name="lockupAmount" required><br>

        <label for="lockupEndDate">락업 종료일:</label>
        <input type="date" id="lockupEndDate" name="lockupEndDate" required><br>

        <button type="submit">락업하기</button>
    </form>

    <script>
        document.getElementById("lockupForm").addEventListener("submit", (e) => {
            e.preventDefault();

            const formData = {
                user_id: document.getElementById("lockupUserId").value,
                amount: document.getElementById("lockupAmount").value,
                end_date: document.getElementById("lockupEndDate").value
            };

            fetch("/service/lockup_coin.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData)
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("코인이 성공적으로 락업되었습니다.");
                    window.opener.location.reload(); // 부모 페이지 새로고침
                    window.close(); // 성공 후 창 닫기
                } else {
                    alert(`락업 실패: ${data.message}`);
                }
            })
            .catch((error) => console.error("Error:", error));
        });
    </script>
</body>
</html>
