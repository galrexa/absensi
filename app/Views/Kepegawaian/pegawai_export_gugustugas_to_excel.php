<?php 
require APPPATH.'Libraries/PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$fileName = 'dataReportTenagaGugusTugas-'.time().'.xlsx';
$objPHPExcel = new Spreadsheet();
$sheet = $objPHPExcel->getActiveSheet();
$styleArray = array(
	'font' => array(
		'bold' => true,
		'size'=> 12,
	),
	'alignment' => array(
		'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
	),
);
$styleArray2 = [
	'font' => [
		'bold' => true,
		'size'=>12,
	],
	'alignment' => [
		'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
	],
    'fill' => [
        'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
        'startcolor' => [
            'argb' => 'FFA0A0A0',
        ],
        'endcolor' => [
            'argb' => 'FFFFFFFF',
        ],
    ],
    'borders' => [
        'allborders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
            'color' => ['argb' => '000000'],
        ],
    ],
];
$styleBold = [
	'font'=>[
		'bold'=>true,
	]
];
// set header
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:F1');
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'REKAPITULASI SEMENTARA JUMLAH PEGAWAI GUGUS TUGAS');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:F2');
$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'PER '.strtoupper(tanggal(date('Y-m-d'),2)));
$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray($styleArray);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:F3');
$objPHPExcel->getActiveSheet()->SetCellValue('A4', 'No');
$objPHPExcel->getActiveSheet()->SetCellValue('B4', 'Nama');
$objPHPExcel->getActiveSheet()->SetCellValue('C4', 'Nomor SK');
$objPHPExcel->getActiveSheet()->SetCellValue('D4', 'Tanggal Mulai');
$objPHPExcel->getActiveSheet()->SetCellValue('E4', 'Tanggal Berakhir');
$objPHPExcel->getActiveSheet()->SetCellValue('F4', 'Ketarangan');
$objPHPExcel->getActiveSheet()->getStyle('A4:F4')->applyFromArray($styleArray2);
// set Row

$rowCountKS = 5;
$no1 = 0;
$n1 = 0;
foreach ($data as $element) {
	$n1 = ++$n1;
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, ' '.$element->nama);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, $element->nomor_sk);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element->mulai);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element->akhir);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element->keterangan2);
	$rowCountKS++;
}

$rowCount = $rowCountKS-1;
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCount+1).':F'.($rowCount+1).'');
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.($rowCount+2).':F'.($rowCount+2).'');
// $objPHPExcel->getActiveSheet()->SetCellValue('D'.($rowCount+2), 'Jakarta, '.tanggal(date('Y-m-d'),1));
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.($rowCount+3).':F'.($rowCount+3).'');
// $objPHPExcel->getActiveSheet()->SetCellValue('D'.($rowCount+3), 'Kepala Sekretariat Kantor Staf Presiden');
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.($rowCount+7).':F'.($rowCount+7).'');
// $objPHPExcel->getActiveSheet()->SetCellValue('D'.($rowCount+7), 'Yan Adikusuma');

$writer = new Xlsx($objPHPExcel);
$writer->save(WRITEPATH.'temp_zip/'.$fileName);

header('Content-Disposition: attachment; filename='.$fileName);
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize(WRITEPATH.'temp_zip/'.$fileName));
ob_clean();
flush();
readfile(WRITEPATH.'temp_zip/'.$fileName);
@unlink(WRITEPATH.'temp_zip/'.$fileName);
exit;