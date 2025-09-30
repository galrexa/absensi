<?php 
require APPPATH.'Libraries/PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$fileName = 'dataReportTenagaRekanan-'.time().'.xlsx';
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
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'REKAPITULASI SEMENTARA JUMLAH TENAGA REKANAN');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:H2');
$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'PER '.strtoupper(tanggal(date('Y-m-d'),2)));
$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray($styleArray);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:H3');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A4:B4');
$objPHPExcel->getActiveSheet()->SetCellValue('A4', 'No');
$objPHPExcel->getActiveSheet()->SetCellValue('C4', 'Nama');
$objPHPExcel->getActiveSheet()->SetCellValue('D4', 'Jabatan');
$objPHPExcel->getActiveSheet()->SetCellValue('E4', 'Nomor Kontrak Kerja');
$objPHPExcel->getActiveSheet()->SetCellValue('F4', 'Tanggal Mulai');
$objPHPExcel->getActiveSheet()->SetCellValue('G4', 'Tanggal Berakhir');
$objPHPExcel->getActiveSheet()->SetCellValue('H4', 'Keterangan');
$objPHPExcel->getActiveSheet()->getStyle('A4:H4')->applyFromArray($styleArray2);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A5:H5');
$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'TEKNISI');
$objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($styleArray2);
// set Row

$rowCountKS = 6;
$no1 = 0;
$n1 = 0;
foreach ($data as $element) if($element->jabatan_id==45){
	$n1 = ++$n1;
	if(array_keys(['', '-'],$element->akhir)){
		$tglakhir = '';
	}else{
		$tglakhir = tanggal($element->akhir,1);
	}
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, ++$no1);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, $element->nama);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element->keterangan);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element->nomor_sk);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element->mulai);
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCountKS, $tglakhir);
	$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCountKS, $element->keterangan2);
	$rowCountKS++;
}

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS).':H'.($rowCountKS).'');
$objPHPExcel->getActiveSheet()->SetCellValue('A'.($rowCountKS), 'TENAGA KEBERSIHAN');
$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCountKS))->applyFromArray($styleArray2);

$rowCountKS = $rowCountKS+1;
$no1 = 0;
$n1 = $n1;
foreach ($data as $element) if($element->jabatan_id==44){
	$n1 = ++$n1;
	if(array_keys(['', '-'],$element->akhir)){
		$tglakhir = '';
	}else{
		$tglakhir = tanggal($element->akhir,1);
	}
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, ++$no1);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, $element->nama);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element->keterangan);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element->nomor_sk);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element->mulai);
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCountKS, $tglakhir);
	$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCountKS, $element->keterangan2);
	$rowCountKS++;
}

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS).':H'.($rowCountKS).'');
$objPHPExcel->getActiveSheet()->SetCellValue('A'.($rowCountKS), 'RESEPSIONIS');
$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCountKS))->applyFromArray($styleArray2);

$rowCountKS = $rowCountKS+1;
$no1 = 0;
$n1 = $n1;
foreach ($data as $element) if($element->jabatan_id==46){
	$n1 = ++$n1;
	if(array_keys(['', '-'],$element->akhir)){
		$tglakhir = '';
	}else{
		$tglakhir = tanggal($element->akhir,1);
	}
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, ++$no1);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, $element->nama);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element->keterangan);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element->nomor_sk);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element->mulai);
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCountKS, $tglakhir);
	$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCountKS, $element->keterangan2);
	$rowCountKS++;
}

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS).':H'.($rowCountKS).'');
$objPHPExcel->getActiveSheet()->SetCellValue('A'.($rowCountKS), 'AUDITOR TI');
$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCountKS))->applyFromArray($styleArray2);

$rowCountKS = $rowCountKS+1;
$no1 = 0;
$n1 = $n1;
foreach ($data as $element) if($element->jabatan_id==51){
	$n1 = ++$n1;
	if(array_keys(['', '-'],$element->akhir)){
		$tglakhir = '';
	}else{
		$tglakhir = tanggal($element->akhir,1);
	}
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, ++$no1);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, $element->nama);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element->keterangan);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element->nomor_sk);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element->mulai);
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCountKS, $tglakhir);
	$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCountKS, $element->keterangan2);
	$rowCountKS++;
}

$rowCount = $rowCountKS-1;
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCount+1).':H'.($rowCount+1).'');
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.($rowCount+2).':H'.($rowCount+2).'');
// $objPHPExcel->getActiveSheet()->SetCellValue('F'.($rowCount+2), 'Jakarta, '.tanggal(date('Y-m-d'),1));
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.($rowCount+3).':H'.($rowCount+3).'');
// $objPHPExcel->getActiveSheet()->SetCellValue('F'.($rowCount+3), 'Kepala Sekretariat Kantor Staf Presiden');
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.($rowCount+7).':H'.($rowCount+7).'');
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