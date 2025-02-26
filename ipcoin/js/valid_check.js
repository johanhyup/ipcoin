document.addEventListener("DOMContentLoaded", function () {
    // 출금 신청 버튼 클릭 시
    document.getElementById("inner_withdraw_submit").addEventListener("click", function (e) {
        e.preventDefault(); // 기본 이벤트 방지
        
        const withdrawAmountInput = document.getElementById("inner_withdraw_amount");
        const withdrawAmount = parseFloat(withdrawAmountInput.value.trim());

        // 유효성 검사: 출금수량이 20 RAY 미만인지 확인
        if (isNaN(withdrawAmount) || withdrawAmount < 20) {
            alert("출금수량은 최소 20 RAY 이상이어야 합니다.");
            withdrawAmountInput.focus();
            return false;
        }

    });
});

document.addEventListener("DOMContentLoaded", function () {
    // 출금 신청 버튼 클릭 시
    document.getElementById("outer_withdraw_submit").addEventListener("click", function (e) {
        e.preventDefault(); // 기본 이벤트 방지
        
        const withdrawAmountInput = document.getElementById("outer_withdraw_submit");
        const withdrawAmount = parseFloat(withdrawAmountInput.value.trim());

        // 유효성 검사: 출금수량이 20 RAY 미만인지 확인
        if (isNaN(withdrawAmount) || withdrawAmount < 20) {
            alert("출금수량은 최소 20 RAY 이상이어야 합니다.");
            withdrawAmountInput.focus();
            return false;
        }

    });
});




function inner_pricetimes(price){
    var price = price;
    var priceD = $('#inner_a_balance').val();

    if(priceD == 0){
        window.alert("출금가능한 수량이 부족합니다. \n\n락업수량과 출금가능수량을 확인해주세요");
        $('#inner_withdraw_amount').focus();
        return false;
    }

    num1 = Number(price);
    num2 = Number(priceD);
    pricesum = num1 * num2;
    pricesum2 = pricesum;
    $('#inner_withdraw_amount').val(pricesum);
    //pricesum2 = numberWithCommas(pricesum);
    $("#inner_withdraw_total") (numberWithCommas(pricesum.toFixed(2)));
}

function outer_pricetimes(price){
    var price = price;
    var priceD = $('#outer_a_balance').val();

    if(priceD == 0){
        window.alert("출금가능한 수량이 부족합니다. \n\n락업수량과 출금가능수량을 확인해주세요");
        $('#outer_withdraw_amount').focus();
        return false;
    }

    num1 = Number(price);
    num2 = Number(priceD);

    pricesum = num1 * num2;
    $('#outer_withdraw_amount').val(pricesum);
    //pricesum2 = numberWithCommas(pricesum);
    $("#outer_withdraw_total") (numberWithCommas(pricesum.toFixed(2)));
}

$('#inner_withdraw_submit').click(function() {

  var theForm = document.inner_withdrawform;
  var balanceD = $('#inner_a_balance').val();

    if(theForm.inner_withdraw_address.value ==""){
        window.alert("출금주소 또는 아이디를 입력하세요.");
        theForm.inner_withdraw_address.focus();
        return false;
    }

    if(theForm.inner_withdraw_amount.value < 20){
        window.alert("출금수량은 최소 20RAY 이상입니다.");
        theForm.inner_withdraw_amount.focus();
        return false;
    }

    if(theForm.inner_withdraw_amount.value > Number(balanceD)){
        window.alert("출금가능한 수량이 부족합니다. \n\n락업수량과 출금가능수량을 확인해주세요");
        theForm.inner_withdraw_amount.focus();

        return false;
    }
});
$('#outer_withdraw_submit').click(function() {
  var theForm = document.outer_withdrawform;
  var balanceD = $('#outer_a_balance').val();

    if(theForm.outer_withdraw_address.value ==""){
        window.alert("출금주소를 입력하세요.");
        theForm.outer_withdraw_address.focus();
        return false;
    }

    if(theForm.outer_withdraw_amount.value > Number(balanceD)){
        window.alert("출금가능한 수량이 부족합니다. \n\n락업수량과 출금가능수량을 확인해주세요");
        theForm.outer_withdraw_amount.focus();

        return false;
    }
});
$('#outer_withdraw_submit').click(function() {
  var theForm = document.outer_withdrawform;
  var balanceD = $('#outer_a_balance').val();

    if(theForm.outer_withdraw_address.value ==""){
        window.alert("출금주소를 입력하세요.");
        theForm.outer_withdraw_address.focus();
        return false;
    }

    if(theForm.outer_withdraw_amount.value < 20){
        window.alert("출금수량은 최소 20RAY 이상입니다.");
        theForm.outer_withdraw_amount.focus();
        return false;
    }

    if(theForm.outer_withdraw_amount.value > Number(balanceD)){
        window.alert("출금가능한 수량이 부족합니다. \n\n락업수량과 출금가능수량을 확인해주세요");
        theForm.outer_withdraw_amount.focus();

        return false;
    }
});

