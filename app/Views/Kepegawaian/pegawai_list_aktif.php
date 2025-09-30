<div class="card">
    <div class="card-header d-flex">
        <div class="flex-grow-1"><?=$title?></div>
        <?php if(return_access_link(['kepegawaian/form'])){?>
            <a href="<?=site_url('kepegawaian/form')?>" class="btn btn-sm btn-success" title="Tambah pegawai"><i class="fa fa-plus-circle"></i> Tambah</a>
        <?php }?>
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
        <!-- <div class="row mb-2">
            <div class="col-12">
                <?php if(!array_keys([7], $unit)){?>
                    <a href="#" class="me-2" onclick="download_data(1, '<?=$unit?>')"><i class="far fa-file-excel"></i> Excel <?php if(array_keys(['',1,9,8,2,3,4,5,6], $unit)){echo'TP';}else{echo ucfirst($unit);}?></a>
                <?php }?>
                <a href="#" class="me-2" onclick="download_data(1, 7)"><i class="far fa-file-excel"></i> Excel Sekre ASN</a>
                <a href="#" class="me-2" onclick="download_foto(1, '<?=$unit?>')"><i class="far fa-file-archive"></i> File Foto</a>
            </div>
        </div> -->
        <div class="row mb-2">
            <div class="col-12 table-responsive">
                <table id="example1" class="table table-striped table-hover">
                	<thead class="bg-light">
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
        select2_jabatan('#input_jabatan', '<?=$unit?>')
        select2_referensi('#input_kelamin', 'gender')
        select2_referensi('#input_status_pns', 'pegawai_status_pns')
	})
    var array_pegawai = []

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
            pageLength: 100,
            layout: {
                topEnd: {
                    search: {
                        placeholder: 'NIK, NIP, Nama, HP & Tempat Lahir'
                    }
                }
            },
            ajax: {
                url: '<?=site_url('api/kepegawaian/aktif?id='.$unit)?>',
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
                        var txt_view = ''
                        <?php if(return_access_link(['kepegawaian/profile'])){?>
                            array_pegawai.push({ID: data, NAMA: row.nama})
                            txt_view += '<a style="font-size:14pt" class="m-1 text-primary" href="<?=site_url('kepegawaian/profile?id=')?>'+data+'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Detail data" id="foto'+data+'"><i class="fas fa-spinner fa-spin"></i></a>'
                        <?php }?>
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


    var unit_kerja_internal = ''
    <?php $ck=0; foreach(session()->get('units') as $k) if($k->unit_kerja_id==$unit) {$ck+=1;?>
        unit_kerja_internal += '<option value="<?=$k->unit_kerja_id?>" selected="selected"><?=$k->unit_kerja_name?></option>'
    <?php } ?>
    $('#input_unit_kerja').append(unit_kerja_internal).trigger('change')
    <?php if($unit<>''){?>
        <?php if($ck>0){?>
            .prop('disabled', true)
        <?php }else{?>
            // .append('<option value="" selected="selected"><?=$unit?></option>').trigger('change')
        <?php }?>
    <?php }?>

// array_pegawai.forEach(({ID}, index)=>{
//     // console.log('ID.',index, ' : ', ID)
//     console.log('ID.')
// })
// $.each(array_pegawai, function(i, row){
//     console.log('hasil ID: ',row.ID)
// })
//     for (let i = 0; i < array_pegawai.length; i++) {
//         console.log(`Index ${i}: ${numbers[i].ID}`);
//     }   
// for (const { ID } of array_pegawai) {
//     console.log(`${ID} is  years old`);
// }

    $(function(){
        console.log('log:', array_pegawai)
        $.each(array_pegawai, function(i, row){
            console.log('__ID__',i, row)
            load_foto_pegawai(row.ID, 1)
        })
        array_pegawai.forEach(({ID, NAMA}, index)=>{
            console.log('_ID_',ID,NAMA)
        })
    })

    function load_foto_pegawai(pegawai_id, thumbnail=1)
    {
        $('#foto'+pegawai_id).html('<i class="fas fa-spinner fa-spin"></i>')
        $.get('<?=site_url('foto')?>', {id:pegawai_id, thumbnail:1}, function(rs){
            var dataEncode = rs.data
        // get_foto(pegawai_id, thumbnail, function(dataEncode){
            var img = '<img src="'+dataEncode+'" width="50">'
            $('#foto'+pegawai_id).html(img)
            // console.log('data:', dataEncode)
        })
    }
</script>