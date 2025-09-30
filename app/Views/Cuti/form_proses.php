<div class="row">
    <div class="col-sm-12 col-md-7 mb-2">
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
    <div class="col-sm-12 col-md-5">
        <?=form_open_multipart('cuti/form/proses?'.$_SERVER['QUERY_STRING'], ['class'=>'form', 'id'=>'form_proses_cuti'])?>
            <div class="card mb-2">
                <div class="card-header color-red">
                    Status Proses Kepegawaian
                </div>
                <div class="card-body" style="background-color: var(--abu-terang);">
                    <div class="row mb-2">
                        <div class="col-sm-12 col-md-3 fw-bold">
                            Nomor:
                        </div>
                        <div class="col-sm-12 col-md-9">
                            <?=$data['nomor_surat']?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-12 col-md-3 fw-bold">
                            Jenis Cuti:
                        </div>
                        <div class="col-sm-12 col-md-9">
                            <?=$data['jenis_cuti_name']?> (<i><?=$data['jenis_cuti_name_alt']?></i>)
                            <?php if(array_keys([2,3,4], $data['jenis_cuti']) && $data['lampiran']<>''){?>
                                <?=link_files_by_id($data['lampiran'])?>
                            <?php }?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-12 col-md-3 fw-bold">
                            Pemohon:
                        </div>
                        <div class="col-sm-12 col-md-9">
                            <?=$data['nama'] .' (<i>'.$data['jabatan_name'].' - '.$data['unit_kerja_name_alt'].'</i>)'?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-12 col-md-3 fw-bold">
                            Pejabat Berwenang:
                        </div>
                        <div class="col-sm-12 col-md-9">
                            <?=$data['nama_pimpinan'] .' (<i>'.$data['jabatan_name_pimpinan'].' - '.$data['unit_kerja_name_alt_pimpinan'].'</i>)'?>
                            <input type="hidden" name="id" value="<?=$data['id']?>">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-12 col-md-3 fw-bold">
                            Approval Pimpinan:
                        </div>
                        <div class="col-sm-12 col-md-9 fw-bold">
                            <?php if($data['status'] > 4){?>
                                <?=$data['status_approval_name']?>
                            <?php }else{?>
                                <?=$data['status_name']?>
                            <?php }?>
                        </div>
                    </div>
                    <?php if($data['catatan_pimpinan']<>''){?>
                        <div class="row mb-2">
                            <div class="col-sm-12 col-md-3 fw-bold">
                                Catatan:
                            </div>
                            <div class="col-sm-12 col-md-9">
                                <?=($data['catatan_pimpinan'])?:'-'?>
                            </div>
                        </div>
                    <?php }?>
                    <hr>
                    <div class="row mb-2">
                        <div class="col-sm-12 col-md-3 fw-bold">
                            Status*:
                        </div>
                        <div class="col-sm-12 col-md-9 fw-bold">
                            <input type="hidden" name="id" value="<?=$data['id']?>">
                            <?php foreach (return_referensi_list('cuti_status_proses_kepeg') as $key) if($key->ref_code>1){?>
                                <label class="me-2"><input type="radio" name="status_approval" onclick="check_status_pengajuan()" value="<?=$key->ref_code?>"> <?=$key->ref_name?></label>
                            <?php }?>
                        </div>
                    </div>
                    <div class="row mb-2" id="div_catatan">
                        <div class="col-sm-12 col-md-3 fw-bold">
                            Catatan:
                        </div>
                        <div class="col-sm-12 col-md-9">
                            <textarea class="form-control" id="catatan" name="catatan" placeholder="Catatan..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-lg btn-warning btn-simpan" data-bs-toggle="tooltip" data-bs-placement="top" title="Proses akhir permohonan"><i class="fa fa-check-circle"></i></button>
                    <?php if(return_access_link(['cuti/proses'])){?>
                        <a href="<?=site_url('cuti/proses')?>" class="btn btn-lg btn-secondary btn-batal" data-bs-toggle="tooltip" data-bs-placement="top" title="Kembali ke halaman sebelumnya"><i class="fa fa-arrow-alt-circle-left"></i></a>
                    <?php }?>
                </div>
            </div>
        <?=form_close()?>
    </div>
</div>
<script type="text/javascript">

    $(function(){
        check_status_pengajuan()
    })
    
    function check_status_pengajuan()
    {
        var status = $('input[name=status_approval]:checked').val()
        switch(status) {
          case '3':
          case '4':
          case '5':
            $('#div_catatan').show()
            $('#catatan').prop('required', true)
            break;
          default:
            $('#div_catatan').hide()
            $('#catatan').prop('required', false)
            break;
        } 
    }

    $('#form_proses_cuti').on('submit', function(e){
        $('.btn-batal').attr('disabled', true);
        $('.btn-simpan').attr('disable', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> harap tunggu...')
        e.preventDefault();
        var form = $(this);
        $.ajax({
            crossDomain: true,
            crossOrigin: true,
            dataType: 'json',
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(),
            success: function(responseData, textStatus, jqXHR) {
                var dt = responseData
                $('input[name=<?=csrf_token()?>]').val(dt.csrf)
                if(dt.status==true)
                {
                    window.location.assign('<?=site_url('cuti/proses')?>')
                }else{
                    alert(dt.message)
                    $('.btn-simpan').html('<i class="fa fa-check-circle"></i>').removeAttr('disabled', false);
                    $('.btn-batal').removeAttr('disabled', false);
                }
            }
        });
    });
</script>