<?php
if(!empty($data)){
	$id 			= $data['id'];
	$pegawai_id 	= $data['pegawai_id'];
	$jabatan_id 	= $data['jabatan_id'];
	$unit_kerja_id 		= $data['unit_kerja_id'];
	$jenis_cuti 	= $data['jenis_cuti'];
	$nomor_surat 	= $data['nomor_surat'];
	$keterangan 	= $data['keterangan'];
	$alamat 		= $data['alamat'];
	$telpon 		= $data['telpon'];
	$status 		= $data['status'];
	$tanggal 		= implode(',', $data['tanggal']);
	$jumlah 		= $data['jumlah'];
	$lamanya		= [];
	foreach ($data['tanggal'] as $k=>$v) {
		array_push($lamanya, $v);
	}
	$pejabat_berwenang = $data['pegawai_id_pimpinan'];
	$pejabat_berwenang_verify = '';
	if(!empty($data_verify))
		$pejabat_berwenang_verify = $data_verify->pegawai_id;
	$lampiran 		= $data['lampiran'];
}else{
	$id = $id;
	$pegawai_id = $data_pegawai->pegawai_id;
	$jabatan_id = $data_pegawai->jabatan_id;
	$unit_kerja_id = $data_pegawai->unit_kerja_id;
	$jenis_cuti = '';
	$nomor_surat = '';
	$keterangan = '';
	$alamat = '';
	$telpon = '';
	$status = '';
	$tanggal = [];
	$jumlah = '';
	$lamanya = [];
	$pejabat_berwenang = '';
	$pejabat_berwenang_verify = '';
	$lampiran = '';
}
$link = ($link)?:'riwayat';
$query_link = '';
if(array_keys(['detail'],$link)){
	$query_link = '?id='.$data['hash'];
}
echo form_open_multipart('cuti/form?'.$_SERVER['QUERY_STRING'], ['class'=>'form', 'id'=>'form_create_cuti'.$id])?>
	<div class="row">
		<div class="col-sm-12 col-md-6 mb-1">
			<?php if(empty($data) || !empty($data)){?>
				<div class="card">
					<div class="card-header fw-bold">
						Formulir Permintaan Cuti
					</div>
					<div class="card-body">
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Nama:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<?=$data_pegawai->nama?>
								<input type="hidden" name="id" id="id" value="<?=$id?>">
								<input type="hidden" name="pegawai_id" id="pegawai_id" value="<?=$pegawai_id?>">
							</div>
						</div>
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Jabatan:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<?=$data_pegawai->jabatan?>
							</div>
						</div>
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Kedeputian:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<?=$data_pegawai->unit_kerja_alt?>
								(<?=$data_pegawai->unit_kerja?>)
							</div>
						</div>
						<hr>
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Jenis Cuti*:</b>
							</div>
							<div class="col-sm-12 col-md-6">
								<select name="jenis_cuti" id="jenis_cuti" class="form-control" style="width:100%!important;" required onclick="" onchange="check_jenis_cuti()">
									<option value="">Pilih</option>
									<?php foreach(return_referensi_list('cuti') as $k){?>
										<option value="<?=$k->ref_code?>"
											<?php if($jenis_cuti==$k->ref_code){echo 'selected';}?>
											><?=$k->ref_name .' - '. $k->ref_description?></option>
									<?php }?>
								</select>
							</div>
						</div>
						<?php if(!empty($data)){?>
							<div class="row mb-1">
								<div class="col-sm-12 col-md-3">
									<b class="d-block">Alasan Cuti*:</b>
								</div>
								<div class="col-sm-12 col-md-9">
									<div class="border p-2 alasanCheck">
										<label class="d-block"><input type="radio" name="keterangan2" <?php if($keterangan=='Keadaan Darurat (Sakit)'){echo 'checked';}?> value="Keadaan Darurat (Sakit)"> Keadaan Darurat (Sakit)</label>
										<label class="d-block"><input type="radio" name="keterangan2" <?php if($keterangan=='Pernikahan'){echo 'checked';}?> value="Pernikahan"> Pernikahan</label>
										<label class="d-block"><input type="radio" name="keterangan2" <?php if($keterangan=='Kelahiran'){echo 'checked';}?> value="Kelahiran"> Kelahiran</label>
										<label class="d-block"><input type="radio" name="keterangan2" <?php if($keterangan=='Kematian'){echo 'checked';}?> value="Kematian"> Kematian</label>
										<label class="d-block"><input type="radio" name="keterangan2" <?php if($keterangan=='Alasan lain'){echo 'checked';}?> value="Alasan lain"> Alasan lain</label>
									</div>
									<textarea class="form-control alasanField" id="keterangan" name="keterangan"><?=$keterangan?></textarea>
								</div>
							</div>
							<div class="row mb-1">
								<div class="col-sm-12 col-md-3">
									<b class="d-block">Alamat Selama Cuti*:</b>
								</div>
								<div class="col-sm-12 col-md-9">
									<textarea class="form-control" id="alamat" name="alamat"><?=$alamat?></textarea>
								</div>
							</div>
							<div class="row mb-1">
								<div class="col-sm-12 col-md-3">
									<b class="d-block">Telepon*:</b>
								</div>
								<div class="col-sm-12 col-md-9">
									<input class="form-control" id="telpon" name="telpon" value="<?=$telpon?>">
								</div>
							</div>
							<div class="row mb-1 div_lampirannya">
								<?php ?>
								<div class="col-sm-12 col-md-3">
									<b class="d-block">Lampiran:</b>
								</div>
								<div class="col-sm-12 col-md-9">
									<div class="card">
										<div class="card-header">
											<input type="file" name="lampiran" id="lampiran" class="form-control">
										</div>
										<div class="card-body">
											<input type="hidden" name="file_lampiran" id="file_lampiran" value="<?=$lampiran?>">
											<?=link_files_by_id($lampiran, '#file_lampiran', 2, 1, 'list_file')?>
										</div>
									</div>
								</div>
								<?php  ?>
							</div>
						<?php }?>
					</div>
				</div>
			<?php }?>
		</div>
		<div class="col-sm-12 col-md-6 mb-1">
			<?php if(!empty($data)){?>
				<div class="card mb-1">
					<div class="card-header">
						Tanggal Cuti
					</div>
					<div class="card-body">
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Cuti Tahunan:</b>
							</div>
							<div class="col-sm-12 col-md-9 fw-bold">
								Sisa <u class="fs-6"><?=return_sisa_cuti_pegawai($data_pegawai->pegawai_id)?></u> Hari
							</div>
						</div>
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Tanggal*:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<input type="text" class="form-control mb-1" id="lamanya" name="lamanya" value="<?=implode(',', $lamanya)?>">
								<span class="d-block">Jumlah hari yang diambil: <i class="jml_hari"><?=$data['jumlah']?></i> Hari, </span>
							</div>
						</div>
						<!-- <div id="tanggal_view"></div> -->
					</div>
				</div>
				<div class="card">
					<div class="card-header fw-bold">
						Pejabat Pemberi Cuti
					</div>
					<div class="card-body">
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Nama*:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<select name="pejabat_berwenang" id="pejabat_berwenang" class="select2_select_option form-control" required style="width:100%!important;">
									<option value="">Pilih</option>
									<optgroup label="Kepala dan Pimpinan Unit Kerja">
										<?php foreach($list_pejabat as $k) {?>
												<option value="<?=$k->pegawai_id?>"
													<?php if($k->pegawai_id==$pejabat_berwenang || (($k->jabatan_id>1 && $k->unit_kerja_id==$unit_kerja_id) || ($k->jabatan_id==1 && array_keys([2,3,4,5], $unit_kerja_id)))){echo 'selected';}?>
													>
													<?=$k->nama?> (<?=$k->jabatan_name?>)
												</option>
										<?php }?>
									</optgroup>
								</select> 
							</div>
						</div>
					</div>
				</div>
			<?php }?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 col-md-12 mt-2">
			<button id="ID_BTN_SIMPAN" type="submit" class="btn btn-primary btn-simpan mr-2"><?php if($id==''){echo '<i class="fa fa-angle-right"></i> Lanjut';}else{echo '<i class="fa fa-save"></i> Simpan';}?></button>
			<button type="reset" class="btn btn-secondary btn-batal" data-bs-dismiss="modal" onclick="window.location.assign('<?=site_url('cuti/'.$link.$query_link)?>')"><i class="fa fa-times"></i> Batal</button>
		</div>
	</div>
<?=form_close()?>
<link href="<?=base_url('assets/')?>css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url('assets/')?>js/select2.full.min.js"></script>
<link href="<?=base_url('assets/vendors/bootstrap-datepicker/css/bootstrap-datepicker.min.css')?>" rel="stylesheet" />
<script src="<?=base_url('assets/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<script type="text/javascript">

	$('.select2_select_option').select2({
		placeholder: 'Pilih Data',
		<?php if(empty($data)){?>
        	dropdownParent: $('#modalXl'),
    	<?php }?>
	});

	$('#lamanya').datepicker({
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
	// $('#tanggal_view').datepicker({
	// 	// beforeShowDay: $.datepicker.noWeekends,
	// 	todayHighlight: true,
	// 	multidate: true,
	// 	format: 'yyyy-mm-dd',
	// 	// datesDisabled: ['2025-07-16','2025-07-21'],
	// 	setDaysOfWeekHighlighted: ['2025-07-17', '2025-07-20'],
	// 	// daysOfWeekHighlighted: [2025-07-17,2025-07-20],
	// 	// input: '2025-07-21',
	// 	// startDate: '-3d'
	// 	// autoclose: true,
	// 	// beforeShowDay: nationalDays
	// 	// beforeShowDay: noWeekendsOrHolidays
	// });
	// $('#tanggal_view').datepicker('update', ['2025-07-17', '2025-07-20']);
	// function noWeekendsOrHolidays(date) {
	//     var noWeekend = $.datepicker.noWeekends(date);
	//     if (noWeekend[0]) {
	//         return nationalDays(date);
	//     } else {
	//         return noWeekend;
	//     }
	// }
	// function nationalDays(date) {
	// 	var natDays = [
	// 		[1, 26, 'au'], [2, 6, 'nz'], [3, 17, 'ie'],
	// 		[4, 27, 'za'], [5, 25, 'ar'], [6, 6, 'se'],
	// 		[7, 4, 'us'], [8, 17, 'id'], [9, 7, 'br'],
	// 		[10, 1, 'cn'], [11, 22, 'lb'], [12, 12, 'ke']
	// 	];
	//     for (i = 0; i < natDays.length; i++) {
	//       if (date.getMonth() == natDays[i][0] - 1
	//           && date.getDate() == natDays[i][1]) {
	//         return [false, natDays[i][2] + '_day'];
	//       }
	//     }
	//   return [true, ''];
	// }
	$('#lamanya').on('change', function(){
		counting_dates()
	})
	function counting_dates()
	{
		// $("#label_dates").tagit("removeAll");
		var ldate = ''
		var adate = []
		let rs = 0;
		var vDate = $('#lamanya').val()
		if(vDate)
		{
			var xvDate = vDate.split(',')
			rs = xvDate.length
			for (var i = 0; i < xvDate.length; i++) {
				// ldate += '<li>'+xvDate[i]+'<li>'
				ldate += '<li class="border border-info rounded p-2">'+xvDate[i]+'<li>'
				adate.push(xvDate[i])
				// $("#label_dates").tagit("createTag", xvDate[i]);
			}
			// $('#tanggal_view').datepicker({
				// multidate: true,
				// format: 'yyyy-mm-dd',
				// valueDate: '2025-07-22',
			// })
		}
		// $('#label_dates').html(ldate)
		// $("#label_dates").tagit("createTag", adate);
		console.log('jml dates:', rs)
		$('.jml_hari').html(rs)
		return rs
	}
	$(function(){
		counting_dates()
	})


	$('#form_create_cuti<?=$id?>').on('submit', function(e){
		$('.btn-batal').attr('disabled', true);
		$('.btn-simpan').attr('disable', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> harap tunggu...')
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
					<?php if(array_keys(['',0],$id)){?>
						window.location.assign('<?=site_url('cuti/form?id=')?>'+dt.ID)
					<?php }else{?>
						window.location.assign('<?=site_url('cuti/detail?id=')?>'+dt.ID)
					<?php }?>
				}else{
					alert(dt.message)
					$('.btn-simpan').html('<i class="fa fa-save"></i> Simpan').removeAttr('disabled', false);
					$('.btn-batal').removeAttr('disabled', false);
				}
			}
		});
	});

	function check_jenis_cuti()
	{
		var jc = $('#jenis_cuti').val()
		if(jc==='2' || jc==='3' || jc==='4'){
			$('.div_lampirannya').show()
			$('#file_lampiran').prop('required', true)
		}else{
			$('.div_lampirannya').hide()
			$('#file_lampiran').prop('required', false).val('')
		}
		// khusus cap
		if(jc==='4'){
			$('.alasanCheck').show().val('<?=$keterangan?>')
			$('.alasanField').hide().val('')
		}else{
			$('.alasanCheck').hide().prop('checked', false)
			$('.alasanField').show().val('<?=$keterangan?>')
		}
	}

	$(function(){
		check_jenis_cuti()
	})

	$('#lampiran').on('change', function(){
		// load_modal_for_message('Upload file')
		file_upload_form('#lampiran', 'lampiran_cuti', '#file_lampiran', '#list_file', '2')
	});
</script>