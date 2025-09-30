

    <div class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <label class="ml-1 mt-3 mb-1" style="font-weight: bold;">Menu</label>
            <?php if(return_access_link(['cuti/beranda'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('cuti/beranda')?>" title="Beranda"><i class="fas fa-home"></i> Beranda</a></li>
            <?php }?>
            <?php if(return_access_link(['cuti/form'])){?>
                <li class="sidebar-item fw-bold fs-6"><a class="sidebar-link" href="<?=site_url('cuti/form')?>" title="Tambah Draft Cuti Baru"><i class="fas fa-edit"></i> Draft Cuti</a></li>
            <?php }?>
            <?php if(return_access_link(['cuti/riwayat'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('cuti/riwayat')?>" title="Riwayat Cuti"><i class="fas fa-history"></i> Riwayat Cuti</a></li>
            <?php }?>
            <?php if(return_access_link(['cuti/permohonan'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('cuti/permohonan')?>" title="Permohonan Cuti"><i class="fa fa-inbox"></i> Permohonan Cuti</a></li>
            <?php }?>
            <?php if(return_access_link(['cuti/proses'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('cuti/proses')?>" title="Proses Cuti"><i class="fa fa-link"></i> Proses Cuti</a></li>
            <?php }?>
            <?php if(return_access_link(['cuti/master/saldo'])){?>
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-dropdown" data-bs-toggle="collapse" href="#smPengaturan" role="button" aria-expanded="false"><span><i class="fas fa-cog"></i> Master Data</span><i class="fas fa-chevron-down dropdown-arrow"></i></a>
                    <div class="collapse submenu" id="smPengaturan">
                        <ul class="sidebar-menu-sub">
                            <?php if(return_access_link(['cuti/master/saldo'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('cuti/master/saldo')?>" title="Saldo cuti">Saldo Cuti</a></li>
                            <?php }?>
                        </ul>
                    </div>
                </li>
            <?php }?>
        </ul>
    </div>