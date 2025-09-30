<div class="card">
    <div class="card-header">
        <?=$title?>
    </div>
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Jenis cuti:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="jenis_cuti[]" id="jenis_cuti" class="form-control" multiple onchange="load_data('#example1', [1,2,3,4,5,6,7,8], $('#tahun').val()+'-'+$('#bulan').val(), 1);"></select>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Status Proses:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="status_proses[]" id="status_proses" class="form-control" multiple onchange="load_data('#example1', [1,2,3,4,5,6,7,8], $('#tahun').val()+'-'+$('#bulan').val(), 1);"></select>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Status Persetujuan:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="status_persetujuan[]" id="status_persetujuan" class="form-control" multiple onchange="load_data('#example1', [1,2,3,4,5,6,7,8], $('#tahun').val()+'-'+$('#bulan').val(), 1);"></select>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Status Admin:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="status_proses_kepeg[]" id="status_proses_kepeg" class="form-control" multiple onchange="load_data('#example1', [1,2,3,4,5,6,7,8], $('#tahun').val()+'-'+$('#bulan').val(), 1);"></select>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Periode:
            </div>
            <div class="col-sm-12 col-md-10">
                <div class="input-group">
                    <select name="tahun" id="tahun" class="" onchange="load_data('#example1', [1,2,3,4,5,6,7,8], this.value+'-'+$('#bulan').val(), 1)">
                        <?php $arrayTahun =[]; foreach (return_tahun_list() as $key) {array_push($arrayTahun, $key->tahun);}?>
                        <?php if(!array_keys($arrayTahun, date('Y')) or empty($arrayTahun)){?>
                            <option value="<?=date('Y')?>" selected><?=date('Y')?></option>
                        <?php }?>
                        <?php foreach (return_tahun_list() as $key) {?>
                            <option value="<?=$key->tahun?>" <?php if($key->tahun==date('Y')){echo'selected';}?>><?=$key->tahun?></option>
                        <?php }?>
                    </select>
                    <select name="bulan" id="bulan" class="" onchange="load_data('#example1', [1,2,3,4,5,6,7,8], $('#tahun').val()+'-'+this.value, 1)">
                        <?php foreach (array_bulan() as $key=>$value) {?>
                            <option value="<?=$key?>" <?php if($key==date('m')){echo'selected';}?>><?=$value?></option>
                        <?php }?>
                    </select>
                    <label class="ms-2"><input type="checkbox" name="check_filter" id="check_filter" value="1" onchange="load_data('#example1', [1,2,3,4,5,6,7,8], $('#tahun').val()+'-'+$('#bulan').val(), 1)" class="me-2"> Terapkan pada filter</label>
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
                                <th class="text-center color-abu">JENIS CUTI</th>
                                <th class="text-center color-abu">JUMLAH HARI</th>
                                <th class="text-center color-abu">STATUS PROSES</th>
                                <th class="text-center color-abu">STATUS PERSETUJUAN</th>
                                <th class="text-center color-abu">ALASAN / KETERANGAN</th>
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
        select2_referensi('#status_proses', 'cuti_status_proses')
        select2_referensi('#status_persetujuan', 'cuti_status_approval')
        select2_referensi('#status_proses_kepeg', 'cuti_status_proses_kepeg')
        select2_referensi('#jenis_cuti', 'cuti')
        load_data('#example1', [1,2,3,4,5,6,7,8], $('#tahun').val()+'-'+$('#bulan').val(), 1)
	})

    function load_data(element, status, periode='', tab='')
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
                        placeholder: 'Keterangan atau alamat..'
                    }
                }
            },
            ajax: {
                url: '<?=site_url('api/cuti/riwayat')?>',
                type: 'POST',
                data: {
                    pegawai_id: '<?=$pegawai_id?>',
                    status: status,
                    periode: periode,
                    jenis_cuti: $('#jenis_cuti').val(),
                    status_proses: $('#status_proses').val(),
                    status_persetujuan: $('#status_persetujuan').val(),
                    status_proses_kepeg: $('#status_proses_kepeg').val(),
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
                                txt_view += '<a class="fs-6 m-1 text-primary" href="<?=site_url('cuti/detail?id=')?>'+row.hash+'&link=riwayat&tab='+tab+'" data-bs-toggle="tooltip" data-bs-placement="top" title="Info detail cuti"><i class="fa fa-exclamation-circle"></i></a> '
                            <?php }?>
                            if(tab!=2 && row.status < 2){
                                <?php if(return_access_link(['cuti/hapus'])){?>
                                    txt_view += '<a class="fs-6 m-1 text-danger" href="#" onclick="hapus_cuti(\''+row.hash+'\')" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus data"><i class="fa fa-trash"></i></a> '
                                <?php }?>
                                <?php if(return_access_link(['cuti/form'])){?>
                                    txt_view += '<a class="fs-6 m-1 text-success" href="<?=site_url('cuti/form?id=')?>'+row.hash+'&link=riwayat&tab='+tab+'" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit data"><i class="fa fa-edit"></i></a> '
                                <?php }?>
                            }
                        return txt_view
                    }
                },
                {
                    data: 'jenis_cuti',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = row.jenis_cuti_name_alt +' ('+ row.jenis_cuti_name +')'
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

    <?php if(return_access_link(['cuti/hapus'])){?>
        function hapus_cuti(id)
        {
            var cf = confirm('Hapus data ini?')
            if(cf==true)
            {
                $.get('<?=site_url('cuti/hapus')?>', {id:id}, function(r){
                    if(r.status==true)
                    {
                        load_data('#example1', [1,2,3,4,5,6,7,8], '', 1)
                    }else{
                        alert(r.message)
                    }
                })
            }
        }
    <?php }?>
</script>