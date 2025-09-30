<div class="card">
    <div class="card-header fw-bold" style="font-size:16pt">
        <?=$title?>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="row">
                    <div class="col-sm-12 col-md-3 mb-1">
                        <b class="d-block">Nomor SK :</b>
                    </div>
                    <div class="col-sm-12 col-md-9 mb-1">
                        <?=$data->nomor_sk?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 mb-1">
                        <b class="d-block">Tanggal SK :</b>
                    </div>
                    <div class="col-sm-12 col-md-9 mb-1">
                        <?=tanggal($data->tgl_sk,1)?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 mb-1">
                        <b class="d-block">Periode :</b>
                    </div>
                    <div class="col-sm-12 col-md-9 mb-1">
                        <?=tanggal_range($data->tgl_awal, $data->tgl_akhir)?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 mb-1">
                        <b class="d-block">Keterangan :</b>
                    </div>
                    <div class="col-sm-12 col-md-9 mb-1">
                        <?=($data->keterangan)?:'-'?>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <b class="d-block">Files :</b>
                <?=link_files_by_id($data->file)?>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12 col-md-12 mb-2">
                <a href="<?=site_url('kepegawaian/tim')?>" class="btn btn-secondary btn-sm" title="Kembali ke List"><i class="fa fa-arrow-left"></i> Kembali</a>
                <?php if(return_access_link(['kepegawaian/tim/form']) and (($data->status_sk==1) or ($data->status_sk==2 && return_roles([1])))){?>
                    <a href="<?=site_url('kepegawaian/tim/form?id='.string_to($data->id_sk_tim,'encode'))?>" class="btn btn-success btn-sm" title="Ubah Data SK TIM"><i class="fa fa-edit"></i> Ubah</a>
                <?php }?>
            </div>
        </div>
        <?php if(($data->status_sk==1) or ($data->status_sk==2 && return_roles([1]))){?>
            <div class="row">
                <div class="col-sm-12 col-md-6 mb-2">
                    <span class="d-block fw-bold">Anggota Internal :</span>
                    <select name="anggota_intern" id="anggota_intern" class="form-control form-control-sm"></select>
                </div>
                <div class="col-sm-12 col-md-6 mb-2">
                    <span class="d-block fw-bold">Anggota Eksternal :</span>
                    <div class="input-group">
                        <select name="anggota_ekstern" id="anggota_ekstern" class="form-control form-control-sm"></select>
                        <a href="#" class="input-group-text"><i class="fa fa-plus-circle"></i></a>
                    </div>
                </div>
            </div>
        <?php }?>
        <div class="row">
            <div class="col-sm-12 col-md-12 table-responsive">
                <table class="table table-sm table-striped">
                    <thead class="bg-primary">
                        <tr>
                            <th width="5%">#</th>
                            <th width="20%">Nama</th>
                            <th width="25%">Jabatan</th>
                            <th width="25%">Unit Kerja</th>
                            <th width="10%">Email</th>
                            <th width="15%">Kontak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        //print_r($data_detail);
                        foreach ($data_detail as $r) {?>
                            <tr>
                                <td>
                                    <?php if(return_access_link(['api/kepegawaian/tim/detail/deleted']) && (($data->status_sk==1) or ($data->status_sk==2 && return_roles([1])))){?>
                                        <a href="<?=site_url('api/kepegawaian/tim/detail/deleted?id='.string_to($r->id, 'encode'))?>" class="text-danger" onclick="var cf = confirm('Hapus data ini?...'); if(cf==true){return true;}else{return false;}"><i class="fa fa-trash"></i></a>
                                    <?php }?>
                                </td>
                                <td><?=$r->nama?></td>
                                <td><?=$r->jabatan?></td>
                                <td><?=$r->unit_kerja?></td>
                                <td><?=$r->email?></td>
                                <td><?=$r->kontak?></td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<link href="<?=base_url('assets/')?>css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url('assets/')?>js/select2.full.min.js"></script>
<script type="text/javascript">
    $(function(){
        select2_pegawai('#anggota_intern')
        select2_member('#anggota_ekstern')
    })

    $('#anggota_intern').on('select2:select', function(){
        $.post('<?=site_url('api/kepegawaian/tim/detail/save')?>', {id_sk:<?=$data->id_sk_tim?>, pegawai_id:this.value, source:1}, function(rs){
            console.log(rs)
            window.location.reload(true)
        })
    })

    $('#anggota_ekstern').on('select2:select', function(){
        $.post('<?=site_url('api/kepegawaian/tim/detail/save')?>', {id_sk:<?=$data->id_sk_tim?>, pegawai_id:this.value, source:2}, function(rs){
            console.log(rs)
            window.location.reload(true)
        })
    })
</script>