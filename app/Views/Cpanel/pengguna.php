<div class="card">
    <div class="card-header d-flex">
        <div class="flex-grow-1"><?=$title?></div>
        <?php if(return_access_link(['data/pengguna/form'])){?>
            <a href="<?=site_url('data/pengguna/form')?>" class="btn btn-sm btn-success" title="Tambah unit kerja"><i class="fa fa-plus-circle"></i> Tambah</a>
        <?php }?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="example1" class="table table-striped table-hover">
            	<thead class="">
            		<tr>
            			<th width="5%">#</th>
            			<th width="5%">ID</th>
                        <th width="">NAME</th>
                        <th width="">URUTAN</th>
            			<th width="">STATUS</th>
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
            pageLength: 25,
            ajax: {
                url: "<?=site_url('api/data/pengguna')?>",
                type: "POST",
                data: {}
            },
            columns: [
                {
                    data: 'id',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '';
                            <?php if(return_access_link(['data/pengguna/form'])){?>
                                txt_view = '<a style="font-size:14pt" class="m-1 text-success" href="<?=site_url('data/pengguna/form?id=')?>'+row.hash+'" title="Edit data"><i class="fa fa-edit"></i></a> '
                            <?php }?>
                        return txt_view
                    }
                },
                {
                    data: 'id',
                    className: 'text-center fw-bold',
                    orderable: true,
                    render: function(data, type, row, index){
                        return data
                    }
                },
                {
                    data: "username",
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                    	return data
                    }
                },
                {
                    data: 'email',
                    render: function(data, type, row){
                        return data
                    }
                },
                {
                    data: 'status',
                    render: function(data, type, row){
                        return data
                    }
                }
            ],
            order: [[ 0, 'asc' ]]
        })
    }
</script>