<div class="card">
    <div class="card-header">
        <?=$title?>
    </div>
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-sm-12 col-md-2">
                Periode:
            </div>
            <div class="col-sm-12 col-md-10">
                <div class="input-group">
                    <select name="tahun" id="tahun" class="" onchange="load_data()">
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-hover table-bordered">
                    	<thead class="">
                    		<tr>
                                <th class="bg-secondary text-center text-light">
                                    <input type="checkbox" id="pilih_semua" value="1" onclick="if(this.checked==true){this.title='lepas semua'; $('.check_tanggal').prop('checked', true)}else{this.title='pilih semua'; $('.check_tanggal').prop('checked', false)}" title="pilih semua">
                                </th>
                                <th class="bg-secondary text-center text-light">TANGGAL</th>
                                <th class="bg-secondary text-center text-light">KETERANGAN</th>
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
                        placeholder: 'Hari libur?'
                    }
                }
            },
            ajax: {
                url: '<?=site_url('api/presensi/hari_libur')?>',
                type: 'POST',
                data: {
                    periode: $('#tahun').val()
                }
            },
            columns: [
                {
                    data: 'tanggal',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '';
                            <?php if(return_access_link(['presensi', 'riwayat'])){?>
                                txt_view += '<input type="checkbox" value="'+data+'" class="check_tanggal" onchange="select_checkbox()">'
                            <?php }?>
                        return txt_view
                    }
                },
                {
                    data: 'tanggal',
                    className: 'fw-bold text-center',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
                {
                    data: 'keterangan',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = data
                        return txt_view
                    }
                },
            ],
            order: [[ 0, 'desc' ]]
        })
    }


    function select_checkbox()
    {
        var arr_id = []
        $.each($(".check_tanggal:checked"), function(){
            arr_id.push($(this).val());
        });
        if(arr_id.length==0)
        {
            // alert('Tidak ada data yang dipilih, pilih data terlebih dahulu...')
            console.log('ID => Not selected data')
        }else{
            console.log('ID => ', arr_id)
            // load_page_in_modal_dialog('<?=site_url('documents/signing_multiple/?id=')?>'+arr_id.join(',')+'', 'modal-lg')
        }
    }
</script>