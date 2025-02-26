<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>빗썸 비트코인 실시간 가격 정보</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        h1 { font-size: 2rem; }
    </style>
</head>
<body>
    <h1>빗썸 비트코인 실시간 가격 정보</h1>
    <p>현재 비트코인 가격: <span id="btc-price">가져오는 중...</span></p>
    <script>
        const websocket = new WebSocket('wss://pubwss.bithumb.com/pub/ws');

        websocket.onopen = () => {
            console.log('WebSocket connected');
            websocket.send(JSON.stringify({
                type: 'ticker',
                symbols: ['RAY_KRW'],
                tickTypes: ['1H']
            }));
        };

        websocket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            if (data.hasOwnProperty('content') && data.content.tickType === '1H') {
                const btcPrice = data.content.closePrice;
                document.getElementById('btc-price').textContent = btcPrice.toLocaleString() + ' KRW';
            }
        };

        websocket.onclose = () => {
            console.log('WebSocket disconnected');
        };

        websocket.onerror = (error) => {
            console.error('WebSocket error:', error);
        };

                const options = { method: 'GET', headers: { accept: 'application/json' } };

        fetch('https://api.bithumb.com/v1/ticker?markets=KRW-RAY', options)
            .then(response => response.json())
            .then(response => {
                console.log(response); // 콘솔에 출력
                console.log(response.market);
                document.getElementById('response-output').textContent = JSON.stringify(response, null, 2); // 페이지에 출력

            })
            .catch(err => {
                console.error(err);
                document.getElementById('response-output').textContent = "Error: " + err.message;
            });
    </script>
</body>
</html>