<div class="row">
	<div class="col-sm-12 col-md-8">
		<div class="card">
			<div class="card-header fw-bold">
				<?=($title)?:'Selamat datang'?>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<b>Nama:</b> <?=session()->get('nama')?>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<b>Jabatan:</b> <?=return_jabatan_name(session()->get('jabatan_id'))?>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-sm-12 col-md-12">
						<b>Unit Kerja:</b> <?=return_unit_kerja_name(session()->get('unit_kerja_id'),3)?>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-bordered">
						<thead>
							<tr>
								<th class="text-center color-yellow-amber" colspan="4">Berikut Informasi Persuratan</th>
							</tr>
							<tr>
								<th class="text-center color-yellow-amber">Inbox</th>
								<th class="text-center color-yellow-amber">Disposisi</th>
								<th class="text-center color-yellow-amber">Review</th>
								<th class="text-center color-yellow-amber">Terkirim</th>
							</tr>
						</thead>
						<tbody>
							<?php $total=0; foreach ($data_saldo as $k) {$total += $k->sisa_saat_ini;?>
								<tr>
									<td class="text-center"><?=$k->tahun?></td>
									<td class="text-center"><?=$k->jatah?></td>
									<td class="text-center"><?=$k->digunakan?></td>
									<td class="text-center"><?=$k->sisa_saat_ini?></td>
								</tr>
							<?php }?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="3" align="right" class="fw-bold">Total Sisa:</td>
								<td class="fw-bold text-center"><?=$total?></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
