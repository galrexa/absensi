<div class="card">
	<div class="card-header fw-bold d-flex">
        <div class="flex-grow-1"><?=$title?></div>
        <?php if(return_access_link(['kepegawaian/sk/form'])){?>
            <a href="<?=site_url('kepegawaian/sk/form?pegawai_id='.string_to($id,'encode'))?>" class="btn btn-sm btn-success" title="Tambah sk"><i class="fa fa-plus-circle"></i> Tambah</a>
        <?php }?>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col table-responsive">
				<table class="table table-striped table-sm">
					<thead>
						<tr>
							<th width="5%">#</th>
							<th width="20%">Jenis</th>
							<th width="75%">Detail</th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($data)){ foreach($data as $r){$color='danger'; if($r->status==1){$color='success';}?>
							<tr>
								<td>
									<?php if(return_access_link(['kepegawaian/sk/form'])){?>
										<a style="font-size:14pt" class="m-1 text-success" href="<?=site_url('kepegawaian/sk/form?id='.string_to($r->id,'encode').'&pegawai_id='.string_to($r->pegawai_id,'encode'))?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Detail data"><i class="fa fa-edit"></i></a>
									<?php }?>
								</td>
								<td>
									<b><?=$r->jenis_name?></b>
									<b class="d-block text-<?=$color?>"><?=$r->status_name?></b>
									<div class="d-block fw-bold text-<?=$color?>">
										<?=tanggal_range($r->periode_awal, $r->periode_akhir)?>
									</div>
								</td>
								<td>
									<div class="row">
										<div class="col-sm-12 col-md-4 mb-1">
											Nomor:
										</div>
										<div class="col-sm-12 col-md-8 mb-1">
											<?=$r->nomor?>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 col-md-4 mb-1">
											Tanggal:
										</div>
										<div class="col-sm-12 col-md-8 mb-1">
											<?=tanggal($r->tanggal,3)?>
										</div>
									</div>
									<!-- <div class="row">
										<div class="col-sm-12 col-md-4 mb-1">
											Periode:
										</div>
										<div class="col-sm-12 col-md-8 mb-1 text-<?=$color?>">
											<?=tanggal_range($r->periode_awal, $r->periode_akhir)?>
										</div>
									</div> -->
									<div class="row">
										<div class="col-sm-12 col-md-4 mb-1">
											Jabatan:
										</div>
										<div class="col-sm-12 col-md-8 mb-1">
											<?=$r->jabatan_name.', '.$r->unit_kerja_name?>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 col-md-12 mb-1">
											<b class="d-block">Keterangan:</b>
											<?=($r->keterangan)?:'-'?>
											<?=link_files_by_id($r->dokumen)?>
										</div>
									</div>
								</td>
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