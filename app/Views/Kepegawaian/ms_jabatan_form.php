<?=form_open('', ['id'=>'jabatan_form', 'class'=>'form']);
$id = 0;
if(!empty($data))
{
	$id = $data->jabatan_id;
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
						<input type="text" name="name" class="form-control" value="<?=(isset($data))?$data->jabatan_name:(!empty($_POST)?$_POST['name']:'')?>">
						<input type="hidden" name="id" value="<?=$id?>">
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Status :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<label class="me-3"><input type="radio" value="1" name="status" <?php if(((isset($data))?$data->jabatan_status:1)==1){echo 'checked';}?>> Aktif</label>
						<label><input type="radio" value="0" name="status" <?php if(((isset($data))?$data->jabatan_status:1)==0){echo 'checked';}?>> Non Aktif</label>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Description :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<textarea name="description" class="form-control"><?=(isset($data))?$data->jabatan_description:(!empty($_POST)?$_POST['description']:'')?></textarea>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Slot :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<input type="text" name="slot" id="slot" class="form-control" value="<?=(isset($data))?$data->jabatan_slot:(!empty($_POST)?$_POST['slot']:'')?>">
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Terpakai :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<input type="text" name="slot_terpakai" id="slot_terpakai" class="form-control" value="<?=(isset($data))?$data->jabatan_slot_terpakai:(!empty($_POST)?$_POST['slot_terpakai']:'')?>" onchange="$('#slot_kosong').val($('#slot').val()-$('#slot_terpakai').val())">
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Sisa :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<input type="text" name="slot_kosong" id="slot_kosong" class="form-control" value="<?=(isset($data))?$data->jabatan_slot_kosong:(!empty($_POST)?$_POST['slot_kosong']:'')?>">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<button class="btn btn-success" type="submit" class="btn_login">Submit</button>
		<button class="btn btn-secondary" type="reset" onclick="window.location.assign('<?=site_url('kepegawaian/jabatan')?>')">Cancel</button>
	</div>
</div>
<?=form_close()?>
<script type="text/javascript">
	$('#jabatan_form').on('submit', function(e)
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
					window.location.assign('<?=site_url('kepegawaian/jabatan')?>');
				}else{
					alert(dt.message.replace(/<p>|<\/p>/g, ""));
				}
				$('.btn_login').prop('disabled', false).html('Submit');
				$('input[name=<?=csrf_token()?>]').val(dt.csrf);
			}
		});
	});
</script>