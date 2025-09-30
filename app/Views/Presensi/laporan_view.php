<?php if(!empty($presensi)){?>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="card mb-1">
                <div class="card-header d-flex">
                    <div class="flex-grow-1">Detail Presensi</div>
                    <a href="<?=site_url('presensi/'.$link)?>" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Kembali kehalaman sebelumnya"><i class="fa fa-times-circle"></i></a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-2 fw-bold">
                            Nama:
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <?=$presensi->nama?> (<i><?=$presensi->jabatan_name?>, <?=$presensi->unit_kerja_name_alt?></i>)
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-2 fw-bold">
                            Tanggal:
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <?=(isset($presensi))?tanggal($presensi->tanggal,4):'-'?>
                        </div>
                    </div>
                    <div class="row color-abu rounded mt-1">
                        <div class="col-sm-12 col-md-2 fw-bold">
                            Mulai:
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <?=(isset($presensi))?$presensi->start:'-'?> (<a href="#" onclick="window.open('<?=site_url('service/maps?latlng='.$presensi->start_latlong.'&title=Lokasi absen mulai `'.$presensi->nama.'`, pada '.$presensi->start.'')?>', '', 'top=100,left=300,width=700,height=639')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat maps"><i class="fas fa-map-marker-alt text-success"></i><?=(isset($presensi))?$presensi->start_log:'-'?></a>) IPAddress: <?=$presensi->start_ip?>
                            <?php if($presensi->start_catatan<>''){?>
                                <div class="d-block">
                                    <b class="d-block">Keterangan:</b>
                                    <?=$presensi->start_catatan?>
                                    <?=files_camera_presensi($presensi->start_cam, 150)?>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                    <?php if($presensi->stop<>''){?>
                        <div class="row color-abu rounded mt-1">
                            <div class="col-sm-12 col-md-2 fw-bold">
                                Selesai:
                            </div>
                            <div class="col-sm-12 col-md-10">
                                <?php if($presensi->stop<>''){?>
                                    <?=(isset($presensi))?$presensi->stop:'-'?> (<a href="#" onclick="window.open('<?=site_url('service/maps?latlng='.$presensi->stop_latlong.'&title=Lokasi absen mulai `'.$presensi->nama.'`, pada '.$presensi->stop.'`')?>', '', 'top=100,left=300,width=700,height=639')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat maps"><i class="fas fa-map-marker-alt text-danger"></i><?=(isset($presensi))?$presensi->stop_log:'-'?></a>) IPAddress: <?=$presensi->stop_ip?>
                                    <?php if($presensi->start_catatan<>''){?>
                                        <div class="d-block">
                                            <b class="d-block">Keterangan:</b>
                                            <?=$presensi->stop_catatan?>
                                            <?=files_camera_presensi($presensi->stop_cam, 150)?>
                                        </div>
                                    <?php }?>
                                <?php }?>
                            </div>
                        </div>
                    <?php }?>
                    <div class="row mt-1">
                        <div class="col-sm-12 col-md-2 fw-bold">
                            Durasi:
                        </div>
                        <div class="col-sm-12 col-md-10 fw-bold">
                            <?=$presensi->total_durasi?>
                            <div class="fw-normal">
                                (
                                    Flexi: <?=($presensi->durasi_terlambat)?'-':$presensi->durasi_flexi?>, 
                                    Terlambat: <?=($presensi->durasi_terlambat)?$presensi->durasi_terlambat:'-'?>, 
                                    Mendahului: <?=($presensi->durasi_mendahului)?$presensi->durasi_mendahului:'-'?>
                                )
                            </div>
                            <div class="fw-bold">
                                <?php 
                                $list_array = [];
                                foreach($list_pelanggaran as $k){
                                    array_push($list_array, $k->kode .' (<i class="fw-normal">'.$k->keterangan.'</i>)');
                                }
                                ?>
                                <?=implode('<br>', $list_array)?>
                                <?php #print_r($list_pelanggaran)?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="card">
                <div class="card-header d-flex">
                    <div class="flex-grow-1">Detail Laporan Kegiatan</div>
                    <!-- <a href="<?=session()->get('_ci_previous_url')?>" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Kembali kehalaman sebelumnya"><i class="fa fa-times-circle"></i></a> -->
                </div>
                <div class="card-body">
                    <?php if(!empty($data)){?>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <b class="d-block">Detail Kegiatan:</b>
                                <?=(isset($data))?$data->laporan:'-'?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <b class="d-block">Eviden file:</b>
                                <?=link_files_by_id($data->lampiran)?>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <b class="d-block">Eviden Foto:</b>
                                <?=files_camera_presensi($data->camera, 150)?>
                            </div>
                        </div>
                    <?php }else{?>
                        <h5><i class="fa fa-exclamation-circle text-warning"></i> Tidak ada laporan kegiatan.</h5>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <script src="<?=base_url('assets/js/jquery.zoom.min.js')?>"></script>
    <script>
        $(document).ready(function(){
            $('.cam_view').zoom(/*{ on:'click|toggle|grab' }*/);
        });
    </script>
<?php }else{?>
    <h5>Presensi tidak tersedia.</h5>
<?php }?>