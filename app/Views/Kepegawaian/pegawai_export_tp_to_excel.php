<?php 
require APPPATH.'Libraries/PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$fileName = 'dataReportPegawaiProfesional-'.time().'.xlsx'; 

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
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:K1');
$objPHPExcel->getActiveSheet()->SetCellValue('A1', $title);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:K2');
$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'PADA KANTOR STAF PRESIDEN');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:K3');
$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'PER '.strtoupper(tanggal(date('Y-m-d'),2)));
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->applyFromArray($styleArray);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A4:K4');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A5:B6');
$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'No');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C5:C6');
$objPHPExcel->getActiveSheet()->SetCellValue('C5', 'Nomor Pegawai');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D5:D6');
$objPHPExcel->getActiveSheet()->SetCellValue('D5', 'Nama');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E5:E6');
$objPHPExcel->getActiveSheet()->SetCellValue('E5', 'Jabatan');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F5:G5');
$objPHPExcel->getActiveSheet()->SetCellValue('F5', 'Periode Pengangkatan');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H5:H6');
$objPHPExcel->getActiveSheet()->SetCellValue('H5', 'Eselon');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('I5:I6');
$objPHPExcel->getActiveSheet()->SetCellValue('I5', 'Keterangan');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J5:J6');
$objPHPExcel->getActiveSheet()->SetCellValue('J5', 'Nomor SK');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('K5:K6');
$objPHPExcel->getActiveSheet()->SetCellValue('K5', 'Tanggal SK');
$objPHPExcel->getActiveSheet()->getStyle('A5:K5')->applyFromArray($styleArray2);
$objPHPExcel->getActiveSheet()->SetCellValue('F6', 'Mulai');
$objPHPExcel->getActiveSheet()->SetCellValue('G6', 'Sampai');
$objPHPExcel->getActiveSheet()->getStyle('A6:K6')->applyFromArray($styleArray2);
// set Row

$rowCountKS = 7;
$no1 = 0;
$n1 = 0;
foreach ($data as $element) if(array_keys([10,55], $element->jabatan_id)){
	$n1 = ++$n1;
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, ++$no1);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, ' '.$element->nip);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element->nama);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element->jabatan_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element->mulai);
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCountKS, $element->akhir);
	$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCountKS, $element->eselon);
	$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCountKS, 'Pejabat Negara');
	$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCountKS, $element->nomor_sk);
	$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCountKS, $element->tanggal_sk);
	$rowCountKS++;
}
foreach ($data as $element) if(array_keys([49,50], $element->jabatan_id)){
	$n1 = ++$n1;
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, ++$no1);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, ' '.$element->nip);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element->nama);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element->jabatan_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element->mulai);
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCountKS, $element->akhir);
	$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCountKS, $element->eselon);
	$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCountKS, $element->jabatan_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCountKS, $element->nomor_sk);
	$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCountKS, $element->tanggal_sk);
	$rowCountKS++;
}

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS).':K'.($rowCountKS).'');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS+1).':K'.($rowCountKS+1).'');
$objPHPExcel->getActiveSheet()->SetCellValue('A'.($rowCountKS+1), 'STAF KHUSUS');
$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCountKS+1))->applyFromArray($styleArray2);

$rowCountKS = $rowCountKS+2;
$no1 = 0;
$n1 = $n1;
foreach ($data as $element) if(array_keys([35], $element->jabatan_id)){
	$n1 = ++$n1;
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, ++$no1);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, ' '.$element->nip);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element->nama);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element->jabatan_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element->mulai);
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCountKS, $element->akhir);
	$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCountKS, $element->eselon);
	if(array_keys([1,2], $element->status_pns)){
		$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCountKS)->applyFromArray($styleBold);
	}
	$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCountKS, $element->status_pns_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCountKS, $element->nomor_sk);
	$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCountKS, $element->tanggal_sk);
	$rowCountKS++;
}

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS).':K'.($rowCountKS).'');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS+1).':K'.($rowCountKS+1).'');
$objPHPExcel->getActiveSheet()->SetCellValue('A'.($rowCountKS+1), 'DEPUTI');
$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCountKS+1))->applyFromArray($styleArray2);

$rowCountKS = $rowCountKS+2;
$no1 = 0;
$n1 = $n1;
foreach ($data as $element) if(array_keys([1,2,3,4,5,67,68,70,71,72,73], $element->jabatan_id)){
	$n1 = ++$n1;
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, ++$no1);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, ' '.$element->nip);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element->nama);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element->jabatan_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element->mulai);
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCountKS, $element->akhir);
	$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCountKS, $element->eselon);
	if(array_keys([1,2], $element->status_pns)){
		$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCountKS)->applyFromArray($styleBold);
	}
	$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCountKS, $element->status_pns_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCountKS, $element->nomor_sk);
	$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCountKS, $element->tanggal_sk);
	$rowCountKS++;
}

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS).':K'.($rowCountKS).'');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS+1).':K'.($rowCountKS+1).'');
$objPHPExcel->getActiveSheet()->SetCellValue('A'.($rowCountKS+1), 'TIM DEPUTI I');
$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCountKS+1))->applyFromArray($styleArray2);

$rowCountKS = $rowCountKS+2;
$no1 = 0;
$n1 = $n1;
foreach ($data as $element) if(array_keys([6,7,8,9], $element->jabatan_id) && $element->unit_kerja_id==2){
	$n1 = ++$n1;
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, ++$no1);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, ' '.$element->nip);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element->nama);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element->jabatan_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element->mulai);
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCountKS, $element->akhir);
	$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCountKS, $element->eselon);
	if(array_keys([1,2], $element->status_pns)){
		$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCountKS)->applyFromArray($styleBold);
	}
	$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCountKS, $element->status_pns_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCountKS, $element->nomor_sk);
	$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCountKS, $element->tanggal_sk);
	$rowCountKS++;
}

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS).':K'.($rowCountKS).'');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS+1).':K'.($rowCountKS+1).'');
$objPHPExcel->getActiveSheet()->SetCellValue('A'.($rowCountKS+1), 'TIM DEPUTI II');
$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCountKS+1))->applyFromArray($styleArray2);

$rowCountKS = $rowCountKS+2;
$no1 = 0;
$n1 = $n1;
foreach ($data as $element) if(array_keys([6,7,8,9], $element->jabatan_id) && $element->unit_kerja_id==3){
	$n1 = ++$n1;
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, ++$no1);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, ' '.$element->nip);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element->nama);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element->jabatan_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element->mulai);
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCountKS, $element->akhir);
	$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCountKS, $element->eselon);
	if(array_keys([1,2], $element->status_pns)){
		$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCountKS)->applyFromArray($styleBold);
	}
	$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCountKS, $element->status_pns_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCountKS, $element->nomor_sk);
	$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCountKS, $element->tanggal_sk);
	$rowCountKS++;
}

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS).':K'.($rowCountKS).'');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS+1).':K'.($rowCountKS+1).'');
$objPHPExcel->getActiveSheet()->SetCellValue('A'.($rowCountKS+1), 'TIM DEPUTI III');
$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCountKS+1))->applyFromArray($styleArray2);

$rowCountKS = $rowCountKS+2;
$no1 = 0;
$n1 = $n1;
foreach ($data as $element) if(array_keys([6,7,8,9], $element->jabatan_id) && $element->unit_kerja_id==4){
	$n1 = ++$n1;
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, ++$no1);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, ' '.$element->nip);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element->nama);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element->jabatan_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element->mulai);
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCountKS, $element->akhir);
	$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCountKS, $element->eselon);
	if(array_keys([1,2], $element->status_pns)){
		$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCountKS)->applyFromArray($styleBold);
	}
	$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCountKS, $element->status_pns_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCountKS, $element->nomor_sk);
	$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCountKS, $element->tanggal_sk);
	$rowCountKS++;
}

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS).':K'.($rowCountKS).'');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS+1).':K'.($rowCountKS+1).'');
$objPHPExcel->getActiveSheet()->SetCellValue('A'.($rowCountKS+1), 'TIM DEPUTI IV');
$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCountKS+1))->applyFromArray($styleArray2);

$rowCountKS = $rowCountKS+2;
$no1 = 0;
$n1 = $n1;
foreach ($data as $element) if(array_keys([6,7,8,9], $element->jabatan_id) && $element->unit_kerja_id==5){
	$n1 = ++$n1;
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, ++$no1);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, ' '.$element->nip);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element->nama);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element->jabatan_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element->mulai);
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCountKS, $element->akhir);
	$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCountKS, $element->eselon);
	if(array_keys([1,2], $element->status_pns)){
		$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCountKS)->applyFromArray($styleBold);
	}
	$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCountKS, $element->status_pns_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCountKS, $element->nomor_sk);
	$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCountKS, $element->tanggal_sk);
	$rowCountKS++;
}

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS).':K'.($rowCountKS).'');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCountKS+1).':K'.($rowCountKS+1).'');
$objPHPExcel->getActiveSheet()->SetCellValue('A'.($rowCountKS+1), 'TIM DEPUTI V');
$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCountKS+1))->applyFromArray($styleArray2);

$rowCountKS = $rowCountKS+2;
$no1 = 0;
$n1 = $n1;
foreach ($data as $element) if(array_keys([6,7,8,9], $element->jabatan_id) && $element->unit_kerja_id==6){
	$n1 = ++$n1;
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, ++$no1);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, ' '.$element->nip);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element->nama);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element->jabatan_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element->mulai);
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCountKS, $element->akhir);
	$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCountKS, $element->eselon);
	if(array_keys([1,2], $element->status_pns)){
		$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCountKS)->applyFromArray($styleBold);
	}
	$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCountKS, $element->status_pns_name);
	$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCountKS, $element->nomor_sk);
	$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCountKS, $element->tanggal_sk);
	$rowCountKS++;
}

$rowCount = $rowCountKS-1;
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCount+1).':K'.($rowCount+1).'');
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('I'.($rowCount+2).':K'.($rowCount+2).'');
// $objPHPExcel->getActiveSheet()->SetCellValue('I'.($rowCount+2), 'Jakarta, '.tanggal(date('Y-m-d'),1));
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('I'.($rowCount+3).':K'.($rowCount+3).'');
// $objPHPExcel->getActiveSheet()->SetCellValue('I'.($rowCount+3), 'Kepala Sekretariat Kantor Staf Presiden');
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('I'.($rowCount+7).':K'.($rowCount+7).'');
// $objPHPExcel->getActiveSheet()->SetCellValue('I'.($rowCount+7), 'Yan Adikusuma'/*$this->open->getKepalaSekre()*/);

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