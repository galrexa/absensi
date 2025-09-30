<?php 
require APPPATH.'Libraries/PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$fileName = 'Report-Presensi-Harian-Tanggal-'.str_replace(['-'], '', $tanggal).'.xlsx';
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
    //  'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
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
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:P1');
$objPHPExcel->getActiveSheet()->SetCellValue('A1', strtoupper($title).'. '.strtoupper(tanggal($tanggal,4)));
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray3);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:P2');
$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'No');
$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'Nama');
$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'Jabatan');
$objPHPExcel->getActiveSheet()->SetCellValue('D3', 'Unit Kerja');
$objPHPExcel->getActiveSheet()->SetCellValue('E3', 'Mulai');
$objPHPExcel->getActiveSheet()->SetCellValue('F3', 'Flexi');
$objPHPExcel->getActiveSheet()->SetCellValue('G3', 'Terlambat');
$objPHPExcel->getActiveSheet()->SetCellValue('H3', 'Selesai');
$objPHPExcel->getActiveSheet()->SetCellValue('I3', 'Cepat Pulang');
$objPHPExcel->getActiveSheet()->SetCellValue('J3', 'Durasi');
$objPHPExcel->getActiveSheet()->SetCellValue('K3', 'Istirahat');
$objPHPExcel->getActiveSheet()->SetCellValue('L3', 'Durasi Kerja');
$objPHPExcel->getActiveSheet()->SetCellValue('M3', 'Durasi Kerja Harian');
$objPHPExcel->getActiveSheet()->SetCellValue('N3', 'Ketarangan');
$objPHPExcel->getActiveSheet()->SetCellValue('O3', 'Loc Mulai');
$objPHPExcel->getActiveSheet()->SetCellValue('P3', 'Loc Selesai');
$objPHPExcel->getActiveSheet()->getStyle('A3:P3')->applyFromArray($styleArray2);
// set Row

$rowCountKS = 4;
$no1 = 0;
$n1 = 0;
foreach ($data as $element) {
    $n1 = ++$n1;
    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCountKS, $n1);
    $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCountKS, $element['nama']);
    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCountKS, $element['jabatan_name']);
    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCountKS, $element['unit_kerja_name_alt'].' ('.$element['unit_kerja_name'].')');
    $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCountKS, substr($element['start'],11));
    $objPHPExcel->getActiveSheet()->getStyle('E' . $rowCountKS)->applyFromArray($styleArray3);
    $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCountKS, $element['durasi_flexi']);
    $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCountKS, $element['durasi_terlambat']);
    $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCountKS, substr($element['stop'],11));
    $objPHPExcel->getActiveSheet()->getStyle('H' . $rowCountKS)->applyFromArray($styleArray3);
    $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCountKS, $element['durasi_mendahului']);
    $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCountKS, $element['total_durasi']);
    $objPHPExcel->getActiveSheet()->getStyle('J' . $rowCountKS)->applyFromArray($styleArray3);
    $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCountKS, $element['df_durasi_istirahat']);
    $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCountKS, $element['total_durasi_kerja']);
    $objPHPExcel->getActiveSheet()->getStyle('L' . $rowCountKS)->applyFromArray($styleArray3);
    $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCountKS, $element['df_durasi_kerja']);
    $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCountKS, $element['keterangan']);
    $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCountKS, $element['start_log']);
    $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCountKS, $element['stop_log']);
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