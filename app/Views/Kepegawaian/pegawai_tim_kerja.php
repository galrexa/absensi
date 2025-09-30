<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <?=$title?>
            </div>
            <div class="col">
                <a href="<?=site_url('kepegawaian/tim/form')?>"><i class="fa fa-plus-circle"></i> Tambah Tim Kerja</a>
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table id="example1" class="table table-striped table-hover">
            	<thead class="">
            		<tr>
            			<th width="5%">#</th>
            			<th width="15%">Nomor</th>
                        <th width="65%">Detail</th>
                        <th width="10%">Status</th>
                        <th width="5%">Anggota</th>
            		</tr>
            	</thead>
            	<tbody style="vertical-align: top;"></tbody>
            </table>
        </div>
    </div>
</div>

<link href="<?=base_url()?>assets/css/datatables.min.css" rel="stylesheet" />
<script src="<?=base_url()?>assets/js/datatables.min.js"></script>

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
            pageLength: 25,
            ajax: {
                url: "<?=site_url('api/kepegawaian/tim')?>",
                type: "POST",
                data: {}
            },
            columns: [
                {
                    data: 'id_sk_tim',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '';
                            <?php if(return_access_link(['kepegawaian/tim/detail'])){?>
                                txt_view += '<a style="font-size:14pt" class="m-1 text-primary" href="<?=site_url('kepegawaian/tim/detail?id=')?>'+row.id_sk_tim+'" data-toggle="tooltip" data-placement="top" title="Detail data"><i class="fas fa-exclamation-circle"></i></a> '
                            <?php }?>
                        return txt_view
                    }
                },
                {
                    data: 'nomor_sk',
                    className: '',
                    orderable: true,
                    render: function(data, type, row, index){
                        return data
                    }
                },
                {
                    data: "nomor_sk",
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var txt_view = 'Tanggal: '+row.tgl_sk
                        txt_view += '<div class="d-block">Periode: '+row.tgl_awal+' s.d. '+row.tgl_akhir+'</div>'
                        txt_view += '<div class="d-block fw-bold">'+row.keterangan+'</div>'
                    	return txt_view
                    }
                },
                {
                    data: 'status_sk',
                    render: function(data, type, row){
                        return row.status_sk_name
                    }
                },
                {
                    data: 'anggota',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row){
                        return data +' <i class="fa fa-users"></i>'
                    }
                }
            ],
            order: [[ 0, 'asc' ]]
        })
    }
</script>