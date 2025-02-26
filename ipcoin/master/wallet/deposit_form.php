<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>입금하기</title>
    <link rel="stylesheet" href="/master/assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        form {
            max-width: 500px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            font-size: 12px;
        }

        .wallet-address {
            font-style: italic;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>입금하기</h1>
    <form id="depositForm">
        <label for="coinName">코인 이름:</label>
        <input type="text" id="coinName" name="coinName" value="IP" readonly>
        <br>
        <label for="depositAddress">입금 주소:</label>
        <input type="text" id="depositAddress" name="depositAddress" readonly>
        <br>
        <label for="depositAmount">입금 금액:</label>
        <input type="number" step="0.00000001" id="depositAmount" name="depositAmount" required>
        <br>
        <button type="submit">입금하기</button>
    </form>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const params = new URLSearchParams(window.location.search);
        const userId = params.get("user_id"); // 전달받은 user_id

        if (!userId) {
            alert("잘못된 이유: user_id가 전달되지 않았습니다.");
            return;
        }

        // 사용자 정보 검색
        fetch(`/master/service/get_users.php`)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    const user = data.users.find(u => u.user_id == userId);
                    if (user && user.wallet_address) {
                        document.getElementById("depositAddress").value = user.wallet_address;
                    } else {
                        alert("사용자의 지감 주소를 검색할 수 없습니다.");
                    }
                } else {
                    alert("사용자 목록을 불러올 수 없습니다.");
                }
            })
            .catch((error) => {
                console.error("Error fetching user data:", error);
                alert("서버 요청중 오류가 발생했습니다.");
            });

        // 입금 형식 제출
        document.getElementById("depositForm").addEventListener("submit", (e) => {
            e.preventDefault();

            const formData = {
                user_id: userId,
                coin_name: document.getElementById("coinName").value,
                deposit_address: document.getElementById("depositAddress").value,
                amount: parseFloat(document.getElementById("depositAmount").value),
            };

            fetch("/master/wallet/deposit.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert("입금이 성공적으로 진행되었습니다.");
                        window.opener.location.reload(); // 부모 페이지 새로고침
                        window.close(); // 새 창 닫기
                    } else {
                        alert("입금 실패: " + data.message);
                    }
                })
                .catch((error) => {
                    console.error("Error processing deposit:", error);
                    alert("입금 처리 중 오류가 발생했습니다.");
                });
        });
    });
    </script>
</body>
</html>
