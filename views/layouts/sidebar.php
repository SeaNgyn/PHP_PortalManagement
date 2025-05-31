<ul style="height: 100vh;">
    <li style="margin-top: 27px;"><a href="home.php">Trang chủ</a></li>
    <?php
    if (isset($_SESSION['VaiTro']) && $_SESSION['VaiTro'] != 2) {
        echo '<li><a href="profile.php">Hồ sơ</a></li>';
    }
    ?>
    <li><a href="search.php">Tìm kiếm</a></li>
    <li><a href="dashboard.php">Giảng dạy</a></li>
    <?php

    // Chỉ hiện mục "Quản Lý" nếu VaiTro khác 1
    if (isset($_SESSION['VaiTro']) && $_SESSION['VaiTro'] != 1) {
        echo '<li><a href="manager.php">Quản Lý</a></li>';
    }
    ?>
</ul>


