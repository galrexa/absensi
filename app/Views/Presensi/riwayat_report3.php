<?php
use Mpdf\Mpdf;

$html_content = '
<html>
<body>
    <div align="center" style="font-size:12pt">
        <b>'.$title.' '.tanggal($periode,2).'</b>
    </div>
    <br>
    <div>
        <span>Nama</span>
        <span style="font-weight: bold;">:</span>
        <span>'.$data_pegawai->nama.'</span>
    </div>
    <div>
        <span>Jabatan</span>
        <span style="font-weight: bold;">:</span>
        <span>'.$data_pegawai->jabatan.'</span>
    </div>
    <div style="margin-bottom:5px">
        <span>Unit Kerja</span>
        <span style="font-weight: bold;">:</span>
        <span>'.$data_pegawai->unit_kerja_alt.' ('.$data_pegawai->unit_kerja.')</span>
    </div>
    <table>
    	<thead>
            <tr>
                <th rowspan="2">TANGGAL</th>
                <th colspan="6">PRESENSI</th>
                <th rowspan="2">KETERANGAN PRESENSI</th>
            </tr>
            <tr>
                <th>MASUK</th>
                <th>FLEXI</th>
                <th>TERLAMBAT</th>
                <th>PULANG</th>
                <th>CEPAT PULANG</th>
                <th>TOTAL JAM</th>
            </tr>
    	</thead>
    	<tbody style="vertical-align:top;">
        ';

            $no = 0; 
            foreach ($data as $k) {
                $no++;
                $color_style = 'style=""';
                $keterangan = str_replace(['*'], '*Libur', $k['keterangan']);
                if(strpos($k['keterangan'],'*') > -1)
                {
                    $color_style = 'style="background-color:#facbca"';
                }
                $html_content .= '
                <tr>
                    <td '.$color_style.' align="center">'.tanggal($k['tanggal'],7).'</td>
                    <td '.$color_style.' align="center">'.substr($k['start'],11).'</td>
                    <td '.$color_style.' align="center">'.$k['durasi_flexi'].'</td>
                    <td '.$color_style.' align="center">'.$k['durasi_terlambat'].'</td>
                    <td '.$color_style.' align="center">'.substr($k['stop'],11).'</td>
                    <td '.$color_style.' align="center">'.substr($k['durasi_mendahului'],0,8).'</td>
                    <td '.$color_style.' align="center">'.$k['total_durasi'].'</td>
                    <td '.$color_style.' align="center">'.$keterangan.'</td>
                </tr>
                ';

            }

            $html_content .= '
        </tbody>
    </table>
    <table>
        <thead><tr><th colspan="2" align="left"><b>Keterangan:</b></th></tr></thead>
        <tr>
            <td>
                <ul style="margin:0; font-size:7pt">
                ';
                    foreach(return_referensi_list('absen') as $k){
                        $html_content .= '<li class=""><b>'.$k->ref_name.'</b>: '.$k->ref_description.'</li>';
                    }
                $html_content .= '
                </ul>
            </td>
            <td>
                <ul style="margin:0; font-size:7pt">
                ';
                    foreach(return_referensi_list('cuti') as $k){
                        $html_content .= '<li class=""><b>'.$k->ref_name.'</b>: '.$k->ref_description.'</li>';
                    }
                    foreach(return_referensi_list('dinas') as $k){
                        $html_content .= '<li class=""><b>'.$k->ref_name.'</b>: '.$k->ref_description.'</li>';
                    }
                $html_content .= '
                </ul>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <ul style="margin:0; font-size:7pt">
                ';
                    foreach(return_referensi_list('pelanggaran') as $k){
                        $html_content .= '<li class=""><b>'.$k->ref_name.'</b>: '.$k->ref_description.'</li>';
                    }
                    $html_content .= '
                </ul>
            </td>
        </tr>
    </table>
</body>
</html>
';

// echo $html_content;
$path_ = WRITEPATH.'uploads/presensi_2024/';
$fileName = str_replace(['.', ',', ' '], '_', $data_pegawai->nama.'__'.tanggal($periode,2)).'.pdf';
$path_filenya = $path_.$fileName;

/*CREATE FILE TANPA FOOTER*/
$mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'orientation' => 'P',
        'default_font_size' => 9,
        'default_font' => 'Arialn',
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 15,
        'margin_bottom' => 10,
        // 'margin_header' => 10,
        // 'margin_footer' => 10
    ]);
$stylesheet = file_get_contents(FCPATH.'assets/css/pdf.css');
$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($html_content,\Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->Output($path_filenya, 'F');  # F D I

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($path_filenya));
// Bersihkan output buffer
ob_clean();
flush();
// Baca dan output file
readfile($path_filenya);
// echo json_encode($response->download($path_filenya, null)->setFileName($fileName));
// // @unlink($path_filenya);
?>