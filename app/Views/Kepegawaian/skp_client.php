<div class="card">
    <div class="card-header d-flex">
        <div class="flex-grow-1"><?=$title?></div>
    </div>
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Periode:
            </div>
            <div class="col-sm-12 col-md-10">
                <div class="input-group">
                    <select name="tahun" id="tahun" class="" onchange="load_data(); localStorage.setItem('tahun', this.value)">
                        <?php $arrayTahun =[]; foreach ($list_tahun as $key) {array_push($arrayTahun, $key->tahun);}?>
                        <?php if(!array_keys($arrayTahun, date('Y')) or empty($arrayTahun)){?>
                            <option value="<?=date('Y')?>" selected><?=date('Y')?></option>
                        <?php }?>
                        <?php foreach ($list_tahun as $key) {?>
                            <option value="<?=$key->tahun?>" <?php if($key->tahun==date('Y')){echo'selected';}?>><?=$key->tahun?></option>
                        <?php }?>
                    </select>
                    <select name="bulan" id="bulan" class="me-3" onchange="load_data(); localStorage.setItem('bulan', this.value)">
                        <?php foreach (array_bulan() as $key=>$value) {?>
                            <option value="<?=$key?>" <?php if($key==date('m')){echo'selected';}?>><?=$value?></option>
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
                                <th width="10%" class="color-abu"><div class="d-flex">Slip Gaji</div></th>
                                <th width="" class="color-abu">Periode</th>
                                <th width="" class="color-abu">Status</th>
                                <th width="" class="color-abu">Hak Kuangan</th>
                                <th width="" class="color-abu">PPH 21</th>
                                <th width="" class="color-abu">Iuran BPJS</th>
                                <th width="" class="color-abu">Penghasilan Bersih</th>
                    		</tr>
                    	</thead>
                    	<tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<link href="<?=base_url()?>assets/css/datatables.min.css" rel="stylesheet" />
<script src="<?=base_url()?>assets/js/datatables.min.js"></script>
<script src="<?=base_url()?>assets/js/custom.js"></script>
<link href="<?=base_url('assets/')?>css/select2.min.css" rel="stylesheet" />
<script src="<?=base_url('assets/')?>js/select2.full.min.js"></script>
<script type="text/javascript">
	$(function(){
        $('#tahun').val(localStorage.getItem('tahun'))
        $('#bulan').val(localStorage.getItem('bulan'))
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
                url: "<?=site_url('api/kepegawaian/hak_keuangan')?>",
                type: "POST",
                data: {
                    periode: $('#tahun').val()+'-'+$('#bulan').val(),
                    pegawai_id: <?=session()->get('pegawai_id')?>,
                    check_filter: $('#check_filter:checked').val(),
                }
            },
            columns: [
                {
                    data: 'pegawai_id',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row){
                        var txt_view = '<div class="d-flex">'
                        if(row.file!=0 && row.file_sign==0){
                            txt_view += '<a href="#" onclick="window.open(\'<?=site_url('file/viewer?file=skp&id=')?>'+row.unix_id+'\', \'\', \'top=100,left=300,width=700,height=700\')" title="Lihat file yang belum bertanda tangan." class="m-1 fs-5 text-warning"><i class="fa fa-eye"></i></a>'
                        }else if(row.file_sign!=0){
                            txt_view += '<a href="#" onclick="window.open(\'<?=site_url('file/viewer?file=skp&id=')?>'+row.unix_id+'\', \'\', \'top=100,left=300,width=700,height=700\')" title="Lihat file yang bertanda tangan." class="m-1 fs-5 text-success"><i class="fa fa-eye"></i></a>'
                            txt_view += '<a href="<?=site_url('file/download?file=skp&id=')?>'+row.unix_id+'" target="_blank" title="unduh slip gaji yang bertanda tangan." class="m-1 fs-5 text-success"><i class="fa fa-file-pdf"></i></a>'
                        }else{
                            txt_view += '<a href="#" title="File belum tersedia." class="m-1 fs-5 text-danger"><i class="fa fa-question-circle"></i></a>'
                        }
                        txt_view += '</div>'
                        return txt_view
                    }
                },
                {
                    data: 'PERIODE',
                    className: 'fw-bold',
                    orderable: true,
                    render: function(data, type, row, index){
                        return formatWaktu(data,3)
                    }
                },
                {
                    data: 'STATUS',
                    className: '',
                    orderable: false,
                    render: function(data, type, row, index){
                        return data +' ('+row.status_kawin+')'
                    }
                },
                {
                    data: "PENGHASILAN",
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        return number_format(data)
                    }
                },
                {
                    data: "PPH",
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        return number_format(data)
                    }
                },
                {
                    data: "IURAN",
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        return number_format(data)
                    }
                },
                {
                    data: "NILTERIMA",
                    className: 'fw-bold',
                    orderable: true,
                    render: function(data, type, row){
                        return number_format(data)
                    }
                },
            ],
            order: [[ 1, 'desc' ]]
        })
    }
</script>