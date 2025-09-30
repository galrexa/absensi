<?=form_open('', ['id'=>'app_form', 'class'=>'form']);
$id = 0;
$icon_app = '';
if(!empty($data))
{
	$id = $data->id;
	if(file_exists($data->icon))
	{
		$xicon = explode('public/assets/img/icons/apps/', $data->icon);
		$icon_app = $xicon[1];
	}
}
?>
<div class="row">
	<div class="col-sm-12 col-md-6">
		<div class="card">
			<div class="card-header d-flex">
				<div class="flex-grow-1"><?=$title?></div>
				<a href="<?=site_url('app')?>" class="btn btn-sm btn-danger" title="Keluar halaman ini"><i class="fa fa-times-circle"></i></a>
			</div>
			<div class="card-body">
				<div class="row mb-3">
					<div class="col-md-12 col-sm-12">
						<span class="d-block fw-bold">Name :</span>
						<input type="text" name="name" class="form-control" value="<?=(isset($data))?$data->name:(!empty($_POST)?$_POST['name']:'')?>">
						<input type="hidden" name="id" value="<?=$id?>">
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-md-12 col-sm-12">
						<span class="d-block fw-bold">Path :</span>
						<input type="text" name="path" class="form-control" value="<?=(isset($data))?$data->path:(!empty($_POST)?$_POST['path']:'')?>">
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-md-12 col-sm-12">
						<span class="d-block fw-bold">Status :</span>
						<label class="ms-3"><input type="radio" value="1" name="status" <?php if(((isset($data))?$data->status:1)==1){echo 'checked';}?>> Aktif</label>
						<label class="ms-3"><input type="radio" value="0" name="status" <?php if(((isset($data))?$data->status:1)==0){echo 'checked';}?>> Non Aktif</label>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-md-12 col-sm-12">
						<span class="d-block fw-bold">Description :</span>
						<textarea name="description" class="form-control summernote	"><?=(isset($data))?$data->description:(!empty($_POST)?$_POST['description']:'')?></textarea>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-md-12 col-sm-12">
						<button type="submit" class="btn_login"><i class="fa fa-save"></i> Submit</button>
					</div>
				</div>
				<?php if(!empty($data)){?>
					<div class="row border-top">
						<div class="col-md-8 col-sm-12">
							<span class="d-block fw-bold">Icon App:</span>
							<input type="file" name="icon_app" id="icon_app" class="d-block btn btn-success">
						</div>
						<div class="col-md-4 col-sm-12" align="center">
							<?php if($icon_app<>''){?>
								<img src="<?=base_url('assets/img/icons/apps/'.$icon_app)?>" height="100">
							<?php }?>
						</div>
					</div>
				<?php }?>
			</div>
		</div>
	</div>
	<div class="col-sm-12 col-md-6">
		<div class="card">
			<div class="card-header d-flex">
				<div class="flex-grow-1">Modules</div>
				<?php if($id>0){?>
					<a href="<?=site_url('app/module/form?app='.$id)?>" class="btn btn-sm btn-success" title="Tambah modul baru"><i class="fa fa-plus-circle"></i> Tambah</a>
				<?php }?>
			</div>
			<div class="card-body">
				<div id="modules<?=$id?>" class="" style="font-size: 11pt;"></div>
			</div>
		</div>
	</div>
</div>
<?=form_close()?>
<link href="<?=base_url('assets/vendors/summernote/summernote-lite.css')?>" rel="stylesheet" />
<script src="<?=base_url('assets/vendors/summernote/summernote-lite.min.js')?>"></script>
<script type="text/javascript">
	$(function(){
		getListModulesApp('<?=$id?>', 'modules<?=$id?>')
	})

	$('.summernote').summernote({
		height: 300
	});

	$('#icon_app').on('change', function(){
		file_upload_form_whit_query('#icon_app', 'icon_app', '?app_id=<?=(isset($data))?$data->id:(!empty($_POST)?$_POST['path']:'')?>')
		setTimeout(() => window.location.reload(true), 500)
	})
	
	$('#app_form').on('submit', function(e)
	{
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
					window.location.assign('<?=site_url('app/form?id=')?>'+dt.id);
				}else{
					alert(dt.message.replace(/<p>|<\/p>/g, ""));
				}
				$('.btn_login').prop('disabled', false).html('Submit');
				$('input[name=<?=csrf_token()?>]').val(dt.csrf);
			}
		});
	});

	function getListModulesApp(id, element)
	{
		$('#'+element).html('<span class="spinner-border"></span> loading...')
		$.get('<?=site_url('api/app/modules?id=')?>'+id, function(d){
			if(d.status==true)
			{
				$('#'+element).html('')
				$.each(d.data, function(i, row){
					var text_color = '';
					var text_status = '';
					if(row.status != 1)
						text_color = 'text-danger'
						text_status = 'Function is Non Aktif'
					if(row.id_parent==0)
						$('#'+element).append('<div class="mb-3" id="id_parent'+row.id+'"><a href="#" class="m-2 '+text_color+' fw-bold" onclick="window.location.assign(\'<?=site_url('app/module/form?id=')?>'+row.id+'&app='+row.id_app+'\')" title="'+row.description+'">{'+row.name+'}</a> =></div>')
				})
				$.each(d.data, function(i, row){
					var text_color = '';
					var text_status = '';
					if(row.status != 1)
						text_color = 'text-danger'
						text_status = 'Function is Non Aktif'
					if(row.id_parent != 0)
						$('#id_parent'+row.id_parent).append('<a href="#" class="m-2 '+text_color+'" onclick="window.location.assign(\'<?=site_url('app/module/form?id=')?>'+row.id+'&app='+row.id_app+'\')" title="'+row.description+'">'+row.name+'</a>')
				})
			}else{
				$('#'+element).html('Not found')
			}
		})
	}
</script>