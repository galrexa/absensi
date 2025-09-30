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
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Unit Kerja:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="input_unit_kerja[]" id="input_unit_kerja" class="form-control" multiple onchange="load_data()"></select>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Jabatan:
            </div>
            <div class="col-sm-12 col-md-10">
                <select name="input_jabatan[]" id="input_jabatan" class="form-control" multiple onchange="load_data()"></select>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2 fw-bold">
                Perncarian:
            </div>
            <div class="col-sm-12 col-md-10">
                <input type="text" name="search" id="search" class="form-control" placeholder="NIK atau Nama Pegawai" oninput="load_data()">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-hover table-bordered">
                    	<thead class="">
                    		<tr>
                                <th width="10%" class="color-abu">BPP FILE</th>
                    			<th width="5%" class="color-abu">PERIODE</th>
                    			<th width="" class="color-abu">NAMA</th>
                                <th width="" class="color-abu">JABATAN</th>
                                <th width="" class="color-abu">UNIT KERJA</th>
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
            bFilter: false,
            bInfo: true,
            bAutoWidth: false,
            processing: true,
            serverSide: true,
            pageLength: 25,
            ajax: {
                url: "<?=site_url('api/kepegawaian/bpp_master')?>",
                type: "POST",
                data: {
                    unit_kerja: $('#input_unit_kerja').val(),
                    jabatan: $('#input_jabatan').val(),
                    periode: $('#tahun').val(),
                    search: $('#search').val(),
                    check_filter: $('#check_filter:checked').val(),
                }
            },
            columns: [
                {
                    data: 'pegawai_id',
                    orderable: false,
                    render: function(data, type, row){
                        var txt_view = ''
                            +'<div class="d-flex">'
                                +'<div class="dropdown">'
                                    +'<a class="dropdown-toggle text-primary fs-5" href="#" data-bs-toggle="dropdown"><i class="fa fa-edit"></i></a>'
                                    +'<ul class="dropdown-menu">'
                                        +'<li class="dropdown-item"><input type="file" name="files_user'+data+'" id="files_user'+data+'" data="'+data+'" class="files_user" onchange="prepare_upload(\''+row.periode+'\', \'#files_user'+data+'\', \'bpp\', \'?pegawai_id='+data+'&periode='+$('#tahun').val()+'\')"></li>'
                                    +'</ul>'
                                +'</div>'

                                if(row.id > 0){
                                    txt_view += '<a href="#" onclick="window.open(\'<?=site_url('file/viewer?id=')?>'+row.file_id+'\', \'\', \'top=100,left=300,width=700,height=700\')" title="Lihat file" class="m-1 text-success fs-5"><i class="fa fa-eye"></i></a> <a href="<?=site_url('file/download?id=')?>'+row.file_id+'" target="_blank" title="Unduh file" class="m-1 text-success fs-5"><i class="fa fa-file-pdf"></i></a>'
                                }
                        txt_view += '</div>'
                        return txt_view
                    }
                },
                {
                    data: 'periode',
                    className: 'fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = ''
                        if(row.periode!=0)
                            txt_view = '<i class="fas fa-check-circle text-success"></i> '
                        return txt_view + row.periode
                    }
                },
                {
                    data: 'nama',
                    className: 'fw-bold',
                    orderable: true,
                    render: function(data, type, row, index){
                        return data
                    }
                },
                {
                    data: 'jabatan_id',
                    className: '',
                    orderable: true,
                    render: function(data, type, row, index){
                        return row.jabatan_name
                    }
                },
                {
                    data: "unit_kerja_name_alt",
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        return data
                    }
                },
            ],
            order: [[ 0, 'asc' ]]
        })
    }


    function prepare_upload(id, element, jenis, url_query)
    {
        if(id!=0)
        {
            var cf = confirm('Apakah Anda ingin memperbaharui file bukti potong pajak dengan yang baru?')
            if(cf===true)
            {
                showModalInfo('<div class="fs-3 text-center fw-bold text-primary"><i class="fas fa-spinner fa-spin"></i></div>Harap menunggu sedang unggah.', 0)
                file_upload_form_whit_query(element, jenis, url_query); 
                setTimeout(() => [load_data(), $('#modal1').modal('hide')],1000)  
            }
        }else{
            showModalInfo('<div class="fs-3 text-center fw-bold text-primary"><i class="fas fa-spinner fa-spin"></i></div>Harap menunggu sedang unggah.', 0)
            file_upload_form_whit_query(element, jenis, url_query); 
            setTimeout(() => [load_data(), $('#modal1').modal('hide')],1000)
        }
    }
</script>