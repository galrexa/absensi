<?=form_open('', ['id'=>'form_gugus_tugas', 'class'=>'form']);
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
						<input type="text" name="name" class="form-control" value="<?=(isset($data))?$data->gugustugas:(!empty($_POST)?$_POST['name']:'')?>">
						<input type="hidden" name="id" value="<?=$id?>">
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 col-sm-12">
						<span class="fw-bold">Status :</span>
					</div>
					<div class="col-md-9 col-sm-12">
						<label><input type="radio" value="1" name="status" <?php if(((isset($data))?$data->status:1)==1){echo 'checked';}?>> Aktif</label>
						<label><input type="radio" value="0" name="status" <?php if(((isset($data))?$data->status:1)==0){echo 'checked';}?>> Non Aktif</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<button type="submit" class="btn_gugus_tugas">Submit</button>
		<button type="reset" onclick="window.location.assign('<?=site_url('kepegawaian/gugus_tugas')?>')">Cancel</button>
	</div>
</div>
<?=form_close()?>
<script type="text/javascript">
	$('#form_gugus_tugas').on('submit', function(e)
	{
		$('.btn_gugus_tugas').prop('disabled', true).html('Wait Proccess...');
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
					window.location.assign('<?=site_url('kepegawaian/gugus_tugas')?>');
				}else{
					alert(dt.message.replace(/<p>|<\/p>/g, ""));
				}
				$('.btn_gugus_tugas').prop('disabled', false).html('Submit');
				$('input[name=<?=csrf_token()?>]').val(dt.csrf);
			}
		});
	});
</script>