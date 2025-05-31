<?php 
function Ks($soLuongSv, $loaiLop,$maLopHp)
{

 //$soGv = $countGvMap[$maLopHp] ?? 1; // mặc định là 1 nếu không có trong map
 $soGv=$maLopHp;
 
    switch ($loaiLop) {
        case str_contains($loaiLop,'TH'):
            $hs1 = ($soLuongSv < 15) ? 0.8 : (($soLuongSv < 20) ? 1.0 : 1.2);
            $hs = $hs1/$soGv; 
            return $hs;
            break;
        // case "'str_contains($loaiLop,'LT')'":
            case 'LT':
            // Xử lý theo lớp LT
            $hs = ($soLuongSv <= 40) ? 1.0 : (($soLuongSv <= 60) ? 1.1 : (($soLuongSv <= 80) ? 1.2 : (($soLuongSv <= 100) ? 1.3 : (($soLuongSv <= 120) ? 1.4 : 1.5))));
            return $hs;
            break;
        case str_contains($loaiLop,'BT'):
            $hs = ($soLuongSv <= 40) ? 1.0 : (($soLuongSv <= 60) ? 1.1 : (($soLuongSv <= 80) ? 1.2 : (($soLuongSv <= 100) ? 1.3 : (($soLuongSv <= 120) ? 1.4 : 1.5))));
            return $hs;
            break;
        default:
            // Xử lý mặc định
    }
}
function Kn($ngonNguGiangDay)
{
    switch ($ngonNguGiangDay) {
        case "Tiếng Việt":
            return 1.0;
            break;
        default:
            return 1.5;
    }
}
function Kt($thu, $tiet)
{
    $tietSplit = explode("-", $tiet);
    if ($thu >= 2 && $thu <= 6 && array_filter($tietSplit, fn($x) => $x < 10)) {
        return 1;
    } else {
        return 1.5;
    }
}


function calculateTimeToStudy($phanBoTc, $soLuongSv, $ngonNguGiangDay, $thu, $tiet, $loaiLop,$maLopHp)
{
    // Gọi hàm tính hệ số
    $ks = Ks($soLuongSv, $loaiLop,$maLopHp);
    $kn = Kn($ngonNguGiangDay);
    $kt = Kt($thu, $tiet);

    // $tinChiSplit = explode("/", $phanBoTc);
    //explode() — là cách phổ biến và hiệu quả nhất để tách chuỗi theo ký tự phân cách.
    // $count = count($tinChiSplit);

    // Sử dụng biểu thức chính quy để tìm tất cả các chuỗi số
    preg_match_all('/\d+/', $phanBoTc, $matches);
    // $matches[0] chứa mảng các số tìm được
    //doi mang 2 chieu thanh 1 chieu
    $tinChiSplit = $matches[0];
    $count = count($tinChiSplit);

   if($ngonNguGiangDay=="Tiếng Việt"){
     $C = 1.5;
   }else{
    $C = 2;
   }
$G1 = min($ks * $kn * $kt,$C);
$G2 = min(($ks * $kn * $kt)/2,$C);
    if ($soLuongSv <= 15) {
        switch ($count) {
            case 4:
                $calTC = $tinChiSplit[1] + $tinChiSplit[$count - 2];
                return $calTC *  $G2;
                break;
            case 2:
                $calTC = $tinChiSplit[0] + $tinChiSplit[$count - 1];
                return $calTC *  $G2;
                break;
            case 3:
                $calTC = $tinChiSplit[0] + $tinChiSplit[$count - 2];
                return $calTC *  $G2;
                break;
            default:
                return "no data";
        }
    } else {
        switch ($count) {
            case 4:
                $calTC = $tinChiSplit[1] + $tinChiSplit[$count - 2];
                return $calTC *  $G1;
                break;
            case 2:
                $calTC = $tinChiSplit[0] + $tinChiSplit[$count - 1];
                return $calTC *  $G1;
                break;
            case 3:
                $calTC = $tinChiSplit[0] + $tinChiSplit[$count - 2];
                return $calTC*  $G1;
                break;
            default:
                return "no data";
        }
    }


}
?>