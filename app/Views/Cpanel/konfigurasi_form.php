<?=form_open('', ['id'=>'referensi_form', 'class'=>'form']);
$id = 0;
if(!empty($data))
{
	$id = $data->id;
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
						<input type="text" name="name" class="form-control" value="<?=(isset($data))?$data->name:(!empty($_POST)?$_POST['name']:'')?>">
						<input type="hidden" name="id" value="<?=$id?>">
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Value :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<textarea name="value" class="form-control"><?=(isset($data))?$data->value:(!empty($_POST)?$_POST['value']:'')?></textarea>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Description :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<textarea name="description" class="form-control"><?=(isset($data))?$data->description:(!empty($_POST)?$_POST['description']:'')?></textarea>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Status :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<label class="me-3"><input type="radio" value="1" name="status" <?php if(((isset($data))?$data->status:1)==1){echo 'checked';}?>> Aktif</label>
						<label><input type="radio" value="0" name="status" <?php if(((isset($data))?$data->status:1)==0){echo 'checked';}?>> Non Aktif</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<button class="btn btn-success" type="submit" class="btn_login">Submit</button>
		<button class="btn btn-secondary" type="reset" onclick="window.location.assign('<?=session()->get('_ci_previous_url')?>')">Cancel</button>
	</div>
</div>
<?=form_close()?>
<script type="text/javascript">
	$('#referensi_form').on('submit', function(e)
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
					window.location.assign('<?=site_url('data/konfigurasi')?>');
				}else{
					alert(dt.message.replace(/<p>|<\/p>/g, ""));
				}
				$('.btn_login').prop('disabled', false).html('Submit');
				$('input[name=<?=csrf_token()?>]').val(dt.csrf);
			}
		});
	});
</script>