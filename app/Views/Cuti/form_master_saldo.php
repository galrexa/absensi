<?php echo form_open_multipart('cuti/master/saldo/form?'.$_SERVER['QUERY_STRING'], ['class'=>'form', 'id'=>'form_master_saldo'])?>
	<div class="row">
		<div class="col-sm-12 col-md-12 mb-1">
			<div class="card">
				<div class="card-header fw-bold">
					<?=$title?>
				</div>
				<div class="card-body">
					<div class="row mb-1">
						<div class="col-sm-12 col-md-3">
							<b class="d-block">Tambah Untuk:</b>
						</div>
						<div class="col-sm-12 col-md-9">
							<div class="input-group fw-bold">
								<label class="me-3"><input type="radio" name="optional" class="optional" value="1" <?=(isset($data))?'':'checked'?>> Semua Pegawai</label>
								<label class="me-3"><input type="radio" name="optional" class="optional" value="2"> Unit Kerja</label>
								<label class="me-3"><input type="radio" name="optional" class="optional" value="3" <?=(isset($data))?'checked':''?>> Pegawai Perorangan atau lebih</label>
							</div>
							<input type="hidden" name="id" id="id" value="<?=$id?>">
						</div>
					</div>
					<div class="row mb-1" id="div_unit">
						<div class="col-sm-12 col-md-3">
							<b class="d-block">Unit Kerja:</b>
						</div>
						<div class="col-sm-12 col-md-9">
            				<select name="unit_kerja[]" id="unit_kerja" class="form-control" multiple></select>
						</div>
					</div>
					<div class="row mb-1" id="div_peg">
						<div class="col-sm-12 col-md-3">
							<b class="d-block">Pegawai:</b>
						</div>
						<div class="col-sm-12 col-md-9">
            				<select name="pegawai_id[]" id="pegawai_id" class="form-control" multiple></select>
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-sm-12 col-md-3">
							<b class="d-block">Tahun:</b>
						</div>
						<div class="col-sm-12 col-md-9">
							<input type="text" name="tahun" id="tahun" value="<?=(isset($data))?$data->tahun:date('Y')?>">
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-sm-12 col-md-3">
							<b class="d-block">Saldo Awal:</b>
						</div>
						<div class="col-sm-12 col-md-9">
            				<input type="text" name="saldo" id="saldo" value="<?=(isset($data))?$data->jatah:0?>" oninput="hitung_saldo()">
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-sm-12 col-md-3">
							<b class="d-block">Digunakan:</b>
						</div>
						<div class="col-sm-12 col-md-9">
            				<input type="text" name="digunakan" id="digunakan" value="<?=(isset($data))?$data->digunakan:0?>" oninput="hitung_saldo()">
						</div>
					</div>
					<div class="row mb-1">
						<div class="col-sm-12 col-md-3">
							<b class="d-block">Sisa saat ini:</b>
						</div>
						<div class="col-sm-12 col-md-9">
            				<input type="text" name="sisa_saat_ini" id="sisa_saat_ini" value="<?=(isset($data))?$data->sisa_saat_ini:0?>">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 col-md-12 mt-2">
			<button id="ID_BTN_SIMPAN" type="submit" class="btn btn-primary btn-simpan mr-2"><?php if($id==''){echo '<i class="fa fa-angle-right"></i> Lanjut';}else{echo '<i class="fa fa-save"></i> Simpan';}?></button>
			<button type="reset" class="btn btn-secondary btn-batal" data-bs-dismiss="modal" onclick="window.location.assign('<?=site_url('cuti/master/saldo')?>')"><i class="fa fa-times"></i> Batal</button>
		</div>
	</div>
<?=form_close()?>
<link href="<?=base_url('assets/')?>css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url('assets/')?>js/select2.full.min.js"></script>
<link href="<?=base_url('assets/vendors/bootstrap-datepicker/css/bootstrap-datepicker.min.css')?>" rel="stylesheet" />
<script src="<?=base_url('assets/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<script type="text/javascript">
	$(function(){
        select2_unit_kerja('#unit_kerja')
        select2_pegawai('#pegawai_id')
        check_optional()
	})

	function hitung_saldo(){
		var saldo = $('#saldo').val()
		var digunakan = $('#digunakan').val()
		var sisa = saldo - digunakan
		$('#sisa_saat_ini').val(sisa)
	}

	function check_optional(){
		var ck = $('input[name=optional]:checked').val()
		switch (ck){
			case '1':
				$('#div_unit').hide()
				$('#div_peg').hide()
				break
			case '2':
				$('#div_unit').show()
				$('#div_peg').hide()
				break
			case '3':
				$('#div_unit').hide()
				$('#div_peg').show()
				break
		}
	}

	$('.optional').on('change', function(){
		check_optional()
	})


	$('#form_master_saldo').on('submit', function(e){
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
					window.location.assign('<?=site_url('cuti/master/saldo')?>')
				}else{
					alert(dt.message)
					$('.btn-simpan').html('<i class="fa fa-save"></i> Simpan').removeAttr('disabled', false);
					$('.btn-batal').removeAttr('disabled', false);
				}
			}
		});
	});

	<?php if(!empty($data)){?>
		var txt_view = '<option value="<?=$data->pegawai_id?>" selected><?=$data->nama?></option>';
		$('#pegawai_id').html(txt_view).trigger('change').prop('disabled', true)
		$('#saldo').focus()
	<?php }?>
</script>