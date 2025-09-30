<div class="card">
    <div class="card-header">
        <?=$title?>
    </div>
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Jenis Naskah:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="jenis[]" id="jenis" class="form-control" multiple onchange="load_data('#example1', [1,2,3,4,5,6,7,8]);"></select>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Pengamanan:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="sifat[]" id="sifat" class="form-control" multiple onchange="load_data('#example1', [1,2,3,4,5,6,7,8]);"></select>
            </div>
        </div> 
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Penyampaian:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="urgensi[]" id="urgensi" class="form-control" multiple onchange="load_data('#example1', [1,2,3,4,5,6,7,8]);"></select>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Periode:
            </div>
            <div class="col-sm-12 col-md-10">
                <div class="input-group">
                    <select name="tahun" id="tahun" class="" onchange="load_data('#example1', [1,2,3,4,5,6,7,8])">
                        <?php $arrayTahun =[]; foreach (return_tahun_list() as $key) {array_push($arrayTahun, $key->tahun);}?>
                        <?php if(!array_keys($arrayTahun, date('Y')) or empty($arrayTahun)){?>
                            <option value="<?=date('Y')?>" selected><?=date('Y')?></option>
                        <?php }?>
                        <?php foreach (return_tahun_list() as $key) {?>
                            <option value="<?=$key->tahun?>" <?php if($key->tahun==date('Y')){echo'selected';}?>><?=$key->tahun?></option>
                        <?php }?>
                    </select>
                    <select name="bulan" id="bulan" class="" onchange="load_data('#example1', [1,2,3,4,5,6,7,8])">
                        <?php foreach (array_bulan() as $key=>$value) {?>
                            <option value="<?=$key?>" <?php if($key==date('m')){echo'selected';}?>><?=$value?></option>
                        <?php }?>
                    </select>
                    <label class="ms-2"><input type="checkbox" name="check_filter" id="check_filter" value="1" onchange="load_data('#example1', [1,2,3,4,5,6,7,8])" class="me-2"> Terapkan pada filter</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-hover table-bordered">
                        <thead class="">
                            <tr>
                                <th class="text-center color-abu">#</th>
                                <th class="text-center color-abu">Register</th>
                                <th class="text-center color-abu">Sebagai</th>
                                <th class="text-center color-abu">Pengamanan/Penyampaian</th>
                                <th class="text-center color-abu">Informasi</th>
                                <th class="text-center color-abu">Tanggal Surat</th>
                                <th class="text-center color-abu">Pengirim</th>
                                <th class="text-center color-abu">Status</th>
                        </thead>
                        <tbody style="vertical-align: top;"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<link href="<?=base_url()?>assets/css/datatables.min.css" rel="stylesheet" />
<script src="<?=base_url()?>assets/js/datatables.min.js"></script>
<link href="<?=base_url('assets/')?>css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url('assets/')?>js/select2.full.min.js"></script>
<script src="<?=base_url('assets/js/custom.js')?>"></script>
<script type="text/javascript">
    $(function(){
        select2_referensi('#sifat', 'surat_sifat')
        select2_referensi('#urgensi', 'surat_urgensi')
        select2_referensi('#jenis', 'surat_jenis')
        load_data('#example1', [1,2,3,4,5,6,7,8])
    })

    function load_data(element, status, tab='')
    {
        var t = $(element).DataTable({
            bDestroy: true,
            bPaginate: true,
            bLengthChange: true,
            bFilter: true,
            bInfo: true,
            bAutoWidth: false,
            processing: true,
            serverSide: true,
            pageLength: 50,
            layout: {
                topEnd: {
                    search: {
                        placeholder: 'Keterangan ...'
                    }
                }
            },
            ajax: {
                url: '<?=site_url('api/persuratan/inbox')?>',
                type: 'POST',
                data: {
                    pegawai_id: '<?=$pegawai_id?>',
                    status: status,
                    tahun: $('#tahun').val(),
                    bulan: $('#bulan').val(),
                    jenis: $('#jenis').val(),
                    sifat: $('#sifat').val(),
                    urgensi: $('#urgensi').val(),
                    check_filter: $('#check_filter:checked').val()
                }
            },
            columns: [
                {
                    data: 'id',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '';
                            <?php if(return_access_link(['cuti/detail'])){?>
                                txt_view += '<a class="fs-6 m-1 text-primary" href="<?=site_url('cuti/detail?id=')?>'+row.hash+'&link=riwayat&tab='+tab+'" title="Info detail cuti"><i class="fa fa-exclamation-circle"></i></a> '
                            <?php }?>
                            if(row.status < 2){
                                <?php if(return_access_link(['persuratan/hapus'])){?>
                                    txt_view += '<a class="fs-6 m-1 text-danger" href="#" onclick="hapus_cuti(\''+row.hash+'\')" title="Hapus data"><i class="fa fa-trash"></i></a> '
                                <?php }?>
                                <?php if(return_access_link(['cuti/form'])){?>
                                    txt_view += '<a class="fs-6 m-1 text-success" href="<?=site_url('cuti/form?id=')?>'+row.hash+'&link=riwayat&tab='+tab+'" title="Edit data"><i class="fa fa-edit"></i></a> '
                                <?php }?>
                            }
                        return txt_view
                    }
                },
                {
                    data: 'jenis',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = row.jenis_name_alt +' ('+ row.jenis_name +')'
                        return txt_view
                    }
                },
                {
                    data: 'jumlah',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row){
                        var txt_view = data +' Hari'
                        return txt_view
                    }
                },
                {
                    data: 'status',
                    className: 'text-center fw-bold',
                    orderable: true,
                    render: function(data, type, row, index){
                        var txt_view = row.status_name
                        return txt_view
                    }
                },
                {
                    data: 'status_approval',
                    className: 'text-center',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = '<b>'+row.status_approval_name+'</b>'
                        if(row.status_approval > 2){
                            txt_view += '<small class="d-block border-top">'+row.catatan_pimpinan+'</small>'
                        }
                        return txt_view
                    }
                },
                {
                    data: 'status_kepeg',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row){
                        var txt_view = '<b>'+row.status_kepeg_name+'</b>'
                        if(row.status_kepeg > 2){
                            txt_view += '<small class="d-block border-top">'+row.catatan_kepeg+'</small>'
                        }
                        return txt_view
                    }
                },
                {
                    data: 'keterangan',
                    className: '',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'keterangan',
                    className: '',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = data
                        return txt_view
                    }
                },
            ],
            order: [[ 0, 'desc' ]]
        })
    }

    <?php if(return_access_link(['persuratan/hapus'])){?>
        function hapus_cuti(id)
        {
            var cf = confirm('Hapus data ini?')
            if(cf==true)
            {
                $.get('<?=site_url('persuratan/hapus')?>', {id:id}, function(r){
                    if(r.status==true)
                    {
                        load_data('#example1', [1,2,3,4,5,6,7,8])
                    }else{
                        alert(r.message)
                    }
                })
            }
        }
    <?php }?>
</script>