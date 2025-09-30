<div class="card">
    <div class="card-header d-flex">
        <span class="flex-grow-1 fw-bold">
            <?=$title?>
        </span>
        <?php if(return_access_link(['cuti/master/saldo/form'])){?>
            <a href="<?=site_url('cuti/master/saldo/form')?>" class="ms-1 btn btn-sm btn-success" title="Tambah data"><i class="fa fa-plus-circle"></i></a>
        <?php }?>
        <?php if(return_access_link(['cuti/master/saldo/hapus'])){?>
            <a class="ms-1 btn btn-sm btn-danger" href="#" onclick="hapus_saldo_cuti()" title="Hapus data"><i class="fa fa-trash"></i></a>
        <?php }?>
    </div>
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-sm-12 col-md-2 fw-bold">
                Unit Kerja:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="unit_kerja[]" id="unit_kerja" class="form-control" multiple onchange="load_data('#example1');"></select>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-12 col-md-2 fw-bold">
                Jabatan:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="jabatan[]" id="jabatan" class="form-control" multiple onchange="load_data('#example1');"></select>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-12 col-md-2 fw-bold">
                Periode:
            </div>
            <div class="col-sm-12 col-md-2">
                <div class="input-group">
                    <select name="tahun" id="tahun" class="form-control" onchange="load_data('#example1'); localStorage.setItem('tahun', this.value)">
                        <?php $arrayTahun =[]; foreach ($list_tahun as $key) {array_push($arrayTahun, $key->field);}?>
                        <?php if(!array_keys($arrayTahun, date('Y')) or empty($arrayTahun)){?>
                            <option value="<?=date('Y')?>" selected><?=date('Y')?></option>
                        <?php }?>
                        <?php foreach ($list_tahun as $key) {?>
                            <option value="<?=$key->field?>" <?php if($key->field==date('Y')){echo'selected';}?>><?=$key->field?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-hover table-bordered">
                        <thead class="">
                            <tr>
                                <th width="5%" class="text-center bg-warning">
                                    <input type="checkbox" id="pilih_semua" value="1" onclick="if(this.checked==true){this.title='lepas semua'; $('.check_id_saldo').prop('checked', true)}else{this.title='pilih semua'; $('.check_id_saldo').prop('checked', false)}" title="pilih semua">
                                    #
                                </th>
                                <th class="text-center bg-warning">Nama</th>
                                <th class="text-center bg-warning">Unit Kerja</th>
                                <th class="text-center bg-warning">Tahun</th>
                                <th class="text-center bg-warning">Saldo</th>
                                <th class="text-center bg-warning">Digunakan</th>
                                <th class="text-center bg-warning">Sisa</th>
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
        select2_jabatan('#jabatan', '')
        select2_unit_kerja('#unit_kerja')
        load_data('#example1')
	})

    function load_data(element)
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
            pageLength: 100,
            layout: {
                topEnd: {
                    search: {
                        // placeholder: '...'
                    }
                }
            },
            ajax: {
                url: '<?=site_url('api/cuti/master/saldo')?>',
                type: 'POST',
                data: {
                    tahun: $('#tahun').val(),
                    unit_kerja: $('#unit_kerja').val(),
                    jabatan: $('#jabatan').val(),
                }
            },
            columns: [
                {
                    data: 'id',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '';
                        <?php if(return_access_link(['cuti/master/saldo/hapus'])){?>
                            // txt_view += '<a class="fs-6 m-1 text-danger" href="#" onclick="hapus_saldo_cuti(\''+row.id+'\')" title="Hapus data"><i class="fa fa-trash"></i></a> '
                            txt_view += '<input type="checkbox" value="'+data+'" class="check_id_saldo m-1">'
                        <?php }?>
                        <?php if(return_access_link(['cuti/master/saldo/form'])){?>
                            txt_view += '<a class="fs-6 m-1 text-success" href="<?=site_url('cuti/master/saldo/form?id=')?>'+row.id+'" title="Edit data"><i class="fa fa-edit"></i></a> '
                        <?php }?>
                        return txt_view
                    }
                },
                {
                    data: 'nama',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = '<div class="fw-bold">'+ data +'</div>'+ row.jabatan_name
                        return txt_view
                    }
                },
                {
                    data: 'unit_kerja_name',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'tahun',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'jatah',
                    className: 'text-center fw-bold',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'digunakan',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'sisa_saat_ini',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = data
                        return txt_view
                    }
                },
            ],
            order: [[ 0, 'asc' ]]
        })
    }

    <?php if(return_access_link(['cuti/master/saldo/hapus'])){?>
        function hapus_saldo_cuti(id=0)
        {
            var arr_id = []
            $.each($(".check_id_saldo:checked"), function(){
                arr_id.push($(this).val());
            });
            if(arr_id.length==0)
            {
                alert('Tidak ada data yang dipilih, pilih data terlebih dahulu...')
                return false
            }else{
                var cf = confirm('Hapus data ini?')
                if(cf==true)
                {
                    $.get('<?=site_url('cuti/master/saldo/hapus')?>', {id:arr_id.join(',')}, function(r){
                        if(r.status==true)
                        {
                            load_data('#example1')
                        }else{
                            alert(r.message)
                        }
                    })
                }
            }
        }
    <?php }?>
</script>