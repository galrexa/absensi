<div class="card">
	<div class="card-header fw-bold d-flex">
        <div class="flex-grow-1"><?=$title?></div>
        <?php if(return_access_link(['kepegawaian/fasilitas/form'])){?>
            <a href="<?=site_url('kepegawaian/fasilitas/form?pegawai_id='.string_to($id,'encode'))?>" class="btn btn-sm btn-success" title="Tambah fasilitas"><i class="fa fa-plus-circle"></i> Tambah</a>
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
						<?php if(!empty($data)){ foreach($data as $r){$color='success'; if($r->status==1){$color='warning';}?>
							<tr>
								<td>
									<?php if(return_access_link(['kepegawaian/fasilitas/form'])){?>
										<a style="font-size:14pt" class="m-1 text-success" href="<?=site_url('kepegawaian/fasilitas/form?id='.string_to($r->fasilitas_id,'encode').'&pegawai_id='.string_to($r->pegawai_id,'encode'))?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Detail data"><i class="fa fa-edit"></i></a>
									<?php }?>
								</td>
								<td><?=$r->fasilitas_name?></td>
								<td>
									<div class="row">
										<div class="col-sm-12 col-md-4 mb-1">
											Tanggal diberikan:
										</div>
										<div class="col-sm-12 col-md-8 mb-1">
											<?=tanggal($r->fasilitas_tgl,3)?>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 col-md-4 mb-1">
											Catatan:
										</div>
										<div class="col-sm-12 col-md-8 mb-1">
											<?=$r->fasilitas_value?>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 col-md-4 mb-1">
											Status:
										</div>
										<div class="col-sm-12 col-md-8 mb-1">
											<b class="text-<?=$color?>"><?=$r->status_name?></b>
											<?php if($r->status==2){?>
												<em class="d-block">Tanggal pengembalian <?=tanggal($r->tgl_dikembalikan,3)?></em>
												<em class="d-block"><?=$r->fasilitas_ket?></em>
											<?php }?>
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