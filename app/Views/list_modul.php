<div class="module-grid">
	<?php foreach($data_layanan as $r1){
		$icon_app = 'default.png';
		if(file_exists($r1->icon)){
			$xicon = explode('public/assets/img/icons/apps/', $r1->icon);
			$icon_app = $xicon[1];
		}
		?>
		<a href="<?=site_url('routing/?id='.string_to($r1->id,'encode'))?>" class="module-card">
            <div class="module-icon color-dark">
                <img src="<?=base_url('assets/img/icons/apps/'.$icon_app)?>" height="130">
            </div>
            <div class="module-title fw-bold"><?=$r1->name?></div>
        </a>
	<?php }?>
</div>