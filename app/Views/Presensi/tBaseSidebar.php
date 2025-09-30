    <div class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <label class="ml-1 mt-3 mb-1" style="font-weight: bold;">Menu</label>
            <?php if(return_access_link(['presensi/index'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('presensi/index')?>" title="Halaman Presensi"><i class="fas fa-street-view"></i> Presensi</a></li>
            <?php }?>
            <?php if(return_access_link(['presensi/riwayat'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('presensi/riwayat')?>" title="Riwayat Presensi"><i class="fas fa-history"></i> Riwayat</a></li>
            <?php }?>
            <?php if(return_access_link(['presensi/harian'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('presensi/harian')?>" title="Presensi Harian"><i class="fas fa-calendar-check"></i> Presensi Harian</a></li>
            <?php }?>
            <?php if(return_access_link(['presensi/bulanan'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('presensi/bulanan')?>" title="Presensi Bulanan"><i class="fa fa-calendar-alt"></i> Presensi Bulanan</a></li>
            <?php }?>
            <?php if(return_access_link(['presensi/jam_kerja','presensi/hari_libur','presensi/lokasi'])){?>
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-dropdown" data-bs-toggle="collapse" href="#smPengaturan" role="button" aria-expanded="false"><span><i class="fas fa-cog"></i> Pengaturan</span><i class="fas fa-chevron-down dropdown-arrow"></i></a>
                    <div class="collapse submenu" id="smPengaturan">
                        <ul class="sidebar-menu-sub">
                            <?php if(return_access_link(['presensi/jam_kerja'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('presensi/jam_kerja')?>" title="Jam Kerja">Jam Kerja</a></li>
                            <?php }?>
                            <?php if(return_access_link(['presensi/hari_libur'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('presensi/hari_libur')?>" title="Hari Libur">Hari Libur</a></li>
                            <?php }?>
                            <li><hr class="dropdown-divider"></li>
                            <?php if(return_access_link(['presensi/lokasi'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('presensi/lokasi')?>" title="Lokasi atau Asrea">Lokasi atau Area</a></li>
                            <?php }?>
                        </ul>
                    </div>
                </li>
            <?php }?>
        </ul>
    </div>