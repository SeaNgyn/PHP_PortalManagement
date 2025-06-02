<?php
session_start();
include '../layouts/auth.php';

//session_start();
// Gán mặc định nếu chưa có học kỳ nào được chọn
if (!isset($_SESSION['hoc_ky'])) {
    $_SESSION['hoc_ky'] = 1;
}

// Xử lý khi có request POST để lưu học kỳ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hoc_ky'])) {
    $_SESSION['hoc_ky'] = (int)$_POST['hoc_ky'];
    exit; // Không render HTML nếu là request AJAX
}
?>



<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../css/style.css">
    <!-- <link rel="stylesheet" href="../../css/search.css"> -->



    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <div id="main" class="container-fluid">
        <div id="header" class="row row1" style="height:150px ">
            <div class="col-sm-2 logo-header" style="background-color:#2e43d1;">
                <img src="https://geology.hus.vnu.edu.vn/wp-content/uploads/2020/03/Logo-HUS_Final-01.png" alt="HUS Logo" />
            </div>

            <div class=" col-sm-5 left-header" style="background-color:rgb(226, 235, 237);margin-left: -10px">
                <div class="header-title" style="color:  #222d94">
                    <h3>
                        CỔNG THÔNG TIN ĐÀO TẠO
                    </h3>

                    <h4>
                        <?php
                        if (isset($_SESSION['VaiTro']) && $_SESSION['VaiTro'] == 2) {
                            echo 'DÀNH CHO <span> QUẢN LÝ </span>';
                        } else {
                            echo 'DÀNH CHO <span> GIẢNG VIÊN </span>';
                        }
                        ?>
                    </h4>
                </div>
            </div>
            <div class="container-fluid col-sm-5 right-header" style="background-color:rgb(226, 235, 237);padding:0;">
                <div class="row row3">
                    <div class=" col-sm-9 headerWelcome">
                        Xin chào: <?php echo $_SESSION['tenGiangVien'] ?? 'Giảng viên chưa đăng nhập'; ?>
                        <br>
                        <br>
                        Chọn học kỳ
                        <br>
                        <select id="cboTerm" name="cboTerm"
                            style="background-color:#b8c4e3;padding:0px 5px;width:258px;clear:both">
                            <option value="1" <?= isset($_SESSION['hoc_ky']) && $_SESSION['hoc_ky'] == 1 ? 'selected' : '' ?>>
                                Học kỳ 1 năm 2024-2025
                            </option>
                            <option value="2" <?= isset($_SESSION['hoc_ky']) && $_SESSION['hoc_ky'] == 2 ? 'selected' : '' ?>>
                                Học kỳ 2 năm 2024-2025
                            </option>
                        </select>
                    </div>
                    <div class=" col-sm-3 avatar-container" id="avatarToggle">
                        <!-- Ảnh avatar -->
                        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Avatar"
                            class="avatar" />
                        <!-- Menu dropdown (nội dung thay đổi bằng JS) -->
                        <div style="height: 80px; position: absolute;" class="dropdown" id="dropdownMenu"></div>
                    </div>
                </div>
            </div>
            <div style="background-color: red;width: 100px;width: 100px; margin-left: auto; padding-right: 0;">
            </div>
        </div>

        <!-- Modal Đổi mật khẩu -->
        <div id="changePassModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Đổi mật khẩu</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="changePassForm">
                            <div class="form-group">
                                <label for="oldPass">Mật khẩu cũ</label>
                                <input type="password" name="old_password" id="oldPass" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="newPass">Mật khẩu mới</label>
                                <input type="password" name="new_password" id="newPass" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmPass">Xác nhận mật khẩu</label>
                                <input type="password" name="confirm_password" id="confirmPass" class="form-control" required>
                            </div>
                            <div id="passMessage" class="text-danger"></div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Lưu</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End modal Đổi mật khẩu -->


    </div>
    </div>

    <!-- <script src="../../js/script.js"></script> -->

    <script>
        let isLoggedIn = <?php echo isset($_SESSION['TrangThai']) && $_SESSION['TrangThai'] == 1 ? 'true' : 'false'; ?>;

        const avatarToggle = document.getElementById('avatarToggle');
        const dropdownMenu = document.getElementById('dropdownMenu');

        function renderDropdown() {
            dropdownMenu.innerHTML = isLoggedIn ?
                `<a href="#" onclick="$('#changePassModal').modal('show'); return false;">Đổi mật khẩu</a>
                <a href="logout.php">Đăng xuất</a>` :
                '<a href="index.php">Đăng nhập</a>';
        }




        // Gửi form đổi mật khẩu qua AJAX
        $('#changePassForm').on('submit', function(e) {
            e.preventDefault();

            //kiểm tra mật khẩu theo các yêu cầu sau:Tối thiểu 8 ký tự Có chữ cái đầu viết hoa Có ít nhất một chữ số Có ít nhất một ký tự đặc biệt
            //^                                                 # Bắt đầu chuỗi
            //(?=.*[A-Z])                                       # Ít nhất 1 chữ hoa
            //(?=.*\d)                                          # Ít nhất 1 số
            //(?=.*[!@#$%^&*()_\-+=~`{}[\]|:;"'<>,.?/])         # Ít nhất 1 ký tự đặc biệt
            //[A-Za-z\d!@#$%^&*()_\-+=~`{}[\]|:;"'<>,.?/]{8,}   # Tổng cộng ít nhất 8 ký tự
            //$                                                 # Kết thúc chuỗi

            const newPassword = $('#newPass').val();
            const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=~`{}[\]|:;"'<>,.?/])[A-Za-z\d!@#$%^&*()_\-+=~`{}[\]|:;"'<>,.?/]{8,}$/;

            if (!passwordPattern.test(newPassword)) {
                $('#passMessage').html('Mật khẩu phải có ít nhất 8 ký tự, chữ cái đầu viết hoa, có số và ký tự đặc biệt.');
                return;
            }

            $.ajax({
                url: 'changePass.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#passMessage').html('');
                    if (response === 'OK') {
                        alert('Đổi mật khẩu thành công!');
                        $('#changePassModal').modal('hide');
                        $('#changePassForm')[0].reset();
                    } else {
                        $('#passMessage').html(response);
                    }
                },
                error: function() {
                    $('#passMessage').html('Lỗi hệ thống. Vui lòng thử lại sau.');
                }
            });
        });

        avatarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
            renderDropdown();
        });

        document.addEventListener('click', function() {
            dropdownMenu.style.display = 'none';
        });
    </script>

    <script>
        $('#cboTerm').on('change', function() {
            var hocKy = $(this).val();
            $.post('', {
                hoc_ky: hocKy
            }, function() {
                location.reload(); // Reload lại trang sau khi POST xong
            });
        });
    </script>
</body>

</html>