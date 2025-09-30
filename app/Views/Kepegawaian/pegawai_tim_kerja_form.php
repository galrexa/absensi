<?=form_open('', ['id'=>'form_sk', 'class'=>'form']);
$id = 0;
$status = 1;
if(!empty($data))
{
	$id = $data->id_sk_tim;
	$status = $data->status_sk;
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
						<span class="fw-bold">Nomor :</span>
					</div>
					<div class="col-sm-12 col-md-9">
						<input type="text" name="nomor_sk" class="form-control form-control-sm" value="<?=(isset($data))?$data->nomor_sk:(!empty($_POST)?$_POST['nomor_sk']:'')?>">
						<input type="hidden" name="id" value="<?=$id?>">
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-sm-12 col-md-3">
						<span class="fw-bold">Tanggal :</span>
					</div>
					<div class="col-sm-12 col-md-9">
						<div class="input-group">
							<div class="input-group-text"><i class="fa fa-calendar"></i></div>
							<input type="text" name="tgl_sk" id="tgl_sk" class="form-control form-control-sm" value="<?=(isset($data))?$data->tgl_sk:''?>">
						</div>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-sm-12 col-md-3">
						<span class="fw-bold">Periode :</span>
					</div>
					<div class="col-sm-12 col-md-9">
						<div class="input-group input-group-sm">
							<div class="input-group-text"><i class="fa fa-calendar"></i></div>
							<input type="text" name="tgl_awal" id="tgl_awal" class="form-control form-control-sm" value="<?=(isset($data))?$data->tgl_awal:''?>">
							<div class="input-group-text">s.d</div>
							<input type="text" name="tgl_akhir" id="tgl_akhir" class="form-control form-control-sm" value="<?=(isset($data))?$data->tgl_akhir:''?>">
							<div class="input-group-text"><i class="fa fa-calendar"></i></div>
						</div>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-sm-12 col-md-3">
						<span class="fw-bold">Status :</span>
					</div>
					<div class="col-sm-12 col-md-9">
						<label class="me-2"><input type="radio" value="1" name="status" <?php if($status==1){echo'checked';}?>>Aktif</label>
						<label class="me-2"><input type="radio" value="2" name="status" <?php if($status==2){echo'checked';}?>>Berakhir</label>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-5">
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<span class="fw-bold">Keterangan/Catatan :</span>
						<textarea name="keterangan" class="form-control"><?=(isset($data))?$data->keterangan:(!empty($_POST)?$_POST['keterangan']:'')?></textarea>
						<div class="input-group mt-2">
							<div class="input-group-text">File SK/Surat:</div>
							<input type="file" name="dokumen" id="dokumen" class="form-control form-control-sm">
						</div>
						<input type="hidden" name="dokumen_id" id="dokumen_id" value="<?=(isset($data))?$data->file:''?>">
						<?=link_files_by_id((isset($data))?$data->file:0, '#dokumen_id', 2, $status)?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<button type="submit" class="btn_login">Submit</button>
		<button type="reset" onclick="window.location.assign('<?php if($id>0){echo site_url('kepegawaian/tim/detail?id='.$id);}else{echo site_url('kepegawaian/tim');}?>')">Cancel</button>
	</div>
</div>
<?=form_close()?>
<script type="text/javascript">
	$('#dokumen').on('change', function(){
		file_upload_form('#dokumen', 'sk_tim_', '#dokumen_id', '#list_file', '2')
	})

	$('#form_sk').on('submit', function(e)
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
			success: function(responseData, textStatus, jqXHR) {
				var dt = responseData
				if(dt.status==true)
				{
					window.location.assign('<?=site_url('kepegawaian/tim/detail?id='.$id)?>');
				}else{
					alert(dt.message.replace(/<p>|<\/p>/g, ""));
				}
				$('.btn_login').prop('disabled', false).html('Submit');
				$('input[name=<?=csrf_token()?>]').val(dt.csrf);
			}
		});
	});
</script>