<?php //echo json_encode($data)?>
<div class="card">
    <div class="card-body">
        <?=form_open('', ['id'=>'laporan_kegiatan_form', 'class'=>'form'])?>
        <?php if(isset($data)){?>
            <div class="row mb-2">
                <div class="col-sm-12 col-md-2 fw-bold">
                    Nama:
                </div>
                <div class="col-sm-12 col-md-10">
                    <?=(isset($data))?$data->nama:'-'?> (<i><?=(isset($data))?$data->jabatan_name:'-'?>, <?=(isset($data))?$data->unit_kerja_name_alt:''?></i>)
                </div>
            </div>
        <?php }?>
        <div class="row mb-2">
            <div class="col-sm-12 col-md-2 fw-bold">
                Tanggal:
            </div>
            <div class="col-sm-12 col-md-10">
                <input type="text" name="tanggal" id="tanggal" value="<?=(isset($data))?$data->tanggal:$tanggal?>" class="form-control datepicker">
                <input type="hidden" name="id" value="<?=(isset($data))?$data->id:0?>">
                <input type="hidden" name="pegawai_id" value="<?=(isset($data))?$data->pegawai_id:$pegawai_id?>">
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-12 col-md-2 fw-bold">
                Detail Kegiatan:
            </div>
            <div class="col-sm-12 col-md-10">
                <textarea name="laporan" id="laporan" class="form-control summernote"><?=(isset($data))?$data->laporan:''?></textarea>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-sm-12 col-md-2 fw-bold">
                Eviden Foto:</b>
            </div>
            <div class="col-sm-12 col-md-10">
                <?=files_camera_presensi((isset($data))?$data->camera:'')?>
            </div>
        </div> -->
        <div class="row mb-3">
            <div class="col-sm-12 col-md-2 fw-bold">
                Eviden file:
            </div>
            <div class="col-sm-12 col-md-10">
                <?=link_files_by_id(((isset($data))?$data->lampiran:''), '#lampiran_file', 2, 1, 'list_file')?>
                <input type="file" name="lampiran" id="lampiran" class="form-control">
                <input type="hidden" name="lampiran_file" id="lampiran_file" value="<?=((isset($data))?$data->lampiran:'')?>">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-2"></div>
            <div class="col-sm-12 col-md-10">
                <button type="submit" class="btn_login btn btn-success"><i class="fa fa-save"></i> Submit</button>
                <button type="reset" class="btn_reset btn btn-secondary" onclick="window.location.assign('<?=site_url('presensi/riwayat')?>')"><i class="fa fa-times"></i> Cancel</button>
            </div>
        </div>
        <br><br><br>
        <?=form_close()?>
    </div>
</div>

<!-- Bottom Navigation -->
<div class="bottom-nav">
    <div class="nav-container">
        <div class="nav-item" onclick="window.location.assign('<?=base_url()?>')" title="Beranda" data-bs-toggle="tooltip" data-bs-placement="bottom">
            <i class="fas fa-home"></i>
            <span>Beranda</span>
        </div>
        <?php if(return_access_link(['presensi'])){?>
            <div class="nav-item" onclick="window.location.assign('<?=site_url('presensi')?>')" title="Halaman Presensi" data-bs-toggle="tooltip" data-bs-placement="bottom">
                <i class="fas fa-street-view"></i>
                <span>Presensi</span>
            </div>
        <?php }?>
        <?php if(return_access_link(['presensi/riwayat'])){?>
            <div class="nav-item active" onclick="window.location.assign('<?=site_url('presensi/riwayat')?>')" title="Riwayat Presensi" data-bs-toggle="tooltip" data-bs-placement="bottom">
                <i class="fas fa-history"></i>
                <span>Riwayat</span>
            </div>
        <?php }?>
        <?php if(return_access_link(['presensi/harian'])){?>
            <div class="nav-item" onclick="window.location.assign('<?=site_url('presensi/harian')?>')" title="Presensi Harian Pegawai" data-bs-toggle="tooltip" data-bs-placement="bottom">
                <i class="fas fa-calendar-check"></i>
                <span>Harian</span>
            </div>
        <?php }?>
        <?php if(return_access_link(['presensi/bulanan'])){?>
            <div class="nav-item" onclick="window.location.assign('<?=site_url('presensi/bulanan')?>')" title="Resume Presensi Bulanan" data-bs-toggle="tooltip" data-bs-placement="bottom">
                <i class="fas fa-calendar-alt"></i>
                <span>Bulanan</span>
            </div>
        <?php }?>
    </div>
</div>
<link href="<?=base_url('assets/vendors/bootstrap-datepicker/css/bootstrap-datepicker.min.css')?>" rel="stylesheet" />
<script src="<?=base_url('assets/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<link href="<?=base_url('assets/vendors/summernote/summernote-lite.css')?>" rel="stylesheet" />
<script src="<?=base_url('assets/vendors/summernote/summernote-lite.min.js')?>"></script>
<script src="<?=base_url('assets/js/custom.js')?>"></script>
<script type="text/javascript">
	$(function(){
		// load_data()
	})

    $('.datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
    });

    $('.summernote').summernote({
        height: 300
    });

    $('#laporan_kegiatan_form').on('submit', function(e)
    {
        $('.btn_login').prop('disabled', true).html('Wait Proccess...');
        let data = $(this);
        e.preventDefault();
        $.ajax({
            crossDomain: true,
            crossOrigin: true,
            dataType: 'json',
            type: "POST",
            data: data.serialize(),
            // url: data.attr('action'),
            url: '<?=site_url('api/presensi/laporan/kegiatan?tanggal='.$tanggal)?>',
            headers: {
                "Key": "ramadhiantohandiprimastono@gmail.com",
            },
            success: function(responseData, textStatus, jqXHR) {
                var dt = responseData
                if(dt.status==true)
                {
                    window.location.assign('<?=site_url('presensi/riwayat')?>');
                }else{
                    alert(dt.message.replace(/<p>|<\/p>/g, ""));
                }
                $('.btn_login').prop('disabled', false).html('Submit');
                $('input[name=<?=csrf_token()?>]').val(dt.csrf);
            }
        });
    });

    $('#lampiran').on('change', function(){
        // load_modal_for_message('Upload file')
        file_upload_form('#lampiran', 'lampiran_laporan_harian', '#lampiran_file', '#list_file', '2')
    });

    // aktifasi tooltips
    // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    // var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    //     return new bootstrap.Tooltip(tooltipTriggerEl)
    // })
</script>