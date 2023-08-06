<?php

@include 'config.php';

error_reporting(0);
ini_set('display_errors', 0);

session_start(); 
$sql = "SELECT DISTINCT
o.user_id,
u.name AS user_name,
p.name,
s.aprice,
o.quantity,
o.total_price,
o.payment_status,
s.stok AS total_quantity,
o.total_price - (o.quantity * s.aprice) AS total_earnings
FROM orders o
LEFT JOIN users u ON u.id = o.user_id
LEFT JOIN products p ON p.id = o.product_id
LEFT JOIN stock s ON s.product_id = o.product_id
$where_clause
ORDER BY o.id DESC";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $data = array(); // Sorgudan gelen verileri saklamak için boş bir dizi oluşturuyoruz
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row; // Verileri diziye ekliyoruz
    }
}

require 'C:\xampp\htdocs\flower\phpspreadsheet\vendor\autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
$activeWorksheet->setCellValue('A1', 'Müşteri ID');
$activeWorksheet->setCellValue('B1', 'Müşteri Adı');
$activeWorksheet->setCellValue('C1', 'Ürün Adı');
$activeWorksheet->setCellValue('D1', 'Alış Fiyatı');
$activeWorksheet->setCellValue('E1', 'Satılan Miktar');
$activeWorksheet->setCellValue('F1', 'Toplam Satış Fiyatı');
$activeWorksheet->setCellValue('G1', 'Sipariş Durumu');
$activeWorksheet->setCellValue('I1', 'Toplam Kazanç');

$rowIndex = 2; // İlk satır başlıklar olduğu için 2'den başlatıyoruz

foreach ($data as $row) {
   $activeWorksheet->setCellValue('A' . $rowIndex, $row['user_id']);
   $activeWorksheet->setCellValue('B' . $rowIndex, $row['user_name']);
   $activeWorksheet->setCellValue('C' . $rowIndex, $row['name']);
   $activeWorksheet->setCellValue('D' . $rowIndex, $row['aprice']);
   $activeWorksheet->setCellValue('E' . $rowIndex, $row['quantity']);
   $activeWorksheet->setCellValue('F' . $rowIndex, $row['total_price']);
   $activeWorksheet->setCellValue('G' . $rowIndex, $row['payment_status']);
   $activeWorksheet->setCellValue('I' . $rowIndex, $row['total_earnings']);
   $rowIndex++;
}

$filename = 'excel_listesi.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
?>
