<?=form_open('', ['id'=>'form_user_pegawai', 'class'=>'form']);
$id = 0;
$status = 1;
if(!empty($data))
{
	$id = $data->id;
	$status = $data->status;
}else{
	echo '<marquee class="mb-2 text-danger fw-bold"><i class="fa fa-exclamation-circle"></i> Pegawai belum memiliki user untuk login. Lengkapi form dan simpan data agar pegawai mendapatkan user untuk login ke sistem.</marquee>';
}
?>
<div class="card">
	<div class="card-header fw-bold" style="font-size:">
		<?=$title?>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-sm-12 col-md-3 mb-2">
				<span class="fw-bold">Username :</span>
			</div>
			<div class="col-sm-12 col-md-9 mb-2">
				<input type="text" name="username" id="username" value="<?=(isset($data))?$data->username:explode('@', $data_pegawai->email)[0]?>" class="form-control" required>
				<input type="hidden" name="id" value="<?=$id?>">
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-3 mb-2">
				<span class="fw-bold">Email :</span>
			</div>
			<div class="col-sm-12 col-md-9 mb-2">
				<input type="text" name="email" id="email" value="<?=(isset($data))?$data->email:((isset($data_pegawai))?$data_pegawai->email:'')?>" class="form-control" required>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-3 mb-2">
				<span class="fw-bold">Status :</span>
			</div>
			<div class="col-sm-12 col-md-9 mb-2">
				<label class="me-3"><input type="radio" value="1" name="status" <?php if($status==1){echo'checked';}?>>Aktif</label>
				<label class="me-3"><input type="radio" value="2" name="status" <?php if($status==2){echo'checked';}?>>Banned</label>
				<label class="me-3"><input type="radio" value="1" name="status" <?php if($status==0){echo'checked';}?>>Non Aktif</label>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-3">
				<span class="fw-bold">Password :</span>
			</div>
			<div class="col-sm-12 col-md-9">
				<div class="row">
					<div class="col-sm-12 col-md-6 mb-2">
						<b class="d-block">New</b>
						<div class="input-group">
							<input type="password" name="tpassword" id="tpassword" value="" class="form-control" autocomplete="off">
							<span class="input-group-text"><i id="iPassword" class="fa fa-eye" onclick="showHidePassword('#tpassword', '#iPassword')"></i></span>
						</div>
					</div>
					<div class="col-sm-12 col-md-6 mb-2">
						<b class="d-block">Retry</b>
						<div class="input-group">
							<input type="password" name="tpassword2" id="tpassword2" value="" class="form-control" autocomplete="off">
							<span class="input-group-text"><i id="iPassword2" class="fa fa-eye" onclick="showHidePassword('#tpassword2', '#iPassword2')"></i></span>
						</div>
						<span class="text-danger mtpassword"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<!-- <div class="row">
			<div class="col-sm-12 col-md-3"></div>
			<div class="col-sm-12 col-md-9"> -->
				<button class="btn btn-success" type="submit" class="btn_save_user">Simpan user untuk login pegawai</button>
				<!-- <button class="btn btn-secondary" type="reset" onclick="window.location.assign('<?=site_url('kepegawaian/profile?id='.$pegawai_id.'&tab=user')?>')">Cancel</button> -->
			<!-- </div>
		</div> -->
	</div>
</div>
<?=form_close()?>
<link href="<?=base_url('assets/')?>css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url('assets/')?>js/select2.full.min.js"></script>
<script type="text/javascript">

	$('#tpassword').on('input', function(){
		if(this.value!=''){
			$('#tpassword2').attr('required', true);
			$('.btn-simpan').prop('disabled', true);
		}else{
			$('#tpassword2').attr('required', false).val('');
			$('.btn-simpan').removeAttr('disabled', false);
		}
	});

	$('#tpassword2').on('input', function(){
		var p1 = $('#tpassword').val();
		var p2 = this.value;
		if(p1===p2){
			$('.btn-simpan').removeAttr('disabled', false);
			$('.mtpassword').html('');
		}else{
			$('.btn-simpan').attr('disabled', true);
			$('.mtpassword').html('password tidak sama... ');
		}
	});



	$('#form_user_pegawai').on('submit', function(e){
		$('.btn_save_user').prop('disabled', true).html('Wait Proccess...');
		let data = $(this);
		e.preventDefault();
		$.ajax({
			crossDomain: true,
	        crossOrigin: true,
	        dataType: 'json',
			type: "POST",
			data: data.serialize(),
			url: data.attr('action'),
			success: function(responseData, textStatus, jqXHR) {
				var dt = responseData
				$('input[name=<?=csrf_token()?>]').val(dt.csrf);
				if(dt.status==true)
				{
					load_halaman_in_profile('user', '<?=string_to($pegawai_id, 'encode')?>')
				}else{
					alert(dt.message.replace(/<p>|<\/p>/g, ""));
				}
				$('.btn_save_user').prop('disabled', false).html('Submit');
			}
		});
	});
</script>