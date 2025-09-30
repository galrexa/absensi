<?php
$id = 0;
if(isset($data['id'])){
	$id = $data['id'];
}
$link = ($link)?:'detail';
$query_link = '';
if(array_keys(['detail'],$link)){
	$query_link = '?id='.$data['hash'];
}
echo form_open_multipart('persuratan/teruskan?'.$_SERVER['QUERY_STRING'], ['class'=>'form', 'id'=>'form_teruskan_surat'.$id])?>
	<div class="row">
		<div class="col-sm-12 col-md-12 mb-1">
			<?php if(empty($data) || !empty($data)){?>
				<div class="card">
					<div class="card-header fw-bold">
						<?=$title?>
					</div>
					<div class="card-body">
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Teruskan Kepada:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<select name="penerima[]" id="penerima" class="form-control" multiple></select>
								<input type="hidden" name="id" id="id" value="<?=(isset($data['id']))?string_to($data['id'],'encode'):0?>">
							</div>
						</div>
						<div class="row mb-1">
							<div class="col-sm-12 col-md-3">
								<b class="d-block">Catatan:</b>
							</div>
							<div class="col-sm-12 col-md-9">
								<textarea class="form-control summernote" id="catatan" name="catatan"></textarea>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-sm-12 col-md-3"></div>
							<div class="col-sm-12 col-md-9">
								<button id="ID_BTN_SIMPAN" type="submit" class="btn btn-primary btn-simpan mr-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Teruskan Surat"><i class="fa fa-paper-plane"></i> Teruskan Surat</button>
								<button type="reset" class="btn btn-secondary btn-batal" data-bs-dismiss="modal" onclick="window.location.assign('<?=site_url('persuratan/'.$link.$query_link)?>')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Batal Meneruskan"><i class="fa fa-times"></i> Batal</button>
							</div>
						</div>
					</div>
				</div>
			<?php }?>
		</div>
	</div>
<?=form_close()?>
<link href="<?=base_url('assets/')?>css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url('assets/')?>js/select2.full.min.js"></script>
<link href="<?=base_url('assets/vendors/summernote/summernote-lite.css')?>" rel="stylesheet" />
<script src="<?=base_url('assets/vendors/summernote/summernote-lite.min.js')?>"></script>
<script type="text/javascript">
	$(function(){
        select2_pegawai('#penerima')
        var penerima_opt = ''
        <?php if(isset($data['penerima'])){foreach ($data['penerima'] as $key => $value) {?>
        	penerima_opt += '<option value="<?=$value->pegawai_id?>" selected><?=$value->nama .' ('.$value->jabatan_name.')'?></option>'
        <?php }}?>
        $('#penerima').html(penerima_opt).trigger('change')
	})

    $('.summernote').summernote({
        height: 150,
        placeholder: 'Catatan untuk penerima...'
    });

	$('#form_teruskan_surat<?=$id?>').on('submit', function(e){
		$('.btn-batal').attr('disabled', true);
		$('.btn-simpan').attr('disable', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> harap tunggu...')
	    e.preventDefault();
	    var form = $(this);
		$.ajax({
			crossDomain: true,
	        crossOrigin: true,
	        dataType: 'json',
			type: "POST",
			url: form.attr('action'),
			data: form.serialize(),
			success: function(responseData, textStatus, jqXHR) {
				var dt = responseData
				$('input[name=<?=csrf_token()?>]').val(dt.csrf)
				if(dt.status==true)
				{
					window.location.assign('<?=site_url('persuratan/detail?id=')?>'+dt.ID+'&link=<?=$link?>')
				}else{
					alert(dt.message)
					$('.btn-simpan').html('<i class="fa fa-paper-plane"></i> Teruskan Surat').removeAttr('disabled', false);
					$('.btn-batal').removeAttr('disabled', false);
				}
			}
		});
	});
</script>