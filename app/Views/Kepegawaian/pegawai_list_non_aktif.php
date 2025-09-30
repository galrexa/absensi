<div class="card">
    <div class="card-header d-flex">
        <div class="flex-grow-1"><?=$title?></div>
    </div>
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-md-2 col-sm-12 fw-bold">
                Unit Kerja:
            </div>
            <div class="col-md-10 col-sm-12">
                <select name="input_unit_kerja[]" id="input_unit_kerja" class="form-control form-control-sm" multiple onchange="load_data()"></select>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-2 col-sm-12 fw-bold">
                Jabatan:
            </div>
            <div class="col-md-10 col-sm-12">
                <select name="input_jabatan[]" id="input_jabatan" class="form-control form-control-sm" multiple onchange="load_data()"></select>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-2 col-sm-12 fw-bold">
                Jenis Kelamin:
            </div>
            <div class="col-md-10 col-sm-12">
                <select name="input_kelamin[]" id="input_kelamin" class="form-control form-control-sm" multiple onchange="load_data()"></select>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-2 col-sm-12 fw-bold">
                StatusPNS:
            </div>
            <div class="col-md-10 col-sm-12">
                <select name="input_status_pns[]" id="input_status_pns" class="form-control form-control-sm" multiple onchange="load_data()"></select>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-12 col-md-2 fw-bold">
                Perncarian:
            </div>
            <div class="col-sm-12 col-md-10">
                <input type="text" name="search" id="search" class="form-control" placeholder="Nama Pegawai" oninput="load_data()">
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12 mb-2 table-responsive">
                <table id="example1" class="table table-striped table-hover">
                    <thead class="">
                        <tr>
                            <th width="5%">#</th>
                            <th width="">NIK</th>
                            <th width="">NAMA</th>
                            <th width="">TEMPAT_TGL_LAHIR</th>
                            <th width="">AGAMA</th>
                            <th width="">KELAMIN</th>
                            <th width="">STATUS_PERKAWINAN</th>
                            <th width="">PENDIDIKAN</th>
                            <th width="">JABATAN_TERAKHIR</th>
                            <th width="">UNIT_KERJA</th>
                            <th width="">NO_HP</th>
                            <th width="">NO_TELP</th>
                            <th width="">EMAIL_DINAS</th>
                            <th width="">EMAIL_PRIBADI</th>
                        </tr>
                    </thead>
                    <tbody style="vertical-align: top;"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<link href="<?=base_url()?>assets/css/datatables.min.css" rel="stylesheet" />
<script src="<?=base_url()?>assets/js/datatables.min.js"></script>

<link href="<?=base_url('assets/')?>css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url('assets/')?>js/select2.full.min.js"></script>
<script type="text/javascript">
    $(function(){
        load_data()
        select2_unit_kerja('#input_unit_kerja')
        select2_jabatan('#input_jabatan')
        select2_referensi('#input_kelamin', 'gender')
        select2_referensi('#input_status_pns', 'status_pns')
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
            pageLength: 25,
            layout: {
                topEnd: {
                    search: {
                        placeholder: 'NIK, NIP, Nama, HP & Tempat Lahir'
                    }
                }
            },
            ajax: {
                url: '<?=site_url('api/kepegawaian/non_aktif?id=')?>',
                type: 'POST',
                data: {
                    unit_kerja: $('#input_unit_kerja').val(),
                    jabatan: $('#input_jabatan').val(),
                    kelamin: $('#input_kelamin').val(),
                    status_pns: $('#input_status_pns').val(),
                    search: $('#search').val(),
                }
            },
            columns: [
                {
                    data: 'pegawai_id',
                    className: 'fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '';
                            <?php #if($this->authModel->return_privileges_access_by_variable('kepegawaian', 'profile')){?>
                                txt_view += '<a style="font-size:14pt" class="m-1 text-primary" href="<?=site_url('kepegawaian/profile?id=')?>'+data+'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Detail data"><i class="fa fa-user-circle"></i></a> '
                                // txt_view += '<a style="font-size:14pt" class="m-1 text-success" href="<?=site_url('kepegawaian/form?id=')?>'+data+'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Detail data"><i class="fas fa-edit"></i></a> '
                            <?php #}?>
                        return txt_view
                    }
                },
                {
                    data: 'nik',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'nama',
                    className: '',
                    orderable: true,
                    render: function(data, type, row, index){
                        return data
                    }
                },
                {
                    data: 'tanggal_lahir',
                    className: '',
                    orderable: true,
                    render: function(data, type, row, index){
                        return row.tempat_lahir +', '+ data
                    }
                },
                {
                    data: 'agama',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        return row.agama_name
                    }
                },
                {
                    data: 'kelamin_name',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        return data
                    }
                },
                {
                    data: 'status_perkawinan_name',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        return data
                    }
                },
                {
                    data: 'pendidikan',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        return row.pendidikan_name
                    }
                },
                {
                    data: 'jabatan',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        return data
                    }
                },
                {
                    data: 'unit_kerja',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        return data
                    }
                },
                {
                    data: 'hp',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        return data
                    }
                },
                {
                    data: 'telp',
                    className: '',
                    orderable: false,
                    render: function(data, type, row, index){
                        // var foto_peg = thumbnail_default_pegawai(row.kelamin)
                        // if(row.foto_pegawai_temp)
                        // {
                        //     var xqr = row.foto_pegawai_temp
                        //     foto_peg = (xqr.split('icon_p/'))[1]
                        // }
                        // return '<img src="<?=base_url('assets/img/icons/pegawai/')?>'+foto_peg+'" class="img-thumbnail">'
                        return data
                    }
                },
                {
                    data: 'email',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        return data
                    }
                },
                {
                    data: 'email_pribadi',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        return data
                    }
                },
            ],
            order: [[ 0, 'asc' ]]
        })
    }
</script>