<div class="card">
    <div class="card-header d-flex">
        <div class="flex-grow-1"><?=$title?></div>
        <?php if(return_access_link(['data/referensi/form'])){?>
            <a href="<?=site_url('data/referensi/form')?>" class="btn btn-sm btn-success" title="Tambah referensi"><i class="fa fa-plus-circle"></i> Tambah</a>
        <?php }?>
    </div>
    <div class="card-body">
        <div class="input-group">
            <span class="input-group-text">Referensi:</span>
            <select name="ref" id="ref" onchange="load_data(); localStorage.setItem('ref', this.value)">
                <option value="">All</option>
                <?php foreach ($list_ref as $k) {
                    echo '<option value="'.$k->field.'">'.$k->field.'</option>';
                }?>
            </select>
        </div>
        <div class="table-responsive">
            <table id="example1" class="table table-striped table-hover">
                <thead class="">
                    <tr>
                        <th width="5%">#</th>
                        <th width="">REF</th>
                        <th width="">CODE</th>
                        <th width="">NAME</th>
                        <th width="">DESCRIPTION</th>
                        <th width="">VALUE</th>
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
        $('#ref').val(localStorage.getItem('ref'))
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
                url: "<?=site_url('api/data/referensi')?>",
                type: "POST",
                data: {
                    ref: $('#ref').val(),
                }
            },
            columns: [
                {
                    data: 'id',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = '';
                            <?php if(return_access_link(['data/referensi/form'])){?>
                                txt_view += '<a style="font-size:14pt" class="m-1 text-success" href="<?=site_url('data/referensi/form?id=')?>'+row.id+'" title="Edit data"><i class="fa fa-edit"></i></a> '
                            <?php }?>
                        return txt_view
                    }
                },
                {
                    data: 'ref',
                    orderable: true,
                    render: function(data, type, row, index){
                        return data
                    }
                },
                {
                    data: "ref_code",
                    className: 'fw-bold',
                    orderable: true,
                    render: function(data, type, row){
                        return data
                    }
                },
                {
                    data: 'ref_name',
                    className: 'fw-bold',
                    render: function(data, type, row){
                        return data
                    }
                },
                {
                    data: 'ref_description',
                    render: function(data, type, row){
                        return data
                    }
                },
                {
                    data: 'ref_value',
                    render: function(data, type, row){
                        return data
                    }
                },
                {
                    data: 'ref_status',
                    render: function(data, type, row){
                        return data
                    }
                }
            ],
            order: [[ 1, 'asc' ]]
        })
    }
</script>