<?php 
include '../layouts/header.php'; ?>
<?php
include '../../configuration/database.php';
$idGiangVien = $_SESSION['UserId'];
try {
    $sql = "SELECT * FROM tn_giang_vien WHERE id = $idGiangVien";
    if ($connection === null) {
        throw new Exception("Database connection is not established.");
    }
    $statement = $connection->prepare($sql);
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);
    $profile = $statement->fetch(PDO::FETCH_ASSOC); // Lấy 1 dòng duy nhất

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
// echo json_encode($profile);
?>

<div id="divMain" class="row row2">
    <div class="col-sm-2 sidebar" style="background-color:#2e43d1;padding-right: 0">
        <?php include '../layouts/sidebar.php'; ?>
    </div>
 

    <div class="col-sm-10 content" style="background-color:rgb(252, 252, 252);padding:0">
        <div id="modTitle" class="module-title" style="height:25px; ;">Hồ sơ</div>
        <div class="container">
            <form class="form-horizontal" action="" method="post">
                <fieldset>
                    <legend>Thông tin cá nhân</legend>
                    <!-- Ảnh bên trái -->
                    <div class="col-sm-4 text-center" style="width: 300px;">
                        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Ảnh cá nhân"
                            class="img-thumbnail profile-img">
                        <br>
                        <!-- Input ẩn để chọn ảnh -->
                        <input type="file" id="imageUpload" accept="image/*" style="display: none;">

                        <!-- Link để kích hoạt input file -->
                        <!-- <a href="#" onclick="document.getElementById('imageUpload').click(); return false;">Cập nhật
                            ảnh</a> -->
                    </div>

                    <!-- Thông tin bên phải -->
                    <div class="col-sm-8" style="width: 600px;margin-left:-25px">
                        <div class="form-group" style="margin-bottom: auto">
                            <label class="col-sm-3 control-label">Mã giảng viên</label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" name="id" value="<?= htmlspecialchars($profile['id'] ?? '') ?>" readonly>

                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: auto">
                            <label class="col-sm-3 control-label">Họ và tên</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name ="Name" value="<?= htmlspecialchars($profile['Name'] ?? '') ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: auto">
                            <label class="col-sm-3 control-label">Ngày sinh</label>
                            <div class="col-sm-4">
                                <input type="date" class="form-control" name =""DOB value="<?= htmlspecialchars($profile['DOB'] ?? '') ?>" readonly>
                            </div>

                            <label class="col-sm-2 control-label">Giới tính</label>
                            <div class="col-sm-3">
                                <select class="form-control" name ="sex" readonly>
                                    <option value="">-- Chọn --</option>
                                    <option value="Nam"<?= (isset($profile['sex']) && $profile['sex'] === 1) ? 'selected' : '' ?>>Nam</option>
                                    <option value="Nữ" <?= (isset($profile['sex']) && $profile['sex'] === 0) ? 'selected' : '' ?>>Nữ</option>
                                </select>
                            </div>
                        </div>

                        <!-- <div class="form-group" style="margin-bottom: auto">
                            <label class="col-sm-3 control-label">Quê quán </label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" value="<?= htmlspecialchars($profile[''] ?? '') ?>">
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name ="Email" value="<?= htmlspecialchars($profile['Email'] ?? '') ?>" readonly>
                            </div>
                        </div>
                    </div>

                </fieldset>
            </form>
        </div>

    </div>
</div>