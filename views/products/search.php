<?php include '../layouts/header.php'; ?>
<?php
$hocKy = $_SESSION['hoc_ky'];
$limit = 15;
include '../../configuration/database.php';
if (isset($_GET['trang'])) {
    $page = $_GET['trang'];
} else {
    $page = '';
}

if ($page == '' || $page == 1) {
    $begin = 0;
} else {
    $begin = ($page * $limit) - $limit;
}
$totalRows = 0;
$monhocs = [];

try {
    if ($connection === null) {
        throw new Exception("Database connection is not established.");
    }
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchKey = $_GET['search'];
        $sql = "SELECT * FROM tn_mon_hoc WHERE ten_mon LIKE :keyword OR ma_mon LIKE :keyword";
        $statement = $connection->prepare($sql);
        $statement->execute([':keyword' => '%' . $searchKey . '%']);
        $totalRows = $statement->fetchAll(PDO::FETCH_ASSOC);

        //phan trang 

        $sql = "SELECT * FROM tn_mon_hoc WHERE ten_mon LIKE :keyword OR ma_mon LIKE :keyword LIMIT $begin, $limit";
        $statement = $connection->prepare($sql);
        $statement->execute([':keyword' => '%' . $searchKey . '%']);
        $monhocs = $statement->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Nếu không tìm kiếm, lấy tất cả môn học
        $sql = "SELECT * FROM tn_mon_hoc";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $totalRows = $statement->fetchAll(PDO::FETCH_ASSOC);
        //phan trang
        $sql = "SELECT * FROM tn_mon_hoc LIMIT $begin, $limit";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $monhocs = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage();
}
?>


<div id="divMain" class="row row2">
    <div class="col-sm-2 sidebar" style="background-color:#2e43d1;padding-right: 0">
        <?php include '../layouts/sidebar.php'; ?>
    </div>
    <div class="col-sm-10 content" style="background-color:rgb(252, 252, 252);">
        <div id="modTitle" class="module-title" style="height:25px;margin-left:-15px;">Tìm kiếm</div>
        <div class="search-container" style="height: 5% ; margin-bottom: 50px;">
            <form method="get" action="">
                <input type="text" id="searchCode" name="search" placeholder="Nhập từ khóa..."
                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <input type="submit" class="search-button" value="Tìm kiếm" style="background-color: #2e43d2;">
            </form>
        </div>


        <div class="container" style="background-color: #fcf9f9; padding: 20px;">
            <div class="panel panel-default">
                <div class="panel-heading text-center" style=" background-color: blue;">
                    <h3 class="panel-title" style="font-size: 20px; font-family: Helvetica; color: white; ">
                        Thông tin môn học
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="courseTable" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr style="background-color: #f5f5f5; color: 000;">
                                    <!-- <th>STT</th> -->
                                    <th>Mã học phần</th>
                                    <th>Tên học phần</th>
                                    <th>Số tín chỉ</th>
                                    <!-- <th>Phân bố tín chỉ</th>
                                    <th>Khoá</th>
                                    <th>Thứ</th>
                                    <th>Tiết</th>
                                    <th>Số lượng sinh viên</th>
                                    <th>Giảng đường</th>
                                    <th>Ngôn ngữ giảng dạy</th>
                                    <th>Giảng viên</th> -->
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody style="color: #4f535a;">
                                <?php if (empty($monhocs)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Không có kết quả.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($monhocs as $monhoc) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($monhoc['ma_mon']) ?></td>
                                            <td><?= htmlspecialchars($monhoc['ten_mon']) ?></td>
                                            <td><?= htmlspecialchars($monhoc['so_tin_chi']) ?></td>
                                            <td><a href="detail.php?id=<?= $monhoc['id'] ?>">Xem chi tiết</a></td>
                                        </tr>
                                    <?php } ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <!-- PHÂN TRANG -->
        <div style="text-align: center;">
            <?php
            $trang = ceil(count($totalRows) / $limit);
            if ($trang > 1):
            ?>
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $trang; $i++) { ?>
                        <li <?= ($i == $page) ? 'class="active"' : '' ?>>
                            <a
                                href="search.php?trang=<?= $i ?><?= isset($searchKey) ? '&search=' . urlencode($searchKey) : '' ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>
</div>