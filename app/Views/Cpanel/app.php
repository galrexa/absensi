<div class="row">
	<div class="col-md-8 col-sm-12 mb-2">
		<div class="card">
			<div class="card-header d-flex">
				<span class="flex-grow-1 fw-bold" style="font-size:16pt">
					<?=$title?>
				</span>
				<a href="<?=site_url('app/form')?>" class="btn btn-sm btn-success" title="Tambah aplikasi baru"><i class="fa fa-plus-circle"></i> Tambah</a>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-striped table-hover table-bordered">
					<thead>
						<tr>
							<th width="5%">#</th>
							<th width="5%">#</th>
							<th>Nama Aplikasi</th>
							<th width="20%">Status</th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($data)){?>
							<?php $n=0; foreach ($data as $key){?>
								<?php 
								$n +=1;
								$text_color = 'text-success';
								$text_status = 'Aktif';
								if($key->status < 1)
								{
									$text_color = 'text-danger';
									$text_status = 'Non Aktif';
								}
								?>
								<tr>
									<td><?=$n?></td>
									<td><a href="<?=site_url('app/form?id='.$key->id)?>" class="btn btn-sm btn-success" title="Edit aplikasi"><i class="fa fa-pen"></i></a></td>
									<td>
										<b><?=$key->name?></b>
										<small class="d-block <?=$text_color?>">path://<?=$key->path?></small>
									</td>
									<td class="<?=$text_color?> fw-bold"><?=$text_status?></td>
								</tr>
							<?php }?>
						<?php }else{echo '<tr><td colspan="4">Belum tersedia...</td></tr>';}?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-12 mb-2">
		<div class="card">
			<div class="card-header d-flex">
				<span class="flex-grow-1 fw-bold" style="font-size:16pt">
					Roles
				</span>
				<a href="<?=site_url('app/role/form')?>" class="btn btn-sm btn-success" title="Tambah role baru"><i class="fa fa-plus-circle"></i> Tambah</a>
			</div>
			<div class="card-body">
				<ul>
					<li class="list_roles">loading...</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		getListRoles()
	})

	function getListRoles()
	{
		$('.list_roles').html('<span class="spinner-border"></span> loading...')
		$.get('<?=site_url('api/app/roles')?>', function(d){
			$('.list_roles').html('')
			if(d.status==true)
			{
				$.each(d.data, function(i, row){
					var text_color = '';
					if(row.status != 1)
						text_color = 'text-danger'
					$('.list_roles').append('<li><a href="<?=site_url('app/role/form?id=')?>'+row.id+'" class="'+text_color+'" data-toggle="tooltip" data-placement="top" title="'+row.name+' => '+row.description+'">'+row.name+'</a></li>')
				})
			}
		})
	}
</script>