<?=form_open('', ['id'=>'form_sk', 'class'=>'form']);
$id = 0;
$status = 1;
$dokumen = 0;
if(!empty($data))
{
	$id = $data->id;
	$status = $data->status;
	$dokumen = $data->dokumen;
}
?>
<div class="card">
	<div class="card-header fw-bold" style="font-size:16pt">
		<?=$title?>
	</div>
	<div class="card-body">
		<div class="row mb-2">
			<div class="col-sm-12 col-md-3">
				<span class="fw-bold">Jenis :</span>
			</div>
			<div class="col-sm-12 col-md-3">
				<select name="jenis" id="jenis" class="form-control form-control-sm"></select>
				<input type="hidden" name="id" value="<?=$id?>">
				<input type="hidden" name="pegawai_id" value="<?=$pegawai_id?>">
			</div>
		</div>
		<div class="row mb-2">
			<div class="col-sm-12 col-md-3">
				<span class="fw-bold">Nomor & Tanggal :</span>
			</div>
			<div class="col-sm-12 col-md-3">
				<input type="text" name="nomor" class="form-control form-control-sm" value="<?=(isset($data))?$data->nomor:(!empty($_POST)?$_POST['nomor']:'')?>">
			</div>
			<div class="col-sm-12 col-md-3">
				<div class="input-group">
					<div class="input-group-text"><i class="fa fa-calendar"></i></div>
					<input type="text" name="tanggal" id="tanggal" class="form-control form-control-sm datepicker" value="<?=(isset($data))?$data->tanggal:''?>">
				</div>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col-sm-12 col-md-3">
				<span class="fw-bold">Unit Kerja :</span>
			</div>
			<div class="col-sm-12 col-md-9">
				<select name="unit_kerja_id" id="unit_kerja_id" class="form-control form-control-sm"></select>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col-sm-12 col-md-3">
				<span class="fw-bold">Jabatan :</span>
			</div>
			<div class="col-sm-12 col-md-9">
				<select name="jabatan_id" id="jabatan_id" class="form-control form-control-sm"></select>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col-sm-12 col-md-3">
				<span class="fw-bold">Periode :</span>
			</div>
			<div class="col-sm-12 col-md-9">
				<div class="input-group">
					<div class="input-group-text"><i class="fa fa-calendar"></i></div>
					<input type="text" name="periode_awal" id="periode_awal" class="form-control form-control-sm datepicker" value="<?=(isset($data))?$data->periode_awal:''?>">
					<div class="input-group-text">s.d</div>
					<input type="text" name="periode_akhir" id="periode_akhir" class="form-control form-control-sm datepicker" value="<?=(isset($data))?$data->periode_akhir:''?>">
					<div class="input-group-text"><i class="fa fa-calendar"></i></div>
				</div>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col-sm-12 col-md-3">
				<span class="fw-bold">Status :</span>
			</div>
			<div class="col-sm-12 col-md-9">
				<label class="me-3"><input type="radio" value="1" name="status" <?php if($status==1){echo'checked';}?>>Aktif</label>
				<label class="me-3"><input type="radio" value="2" name="status" <?php if($status==2){echo'checked';}?>>Berakhir</label>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col-sm-12 col-md-3">
				<span class="fw-bold">Keterangan/Catatan :</span>
			</div>
			<div class="col-sm-12 col-md-9">
				<textarea name="keterangan" class="form-control"><?=(isset($data))?$data->keterangan:(!empty($_POST)?$_POST['keterangan']:'')?></textarea>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-3">
				<span class="fw-bold">File :</span>
			</div>
			<div class="col-sm-12 col-md-9">
				<input type="file" name="dokumen" id="dokumen" value="<?=(isset($data))?$data->dokumen:''?>" class="form-control mb-2">
				<input type="hidden" name="dokumen_id" id="dokumen_id" value="<?=(isset($data))?$data->dokumen:''?>">
				<?=link_files_by_id($dokumen, '#dokumen_id', 2)?>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<button class="btn btn-success" type="submit" class="btn_login">Submit</button>
		<button class="btn btn-secondary" type="reset" onclick="window.location.assign('<?=site_url('kepegawaian/profile?id='.$pegawai_id.'&tab=keputusan')?>')">Cancel</button>
	</div>
</div>
<?=form_close()?>
<link href="<?=base_url('assets/vendors/bootstrap-datepicker/css/bootstrap-datepicker.min.css')?>" rel="stylesheet" />
<script src="<?=base_url('assets/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<link href="<?=base_url('assets/')?>css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url('assets/')?>js/select2.full.min.js"></script>
<script type="text/javascript">
	$(function(){
		<?php if(!empty($data)){?>
        	$('#jenis').append('<option value="<?=$data->jenis?>" selected><?=$data->jenis_name?></option>').trigger('change')
        	$('#unit_kerja_id').append('<option value="<?=$data->unit_kerja_id?>" selected><?=$data->unit_kerja_name?></option>').trigger('change')
        	$('#jabatan_id').append('<option value="<?=$data->jabatan_id?>" selected><?=$data->jabatan_name?></option>').trigger('change')
        <?php }?>
		select2_referensi('#jenis', 'pegawai_sk')
		select2_unit_kerja('#unit_kerja_id')
		select2_jabatan('#jabatan_id')
	})

	$('.datepicker').datepicker({
		autoclose: true,
		format: 'yyyy-mm-dd',
	});

	$('#unit_kerja_id').on('select2:select', function(){
		select2_jabatan('#jabatan_id', this.value)
	})

	$('#dokumen').on('change', function(){
		file_upload_form('#dokumen', 'sk', '#dokumen_id', '#list_file', '2')
	})

	$('#form_sk').on('submit', function(e)
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
			url: data.attr('action'),
			headers: {
				"Key": "ramadhiantohandiprimastono@gmail.com",
			},
			success: function(responseData, textStatus, jqXHR) {
				var dt = responseData
				if(dt.status==true)
				{
					window.location.assign('<?=site_url('kepegawaian/profile?id='.$pegawai_id.'&tab=keputusan')?>');
				}else{
					alert(dt.message.replace(/<p>|<\/p>/g, ""));
				}
				$('.btn_login').prop('disabled', false).html('Submit');
				$('input[name=<?=csrf_token()?>]').val(dt.csrf);
			}
		});
	});
</script>