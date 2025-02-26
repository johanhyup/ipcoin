
function chkNumber(obj){
        var tmpValue = $(obj).val().replace(/[^0-9,]/g,'');
        tmpValue = tmpValue.replace(/[,]/g,'');
        $('#hp').val(tmpValue);
        //obj.value = numberWithCommas(tmpValue);
    }

    function emailCheck(email_address){     
        email_regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
        if(!email_regex.test(email_address)){ 
            return false; 
        }else{
            return true;
        }
    }
$(function() {
     $.post("https://api.blockcypher.com/v1/eth/main/addrs", function (data) {
         $("#mb_4").val("L0xe" + data.address); 
     });
    $("#reg_zip_find").css("display", "inline-block");

                });

// ID 검사: 한글 및 특수문자 불가
function validateId(id) {
    const idRegex = /^[a-zA-Z0-9]+$/; // 영문 대소문자와 숫자만 허용
    if (!idRegex.test(id)) {
        return "아이디는 영문과 숫자만 입력 가능합니다.";
    }
    return "";
}

// 생년월일 검사: 오늘 날짜보다 이전인지 확인
function validateBirthDate(birthDate) {
    const selectedDate = new Date(birthDate); // 입력된 생년월일
    const today = new Date(); // 오늘 날짜

    // 오늘 날짜보다 이후인 경우
    if (selectedDate >= today) {
        return "생년월일은 오늘보다 이전 날짜로 설정해야 합니다.";
    }
    return "";
}

// submit 최종 폼체크
function fregisterform_submit(f)
{
   // 아이디 검사
   const idError = validateId(f.mb_id.value);
   if (idError) {
       alert(idError);
       f.mb_id.focus();
       return false;
   }

    if (f.w.value == "") {
        if (f.mb_password.value.length < 4) {
            alert("비밀번호를 4글자 이상 입력하십시오.");
            f.mb_password.focus();
            return false;
        }
    }

    if (f.mb_password.value != f.mb_password_re.value) {
        alert("비밀번호가 같지 않습니다.");
        f.mb_password_re.focus();
        return false;
    }

    if (f.mb_password.value.length > 0) {
        if (f.mb_password_re.value.length < 4) {
            alert("비밀번호를 4글자 이상 입력하십시오.");
            f.mb_password_re.focus();
            return false;
        }
    }
    // 휴대폰 번호 검사
// 휴대폰 번호 검사
const phoneRegex = /^0\d{2}-(\d{3}|\d{4})-\d{4}$/; // 010-123-1234 또는 010-1234-1234 형식 검사
if (!phoneRegex.test(f.mb_tel.value)) {
    alert("휴대폰 번호 형식이 올바르지 않습니다. 예: 010-1234-5678 또는 010-123-5678");
    f.mb_tel.focus();
    return false;
}

// 휴대폰 번호 길이 검사
const rawPhoneNumber = f.mb_tel.value.replace(/-/g, ''); // 하이픈 제거 후 숫자 길이 확인
if (rawPhoneNumber.length !== 10 && rawPhoneNumber.length !== 11) {
    alert("휴대폰 번호는 10자리 또는 11자리여야 합니다.");
    f.mb_tel.focus();
    return false;
}

    // 이름 검사
    if (f.w.value=="") {
        if (f.mb_name.value.length < 1) {
            alert("이름을 입력하십시오.");
            f.mb_name.focus();
            return false;
        }
    // 생년월일 검사
    const birthDateError = validateBirthDate(f.birth_date.value);
    if (birthDateError) {
        alert(birthDateError);
        f.birth_date.focus();
        return false;
    }
        /*
        var pattern = /([^가-힣\x20])/i;
        if (pattern.test(f.mb_name.value)) {
            alert("이름은 한글로 입력하십시오.");
            f.mb_name.select();
            return false;
        }
        */
    }

    
    // 닉네임 검사
    /*
    if ((f.w.value == "") || (f.w.value == "u" && f.mb_nick.defaultValue != f.mb_nick.value)) {
        var msg = reg_mb_nick_check();
        if (msg) {
            alert(msg);
            f.reg_mb_nick.select();
            return false;
        }
    }*/

    

    
    if (typeof f.mb_icon != "undefined") {
        if (f.mb_icon.value) {
            if (!f.mb_icon.value.toLowerCase().match(/.(gif|jpe?g|png)$/i)) {
                alert("회원아이콘이 이미지 파일이 아닙니다.");
                f.mb_icon.focus();
                return false;
            }
        }
    }

    if (typeof f.mb_img != "undefined") {
        if (f.mb_img.value) {
            if (!f.mb_img.value.toLowerCase().match(/.(gif|jpe?g|png)$/i)) {
                alert("회원이미지가 이미지 파일이 아닙니다.");
                f.mb_img.focus();
                return false;
            }
        }
    }

    
    document.getElementById("btn_submit").disabled = "disabled";

    return true;
}

function showRegistForm2()
{
//     var stepsSection = $('.steps-section');
//     var stepSection1 = $('.step-section-1');
//     var stepSection2 = $('.step-section-2');
//     var stepSection1Height = stepSection1.outerHeight();
//     var stepSection2Height = stepSection2.outerHeight();
//     var backBtn = $('.back-btn');
//     var registrationStep2 = $('.registration-step-2');
//     var filledInClass = 'filled-in';
// //	var frm = $('#user_request_form'); 
var reg_cd = $('#mb_recommend').val(); 


if(reg_cd == "")
{
    alert("추천지점코드를 입력해주세요!");
    $('#mb_recommend').focus();
    return;
}

if(reg_cd.length<1 || reg_cd.length>20)
{
    alert("추천지점코드를 정확히 입력해주세요!");
    $('#mb_recommend').focus();
    return;
}

// ajax post send 
$.ajax({ 
    type: "POST", 
    url: "./checkAgency.php",  
    data: "ACODE=" + reg_cd, 
    // error : function(){
    //     alert(" [에러] 서버 연결이 원활하지 않습니다! 잠시 후 다시 시도해주세요!");
    //     return;
    // },
    success: function(msg){ 
        
       
        if(msg.trim() == "1")
        {
            $("#agyChkComplete").val("Y");
            $("#btnAgylCheck").text("확인완료");
        }else{
            $("#agyChkComplete").val("N");
            alert("추천지점코드를 정확히 입력해주세요!!");
            $('#mb_recommend').focus();
            $('#mb_recommend').val(""); 
            return;
        }
    }
    
}); 
function formatPhoneNumber(el) {
    // 숫자 이외의 문자 제거
    let val = el.value.replace(/\D/g, '');
  
    let formatted = '';
    // 한국 휴대폰 번호 형태: 010-####-#### 
    // 숫자 갯수에 따라 동적으로 하이픈 추가
    if(val.length > 10) {
      // 010-1234-5678 형태
      formatted = val.slice(0,3) + '-' + val.slice(3,7) + '-' + val.slice(7,11);
    } else if(val.length > 6) {
      // 010-1234-567 형태 (아직 끝자리가 4자리가 안될 경우)
      formatted = val.slice(0,3) + '-' + val.slice(3,7) + '-' + val.slice(7);
    } else if(val.length > 3) {
      // 010-1234 형태 (중간번호까지 입력한 상태)
      formatted = val.slice(0,3) + '-' + val.slice(3);
    } else {
      // 010 형태 혹은 그 이하
      formatted = val;
    }
  
    el.value = formatted;
  }


}



function formatPhoneNumber(el) {
    // 숫자 이외의 문자 제거
    let val = el.value.replace(/\D/g, '');
  
    let formatted = '';
    // 한국 휴대폰 번호 형태: 010-####-#### 
    // 숫자 갯수에 따라 동적으로 하이픈 추가
    if(val.length > 10) {
      // 010-1234-5678 형태
      formatted = val.slice(0,3) + '-' + val.slice(3,7) + '-' + val.slice(7,11);
    } else if(val.length > 6) {
      // 010-1234-567 형태 (아직 끝자리가 4자리가 안될 경우)
      formatted = val.slice(0,3) + '-' + val.slice(3,7) + '-' + val.slice(7);
    } else if(val.length > 3) {
      // 010-1234 형태 (중간번호까지 입력한 상태)
      formatted = val.slice(0,3) + '-' + val.slice(3);
    } else {
      // 010 형태 혹은 그 이하
      formatted = val;
    }
  
    el.value = formatted;
  }