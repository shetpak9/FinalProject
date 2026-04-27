<?php
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['mapImage'])) {
        throw new Exception('No map image provided');
    }

    // HTML content for PDF
    $html = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; margin: 10px; }
            h1 { color: #333; }
            img { max-width: 100%; height: 98%; }
        </style>
    </head>
    <body>
        <h3>' . htmlspecialchars($data['title'] ?? 'Map Report') . '</h3>
        <p>Generated on: ' . date('Y-m-d H:i:s') . '</p>
        <div>
            <img src="' . $data['mapImage'] . '" style="width: 100%;">
        </div>
    </body>
    </html>
    ';

    // Create Dompdf instance
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'Landscape');
    $dompdf->render();

    // Output PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="map-report.pdf"');
    echo $dompdf->output();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}