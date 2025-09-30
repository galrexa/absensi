
<div class="row">
    <div class="col-sm-12 col-md-6 mb-2">
        <div class="card">
            <div class="card-header">
                <?=$title?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        NIK:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['nik']?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Nama:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['nama']?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Jabatan:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['jabatan_name']?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Unit Kerja:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['unit_kerja_name_alt']?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Nomor:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['nomor_surat']?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Jenis Cuti:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['jenis_cuti_name']?> (<?=$data['jenis_cuti_name_alt']?>)
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Keterangan:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['keterangan']?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Alamat:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['alamat']?>
                        <div>Kontak: <b><?=$data['telpon']?></b></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Jumlah:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <b class="fs-6"><?=$data['jumlah']?></b> Hari
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Tanggal Cuti:
                    </div>
                    <div class="col-sm-12 col-md-9 fs-6">
                        <?=groupTanggalInMonth($data['tanggal'])?>
                    </div>
                </div>
                <?php if(array_keys([2,3,4], $data['jenis_cuti'])){?>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12 col-md-3 fw-bold">
                            Lampiran:
                        </div>
                        <div class="col-sm-12 col-md-9 fs-6">
                            <?=link_files_by_id($data['lampiran'])?>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
        <div class="card mt-2 mb-2">
            <div class="card-header accent-navy">
                Pejabat yang memberikan cuti
            </div>
            <div class="card-body" style="background-color: var(--abu-terang);">
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Nama:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['nama_pimpinan']?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Jabatan:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['jabatan_name_pimpinan']?> (<?=$data['unit_kerja_name_alt_pimpinan']?>)
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Status:
                    </div>
                    <div class="col-sm-12 col-md-9 fw-bold">
                        <?php if($data['status'] > 4){?>
                            <?=$data['status_approval_name']?>
                        <?php }else{?>
                            <?=$data['status_name']?>
                        <?php }?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Catatan:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['catatan_pimpinan']?>
                    </div>
                </div>
                <?php if($data['status'] > 7){?>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 text-center" style="font-size: 9pt;">
                            Telah <b>diproses</b> oleh Kepegawaian pada 
                            <?php 
                            $tgl = substr($data['respon_time_kepeg'], 0, 10);
                            $jam = substr($data['respon_time_kepeg'], 11);
                            echo tanggal($tgl, 4) .', Pukul '. $jam;
                            echo ', dengan status proses <b><u>'.$data['status_kepeg_name'].'</u></b>';
                            if($data['status_kepeg']==3){
                                echo ', dengan alasan: <i><u>'.$data['catatan_kepeg'].'</u></i>';
                            }
                            ?>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-12 col-md-12 text-center">
                <?php if(return_access_link(['cuti/kirim/permohonan']) && $data['status'] < 2){?>
                    <a href="#" onclick="var cf=confirm('Apakah Anda akan melanjutkan untuk mengirim permohonan cuti?'); if(cf===true){window.location.assign('<?=site_url('cuti/kirim/permohonan?id='.$data['hash'].'&link='.$link.'&tab='.$tab)?>')}" class="btn btn-lg btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Kirim permohonan"><i class="fa fa-paper-plane"></i></a>
                <?php }?>
                <?php if(return_access_link(['cuti/form']) && $data['status'] < 2){?>
                    <a href="<?=site_url('cuti/form?id='.$data['hash'].'&link=detail')?>" class="btn btn-lg btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Ubah data"><i class="fa fa-edit"></i></a>
                <?php }?>
                <?php if(return_access_link(['cuti/riwayat', 'cuti/permohonan', 'cuti/proses'])){?>
                    <a href="<?=site_url('cuti/'.(($link)?:'riwayat').'?tab='.$tab)?>" class="btn btn-lg btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Kembali ke halaman sebelumnya"><i class="fa fa-arrow-alt-circle-left"></i></a>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <!-- <div class="card">
            <div class="card-body"> -->
                <?php
                    switch ($data['status']) {
                        case 6:
                        case 7:
                            $data_path = $data['path_pimpinan'];
                            break;
                        case 8:
                            $data_path = $data['path_kepeg'];
                            break;
                        default:
                            $data_path = $data['path'];
                            break;
                    }
                    $data_path_name = $data['unix_id'].'.pdf';
                    if(file_exists($data_path))
                    {
                        $type = pathinfo($data_path, PATHINFO_EXTENSION);
                        $dataFile = file_get_contents($data_path);
                        $file = /*'data:application/'. $type .';base64,'.*/base64_encode($dataFile);
                        $licenseKey = return_value_in_options('pdf_viewer_license')['pdfjse'];
                        ?>
                        <div id='viewerPDFJSE' style='height:690px; margin: 0 auto; border:1px solid grey;'></div>
                        <script type="text/javascript">
                            $(function(){
                                pushPdfToViewer('<?=$file?>', '<?=$licenseKey?>', '<?=$data_path_name?>')
                            })
                        </script>
                        <?php
                    }else{
                        echo '<h4>File tidak ditemukan...</h4>';
                    } 
                ?>
            <!-- </div> -->
        <!-- </div> -->
    </div>
</div>