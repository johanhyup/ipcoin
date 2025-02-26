
<?php
require_once __DIR__ . '/config.php'; // 루트 디렉토리의 config.php
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="referrer" content="no-referrer">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Story(IP) Wallet</title>
<?php
// 현재 파일의 디렉토리를 기준으로 frames/nav.php 포함
require_once __DIR__ . '/frames/nav.php';

// 추가 코드 작성 가능
echo "Nav file has been imported!";
?>


  <div class="main">
    <div class="main_wrapper">

<!-------------------------- 구글 아이콘 -------------------------->



        <div class="wallet_balance">
            <div class="item">
                <div class="card">
                    <div class="card-header mb-0">
                        <h4 class="card-title mb-0">Total Balance</h4>
                    </div>
                    <div class="card-body pt-0">
                        <p>
                            <span id="total_balance" class="text-bitcoin cfs-30 fw-bold"> 0 </span>
                            <span class="fw-normal">IP</span>
                        </p>
                        <p id="total_krw" class="sub-text mt-n1">= 0 KRW</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="card">
                    <div class="card-header mb-0">
                        <h4 class="card-title mb-0">Locked Balance</h4>
                    </div>
                    <div class="card-body pt-0">
                        <p>
                            <span id="locked_balance" class="text-danger cfs-30 fw-bold">  0</span>
                            <span class="fw-normal">IP</span>
                        </p>
                        <p id="locked_krw" class="sub-text mt-n1">= 0 KRW</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="main_chart">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">IP Chart</h4>
                </div>
                <div class="card-body" id="deposit">
                    <iframe id="tradingview_323f6"
                    src="https://s.tradingview.com/widgetembed/?frameElementId=tradingview_323f6&amp;symbol=BITHUMB%3AIPKRW&amp;interval=1&amp;hidetoptoolbar=1&amp;symboledit=1&amp;saveimage=1&amp;toolbarbg=f1f3f6&amp;studies=%5B%5D&amp;theme=light&amp;style=1&amp;timezone=Asia%2FSeoul&amp;studies_overrides=%7B%7D&amp;overrides=%7B%7D&amp;enabled_features=%5B%5D&amp;disabled_features=%5B%5D&amp;locale=kr&amp;utm_source=bitauc4630.com&amp;utm_medium=widget&amp;utm_campaign=chart&amp;utm_term=BITHUMB%3AIPKRW" style="width: 100%; height: 420px; margin: 0 !important; padding: 0 !important;" frameborder="0" allowtransparency="true" scrolling="no" allowfullscreen="" class="light_ifrm"></iframe>
                </div>
            </div>
        </div>
        <div class="main_withdraw">
            <div class="card">
                
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">IP Withdraw</h4>
                    <div class="nav d-flex" id="quickTrade" role="tablist">
                        
                        <a class="mb-2 px-1 me-2" id="create_wallet_button">지갑생성</a>
                        <!--
                        <a class="mb-2 px-1 me-2" id="show_address_btn" >주소확인</a>
-->
                        <a class="mb-2 px-1 me-2 active" data-toggle="tab" href="#inner_send" role="tab" aria-selected="true" >내부전송</a>
                        <a class="mb-2 px-1 me-2" data-toggle="tab" href="#outer_send" role="tab" aria-selected="true" >외부전송</a>
                    </div>
                    <div></div>
                </div>
            
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane  active" id="inner_send" role="tabpanel">
                            <form class="form" id="inner_withdrawform" name="inner_withdrawform">
                                <input type="hidden" name="inner_withdrawcoin" id="inner_withdrawcoin" value="RAY">
                                <div class="form-group mb-4">
                                    <div class="cfs-16 mb-1 fw-bold" id="inner_withdraw_balance">
                                    </div>
                                </div>
                                <div class="input-group mb-2">
                                    
                                    <div class="input-group">
                                        <div class="op-bitcoin border-0 input-group-text">출금주소</div>
                                        <input type="text" id="inner_withdraw_address" placeholder="출금할 주소 또는 아이디를 입력해주세요." class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group" style="padding-bottom:5px;">
                                        <div class="op-bitcoin border-0 input-group-text">출금수량</div>
                                        <input type="text" id="inner_withdraw_amount" placeholder="최소출금수량 : 20 IP" class="form-control" >
                                    </div>

                                    <div style="padding-bottom:5px;">
                                        <table width="100%">
                                            <tr>
                                                <td><span class="btn btn-outline-secondary btn-sm  border-1 w-100" onclick="javascript:setWithdrawAmount(0.1);" style="cursor:pointer">10%</span></td>
                                                <td><span class="btn btn-outline-secondary btn-sm  border-1 w-100" onclick="javascript:setWithdrawAmount(0.25);" style="cursor:pointer">25%</span></td>
                                                <td><span class="btn btn-outline-secondary btn-sm  border-1 w-100" onclick="javascript:setWithdrawAmount(0.5);" style="cursor:pointer">50%</span></td>
                                                <td><span class="btn btn-outline-secondary btn-sm  border-1 w-100" onclick="javascript:setWithdrawAmount(1);" style="cursor:pointer">최대</span></td>
                                            </tr>
                                        </table>
                                    </div>
                        
                                    <div class="d-flex align-items-center justify-content-between mt-4">
                                        <p><b>총 출금</b></p>
                                        <p class="text-uppercase"><b><span id="inner_withdraw_total">0</span> IP</b></p>
                                    </div>
                                    <p class="sub-text" style="padding-top:10px;">
                                        <span style="color:red;"><b>※ 출금시 유의사항!</b></span><br>
                                        <span style="font-size:12px;">- 디지털 자산의 특성상 출금신청이 완료되면 취소할 수 없습니다.<br />
                                        - 관리자 확인 후 수동으로 출금이 이루어지기 때문에 시간이 다소 걸릴 수 있습니다.<br />
                                        - ERC20 지갑으로만 송금 가능합니다.  다른 디지털 자산 지갑으로 잘못 송금하는 도와드릴 수 있는 부분이 없습니다.</span>
                                    </p>
                                </div>
                                <div class="form-group btn-group w-100 mt-4">
                                    <button id="inner_withdraw_submit" class="btn btn-danger border-0 w-100">출금신청</button>
                                </div>
                            </form>
                        </div>


<!--           외부  @@@@@@@       출금        -->
                        <div class="tab-pane " id="outer_send" role="tabpanel">
                            <form class="form" id="outer_withdrawform" name="outer_withdrawform">
                                <input type="hidden" name="outer_withdrawcoin" id="outer_withdrawcoin" value="RAY">
                                <div class="form-group mb-4">
                                    <div class="cfs-16 mb-1 fw-bold" id="outer_withdraw_balance">
                                    </div>
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group">
                                        <div class="op-bitcoin border-0 input-group-text">출금주소</div>
                                        <input type="text"  id="outer_withdraw_address" placeholder="출금할 주소를 입력해주세요." class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group" style="padding-bottom:5px;">
                                        <div class="op-bitcoin border-0 input-group-text">출금수량</div>
                                        <input type="text"  id="outer_withdraw_amount" placeholder="최소출금수량 : 20 IP" class="form-control" onkeyup="outer_chkNumber()">
                                    </div>
                                    <div style="padding-bottom:5px;">

                                        <table width="100%">
                                            <tr>
                                                <td><span class="btn btn-outline-secondary btn-sm  border-1 w-100" onclick="javascript:outer_pricetimes(0.1);" style="cursor:pointer">10%</span></td>
                                                <td><span class="btn btn-outline-secondary btn-sm  border-1 w-100" onclick="javascript:outer_pricetimes(0.25);" style="cursor:pointer">25%</span></td>
                                                <td><span class="btn btn-outline-secondary btn-sm  border-1 w-100" onclick="javascript:outer_pricetimes(0.5);" style="cursor:pointer">50%</span></td>
                                                <td><span class="btn btn-outline-secondary btn-sm  border-1 w-100" onclick="javascript:outer_pricetimes(1);" style="cursor:pointer">최대</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="sub-text mt-3">
                                        <div class="d-flex align-items-center justify-content-between mt-">
                                            <p>출금수수료</p>
                                            <p class="text-uppercase">20 IP</p>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between mt-4">
                                        <p><b>총 출금</b></p>
                                        <p class="text-uppercase"><b><span id="outer_withdraw_total">0</span> IP</b></p>
                                    </div>
                                    <p class="sub-text" style="padding-top:10px;">
                                        <span style="color:red;"><b>※ 출금시 유의사항!</b></span><br>
                                        <span style="font-size:12px;">- 디지털 자산의 특성상 출금신청이 완료되면 취소할 수 없습니다.<br />
                                        - 관리자 확인 후 수동으로 출금이 이루어지기 때문에 시간이 다소 걸릴 수 있습니다.<br />
                                        - ERC20 지갑으로만 송금 가능합니다.  다른 디지털 자산 지갑으로 잘못 송금하는 도와드릴 수 있는 부분이 없습니다.</span>

                                    </p>
                                </div>
                                <div class="form-group btn-group w-100 mt-4">
                        
                                    <button id="outer_withdraw_submit" class="btn btn-danger border-0 w-100">출금신청</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main_lpt">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">About IP</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="cfs-40 rounded text-center cw-70 ch-70  d-flex align-items-center justify-content-center">
                            <img src="./img/rdo/IP.png"  style="width:100%;">
                        </div>
                        <div class="ms-2">
                            <h5 class="text-capitalize">IP</h5>
                            <p class="text-uppercase">1 IP = <span id="aboutcoin">0</span> KRW</p>
                        </div>
                    </div>
                    <p class="mt-3 sub-text">
                        What Is Story(IP)?<br>
                        스토리(STORY)는 지적 재산을 위해 특별히 설계된 레이어 1 블록체인입니다.
스토리를 사용하면 블록체인에 IP를 등록할 수 있습니다. 이 IP는 이미지, 노래, RWA, AI 교육 데이터 또는 그 사이의 모든 것이 될 수 있습니다.
귀하의 IP에 사용 조건을 추가할 수 있으며, 이는 "내 IP를 사용하면 상업적 수익의 50%를 내게 지불해야 합니다" 또는 "내 IP의 파생 상품을 만들 수 없습니다"와 같이 다른 사람이 귀하의 IP를 어떻게 사용할 수 있는지 지정하는 것입니다.
IP를 블록체인에서 프로그래밍 가능하게 만들면 AI 에이전트(또는 다른 소프트웨어)와 사람이 모두 IP에서 거래할 수 있는 투명하고 분산된 글로벌 IP 저장소가 탄생합니다.                <br><a href="https://coinone.co.kr/exchange/trade/ip/krw" class="sub-text text-primary">read more</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="main_latest">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Latest Transactions</h4>
                </div>
                <div class="card-body">
                    <div class="" id="transaction_view">
                        <table class="table mb-0 align-middle">
                            <thead class="align-middle">
                                <tr class="text-muted">
                                    <th class="border-0" style="width:50px;"></th>
                                    <th class="border-0 mb_hidden">TID </th>
                                    <th class="border-0" style="width:180px;">Date</th>
                                    <th class="border-0" style="width:180px;">Amount</th>
                                    <th class="border-0" style="width:120px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
<script>
    $(document).ready(function () {
    /**
     * create_wallet 함수
     * 지갑 생성 요청을 서버로 보내고 결과를 처리
     */
    function create_wallet() {
        $.ajax({
            url: "service/create_wallet.php", // PHP 파일 경로
            type: "POST", // HTTP 요청 메서드
            dataType: "json", // 서버 응답 타입 (JSON)
            success: function (response) {
                if (response.success) {
                    // 성공 시 화면에 메시지 표시
                    alert("지갑이 성공적으로 생성되었습니다.\n지갑 주소: " + response.wallet_address);
                    $("#wallet_address_display").text(response.wallet_address); // 화면에 지갑 주소 표시
                } else {
                    // 실패 시 에러 메시지 표시
             //      alert("오류: " + response.message);
                }
            },
            error: function () {
                alert("서버 요청 중 오류가 발생했습니다.");
            }
        });
    }

    // 페이지 로드 시 create_wallet 함수 자동 호출
    create_wallet();

    // 버튼 클릭 이벤트: create_wallet 함수 호출
    $("#create_wallet_button").click(function (e) {
        e.preventDefault(); // 기본 동작 방지
        create_wallet(); // 함수 호출
    });
});
</script>
<script>


    function inner_withdraw_balance(){
        $.ajax({
            type:"get",
            dataType :"html",
            url: '/service/withdraw_balance.php',
            //data: form_data,
            success: function(listdata) {
                $("#inner_withdraw_balance") (listdata);
                $('#outer_withdraw_balance') (listdata);
            }
        });
    }



    wallet_view();
    inner_withdraw_balance();
    aboutcoin();
    transaction_view();
    fetchWalletAddress();
    setInterval(function () {
        wallet_view();
        inner_withdraw_balance();
        aboutcoin();
        transaction_view();
        getbalance();
    }, 3000);
</script>

    <div id="check"></div>
    
    </div>
</div>

    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script>
    var jQuery = $.noConflict(true);
    </script>
    <script src="/theme/fx/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/theme/fx/assets/parallax/js/parallax.min.js"></script>
    <script src="/theme/fx/assets/owlcarousel/js/owl.carousel.min.js"></script>
    <!-- countdown -->
    <script type="text/javascript" src="/theme/fx/assets/countdown/js/kinetic.js"></script>
    <script type="text/javascript" src="/theme/fx/assets/countdown/js/jquery.final-countdown.js"></script>
    <script type="text/javascript" src="/theme/fx/js/bootstrap-dropdownhover.js"></script>
    <script type="text/javascript" src="/theme/fx/js/custom.js"></script>

    
    <script>
        $(document).ready(function () {
            //owl
            jQuery(".owl-carousel").owlCarousel({
                autoplay:true,
                autoplayTimeout:3000,// 1000 -> 1초
                autoplayHoverPause:true,
                loop:true,
                margin:10,//이미지 사이의 간격
                nav:true,
                responsive:{
                    0:{
                        items:2 // 모바일
                    },
                    600:{
                        items:3 // 브라우저 600px 이하
                    },
                    1000:{
                        items:5 // 브라우저 100px 이하
                    }
                }
            });

            // countdown
            'use strict';
            jQuery('.countdown').final_countdown({
                'start': 1362139200,
                'end': 1388461320,
                'now': 1387461319
            });
        });
    </script>
    <script>
        function ajax_update_toastr()
            {
                $.ajax({
                    url:"/plugin/toastr/toastr",
                    type:"POST",
                    success: function(response, status) {
                            $('#check') (response);
                    },
                    eror:function(request, status, error){
                        
                    },
                    complete:function ()
                    {
                        
                    }
                });
            }

            $( document ).ready(function() {
                ajax_update_toastr();
                setInterval("ajax_update_toastr()", 20000);
            });
    </script>

        
</div>


<!-- } 하단 끝 -->

<script>
$(function() {
    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
});
</script>
<script>
        document.getElementById("star").addEventListener("click", () => {
            const body = document.body;

            if (body.classList.contains("dark")) {
                body.classList.remove("dark");
                localStorage.setItem("darkTheme","false");
            } else {
                body.classList.add("dark");
                localStorage.setItem("darkTheme","true");
            }
        });
        const storedTheme = localStorage.getItem("darkTheme");

        if (storedTheme !== null) {
            if (storedTheme ==="true") {
                document.body.classList.add("dark");
            }
        } else if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
            document.body.classList.add("dark");
        }
    </script>


<!-- ie6,7에서 사이드뷰가 게시판 목록에서 아래 사이드뷰에 가려지는 현상 수정 -->
<!--[if lte IE 7]>
<script>
$(function() {
    var $sv_use = $(".sv_use");
    var count = $sv_use.length;

    $sv_use.each(function() {
        $(this).css("z-index", count);
        $(this).css("position","relative");
        count = count - 1;
    });
});
</script>
<![endif]-->


</body>
</html>
