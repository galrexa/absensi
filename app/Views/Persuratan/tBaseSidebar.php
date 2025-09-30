

    <div class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <label class="ml-1 mt-3 mb-1" style="font-weight: bold;">Menu</label>
            <?php if(return_access_link(['persuratan/beranda'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('persuratan/beranda')?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Beranda"><i class="fas fa-home"></i> Beranda</a></li>
            <?php }?>
            <?php if(return_access_link(['persuratan/compose'])){?>
                <li class="sidebar-item fw-bold fs-6"><a class="sidebar-link" href="<?=site_url('persuratan/compose')?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Compose"><i class="fas fa-edit"></i> Compose</a></li>
            <?php }?>
            <?php if(return_access_link(['persuratan/inbox'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('persuratan/inbox')?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Inbox"><i class="fas fa-inbox"></i> Inbox</a></li>
            <?php }?>
            <?php if(return_access_link(['persuratan/review'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('persuratan/review')?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Review"><i class="fas fa-check-circle"></i> Review</a></li>
            <?php }?>
            <?php if(return_access_link(['persuratan/draft'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('persuratan/draft')?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Draft"><i class="fa fa-clipboard"></i> Draft</a></li>
            <?php }?>
            <?php if(return_access_link(['persuratan/sent'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('persuratan/sent')?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Sent"><i class="fa fa-paper-plane"></i> Sent</a></li>
            <?php }?>
            <?php if(return_access_link(['persuratan/register'])){?>
                <hr>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('persuratan/register')?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Register Surat"><i class="fa fa-registered"></i> Register Surat</a></li>
            <?php }?>
            <?php /* if(return_access_link(['cuti/master/saldo'])){?>
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-dropdown" data-bs-toggle="collapse" href="#smPengaturan" role="button" aria-expanded="false"><span><i class="fas fa-cog"></i> Master Data</span><i class="fas fa-chevron-down dropdown-arrow"></i></a>
                    <div class="collapse submenu" id="smPengaturan">
                        <ul class="sidebar-menu-sub">
                            <?php if(return_access_link(['cuti/master/saldo'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('cuti/master/saldo')?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Saldo cuti">Saldo Cuti</a></li>
                            <?php }?>
                        </ul>
                    </div>
                </li>
            <?php } */ ?>
        </ul>
    </div>