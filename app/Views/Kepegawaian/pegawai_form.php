<div class="card">
    <div class="card-body">
		<?=form_open('', ['id'=>'form_pegawai', 'class'=>''])?>
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>NIP:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<div class="input-group input-group-sm">
							<input type="text" name="nip" id="nip" class="form-control form-control-sm" value="<?=(isset($data))?$data->nip:''?>" <?php if(!return_roles([1,2])){echo 'disabled';}?>>
						</div>
						<input type="hidden" name="pegawai_id" value="<?=(isset($data))?$data->pegawai_id:''?>">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>NIK*:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<div class="input-group input-group-sm">
							<input type="text" name="nik" id="nik" class="form-control form-control-sm" value="<?=(isset($data))?$data->nik:''?>" <?php #if(!return_roles([1,2])){echo 'disabled';}?>>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>NPWP*:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<div class="input-group input-group-sm">
							<input type="text" name="npwp" id="npwp" class="form-control form-control-sm" value="<?=(isset($data))?$data->npwp:''?>" <?php #if(!return_roles([1,2])){echo 'disabled';}?>>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Nama*:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<div class="input-group input-group-sm">
							<input type="text" name="nama" id="nama" class="form-control form-control-sm" value="<?=(isset($data))?$data->nama:''?>" <?php #if(!return_roles([1,2])){echo 'disabled';}?>>
						</div>
						<div class="input-group input-group-sm">
							<input type="text" name="gelar_depan" id="gelar_depan" class="form-control form-control-sm" value="<?=(isset($data))?$data->gelar_depan:''?>" placeholder="Gelar Depan" <?php #if(!return_roles([1,2])){echo 'disabled';}?>>
							<input type="text" name="gelar_belakang" id="gelar_belakang" class="form-control form-control-sm" value="<?=(isset($data))?$data->gelar_belakang:''?>" placeholder="Gelar Belakang" <?php #if(!return_roles([1,2])){echo 'disabled';}?>>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>TTL*:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<div class="input-group input-group-sm">
							<div class="input-group-text"><i class="fa fa-map"></i></div>
							<input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control form-control-sm" value="<?=(isset($data))?$data->tempat_lahir:''?>" <?php #if(!return_roles([1,2])){echo 'disabled';}?>>
								<!-- <div class="input-group-text">, </div> -->
							<div class="input-group-text"><i class="fa fa-calendar"></i></div>
							<input type="text" name="tanggal_lahir" id="tanggal_lahir" class="form-control form-control-sm datepicker" value="<?=(isset($data))?$data->tanggal_lahir:''?>" <?php #if(!return_roles([1,2])){echo 'disabled';}?>>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Agama*:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<select name="agama" id="agama" class="form-control form-control-sm"></select>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Kelamin*:</b>
					</div>
					<div class="col-sm-12 col-md-5 mt-1">
						<select name="kelamin" id="kelamin" class="form-control form-control-sm"></select>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Pendidikan Terakhir*:</b>
					</div>
					<div class="col-sm-12 col-md-4 mt-1">
						<select name="pendidikan" id="pendidikan" class="form-control form-control-sm" <?php #if(!return_roles([1,2])){echo 'disabled';}?>></select>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Status Perkawinan*:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<select name="status_perkawinan" id="status_perkawinan" class="form-control form-control-sm"></select>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Nomor HP*:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<div class="input-group input-group-sm">
							<input type="text" name="hp" id="hp" class="form-control form-control-sm" value="<?=(isset($data))?$data->hp:''?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Nomor Telp:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<div class="input-group input-group-sm">
							<input type="text" name="telp" id="telp" class="form-control form-control-sm" value="<?=(isset($data))?$data->telp:''?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Email Kantor*:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<div class="input-group input-group-sm">
							<input type="text" name="email" id="email" class="form-control form-control-sm" value="<?=(isset($data))?$data->email:''?>" <?php #if(!return_roles([1,2])){echo 'disabled';}?>>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Email Pribadi:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<div class="input-group input-group-sm">
							<input type="text" name="email_pribadi" id="email_pribadi" class="form-control form-control-sm" value="<?=(isset($data))?$data->email_pribadi:''?>">
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Jenis Pegawai*:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<select name="status_jenis_pegawai" id="status_jenis_pegawai" class="form-control form-control-sm" <?php if(!return_roles([1,2])){echo 'disabled';}?> onchange="jika_status_jenis_pegawai(this.value)"></select>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Unit Kerja*:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<select name="unit_kerja_id" id="unit_kerja_id" class="form-control form-control-sm" <?php if(!return_roles([1,2])){echo 'disabled';}?>></select>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Jabatan*:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<select name="jabatan_id" id="jabatan_id" class="form-control form-control-sm" <?php if(!return_roles([1,2])){echo 'disabled';}?> onchange="jika_jabatan(this.value)"></select>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Eselon/Setara:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<select name="eselon" id="eselon" class="form-control form-control-sm" <?php if(!return_roles([1,2])){echo 'disabled';}?>></select>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-3">
						<b>Status PNS*:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-3">
						<select name="status_pns" id="status_pns" class="form-control form-control-sm" <?php if(!return_roles([1,2])){echo 'disabled';}?>></select>
					</div>
				</div>
				<div class="row div_asn">
					<div class="col-sm-12 col-md-4 mt-2">
						<b>Asal Instansi:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-2">
						<div class="input-group input-group-sm">
							<input type="text" name="asal_instansi" id="asal_instansi" class="form-control form-control-sm" value="<?=(isset($data))?$data->asal_instansi:''?>" <?php if(!return_roles([1,2])){echo 'disabled';}?>>
						</div>
					</div>
				</div>
				<div class="row div_asn">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>NIP Lama/NRP:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<div class="input-group input-group-sm">
							<input type="text" name="nip_lama" id="nip_lama" class="form-control form-control-sm" value="<?=(isset($data))?$data->nip_lama:''?>" <?php if(!return_roles([1,2])){echo 'disabled';}?>>
						</div>
					</div>
				</div>
				<div class="row div_asn">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Pangkat:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<div class="input-group input-group-sm">
							<input type="text" name="pangkat" id="pangkat" class="form-control form-control-sm" value="<?=(isset($data))?$data->pangkat:''?>" <?php if(!return_roles([1,2])){echo 'disabled';}?>>
						</div>
					</div>
				</div>
				<div class="row div_asn">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Golongan:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<div class="input-group input-group-sm">
							<input type="text" name="gol" id="gol" class="form-control form-control-sm" value="<?=(isset($data))?$data->gol:''?>" <?php if(!return_roles([1,2])){echo 'disabled';}?>>
						</div>
					</div>
				</div>
				<div class="row div_asn">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>TMT:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<div class="input-group input-group-sm">
							<input type="text" name="tmt_pang_gol" id="tmt_pang_gol" class="form-control form-control-sm" value="<?=(isset($data))?$data->tmt_pang_gol:''?>" <?php if(!return_roles([1,2])){echo 'disabled';}?>>
						</div>
					</div>
				</div>
				<div class="row div_magang">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Asal Universitas:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<select name="universitas" id="universitas" class="form-control form-control-sm" <?php if(!return_roles([1,2])){echo 'disabled';}?>></select>
					</div>
				</div>
				<div class="row div_gugustugas">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Tim Gugus Tugas:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<select name="gugustugas" id="gugustugas" class="form-control form-control-sm" <?php if(!return_roles([1,2])){echo 'disabled';}?>></select>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-2">
						<b>Nama Bank:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-2">
						<div class="input-group input-group-sm">
							<input type="text" name="bank_name" id="bank_name" class="form-control form-control-sm" value="<?=(isset($data))?$data->bank_name:''?>" <?php if(!return_roles([1,2])){echo 'disabled';}?>>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Kantor Cabang:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<div class="input-group input-group-sm">
							<input type="text" name="bank_region" id="bank_region" class="form-control form-control-sm" value="<?=(isset($data))?$data->bank_region:''?>" <?php if(!return_roles([1,2])){echo 'disabled';}?>>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Nomor Rekening:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<div class="input-group input-group-sm">
							<input type="text" name="bank_account" id="bank_account" class="form-control form-control-sm" value="<?=(isset($data))?$data->bank_account:''?>" <?php if(!return_roles([1,2])){echo 'disabled';}?>>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-1">
						<b>Nama Pemilik Rekening:</b>
					</div>
					<div class="col-sm-12 col-md-8 mt-1">
						<div class="input-group input-group-sm">
							<input type="text" name="bank_account_name" id="bank_account_name" class="form-control form-control-sm" value="<?=(isset($data))?$data->bank_account_name:''?>" <?php if(!return_roles([1,2])){echo 'disabled';}?>>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="row">
					<div class="col-sm-12 col-md-4 mt-3">
						<b>Status Kepegawaian*:</b>
					</div>
					<div class="col-sm-12 col-md-4 mt-3">
						<select name="status" id="status" class="form-control form-control-sm" <?php if(!return_roles([1,2])){echo 'disabled';}?>></select>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-sm-12 col-md-4"></div>
					<div class="col-sm-12 col-md-8">
						<button class="btn btn-success" id="tbnsimpan" type="submit">Simpan</button>
						<?php if(empty($data)){?>
							<button class="btn btn-secondary" type="reset" onclick="window.location.assign('<?=session()->get('_ci_previous_url')?>')">Kembali</button>
						<?php }else{?>
							<button class="btn btn-secondary" type="reset" onclick="window.location.assign('<?=site_url('kepegawaian/profile?id='.$data->pegawai_id)?>')">Kembali</button>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
		<?=form_close()?>
	</div>
</div>
<link href="<?=base_url('assets/vendors/bootstrap-datepicker/css/bootstrap-datepicker.min.css')?>" rel="stylesheet" />
<script src="<?=base_url('assets/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<link href="<?=base_url('assets/')?>css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url('assets/')?>js/select2.full.min.js"></script>
<script type="text/javascript">
	$(function(){
        <?php if(!empty($data)){?>
        	$('#status').append('<option value="<?=$data->status?>" selected><?=$data->status_name?></option>').trigger('change')
        	$('#status_pns').append('<option value="<?=$data->status_pns?>" selected><?=$data->status_pns_name?></option>').trigger('change')
        	$('#eselon').append('<option value="<?=$data->eselon?>" selected><?=$data->eselon?></option>').trigger('change')
        	$('#status_perkawinan').append('<option value="<?=$data->status_perkawinan?>" selected><?=$data->status_perkawinan_name?></option>').trigger('change')
        	$('#status_jenis_pegawai').append('<option value="<?=$data->status_jenis_pegawai?>" selected><?=$data->status_jenis_pegawai_name?></option>').trigger('change')
        	$('#pendidikan').append('<option value="<?=$data->pendidikan?>" selected><?=$data->pendidikan_name?></option>').trigger('change')
        	$('#kelamin').append('<option value="<?=$data->kelamin?>" selected><?=$data->kelamin_name?></option>').trigger('change')
        	$('#agama').append('<option value="<?=$data->agama?>" selected><?=$data->agama_name?></option>').trigger('change')
        	$('#unit_kerja_id').append('<option value="<?=$data->unit_kerja_id?>" selected><?=$data->unit_kerja?></option>').trigger('change')
        	$('#jabatan_id').append('<option value="<?=$data->jabatan_id?>" selected><?=$data->jabatan?></option>').trigger('change')
        	$('#universitas').append('<option value="<?=$data->universitas?>" selected><?=$data->universitas_name?></option>').trigger('change')
        	$('#gugustugas').append('<option value="<?=$data->gugustugas?>" selected><?=$data->gugustugas_name?></option>').trigger('change')
        <?php }?>
        select2_unit_kerja('#unit_kerja_id')
        select2_jabatan('#jabatan_id', '')
        select2_referensi('#agama', 'agama')
        select2_referensi('#kelamin', 'gender')
        select2_referensi('#pendidikan', 'pendidikan')
        select2_referensi('#status_jenis_pegawai', 'pegawai_status_jenis')
        select2_referensi('#status_perkawinan', 'pegawai_status_kawin')
        select2_referensi('#eselon', 'pegawai_golongan')
        select2_referensi('#status_pns', 'pegawai_status_pns')
        select2_referensi('#status', 'pegawai_status')
        select2_perguruan_tinggi('#universitas')
        select2_gugus_tugas('#gugustugas')

        // jika_status_pns('<?=(isset($data))?$data->status_pns:0?>')
        jika_status_jenis_pegawai('<?=(isset($data))?$data->status_jenis_pegawai:0?>')
        jika_jabatan('<?=(isset($data))?$data->jabatan_id:0?>')
	})

	$('.datepicker').datepicker({
		autoclose: true,
		format: 'yyyy-mm-dd',
	});

	function jika_jabatan(id)
	{
		switch (id){
		case '42':
			$('.div_gugustugas').show();
			$('#gugustugas').prop('required', true)
			break
		default:
			$('.div_gugustugas').hide();
			$('#gugustugas').prop('required', false).val('').trigger('change')
			break
		}
	}

	// function jika_status_pns(id)
	// {
	// 	switch (id){
	// 	case '1':
	// 	case '2':
	// 		$('.div_asn').show();
	// 		$('#asal_instansi').prop('required', true);$('#nip_lama').prop('required', true);$('#pangkat').prop('required', true);$('#gol').prop('required', true);$('#tmp_pang_gol').prop('required', true);
	// 		break
	// 	case '3':
	// 		$('.div_asn').hide();
	// 		$('#asal_instansi').prop('required', false);$('#nip_lama').prop('required', false);$('#pangkat').prop('required', false);$('#gol').prop('required', false);$('#tmp_pang_gol').prop('required', false);
	// 		break
	// 	case '4':
	// 		$('.div_asn').hide();
	// 		$('#asal_instansi').prop('required', false);$('#nip_lama').prop('required', false);$('#pangkat').prop('required', false);$('#gol').prop('required', false);$('#tmp_pang_gol').prop('required', false);
	// 		break
	// 	default:
	// 		$('.div_asn').hide();
	// 		$('#asal_instansi').prop('required', false);$('#nip_lama').prop('required', false);$('#pangkat').prop('required', false);$('#gol').prop('required', false);$('#tmp_pang_gol').prop('required', false);
	// 		break
	// 	}
	// }

	function jika_status_jenis_pegawai(id)
	{
		switch (id){
		case '1':
			$('.div_asn').show();
			$('#asal_instansi').prop('required', true);$('#nip_lama').prop('required', true);$('#pangkat').prop('required', true);$('#gol').prop('required', true);$('#tmp_pang_gol').prop('required', true);
			$('.div_magang').hide();
			$('#universitas').prop('required', false);
			break
		case '5':
			$('.div_magang').show();
			$('#universitas').prop('required', true);
			$('.div_asn').hide();
			$('#asal_instansi').prop('required', false);$('#nip_lama').prop('required', false);$('#pangkat').prop('required', false);$('#gol').prop('required', false);$('#tmp_pang_gol').prop('required', false);
			break
		default:
			$('.div_asn').hide();
			$('#asal_instansi').prop('required', false);$('#nip_lama').prop('required', false);$('#pangkat').prop('required', false);$('#gol').prop('required', false);$('#tmp_pang_gol').prop('required', false);
			$('.div_magang').hide();
			$('#universitas').prop('required', false);
			break
		}
	}

	$('#form_pegawai').on('submit', function(e){
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
					window.location.assign('<?=site_url('kepegawaian/profile?id=')?>'+dt.id);
				}else{
					alert(dt.message.replace(/<p>|<\/p>/g, ""));
				}
				$('.btn_login').prop('disabled', false).html('Submit');
				$('input[name=<?=csrf_token()?>]').val(dt.csrf);
			}
		});
	});
</script>