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

    // Tìm theo email
    $sql = "SELECT * FROM tn_tai_khoan WHERE email = ?";
    $statement = $connection->prepare($sql);
    $statement->execute([$taikhoan]);
    $account = $statement->fetch(PDO::FETCH_ASSOC);

    if ($account) {
      $matKhauDB = $account['mat_khau'];
      $isVerified = false;

      // Ưu tiên dùng password_verify nếu có vẻ là hash
      if (strlen($matKhauDB) > 30 && password_verify($matkhau, $matKhauDB)) {
        $isVerified = true;
      }
      // Nếu không phải hash, kiểm tra chuỗi thuần
      else if ($matkhau === $matKhauDB) {
        $isVerified = true;

        // Cập nhật lại mật khẩu thành bản mã hóa
        $newHash = password_hash($matkhau, PASSWORD_DEFAULT);
        $update = $connection->prepare("UPDATE tn_tai_khoan SET mat_khau = ? WHERE id = ?");
        $update->execute([$newHash, $account['id']]);
      }

      if ($isVerified) {
        // Đăng nhập thành công
        $_SESSION['VaiTro'] = $account['vai_tro'];
        $_SESSION['TrangThai'] = $account['trang_thai'];
        $_SESSION['AccountId'] = $account['id'];
        $_SESSION['UserId'] = $account['giang_vien_id'] ?? $account['id'];

        if ($_SESSION['VaiTro'] == 1 && !empty($account['giang_vien_id'])) {
          $sql = "SELECT * FROM tn_giang_vien WHERE id = ?";
          $statement = $connection->prepare($sql);
          $statement->execute([$account['giang_vien_id']]);
          $gv = $statement->fetch(PDO::FETCH_ASSOC);
          $_SESSION['tenGiangVien'] = $gv['Name'] ?? '';
        } else {
          $_SESSION['tenGiangVien'] = 'Admin';
        }

        header('Location: home.php');
        exit;
      }
    }

    $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
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