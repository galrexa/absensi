<?php echo form_open_multipart('file/upload');?>
<div class="card mb-2">
	<div class="card-header">
		Upload FIle
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-6">
				<div class="input-group mb-1">
					<div class="input-group-text">
						Prefix:
					</div>
					<input type="text" name="first" value="file_">
				</div>
				<div class="input-group mb-1">
					<div class="input-group-text">
						Output Hasil:
					</div>
					<input type="text" name="output" value="page">
				</div>
				<div class="input-group mb-1">
					<b class="input-group-text">Pilih file:</b>
					<input type="file" name="userfile" class="form-control">
					<?php #echo form_upload('userfile')?>
				</div>
			</div>
			<div class="col-6" style="font-size:10pt;color: blueviolet;">
				<?php
				if(!empty($data)){
					echo json_encode($data);
				}
				?>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<button type="submit">Submit</button>
	</div>
</div>
<?=form_close()?>
<?php 
if(!empty($data_file)){
	echo '<div class="card card-body" style="font-size:9pt;">';
		echo '<ol class="">';
		foreach ($data_file as $k) {
			echo '<li><a href="'.site_url('file/download?id='.string_to($k->id,'encode')).'">'.$k->client_name.'</a></li>';
		}
		echo '</ol>';
	echo '</div>';
}
?>