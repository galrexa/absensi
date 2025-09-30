
    <div class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <label class="ml-1 mt-3 mb-1" style="font-weight: bold;">Menu</label>
            <?php if(return_access_link(['kepegawaian/profile'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/profile')?>" title="Profile"><i class="fas fa-user-circle"></i> Profile</a></li>
            <?php }?>
            <?php if(return_access_link(['kepegawaian/hak_keuangan'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/hak_keuangan')?>" title="Hak Keuangan"><i class="fas fa-money-bill-alt"></i> Hak Keuangan</a></li>
            <?php }?>
            <?php if(return_access_link(['kepegawaian/bukti_potong_pajak'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/bukti_potong_pajak')?>" title="Bukti Potong Pajak"><i class="far fa-file-alt"></i> Bukti Potong Pajak</a></li>
            <?php }?>
            <?php if(return_access_link(['kepegawaian/ulang_tahun'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/ulang_tahun')?>" title="Ulang Tahun"><i class="fa fa-birthday-cake"></i> Ulang Tahun</a></li>
            <?php }?>
            <?php if(return_access_link(['kepegawaian/aktif','kepegawaian/non_aktif','kepegawaian/tim'])){?>
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-dropdown" data-bs-toggle="collapse" href="#dashboardSubmenu" role="button" aria-expanded="false"><span><i class="fas fa-users"></i> Kepegawaian</span><i class="fas fa-chevron-down dropdown-arrow"></i></a>
                    <div class="collapse submenu" id="dashboardSubmenu">
                        <ul class="sidebar-menu-sub">
                            <?php if(return_access_link(['kepegawaian/aktif'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/aktif')?>" title="Pegawai Aktif">Pegawai Aktif</a></li>
                                <?php foreach (session()->get('units') as $key) {?>
                                    <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/aktif?id='.$key->unit_kerja_id)?>" title="<?=$key->unit_kerja_name?>"><i class="fa fa-arrow-right"></i> <?=$key->unit_kerja_name_alt?></a></li>
                                <?php }?>
                            <?php }?>
                            <li><hr class="dropdown-divider"></li>
                            <?php if(return_access_link(['kepegawaian/non_aktif'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/non_aktif')?>" title="Pegawai Non Aktif (Arsip)">Pegawai Non Aktif (Arsip)</a></li>
                            <?php }?>
                            <?php if(return_access_link(['kepegawaian/tim'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/tim')?>" title="Tim Kerja">Tim Kerja</a></li>
                            <?php }?>
                        </ul>
                    </div>
                </li>
            <?php }?>
            <?php if(return_access_link(['kepegawaian/skp_master','kepegawaian/bpp_master'])){?>
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-dropdown" data-bs-toggle="collapse" href="#keuanganSubmenu" role="button" aria-expanded="false"><span><i class="fas fa-money-bill-alt"></i> Keuangan</span><i class="fas fa-chevron-down dropdown-arrow"></i></a>
                    <div class="collapse submenu" id="keuanganSubmenu">
                        <ul class="sidebar-menu-sub">
                            <?php if(return_access_link(['kepegawaian/skp_master'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/skp_master')?>" title="Hak keuangan atau slip gaji">Hak Keuangan</a></li>
                            <?php }?>
                            <?php if(return_access_link(['kepegawaian/bpp_master'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/bpp_master')?>" title="Bukti Potong Pajak">Bukti Potong Pajak</a></li>
                            <?php }?>
                        </ul>
                    </div>
                </li>
            <?php }?>
            <?php if(return_access_link(['kepegawaian/unit','kepegawaian/jabatan','kepegawaian/gugus_tugas','kepegawaian/perguruan_tinggi'])){?>
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-dropdown" data-bs-toggle="collapse" href="#masterdataSubmenu" role="button" aria-expanded="false"><span><i class="fa fa-database"></i> Master Data</span><i class="fas fa-chevron-down dropdown-arrow"></i></a>
                    <div class="collapse submenu" id="masterdataSubmenu">
                        <ul class="sidebar-menu-sub">
                            <?php if(return_access_link(['kepegawaian/unit'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/unit')?>" title="Unit Kerja">Unit Kerja</span></a></li>
                            <?php }?>
                            <?php if(return_access_link(['kepegawaian/jabatan'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/jabatan')?>" title="Jabatan">Jabatan</span></a></li>
                            <?php }?>
                            <?php if(return_access_link(['kepegawaian/gugus_tugas'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/gugus_tugas')?>" title="Gugus Tugas">Gugus Tugas</span></a></li>
                            <?php }?>
                            <?php if(return_access_link(['kepegawaian/perguruan_tinggi'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/perguruan_tinggi')?>" title="Perguruan Tinggi">Perguruan Tinggi</span></a></li>
                            <?php }?>
                        </ul>
                    </div>
                </li>
            <?php }?>
        </ul>
    </div>