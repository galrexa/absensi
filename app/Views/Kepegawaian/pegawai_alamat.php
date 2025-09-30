<div class="card">
	<div class="card-header fw-bold d-flex">
        <div class="flex-grow-1"><?=$title?></div>
        <?php if(return_access_link(['kepegawaian/alamat/form'])){?>
            <a href="<?=site_url('kepegawaian/alamat/form?pegawai_id='.string_to($id,'encode'))?>" class="btn btn-sm btn-success" title="Tambah alamat"><i class="fa fa-plus-circle"></i> Tambah</a>
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
							<th width="75%">Detail Alamat</th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($data)){ foreach($data as $r){?>
							<tr>
								<td>
									<?php if(return_access_link(['kepegawaian/alamat/form'])){?>
										<a style="font-size:14pt" class="m-1 text-success" href="<?=site_url('kepegawaian/alamat/form?id='.string_to($r->alamat_id,'encode').'&pegawai_id='.string_to($r->pegawai_id,'encode'))?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Detail data"><i class="fa fa-edit"></i></a>
									<?php }?>
								</td>
								<td><?=$r->alamat_name?></td>
								<td><?=$r->alamat.', RT/RW '.$r->rt.'/'.$r->rw.', Kel. '.$r->kelurahan_name.', Kec. '.$r->kecamatan_name.', Kab/Kot. '.$r->kabupaten_name.', Prov. '.$r->provinsi_name.', Kodepos '.$r->kodepos?></td>
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