

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bithumb API Test</title>
</head>
<body>
    <h1>Bithumb API Response</h1>
    <pre id="response-output">Loading...</pre>

    <script>
const options = {method: 'GET', headers: {accept: 'application/json'}};

fetch('https://api.bithumb.com/public/ticker/RAY_KRW', options)
  .then(res => res.json())
  .then(res => 
  {console.log(res)
    // Extract the closing_price from the response
    const closingPrice = res.data.closing_price;
    document.getElementById('response-output').textContent = JSON.stringify(res, null, 2); // 페이지에 출력
    document.getElementById('response-output').textContent = 'Closing Price: ' + closingPrice;
  })

  .catch(err => 
  {console.error(err);
    document.getElementById('response-output').textContent = "Error: " + err.message;
  });
        

    </script>
</body>
</html>
<?php
require_once dirname(__DIR__) . '/config.php';

header('Content-Type: application/json');

try {
    // 출금 테이블에서 amount의 합계 계산
    $query = "SELECT SUM(amount) AS total_withdraw FROM withdraw_requests WHERE status = 'pending'";
    $result = $conn->query($query);

    if ($row = $result->fetch_assoc()) {
        $total_withdraw = $row['total_withdraw'] ? $row['total_withdraw'] : 0;
        echo json_encode(["success" => true, "total_withdraw" => $total_withdraw]);
    } else {
        echo json_encode(["success" => false, "message" => "No data found"]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
} finally {
    $conn->close();
}
?>
