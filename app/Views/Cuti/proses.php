<div class="card">
    <div class="card-header">
        <?=$title?>
    </div>
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Unit Kerja:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="input_unit_kerja[]" id="input_unit_kerja" class="form-control" multiple onchange="load_data('#example1', [7], '', 1); load_data('#example2', [8], $('#tahun').val()+'-'+$('#bulan').val(), 2);"></select>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Jabatan:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="input_jabatan[]" id="input_jabatan" class="form-control" multiple onchange="load_data('#example1', [7], '', 1); load_data('#example2', [8], $('#tahun').val()+'-'+$('#bulan').val(), 2);"></select>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Status Admin:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="status_proses_kepeg[]" id="status_proses_kepeg" class="form-control" multiple onchange="load_data('#example1', [7], '', 1); load_data('#example2', [8], $('#tahun').val()+'-'+$('#bulan').val(), 2);"></select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-2 fw-bold">
                Periode:
            </div>
            <div class="col-sm-12 col-md-5">
                <div class="input-group">
                    <select name="tahun" id="tahun" class="" onchange="load_data('#example2', [8], this.value+'-'+$('#bulan').val(), 2)">
                        <?php $arrayTahun =[]; foreach (return_tahun_list() as $key) {array_push($arrayTahun, $key->tahun);}?>
                        <?php if(!array_keys($arrayTahun, date('Y')) or empty($arrayTahun)){?>
                            <option value="<?=date('Y')?>" selected><?=date('Y')?></option>
                        <?php }?>
                        <?php foreach (return_tahun_list() as $key) {?>
                            <option value="<?=$key->tahun?>" <?php if($key->tahun==date('Y')){echo'selected';}?>><?=$key->tahun?></option>
                        <?php }?>
                    </select>
                    <select name="bulan" id="bulan" class="" onchange="load_data('#example2', [8], $('#tahun').val()+'-'+this.value, 2)">
                        <?php foreach (array_bulan() as $key=>$value) {?>
                            <option value="<?=$key?>" <?php if($key==date('m')){echo'selected';}?>><?=$value?></option>
                        <?php }?>
                    </select>
                    <label class="ms-2"><input type="checkbox" name="check_filter" id="check_filter" value="1" onchange="load_data('#example2', [6,7,8], $('#tahun').val()+'-'+$('#bulan').val(), 2)"> Terapkan filter periode</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <li class="nav-item"><a class="nav-link <?php if(array_keys(['',1], $tab)){echo'active';}?>" data-bs-toggle="tab" href="#navid1" onclick="load_data('#example1', [7], '', 1)">Menunggu Proses</a></li>
                    <li class="nav-item"><a class="nav-link <?php if($tab==2){echo'active';}?>" data-bs-toggle="tab" href="#navid2" onclick="load_data('#example2', [8], $('#tahun').val()+'-'+$('#bulan').val(), 2)">Sudah Proses</a></li>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade <?php if(array_keys(['',1], $tab)){echo'show active';}?> pt-3" id="navid1">
                        <div class="table-responsive">
                            <table id="example1" class="table table-striped table-hover table-bordered">
                                <thead class="">
                                    <tr>
                                        <th class="text-center color-dark text-light">#</th>
                                        <th class="text-center color-dark text-light">NAMA</th>
                                        <th class="text-center color-dark text-light">JABATAN</th>
                                        <th class="text-center color-dark text-light">UNIT KERJA</th>
                                        <th class="text-center color-dark text-light">JENIS CUTI</th>
                                        <th class="text-center color-dark text-light">JUMLAH</th>
                                        <th class="text-center color-dark text-light">STATUS</th>
                                </thead>
                                <tbody style="vertical-align: top;"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade <?php if($tab==2){echo'show active';}?> pt-3" id="navid2">
                        <div class="table-responsive">
                            <table id="example2" class="table table-striped table-hover table-bordered">
                                <thead class="">
                                    <tr>
                                        <th class="text-center color-dark text-light">#</th>
                                        <th class="text-center color-dark text-light">NAMA</th>
                                        <th class="text-center color-dark text-light">JABATAN</th>
                                        <th class="text-center color-dark text-light">UNIT KERJA</th>
                                        <th class="text-center color-dark text-light">JENIS CUTI</th>
                                        <th class="text-center color-dark text-light">JUMLAH</th>
                                        <th class="text-center color-dark text-light">STATUS</th>
                                </thead>
                                <tbody style="vertical-align: top;"></tbody>
                            </table>
                        </div>
                    </div>
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
        select2_unit_kerja('#input_unit_kerja')
        select2_jabatan('#input_jabatan', '')
        select2_referensi('#status_proses_kepeg', 'cuti_status_proses_kepeg')
		load_data('#example1', [7])
        <?php if(array_keys(['',1], $tab)){?>
            load_data('#example1', [7], '', 1)
        <?php }else{?>
            load_data('#example2', [8], $('#tahun').val()+'-'+$('#bulan').val(), 2)
        <?php }?>
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
                        placeholder: 'Nama, nomor surat, keterangan, atau alamat'
                    }
                }
            },
            ajax: {
                url: '<?=site_url('api/cuti/proses')?>',
                type: 'POST',
                data: {
                    status: status,
                    periode: periode,
                    jabatan: $('#input_jabatan').val(),
                    unit_kerja: $('#input_unit_kerja').val(),
                    status_proses_kepeg: $('#status_proses_kepeg').val(),
                    check_filter: $('#check_filter:checked').val(),
                }
            },
            columns: [
                {
                    data: 'id',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '';
                        if(tab==2){
                            <?php if(return_access_link(['cuti/detail'])){?>
                                txt_view += '<a style="font-size:16pt" class="m-1 text-primary" href="<?=site_url('cuti/detail?id=')?>'+row.hash+'&link=proses&tab='+tab+'" title="Info detail cuti"><i class="fa fa-exclamation-circle"></i></a> '
                            <?php }?>
                        }else{
                            <?php if(return_access_link(['cuti/form/proses'])){?>
                                txt_view += '<a style="font-size:16pt" class="m-1 text-success" href="<?=site_url('cuti/form/proses?id=')?>'+row.hash+'&link=riwayat&tab='+tab+'" title="Proses data"><i class="fa fa-check-circle"></i></a> '
                            <?php }?>
                        }
                        return txt_view
                    }
                },
                {
                    data: 'nama',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = data
                        if(row.status_kepeg==1){
                            txt_view += '<i class="d-block" style="font-size:8pt;">Masuk: '+row.sent_time_kepeg+'</i>'
                        }else{
                            txt_view += '<i class="d-block" style="font-size:8pt;">Proses: '+row.respon_time_kepeg+'</i>'
                        }
                        return txt_view
                    }
                },
                {
                    data: 'jabatan_id',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = row.jabatan_name
                        return txt_view
                    }
                },
                {
                    data: 'unit_kerja_id',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = row.unit_kerja_name_alt
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
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = data +' Hari'
                        return txt_view
                    }
                },
                {
                    data: 'status',
                    className: 'text-center',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = ''
                        if(row.status_kepeg==1){
                            txt_view += '<b>'+ row.status_name +'</b>'
                        }else{
                            txt_view += '<b>'+ row.status_kepeg_name +'</b>'
                            if(row.status_kepeg>2){
                                txt_view += '<i class="d-block border-top" style="font-size:8pt;">'+ row.catatan_kepeg +'</i>'
                            }
                        }
                        return txt_view
                    }
                },
            ],
            order: [[ 0, 'asc' ]]
        })
    }
</script>