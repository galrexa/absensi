<?php 
require APPPATH.'Libraries/PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$fileName = 'dataReportTenagaMagang-'.time().'.xlsx';
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
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'REKAPITULASI SEMENTARA JUMLAH PEGAWAI MAGANG');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:G2');
$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'PER '.strtoupper(tanggal(date('Y-m-d'),2)));
$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray($styleArray);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:G3');
$objPHPExcel->getActiveSheet()->SetCellValue('A4', 'No');
$objPHPExcel->getActiveSheet()->SetCellValue('B4', 'Nama');
$objPHPExcel->getActiveSheet()->SetCellValue('C4', 'Penempatan');
$objPHPExcel->getActiveSheet()->SetCellValue('D4', 'Asal Universitas');
$objPHPExcel->getActiveSheet()->SetCellValue('E4', 'Tanggal Mulai');
$objPHPExcel->getActiveSheet()->SetCellValue('F4', 'Tanggal Berakhir');
$objPHPExcel->getActiveSheet()->SetCellValue('G4', 'Ketarangan');
$objPHPExcel->getActiveSheet()->getStyle('A4:G4')->applyFromArray($styleArray2);
// set Row

$rowCountKS = 5;
$no1 = 0;
$n1 = 0;
foreach ($data as $element) {
	$n1 = ++$n1;
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, ' '.$element->nama);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, $element->unit_kerja_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element->universitas);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element->mulai);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element->akhir);
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCountKS, $element->keterangan2);
	$rowCountKS++;
}

$rowCount = $rowCountKS-1;
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCount+1).':G'.($rowCount+1).'');
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E'.($rowCount+2).':G'.($rowCount+2).'');
// $objPHPExcel->getActiveSheet()->SetCellValue('E'.($rowCount+2), 'Jakarta, '.tanggal(date('Y-m-d'),1));
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E'.($rowCount+3).':G'.($rowCount+3).'');
// $objPHPExcel->getActiveSheet()->SetCellValue('E'.($rowCount+3), 'Kepala Sekretariat Kantor Staf Presiden');
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E'.($rowCount+7).':G'.($rowCount+7).'');
// $objPHPExcel->getActiveSheet()->SetCellValue('E'.($rowCount+7), 'Yan Adikusuma');

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