<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-md-3 col-sm-12">
				<?php
				$foto_peg = create_file_to_base64($data->foto_pegawai);
				if($foto_peg=='')
				{
					$foto_peg = get_foto_default_pegawai((isset($data))?$data->kelamin:1);
				}
				?>
				<div class="card mb-2">
					<div class="card-body">
						<img src="<?=$foto_peg?>" class="img-thumbnail">
					</div>
					<?php if (return_roles([1,2])) {?>
						<div class="card-footer" align="center">
	                    	<label for="foto" class="file-upload-button">
								<input type="file" name="foto" id="foto" class="btn btn-success">
								<i class="fa fa-upload"></i> Unggah Foto
							</label>
						</div>
					<?php }?>
				</div>
				<!-- <div class="input-group mt-2 mb-2"><input type="file" name="foto" id="foto" class="btn btn-success"></div> -->
			</div>
			<div class="col-md-9 col-sm-12">
				<div class="nav nav-tabs" id="nav-tab" role="tablist">
					<li class="nav-item"><a class="nav-link <?php if($tab==''){echo'active';}?>" data-bs-toggle="tab" href="#nav-home">Biodata</a></li>
					<?php if(return_access_link(['kepegawaian/alamat'])){?>
						<li class="nav-item"><a class="nav-link <?php if($tab=='alamat'){echo'active';}?>" data-bs-toggle="tab" href="#nav-profile" onclick="load_halaman_in_profile('alamat', '<?=(isset($data))?string_to($data->pegawai_id,'encode'):''?>')">Alamat</a></li>
					<?php }?>
					<?php if(return_access_link(['kepegawaian/sk'])){?>
						<li class="nav-item"><a class="nav-link <?php if($tab=='keputusan'){echo'active';}?>" data-bs-toggle="tab" href="#nav-profile" onclick="load_halaman_in_profile('keputusan', '<?=(isset($data))?string_to($data->pegawai_id,'encode'):''?>')">Keputusan</a></li>
					<?php }?>
					<?php if(return_access_link(['kepegawaian/fasilitas'])){?>
						<li class="nav-item"><a class="nav-link <?php if($tab=='fasilitas'){echo'active';}?>" data-bs-toggle="tab" href="#nav-profile" onclick="load_halaman_in_profile('fasilitas', '<?=(isset($data))?string_to($data->pegawai_id,'encode'):''?>')">Fasilitas</a></li>
					<?php }?>
					<?php if(return_access_link(['kepegawaian/qrcode'])){?>
						<li class="nav-item"><a class="nav-link <?php if($tab=='qrcode'){echo'active';}?>" data-bs-toggle="tab" href="#nav-profile" onclick="load_halaman_in_profile('qrcode', '<?=(isset($data))?string_to($data->pegawai_id,'encode'):''?>')">ID-Card</a></li>
					<?php }?>
					<?php if(return_access_link(['kepegawaian/files'])){?>
						<li class="nav-item"><a class="nav-link <?php if($tab=='files'){echo'active';}?>" data-bs-toggle="tab" href="#nav-profile" onclick="load_halaman_in_profile('files', '<?=(isset($data))?string_to($data->pegawai_id,'encode'):''?>')">Files</a></li>
					<?php }?>
					<?php if(return_access_link(['kepegawaian/user/form'])){?>
						<li class="nav-item"><a class="nav-link <?php if($tab=='user'){echo'active';}?>" data-bs-toggle="tab" href="#nav-profile" onclick="load_halaman_in_profile('user', '<?=(isset($data))?string_to($data->pegawai_id,'encode'):''?>')">User Login</a></li>
					<?php }?>
				</div>
				<div class="tab-content">
					<div class="tab-pane fade <?php if($tab==''){echo'show active';}?> pt-3" id="nav-home">
						<!-- FORM PEGAWAI -->
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>NIP:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->nip:''?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>NIK:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->nik:''?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>NPWP:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->npwp:''?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>Nama:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->gelar_depan:''?><?=(isset($data))?$data->nama:''?><?=(isset($data))?', '.$data->gelar_belakang:''?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>Tempat, TGL Lahir:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->tempat_lahir:''?>, <?=tanggal((isset($data))?$data->tanggal_lahir:'',3)?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>Agama:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->agama_name:0?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>Kelamin:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->kelamin_name:0?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>Pendidikan Terakhir:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->pendidikan_name:''?>
							</div>
						</div>
						<div class="row mb-4 border-bottom">
							<div class="col-md-4 col-sm-12">
								<b>Status Perkawinan:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->status_perkawinan_name:0?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>Nomor HP:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->hp:''?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>Nomor Telp:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->telp:''?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>Email Kantor:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->email:''?>
							</div>
						</div>
						<div class="row mb-4 border-bottom">
							<div class="col-md-4 col-sm-12 mb-2">
								<b>Email Pribadi:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->email_pribadi:''?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>Jenis Pegawai:</b>
							</div>
							<div class="col-md-8 col-sm-12 fw-bold">
								<?=(isset($data))?$data->status_jenis_pegawai_name:''?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>Unit Kerja:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->unit_kerja_alt:''?> (<?=(isset($data))?$data->unit_kerja:''?>)
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>Jabatan:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->jabatan:''?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>Eselon/Setara:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->eselon:''?>
							</div>
						</div>
						<div class="row mb-4 border-bottom">
							<div class="col-md-4 col-sm-12">
								<b>Status PNS:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->status_pns_name:''?>
							</div>
						</div>
						<?php if(array_keys([1,2], $data->status_pns)){?>
							<div class="row mt-2">
								<div class="col-md-4 col-sm-12">
									<b>Asal Instansi:</b>
								</div>
								<div class="col-md-8 col-sm-12">
									<?=(isset($data))?$data->asal_instansi:''?>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-md-4 col-sm-12">
									<b>NIP Lama/NRP:</b>
								</div>
								<div class="col-md-8 col-sm-12">
									<?=(isset($data))?$data->nip_lama:''?>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-md-4 col-sm-12">
									<b>Pangkat:</b>
								</div>
								<div class="col-md-8 col-sm-12">
									<?=(isset($data))?$data->pangkat:''?>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-md-4 col-sm-12">
									<b>Golongan:</b>
								</div>
								<div class="col-md-8 col-sm-12">
									<?=(isset($data))?$data->gol:''?>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-md-4 col-sm-12">
									<b>TMT:</b>
								</div>
								<div class="col-md-8 col-sm-12">
									<?=(isset($data))?$data->tmt_pang_gol:''?>
								</div>
							</div>
						<?php } if(array_keys([5], $data->status_jenis_pegawai)){?>
							<div class="row mt-2">
								<div class="col-md-4 col-sm-12">
									<b>Asal Universitas:</b>
								</div>
								<div class="col-md-8 col-sm-12">
									<?=(isset($data))?$data->universitas_name:''?>
								</div>
							</div>
						<?php } if(array_keys([42], $data->jabatan_id)){?>
							<div class="row mt-2">
								<div class="col-md-4 col-sm-12">
									<b>Tim Gugus Tugas:</b>
								</div>
								<div class="col-md-8 col-sm-12">
									<?=(isset($data))?$data->gugustugas_name:''?>
								</div>
							</div>
						<?php }?>
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>Bank:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->bank_name:''?>, 
								<?=(isset($data))?$data->bank_region:''?>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-4 col-sm-12">
								<b>Rekening:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->bank_account:''?>, AN. 
								<?=(isset($data))?$data->bank_account_name:''?>
							</div>
						</div>
						<div class="row mt-2">
							<div class="col-md-4 col-sm-12">
								<b>Status Kepegawaian:</b>
							</div>
							<div class="col-md-8 col-sm-12">
								<?=(isset($data))?$data->status_name:''?>
								<?php if(return_access_link(['kepegawaian/form'])){?>
									<hr>
									<a href="<?=site_url('kepegawaian/form?id='.string_to($data->pegawai_id, 'encode'))?>" class="btn btn-success btn-sm" title="Edit data"><i class="fa fa-edit"></i> Edit</a>
								<?php }?>
							</div>
						</div>
					</div>
					<div class="tab-pane fade <?php if(array_keys(['alamat','keputusan','fasilitas','qrcode','files'],$tab)){echo'show active';}?> pt-3" id="nav-profile">...</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		<?php if($tab<>''){?>
			load_halaman_in_profile('<?=$tab?>', '<?=string_to($data->pegawai_id, 'encode')?>')
		<?php }?>
	})

	<?php if (return_roles([1,2])) {?>
		$('#foto').on('change', function(){
			file_upload_form_whit_query('#foto', 'foto_', '?pegawai_id=<?=$data->pegawai_id?>')
			setTimeout(() => window.location.reload(true), 500)
		})

	    const fileInput = document.getElementById(`foto`);
	    // Handle file selection
	    fileInput.addEventListener('change', function() {});
	<?php }?>

	function load_halaman_in_profile(page, id)
	{
		$('#nav-profile').html('<i class="fa fa-exclamation-circle"></i> Loading.....')
		var url = '<?=site_url('kepegawaian/qrcode?id=')?>'+id;
		switch(page){
			case 'alamat':
				url = '<?=site_url('kepegawaian/alamat?id=')?>'+id;
				break
			case 'keputusan':
				url = '<?=site_url('kepegawaian/sk?id=')?>'+id;
				break
			case 'fasilitas':
				url = '<?=site_url('kepegawaian/fasilitas?id=')?>'+id;
				break
			case 'files':
				url = '<?=site_url('kepegawaian/files?id=')?>'+id;
				break
			case 'user':
				url = '<?=site_url('kepegawaian/user/form?id=')?>'+id;
				break
		}
		$.get(url, function(rs){
			$('#nav-profile').html(rs)
		})
	}
</script>