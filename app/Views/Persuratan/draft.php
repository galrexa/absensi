<div class="card">
    <div class="card-header">
        <?=$title?>
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
                Pengamanan:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="sifat[]" id="sifat" class="form-control" multiple onchange="load_data('#example1', [1,2,3,4,5,6,7,8]);"></select>
            </div>
        </div> 
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Penyampaian:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="urgensi[]" id="urgensi" class="form-control" multiple onchange="load_data('#example1', [1,2,3,4,5,6,7,8]);"></select>
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
                                <th class="text-center color-abu">#</th>
                                <th class="text-center color-abu"><i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Info detail naskah"></i></th>
                                <th class="text-center color-abu">Drafting</th>
                                <th class="text-center color-abu">Sifat</th>
                                <th class="text-center color-abu">Urgensi</th>
                                <th class="text-center color-abu">Informasi</th>
                                <th class="text-center color-abu">TTE</th>
                                <th class="text-center color-abu">Status</th>
                        </thead>
                        <tbody style="vertical-align: top;"></tbody>
                        <tfoot class="bg-secondary">
                            <?php if(return_access_link(['signing_multiple'])){?>
                                <tr>
                                    <td>
                                        <input type="checkbox" id="pilih_semua" value="1" onclick="if(this.checked==true){this.title='lepas semua'; $('.id_surat_tmp').prop('checked', true)}else{this.title='pilih semua'; $('.id_surat_tmp').prop('checked', false)}" title="pilih semua">
                                    </td> 
                                    <td colspan="7">
                                        <button onclick="open_form_tte()">Buka form tanda tangan elektronik</button>
                                        <script type="text/javascript">
                                            function open_form_tte()
                                            {
                                                var arr_id = []
                                                $.each($(".id_surat_tmp:checked"), function(){
                                                    arr_id.push($(this).val());
                                                });
                                                if(arr_id.length==0)
                                                {
                                                    alert('Tidak ada data yang dipilih, pilih data terlebih dahulu...')
                                                }else{
                                                    console.log('ID => ', arr_id)
                                                    load_page_in_modal_dialog('<?=site_url('documents/signing_multiple/?id=')?>'+arr_id.join(',')+'', 'modal-lg')
                                                }
                                            }
                                        </script>
                                    </td>
                                </tr>
                            <?php }?>
                        </tfoot>
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
                url: '<?=site_url('api/persuratan/draft')?>',
                type: 'POST',
                data: {
                    pegawai_id: '<?=$pegawai_id?>',
                    tahun: $('#tahun').val(),
                    bulan: $('#bulan').val(),
                    jenis: $('#jenis').val(),
                    sifat: $('#sifat').val(),
                    urgensi: $('#urgensi').val(),
                    check_filter: $('#check_filter:checked').val()
                }
            },
            columns: [
                {
                    data: 'id',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '<i class="fa fa-exclamation text-danger"></i>'
                        if(row.signer_status==0)
                        {
                            <?php if(return_access_link(['signing_multiple'])){?>
                                if(row.pegawai_id==<?=session()->get('id_pegawai')?> && (row.status==4 || ((row.status==1 || row.status==3) && row.cuser==<?=session()->get('id')?>)))
                                    txt_view = '<input type="checkbox" class="id_surat_tmp" value="'+row.id+'" >'
                            <?php }?>
                        }else if(row.signer_status==1){
                            txt_view = '<i class="fa fa-check text-success"></i>'
                        }
                        return txt_view
                    }
                },
                {
                    data: 'register_number',
                    className: 'text-center fw-bold',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = '';
                            <?php if(return_access_link(['persuratan/detail'])){?>
                                txt_view += '<a class="fs-4 text-success" href="<?=site_url('persuratan/detail?id=')?>'+row.hash+'&link=draft&tab='+tab+'" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Info detail naskah"><i class="fa fa-info-circle"></i></a> '
                            <?php }?>
                        return txt_view
                    }
                },
                {
                    data: 'draf_type',
                    className: 'text-center',
                    orderable: true,
                    render: function(data, type, row, index){
                        var txt_view = row.draf_type_name
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
                        var txt_view = ''
                        txt_view += '<div><b>Hal:</b> '+row.hal+'</div>'
                        if(row.penerima){
                            var penerima = []
                            $.each(row.penerima, function(i, r){
                                var diteruskan = '<i class="fa fa-stop text-danger"></i>'
                                if(r.sent==1)
                                    diteruskan = '<i class="fa fa-paper-plane text-success"></i>'
                                penerima.push(r.nama+' ('+r.jabatan_name+'(<i class="fw-bold" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Diteruskan kepada '+r.nama+'? '+r.diteruskan+'">'+diteruskan+'</i>))')
                            })
                        }
                        if(penerima.length < 3){
                            txt_view += '<div><b>Penerima:</b> '+penerima.join(', ')+'</div>'
                        }else{
                            txt_view += '<div><b>Penerima:</b> '+penerima.join(', ')+'<a href="#" class="d-block">lihat lebih banyak...</a></div>'
                        }
                        return txt_view
                    }
                },
                {
                    data: 'signer_status',
                    className: 'text-center',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = '<i class="fa fa-minus-circle text-danger" title="Belum ditandatangan"></i>'
                        if(data==1)
                            txt_view = '<i class="fa fa-check-circle text-success" title="Sudah ditandatangan"></i>'
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
            "createdRow": function( row, data, dataIndex){
                if( data.respon_user ==  0){
                    $(row).addClass('fw-bold');
                    $('td', row).css('background', '#fcf5a7');
                }
                if( data.read_user ==  0){
                    $(row).addClass('fw-bold');
                    $('td', row).css('background', '#fabbbb');
                }
            },
            order: [[ 0, 'desc' ]]
        })
    }
</script>