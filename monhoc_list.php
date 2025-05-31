<?php
include './configuration/database.php';
try {
    $sql = "SELECT * from mon_hoc;";
    if ($connection === null) {
        throw new Exception("Database connection is not established.");
    }
    $statement = $connection->prepare($sql);
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);
    $monhocs = $statement->fetchAll();
    // echo '<ul class="list-group">';
    // foreach ($monhocs as $monhoc) {
    //     $maHp = $monhoc['ma_hp'] ?? '';
    //     $tenHp = $monhoc['ten_hp'] ?? '';
    //     $soTc = $monhoc['so_tin_chi'] ?? '';
    //     echo '<li class="list-group-item">';
    //     echo "<p>$maHp</p>";
    //     echo "<p>$tenHp</p>";
    //     echo "<p>$soTc</p>";
    //     echo "</li>";
    // }
    // echo '</ul>';
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <div>
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã học phần</th>
                    <th>Tên học phần</th>
                    <th>Số tín chỉ</th>
                    <th>Mã lớp học phần</th>
                    <th>Phân bổ tín chỉ</th>
                    <th>Loại lớp</th>
                    <th>Ngành</th>
                    <th>Khoa</th>
                    <th>Chương trình đào tạo</th>
                    <th>Số lượng sinh viên</th>
                    <th>Thứ</th>
                    <th>Tiết</th>
                    <th>Ngôn ngữ giảng dạy</th>
                    <th>Giảng đường</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($monhocs as $monhoc) {
                    $STT = $monhoc['STT'] ?? '';
                    $maHp = $monhoc['ma_hp'] ?? '';
                    $tenHp = $monhoc['ten_hp'] ?? '';
                    $soTc = $monhoc['so_tin_chi'] ?? '';
                    $maLopHp = $monhoc['ma_lop_hp'] ?? '';
                    $phanBoTc = $monhoc['phan_bo_tin_chi'] ?? '';
                    $loaiLop = $monhoc['loai_lop'] ?? '';
                    $nganh = $monhoc['nganh'] ?? '';
                    $khoa = $monhoc['khoa'] ?? '';
                    $chuongTrinhDt = $monhoc['chuong_trinh_dao_tao'] ?? '';
                    $soLuongSv = $monhoc['so_luong_sv'] ?? '';
                    $thu = $monhoc['thu'] ?? '';
                    $tiet = $monhoc['tiet'] ?? '';
                    $ngonNguGiangDay = $monhoc['ngon_ngu_giang_day'] ?? '';
                    $giangDuong = $monhoc['giang_duong'] ?? '';

                    echo '<tr>';
                    echo "<td>$STT</td>";
                    echo "<td>$maHp</td>";
                    echo "<td>$tenHp</td>";
                    echo "<td>$soTc</td>";
                    echo "<td>$maLopHp</td>";
                    echo "<td>$phanBoTc</td>";
                    echo "<td>$loaiLop</td>";
                    echo "<td>$nganh</td>";
                    echo "<td>$khoa</td>";
                    echo "<td>$chuongTrinhDt</td>";
                    echo "<td>$soLuongSv</td>";
                    echo "<td>$thu</td>";
                    echo "<td>$tiet</td>";
                    echo "<td>$ngonNguGiangDay</td>";
                    echo "<td>$giangDuong</td>";
                    echo '</tr>';
                }
                echo '<a href="index.php">Click here to back Home to upload file</a>';
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>