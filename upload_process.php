<?php
set_time_limit(0);
ob_start();

require 'vendor/autoload.php';
include './configuration/database.php';
libxml_use_internal_errors(true);

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_FILES['fileExcel']['error'] !== UPLOAD_ERR_OK) {
    die("File upload failed.");
}

$semester = $_POST['semester'] ?? null;

$file = $_FILES['fileExcel']['tmp_name'];
try {
    $reader = IOFactory::createReaderForFile($file);
    $spreadsheet = $reader->load($file);
} catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
    echo "<h4 style='color: red;'>Chỉ chấp nhận file Excel có định dạng .xlsx hoặc .xls</h4>";
    exit;
}
$sheetCount = $spreadsheet->getSheetCount();

$columnIndexes = [
    'STT' => ['STT'],
    'maHp' => ['MHP', 'Mã học phần'],
    'tenHp' => ['Tên HP', 'Học phần'],
    'soTc' => ['STC', 'Số TC'],
    'maLopHp' => ['Mã lớp học phần', 'Mã LHP'],
    'phanBoTc' => ['Phân bổ TC'],
    'loaiLop' => ['LT, TH/BT, TH'],
    'nganh' => ['Ngành'],
    'khoa' => ['Khoa'],
    'chuongTrinhDt' => ['CTĐT'],
    'soLuongSv' => ['Số SV đang học', 'Số ĐK', 'Số SV đăng ký'],
    'thu' => ['Thứ'],
    'tiet' => ['Tiết'],
    'ngonNguGiangDay' => ['Ngôn ngữ giảng dạy'],
    'giangDuong' => ['Giảng đường'],
    'tenGv' => ['Họ tên GV'],
    'namSinhGv' => ['Empty'],
    'hocViGv' => ['Họchàm/họcvị'],
    'emailGv' => ['Empty'],
    'sdtGv' => ['Empty'],
    'khoaGv' => ['Khoa']
];

function getCellValue($rowData, $indexResult, $key)
{
    // nếu không tìm thấy key trong indexResult, trả null
    if (!isset($indexResult[$key])) {
        return null;
    }
    // nếu ô rỗng hoặc không tồn tại, trả null
    $val = $rowData[$indexResult[$key]] ?? null;
    if ($val === "" || $val === null) {
        return null;
    }
    return $val;
}
function tachTenGV($chuoi)
{
    // 1. Loại bỏ phần trong dấu ngoặc như (PV), (GV), (HĐ),...
    $chuoi = preg_replace('/\s*\(.*?\)\s*/', '', $chuoi);

    // 2. Chuẩn hóa chuỗi: loại bỏ khoảng trắng đầu-cuối, xuống dòng, dấu phẩy, chấm phẩy, dấu +
    $chuoi = trim($chuoi);
    $chuoi = preg_replace('/[\r\n,;+]+/', '|', $chuoi);
    $chuoi = preg_replace('/\s*\|\s*/', '|', $chuoi); // xóa khoảng trắng quanh dấu phân cách

    // 3. Tách thành mảng
    $tenArr = explode('|', $chuoi);

    // 4. Loại bỏ khoảng trắng thừa và phần tử rỗng
    $tenArr = array_filter(array_map('trim', $tenArr));

    // 5. Loại bỏ tên trùng
    $tenArr = array_unique($tenArr);

    return array_values($tenArr); // Đảm bảo trả về mảng tuần tự

}
$inserted = 0;
for ($s = 0; $s < $sheetCount; $s++) {
    $sheet = $spreadsheet->getSheet($s);
    $data = $sheet->toArray();

    // echo '<pre>';
    // print_r($data);
    // echo '</pre>';


    // Tìm cột theo tiêu đề
    $indexResult = [];
    foreach ($data as $rowIndex => $row) {
        foreach ($row as $i => $cellValue) {
            foreach ($columnIndexes as $key => $aliases) {
                if (in_array(trim($cellValue), $aliases)) {
                    $indexResult[$key] = $i;
                }
            }
        }
        if (!empty($indexResult)) {
            $headerRowIndex = $rowIndex;
            break;
        }
    }


    // Duyệt dữ liệu từ dòng tiếp theo
    $total = count($data);
    // echo '<pre>';
    // print_r($hello);
    // echo '</pre>';

    for ($i = $headerRowIndex + 1; $i < $total; $i++) {
        $intFields = [
            (int)getCellValue($data[$i], $indexResult, "STT"),
            (int)getCellValue($data[$i], $indexResult, "soTc"),
            (int)getCellValue($data[$i], $indexResult, "soLuongSv"),
        ];

        $stringFields = [
            getCellValue($data[$i], $indexResult, "maHp"),
            getCellValue($data[$i], $indexResult, "tenHp"),
            getCellValue($data[$i], $indexResult, "maLopHp"),
            getCellValue($data[$i], $indexResult, "phanBoTc"),
            getCellValue($data[$i], $indexResult, "loaiLop"),
            getCellValue($data[$i], $indexResult, "nganh"),
            getCellValue($data[$i], $indexResult, "khoa"),
            getCellValue($data[$i], $indexResult, "chuongTrinhDt"),
            getCellValue($data[$i], $indexResult, "thu"),
            getCellValue($data[$i], $indexResult, "tiet"),
            getCellValue($data[$i], $indexResult, "ngonNguGiangDay"),
            getCellValue($data[$i], $indexResult, "giangDuong")
            // getCellValue($data[$i], $indexResult, "tenGv"),
            // getCellValue($data[$i], $indexResult, "namSinhGv"),
            // getCellValue($data[$i], $indexResult, "hocViGv"),
            // getCellValue($data[$i], $indexResult, "emailGv"),
            // getCellValue($data[$i], $indexResult, "sdtGv"),
            // getCellValue($data[$i], $indexResult, "khoaGv")
        ];

        $stringFieldsGv = [
            getCellValue($data[$i], $indexResult, "tenGv"),
            getCellValue($data[$i], $indexResult, "namSinhGv"),
            getCellValue($data[$i], $indexResult, "hocViGv"),
            getCellValue($data[$i], $indexResult, "emailGv"),
            getCellValue($data[$i], $indexResult, "sdtGv"),
            getCellValue($data[$i], $indexResult, "khoaGv")
        ];

        // Kiểm tra xem tất cả giá trị có phải đều rỗng/0 không
        $hasValidInt = array_filter($intFields, fn($v) => $v !== null && $v != 0);
        $hasValidString = array_filter($stringFields, fn($v) => $v !== null && trim($v) !== "");
        $hasValidStringGv = array_filter($stringFieldsGv, fn($v) => $v !== null && trim($v) !== "");

        if (!empty($hasValidInt) || !empty($hasValidString)) {
            $values = [
                $intFields[0],                    // STT
                $stringFields[2],                 // ma_lop_hp
                $stringFields[3],                 // phan_bo_tin_chi
                $stringFields[4],                 // loai_lop
                $stringFields[5],                 // nganh
                $stringFields[6],                 // khoa
                $stringFields[7],                 // chuong_trinh_dao_tao
                $intFields[2],                    // so_luong_sv
                $stringFields[8],                 // thu
                $stringFields[9],                 // tiet
                $stringFields[10],                // ngon_ngu_giang_day
                $stringFields[11]                 // giang duong
            ];

            $values3 = [
                $stringFields[1],                 // ten_hp
                $stringFields[0],                 // ma_hp
                $intFields[1],                    // so_tin_chi
            ];

            $values1 = [
                $stringFieldsGv[0],                // ten giang vien
                $stringFieldsGv[1],                // nam sinh giang vien
                $stringFieldsGv[2],                // hoc vi giang vien
                $stringFieldsGv[3],                // email giang vien
                $stringFieldsGv[4],                // sdt giang vien
                $stringFieldsGv[5]                 // khoa cua giang vien
            ];

            if ($connection !== null) {
                // Kiểm tra xem môn học đã tồn tại chưa
                $stmtCheck = $connection->prepare("SELECT id FROM tn_mon_hoc WHERE ma_mon = ?");
                $stmtCheck->execute([$values3[1]]);  // ma_mon
                $monHoc = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if ($monHoc) {
                    // Nếu đã tồn tại thì lấy ID
                    $lastMonhocId = $monHoc['id'];
                } else {
                    // Nếu chưa có thì insert mới
                    $stmt3 = $connection->prepare("INSERT INTO tn_mon_hoc (
                    ten_mon, ma_mon, so_tin_chi
                    ) VALUES (?, ?, ?)");
                    $stmt3->execute($values3);
                    $lastMonhocId = $connection->lastInsertId();
                }

                if (!empty($semester)) {
                    switch ($semester) {
                        case 1:
                            $tenHocKy = 'Học kỳ 1';
                            break;
                        case 2:
                            $tenHocKy = 'Học kỳ 2';
                            break;
                        case 3:
                            $tenHocKy = 'Học kỳ Hè';
                            break;
                        default:
                            $tenHocKy = 'Không xác định';
                    }
                    // Check trùng học kỳ theo tên và năm
                    $stmtCheckHK = $connection->prepare("SELECT id FROM tn_hoc_ky WHERE ten = ? AND nam_hoc = ?");
                    $stmtCheckHK->execute([$tenHocKy, date("Y")]);
                    $hocky = $stmtCheckHK->fetch(PDO::FETCH_ASSOC);

                    if ($hocky) {
                        $lastHocKyId = $hocky['id'];
                    } else {
                        $stmt5 = $connection->prepare("INSERT INTO tn_hoc_ky (ten, nam_hoc) VALUES (?, ?)");
                        $stmt5->execute([$tenHocKy, date("Y")]);

                        // Lấy ID mới insert
                        $lastHocKyId = $connection->lastInsertId();
                    }
                }

                $stmtCheckMLHP = $connection->prepare("SELECT id FROM tn_ma_lop_hp mlhp join tn_hocky_malophp hkmlhp on mlhp.id = hkmlhp.malophp_id 
                WHERE ten_ma_lop_hp = ? AND loai_lop = ? AND hkmlhp.hocky_id = ?;");
                $stmtCheckMLHP->execute([$values[1], $values[3], $lastHocKyId]);  

                $maLopHpCk = $stmtCheckMLHP->fetch(PDO::FETCH_ASSOC);
                if ($maLopHpCk) {
                    continue;
                } else {
                    $stmt = $connection->prepare("INSERT INTO tn_ma_lop_hp (
                        STT, ten_ma_lop_hp, phan_bo_tin_chi,
                        loai_lop, nganh, khoa, chuong_trinh_dao_tao, so_luong_sv,
                        thu, tiet, ngon_ngu_giang_day, giang_duong, id_mon_hoc
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                    $stmt->execute([...$values, $lastMonhocId]);
                    $lastMaLopHocPhanId = $connection->lastInsertId();

                    // Check trùng học kỳ - môn học
                    $stmtCheck = $connection->prepare("SELECT 1 FROM tn_hocky_malophp WHERE hocky_id = ? AND malophp_id = ?");
                    $stmtCheck->execute([$lastHocKyId, $lastMaLopHocPhanId]);

                    if (!$stmtCheck->fetch(PDO::FETCH_ASSOC)) {
                        $stmt6 = $connection->prepare("INSERT INTO tn_hocky_malophp (hocky_id, malophp_id) VALUES (?, ?)");
                        $stmt6->execute([$lastHocKyId, $lastMaLopHocPhanId]);
                    }
                }




                if (!empty($values1[0])) {

                    $giangVienArray = tachTenGV($stringFieldsGv[0]);
                    // $giangVienArray = preg_split('/\r\n|\r|\n/', $stringFieldsGv[0]);
                    foreach ($giangVienArray as $gvName) {
                        $gvName = trim($gvName);
                        if ($gvName === '') continue;
                        // Kiểm tra giảng viên có tồn tại chưa
                        $stmtCheckGv = $connection->prepare("SELECT id FROM tn_giang_vien WHERE Name = ?");
                        $stmtCheckGv->execute([$gvName]);
                        $gv = $stmtCheckGv->fetch(PDO::FETCH_ASSOC);
                        $lastGiangvienId = $gv ? $gv['id'] : null;

                        if (!empty($lastGiangvienId)) {
                            // Kiểm tra cặp giảng_vien_id và id_mon_hoc đã tồn tại chưa
                            $stmtCheckGV_MH = $connection->prepare("
                            SELECT 1 FROM tn_giangvien_monhoc 
                            WHERE giang_vien_id = ? AND id_mon_hoc = ?
                            ");
                            $stmtCheckGV_MH->execute([$lastGiangvienId, $lastMonhocId]);

                            if (!$stmtCheckGV_MH->fetch()) {
                                // Chưa tồn tại => chèn mới
                                $stmt2 = $connection->prepare("INSERT INTO tn_giangvien_monhoc (
                                giang_vien_id, id_mon_hoc
                                ) VALUES (?, ?)");
                                $stmt2->execute([$lastGiangvienId, $lastMonhocId]);
                            }
                            $stmt4 = $connection->prepare("INSERT INTO tn_giangvien_malophp (
                                giang_vien_id, id_ma_lop_hp
                                ) VALUES (?, ?)");
                            $stmt4->execute([$lastGiangvienId, $lastMaLopHocPhanId]);
                        }
                    }
                }
                $inserted++;
            }
        }
    }
}

if ($inserted != 0) {
    echo "<h3>Tải dữ liệu file lên thành công</h3>";
    echo "<h5>Tải lại trang để cập nhật dữ liệu</h5>";
    //echo "<h3>Đã xử lý thành công $inserted dòng từ tất cả các sheet.</h3>";
    //echo '<a href="monhoc_list.php">Chuyển đến danh sách</a>';
} else {
    //echo "<h3>Đã xử lý thành công $inserted dòng từ tất cả các sheet.</h3>";
    echo '<h4 style="color: red;">Dữ liệu file đã tồn tại trong CSDL</h4>';
}
