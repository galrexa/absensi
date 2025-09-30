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
						<span class="fw-bold">Ref :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<input type="text" name="ref" class="form-control" value="<?=(isset($data))?$data->ref:(!empty($_POST)?$_POST['ref']:'')?>">
						<input type="hidden" name="id" value="<?=$id?>">
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Code :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<input type="text" name="ref_code" class="form-control" value="<?=(isset($data))?$data->ref_code:(!empty($_POST)?$_POST['ref_code']:'')?>">
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Name :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<input type="text" name="ref_name" class="form-control" value="<?=(isset($data))?$data->ref_name:(!empty($_POST)?$_POST['ref_name']:'')?>">
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Description :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<textarea name="ref_description" class="form-control"><?=(isset($data))?$data->ref_description:(!empty($_POST)?$_POST['ref_description']:'')?></textarea>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Value :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<input type="text" name="ref_value" class="form-control" value="<?=(isset($data))?$data->ref_value:(!empty($_POST)?$_POST['ref_value']:'')?>">
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Status :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<label class="me-3"><input type="radio" value="1" name="ref_status" <?php if(((isset($data))?$data->ref_status:1)==1){echo 'checked';}?>> Aktif</label>
						<label><input type="radio" value="0" name="ref_status" <?php if(((isset($data))?$data->ref_status:1)==0){echo 'checked';}?>> Non Aktif</label>
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
					window.location.assign('<?=site_url('data/referensi')?>');
				}else{
					alert(dt.message.replace(/<p>|<\/p>/g, ""));
				}
				$('.btn_login').prop('disabled', false).html('Submit');
				$('input[name=<?=csrf_token()?>]').val(dt.csrf);
			}
		});
	});
</script>