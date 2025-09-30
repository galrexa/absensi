<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="row mb-1">
                    <div class="col-sm-12 col-md-2 fw-bold">
                        Nama:
                    </div>
                    <div class="col-sm-12 col-md-10">
                        <?=$data_pegawai->nama?>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-sm-12 col-md-2 fw-bold">
                        Jabatan:
                    </div>
                    <div class="col-sm-12 col-md-10">
                        <?=$data_pegawai->jabatan?>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-sm-12 col-md-2 fw-bold">
                        UnitKerja:
                    </div>
                    <div class="col-sm-12 col-md-10">
                        <?=$data_pegawai->unit_kerja_alt?> (<?=$data_pegawai->unit_kerja?>)
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-12 col-md-2 fw-bold">
                        Periode:
                    </div>
                    <div class="col-sm-12 col-md-10">
                        <div class="input-group">
                            <select name="tahun" id="tahun" class="" onchange="load_data()">
                                <?php $arrayTahun =[]; foreach ($list_tahun as $key) {array_push($arrayTahun, $key->tahun);}?>
                                <?php if(!array_keys($arrayTahun, $tahun) or empty($arrayTahun)){?>
                                    <option value="<?=$tahun?>" selected><?=$tahun?></option>
                                <?php }?>
                                <?php foreach ($list_tahun as $key) {?>
                                    <option value="<?=$key->tahun?>" <?php if($key->tahun==$tahun){echo'selected';}?>><?=$key->tahun?></option>
                                <?php }?>
                            </select>
                            <select name="bulan" id="bulan" class="" onchange="load_data()">
                                <?php foreach (array_bulan() as $key=>$value) {?>
                                    <option value="<?=$key?>" <?php if($key==$bulan){echo'selected';}?>><?=$value?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-2"></div>
                    <div class="col-sm-12 col-md-10">
                        <a href="#" class="btn btn-info fw-bold" data-bs-toggle="modal" data-bs-target="#modal_tte"><i class="fa fa-exclamation-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Informasi Kode Presensi"></i></a>
                        <?php if(return_access_link(['presensi/riwayat/unduh'])){?>
                            <a href="#" onclick="window.open('<?=site_url('presensi/riwayat/unduh?id='.$pegawai_id.'&periode=')?>'+$('#tahun').val()+'-'+$('#bulan').val())" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Unduh data dalam bentuk file excel"><i class="fa fa-file-excel"></i></a>
                            <a href="#" onclick="window.open('<?=site_url('presensi/riwayat/unduh?id='.$pegawai_id.'&periode=')?>'+$('#tahun').val()+'-'+$('#bulan').val()+'&file=print')" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Print data"><i class="fa fa-print"></i></a>
                            <a href="#" onclick="window.open('<?=site_url('presensi/riwayat/unduh?id='.$pegawai_id.'&periode=')?>'+$('#tahun').val()+'-'+$('#bulan').val()+'&file=pdf')" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Pdf data"><i class="fa fa-file-pdf"></i></a>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col table-responsive">
                <table id="example1" class="table table-striped table-hover table-bordered">
                	<thead>
                        <tr>
                            <th class="text-center color-dark text-light" rowspan="2"><i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Detail presensi"></i></th>
                            <th class="text-center color-dark text-light" rowspan="2"><i class="fa fa-pencil-alt" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Form laporan kegiatan harian"></i></th>
                            <th class="text-center color-dark text-light" rowspan="2">TANGGAL</th>
                            <th class="text-center color-dark text-light" colspan="6">PRESENSI</th>
                            <th class="text-center color-dark text-light" rowspan="2">KETERANGAN</th>
                        </tr>
                        <tr>
                            <th class="text-center color-dark text-light">MASUK</th>
                            <th class="text-center color-dark text-light">FLEXI</th>
                            <th class="text-center color-dark text-light">TERLAMBAT</th>
                            <th class="text-center color-dark text-light">PULANG</th>
                            <th class="text-center color-dark text-light">CEPAT PULANG</th>
                            <th class="text-center color-dark text-light">TOTAL</th>
                        </tr>
                	</thead>
                	<tbody style="vertical-align: top;"></tbody>
                </table>
            </div>
        </div>
        <br><br><br>
        <div class="modal modal-lg" tabindex="-1" id="modal_tte" data-bs-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header color-abu fw-bold" id="modal_tte_header">
                        <h5 class="modal-title">Informasi Kode</h5>
                        <button type="button" class="bg-danger btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal_tte_body">
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
<link href="<?=base_url()?>assets/css/datatables.min.css" rel="stylesheet" />
<script src="<?=base_url()?>assets/js/datatables.min.js"></script>
<link href="<?=base_url('assets/')?>css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url('assets/')?>js/select2.full.min.js"></script>
<script src="<?=base_url('assets/js/custom.js')?>"></script>
<script type="text/javascript">
	$(function(){
		load_data()
	})

    function load_data()
    {
        var t = $('#example1').DataTable({
            bDestroy: true,
            bPaginate: false,
            bLengthChange: false,
            bFilter: false,
            bInfo: false,
            bAutoWidth: false,
            processing: true,
            serverSide: true,
            pageLength: 25,
            layout: {
                topEnd: {
                    search: {
                        placeholder: 'NIK, NIP, Nama, HP & Tempat Lahir'
                    }
                }
            },
            ajax: {
                url: '<?=site_url('api/presensi/riwayat?'.$_SERVER['QUERY_STRING'])?>',
                type: 'POST',
                data: {
                    pegawai_id: '<?=$pegawai_id?>',
                    periode: $('#tahun').val()+'-'+$('#bulan').val(),
                }
            },
            columns: [
                {
                    data: 'pegawai_id',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = ''
                        if(row.start!='-'){
                            <?php if(return_access_link(['presensi', 'laporan/kegiatan/view'])){?>
                                txt_view += '<a class="m-1 fs-4 text-success" href="<?=site_url('presensi/laporan/kegiatan/view?id=')?>'+row.pegawai_id_hash+'&tanggal='+row.tanggal+'&link=riwayat" title="Detail & Laporan Kegiatan"><i class="fa fa-info-circle"></i></a> '
                            <?php }?>
                        }
                        return txt_view
                    }
                },
                {
                    data: 'pegawai_id',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = ''
                        var title_ = 'Tambah laporan kegiatan'
                        var icon_lap = 'far fa-comment-alt'
                        if(row.laporan==1){
                            title_ = 'Edit atau lihat laporan kegiatan'
                            icon_lap = 'fas fa-comment-alt'
                        }
                        if(row.start!='-'){
                            <?php if(return_access_link(['presensi', 'laporan/kegiatan'])){?>
                                txt_view += '<a class="m-1 fs-4 text-success" href="<?=site_url('presensi/laporan/kegiatan?tanggal=')?>'+row.tanggal+'" title="'+title_+'"><i class="'+icon_lap+'"></i></a> '
                            <?php }?>
                        }
                        return txt_view
                    }
                },
                {
                    data: 'tanggal',
                    className: 'text-center fw-bold',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'start',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = data.substring(10)
                        if(row.start!='-')
                            txt_view += '<span class="d-block">(<a href="#" onclick="window.open(\'<?=site_url('service/maps')?>?latlng='+row.start_latlong+'&title=Lokasi absen mulai `<?=$data_pegawai->nama?>`, pada '+row.start+'\', \'\', \'top=100,left=300,width=700,height=639\')">'+row.start_log+'</a>)</span>'
                            // txt_view += '<span class="d-block">'+row.start_latlong+'</span>'
                        return txt_view
                    }
                },
                {
                    data: 'durasi_flexi',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '-'
                        if(row.flexi==1)
                            txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'durasi_terlambat',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '-'
                        if(row.terlambat==1)
                            txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'stop',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = ''
                        if(data===null || data=='-'){}else{
                            txt_view = data.substring(10)
                            if(row.stop)
                                txt_view += '<span class="d-block">(<a href="#" onclick="window.open(\'<?=site_url('service/maps')?>?latlng='+row.stop_latlong+'&title=Lokasi absen selesai `<?=$data_pegawai->nama?>` pada '+row.start+'\', \'\', \'top=100,left=300,width=700,height=639\')">'+row.stop_log+'</a>)</span>'
                                // txt_view += '<span class="d-block">'+row.stop_latlong+'</span>'
                        }
                        return txt_view
                    }
                },
                {
                    data: 'durasi_mendahului',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '-'
                        if(row.mendahului==1)
                            txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'total_durasi',
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
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                }
            ],
            "createdRow": function( row, data, dataIndex){
                if( data.libur ==  1){
                    $(row).addClass('fw-bold');
                    // $(row).addStyle('background-color: red;');
                    $('td', row).css('color', 'red');
                }
            },
            order: [[ 0, '<?=$order?>' ]]
        })
    }
</script>