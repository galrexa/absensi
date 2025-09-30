<div class="card">
    <div class="card-header d-flex">
        <div class="flex-grow-1"><?=$title?></div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 col-md-2 fw-bold">
                Periode:
            </div>
            <div class="col-sm-12 col-md-10">
                <div class="input-group">
                    <select name="tahun" id="tahun" class="me-3" onchange="load_data()">
                        <?php $arrayTahun =[]; foreach ($list_tahun as $key) {array_push($arrayTahun, $key->tahun);}?>
                        <?php if(!array_keys($arrayTahun, date('Y')) or empty($arrayTahun)){?>
                            <option value="<?=date('Y')?>" selected><?=date('Y')?></option>
                        <?php }?>
                        <?php foreach ($list_tahun as $key) {?>
                            <option value="<?=$key->tahun?>" <?php if($key->tahun==date('Y')){echo'selected';}?>><?=$key->tahun?></option>
                        <?php }?>
                    </select>
                    <label class=""><input type="checkbox" name="check_filter" id="check_filter" value="1" onchange="load_data()"> Terapkan filter</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-hover">
                    	<thead class="">
                    		<tr>
                                <th width="5%" class="color-abu">Lihat</th>
                                <th width="5%" class="color-abu">Unduh</th>
                                <th width="" class="color-abu">Keterangan</th>
                    		</tr>
                    	</thead>
                    	<tbody></tbody>
                    </table>
                </div>
            </th>
        </tr>
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
        select2_jabatan('#input_jabatan', '')
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
                url: "<?=site_url('api/kepegawaian/bukti_potong_pajak')?>",
                type: "POST",
                data: {
                    periode: $('#tahun').val(),
                    pegawai_id: <?=session()->get('pegawai_id')?>,
                    check_filter: $('#check_filter:checked').val(),
                }
            },
            columns: [
                {
                    data: 'periode',
                    className: 'fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view =  '<a href="#" title="File belum tersedia." class="m-1 fs-5 text-danger"><i class="fa fa-question-circle"></i></a>'
                        if(row.id > 0){
                            txt_view = '<a href="#" onclick="window.open(\'<?=site_url('file/viewer?id=')?>'+row.file_id+'\', \'\', \'top=100,left=300,width=700,height=700\')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat file" class="m-1 text-success fs-5"><i class="fa fa-eye"></i></a>'
                        }
                        return txt_view
                    }
                },
                {
                    data: 'periode',
                    className: 'fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view =  '<a href="#" title="File belum tersedia." class="m-1 fs-5 text-danger"><i class="fa fa-question-circle"></i></a>'
                        if(row.id > 0){
                            txt_view = '<a href="<?=site_url('file/download?id=')?>'+row.file_id+'" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Unduh file" class="m-1 text-success fs-5"><i class="fa fa-file-pdf"></i></a>'
                        }
                        return txt_view
                    }
                },
                {
                    data: 'periode',
                    className: 'fw-bold',
                    orderable: true,
                    render: function(data, type, row, index){
                        var txt_view =  '<div class="m-1" style="font-size:">Bukti Potong Periode '+data+'</div>'
                        return txt_view
                    }
                },
            ],
            order: [[ 0, 'desc' ]]
        })
    }
</script>