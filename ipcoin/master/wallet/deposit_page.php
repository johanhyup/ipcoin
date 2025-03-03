<?php
session_start(); 
require_once dirname(__DIR__) . '/frames/header.php';
?>

<?php
require_once dirname(__DIR__) . '/../config.php'; // 데이터베이스 연결
// 승인/거절 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $approved = isset($_POST['approved']) ? intval($_POST['approved']) : 0;

    if ($user_id > 0) {
        $stmt = $conn->prepare("UPDATE users SET approved = ? WHERE id = ?");
        $stmt->bind_param("ii", $approved, $user_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: deposit_manage.php");
    exit;
}
?>

<style>
    /* 검색바 스타일 (예시) */
    .search-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
        align-items: center;
    }
    .search-bar select,
    .search-bar input {
        height: 38px;
        padding: 5px 10px;
    }
    .search-bar button {
        height: 38px;
    }
    .search-bar .form-control::placeholder {
        color: #aaa;
    }

    /* 테이블 헤더 주황색, 글자 흰색 */
    .table thead th {
        background-color: orange;
        color: #fff;
        white-space: nowrap;
    }
    /* 상태 버튼(미승인 시 파랑), 승인 텍스트(검정) */
    .btn-approve {
        background-color: #007bff; /* 파랑 */
        color: #fff;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 4px;
    }

    /* 상세정보 버튼(노랑) */
    .btn-detail {
        background-color: #ffc107; /* 노랑 */
        color: #212529; /* 검정 */
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 4px;
    }

    /* 모달 내부 스타일 살짝 */
    .modal-header {
        background-color: #f8f9fa;
    }
    .modal-body label {
        font-weight: bold;
    }

    /* 여유 */
    .input-box {
        height: 35px; 
        padding: 5px;
    }
</style>

<body class="hold-transition sidebar-mini layout-fixed">  
     <!-- Top Navigation -->
    <?php require_once dirname(__DIR__) . '/frames/top_nav.php'; ?>
    <!-- Sidebar Navigation -->
    <?php require_once dirname(__DIR__) . '/frames/nav.php'; ?>

<!-- 코인 전체 락업기간 변경 -->
<div class="card-body">
    <div class="actions">
        <label for="update-id">락업 </label>
        <input class="input-box" id="lockupPeriodInput" type="number" placeholder="기간을 입력 (일 단위)">
        <button class="rocktButton" type="button" id="updateLockupButton">전체 락업기간 변경</button>
    </div>
</div>

<!-- 아이디 블락기능 -->
<div class="card-body">
    <div class="actions">
        <div>
            <label for="update-id">블락 </label>
            <input type="text" id="update-id" class="input-box" placeholder="아이디 입력">
            <button class="approve-button" id="approve-button">승인</button>       
            <button class="block-button" id="block-button">블락</button>
        </div>
    </div>
</div>

<!-- 아이디 비밀번호 초기화 -->
<div class="card-body">
    <div class="actions">
        <div>
            <label for="reset-id">비번 </label>
            <input type="text" id="reset-id" class="input-box" placeholder="아이디 입력">
            <button class="edit-button2" id="reset-button">비밀번호 초기화</button>
            <span id="reset-error" class="error"></span>
        </div>
    </div>
</div>

<div class="wrapper">
<!-- 메인 콘텐츠 WRAPPER -->
    <section class="content pt-3">
    <div class="container-fluid">
        <!-- 1) 검색바 -->
        <div class="search-bar">
            <!-- 첫 번째 셀렉트박스: 정렬 기준 -->
            <select id="sortType" class="form-control" style="width:auto;">
                <option value="recent">최근 가입순</option>
                <option value="coin">코인 보유순</option>
                <option value="name">가나다순</option>
            </select>

            <!-- 두 번째 셀렉트박스: 페이지당 표시 개수 -->
            <select id="rowsPerPage" class="form-control" style="width:auto;">
                <option value="10">10개씩 정렬</option>
                <option value="20">20개씩 정렬</option>
                <option value="30">30개씩 정렬</option>
            </select>

            <!-- 검색어 텍스트박스 (placeholder: "이름 / 아이디") -->
            <input type="text" id="searchInput" class="form-control" placeholder="이름 / 아이디" style="width:200px;">
            
            <!-- 검색 버튼 (파랑색) -->
            <button id="searchBtn" class="btn btn-primary">검색</button>

            <!-- === 수정 부분: “수동입금” 버튼 (누르면 전체 회원이 아닌, 특정 회원 지정을 안내) === -->
            <!-- 기존에 있던 "코인 수동입금" 버튼을 그대로 쓰되, 안내를 위해 유지하거나, 
                 필요없다면 삭제해도 무방합니다. 이건 '회원 선택 없이' 바로 /deposit_page.php만 여는 예시. -->
            <div>
                <button id="depositButton" class="btn btn-primary" onclick="openLockupForm()">
                    코인 수동입금(개별)
                </button>
            </div>
        </div>

        <!-- 2) 회원 목록 테이블 (id="userTable") -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="userTable">
                <thead>
                <tr>
                    <th style="width:50px;">No.</th>
                    <th>가입일</th>
                    <th>아이디</th>
                    <th>이름</th>
                    <th>전화번호</th>
                    <th>메일</th>
                    <th>코인</th>
                    <th>상태</th>
                    <th>수동입금</th> <!-- === 수정: 열 헤더 이름 변경 '수동입금' -->
                </tr>
                </thead>
                <tbody>
                    <!-- AJAX로 불러온 내용이 들어갈 예정 -->
                </tbody>
            </table>
        </div>

        <!-- 페이지네이션 영역(필요 시) -->
        <div id="pagination" class="mt-2"></div>

    </div><!-- /.container-fluid -->
    </section>
</div><!-- /.content-wrapper -->

<!-- 회원 상세정보 모달 -->
<div class="modal fade" id="userDetailModal" tabindex="-1" role="dialog" aria-labelledby="userDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"><!-- 좀 넓게 -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailModalLabel">회원 상세정보</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- 내용은 AJAX 로드 -->
                <div id="userDetailContent">로딩중...</div>
            </div>
            <div class="modal-footer">
                <!-- 닫기 버튼 -->
                <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php require_once dirname(__DIR__) . '/../master/frames/footer.php'; ?>

<script>
// === (1) "회원별" 수동입금: 특정 user_id를 파라미터로 deposit_page.php 열기 ===
function openLockupFormForUser(userId) {
    // userId가 유효하다면 새 창으로 연다
    if (userId) {
        const url = `/master/wallet/deposit_page.php?user_id=${userId}`;
        window.open(url, 'lockupForm', 'width=600,height=600,resizable=yes,scrollbars=yes');
    } else {
        alert('유효하지 않은 사용자입니다.');
    }
}

// === (2) "버튼"을 클릭했을 때(전 회원용) deposit_page.php 열기 (기존 코드)
function openLockupForm() {
    window.open('/master/wallet/deposit_page.php', 'lockupForm', 'width=600,height=600,resizable=yes,scrollbars=yes');
}
</script>

<script>
// === 비밀번호 초기화 ===
document.getElementById('reset-button').addEventListener('click', () => {
    const mbId = document.getElementById('reset-id').value.trim();
    const errorSpan = document.getElementById('reset-error');

    if (!mbId) {
        errorSpan.textContent = '아이디를 입력해주세요.';
        return;
    }

    errorSpan.textContent = ''; // 초기화
    fetch('/master/manage_user/reset_password.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `mb_id=${mbId}`,
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('1111로 비밀번호가 초기화되었습니다.');
        } else {
            alert(result.message || '비밀번호 초기화에 실패했습니다.');
        }
    })
    .catch(error => {
        console.error('Error resetting password:', error);
        alert('비밀번호 초기화 중 오류가 발생했습니다.');
    });
});

// === 승인/블락 버튼 ===
document.getElementById('block-button').addEventListener('click', () => {
    const mbId = document.getElementById('update-id').value.trim();
    if (!mbId) {
        alert('아이디를 입력해주세요.');
        return;
    }
    updateApprovalStatus(mbId, 0);
});

document.getElementById('approve-button').addEventListener('click', () => {
    const mbId = document.getElementById('update-id').value.trim();
    if (!mbId) {
        alert('아이디를 입력해주세요.');
        return;
    }
    updateApprovalStatus(mbId, 1);
});

// === 락업기간 일괄 변경 (추가 기능) ===
document.getElementById('updateLockupButton').addEventListener('click', () => {
    const days = document.getElementById('lockupPeriodInput').value.trim();
    if (!days) {
        alert('기간(일 단위)을 입력하세요.');
        return;
    }
    // 이 부분에서 AJAX 등으로 락업 기간을 업데이트하는 로직을 구현
    alert(days + '일 동안 전체 락업기간 변경 (예시).');
});

// ==========================================================
// ============= 회원 목록 관련 (검색 / 페이징) =============
// ==========================================================

$(document).ready(function() {
    // 검색 버튼 클릭 이벤트
    $('#searchBtn').on('click', function() {
        loadUserList(1); // 첫 페이지부터 다시 로드
    });

    // 셀렉트박스 변경 시에도 자동으로 로드하도록
    $('#sortType, #rowsPerPage').on('change', function() {
        loadUserList(1);
    });

    // 엔터키로 검색
    $('#searchInput').on('keyup', function(e) {
        if(e.key === 'Enter') {
            loadUserList(1);
        }
    });

    // 초기 로드
    loadUserList(1);
});

// 유저 목록 불러오기 함수 (page 파라미터)
function loadUserList(page) {
    let sortType = $('#sortType').val();
    let rowsPerPage = $('#rowsPerPage').val();
    let searchText = $('#searchInput').val().trim();

    $.ajax({
        url: '/master/manage_user/user_list_ajax.php', // 실제 검색/페이징 서버URL
        method: 'GET',
        data: {
            page: page,
            sort: sortType,
            limit: rowsPerPage,
            search: searchText
        },
        dataType: 'json',
        success: function(res) {
            if(res.success) {
                renderUserTable(res.data.users, res.data.startIndex);
                renderPagination(res.data.totalPages, page);
            } else {
                alert('회원 목록을 불러오지 못했습니다: ' + res.message);
            }
        },
        error: function(err) {
            console.error(err);
            alert('회원 목록 로드 중 오류가 발생했습니다.');
        }
    });
}

// 테이블 렌더링
function renderUserTable(users, startIndex) {
    let tbody = $('#userTable tbody');
    tbody.empty();

    if(!users || users.length === 0) {
        let emptyRow = `
            <tr>
                <td colspan="9" style="text-align:center;">데이터가 없습니다.</td>
            </tr>
        `;
        tbody.append(emptyRow);
        return;
    }

    users.forEach(function(user, i) {
        let no = startIndex + i; // No. (시작 인덱스 + i)
        let approved = parseInt(user.approved) === 1; // DB에서 approved=1이면 승인됨

        let statusHtml = '';
        if(!approved) {
            // 미승인 -> 승인 버튼
            statusHtml = `<button class="btn-approve" onclick="approveUser(${user.id})">승인</button>`;
        } else {
            // 승인됨 -> 검정 텍스트
            statusHtml = `<span style="color:black;">승인</span>`;
        }

        // 가입일(예: yyyy-mm-dd)
        let createdDate = (user.created_at) ? user.created_at.substring(0, 10) : '';

        // === 수정 부분: 수동입금 버튼 -> user.id 또는 user.user_id (실 DB 컬럼에 맞춰서) ===
        // 이 예시에서는 user.id가 실제 유저 PK라고 가정
        let depositButton = `
            <button class="btn btn-primary" onclick="openLockupFormForUser(${user.id})">
                수동입금
            </button>
        `;

        let rowHtml = `
        <tr>
            <td>${no}</td>
            <td>${createdDate}</td>
            <td>${user.mb_id}</td>
            <td>${user.mb_name}</td>
            <td>${user.mb_tel || ''}</td>
            <td>${user.mb_email || ''}</td>
            <td>${user.coin_balance || 0}</td>
            <td>${statusHtml}</td>
            <td>${depositButton}</td>
        </tr>
        `;
        tbody.append(rowHtml);
    });
}

// 페이지네이션 렌더링
function renderPagination(totalPages, currentPage) {
    let pagDiv = $('#pagination');
    pagDiv.empty();
    
    if(totalPages <= 1) return;

    for(let i = 1; i <= totalPages; i++) {
        let btnClass = (i === currentPage) ? 'btn-primary' : 'btn-outline-primary';
        pagDiv.append(`
            <button class="btn ${btnClass} btn-sm mr-1" onclick="loadUserList(${i})">
                ${i}
            </button>
        `);
    }
}

// 승인 버튼 클릭 시 (Ajax)
function approveUser(userId) {
    if(!confirm('승인 처리하시겠습니까?')) return;
    
    $.ajax({
        url: '/master/manage_user/approve_user.php', // 실제 승인 처리용 서버URL
        method: 'POST',
        data: { user_id: userId },
        dataType: 'json',
        success: function(res) {
            if(res.success) {
                alert('승인되었습니다.');
                loadUserList(1); // 목록 새로고침
            } else {
                alert('승인 실패: ' + res.message);
            }
        },
        error: function(err) {
            console.error(err);
            alert('승인 처리 중 오류가 발생했습니다.');
        }
    });
}

// 상세 모달 열기
function openDetailModal(userId) {
    // 모달 열기 전, 내용 초기화
    $('#userDetailContent').html('로딩중...');

    // Ajax로 상세정보 불러오기
    $.ajax({
        url: '/master/manage_user/user_detail.php',
        method: 'GET',
        data: { user_id: userId },
        dataType: 'html',
        success: function(html) {
            $('#userDetailContent').html(html);
            // 모달 열기
            $('#userDetailModal').modal('show');
        },
        error: function(err) {
            console.error(err);
            alert('상세정보 불러오기 중 오류가 발생했습니다.');
        }
    });
}
</script>

<?php $conn->close(); ?>