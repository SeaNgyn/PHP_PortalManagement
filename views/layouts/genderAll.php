<?php
require '../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Khởi tạo Dompdf
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Nội dung HTML bạn muốn in
ob_start();
?>

<style>
    body {
        font-family: 'DejaVu Sans', sans-serif;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    th,
    td {
        border: 1px solid #333;
        padding: 5px;
        text-align: left;
    }
</style>

<div class="panel panel-primary " style="margin-top: 20px; height: auto; ">
    <div class="panel-heading"
        style="height: auto; font-size: 20px; font-family: DejaVu Sans, sans-serif; color: white; background-color: blue">
        Danh sách thống kê giờ dạy giảng viên Khoa Vật Lý
    </div>

    <table class="table table-striped" id="courseTable">
        <thead>
            <tr style="background-color: #f5f5f5; color: 000;">
                <th>STT</th>
                <th>Giảng viên</th>
                <th>Số môn</th>
                <th>Thời gian dạy</th>
            </tr>
        </thead>
        <tbody style="color: #4f535a;">
            <?php
            include '../../configuration/database.php';
            include '../layouts/calculate.php';
            if ($connection === null) {
                throw new Exception("Database connection is not established.");
            }

            // Lấy dữ liệu từ DB như bạn đã viết:
            $sql = "SELECT * from tn_giang_vien gv 
            join tn_giangvien_malophp gvmlhp on gv.id = gvmlhp.giang_vien_id 
            join tn_ma_lop_hp mlhp on mlhp.id = gvmlhp.id_ma_lop_hp 
            join tn_mon_hoc mh on mlhp.id_mon_hoc = mh.id";
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            $monhocs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $count = "SELECT 
            mlhp.ten_ma_lop_hp, 
            COUNT(gvmlhp.giang_vien_id) AS so_giang_vien
            FROM tn_ma_lop_hp mlhp
            JOIN tn_giangvien_malophp gvmlhp ON mlhp.id = gvmlhp.id_ma_lop_hp
            GROUP BY mlhp.ten_ma_lop_hp";
            $stmt = $connection->prepare($count);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $sql = "SELECT gv.id AS giang_vien_id,gv.Name,
            COUNT(DISTINCT mlhp.id) AS so_mon_day
            FROM tn_giang_vien gv
            JOIN tn_giangvien_malophp gvmlhp ON gv.id = gvmlhp.giang_vien_id
            JOIN tn_ma_lop_hp mlhp ON mlhp.id = gvmlhp.id_ma_lop_hp
            JOIN tn_mon_hoc mh ON mh.id = mlhp.id_mon_hoc
            GROUP BY gv.id, gv.Name
            ORDER BY so_mon_day DESC ";
            $statement = $connection->prepare($sql);
            $statement->execute();
            $gvs = $statement->fetchAll(PDO::FETCH_ASSOC);

            $countGvMap = [];
            foreach ($results as $row) {
                $countGvMap[$row['ten_ma_lop_hp']] = $row['so_giang_vien'];
            }

            $totaltimeMap = [];
            foreach ($monhocs as $monhoc) {
                $phanBoTc = $monhoc['phan_bo_tin_chi'] ?? '';
                $soLuongSv = $monhoc['so_luong_sv'] ?? '';
                $ngonNguGiangDay = $monhoc['ngon_ngu_giang_day'] ?? '';
                $thu = $monhoc['thu'] ?? '';
                $tiet = $monhoc['tiet'] ?? '';
                $loaiLop = $monhoc['loai_lop'] ?? '';
                $maLopHp = $monhoc['ten_ma_lop_hp'] ?? '';
                $gvId = $monhoc['giang_vien_id'];
                $giangVien = $monhoc['Name'] ?? '';

                $gioDay = calculateTimeToStudy($phanBoTc, $soLuongSv, $ngonNguGiangDay, $thu, $tiet, $loaiLop, $countGvMap[$maLopHp]);

                if (!isset($totaltimeMap[$gvId])) {
                    $totaltimeMap[$gvId] = ['Name' => $giangVien, 'tong_gio' => 0];
                }

                $totaltimeMap[$gvId]['tong_gio'] += (float)$gioDay;
            }

            foreach ($gvs as $gvif) {
                $gvId = $gvif['giang_vien_id'] ?? '';
                $GiangVienName = $gvif['Name'] ?? '';
                $soMon = $gvif['so_mon_day'] ?? '';
                $tongGio = isset($totaltimeMap[$gvId]) ? $totaltimeMap[$gvId]['tong_gio'] : 0;

                echo '<tr>';
                echo "<td>$gvId</td>";
                echo "<td>$GiangVienName</td>";
                echo "<td>$soMon</td>";
                echo "<td>$tongGio</td>";
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php
$html = ob_get_clean();

// Load HTML vào Dompdf
$dompdf->loadHtml($html);

// Cài đặt khổ giấy và hướng
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Xuất file PDF ra trình duyệt
$dompdf->stream("danh_sach_mon_hoc.pdf", array("Attachment" => false)); // false = mở trên trình duyệt
?>