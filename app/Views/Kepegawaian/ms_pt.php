<div class="card">
    <div class="card-header d-flex">
        <div class="flex-grow-1"><?=$title?></div>
        <?php if(return_access_link(['kepegawaian/perguruan_tinggi/form'])){?>
            <a href="<?=site_url('kepegawaian/perguruan_tinggi/form')?>" class="btn btn-sm btn-success" title="Tambah perguruan tinggi"><i class="fa fa-plus-circle"></i> Tambah</a>
        <?php }?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="example1" class="table table-striped table-hover">
            	<thead class="">
            		<tr>
            			<th width="5%">#</th>
            			<th width="40%">NAMA</th>
                        <th width="35%">NAME</th>
                        <th width="10%">KOTA</th>
            			<th width="10%">NEGARA</th>
            		</tr>
            	</thead>
            	<tbody></tbody>
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
            pageLength: 50,
            ajax: {
                url: "<?=site_url('api/kepegawaian/perguruan_tinggi')?>",
                type: "POST",
                data: {}
            },
            columns: [
                {
                    data: 'id_pt',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '';
                            <?php if(return_access_link(['kepegawaian/perguruan_tinggi/form'])){?>
                                txt_view += '<a style="font-size:14pt" class="m-1 text-success" href="<?=site_url('kepegawaian/perguruan_tinggi/form?id=')?>'+row.id_pt+'" title="Edit data"><i class="fa fa-edit"></i></a> '
                            <?php }?>
                        return txt_view
                    }
                },
                {
                    data: 'nama_pt',
                    className: 'fw-bold',
                    orderable: true,
                    render: function(data, type, row, index){
                        return data
                    }
                },
                {
                    data: "alamat_pt",
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                    	return data +', Telp. '+row.telp_pt
                    }
                },
                {
                    data: 'kota_pt',
                    render: function(data, type, row){
                        return data
                    }
                },
                {
                    data: 'negara_pt',
                    render: function(data, type, row){
                        return data
                    }
                }
            ],
            order: [[ 0, 'asc' ]]
        })
    }
</script>