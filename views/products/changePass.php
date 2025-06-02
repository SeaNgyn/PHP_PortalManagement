<?php
session_start();
include '../layouts/auth.php';
include '../../configuration/database.php';
if ($connection === null) {
    throw new Exception("Database connection is not established.");
  }
header('Content-Type: text/plain');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'Phương thức không hợp lệ.';
    exit;
}

$id = $_SESSION['AccountId'] ?? 0;
$old = $_POST['old_password'] ?? '';
$new = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (!$id || !$old || !$new || !$confirm) {
    echo 'Vui lòng điền đầy đủ thông tin.';
    exit;
}

if ($new !== $confirm) {
    echo 'Mật khẩu xác nhận không khớp.';
    exit;
}

// Kiểm tra mật khẩu mới có đủ mạnh không
if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=~`{}[\]|:;"\'<>,.?\/])[A-Za-z\d!@#$%^&*()_\-+=~`{}[\]|:;"\'<>,.?\/]{8,}$/', $new)) {
    echo "Mật khẩu phải có ít nhất 8 ký tự, chữ cái đầu viết hoa, có số và ký tự đặc biệt.";
    exit;
}

// Lấy mật khẩu hiện tại từ DB
$stmt = $connection->prepare("SELECT mat_khau FROM tn_tai_khoan WHERE id = ?");
$stmt->execute([$id]);
$currentHash = $stmt->fetchColumn();

if (!$currentHash || !password_verify($old, $currentHash)) {
    echo 'Mật khẩu cũ không đúng.';
    exit;
}

// Cập nhật mật khẩu mới
$newHash = password_hash($new, PASSWORD_DEFAULT);
$update = $connection->prepare("UPDATE tn_tai_khoan SET mat_khau = ? WHERE id = ?");
$update->execute([$newHash, $id]);

echo 'OK';
