<?php echo form_open_multipart();?>
<div class="card">
	<div class="card-header">
		<?=$title?>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-12">
				<div class="input-group mb-1">
					<div class="input-group-text col-2">
						To:
					</div>
					<input type="text" name="to" class="form-control" value="ramadhiantohandiprimastono@gmail.com">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="input-group mb-1">
					<div class="input-group-text col-2">
						Title:
					</div>
					<input type="text" name="title" class="form-control" value="Test mail">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="input-group mb-1">
					<b class="input-group-text col-2">Message:</b>
					<textarea name="message" class="form-control">test</textarea>
				</div>
				<div class="input-group mb-1">
					<b class="input-group-text col-2">Pilih file:</b>
					<input type="file" name="userfile" class="form-control">
					<?php #echo form_upload('userfile')?>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<button type="submit">Submit</button>
	</div>
</div>
<?=form_close()?>
<?php 
// echo json_encode($test_env);
?>