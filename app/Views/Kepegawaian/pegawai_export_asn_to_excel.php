<?php 
require APPPATH.'Libraries/PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$fileName = 'dataReportPegawaiPNS-'.time().'.xlsx';
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
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DAFTAR PEGAWAI SEKRETARIAT KANTOR STAF PRESIDEN');
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:F2');
$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'No');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B3:C3');
$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'NIP');
$objPHPExcel->getActiveSheet()->SetCellValue('D3', 'Nama');
$objPHPExcel->getActiveSheet()->SetCellValue('E3', 'Pangkat/Golongan');
$objPHPExcel->getActiveSheet()->SetCellValue('F3', 'Jabatan');
$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($styleArray2);
// set Row

$rowCountKS = 4;
$no1 = 0;
$n1 = 0;
foreach ($data as $element) {
	$n1 = ++$n1;
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, ' '.$element->nip_lama);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, ' '.$element->nip);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element->nama);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element->pangkat.' / '.$element->gol);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element->jabatan_name);
	$rowCountKS++;
}

$rowCount = $rowCountKS-1;
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCount+1).':F'.($rowCount+1).'');
// $objPHPExcel->getActiveSheet()->SetCellValue('F'.($rowCount+2), 'Jakarta, '.tanggal(date('Y-m-d'),1));
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.($rowCount+3).':F'.($rowCount+3).'');
// $objPHPExcel->getActiveSheet()->SetCellValue('F'.($rowCount+3), 'Kepala Sekretariat Kantor Staf Presiden');
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.($rowCount+7).':F'.($rowCount+7).'');
// $objPHPExcel->getActiveSheet()->SetCellValue('F'.($rowCount+7), 'Yan Adikusuma');

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