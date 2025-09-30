<div class="card">
	<div class="card-header fw-bold" style="font-size:">
		<?=$title?>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col table-responsive">
				<table class="table table-striped table-sm">
					<thead>
						<tr>
							<th width="10%">#</th>
							<th width="20%">Jenis</th>
							<th width="70%">Detail</th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($data)){ $no=0; foreach($data as $r){$no+=1; $filenya='belum tersedia'; $color='danger'; if($r->status==1){$filenya='tersedia'; $color='success';}?>
							<tr>
								<td>
									<div class="input-group">
										<div class="dropdown">
											<a class="dropdown-toggle text-success" href="#" data-bs-toggle="dropdown"><i class="fa fa-edit"></i></a>
											<ul class="dropdown-menu">
												<li class="dropdown-item"><input type="file" name="files_user<?=$r->ref_code?>" id="files_user<?=$r->ref_code?>" data="<?=$r->ref_code?>" class="files_user"></li>
												<?php if($r->status==1){?>
													<li class="dropdown-item"><a href="#" class="text-danger" onclick="file_deleted_('<?=string_to($r->file_id, 'encode')?>')"><i class="fa fa-trash"></i> Hapus file <?=$r->file_jenis_name?></a></li>
												<?php }?>
											</ul>
										</div>
										<?php if($r->status==1){?>
											<!-- <a href="<?=site_url('file/download?id='.string_to($r->file_id, 'encode'))?>" target="_blank" class="ms-3"><i class="fa fa-download"></i></a> -->
											<a href="<?=site_url('file/download?id='.$r->file_id)?>" target="_blank" class="ms-3"><i class="fa fa-download"></i></a>
										<?php }?>
									</div>
								</td>
								<td><?=$r->file_jenis_name?></td>
								<td class="text-<?=$color?>"><?=$filenya?></td>
							</tr>
						<?php }}else{?>
							<tr>
								<td colspan="3">empty</td>
							</tr>
						<?php }?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('.files_user').on('change', function(){
		var first = 'other_'
		var file_jenis = 0
		switch(this.name){
		case 'files_user1':
			first = 'ktp'
			file_jenis=1
			break
		case 'files_user2':
			first = 'kk'
			file_jenis=2
			break
		case 'files_user3':
			first = 'bpjs_kesehatan'
			file_jenis=3
			break
		case 'files_user4':
			first = 'bpjs_tk'
			file_jenis=4
			break
		case 'files_user5':
			first = 'npwp'
			file_jenis=5
			break
		case 'files_user6':
			first = 'spesimen_ttd'
			file_jenis=6
			break
		// case 'files_user6':
		// 	first = 'cert_tte'
		// 	file_jenis=6
		// 	break
		}
		var rsj = file_upload_form_whit_query('#'+this.name, first, '?pegawai_id=<?=$id?>&file_jenis='+file_jenis)
		setTimeout(() => load_halaman_in_profile('files', '<?=string_to($id, 'encode')?>'),500)
	})


	function file_deleted_(id){
		var cf = confirm('Hapus File ini?...')
		if(cf===true)
		{
			$.get('<?=site_url('file/deleted')?>', {id:id}, function(data){
				if(data.status){
					setTimeout(() => load_halaman_in_profile('files', '<?=string_to($id, 'encode')?>'),500)
				}else{
					alert(data.message)
				}
			})
		}
	}
</script>