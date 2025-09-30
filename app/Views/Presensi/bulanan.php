<div class="card">
    <div class="card-header">
        <?=$title?>
    </div>
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2">
                Periode:
            </div>
            <div class="col-sm-12 col-md-10">
                <div class="input-group">
                    <select name="tahun" id="tahun" class="" onchange="load_data(); localStorage.setItem('tahun', this.value)">
                        <?php $arrayTahun =[]; foreach ($list_tahun as $key) {array_push($arrayTahun, $key->tahun);}?>
                        <?php if(!array_keys($arrayTahun, date('Y')) or empty($arrayTahun)){?>
                            <option value="<?=date('Y')?>" selected><?=date('Y')?></option>
                        <?php }?>
                        <?php foreach ($list_tahun as $key) {?>
                            <option value="<?=$key->tahun?>" <?php if($key->tahun==date('Y')){echo'selected';}?>><?=$key->tahun?></option>
                        <?php }?>
                    </select>
                    <select name="bulan" id="bulan" class="" onchange="load_data(); localStorage.setItem('bulan', this.value)">
                        <?php foreach (array_bulan() as $key=>$value) {?>
                            <option value="<?=$key?>" <?php if($key==date('m')){echo'selected';}?>><?=$value?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2">
                Unit Kerja:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="input_unit_kerja[]" id="input_unit_kerja" class="form-control" multiple onchange="load_data()"></select>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2">
                Jabatan:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="input_jabatan[]" id="input_jabatan" class="form-control" multiple onchange="load_data()"></select>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2">
                Perncarian:
            </div>
            <div class="col-sm-12 col-md-10">
                <input type="text" name="search" id="search" class="form-control" placeholder="Nama Pegawai" oninput="load_data()">
            </div>
        </div>
        <?php if(return_access_link(['presensi/bulanan/unduh'])){?>
            <div class="row">
                <div class="col-sm-12 col-md-2 fw-bold"></div>
                <div class="col-sm-12 col-md-10 pt-2">
                    <a href="#" onclick="window.open('<?=site_url('presensi/bulanan/unduh?periode=')?>'+$('#tahun').val()+'-'+$('#bulan').val()+'&unit_kerja_id='+$('#input_unit_kerja').val()+'&jabatan_id='+$('#input_jabatan').val()+'&search='+$('#search').val())" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Unduh data dalam bentuk file excel"><i class="fa fa-file-excel"></i></a>
                    <a href="#" onclick="window.open('<?=site_url('presensi/bulanan/unduh?periode=')?>'+$('#tahun').val()+'-'+$('#bulan').val()+'&unit_kerja_id='+$('#input_unit_kerja').val()+'&jabatan_id='+$('#input_jabatan').val()+'&search='+$('#search').val()+'&file=print')" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Print data"><i class="fa fa-print"></i></a>
                </div>
            </div>
        <?php }?>
        <div class="row">
            <div class="col mb-1">
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-hover table-bordered">
                    	<thead class="">
                    		<tr>
                                <th class="color-dark text-center text-light" rowspan="2"><i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="info detail riwayat presensi perbulan"></i></th>
                                <th class="color-dark text-center text-light" rowspan="2"><i class="fa fa-file-pdf" data-bs-toggle="tooltip" data-bs-placement="bottom" title="pdf detail riwayat presensi perbulan"></i></th>
                                <th class="color-dark text-center text-light" rowspan="2">NAMA</th>
                                <th class="color-dark text-center text-light" rowspan="2">JABATAN</th>
                                <th class="color-dark text-center text-light" rowspan="2">UNIT KERJA</th>
                                <th class="color-dark text-center text-light" colspan="7">HARI KERJA</th>
                                <th class="color-dark text-center text-light" rowspan="2">POTONGAN</th>
                                <th class="color-dark text-center text-light" rowspan="2">KETERANGAN</th>
                    		</tr>
                            <tr>
                                <th class="color-dark text-center text-light">HARI KERJA</th>
                                <th class="color-dark text-center text-light">HADIR</th>
                                <th class="color-dark text-center text-light">TERLAMBAT</th>
                                <th class="color-dark text-center text-light">CEPAT PULANG</th>
                                <th class="color-dark text-center text-light">CUTI/IZIN</th>
                                <th class="color-dark text-center text-light">DINAS</th>
                                <th class="color-dark text-center text-light">TIDAK HADIR</th>
                            </tr>
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
        $('#tahun').val(localStorage.getItem('tahun'))
        $('#bulan').val(localStorage.getItem('bulan'))
		load_data()
        select2_unit_kerja('#input_unit_kerja')
        select2_jabatan('#input_jabatan', '<?=$unit?>')
	})

    function load_data()
    {
        var t = $('#example1').DataTable({
            bDestroy: true,
            bPaginate: true,
            bLengthChange: true,
            bFilter: false,
            bInfo: true,
            bAutoWidth: false,
            processing: true,
            serverSide: true,
            pageLength: 50,
            layout: {
                topEnd: {
                    search: {
                        placeholder: 'NIK, NIP, Nama, HP & Tempat Lahir'
                    }
                }
            },
            ajax: {
                url: '<?=site_url('api/presensi/bulanan')?>',
                type: 'POST',
                data: {
                    unit_kerja: $('#input_unit_kerja').val(),
                    jabatan: $('#input_jabatan').val(),
                    search: $('#search').val(),
                    periode: $('#tahun').val()+'-'+$('#bulan').val(),
                }
            },
            columns: [
                {
                    data: 'pegawai_id',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '';
                            <?php if(return_access_link(['presensi', 'riwayat'])){?>
                                txt_view += '<a class="m-1 fs-4 text-success btn-sm" href="<?=site_url('presensi/riwayat?id=')?>'+row.hash+'&tahun='+$('#tahun').val()+'&bulan='+$('#bulan').val()+'" title="Info detail riwayat presensi perbulan"><i class="fa fa-info-circle"></i></a> '
                            <?php }?>
                        return txt_view
                    }
                },
                {
                    data: 'pegawai_id',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '';
                            <?php if(return_access_link(['presensi/riwayat/unduh'])){?>
                                txt_view += '<a class="m-1 fs-4 text-success btn-sm" href="<?=site_url('presensi/riwayat/unduh?id=')?>'+row.hash+'&periode='+$('#tahun').val()+'-'+$('#bulan').val()+'&file=pdf" target="_blank" title="PDF detail riwayat presensi perbulan"><i class="fa fa-file-pdf"></i></a> '
                            <?php }?>
                        return txt_view
                    }
                },
                {
                    data: 'nama',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'jabatan_name',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'unit_kerja_name_alt',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'hari_kerja',
                    className: 'text-center fw-bold',
                    orderable: true,
                    render: function(data, type, row, index){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'hadir',
                    className: 'text-center fw-bold',
                    orderable: true,
                    render: function(data, type, row, index){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'terlambat',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'mendahului',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'cuti',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'dinas',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'tidak_hadir',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'potongan',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row){
                        var txt_view = 0
                        if(data > 0){
                            txt_view = data +'%'
                        }
                        return txt_view
                    }
                },
                {
                    data: 'keterangan',
                    className: '',
                    orderable: false,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                }
            ],
            order: [[ 2, 'asc' ]]
        })
    }
</script>