<div class="card">
    <div class="card-header">
        <?=$title?>
    </div>
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2">
                Tanggal:
            </div>
            <div class="col-sm-12 col-md-10">
                <input type="text" name="tanggal" id="tanggal" value="<?=date('Y-m-d')?>" onchange="load_data()" class="datepicker"></td>
            </div>
        </div>
                
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2">
                Unit Kerja:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="input_unit_kerja[]" id="input_unit_kerja" class="form-control" multiple onchange="load_data()"></select></td>
            </div>
        </div>
                
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2">
                Jabatan:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="input_jabatan[]" id="input_jabatan" class="form-control" multiple onchange="load_data()"></select></td>
            </div>
        </div>  
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2">
                Pencarian:
            </div>
            <div class="col-sm-12 col-md-10">
                <input type="text" name="search" id="search" class="form-control" placeholder="Nama Pegawai" oninput="load_data()"></td>
            </div>
        </div> 
        <div class="row">
            <div class="col-sm-12 col-md-2 fw-bold"></div>
            <div class="col-sm-12 col-md-10 pt-2">
                <a href="#" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal_tte"><i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Informasi Kode Presensi"></i></a>
                <?php if(return_access_link(['presensi/harian/unduh'])){?>
                    <a href="#" onclick="window.open('<?=site_url('presensi/harian/unduh?tanggal=')?>'+$('#tanggal').val()+'&unit_kerja_id='+$('#input_unit_kerja').val()+'&jabatan_id='+$('#input_jabatan').val()+'&search='+$('#search').val())" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Unduh data dalam bentuk file excel"><i class="fa fa-file-excel"></i></a>
                    <a href="#" onclick="window.open('<?=site_url('presensi/harian/unduh?tanggal=')?>'+$('#tanggal').val()+'&unit_kerja_id='+$('#input_unit_kerja').val()+'&jabatan_id='+$('#input_jabatan').val()+'&search='+$('#search').val()+'&file=pdf')" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Print data"><i class="fa fa-print"></i></a>
                <?php }?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-hover table-bordered">
                    	<thead class="">
                            <tr>
                                <th class="color-dark text-center text-light" rowspan="2"><i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="info detail presensi"></i></th>
                                <th class="color-dark text-center text-light" rowspan="2">PEGAWAI</th>
                                <th class="color-dark text-center text-light" rowspan="2">JABATAN</th>
                                <th class="color-dark text-center text-light" rowspan="2">UNIT KERJA</th>
                                <th class="color-dark text-center text-light" colspan="9">LOG PRESENSI</th>
                                <th class="color-dark text-center text-light" colspan="3">KETERANGAN</th>
                            </tr>
                            <tr>
                                <th class="color-dark text-center text-light">MULAI</th>
                                <th class="color-dark text-center text-light">FLEXI</th>
                                <th class="color-dark text-center text-light">TERLAMBAT</th>
                                <th class="color-dark text-center text-light">SELESAI</th>
                                <th class="color-dark text-center text-light">CEPAT PULANG</th>
                                <th class="color-dark text-center text-light">DURASI</th>
                                <th class="color-dark text-center text-light">ISTIRAHAT</th>
                                <th class="color-dark text-center text-light">DURASI KERJA</th>
                                <th class="color-dark text-center text-light">DURASI KERJA HARIAN</th>
                                <th class="color-dark text-center text-light">PRESENSI</th>
                                <th class="color-dark text-center text-light">LOKASI MULAI</th>
                                <th class="color-dark text-center text-light">LOKASI SELESAI</th>
                            </tr>
                    	</thead>
                    	<tbody style="vertical-align: top;"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal modal-lg" tabindex="-1" id="modal_tte" data-bs-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header color-abu fw-bold" id="modal_tte_header">
                        <h5 class="modal-title">Informasi Kode</h5>
                        <button type="button" class="bg-danger btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6" style="font-size:9pt;">
                                <b>Presensi:</b>
                                <ul>
                                    <?php foreach(return_referensi_list('absen') as $k){?>
                                        <li><b><?=$k->ref_name?></b>: <?=$k->ref_description?></li>
                                    <?php }?>
                                </ul>
                            </div>
                            <div class="col-sm-12 col-md-6" style="font-size:9pt;">
                                <b>Cuti & Dinas:</b>
                                <ul>
                                    <?php foreach(return_referensi_list('cuti') as $k){?>
                                        <li><b><?=$k->ref_name?></b>: <?=$k->ref_description?></li>
                                    <?php }?>
                                    <?php foreach(return_referensi_list('dinas') as $k){?>
                                        <li><b><?=$k->ref_name?></b>: <?=$k->ref_description?></li>
                                    <?php }?>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12" style="font-size:9pt;">
                                <b>Pelanggaran:</b>
                                <ul>
                                    <?php foreach(return_referensi_list('pelanggaran') as $k){?>
                                        <li><b><?=$k->ref_name?></b>: <?=$k->ref_description?></li>
                                    <?php }?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link href="<?=base_url('assets/vendors/bootstrap-datepicker/css/bootstrap-datepicker.min.css')?>" rel="stylesheet" />
<script src="<?=base_url('assets/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<link href="<?=base_url()?>assets/css/datatables.min.css" rel="stylesheet" />
<script src="<?=base_url()?>assets/js/datatables.min.js"></script>
<link href="<?=base_url('assets/')?>css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url('assets/')?>js/select2.full.min.js"></script>
<script src="<?=base_url('assets/js/custom.js')?>"></script>
<script type="text/javascript">

    $('.datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
    });

	$(function(){
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
                url: '<?=site_url('api/presensi/harian')?>',
                type: 'POST',
                data: {
                    unit_kerja: $('#input_unit_kerja').val(),
                    jabatan: $('#input_jabatan').val(),
                    search: $('#search').val(),
                    periode: $('#tanggal').val(),
                }
            },
            columns: [
                {
                    data: 'pegawai_id_hash',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '';
                            <?php if(return_access_link(['presensi', 'laporan/kegiatan/view'])){?>
                                txt_view += '<a class="m-1 text-success fs-4" href="<?=site_url('presensi/laporan/kegiatan/view?id=')?>'+data+'&tanggal='+row.tanggal+'&link=harian" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail presensi & Laporan Kegiatan"><i class="fa fa-info-circle"></i></a> '
                            <?php } ?>
                        return txt_view
                    }
                },
                {
                    data: 'nama',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = ''
                            +'<div>'+row.nama+'</div>'
                        return txt_view
                    }
                },
                {
                    data: 'jabatan_name',
                    className: '',
                    orderable: false,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'unit_kerja_name_alt',
                    className: '',
                    orderable: false,
                    render: function(data, type, row){
                        var txt_view = data+' ('+row.unit_kerja_name+')'
                        return txt_view
                    }
                },
                {
                    data: 'start',
                    className: 'text-center fw-bold',
                    orderable: true,
                    render: function(data, type, row, index){
                        var txt_view = data.substring(10)
                        return txt_view
                    }
                },
                {
                    data: 'durasi_flexi',
                    className: 'text-center',
                    orderable: true,
                    render: function(data, type, row, index){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'durasi_terlambat',
                    className: 'text-center',
                    orderable: true,
                    render: function(data, type, row, index){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'stop',
                    className: 'text-center fw-bold',
                    orderable: true,
                    render: function(data, type, row, index){
                        var txt_view = '-'
                        if(row.stop!=null)
                            txt_view = data.substring(11)
                        return txt_view
                    }
                },
                {
                    data: 'durasi_mendahului',
                    className: 'text-center',
                    orderable: true,
                    render: function(data, type, row, index){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'total_durasi',
                    className: 'text-center fw-bold',
                    orderable: true,
                    render: function(data, type, row, index){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'df_durasi_istirahat',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'total_durasi_kerja',
                    className: 'text-center fw-bold',
                    orderable: true,
                    render: function(data, type, row, index){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'df_durasi_kerja',
                    className: 'text-center',
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
                {
                    data: 'start_latlong',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row){
                        var txt_view = ''
                        if(data && data!=null)
                            txt_view = '(<a href="#" onclick="window.open(\'<?=site_url('service/maps')?>?latlng='+data+'&title=Lokasi absen mulai `'+row.nama+'`, pada '+row.start+'\', \'\', \'top=100,left=300,width=700,height=639\')">'+row.start_log+'</a>)'
                        return txt_view
                    }
                },
                {
                    data: 'stop_latlong',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row){
                        var txt_view = ''
                        if(data && data!=null)
                            txt_view = '(<a href="#" onclick="window.open(\'<?=site_url('service/maps')?>?latlng='+data+'&title=Lokasi absen mulai `'+row.nama+'`, pada '+row.stop+'\', \'\', \'top=100,left=300,width=700,height=639\')">'+row.stop_log+'</a>)'
                        return txt_view
                    }
                }
            ],
            order: [[ 4, 'desc' ]]
        })
    }
</script>