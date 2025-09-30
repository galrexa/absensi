<?php
if(!empty($data)){
	$tanggal = [];
	$keterangan = '';
	foreach ($data as $k) {
		array_push($tanggal, $k->tanggal);
		$keterangan = $k->keterangan;
	}
}else{
	$tanggal = [];
	$keterangan = '';
}
echo form_open_multipart('presensi/hari_libur/form?'.$_SERVER['QUERY_STRING'], ['class'=>'form', 'id'=>'form_hari_libur'])?>
	<div class="card">
		<div class="card-header fw-bold">
			<?=$title?>
		</div>
		<div class="card-body">
			<div class="row mb-1">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Tanggal*:</b>
				</div>
				<div class="col-sm-12 col-md-9">
					<input type="text" class="form-control mb-1" id="tanggal" name="tanggal" value="<?=implode(',', $tanggal)?>">
					<input type="hidden" class="form-control mb-1" id="tanggal_old" name="tanggal_old" value="<?=implode(',', $tanggal)?>">
				</div>
			</div>
			<div class="row mb-1">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Keterangan*:</b>
				</div>
				<div class="col-sm-12 col-md-9">
					<textarea class="form-control" id="keterangan" name="keterangan"><?=$keterangan?></textarea>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<button id="ID_BTN_SIMPAN" type="submit" class="btn btn-primary btn-simpan mr-2"><i class="fa fa-save"></i> Simpan</button>
			<button type="reset" class="btn btn-secondary btn-batal" data-bs-dismiss="modal" onclick="window.location.assign('<?=site_url('presensi/hari_libur')?>')"><i class="fa fa-times"></i> Batal</button>
		</div>
	</div>
<?=form_close()?>
<link href="<?=base_url('assets/vendors/bootstrap-datepicker/css/bootstrap-datepicker.min.css')?>" rel="stylesheet" />
<script src="<?=base_url('assets/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<script type="text/javascript">
	$('#tanggal').datepicker({
		// beforeShowDay: $.datepicker.noWeekends,
		todayHighlight: true,
		multidate: true,
		format: 'yyyy-mm-dd',
		orientation: 'bottom',
		// startDate: '-3d'
		// autoclose: true,
		// beforeShowDay: nationalDays
		// beforeShowDay: noWeekendsOrHolidays
	});

	$('#form_hari_libur').on('submit', function(e){
		$('.btn-batal').attr('disabled', true);
		$('.btn-simpan').attr('disable', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> harap tunggu sebentar')
	    e.preventDefault();
	    var form = $(this);
		$.ajax({
			crossDomain: true,
	        crossOrigin: true,
	        dataType: 'json',
			type: "POST",
			url: form.attr('action'),
			data: form.serialize(),
			success: function(responseData, textStatus, jqXHR) {
				var dt = responseData
				$('input[name=<?=csrf_token()?>]').val(dt.csrf)
				if(dt.status==true)
				{
					window.location.assign('<?=site_url('presensi/hari_libur')?>')
				}else{
					alert(dt.message)
					$('.btn-simpan').html('<i class="fa fa-save"></i> Simpan').removeAttr('disabled', false);
					$('.btn-batal').removeAttr('disabled', false);
				}
			}
		});
	});
</script>