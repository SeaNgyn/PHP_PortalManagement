<?php 
include '../../configuration/database.php'; 
// $monhocs = [];
$limit = 10;
if (isset($_GET['trang'])){
    $page = $_GET['trang'];
}else{
    $page = '';
}

if($page ==''|| $page ==1){
    $begin = 0;
}else{
    $begin = ($page*$limit)-$limit;
}
$totalRows = [];
$monhocs = [];
try {
    if ($connection === null) {
        throw new Exception("Database connection is not established.");
    }
    $id_mon = $_GET['id'] ?? null;

    if ($id_mon) {
        $sql = "SELECT *
        FROM tn_mon_hoc mh
        JOIN tn_ma_lop_hp mlhp ON mh.id = mlhp.id_mon_hoc
        LEFT JOIN tn_giangvien_malophp gvmlhp ON mlhp.id = gvmlhp.id_ma_lop_hp
        LEFT JOIN tn_giang_vien gv ON gvmlhp.giang_vien_id = gv.id
        WHERE mh.id = $id_mon ;";
                // Lấy thông tin môn học
                // $sqlMon = "SELECT * FROM mon_hoc WHERE id = ?";
                $statement = $connection->prepare($sql);
                    $statement->execute();
                    $statement->setFetchMode(PDO::FETCH_ASSOC);
                    $totalRows = $statement->fetchAll();

        $sql = "SELECT *
            FROM tn_mon_hoc mh
            JOIN tn_ma_lop_hp mlhp ON mh.id = mlhp.id_mon_hoc
            LEFT JOIN tn_giangvien_malophp gvmlhp ON mlhp.id = gvmlhp.id_ma_lop_hp
    LEFT JOIN tn_giang_vien gv ON gvmlhp.giang_vien_id = gv.id
WHERE mh.id = $id_mon  LIMIT $begin, $limit;";
        // Lấy thông tin môn học
        // $sqlMon = "SELECT * FROM mon_hoc WHERE id = ?";
        $statement = $connection->prepare($sql);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $monhocs = $statement->fetchAll();
 
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();}
    

?>
<?php include '../layouts/header.php'; ?>

<div id="divMain" class="row row2">
    <div class="col-sm-2 sidebar" style="background-color:#2e43d1;padding-right: 0">
        <?php include '../layouts/sidebar.php'; ?>
    </div>
    <div class="col-sm-10 content" style="background-color:rgb(252, 252, 252);">
        <div id="modTitle" class="module-title" style="height:25px;margin-left:-15px;">Chi tiết</div>
        <div class="panel panel-primary " style="margin-top: 20px; height: auto; ">
            <div class="panel-heading"
                style="height: auto; font-size: 20px; font-family: Helvetica; color: white;background-color:blue">Danh
                sách môn chi tiết học phần </div>
            <div class="panel-body" style="height: auto;">
                <table class="table table-striped" id="courseTable">
                    <thead>
                        <tr style="background-color: #f5f5f5; color: 000;">
                            <th>STT</th>
                            <th>Mã học phần</th>
                            <th>Tên học phần</th>
                            <th>Số tín chỉ</th>
                            <th>Mã lớp học phần</th>
                            <th>Phân bố tín chỉ</th>
                            <th>Loại lớp</th>
                            <th>Khoa</th>
                            <th>Thứ</th>
                            <th>Tiết</th>
                            <th>Số lượng sinh viên</th>
                            <th>Giảng đường</th>
                            <th>Ngôn ngữ giảng dạy</th>
                            <!-- <th>Thời gian dạy</th> -->
                            <th>Giảng viên</th>
                        </tr>
                    </thead>
                    <tbody style="color: #4f535a;">
                        <?php
                    foreach ($monhocs as $monhoc) {
                        $STT = $monhoc['STT'] ?? '';
                        $maHp = $monhoc['ma_mon'] ?? '';
                        $tenHp = $monhoc['ten_mon'] ?? '';
                        $soTc = $monhoc['so_tin_chi'] ?? '';
                        $maLopHp = $monhoc['ten_ma_lop_hp'] ?? '';
                         $phanBoTc = $monhoc['phan_bo_tin_chi'] ?? '';
                        $loaiLop = $monhoc['loai_lop'] ?? '';
                        $nganh = $monhoc['nganh'] ?? '';
                        $khoa = $monhoc['khoa'] ?? '';
                        $chuongTrinhDt = $monhoc['chuong_trinh_dao_tao'] ?? '';
                        $soLuongSv = $monhoc['so_luong_sv'] ?? '';
                        $thu = $monhoc['thu'] ?? '';
                        $tiet = $monhoc['tiet'] ?? '';
                        $ngonNguGiangDay = $monhoc['ngon_ngu_giang_day'] ?? '';
                        $giangVien = $monhoc['Name'] ?? '';
                        $giangDuong = $monhoc['giang_duong'] ?? '';
                        echo '<tr>';
                        echo "<td>$STT</td>";
                        echo "<td>$maHp</td>";
                        echo "<td>$tenHp</td>";
                        echo "<td >$soTc</td>";
                        echo "<td>$maLopHp</td>";
                        echo "<td>$phanBoTc</td>";

                        echo "<td>$loaiLop</td>";
                        // echo "<td>$nganh</td>";
                        echo "<td>$khoa</td>";
                        //echo "<td>$chuongTrinhDt</td>";
                        echo "<td>$thu</td>";
                        echo "<td>$tiet</td>";
                        echo "<td>$soLuongSv</td>";
                        echo "<td>$giangDuong</td>";
                        echo "<td>$ngonNguGiangDay</td>";
                        // echo "<td>" . calculateTimeToStudy($phanBoTc, $soLuongSv, $ngonNguGiangDay, $thu, $tiet, $loaiLop,$countGvMap[$maLopHp]) . "</td>";
                        echo "<td>$giangVien</td>";
                        // echo '</tr>';
                    }
                    //echo '<a href="index.php">Click here to back Home to upload file</a>';
                    ?>
                        <!-- Các dòng dữ liệu khác có thể thêm vào đây -->
                    </tbody>
                </table>
            </div>

        </div>
        <!-- PHÂN TRANG -->
        <div style="text-align: center;">
            <?php
            $trang = ceil(count($totalRows) / $limit);?>
            <ul class="pagination">
                <?php
                for ($i = 1; $i <= $trang; $i++) {
                ?>
                <li><a href="detail.php?trang=<?php echo $i; ?>&id=<?php echo $id_mon; ?>"><?php echo $i; ?></a></li>
                <!-- <li class="disabled"><a href="#">«</a></li>
                <li class="active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">»</a></li> -->

                <?php
                }
                ?>
            </ul>

        </div>

    </div>
</div>