<?php
// echo json_encode($data);
// if($data['penerima'])
print_r($data['pos_id']);
$id = 0;
if(isset($data['id'])){
	$id = $data['hash'];
}
$query_link = '?'.$_SERVER['QUERY_STRING'];
echo '-'. return_update_status_read_surat($data['pos_id'], $datetime_now, session()->get('pegawai_id'));
echo form_open_multipart('persuratan/form/tindaklanjut?'.$query_link, ['class'=>'form', 'id'=>'form_tindaklanjut_surat'.$id])?>
	<div class="row">
		<div class="col-sm-12 col-md-12 mb-1">
			<?php if(empty($data) || !empty($data)){?>
				<div class="card">
					<div class="card-header fw-bold">
						<?=$title?>
					</div>
					<div class="card-body">
						<div class="row mb-2">
							<div class="col-sm-12 col-md-8 mb-2">
								<b class="d-block">Status Respon :</b>
								<div class="input-group">
									<?php
									$ref_code = ['1'=>'Disposisi', '2'=>'Tindak lanjut'];
									foreach($ref_code as $keyReff => $valueReff) if(!array_keys([0], $keyReff)){
										echo '<label class="me-3"><input type="radio" name="status" id="status'.$keyReff.'" class="status" value="'.$keyReff.'" ';
											if(1==$keyReff){echo'checked="checked"';}
										echo ' onclick="selected_status()"> '.ucfirst($valueReff).' </label>';	
									}
									?>
								</div>
							</div>
						</div>
						<div class="row mb-2 div-tindaklanjut">
							<div class="col-sm-12 col-md-12">
								<b class="d-block">Aksi tindak lanjut :</b>
								<?php 
								foreach(return_referensi_list('surat_status_tindaklanjut') as $k) {
									$fontSize = '';
									if(strlen($k->ref_name)>=30){
										$fontSize = 'font-size:13px;';
									}
									echo '<label style="display:block;'.$fontSize.'"><input type="radio" name="disposisi2[]" class="disosisi2'.$k->ref_code.'" value="'.$k->ref_name.'" ';
									// if(array_keys($detil_dispo,$k->ref_code)){echo'checked';}
									echo '> '.$k->ref_name.'</label>';
								}
								?>
							</div>
						</div>
						<div class="row mb-2 div-penerima">
							<div class="col-sm-12 col-md-12">
								<b class="d-block">Tambah Untuk:</b>
								<div class="input-group fw-bold">
									<label class="me-3 text-primary"><input type="radio" name="optional" class="optional" value="1" <?=(isset($data))?'':'checked'?>> Semua Pegawai</label>
									<label class="me-3 text-primary"><input type="radio" name="optional" class="optional" value="2"> Unit Kerja</label>
									<label class="me-3 text-primary"><input type="radio" name="optional" class="optional" value="3" <?=(isset($data))?'checked':''?>> Pegawai Perorangan atau lebih</label>
								</div>
								<input type="hidden" name="id" id="id" value="<?=$id?>">
							</div>
						</div>
						<div class="row mb-3 div-penerima" id="div_unit">
							<div class="col-sm-12 col-md-12">
	            				<select name="unit_kerja[]" id="unit_kerja" class="form-control" multiple></select>
							</div>
						</div>
						<div class="row mb-3 div-penerima" id="div_peg">
							<div class="col-sm-12 col-md-12">
	            				<select name="pegawai_id[]" id="pegawai_id" class="form-control" multiple></select>
							</div>
						</div>
						<div class="row mb-3 div-penerima">
							<div class="col-sm-12 col-md-12">
								<b class="d-block">Referensi Disposisi :</b>
								<?php 
								echo '<div class="row">';
									echo '<div class="col-md-4">';
									foreach(return_referensi_list('surat_ref_disposisi') as $k) if($k->ref_code <=5){
										$fontSize = '';
										if(strlen($k->ref_name)>=30){
											$fontSize = 'font-size:13px;';
										}
										echo '<label style="display:block;'.$fontSize.'"><input type="checkbox" name="disposisi[]" value="'.$k->ref_name.'" ';
										// if(array_keys($detil_dispo,$k->ref_code)){echo'checked';}
										echo '> '.$k->ref_name.'</label>';
									}
									echo '</div>';
									echo '<div class="col-md-4">';
									foreach(return_referensi_list('surat_ref_disposisi') as $k) if($k->ref_code >5 && $k->ref_code<=10){
										echo '<label style="display:block"><input type="checkbox" name="disposisi[]" value="'.$k->ref_name.'" ';
										// if(array_keys($detil_dispo,$k->ref_code)){echo'checked';}
										echo '> '.$k->ref_name.'</label>';
									}
									echo '</div>';
									echo '<div class="col-md-4">';
									foreach(return_referensi_list('surat_ref_disposisi') as $k) if($k->ref_code >10 && $k->ref_code<=15){
										echo '<label style="display:block"><input type="checkbox" name="disposisi[]" value="'.$k->ref_name.'" ';
										// if(array_keys($detil_dispo,$k->ref_code)){echo'checked';}
										echo '> '.$k->ref_name.'</label>';
									}
									echo '</div>';
								echo '</div>';
								?>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-sm-12 col-md-12">
								<b class="d-block">Keterangan/Disposisi/Catatan:</b>
								<textarea class="form-control summernote" id="catatan" name="catatan"></textarea>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 col-md-12">
								<b class="d-block">Lampiran:</b>
									<?=link_files_by_id(0, '#file_lampiran', 2, 1, 'list_file')?>
									<input type="file" name="lampiran" id="lampiran" class="form-control">
									<input type="hidden" name="file_lampiran" id="file_lampiran" value="">
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-sm-12 col-md-3"></div>
							<div class="col-sm-12 col-md-9">
								<button id="ID_BTN_SIMPAN" type="submit" class="btn btn-primary btn-simpan mr-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Teruskan Surat"><i class="fa fa-paper-plane"></i> Proses</button>
								<button type="reset" class="btn btn-secondary btn-batal" data-bs-dismiss="modal" onclick="window.location.assign('<?=site_url('persuratan/detail'.$query_link)?>')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Batal Meneruskan"><i class="fa fa-times"></i> Batal</button>
							</div>
						</div>
					</div>
				</div>
			<?php }?>
		</div>
	</div>
<?=form_close()?>
<link href="<?=base_url('assets/')?>css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url('assets/')?>js/select2.full.min.js"></script>
<link href="<?=base_url('assets/vendors/summernote/summernote-lite.css')?>" rel="stylesheet" />
<script src="<?=base_url('assets/vendors/summernote/summernote-lite.min.js')?>"></script>
<script type="text/javascript">
	$(function(){
        select2_unit_kerja('#unit_kerja')
        select2_pegawai('#pegawai_id')
        selected_status()
	})

    $('.summernote').summernote({
        height: 150,
        placeholder: 'Keterangan/Disposisi/Catatan untuk penerima...'
    });

	$('#form_tindaklanjut_surat<?=$id?>').on('submit', function(e){
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
					window.location.assign('<?=site_url('persuratan/detail?'.$query_link)?>')
				}else{
					alert(dt.message)
					$('.btn-simpan').html('<i class="fa fa-paper-plane"></i> Teruskan Surat').removeAttr('disabled', false);
					$('.btn-batal').removeAttr('disabled', false);
				}
			}
		});
	});


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

	function selected_status()
	{
		let cb = $('input[name=status]:checked').val();
		switch(cb) {
		  case '2':
		  case '3':
		  case '4':
		    $('#penerima').prop('required', false)
		    $('.div-penerima').hide()
			$('#div_peg').hide()
			$('input[name=optional]').prop('checked', false)
		    $('.div-tindaklanjut').show()
		    break;
		  default:
		    $('#penerima').prop('required', true)
		    $('.div-penerima').show()
			$('#div_peg').show()
			$('input[name=optional][value=3]').prop('checked', true)
		    $('.div-tindaklanjut').hide()
			break
		}
        check_optional()
	}

	$('#lampiran').on('change', function(){
		// load_modal_for_message('Upload file')
		file_upload_form('#lampiran', 'lampiran_cuti', '#file_lampiran', '#list_file', '2')
	});
</script>