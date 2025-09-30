<link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
<?php 
$html_content = '
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-12 col-md-12 text-center fw-bold fs-4">
            '.$title.' '.tanggal($tanggal,4).'
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table id="example1" class="table table-striped table-hover table-bordered">
                <thead class="">
                    <tr>
                        <th class="bg-secondary text-center text-light" rowspan="2">#</th>
                        <th class="bg-secondary text-center text-light" rowspan="2">PEGAWAI</th>
                        <th class="bg-secondary text-center text-light" rowspan="2">JABATAN</th>
                        <th class="bg-secondary text-center text-light" rowspan="2">UNIT KERJA</th>
                        <th class="bg-secondary text-center text-light" colspan="9">LOG PRESENSI</th>
                        <th class="bg-secondary text-center text-light" colspan="3">KETERANGAN</th>
                    </tr>
                    <tr>
                        <th class="bg-secondary text-center text-light">MULAI</th>
                        <th class="bg-secondary text-center text-light">FLEXI</th>
                        <th class="bg-secondary text-center text-light">TERLAMBAT</th>
                        <th class="bg-secondary text-center text-light">SELESAI</th>
                        <th class="bg-secondary text-center text-light">CEPAT PULANG</th>
                        <th class="bg-secondary text-center text-light">DURASI</th>
                        <th class="bg-secondary text-center text-light">ISTIRAHAT</th>
                        <th class="bg-secondary text-center text-light">DURASI KERJA</th>
                        <th class="bg-secondary text-center text-light">DURASI KERJA HARIAN</th>
                        <th class="bg-secondary text-center text-light">PRESENSI</th>
                        <th class="bg-secondary text-center text-light">LOKASI MULAI</th>
                        <th class="bg-secondary text-center text-light">LOKASI SELESAI</th>
                    </tr>
                </thead>
            	<tbody style="vertical-align: top;">
                ';

                    $no = 0; 
                    foreach ($data as $k) { 
                        $no++;
                        $html_content .= '
                        <tr>
                            <td>'.$no.'</td>
                            <td>'.$k['nama'].'</td>
                            <td>'.$k['jabatan_name'].'</td>
                            <td>'.$k['unit_kerja_name_alt'].'</td>
                            <td class="text-center fw-bold">'.substr($k['start'],11).'</td>
                            <td class="text-center">'.$k['durasi_flexi'].'</td>
                            <td class="text-center">'.$k['durasi_terlambat'].'</td>
                            <td class="text-center fw-bold">'.substr($k['stop'],11).'</td>
                            <td class="text-center">'.$k['durasi_mendahului'].'</td>
                            <td class="text-center fw-bold">'.$k['total_durasi'].'</td>
                            <td class="text-center">'.$k['df_durasi_istirahat'].'</td>
                            <td class="text-center fw-bold">'.$k['total_durasi_kerja'].'</td>
                            <td class="text-center">'.$k['df_durasi_kerja'].'</td>
                            <td>'.$k['keterangan'].'</td>
                            <td class="text-center">'.$k['start_log'].'</td>
                            <td class="text-center">'.$k['stop_log'].'</td>
                        </tr>
                        ';
                    }

                    $html_content .= '
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-4" style="font-size:9pt;">
            <div class="alert alert-light">
                <b class="d-block">Presensi:</b>
                <ul>
                ';

                    foreach(return_referensi_list('absen') as $k){
                        $html_content .= '<li class=""><b>'.$k->ref_name.'</b>: '.$k->ref_description.'</li>';
                    }

                    $html_content .= '
                </ul>
            </div>
        </div>
        <div class="col-sm-12 col-md-4" style="font-size:9pt;">
            <div class="alert alert-light">
                <b class="d-block">Pelanggaran:</b>
                <ul>
                ';

                    foreach(return_referensi_list('pelanggaran') as $k){
                        $html_content .= '<li class=""><b>'.$k->ref_name.'</b>: '.$k->ref_description.'</li>';
                    }

                    $html_content .= '
                </ul>
            </div>
        </div>
        <div class="col-sm-12 col-md-4" style="font-size:9pt;">
            <div class="alert alert-light">
                <b class="d-block">Cuti & Dinas:</b>
                <ul>
                ';

                    foreach(return_referensi_list('cuti') as $k){
                        $html_content .= '<li class=""><b>'.$k->ref_name.'</b>: '.$k->ref_description.'</li>';
                    }
                    foreach(return_referensi_list('dinas') as $k){
                        $html_content .= '<li class=""><b>'.$k->ref_name.'</b>: '.$k->ref_description.'</li>';
                    }

                    $html_content .= '
                </ul>
            </div>
        </div>
    </div>
</div>
';

echo $html_content;

/*CREATE FILE TANPA FOOTER*/
// require_once APPPATH.'Libraries/PHPWord-develop/vendor/autoload.php';
// $mpdf = new \Mpdf\Mpdf([
//         'mode' => 'utf-8',
//         'format' => 'A4',
//         'orientation' => 'P',
//         'default_font_size' => 12,
//         'default_font' => 'Arialn',
//         'margin_left' => 10,
//         'margin_right' => 10,
//         'margin_top' => 5,
//         'margin_bottom' => 10,
//         // 'margin_header' => 10,
//         // 'margin_footer' => 10
//     ]);
// $stylesheet = file_get_contents(FCPATH.'assets/css/bootstrap.min.css');
// $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
// $mpdf->WriteHTML($html_content,\Mpdf\HTMLParserMode::HTML_BODY);// $mpdf->WriteHTML($html_content);
// $path_filenya = WRITEPATH.'writable/temp_zip/Report-Presensi-Harian-Tanggal-'.str_replace(['-'], '', $tanggal).'.pdf';
// $mpdf->Output($path_filenya, 'F');
// ob_clean();
/*END*/
?>
<script type="text/javascript">
    // Print halaman saat ini
    window.addEventListener('load', function() {
        // Beri delay sebentar agar halaman fully loaded
        setTimeout(() => {
            // Setup close handler
            window.addEventListener('afterprint', function() {
                setTimeout(() => {
                    window.close();
                }, 500);
            });
            
            // Auto print
            window.print();
        }, 500);
    });
</script>