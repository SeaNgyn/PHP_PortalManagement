<?php
session_start();
// Nếu đã đăng nhập, chuyển về trang home.php
if (isset($_SESSION['UserId'])) {
  header('Location: home.php');
  exit;
}
$taikhoan = $_POST['taikhoan'] ?? null;
$matkhau = $_POST['matkhau'] ?? null;

include '../../configuration/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    if ($connection === null) {
      throw new Exception("Database connection is not established.");
    }

    // Chuẩn bị câu lệnh SQL kiểm tra đăng nhập
    $sql = "SELECT * FROM tn_tai_khoan WHERE email = ? AND mat_khau = ?";
    $statement = $connection->prepare($sql);
    $statement->execute([$taikhoan, $matkhau]);
    $account = $statement->fetch(PDO::FETCH_ASSOC);
    if ($account) {
      $_SESSION['VaiTro'] = $account['vai_tro'];
      $_SESSION['TrangThai'] = $account['trang_thai'];
      $_SESSION['UserId'] = $account['giang_vien_id'] ?? $account['id']; // Nếu không có giang_vien_id, dùng id tài khoản

      // Nếu là giảng viên thì lấy thông tin giảng viên
      if ($_SESSION['VaiTro'] == 1 && !empty($account['giang_vien_id'])) {
        $sql = "SELECT * FROM giang_vien WHERE id = ?";
        $statement = $connection->prepare($sql);
        $statement->execute([$account['giang_vien_id']]);
        $gv = $statement->fetch(PDO::FETCH_ASSOC);
        $_SESSION['tenGiangVien'] = $gv['Name'] ?? '';
      } else {
        $_SESSION['tenGiangVien'] = 'Admin';
      }


      // Chuyển hướng sang trang home.php nếu đăng nhập thành công
      header('Location: home.php');
      exit;
    } else {
      $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
    }
  } catch (PDOException $e) {
    $error = "Lỗi: " . $e->getMessage();
  } catch (Exception $e) {
    $error = "Lỗi: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đăng nhập - Hệ thống quản lý giảng dạy</title>
  <link rel="stylesheet" href="../../css/index.css" />
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous"> -->

</head>

<body>
  <div id="header">
    <div> <img src="https://geology.hus.vnu.edu.vn/wp-content/uploads/2020/03/Logo-HUS_Final-01.png" alt="HUS Logo" class="header-logo" /></div>
    <div class="header-content">
      <h1 style="font-size: larger;">HỆ THỐNG QUẢN LÝ GIẢNG DẠY</h1>
    </div>
  </div>

  <main>
    <section class="login-section">
      <h3>Đăng nhập</h3>
      <form class="login-form" method="POST">
        <label for="username">Tên đăng nhập</label>
        <input type="text" name="taikhoan" id="username" placeholder="Nhập tên đăng nhập" />

        <label for="password">Mật khẩu</label>
        <input type="password" name="matkhau" id="password" placeholder="Nhập mật khẩu" />

        <button type="submit">Đăng nhập</button>
      </form>
    </section>
  </main>
</body>

</html>