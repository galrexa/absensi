<div class="card">
    <div class="card-header d-flex">
        <span class="flex-grow-1">
            <?=$title?>
        </span>
        <a href="<?=site_url('persuratan/register/form')?>" class="btn btn-sm btn-success" title="Tambah register baru"><i class="fa fa-plus-circle"></i> Tambah</a>
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
                Sifat:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="sifat[]" id="sifat" class="form-control" multiple onchange="load_data('#example1', [1,2,3,4,5,6,7,8]);"></select>
            </div>
        </div> 
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Urgensi:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="urgensi[]" id="urgensi" class="form-control" multiple onchange="load_data('#example1', [1,2,3,4,5,6,7,8]);"></select>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Status Surat:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="status[]" id="status" class="form-control" multiple onchange="load_data('#example1', [1,2,3,4,5,6,7,8]);"></select>
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
                    <label class="ms-2"><input type="checkbox" name="check_filter" id="check_filter" value="1" onchange="load_data('#example1', [1,2,3,4,5,6,7,8])" class="me-2"> Terapkan pada filter periode</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-hover table-bordered">
                        <thead class="">
                            <tr>
                                <th class="text-center color-dark text-white">#</th>
                                <th class="text-center color-dark text-white">Register</th>
                                <th class="text-center color-dark text-white">Sifat</th>
                                <th class="text-center color-dark text-white">Urgensi</th>
                                <th class="text-center color-dark text-white">Informasi Surat</th>
                                <th class="text-center color-dark text-white">Tanggal Surat</th>
                                <th class="text-center color-dark text-white">Pengirim</th>
                                <th class="text-center color-dark text-white">Status</th>
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
        select2_referensi('#status', 'surat_status_ext')
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
                url: '<?=site_url('api/persuratan/register')?>',
                type: 'POST',
                data: {
                    tahun: $('#tahun').val(),
                    bulan: $('#bulan').val(),
                    jenis: $('#jenis').val(),
                    sifat: $('#sifat').val(),
                    urgensi: $('#urgensi').val(),
                    status: $('#status').val(),
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
                            <?php if(return_access_link(['persuratan/detail'])){?>
                                txt_view += '<a class="fs-6 m-1 text-primary" href="<?=site_url('persuratan/detail?id=')?>'+row.hash+'&link=register&tab='+tab+'" title="Info detail cuti"><i class="fa fa-exclamation-circle"></i></a> '
                            <?php }?>
                            if(row.status < 2){
                                <?php if(return_access_link(['persuratan/hapus'])){?>
                                    txt_view += '<a class="fs-6 m-1 text-danger" href="#" onclick="hapus_cuti(\''+row.hash+'\')" title="Hapus data"><i class="fa fa-trash"></i></a> '
                                <?php }?>
                                <?php if(return_access_link(['persuratan/register/form'])){?>
                                    txt_view += '<a class="fs-6 m-1 text-success" href="<?=site_url('persuratan/register/form?id=')?>'+row.hash+'&link=register&tab='+tab+'" title="Edit data"><i class="fa fa-edit"></i></a> '
                                <?php }?>
                            }
                        return txt_view
                    }
                },
                {
                    data: 'register_number',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = data
                        txt_view += '<i class="d-block border-top pt-2" style="font-size:8pt; color:blue">'+row.register_time+'</i>'
                        return txt_view
                    }
                },
                {
                    data: 'sifat',
                    className: 'text-center',
                    orderable: true,
                    render: function(data, type, row, index){
                        var txt_view = row.sifat_name
                        return txt_view
                    }
                },
                {
                    data: 'urgensi',
                    className: 'text-center',
                    orderable: true,
                    render: function(data, type, row, index){
                        var txt_view = row.urgensi_name
                        return txt_view
                    }
                },
                {
                    data: 'nomor',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = '<b>No: '+row.nomor+'</b>'
                        txt_view += '<div><b>Hal:</b> '+row.hal+'</div>'
                        if(row.penerima){
                            var penerima = []
                            $.each(row.penerima, function(i, r){
                                penerima.push(r.nama+' ('+r.jabatan_name+')')
                            })
                        }
                        if(penerima.length < 3){
                            txt_view += '<div><b>Penerima('+row.penerima_sebagai+'):</b> '+penerima.join(', ')+'</div>'
                        }else{
                            txt_view += '<div><b>Penerima('+row.penerima_sebagai+'):</b> '+penerima.join(', ')+'<a href="#" class="d-block">lihat lebih banyak...</a></div>'
                        }
                        return txt_view
                    }
                },
                {
                    data: 'tanggal',
                    className: 'text-center',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'pengirim',
                    className: '',
                    orderable: true,
                    render: function(data, type, row, index){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'status',
                    className: 'fw-bold',
                    orderable: true,
                    render: function(data, type, row, index){
                        var txt_view = row.status_name
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