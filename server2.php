<?php

@include 'config.php';

error_reporting(0);
ini_set('display_errors', 0);

$sql2 = "SELECT 
s.product_id, 
s.stok AS total_quantity, 
p.name AS product_name,
s.firststok * s.aprice AS total_purchase_price,
SUM(o.quantity) AS total_sales_quantity,
SUM(o.quantity) * s.sprice AS total_sprice,
(SUM(o.quantity) * s.sprice) - (s.firststok * s.aprice) AS total_earnings,
s.sprice, s.firststok, s.aprice,
p.name, p.barcod
FROM stock s
LEFT JOIN orders o ON s.product_id = o.product_id
LEFT JOIN products p ON s.product_id = p.id  
WHERE 1 $where_clause
GROUP BY s.product_id";


$result = mysqli_query($conn, $sql2);

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
$activeWorksheet->setCellValue('A1', 'Urun ID');
$activeWorksheet->setCellValue('B1', 'Urun Adı');
$activeWorksheet->setCellValue('C1', 'Alınan Miktar');
$activeWorksheet->setCellValue('D1', 'Satılan Miktar');
$activeWorksheet->setCellValue('E1', 'Kalan Stok Miktarı');
$activeWorksheet->setCellValue('F1', ' Alış Fiyatı');
$activeWorksheet->setCellValue('G1', ' Satış Fiyatı');
$activeWorksheet->setCellValue('H1', 'Toplam Kazanç');


$rowIndex = 2; // İlk satır başlıklar olduğu için 2'den başlatıyoruz

foreach ($data as $row) {
   $activeWorksheet->setCellValue('A' . $rowIndex, $row['product_id']);
   $activeWorksheet->setCellValue('B' . $rowIndex, $row['product_name']);
   $activeWorksheet->setCellValue('C' . $rowIndex, $row['firststok']);
   $activeWorksheet->setCellValue('D' . $rowIndex, $row['total_sales_quantity']);
   $activeWorksheet->setCellValue('E' . $rowIndex, $row['total_quantity']);
   $activeWorksheet->setCellValue('F' . $rowIndex, $row['aprice']);
   $activeWorksheet->setCellValue('G' . $rowIndex, $row['sprice']);
   $activeWorksheet->setCellValue('H' . $rowIndex, $row['total_earnings']);
 
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
