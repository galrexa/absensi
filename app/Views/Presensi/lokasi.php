<div class="card">
    <div class="card-header d-flex">
        <span class="flex-grow-1 fw-bold">
            <?=$title?>
        </span>
        <?php if(return_access_link(['presensi/lokasi/form'])){?>
            <a href="<?=site_url('presensi/lokasi/form')?>" class="me-1 btn btn-sm btn-primary" title="Tambah lokasi"><i class="fa fa-plus-circle"></i></a>
            <a href="#" class="me-1 btn btn-success btn-sm" title="Edit lokasi" onclick="option_data()"><i class="fa fa-edit"></i></a>
            <a href="#" class="me-1 btn btn-danger btn-sm" title="Hapus lokasi" onclick="option_data(1)"><i class="fa fa-trash"></i></a>
        <?php }?>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col mb-2">
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-hover table-bordered">
                        <thead class="">
                            <tr>
                                <th width="5%" class="bg-secondary text-center text-light">#</th>
                                <th width="30%" class="bg-secondary text-center text-light">NAMA</th>
                                <th width="" class="bg-secondary text-center text-light">KETERANGAN</th>
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
        load_data()
    })

    function load_data()
    {
        var t = $('#example1').DataTable({
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
                        placeholder: 'lokasi?'
                    }
                }
            },
            ajax: {
                url: '<?=site_url('api/presensi/lokasi')?>',
                type: 'POST',
                data: {
                    // periode: $('#tahun').val()
                }
            },
            columns: [
                {
                    data: 'id',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '';
                            <?php if(return_access_link(['presensi/lokasi/form'])){?>
                                if(row.show_data==1){
                                    txt_view += '<input type="checkbox" value="'+data+'" class="check_tanggal">'
                                }
                            <?php }?>
                        return txt_view
                    }
                },
                {
                    data: 'name',
                    className: 'fw-bold',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'description',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = ''
                        if(data){
                            txt_view += data
                        }
                        txt_view += 'LongLat: '+ row.latlong
                        return txt_view
                    }
                },
            ],
            order: [[ 0, 'desc' ]]
        })
    }

    <?php if(return_access_link(['presensi/lokasi/form'])){?>
        function option_data(trash=0)
        {
            var arr_id = []
            $.each($(".check_tanggal:checked"), function(){
                arr_id.push($(this).val());
            });
            if(arr_id.length==0)
            {
                alert('Tidak ada data yang dipilih, pilih data terlebih dahulu...')
                return false
            }else{
                if(trash==1)
                {
                    var cf = confirm('Hapus data yang dipilih?')
                    if(cf===true){
                        window.location.assign('<?=site_url('presensi/lokasi/form?trash=1&id=')?>'+arr_id.join(','))
                    }
                }else{
                    window.location.assign('<?=site_url('presensi/lokasi/form?id=')?>'+arr_id.join(','))
                }
            }
        }
    <?php }?>
</script>