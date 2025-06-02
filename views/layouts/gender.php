<?php
require '../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);

ob_start();
include '../layouts/dash.php';
$html = ob_get_clean();

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape'); // in ngang
$dompdf->render();
$dompdf->stream("Danhsach.pdf");
?>