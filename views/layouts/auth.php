<?php
//session_start();
// Nếu chưa đăng nhập, chuyển về trang index.php (trang login)
if (!isset($_SESSION['UserId'])) {
    header("Location: ../products/index.php"); // hoặc đường dẫn phù hợp
    exit;
}
?>