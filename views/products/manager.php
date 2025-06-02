<?php
error_reporting(~E_NOTICE);
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Kiểm tra vai trò trước khi có output
if (!isset($_SESSION['VaiTro']) || $_SESSION['VaiTro'] != 2) {
  header('Location: ../products/home.php');
  exit;
}
include '../../configuration/database.php';
include '../layouts/header.php';
include '../layouts/calculate.php';
if (isset($_GET['trang'])) {
  $page = $_GET['trang'];
} else {
  $page = '';
}

if ($page == '' || $page == 1) {
  $begin = 0;
} else {
  $begin = ($page * 10) - 10;
}
$totalRows = [];
$monhocs = [];
$limit = 15;
try {
  if ($connection === null) {
    throw new Exception("Database connection is not established.");
  }
  $sql = "SELECT * from tn_giang_vien gv 
      join tn_giangvien_malophp gvmlhp on gv.id = gvmlhp.giang_vien_id 
      join tn_ma_lop_hp mlhp on mlhp.id = gvmlhp.id_ma_lop_hp 
      join tn_mon_hoc mh on mlhp.id_mon_hoc = mh.id
      -- LIMIT $begin,10;
      ";
  if ($connection === null) {
    throw new Exception("Database connection is not established.");
  }
  $statement = $connection->prepare($sql);
  $statement->execute();
  $statement->setFetchMode(PDO::FETCH_ASSOC);
  $monhocs = $statement->fetchAll();

  $sql = "SELECT gv.id AS giang_vien_id,gv.Name,
    COUNT(DISTINCT mlhp.id) AS so_mon_day
    FROM tn_giang_vien gv
    JOIN tn_giangvien_malophp gvmlhp ON gv.id = gvmlhp.giang_vien_id
    JOIN tn_ma_lop_hp mlhp ON mlhp.id = gvmlhp.id_ma_lop_hp
    JOIN tn_mon_hoc mh ON mh.id = mlhp.id_mon_hoc
    GROUP BY gv.id, gv.Name
    ORDER BY so_mon_day DESC limit $begin,$limit";
  $statement = $connection->prepare($sql);
  $statement->execute();
  $gvs = $statement->fetchAll(PDO::FETCH_ASSOC);
  // print_r($gvs);
  // $tenGiangVien = $gvs['Name'] ?? 'Không tìm thấy';
  $count = "SELECT 
  mlhp.ten_ma_lop_hp, 
  COUNT(gvmlhp.giang_vien_id) AS so_giang_vien
FROM 
  tn_ma_lop_hp mlhp
JOIN 
  tn_giangvien_malophp gvmlhp ON mlhp.id = gvmlhp.id_ma_lop_hp
GROUP BY 
  mlhp.ten_ma_lop_hp;";

  //dem
  $statement = $connection->prepare($count);
  $statement->execute();
  $statement->setFetchMode(PDO::FETCH_ASSOC);
  $results = $statement->fetchAll();
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$countGvMap = [];
foreach ($results as $row) {
  $maLopHp = $row['ten_ma_lop_hp'];
  $soGv = $row['so_giang_vien'];
  $countGvMap[$maLopHp] = $soGv;
}
?>

<div id="divMain" class="row row2">
  <div class="col-sm-2 sidebar" style="background-color:#2e43d1;padding-right: 0">
    <?php include '../layouts/sidebar.php'; ?>
  </div>
  <div class="col-sm-10 content" style="background-color:rgb(252, 252, 252);">
    <div id="modTitle" class="module-title" style="height:25px;margin-left:-15px;">Quản Lý</div>
    <?php include '../layouts/upload.php'; ?>
    <div class="panel panel-primary " style="margin-top: 20px; height: auto; ">
      <div class="panel-heading"
        style="height: auto; font-size: 20px; font-family: Helvetica; color: white;background-color:blue">Danh sách thống kê giờ dạy giảng viên khoa vật lý </div>
      <div class="panel-body" style="height: auto;">
        <table class="table table-striped" id="courseTable">
          <thead>
            <tr style="background-color: #f5f5f5; color: 000;">
              <th>STT</th>
              <th>Giảng viên</th>
              <th>Số môn dạy</th>
              <th>Thời gian dạy</th>
            </tr>
          </thead>
          <tbody style="color: #4f535a;">
            <?php
            foreach ($monhocs as $monhoc) {
              // $STT = $monhoc['STT'] ?? '';
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
              $gvId = $monhoc['giang_vien_id'];
              echo '</tr>';
              $gioDay = calculateTimeToStudy($phanBoTc, $soLuongSv, $ngonNguGiangDay, $thu, $tiet, $loaiLop, $countGvMap[$maLopHp]);
              if (!isset($totaltimeMap[$gvId])) {
                $totaltimeMap[$gvId] = [
                  'Name' => $giangVien,
                  'tong_gio' => 0
                ];
              }

              $totaltimeMap[$gvId]['tong_gio'] += (float) $gioDay;
            }
            foreach($gvs as $gvif){
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
        <a href="../layouts/genderAll.php" class="btn btn-danger" target="_blank">Mở PDF</a>

        <div style="text-align: center;">

          <?php
          if ($connection === null) {
            throw new Exception("Database connection is not established.");
          }
          // $sql_trang = mysqli_query($statement,"SELECT * FROM ma_lop_hp");
          $sql = "SELECT * from tn_giang_vien;";
          $statement = $connection->prepare($sql);
          $statement->execute();
          $statement->setFetchMode(PDO::FETCH_ASSOC);
          $monhocs = $statement->fetchAll();
          $row_count = count($monhocs);
          $trang = ceil($row_count / $limit);
          ?>
          <ul class="pagination">
            <?php
            for ($i = 1; $i <= $trang; $i++) {
            ?>
              <li><a href="manager.php?trang=<?php echo $i ?>"><?php echo $i ?></a></li>
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
    <!-- PHÂN TRANG -->