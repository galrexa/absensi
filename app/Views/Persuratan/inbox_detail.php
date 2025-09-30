<div class="row">
    <div class="col-sm-12 col-md-12 mb-2">
        <div class="card">
            <!-- <div class="card-header"> -->
                <!-- <?=$title?> -->
            <!-- </div> -->
            <div class="card-body">
                <?php
                    echo $data['id'].'->'.$data['pos_id'].'<-'.$data['sebagai'];
                    echo '-'. return_update_status_read_surat($data['pos_id'], $datetime_now, session()->get('pegawai_id'));
                    $data_path = $data['path_sign'];
                    $data_path_name = $data['path_sign_name'];
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
            </div>
            <div class="card-footer text-center">
                <?php if(return_access_link(['persuratan/teruskan']) && $data['status'] < 2  &&$data['sumber_ext']==1){?>
                    <a href="<?=site_url('persuratan/teruskan?id='.$data['hash'].'&link='.$link.'&tab='.$tab)?>" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Teruskan Naskah"><i class="fa fa-paper-plane"></i></a>
                <?php }?>
                <?php if(return_access_link(['persuratan/form/tindaklanjut']) && $data['status'] >= 6 && (return_roles([1]) || return_check_penerima_by_surat_id_and_pegawai_id($data['id'], session()->get('pegawai_id'))) ){?>
                    <a href="<?=site_url('persuratan/form/tindaklanjut?id='.$data['hash'].'&link='.$link.'&tab='.$tab)?>" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tindak lanjut Naskah"><i class="fa fa-check"></i></a>
                <?php }?>
                <?php if(return_access_link(['persuratan/register', 'persuratan/inbox', 'persuratan/sent', 'persuratan/draft'])){?>
                    <a href="<?=site_url('persuratan/'.(($link)?:'register').'?tab='.$tab)?>" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Kembali ke halaman sebelumnya"><i class="fa fa-arrow-alt-circle-left"></i></a>
                <?php }?>
                <a href="#" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal_info_naskah"><i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Informasi Naskah"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <?php if(!empty($data_tindaklanjut)){?>
                    <?php foreach ($data_tindaklanjut as $k) {
                         $oleh_user = '';
                         if(return_roles([1])){
                            $oleh_user = ', Oleh user '.$k->create_by_name;
                         }
                        ?>
                        <?php if($k->status==1){?>
                            <div class="border rounded p-2 mb-2 color-yellow-light">
                                <div class="border rounded p-2 color-yellow">
                                    <b><?=$k->status_name?></b> Dari <b><?=$k->pengirim_nama?> [<i class="fw-normal"><?=$k->pengirim_jabatan_name?>, <?=$k->pengirim_unit_name?></i>]</b>
                                    <small class="d-block">
                                        <span class="d-block" data-bs-toggle="tooltip" data-bs-placement="top" title="Waktu kirim <?=tanggal(substr($k->sent_time,0,10),4)?>, Pukul <?=substr($k->sent_time,11) . $oleh_user?>">Pada <?=tanggal(substr($k->sent_time,0,10),4)?>, Pukul <?=substr($k->sent_time,11)?></span>
                                    </small>
                                </div>
                                <div class="border rounded p-2 color-abu">
                                    Kepada <b><?=$k->penerima_nama?> [<i class="fw-normal"><?=$k->penerima_jabatan_name?>, <?=$k->penerima_unit_name?></i>]</b> 
                                    <?php if($k->read==0 && $k->respon==0){ ?>
                                        <i class="fa fa-minus-circle text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Belum respon"></i>
                                    <?php }else{ ?>
                                        <?php if($k->read==1){?>
                                            <i class="far fa-registered text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Dibaca pada <?=tanggal(substr($k->read_time,0,10),4)?>, Pukul <?=substr($k->read_time,11)?>"></i>
                                        <?php }?>
                                        <?php if($k->respon==1){?>
                                            <i class="far fa-check-circle text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Ditindaklajuti pada <?=tanggal(substr($k->respon_time,0,10),4)?>, Pukul <?=substr($k->respon_time,11)?>"></i>
                                        <?php }else{?>
                                            <i class="far fa-check-circle text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Belum ditindaklajuti"></i>
                                        <?php }?>

                                    <?php } ?>
                                </div>
                                <div class="border rounded p-2 color-abu">
                                    <b class="d-block">Keterangan/Catatan/Disposisi:</b>
                                    <?=$k->value?>
                                    <?=$k->catatan?>
                                    <?php if($k->lampiran){?>
                                        <div>
                                            <?php $lampiranExp = explode(',', $k->lampiran);
                                            foreach ($lampiranExp as $key => $value) {
                                                echo '<a href="'.site_url('file/download?id='.$value).'" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Unduh file lampiran" class="me-2"><i class="fa fa-paperclip"></i></a>';
                                            }
                                            ?>
                                        </div>
                                    <?php }?>
                                    <div>
                                        <?php if(return_access_link(['persuratan/form/tindaklanjut/disposisi']) && ($k->penerima_id==session()->get('pegawai_id'))){?>
                                            <center><a href="<?=site_url('persuratan/form/tindaklanjut/disposisi?'.$_SERVER['QUERY_STRING'].'&id_tl='.string_to($k->id, 'encode'))?>" class="btn btn-warning btn-sm"><i class="fa fa-check"></i> tindak lanjut</a></center>
                                        <?php }?>
                                        <?=view_result_surat_tindaklanjut_by_surat_id_and_id($k->surat_id, $k->id)?>
                                    </div>
                                </div>
                            </div>
                        <?php }else{?>
                            <div class="border rounded p-2 mb-2 color-green-light">
                                <div class="border rounded p-2 color-abu">
                                    <b><?=$k->status_name?></b> dari <b><?=$k->pengirim_nama?> [<i class="fw-normal"><?=$k->pengirim_jabatan_name?>, <?=$k->pengirim_unit_name?></i>]</b> 
                                    <small class="d-block">
                                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Waktu proses <?=tanggal(substr($k->sent_time,0,10),4)?>, Pukul <?=substr($k->sent_time,11) . $oleh_user?>">Pada <?=tanggal(substr($k->sent_time,0,10),4)?>, Pukul <?=substr($k->sent_time,11)?></span>
                                    </small>
                                </div>
                                <div class="border rounded p-2 color-abu">
                                    <b class="d-block">Keterangan/Catatan:</b>
                                    <?=$k->value?>
                                    <?=$k->catatan?>
                                    <?php if($k->lampiran){?>
                                        <div>
                                            <?php $lampiranExp = explode(',', $k->lampiran);
                                            foreach ($lampiranExp as $key => $value) {
                                                echo '<a href="'.site_url('file/download?id='.$value).'" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Unduh file lampiran" class="me-2"><i class="fa fa-paperclip"></i></a>';
                                            }
                                            ?>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                        <?php }?>
                    <?php }?>
                <?php }else{?>
                    <b class="d-block text-danger">Belum ada tindak lanjut <i class="fa fa-question-circle"></i></b>
                <?php }?>
            </div>
        </div>
    </div>
</div>
<!-- modal  -->
<div class="modal modal-lg" tabindex="-1" id="modal_info_naskah" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header color-abu fw-bold" id="modal_info_naskah_header">
                <h5 class="modal-title">Informasi Naskah</h5>
                <button type="button" class="bg-danger btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_info_naskah_body">
                <div class="row mb-2">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Register:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <b><?=$data['register_number']?></b>, 
                        <?=tanggal(substr($data['register_time'],0,10), 4) .', Pukul '. substr($data['register_time'], 11)?>
                    </div>
                </div>
                <hr>
                <div class="row mb-2">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Sifat/Urgensi:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['sifat_name'] .'/'. $data['urgensi_name']?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Nomor:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['nomor']?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Tanggal:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=tanggal($data['tanggal'],4)?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Hal:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['hal']?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Pengirim:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['pengirim']?>
                    </div>
                </div>
                <?php if(!empty($data['penerima'])){?>
                    <div class="row mb-2">
                        <div class="col-sm-12 col-md-3 fw-bold">
                            Penerima:
                        </div>
                        <div class="col-sm-12 col-md-9">
                            <?php if(count($data['penerima'])<=3){?>
                            <ul>
                                <?php foreach ($data['penerima'] as $key => $value) {?>
                                    <li><?=$value->nama .' ('.$value->jabatan_name.')'?></li>
                                <?php }?>
                            </ul>
                        <?php }else{?>
                            <ul>
                                <?php foreach ($data['penerima'] as $key => $value) if($key<3){?>
                                    <li><?=$value->nama .' ('.$value->jabatan_name.')'?></li>
                                <?php }?>
                            </ul>
                            <a href="#">lihat lebih banyak...</a>
                        <?php }?>
                        </div>
                    </div>
                <?php }?>
                <?php if(!empty($data['tembusan'])){?>
                    <div class="row mb-2">
                        <div class="col-sm-12 col-md-3 fw-bold">
                            tembusan:
                        </div>
                        <div class="col-sm-12 col-md-9">
                            <ul>
                                <?php foreach ($data['tembusan'] as $key => $value) {?>
                                    <li><?=$value->nama .' ('.$value->jabatan_name.')'?></li>
                                <?php }?>
                            </ul>
                        </div>
                    </div>
                <?php }?>
                <?php if($data['catatan']<>''){?>
                    <hr>
                    <div class="row mb-2">
                        <div class="col-sm-12 col-md-3 fw-bold">
                            Keterangan:
                        </div>
                        <div class="col-sm-12 col-md-9">
                            <?=$data['catatan']?>
                        </div>
                    </div>
                <?php }?>
                <?php if(!array_keys([null,''], $data['lampiran'])){?>
                    <div class="row mb-2">
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
    </div>
</div>
