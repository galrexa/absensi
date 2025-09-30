<?=form_open('', ['id'=>'form_fasilitas', 'class'=>'form']);
$id = 0;
$status = 1;
if(!empty($data))
{
	$id = $data->fasilitas_id;
	$status = $data->status;
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
			<div class="col-sm-12 col-md-9">
				<select name="ref_fasilitas_id" id="ref_fasilitas_id" class="form-control form-control-sm"></select>
				<input type="hidden" name="id" value="<?=$id?>">
				<input type="hidden" name="pegawai_id" value="<?=$pegawai_id?>">
			</div>
		</div>
		<div class="row mb-2">
			<div class="col-sm-12 col-md-3">
				<span class="fw-bold">Tanggal :</span>
			</div>
			<div class="col-sm-12 col-md-9">
				<div class="input-group">
					<div class="input-group-text"><i class="fa fa-calendar"></i></div>
					<input type="text" name="fasilitas_tgl" id="fasilitas_tgl" class="form-control form-control-sm datepicker" value="<?=(isset($data))?$data->fasilitas_tgl:''?>">
				</div>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col-sm-12 col-md-3">
				<span class="fw-bold">Catatan :</span>
			</div>
			<div class="col-sm-12 col-md-9">
				<textarea name="fasilitas_value" class="form-control"><?=(isset($data))?$data->fasilitas_value:(!empty($_POST)?$_POST['fasilitas_value']:'')?></textarea>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col-sm-12 col-md-3">
				<span class="fw-bold">Status :</span>
			</div>
			<div class="col-sm-12 col-md-9">
				<label class="me-3"><input type="radio" value="1" name="status" <?php if($status==1){echo'checked';}?>>Aktif</label>
				<label class="me-3"><input type="radio" value="2" name="status" <?php if($status==2){echo'checked';}?>>Dikembalikan</label>
			</div>
		</div>
		<div class="row mb-2 div_pengembalian">
			<div class="col-sm-12 col-md-3">
				<span class="fw-bold">Tanggal Balik :</span>
			</div>
			<div class="col-sm-12 col-md-9">
				<div class="input-group">
					<div class="input-group-text"><i class="fa fa-calendar"></i></div>
					<input type="text" name="tgl_dikembalikan" id="tgl_dikembalikan" class="form-control form-control-sm datepicker" value="<?=(isset($data))?$data->tgl_dikembalikan:''?>">
				</div>
			</div>
		</div>
		<div class="row div_pengembalian">
			<div class="col-sm-12 col-md-3">
				<span class="fw-bold">Catatan :</span>
			</div>
			<div class="col-sm-12 col-md-9">
				<textarea name="fasilitas_ket" id="fasilitas_ket" class="form-control"><?=(isset($data))?$data->fasilitas_ket:(!empty($_POST)?$_POST['fasilitas_ket']:'')?></textarea>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<button class="btn btn-success" type="submit" class="btn_login">Submit</button>
		<button class="btn btn-secondary" type="reset" onclick="window.location.assign('<?=site_url('kepegawaian/profile?id='.$pegawai_id.'&tab=fasilitas')?>')">Cancel</button>
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
        	$('#ref_fasilitas_id').append('<option value="<?=$data->ref_fasilitas_id?>" selected><?=$data->fasilitas_name?></option>').trigger('change')
        <?php }?>
       	check_status_fasilitas()
		select2_referensi('#ref_fasilitas_id', 'pegawai_fasilitas')
	})

	$('.datepicker').datepicker({
		autoclose: true,
		format: 'yyyy-mm-dd',
		orientation: 'bottom',
	});

	$('#unit_kerja_id').on('select2:select', function(){
		select2_jabatan('#jabatan_id', this.value)
	})

	$('input[name=status]').on('change', function(){
		check_status_fasilitas()
	})

	function check_status_fasilitas()
	{
		var id = $('input[name=status]:checked').val()
		switch (id){
		case '1':
			$('.div_pengembalian').hide()
			$('#tgl_dikembalikan').val('')
			$('#fasilitas_ket').val('')
			break
		default:
			$('.div_pengembalian').show()
			break
		}
	}

	$('#form_fasilitas').on('submit', function(e)
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
					window.location.assign('<?=site_url('kepegawaian/profile?id='.$pegawai_id.'&tab=fasilitas')?>');
				}else{
					alert(dt.message.replace(/<p>|<\/p>/g, ""));
				}
				$('.btn_login').prop('disabled', false).html('Submit');
				$('input[name=<?=csrf_token()?>]').val(dt.csrf);
			}
		});
	});
</script>