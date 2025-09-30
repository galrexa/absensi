<div class="row">
    <div class="col-sm-12 col-md-8 mb-2">
        <?php
            echo $data['id'].'->'.$data['pos_id'].'<-'.$data['sebagai'];
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
    <div class="col-sm-12 col-md-4">
        <div class="card">
            <div class="card-header">
                <?=$title?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Nomor Reg:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['register_number']?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Tanggal Reg:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=tanggal($data['register_time'])?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Nomor:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['nomor']?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Tanggal:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['tanggal']?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Sif/Urg:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['sifat_name'] .'/'. $data['urgensi_name']?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Hal:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['hal']?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 fw-bold">
                        Pengirim:
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <?=$data['pengirim']?>
                    </div>
                </div>
                <?php if($data['catatan']<>''){?>
                    <div class="row">
                        <div class="col-sm-12 col-md-3 fw-bold">
                            Keterangan:
                        </div>
                        <div class="col-sm-12 col-md-9">
                            <?=$data['catatan']?>
                        </div>
                    </div>
                <?php }?>
                <?php if(!array_keys([null,''], $data['lampiran'])){?>
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
                Tujuan Kepada
            </div>
            <div class="card-body" style="background-color: var(--abu-terang);">
                <?php if(!empty($data['penerima'])){?>
                    <div class="row">
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
                    <div class="row">
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
            </div>
            <div class="card-footer text-center">
                <?php if(return_access_link(['persuratan/compose']) && $data['status'] < 2 && $data['sumber_ext']==0){?>
                    <a href="<?=site_url('persuratan/compose?id='.$data['hash'].'&link=detail')?>" class="btn btn-lg btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah naskah"><i class="fa fa-edit"></i></a>
                <?php }?>
                <?php if(return_access_link(['persuratan/register/form']) && $data['status'] < 2 && $data['sumber_ext']==1){?>
                    <a href="<?=site_url('persuratan/register/form?id='.$data['hash'].'&link=detail')?>" class="btn btn-lg btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah data"><i class="fa fa-edit"></i></a>
                <?php }?>
                <?php if(return_access_link(['persuratan/teruskan']) && $data['status'] < 2  &&$data['sumber_ext']==1){?>
                    <a href="<?=site_url('persuratan/teruskan?id='.$data['hash'].'&link='.$link.'&tab='.$tab)?>" class="btn btn-lg btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Teruskan Naskah"><i class="fa fa-paper-plane"></i></a>
                <?php }?>
                <?php if(return_access_link(['persuratan/kirim/review']) && $data['status'] < 2 && $data['sumber_ext']==0){?>
                    <a href="<?=site_url('persuratan/kirim/review?id='.$data['hash'].'&link='.$link.'&tab='.$tab)?>" class="btn btn-lg btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Kirim pada reviewer Naskah"><i class="fa fa-paper-plane"></i></a>
                <?php }?>
                <?php if(return_access_link(['persuratan/kirim/naskah']) && $data['status'] < 2 && $data['sumber_ext']==0){?>
                    <a href="<?=site_url('persuratan/kirim/naskah?id='.$data['hash'].'&link='.$link.'&tab='.$tab)?>" class="btn btn-lg btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Kirim Naskah"><i class="fa fa-paper-plane"></i></a>
                <?php }?>
                <?php if(return_access_link(['persuratan/form/tindaklanjut']) && $data['status'] >= 6 && (return_roles([1])) && array_keys(['inbox'], $link)){?>
                    <a href="<?=site_url('persuratan/form/tindaklanjut?id='.$data['hash'].'&link='.$link.'&tab='.$tab)?>" class="btn btn-lg btn-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tindak lanjut Naskah"><i class="fa fa-check"></i></a>
                <?php }?>
                <?php if(return_access_link(['persuratan/register', 'persuratan/inbox', 'persuratan/sent', 'persuratan/draft'])){?>
                    <a href="<?=site_url('persuratan/'.(($link)?:'register').'?tab='.$tab)?>" class="btn btn-lg btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Kembali ke halaman sebelumnya"><i class="fa fa-arrow-alt-circle-left"></i></a>
                <?php }?>
            </div>
        </div>
        <div class="row mb-2">
        </div>
    </div>
</div>