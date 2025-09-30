<link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
<?php 
$html_content = '
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-12 col-md-12 text-center fw-bold fs-4">
            '.$title.'
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-sm-12 col-md-2 fw-bold">
            Periode :
        </div>
        <div class="col-sm-12 col-md-10 fw-bold">
            '.tanggal($periode,2).'
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table id="example1" class="table table-striped table-hover table-bordered">
            	<thead class="bg-light">
                        <tr>
                            <th class="bg-secondary text-center text-light" rowspan="2">#</th>
                            <th class="bg-secondary text-center text-light" rowspan="2">NAMA</th>
                            <th class="bg-secondary text-center text-light" rowspan="2">JABATAN</th>
                            <th class="bg-secondary text-center text-light" rowspan="2">UNIT KERJA</th>
                            <th class="bg-secondary text-center text-light" colspan="7">HARI KERJA</th>
                            <th class="bg-secondary text-center text-light" rowspan="2">POTONGAN</th>
                            <th class="bg-secondary text-center text-light" rowspan="2">KETERANGAN</th>
                        </tr>
                        <tr>
                            <th class="bg-secondary text-center text-light">HARI KERJA</th>
                            <th class="bg-secondary text-center text-light">HADIR</th>
                            <th class="bg-secondary text-center text-light">TERLAMBAT</th>
                            <th class="bg-secondary text-center text-light">CEPAT PULANG</th>
                            <th class="bg-secondary text-center text-light">CUTI/IZIN</th>
                            <th class="bg-secondary text-center text-light">DINAS</th>
                            <th class="bg-secondary text-center text-light">TIDAK HADIR</th>
                        </tr>
            	</thead>
            	<tbody style="vertical-align: top;">
                ';

                    $no = 0;
                    foreach ($data as $k) {
                        $no++;
                        $potongan = 0;
                        if($k['potongan']>0){
                            $potongan = $k['potongan'] .'%';
                        }

                        $html_content .= '
                        <tr>
                            <td>'.$no.'</td>
                            <td>'.$k['nama'].'</td>
                            <td>'.$k['jabatan_name'].'</td>
                            <td>'.$k['unit_kerja_name_alt'].'</td>
                            <td class="text-center fw-bold">'.$k['hari_kerja'].'</td>
                            <td class="text-center fw-bold">'.$k['hadir'].'</td>
                            <td class="text-center">'.$k['terlambat'].'</td>
                            <td class="text-center">'.$k['mendahului'].'</td>
                            <td class="text-center">'.$k['cuti'].'</td>
                            <td class="text-center">'.$k['dinas'].'</td>
                            <td class="text-center fw-bold">'.$k['tidak_hadir'].'</td>
                            <td class="text-center fw-bold">'.$potongan.'</td>
                            <td>'.$k['keterangan'].'</td>
                        </tr>
                        ';

                    }

                    $html_content .='
                </tbody>
            </table>
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