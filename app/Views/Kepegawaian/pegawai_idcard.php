<div class="card">
	<div class="card-header fw-bold" style="font-size:">
		<?=$title?>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col table-responsive">
				<table class="table table-sm">
					<thead>
						<tr>
							<th width="5%">#</th>
							<th width="45%">Link & Hash</th>
							<th width="50%">QR-Code</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<?php if(return_access_link(['api/kepegawaian/qrcode'])){?>
									<a href="#" id="btn-qrcode" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Buat QR Baru"><i class="fa fa-qrcode"></i></a>
								<?php }?>
							</td>
							<td>
								<em class="d-block mb-1"><?=(isset($data))?$data->url:'-'?></em>
								<textarea class="form-control mb-2" disabled><?=(isset($data))?$data->id_hash:'-'?></textarea>
								<?php if(return_access_link(['api/kepegawaian/idcard'])){?>
									<a href="#" onclick="window.open('<?=site_url('api/kepegawaian/idcard?id='.string_to($id, 'encode').'&file=pdf')?>', '', 'top=100,left=400,width=500,height=600')" class="me-3"><i class="fa fa-download"></i> PDF</a>
									<!-- <a href="#" onclick="window.open('<?=site_url('api/kepegawaian/idcard?id='.string_to($id, 'encode').'&file=front')?>', '', 'top=100,left=400,width=500,height=600')" class="me-3"><i class="fa fa-download"></i> Depan</a> -->
									<!-- <a href="#" onclick="window.open('<?=site_url('api/kepegawaian/idcard?id='.string_to($id, 'encode').'&file=end')?>', '', 'top=100,left=400,width=500,height=600')" class="me-3"><i class="fa fa-download"></i> Belakang</a> -->
									<a href="#" onclick="window.open('<?=site_url('api/kepegawaian/idcard?id='.string_to($id, 'encode').'&file=master')?>', '', 'top=100,left=400,width=500,height=600')" class="me-3"><i class="fa fa-download"></i> File Master</a>
								<?php }?>
							</td>
							<td>
								<?php 
								$qrcode = 'assets/img/qr_default.png';
								$last_change = '';
								if(!empty($data))
								{
									$xqr = explode('public/', $data->qrcode);
									$qrcode = $xqr[1];
									$last_change = 'Dibuat pada '.tanggal(substr($data->last_change,0,10),1).', pukul '.substr($data->last_change,10);
								}
								?>
								<img src="<?=base_url($qrcode)?>" class="img-thumbnail">
								<em class="d-block" style="font-size:9pt"><?=$last_change?></em>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
<?php if(return_access_link(['api/kepegawaian/qrcode'])){?>
	$('#btn-qrcode').on('click', function(){
		var status = 0
		<?php if(!empty($data)){?>
			status = 1
		<?php }?>
		if(status==0)
		{
			var cf = confirm('Buat QR Baru?')
			if(cf==true)
			{
				new_hash()
			}
		}else{
			var cf2 = confirm('Apakah Anda benar-benar yakin ingin mengubah QR?..');
			if(cf2==true)
			{
				var person = prompt("Alasan Perubahan:", "");
				if(person == null || person == ""){
					alert("Alasan wajib diisi.");
				}else{
					new_hash('Alasan perubahan: '+person)
				}
			}
		}
	})

	function new_hash(alasan='Pembuatan baru')
	{
		$.post('<?=site_url('api/kepegawaian/qrcode?id='.string_to($id,'encode'))?>', {alasan:alasan}, function(rs){
			load_halaman_in_profile('qrcode', '<?=string_to($id, 'encode')?>')
		})
	}
<?php }?>
</script>