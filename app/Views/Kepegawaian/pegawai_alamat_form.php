<?=form_open('', ['id'=>'form_alamat', 'class'=>'form']);
$id = 0;
if(!empty($data))
{
	$id = $data->alamat_id;
}
?>
<div class="card">
	<div class="card-header fw-bold" style="font-size:16pt">
		<?=$title?>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-sm-12 col-md-7">
				<div class="row mb-2">
					<div class="col-sm-12 col-md-3">
						<span class="fw-bold">Name :</span>
					</div>
					<div class="col-sm-12 col-md-9">
						<input type="text" name="alamat_name" class="form-control form-control-sm" value="<?=(isset($data))?$data->alamat_name:(!empty($_POST)?$_POST['alamat_name']:'')?>">
						<input type="hidden" name="id" value="<?=$id?>">
						<input type="hidden" name="pegawai_id" value="<?=$pegawai_id?>">
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-sm-12 col-md-3">
						<span class="fw-bold">Provinsi :</span>
					</div>
					<div class="col-sm-12 col-md-9">
						<select name="provinsi" id="provinsi" class="form-control form-control-sm"></select>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-sm-12 col-md-3">
						<span class="fw-bold">Kabupaten/Kota :</span>
					</div>
					<div class="col-sm-12 col-md-9">
						<select name="kabupaten" id="kabupaten" class="form-control form-control-sm"></select>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-sm-12 col-md-3">
						<span class="fw-bold">Kecamatan :</span>
					</div>
					<div class="col-sm-12 col-md-9">
						<select name="kecamatan" id="kecamatan" class="form-control form-control-sm"></select>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-sm-12 col-md-3">
						<span class="fw-bold">Kelurahan :</span>
					</div>
					<div class="col-sm-12 col-md-9">
						<select name="kelurahan" id="kelurahan" class="form-control form-control-sm"></select>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-5">
				<div class="row mb-2">
					<div class="col-sm-12 col-md-12">
						<span class="fw-bold">Alamat Detail :</span>
						<textarea name="alamat" class="form-control mb-2"><?=(isset($data))?$data->alamat:(!empty($_POST)?$_POST['alamat']:'')?></textarea>
						<div class="input-group">
							<div class="input-group-text">RT:</div>
							<input type="text" name="rt" id="rt" class="form-control form-control-sm" value="<?=(isset($data))?$data->rt:''?>">
							<div class="input-group-text">RW:</div>
							<input type="text" name="rw" id="rw" class="form-control form-control-sm" value="<?=(isset($data))?$data->rw:''?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="input-group">
							<div class="input-group-text">KodePos:</div>
							<input type="text" name="kodepos" id="kodepos" class="form-control form-control-sm" value="<?=(isset($data))?$data->kodepos:''?>">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<button class="btn btn-success" type="submit" class="btn_login">Submit</button>
		<button class="btn btn-secondary" type="reset" onclick="window.location.assign('<?=site_url('kepegawaian/profile?id='.$pegawai_id.'&tab=alamat')?>')">Cancel</button>
	</div>
</div>
<?=form_close()?>
<link href="<?=base_url('assets/')?>css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url('assets/')?>js/select2.full.min.js"></script>
<script type="text/javascript">
	$(function(){
		<?php if(!empty($data)){?>
        	$('#provinsi').append('<option value="<?=$data->provinsi?>" selected><?=$data->provinsi_name?></option>').trigger('change')
        	$('#kabupaten').append('<option value="<?=$data->kabupaten?>" selected><?=$data->kabupaten_name?></option>').trigger('change')
        	$('#kecamatan').append('<option value="<?=$data->kecamatan?>" selected><?=$data->kecamatan_name?></option>').trigger('change')
        	$('#kelurahan').append('<option value="<?=$data->kelurahan?>" selected><?=$data->kelurahan_name?></option>').trigger('change')
        <?php }?>
		select2_wilayah('#provinsi', 0)
		select2_wilayah('#kabupaten', $('#provinsi').val())
		select2_wilayah('#kecamatan', $('#kabupaten').val())
		select2_wilayah('#kelurahan', $('#kecamatan').val())
	})

	$('#provinsi').on('select2:select', function(){
		select2_wilayah('#kabupaten', this.value)
	})

	$('#kabupaten').on('select2:select', function(){
		select2_wilayah('#kecamatan', this.value)
	})

	$('#kecamatan').on('select2:select', function(){
		select2_wilayah('#kelurahan', this.value)
	})

	$('#form_alamat').on('submit', function(e)
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
					window.location.assign('<?=site_url('kepegawaian/profile?id='.$pegawai_id.'&tab=alamat')?>');
				}else{
					alert(dt.message.replace(/<p>|<\/p>/g, ""));
				}
				$('.btn_login').prop('disabled', false).html('Submit');
				$('input[name=<?=csrf_token()?>]').val(dt.csrf);
			}
		});
	});
</script>