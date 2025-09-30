<?php
$id = 0;
if(isset($data['id'])){
	$id = $data['id'];
}
$link = ($link)?:'register';
$query_link = '';
if(array_keys(['detail'],$link)){
	$query_link = '?id='.$data['hash'];
}
echo form_open_multipart('persuratan/register/form?'.$_SERVER['QUERY_STRING'], ['class'=>'form', 'id'=>'form_register_surat_masuk'.$id])?>
	<div class="row">
		<div class="col-sm-12 col-md-12 mb-1">
			<?php if(empty($data) || !empty($data)){?>
				<div class="card">
					<div class="card-header fw-bold">
						<?=$title?>
					</div>
					<div class="card-body">
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Sumber:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<input type="hidden" name="id" id="id" value="<?=(isset($data['id']))?string_to($data['id'],'encode'):0?>">
								<select name="sumber_bentuk" id="sumber_bentuk" class="form-control"></select>
							</div>
						</div>
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Sifat:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<select name="sifat" id="sifat" class="form-control"></select>
							</div>
						</div>
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Urgensi:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<select name="urgensi" id="urgensi" class="form-control"></select>
							</div>
						</div>
						<hr>
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Nomor & Tanggal Surat:</b>
							</div>
							<div class="col-sm-12 col-md-5">
								<div class="input-group">
									<input class="form-control" id="nomor" name="nomor" value="<?=(isset($data['nomor']))?$data['nomor']:''?>" placeholder="Nomor Surat">
									<input class="form-control" id="tanggal" name="tanggal" value="<?=(isset($data['tanggal']))?$data['tanggal']:''?>" placeholder="Tanggal Surat">
									<span class="input-group-text"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
						</div>
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Hal Surat:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<textarea class="form-control" id="hal" name="hal" placeholder="Hal..."><?=(isset($data['hal']))?$data['hal']:''?></textarea>
							</div>
						</div>
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Pengirim Surat:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<textarea class="form-control" id="pengirim" name="pengirim" placeholder="Pengirim Surat"><?=(isset($data['pengirim']))?$data['pengirim']:''?></textarea>
							</div>
						</div>
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Alamat Pengirim:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<textarea class="form-control" id="pengirim_alamat" name="pengirim_alamat" placeholder="Alamat Pengirim"><?=(isset($data['pengirim_alamat']))?$data['pengirim_alamat']:''?></textarea>
							</div>
						</div>
						<hr>
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Penerima:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<select name="penerima[]" id="penerima" class="form-control" multiple></select>
								<div class="input-group mt-3 fw-bold">
									<label class="me-3"><input type="radio" name="penerima_sebagai" value="langsung" <?php if(((isset($data['penerima_sebagai']))?$data['penerima_sebagai']:'langsung')=='langsung'){echo 'checked';}?>>langsung</label>
									<label><input type="radio" name="penerima_sebagai" value="tembusan" <?php if(((isset($data['penerima_sebagai']))?$data['penerima_sebagai']:'')=='tembusan'){echo 'checked';}?>>tembusan</label>
								</div>
							</div>
						</div>
						<hr>
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">File Surat/Naskah:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<?=link_files_by_id(((isset($data['path']))?$data['path']:''), '#path_file', 1, 1, 'list_doc')?>
								<input type="file" name="path" id="path" value="<?=(isset($data['path']))?$data['path']:''?>" class="form-control mb-2">
								<input type="hidden" name="path_file" id="path_file" value="<?=(isset($data['path']))?$data['path']:''?>">
							</div>
						</div>
						<hr>
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Lampiran:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<?=link_files_by_id(((isset($data['lampiran']))?$data['lampiran']:''), '#lampiran_file', 2, 1, 'list_file')?>
								<input type="file" name="lampiran" id="lampiran" class="form-control">
								<input type="hidden" name="lampiran_file" id="lampiran_file" value="<?=((isset($data['lampiran']))?$data['lampiran']:'')?>">
							</div>
						</div>
						<hr>
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Catatan:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<textarea class="form-control summernote" id="catatan" name="catatan"><?=(isset($data['catatan']))?$data['catatan']:''?></textarea>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-sm-12 col-md-3"></div>
							<div class="col-sm-12 col-md-9">
								<button id="ID_BTN_SIMPAN" type="submit" class="btn btn-primary btn-simpan mr-2"><?php if(!isset($data['id'])){echo '<i class="fa fa-angle-right"></i> Tambah Surat Masuk Baru';}else{echo '<i class="fa fa-save"></i> Simpan Perubahan Data';}?></button>
								<button type="reset" class="btn btn-secondary btn-batal" data-bs-dismiss="modal" onclick="window.location.assign('<?=site_url('persuratan/'.$link.$query_link)?>')"><i class="fa fa-times"></i> Batal</button>
							</div>
						</div>
					</div>
				</div>
			<?php }?>
		</div>
	</div>
<?=form_close()?>
<link href="<?=base_url('assets/')?>css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url('assets/')?>js/select2.full.min.js"></script>
<link href="<?=base_url('assets/vendors/bootstrap-datepicker/css/bootstrap-datepicker.min.css')?>" rel="stylesheet" />
<script src="<?=base_url('assets/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<link href="<?=base_url('assets/vendors/summernote/summernote-lite.css')?>" rel="stylesheet" />
<script src="<?=base_url('assets/vendors/summernote/summernote-lite.min.js')?>"></script>
<script type="text/javascript">
	$(function(){
        select2_referensi('#sumber_bentuk', 'surat_bentuk')
        select2_referensi('#sifat', 'surat_sifat')
        select2_referensi('#urgensi', 'surat_urgensi')
        select2_pegawai('#penerima')
        // set value vield
        $('#sumber_bentuk').html('<option value="<?=(isset($data['sumber_bentuk']))?$data['sumber_bentuk']:'1'?>" selected><?=(isset($data['sumber_bentuk_name']))?$data['sumber_bentuk_name']:'Fisik'?></option>').trigger('change')
        $('#sifat').html('<option value="<?=(isset($data['sifat']))?$data['sifat']:'3'?>" selected><?=(isset($data['sifat_name']))?$data['sifat_name']:'Biasa'?></option>').trigger('change')
        $('#urgensi').html('<option value="<?=(isset($data['urgensi']))?$data['urgensi']:'3'?>" selected><?=(isset($data['urgensi_name']))?$data['urgensi_name']:'Biasa'?></option>').trigger('change')
        var penerima_opt = ''
        <?php if(isset($data['penerima'])){foreach ($data['penerima'] as $key => $value) {?>
        	penerima_opt += '<option value="<?=$value->pegawai_id?>" selected><?=$value->nama .' ('.$value->jabatan_name.')'?></option>'
        <?php }}?>
        $('#penerima').html(penerima_opt).trigger('change')
	})

	$('#tanggal').datepicker({
		todayHighlight: true,
		// multidate: true,
		format: 'yyyy-mm-dd',
		orientation: 'bottom',
	});

    $('.summernote').summernote({
        height: 150
    });

	$('#form_register_surat_masuk<?=$id?>').on('submit', function(e){
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
					window.location.assign('<?=site_url('persuratan/detail?id=')?>'+dt.ID+'&link=<?=$link?>')
				}else{
					alert(dt.message)
					$('.btn-simpan').html('<?php if(!isset($data['id'])){echo '<i class="fa fa-angle-right"></i> Tambah Surat Masuk Baru';}else{echo '<i class="fa fa-save"></i> Simpan Perubahan Data';}?>').removeAttr('disabled', false);
					$('.btn-batal').removeAttr('disabled', false);
				}
			}
		});
	});

	$('#path').on('change', function(){
		// load_modal_for_message('Upload file')
		file_upload_form('#path', 'surat_ext', '#path_file', '#list_doc', '1')
	});

	$('#lampiran').on('change', function(){
		// load_modal_for_message('Upload file')
		file_upload_form('#lampiran', 'lampiran_surat', '#lampiran_file', '#list_file', '2')
	});
</script>