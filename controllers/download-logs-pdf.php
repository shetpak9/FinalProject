<?php
require __DIR__ . "/../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->setIsRemoteEnabled(true);

$dompdf = new Dompdf($options);

ob_start();
require "../dompdf-form.php";
$html = ob_get_clean();

$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "Portrait");
$dompdf->render();

$dompdf->stream("map-activity-logs.pdf", ["Attachment" => true]);