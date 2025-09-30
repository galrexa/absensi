<?=form_open('', ['id'=>'role_form', 'class'=>'form']);
$id = 0;
if(!empty($data))
{
	$id = $data->id;
}
?>
<div class="card">
	<div class="card-header" style="font-size:16pt">
		<?=$title?>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-8">
				<div class="row mb-2">
					<div class="col-3">
						<span class="fw-bold">Name :</span>
					</div>
					<div class="col-9">
						<input type="text" name="name" class="form-control" value="<?=(isset($data))?$data->name:(!empty($_POST)?$_POST['name']:'')?>">
						<input type="hidden" name="id" value="<?=$id?>">
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-3">
						<span class="fw-bold">Status :</span>
					</div>
					<div class="col-9">
						<label><input type="radio" value="1" name="status" <?php if(((isset($data))?$data->status:1)==1){echo 'checked';}?>> Aktif</label>
						<label><input type="radio" value="0" name="status" <?php if(((isset($data))?$data->status:1)==0){echo 'checked';}?>> Non Aktif</label>
					</div>
				</div>
				<div class="row">
					<div class="col-3">
						<span class="fw-bold">Description :</span>
					</div>
					<div class="col-9">
						<textarea name="description" class="form-control"><?=(isset($data))?$data->description:(!empty($_POST)?$_POST['description']:'')?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<button type="submit" class="btn_login">Submit</button>
		<button type="reset" onclick="window.location.assign('<?=site_url('app')?>')">Cancel</button>
	</div>
</div>
<?=form_close()?>
<script type="text/javascript">
	$('#role_form').on('submit', function(e)
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
					window.location.assign('<?=site_url('app')?>');
				}else{
					alert(dt.message.replace(/<p>|<\/p>/g, ""));
				}
				$('.btn_login').prop('disabled', false).html('Submit');
				$('input[name=<?=csrf_token()?>]').val(dt.csrf);
			}
		});
	});
</script>