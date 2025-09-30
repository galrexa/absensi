<?=form_open('', ['id'=>'form_perguruan_tinggi', 'class'=>'form']);
$id = 0;
if(!empty($data))
{
	$id = $data->id_pt;
}
?>
<div class="card">
	<div class="card-header fw-bold" style="font-size:16pt">
		<?=$title?>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col border-end">
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Name :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<input type="text" name="name" class="form-control" value="<?=(isset($data))?$data->nama_pt:(!empty($_POST)?$_POST['name']:'')?>">
						<input type="hidden" name="id" value="<?=$id?>">
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Alamat :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<textarea name="alamat" class="form-control"><?=(isset($data))?$data->alamat_pt:(!empty($_POST)?$_POST['alamat']:'')?></textarea>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Telp :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<input type="text" name="telp" class="form-control" value="<?=(isset($data))?$data->telp_pt:(!empty($_POST)?$_POST['telp']:'')?>">
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Kota :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<input type="text" name="kota" class="form-control" value="<?=(isset($data))?$data->kota_pt:(!empty($_POST)?$_POST['kota']:'')?>">
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Negara :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<input type="text" name="negara" class="form-control" value="<?=(isset($data))?$data->negara_pt:(!empty($_POST)?$_POST['negara']:'')?>">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<button class="btn btn-success" type="submit" class="btn_perguruan_tinggi">Submit</button>
		<button class="btn btn-secondary" type="reset" onclick="window.location.assign('<?=site_url('kepegawaian/perguruan_tinggi')?>')">Cancel</button>
	</div>
</div>
<?=form_close()?>
<script type="text/javascript">
	$('#form_perguruan_tinggi').on('submit', function(e)
	{
		$('.btn_perguruan_tinggi').prop('disabled', true).html('Wait Proccess...');
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
				if(dt.status==true)
				{
					window.location.assign('<?=site_url('kepegawaian/perguruan_tinggi')?>');
				}else{
					alert(dt.message.replace(/<p>|<\/p>/g, ""));
				}
				$('.btn_perguruan_tinggi').prop('disabled', false).html('Submit');
				$('input[name=<?=csrf_token()?>]').val(dt.csrf);
			}
		});
	});
</script>