<?php

$id = (!empty($data))?string_to($data['id'],'encode'):0;
echo form_open_multipart('persuratan/compose?'.$_SERVER['QUERY_STRING'], ['class'=>'form', 'id'=>'form_compose_naskah'.$id]);
?>
<?php if(empty($data) || !empty($data)){?>
	<div class="card mb-2">
		<div class="card-body">
			<div class="row mb-2">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Tujuan Pembuatan* :</b>
				</div>
				<div class="col-sm-12 col-md-4">
					<select name="draf_for" id="draf_for" class="form-control" style="width:100%!important;" required onchange="check_draf_for()"></select>
					<input type="hidden" name="id" id="id" value="<?=$id?>">
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Jenis Naskah* :</b>
				</div>
				<div class="col-sm-12 col-md-4">
					<select name="jenis" id="jenis" class="select2_select_option form-control" style="width:100%!important;" required <?php if(!empty($data)){echo 'disabled';}?>></select>
					<?php if(!empty($data)){?>
						<input type="hidden" name="jenis" value="<?=$data['jenis']?>">
						<input type="hidden" name="draf_type" value="<?=$data['draf_type']?>">
					<?php }?>
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Jenis Drafting* :</b>
				</div>
				<div class="col-sm-12 col-md-4">
					<select name="draf_type" id="draf_type" class="form-control" style="width:100%!important;" required <?php if(!empty($data)){echo 'disabled';}?>></select>
				</div>
			</div>
			<div class="row mb-2 div_ref_id">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Referensi Naskah* :</b>
				</div>
				<div class="col-sm-12 col-md-9">
					<select name="draf_ref_id" id="draf_ref_id" class="select2_select_option form-control" style="width:100%!important;"></select>
				</div>
			</div>
			<script type="text/javascript">
				function check_draf_for()
				{
					var ck = $('#draf_for').val()
					if(ck==2)
					{
						$('.div_ref_id').show()
						$('#ref_id').prop('required', true)
					}else{
						$('.div_ref_id').hide()
						$('#ref_id').prop('required', false).val('')
					}
				}

				$(function(){
					check_draf_for()
				})
			</script>
		</div>
	</div>
<?php }?>
<?php if(!empty($data)){?>
	<div class="card mb-2">
		<div class="card-header fw-bold" style="font-size:16pt">
			Pengirim dan Penandatangan
		</div>
		<div class="card-body">
			<div class="row mb-2">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Pengirim* :</b>
				</div>
				<div class="col-sm-12 col-md-9">
					<select name="pengirim_id" id="pengirim_id" class="select2_select_option form-control" required style="width:100%!important;"></select> 
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-md-3 mb-2">
					<b class="d-block">Penandatangan* :</b>
				</div>
				<div class="col-sm-12 col-md-3 mb-2">
					<select name="signer_opt" id="signer_opt" class="form-control" required style="width:100%!important;" onchange="check_penandatangan()">
						<option value="" <?php if($data['signer_opt']==0){echo 'selected';}?>>Pilih</option>
						<option value="1" <?php if($data['signer_opt']==1){echo 'selected';}?>>Oleh yang Bersangkutan</option>
						<option value="2" <?php if($data['signer_opt']==2){echo 'selected';}?>>Oleh Orang Lain</option>
					</select>
				</div>
				<div class="col-sm-12 col-md-6 mb-2 input_signer">
					<select name="penandatangan" id="penandatangan" class="select2_select_option input_signer form-control" style="width:100%!important;"></select>
				</div>
			</div>
			<div class="row input_signer">
				<div class="col-sm-12 col-md-3 mb-2"></div>
				<div class="col-sm-12 col-md-2 mb-2">
					<select name="signer_alt" id="signer_alt" class="select2_select_option input_signer form-control" style="width:100%!important;">
						<option value="">Pilih</option>
						<?php foreach(return_referensi_list('surat_person_sebagai') as $k){?>
							<option value="<?=$k->ref_code?>" <?php if($data['signer_alt']==$k->ref_code){echo 'selected';}?>><?=$k->ref_name?></option>
						<?php }?>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-md-3"></div>
				<div class="col-sm-12 col-md-9">
					<label class="fw-bold" style="font-style: italic;"><input type="checkbox" name="penandatangan_display" value="1" <?php if($data['signer_show']==1){echo 'checked';}?>> Tampilkan Gelar Penandatangan</label>
				</div>
			</div>
		</div>
	</div>
	<div class="card mb-2">
		<div class="card-header fw-bold" style="font-size:16pt">
			Penerima Surat
		</div>
		<div class="card-body">
			<div class="row mb-2">
				<div class="col-sm-12 col-md-6">
					<b class="d-block">Penerima Internal<?php if(array_keys([2], $data['jenis'])){echo '*';}?> :</b>
					<select name="penerima[]" id="penerima" class="form-control" multiple <?php if(array_keys([2], $data['jenis'])){echo '';}?>></select>
				</div>
				<div class="col-sm-12 col-md-6">
					<b class="d-block">Penerima Eksternal<?php if(array_keys([1,3,8,9,11], $data['jenis'])){echo '*';}?> :</b>
					<div class="input-group">
						<select name="penerima_ext[]" id="penerima_ext" class="form-control" multiple style="width:90%!important;" <?php if(array_keys([1,3,8,9,11], $data['jenis'])){echo '';}?>></select>
						<div class="input-group-text">
							<a href="#" onclick="open_form_kl('penerima')" title="Tambah Pejabat/KL baru"><i class="fa fa-plus"></i></a>
						</div>
					</div>
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-12 col-md-12 border-info" id="div_penerima_internal"></div>
				<script type="text/javascript">
					$(function(){
						get_penerima_st()
					})
					function get_penerima_st()
					{
						$.get('<?=site_url('surat/st_penerima/?id='.$id)?>&uri2=create', function(rs){
							$('#div_penerima_internal').html(rs)
						})
					}
				</script>
			</div>
			<div class="row mb-2">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Tampilan* :</b>
				</div>
				<div class="col-sm-12 col-md-4">
					<div class="row">
						<div class="col-sm-12 col-md-12">
							<select name="penerima_show" id="penerima_show" class="select2_select_option form-control" style="width:100%!important;" required></select>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="row mb-2">
				<div class="col-sm-12 col-md-6">
					<b class="d-block">Tembusan Internal<?php if(array_keys([2], $data['jenis'])){echo '*';}?> :</b>
					<select name="tembusan[]" id="tembusan" class="form-control" multiple <?php if(array_keys([2], $data['jenis'])){echo '';}?>></select>
				</div>
				<div class="col-sm-12 col-md-6">
					<b class="d-block">Tembusan Eksternal<?php if(array_keys([1,3,8,9,11], $data['jenis'])){echo '*';}?> :</b>
					<div class="input-group">
						<select name="tembusan_ext[]" id="tembusan_ext" class="form-control" multiple style="width:90%!important;" <?php if(array_keys([1,3,8,9,11], $data['jenis'])){echo '';}?>></select>
						<div class="input-group-text">
							<a href="#" onclick="open_form_kl('penerima')" title="Tambah Pejabat/KL baru"><i class="fa fa-plus"></i></a>
						</div>
					</div>
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-12 col-md-12 border-info" id="div_tembusan_internal"></div>
				<script type="text/javascript">
					$(function(){
						get_tembusan_st()
					})
					function get_tembusan_st()
					{
						$.get('<?=site_url('surat/st_tembusan/?id='.$id)?>', function(rs){
							$('#div_tembusan_internal').html(rs)
						})
					}
				</script>
			</div>
			<div class="row mb-2">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Tampilan* :</b>
				</div>
				<div class="col-sm-12 col-md-4">
					<div class="row">
						<div class="col-sm-12 col-md-12">
							<select name="tembusan_show" id="tembusan_show" class="select2_select_option form-control" style="width:100%!important;"></select>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="row mb-2">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Alamat Penerima* :</b>
				</div>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="penerima_alamat" value="<?=$data['penerima_alamat']?>" class="form-control">
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-12 col-md-3"></div>
				<div class="col-sm-12 col-md-9">
					<label class="fw-bold" style="font-style: italic;"><input type="checkbox" name="penerima_pada_lampiran" value="1" <?php if($data['penerima_pada_lampiran']==1){echo 'checked';}?> onchange="check_penerima();"> Penerima ditempatkan pada Lampiran</label>
					<!-- let jumlah_lampiran=$('#jumlah_lampiran').val(); if(this.checked){$('#jumlah_lampiran').val(jumlah_lampiran+1)}else{$('#jumlah_lampiran').val(jumlah_lampiran-1)} -->
				</div>
			</div>
			<div class="row mb-2 penerima_keterangan_lampiran">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Keterangan* :</b>
				</div>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="penerima_keterangan_lampiran" id="penerima_keterangan_lampiran" value="<?=$data['penerima_keterangan_lampiran']?>" class="form-control">
				</div>
			</div>
		</div>
	</div>
	<div class="card mb-2">
		<div class="card-header fw-bold" style="font-size:16pt">
			Informasi <?=$data['jenis_name']?>
		</div>
		<div class="card-body">
			<div class="row mb-2">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Tanggal* :</b>
				</div>
				<div class="col-sm-12 col-md-9">
	                <div class="input-group" align="right" style="width:150px">
	                	<input type="text" name="tanggal" id="tanggal" class="form-control datepicker" required value="<?=$data['tanggal']?>" autocomplete="off">
	                    <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
	                </div>
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Hal* :</b>
				</div>
				<div class="col-sm-12 col-md-9">
					<textarea name="hal" id="hal" class="form-control" required><?=$data['hal']?></textarea>
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Derajat Pengamanan* :</b>
				</div>
				<div class="col-sm-12 col-md-4">
					<select name="sifat" id="sifat" class="select2_select_option form-control" required style="width:100%!important;"></select>
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Derajat Penyampaian* :</b>
				</div>
				<div class="col-sm-12 col-md-4">
					<select name="urgensi" id="urgensi" class="select2_select_option form-control" required style="width:100%!important;"></select>
				</div>
			</div>
			<div class="row mb-3 div_draf_type_2">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Isi* :</b>
				</div>
				<div class="col-sm-12 col-md-9">
					<!-- <textarea name="isi_memo" id="isi_memo" class="form-control summernote_text" required><?=$data['catatan']?></textarea> -->
					<div class="centered border border-light p-2">
							<div class="row" >
									<div class="document-editor__toolbar" style="padding:0"></div>
							</div>
							<div class="row row-editor">
									<div class="editor-container">
											<textarea name="data['catatan']" id="data['catatan']" class="form-control" style="display:none"><?=$data['catatan']?></textarea>
											<div name="isi_doc" id="isi_doc" class="form-control" required style="min-height: 300px;"><?=$data['catatan']?></div>
									</div>
							</div>
					</div>
				</div>
			</div>
			<div class="row mb-3 div_draf_type_3">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Upload Surat/Naskah* :</b>
				</div>
				<div class="col-sm-12 col-md-9">
					<div class="card">
						<div class="card-header">
							<input type="file" name="file_memo" id="file_memo" class="form-control">
							<input type="hidden" name="file" id="file" value="<?=$data['path']?>">
						</div>
						<div id="listfile" class="card-body">
							<?=link_files_by_id($data['path'], '#file', 1, 1, 'list_file')?>
						</div>
					</div>
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Lampiran :</b>
				</div>
				<div class="col-sm-12 col-md-9">
					<div class="card">
						<div class="card-header">
							<input type="file" name="lampiran" id="lampiran" class="form-control">
						</div>
						<div class="card-body">
							<input type="hidden" name="file_lampiran" id="file_lampiran" value="<?=$data['lampiran']?>">
							<?=link_files_by_id($data['lampiran'], '#file_lampiran', 2, 1, 'list_file2')?>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="row mb-2">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Kode Klasifikasi Arsip* :</b>
				</div>
				<div class="col-sm-12 col-md-9">
					<select name="kka" id="kka" class="select2_select_option form-control" required style="width:100%!important;" required></select>
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Sub Kode :</b>
				</div>
				<div class="col-sm-12 col-md-9">
					<select name="sub_kka" id="sub_kka" class="select2_select_option form-control" style="width:100%!important;"></select>
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-12 col-md-3">
					<b class="d-block">Catatan :</b>
				</div>
				<div class="col-sm-12 col-md-9">
					<textarea name="catatan" id="catatan" class="form-control summernote_text2"><?=$data['catatan']?></textarea>
				</div>
			</div>
		</div>
	</div>
<?php }?>
	<div class="card">
		<div class="card-body bg-secondary" align="center">
			<button id="ID_BTN_SIMPAN" type="submit" class="btn btn-primary btn-simpan mr-2"><?php if($id==0){echo '<i class="fa fa-angle-right"></i> Lanjut';}else{echo '<i class="fa fa-save"></i> Simpan Naskah';}?></button>
			<button type="reset" class="btn btn-danger btn-batal" data-bs-dismiss="modal" onclick="window.location.assign('<?=site_url('persuratan/draft')?>')"><i class="fa fa-stop-circle"></i> Batal</button>
		</div>
	</div>
<?=form_close()?>
<!-- MODAL FORM INPUT PEJABAT KL -->
<div class="modal fade" id="modal_form_pejabat_kl" tabindex="-1">
	<div class="modal-dialog modal-lg" id="modalXlSub">
		<div class="text-right text-danger" align="right" style="font-size:14pt"><i class="button fa fa-times" data-bs-dismiss="modal"></i></div>
		<div class="modal-content p-4">
			<table class="table table-borderless">
				<tr>
					<td colspan="2" class="fw-bold" style="font-size:14pt" id="label_modal_form_pejabat_kl"></td>
				</tr>
				<tr>
					<td width="20%">Nama*</td>
					<td><input type="text" id="pkl_nama" class="form-control"></td>
				</tr>
				<tr>
					<td>Jabatan*</td>
					<td><textarea id="pkl_jabatan" class="form-control"></textarea></td>
				</tr>
				<tr>
					<td>Instansi*</td>
					<td><textarea id="pkl_instansi" class="form-control"></textarea></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><input type="text" id="pkl_email" class="form-control"></td>
				</tr>
				<tr>
					<td>Kontak</td>
					<td><input type="text" id="pkl_kontak" class="form-control"></td>
				</tr>
				<tr>
					<td>Alamat</td>
					<td><textarea id="pkl_alamat" class="form-control"></textarea></td>
				</tr>
				<tr class="bg-secondary">
					<td colspan="2" align="right">
						<a href="#" class="btn btn-default" onclick="save_pejabat_kl()">Simpan data</a>
						<a href="#" class="btn btn-danger" data-bs-dismiss="modal">Batal</a>
						<input type="hidden" id="txt_ext" value="penerima">
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
<link href="<?=base_url('assets/')?>css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url('assets/')?>js/select2.full.min.js"></script>
<script type="text/javascript">

	$(function(){
		select2_referensi('#draf_for', 'surat_create');
		select2_referensi('#jenis', 'surat_jenis');
		select2_referensi('#draf_type', 'surat_drafting');
		select2_referensi('#sifat', 'surat_sifat');
		select2_referensi('#urgensi', 'surat_urgensi');
		// select2_referensi('#signer_alt', 'surat_person_sebagai');
        select2_referensi('#penerima_show', 'surat_person')
        select2_referensi('#tembusan_show', 'surat_person')
	})

	$('.select2_select_option').select2({
		placeholder: 'Pilih Data',
		<?php if(empty($data)){?>
        	dropdownParent: $('#modalXl'),
    	<?php }?>
	});



	$('#form_compose_naskah<?=$id?>').on('submit', function(e){
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
			timeout: 60000,
			success: function(responseData, textStatus, jqXHR) {
				var dt = responseData
				$('input[name=<?=csrf_token()?>]').val(dt.csrf)
				if(dt.status==true)
				{
					<?php if($id==0){?>
						var jns_nskh = $('#jenis').val()
						window.location.assign('<?=site_url('persuratan/compose?id=')?>'+dt.ID)
					<?php }else{?>
						window.location.assign('<?=site_url('surat/draft_detail/?id=')?>'+dt.ID)
					<?php }?>
				}else{
					alert(dt.message)
					$('.btn-simpan').html('<i class="fa fa-save"></i> Simpan').removeAttr('disabled', false);
					$('.btn-batal').removeAttr('disabled', false);
				}
			}
		}).catch(function(e) {
			if(e.statusText == 'timeout')
			{     
				alert('Failed from timeout'); 
				window.location.reload(true)
				//do something. Try again perhaps?
			}
		});
	});
</script>
<?php if(!empty($data)){?>
	<link href="<?=base_url('assets/vendors/bootstrap-datepicker/')?>css/bootstrap-datepicker.min.css" rel="stylesheet" />
	<script src="<?=base_url('assets/vendors/bootstrap-datepicker/')?>js/bootstrap-datepicker.min.js"></script>
	<link rel="stylesheet" href="<?=base_url('assets/vendors/summernote/summernote-lite.css')?>">
	<script src="<?=base_url('assets/vendors/summernote/summernote-lite.min.js')?>"></script>
	<!-- <link rel="stylesheet" type="text/css" href="<?=base_url('assets/')?>src/styles.css"> -->
	<!-- <script src="<?=base_url('assets/')?>build/ckeditor.js"></script> -->
	<script type="text/javascript">

		$(function(){
			$('#draf_for').html('<option value="<?=$data['draf_for']?>" selected><?=$data['draf_for_name']?></option>').trigger('change')
			$('#jenis').html('<option value="<?=$data['jenis']?>" selected><?=$data['jenis_name']?></option>').trigger('change')
			$('#draf_type').html('<option value="<?=$data['draf_type']?>" selected><?=$data['draf_type_name']?></option>').trigger('change')
			$('#sifat').html('<option value="<?=$data['sifat']?>" selected><?=$data['sifat_name']?></option>').trigger('change')
			$('#urgensi').html('<option value="<?=$data['urgensi']?>" selected><?=$data['urgensi_name']?></option>').trigger('change')
			$('#signer_show').html('<option value="<?=$data['signer_show']?>" selected><?=$data['signer_show']?></option>').trigger('change')
			$('#pengirim_show').html('<option value="<?=$data['pengirim_show']?>" selected><?=$data['pengirim_show']?></option>').trigger('change')
			$('#penerima_show').html('<option value="<?=$data['penerima_show']?>" selected><?=$data['penerima_show']?></option>').trigger('change')
			$('#tembusan_show').html('<option value="<?=$data['tembusan_show']?>" selected><?=$data['tembusan_show']?></option>').trigger('change')
			check_draf_type();
		})



		/*
		*	jsf untuk check sumber naskah
		*	berupa pilihan unggahan/text tulis
		*/
		function check_draf_type()
		{
			// var fl2 = $('select[name=draf_type] option').filter(':selected').val();
			var fl2 = $('#draf_type option').filter(':selected').val();
			if(fl2==1){
				$('.div_draf_type_2').show()
				$('#isi_memo').prop('required', true)
				$('.div_draf_type_3').hide()
				$('#file').prop('required', false)
			}else{
				$('.div_draf_type_3').show()
				$('#file').prop('required', true)
				$('.div_draf_type_2').hide()
				$('#isi_memo').prop('required', false)
			}
			$('#signer_opt').val('<?=$data['signer_opt']?>')
			$('#signer_alt').val('<?=$data['signer_alt']?>')
			<?php if(empty($data)){?>
				$('.input_signer').hide()
			<?php }?>
			check_penandatangan()
			check_penerima()
		}



		/*
		*	check naskah akan ditandatangani oleh sendiri/orang lain
		*/
		function check_penandatangan()
		{
			let cv = $('#signer_opt').val()
			console.log('penandatangan:', cv)
			if(cv==2){
				$('.input_signer').show(); 
				$('#signer_alt').prop('required', true); 
				$('#penandatangan').prop('required', true);
			}else if(cv==='1'){
				$('.input_signer').hide(); 
				$('#signer_alt').prop('required', false); 
				$('#penandatangan').prop('required', false);
			}else{
				$('.input_signer').hide(); 
				$('#signer_alt').prop('required', false); 
				$('#penandatangan').prop('required', false);
			}
		}



		function check_penerima()
		{
			let cv = $('input[name=penerima_pada_lampiran]:checked').val()
			console.log('penerima:', cv)
			if(cv==1){
				$('.penerima_keterangan_lampiran').show(); 
				$('#penerima_keterangan_lampiran').prop('required', true); 
			}else{
				$('.penerima_keterangan_lampiran').hide(); 
				$('#penerima_keterangan_lampiran').prop('required', false); 
			}
		}



		/*
		*	delete file jika sumber naskah dari unggahan
		*/
		function delete_fileg(id)
		{
			$.get('<?=site_url('restapi/remove_surat/')?>', {id:id}, function(rs){
				console.log(rs)
			})
		}


		// if surat editor
		$('.summernote_text').summernote({
			dialogsInBody: true,
			//,airMode: true,
			height: 380,
			minHeight: 300,
			placeholder: 'Input Konten...'
		});



		$('.datepicker').datepicker({
			format: 'yyyy-mm-dd',
			// startDate: '-3d'
			autoclose: true,
		});



		$('#tembusan').on('change', function(){
			var data = $('#tembusan').val()
			console.log(data)
		})



		/*	PENERIMA INTERNAL	*/
		$('#penerima').select2({
			placeholder: 'Pilih Pejabat Penerima Internal',
			// delay: 25,
			ajax: {
				url: '<?=site_url('api/list_pejabat_internal/')?>',
	    		dataType: 'json',
				data: function (params) {
					var query = {
						search: params.term
						// satker: satker,
					}
					return query;
				},
				processResults: function (data) {
		            var dataSet = [];
		            $.each(data, function(i, item){
		            	var tmp = {id:item.id_pegawai, text: item.nama+' ('+item.jabatan+', '+item.satuankerja+')'}
		            	dataSet.push(tmp)
		            })
					return {
						results: dataSet
					};
				}
			}
		});
		var penerima_internal = ''
		<?php /* foreach($data_penerima as $k){?>
			// penerima_internal += '<option value="<?=$k->id_pegawai?>" selected="selected"><?=$k->nama?> <?=$this->open_model->replaceDataPimpinan($k->jabatan.', '.$k->satuankerja)?></option>'
		<?php } */ ?>
		$('#penerima').append(penerima_internal).trigger('change')
		$('#penerima').on('select2:select', function(){
			console.log('Penerima:', this.value)
			$.post('<?=site_url('surat/st_penerima/?id='.$data['id'])?>', {penerima: this.value, id:'<?=$data['id']?>'}, function(rs){
				console.log('HASIL_POST:', rs)
				$('#penerima').val('').trigger('change')
				get_penerima_st()
			})
		})



		/*	TEMBUSAN INTERNAL 	*/
		$('#tembusan').select2({
			placeholder: 'Pilih Pejabat Tembusan Internal',
			ajax: {
				url: '<?=site_url('api/list_pejabat_internal/')?>',
	    		dataType: 'json',
				data: function (params) {
					var query = {
						search: params.term
						// satker: satker,
					}
					return query;
				},
				processResults: function (data) {
		            var dataSet = [];
		            $.each(data, function(i, item){
		            	var tmp = {id:item.id_pegawai, text: item.nama+' ('+item.jabatan+', '+item.satuankerja+')'}
		            	dataSet.push(tmp)
		            })
					return {
						results: dataSet
					};
				}
			}
		});
		var tembusan_internal = ''
		<?php /* foreach($data_tembusan as $k){?>
			// tembusan_internal += '<option value="<?=$k->id_pegawai?>" selected="selected"><?=$k->nama?> <?=$this->open_model->replaceDataPimpinan($k->jabatan.', '.$k->satuankerja)?></option>'
		<?php } */?>
		$('#tembusan').append(tembusan_internal).trigger('change')
		$('#tembusan').on('select2:select', function(){
			console.log('tembusan:', this.value)
			$.post('<?=site_url('surat/st_tembusan/?id='.$data['id'])?>', {tembusan: this.value, id:'<?=$data['id']?>'}, function(rs){
				console.log('HASIL_POST:', rs)
				$('#tembusan').val('').trigger('change')
				get_tembusan_st()
			})
		})



		/*
		*	list penerima eksternal
		*/
		$('#penerima_ext').select2({
			placeholder: 'Pilih Pejabat KL',
			ajax: {
				url: '<?=site_url('api/list_pejabat_kl/')?>',
	    		dataType: 'json',
				data: function (params) {
					var query = {
						search: params.term,
					}
					return query;
				},
				processResults: function (data) {
		            var dataSet = [];
		            $.each(data, function(i, item){
		            	var tmp = {id:item.id, text: item.nama+', '+item.jabatan+', '+item.instansi}
		            	dataSet.push(tmp)
		            })
					return {
						results: dataSet
					};
				}
			}
		});
		$('#penerima_ext').on('select2:select', function(){
			console.log('PenerimaEx:', this.value)
			$.post('<?=site_url('surat/st_penerima/?id='.$data['id'])?>', {penerima_ext: this.value, id:'<?=$data['id']?>'}, function(rs){
				console.log('HASIL_POST:', rs)
				$('#penerima_ext').val('').trigger('change')
				get_penerima_st()
			})
		})



		/*
		*	list tembusan eksternal
		*/
		$('#tembusan_ext').select2({
			placeholder: 'Pilih Tembusan Eksternal',
			ajax: {
				url: '<?=site_url('api/list_pejabat_kl/')?>',
	    		dataType: 'json',
				data: function (params) {
					var query = {
						search: params.term,
					}
					return query;
				},
				processResults: function (data) {
		            var dataSet = [];
		            $.each(data, function(i, item){
		            	var tmp = {id:item.id, text: item.nama+', '+item.jabatan+', '+item.instansi}
		            	dataSet.push(tmp)
		            })
					return {
						results: dataSet
					};
				}
			},
		});
		$('#tembusan_ext').on('select2:select', function(){
			console.log('tembusanEx:', this.value)
			$.post('<?=site_url('surat/st_tembusan/?id='.$data['id'])?>', {tembusan_ext: this.value, id:'<?=$data['id']?>'}, function(rs){
				console.log('HASIL_POST:', rs)
				$('#tembusan_ext').val('').trigger('change')
				get_tembusan_st()
			})
		})



		/*
		*	list sub kka
		*/
		$('#kka').on('change', function(){
			$('#sub_kka').val('').html('').trigger('change')
		})
		$('#sub_kka').select2({
			placeholder: 'Pilih Klasifikasi',
			ajax: {
				url: '<?=site_url('api/klasifikasi_arsip/')?>',
	    		dataType: 'json',
				data: function (params) {
					var query = {
						search: params.term,
						id: $('#kka').val()
					}
					return query;
				},
				processResults: function (data) {
		            var dataSet = [];
		            $.each(data, function(i, item){
		            	var kode = ''
		            	if(item.ref_id!='')
		            	{
		            		kode = item.ref_id+item.id+' - '
		            	}
		            	var tmp = {id:item.id, text: kode+item.name}
		            	dataSet.push(tmp)
		            })
					return {
						results: dataSet
					};
				}
			}
		});
		$("#sub_kka").val('<?=$data['sub_kka']?>').trigger('change');






		/*
		*	form open form input manual penerima & tembusan eksternal
		*/
		function open_form_kl(txt)
		{
			$('#txt_ext').val(txt)
			$('#modal_form_pejabat_kl').modal('show');
			$('label_modal_form_pejabat_kl').html(txt)
		}



		/*
		*	save pejabat kl
		*/
		function save_pejabat_kl()
		{
			$.post('<?=site_url('api/save_pejabat_kl/')?>',
				{
					nama: $('#pkl_nama').val(),	
					jabatan: $('#pkl_jabatan').val(),	
					instansi: $('#pkl_instansi').val(),	
					email: $('#pkl_email').val(),	
					kontak: $('#pkl_kontak').val(),	
					alamat: $('#pkl_alamat').val(),	
					//<?php // $this->security->get_csrf_token_name()?>: $('input[name=<?php //$this->security->get_csrf_token_name()?>]').val(),
				}, function(d){
					$('input[name=<?=csrf_token()?>]').val(dt.csrf)
					if(d.r_status==1){
						if($('#txt_ext').val()=='penerima')
						{
							$("#penerima_ext").append('<option value="'+d.id+'" selected>'+$('#pkl_nama').val()+', '+$('#pkl_jabatan').val()+', '+$('#pkl_instansi').val()+'</option>').trigger('change');
						}else if($('#txt_ext').val()=='tembusan'){
							$("#tembusan_ext").append('<option value="'+d.id+'" selected>'+$('#pkl_nama').val()+', '+$('#pkl_jabatan').val()+', '+$('#pkl_instansi').val()+'</option>').trigger('change');
						}
						$('#pkl_nama').val(''); $('#pkl_jabatan').val(''); $('#pkl_instansi').val(''); $('#pkl_email').val(''); $('#pkl_kontak').val(''); $('#pkl_alamat').val(''); 
					}else{
						alert(d.message)
					}
					$('#modal_form_pejabat_kl').modal('hide')
				}
			);
		}



		/*
		|	FOR FILE
		*/
	</script>
	<?php print_r($data)?>
<?php }?>
