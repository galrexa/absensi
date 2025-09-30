<?php 

print_r($data)
?>
<!-- <div class="row">
	<div class="col"> -->
<?php foreach ($data as $key) {?>
		<div class="card mb-2">
			<div class="card-header d-flex">
				<div class="flex-grow-1">NIP. <?=$key->id?></div>
				<a href="#">Edit</a>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col">
						<div class="row">
							<div class="col-md-3">
								UserName
							</div>
							<div class="col">
								<?=$key->username?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								Email
							</div>
							<div class="col">
								<?=$key->email?>
							</div>
						</div>
					</div>
					<div class="col">

					</div>
				</div>
			</div>
		</div>
	<!-- </div>
</div> -->
<?php }?>