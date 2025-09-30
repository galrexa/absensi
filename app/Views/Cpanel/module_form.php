<?=form_open('', ['id'=>'module_form', 'class'=>'form']);
$idRolesAccess = [];
foreach ($dataRolesAccess as $k) {
	array_push($idRolesAccess, $k->id);
}
$id = 0;
$id_parent = 0;
if(!empty($data))
{
	$id_app = $data->id_app;
	$id = $data->id;
	$id_parent = $data->id_parent;
}
?>
<div class="card">
	<div class="card-header" style="font-size:16pt">
		<?=$title?>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-6">
				<div class="row mb-2">
					<div class="col-3">
						<span class="fw-bold">Name :</span>
					</div>
					<div class="col-9">
						<input type="text" name="name" class="form-control" value="<?=(isset($data))?$data->name:(!empty($_POST)?$_POST['name']:'')?>">
						<input type="hidden" name="id_app" value="<?=(isset($data))?$data->id_app:(!empty($_POST)?$_POST['id_app']:$_GET['app'])?>">
						<input type="hidden" name="id" value="<?=$id?>">
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-3">
						<span class="fw-bold">Parent :</span>
					</div>
					<div class="col-9">
						<select name="id_parent" class="form-control">
							<option value="0">No parent</option>
							<?php foreach ($dataListParent as $key) if($id<>$key->id){?>
								<option value="<?=$key->id?>" <?php if($key->id==$id_parent){echo 'selected';}?>><?=$key->name?></option>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-3">
						<span class="fw-bold">Status :</span>
					</div>
					<div class="col-9">
						<label><input type="radio" value="1" name="status" <?php if(((isset($data))?$data->status:1)==1){echo 'checked';}?>> Aktif</label>
						<label class="ms-3"><input type="radio" value="0" name="status" <?php if(((isset($data))?$data->status:1)==0){echo 'checked';}?>> Non Aktif</label>
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
			<div class="col-6 border-start">
				<span class="d-block fw-bold mb-2">Roles Access:</span>
				<?php if(!empty($dataListRoles)){?>
					<?php foreach ($dataListRoles as $k) {?>
						<label class="me-3"><input type="checkbox" value="<?=$k->id?>" name="id_role[]" class="id_role" <?php if(array_keys($idRolesAccess, $k->id)){echo 'checked';}?>> <?=$k->name?></label>
					<?php }?>
					<label class="d-block mt-2" title="check/uncheck all">{ <input type="checkbox" name="" onchange="if(this.checked===true){$('.id_role').prop('checked', true)}else{$('.id_role').prop('checked', false)}"> All }</label>
				<?php }?>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<button type="submit" class="btn_login"><i class="fa fa-save"></i> Submit</button>
		<button type="reset" onclick="window.location.assign('<?=site_url('app/form?id='.$id_app)?>')"><i class="fa fa-times-circle"></i> Cancel</button>
	</div>
</div>
<?=form_close()?>
<script type="text/javascript">
	$('#module_form').on('submit', function(e)
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
					window.location.assign('<?=site_url('app/form?id='.$id_app)?>');
				}else{
					alert(dt.message.replace(/<p>|<\/p>/g, ""));
				}
				$('.btn_login').prop('disabled', false).html('Submit');
				$('input[name=<?=csrf_token()?>]').val(dt.csrf);
			}
		});
	});
</script>