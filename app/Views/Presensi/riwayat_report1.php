<?php 
require APPPATH.'Libraries/PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$fileName = 'Report-Riwayat-Presensi-'.str_replace(['-'], '', $periode).'-'.str_replace([' ', '.', ','], '_', $data_pegawai->nama).'.xlsx';
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
$styleArray3 = array(
	'font' => array(
		'bold' => true,
		'size'=> 12,
	),
	// 'alignment' => array(
	// 	'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
	// ),
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
$objPHPExcel->getActiveSheet()->SetCellValue('A1', strtoupper($title));
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:K2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:B3');
$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'NAMA :');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:K3');
$objPHPExcel->getActiveSheet()->SetCellValue('C3', strtoupper($data_pegawai->nama));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A4:B4');
$objPHPExcel->getActiveSheet()->SetCellValue('A4', 'JABATAN :');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C4:K4');
$objPHPExcel->getActiveSheet()->SetCellValue('C4', strtoupper($data_pegawai->jabatan));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A5:B5');
$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'UNIT KERJA :');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C5:K5');
$objPHPExcel->getActiveSheet()->SetCellValue('C5', strtoupper($data_pegawai->unit_kerja_alt.' ('.$data_pegawai->unit_kerja.')'));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A6:B6');
$objPHPExcel->getActiveSheet()->SetCellValue('A6', 'PERIODE :');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C6:K6');
$objPHPExcel->getActiveSheet()->SetCellValue('C6', strtoupper(tanggal($periode,2)));
$objPHPExcel->getActiveSheet()->getStyle('A3:A6')->applyFromArray($styleArray3);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:K7');
$objPHPExcel->getActiveSheet()->SetCellValue('A8', 'No');
$objPHPExcel->getActiveSheet()->SetCellValue('B8', 'Tanggal');
$objPHPExcel->getActiveSheet()->SetCellValue('C8', 'Mulai');
$objPHPExcel->getActiveSheet()->SetCellValue('D8', 'Lokasi');
$objPHPExcel->getActiveSheet()->SetCellValue('E8', 'Flexi');
$objPHPExcel->getActiveSheet()->SetCellValue('F8', 'Terlambat');
$objPHPExcel->getActiveSheet()->SetCellValue('G8', 'Selesai');
$objPHPExcel->getActiveSheet()->SetCellValue('H8', 'Lokasi');
$objPHPExcel->getActiveSheet()->SetCellValue('I8', 'Cepat Pulang');
$objPHPExcel->getActiveSheet()->SetCellValue('J8', 'Total');
$objPHPExcel->getActiveSheet()->SetCellValue('K8', 'Ketarangan');
$objPHPExcel->getActiveSheet()->getStyle('A8:K8')->applyFromArray($styleArray2);
// set Row

$rowCountKS = 9;
$no1 = 0;
$n1 = 0;
foreach ($data as $element) {
	$n1 = ++$n1;
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, $element['tanggal']);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, substr($element['start'],11));
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element['start_log']);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, $element['durasi_flexi']);
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element['durasi_terlambat']);
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCountKS, substr($element['stop'],11));
	$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCountKS, $element['stop_log']);
	$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCountKS, $element['durasi_mendahului']);
	$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCountKS, $element['total_durasi']);
	$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCountKS, $element['keterangan']);
	$rowCountKS++;
}

$rowCount = $rowCountKS+3;
$n11=0;
$objPHPExcel->getActiveSheet()->getStyle('A'.($rowCount-1).':K'.($rowCount-1))->applyFromArray($styleArray3);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCount-1).':C'.($rowCount-1));
$objPHPExcel->getActiveSheet()->SetCellValue('A'.($rowCount-1), 'Presensi:');
foreach(return_referensi_list('absen') as $k){
	$n11 = ++$n11;
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($rowCount).':C'.($rowCount));
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.($rowCount), $k->ref_name.': '.$k->ref_description);
	$rowCount++;
}
$rowCount2 = $rowCountKS+3;
$n12=0;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E'.($rowCount2-1).':G'.($rowCount2-1));
$objPHPExcel->getActiveSheet()->SetCellValue('E'.($rowCount2-1), 'Pelanggaran:');
foreach(return_referensi_list('pelanggaran') as $k){
	$n12 = ++$n12;
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E'.($rowCount2).':G'.($rowCount2));
	$objPHPExcel->getActiveSheet()->SetCellValue('E'.($rowCount2), $k->ref_name.': '.$k->ref_description);
	$rowCount2++;
}
$rowCount3 = $rowCountKS+3;
$n13=0;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('I'.($rowCount3-1).':K'.($rowCount3-1));
$objPHPExcel->getActiveSheet()->SetCellValue('I'.($rowCount3-1), 'Cuti & Dinas:');
foreach(return_referensi_list('cuti') as $k){
	$n13 = ++$n13;
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('I'.($rowCount3).':K'.($rowCount3));
	$objPHPExcel->getActiveSheet()->SetCellValue('I'.($rowCount3), $k->ref_name.': '.$k->ref_description);
	$rowCount3++;
}
foreach(return_referensi_list('dinas') as $k){
	$n13 = ++$n13;
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('I'.($rowCount3).':K'.($rowCount3));
	$objPHPExcel->getActiveSheet()->SetCellValue('I'.($rowCount3), $k->ref_name.': '.$k->ref_description);
	$rowCount3++;
}

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