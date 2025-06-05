<?php
error_reporting(~E_NOTICE);
include '../layouts/header.php';
$giangVienId = $_SESSION['UserId'];
$hocKy = $_SESSION['hoc_ky'];
?>


<div id="divMain" class="row row2">
    <div class="col-sm-2 sidebar" style="background-color:#2e43d1;padding-right: 0">
        <?php include '../layouts/sidebar.php'; ?>
    </div>
    <div class="col-sm-10 content" style="background-color:rgb(252, 252, 252);">
        <div id="modTitle" class="module-title" style="height:25px;margin-left:-15px;">Giảng dạy</div>
        <?php include '../layouts/dash.php'; ?>
        <?php
        if ($_SESSION['VaiTro'] == 1 && $giangVienId != 110) {
        ?>
            <a href="../layouts/gender.php" class="btn btn-danger" target="_blank">Tải PDF</a>
        <?php
        }
        ?>

        <!-- Phân trang -->
        <div style="text-align: center;">
            <?php
            // $sql_trang = mysqli_query($statement,"SELECT * FROM ma_lop_hp");
            if ($_SESSION['VaiTro'] == 1 && $giangVienId != 110) {
                $sql = "SELECT * from tn_giang_vien gv 
                join tn_giangvien_malophp gvmlhp on gv.id = gvmlhp.giang_vien_id 
                join tn_ma_lop_hp mlhp on mlhp.id = gvmlhp.id_ma_lop_hp
                join tn_hocky_malophp hkmlhp on mlhp.id = hkmlhp.malophp_id 
                join tn_mon_hoc mh on mlhp.id_mon_hoc = mh.id where gvmlhp.giang_vien_id = $giangVienId AND hkmlhp.hocky_id = $hocKy";
                $statement = $connection->prepare($sql);
                $statement->execute();
            } else {
                $sql = "SELECT * from tn_giang_vien gv 
                join tn_giangvien_malophp gvmlhp on gv.id = gvmlhp.giang_vien_id 
                join tn_ma_lop_hp mlhp on mlhp.id = gvmlhp.id_ma_lop_hp 
                join tn_hocky_malophp hkmlhp on mlhp.id = hkmlhp.malophp_id 
                join tn_mon_hoc mh on mlhp.id_mon_hoc = mh.id where hkmlhp.hocky_id = $hocKy";
                $statement = $connection->prepare($sql);
                $statement->execute();
            }
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $monhocs = $statement->fetchAll();
            $row_count = count($monhocs);
            $trang = ceil($row_count / 10);
            ?>

            <ul class="pagination">
                <?php
                for ($i = 1; $i <= $trang; $i++) {
                ?>
                    <li><a href="dashboard.php?trang=<?php echo $i ?>"><?php echo $i ?></a></li>
                <?php
                }
                ?>
            </ul>

        </div>
    </div>
</div>
<script src="../../js/dash.js"></script>