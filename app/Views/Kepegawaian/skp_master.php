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
                    <select name="bulan" id="bulan" class="" onchange="load_data(); localStorage.setItem('bulan', this.value)">
                        <?php foreach (array_bulan() as $key=>$value) {?>
                            <option value="<?=$key?>" <?php if($key==date('m')){echo'selected';}?>><?=$value?></option>
                        <?php }?>
                    </select>
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
        <div class="row mb-1">
            <div class="col-sm-12 col-md-2"></div>
            <div class="col-sm-12 col-md-10">
                <?php if(return_access_link(['api/kepegawaian/skp_master/generate'])){?>
                    <a href="#" class="btn btn-sm btn-warning fw-bold" onclick="generate_slip()"><i class="fa fa-file-pdf"></i> Buat Slip Gaji</a>
                    <script type="text/javascript">
                        function generate_slip(){
                            var cf = confirm('Lanjutkan membuat slip gaji?')
                            if(cf===true){
                                showModalInfo('<div class="fs-1 text-center fw-bold text-primary"><i class="fas fa-spinner fa-spin"></i></div>Proses pembuatan slip gaji sedang berlangsung. Harap tunggu dan jangan tutup atau perbarui halaman ini.', 0)
                                $.post('<?=site_url('api/kepegawaian/skp_master/generate')?>', {
                                    unit_kerja: $('#input_unit_kerja').val(),
                                    jabatan: $('#input_jabatan').val(),
                                    periode: $('#tahun').val()+'-'+$('#bulan').val(),
                                    search: $('#search').val()
                                }, function(r){
                                    $('#modal1_body').html('<div class="">'+ r.message +'</div>')
                                    $('#modal1_header').show()
                                    load_data()
                                })
                            }
                        }
                    </script>
                <?php }?>
                <?php if(return_access_link(['api/kepegawaian/skp_master/generate'])){?>
                    <a href="#" class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#modal_tte"><i class="fa fa-pencil-alt"></i> Tanda Tangan Elektronik</a>
                <?php }?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-hover table-bordered">
                    	<thead class="">
                    		<tr>
                                <th width="10%" class="color-abu"><div class="d-flex">SLIP GAJI</div></th>
                    			<th width="" class="color-abu">PERIODE</th>
                    			<th width="" class="color-abu">NAMA</th>
                                <th width="" class="color-abu">JABATAN</th>
                                <th width="" class="color-abu">STATUS</th>
                                <th width="" class="color-abu">HAK KEUANGAN</th>
                                <th width="" class="color-abu">PPH 21</th>
                                <th width="" class="color-abu">IURAN BPJS</th>
                                <th width="" class="color-abu">BERSIH</th>
                    		</tr>
                    	</thead>
                    	<tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if(return_access_link(['api/kepegawaian/skp_master/generate'])){?>
    <div class="modal" tabindex="-1" id="modal_tte" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header color-red fw-bold" id="modal_tte_header">
                    <h5 class="modal-title">Form TTE</h5>
                </div>
                <div class="modal-body" id="modal_tte_body">
                    <div class="row mb-2">
                        <div class="col-sm-12 col-md-12">
                            <ul>
                                <li>Form ini digunakan untuk menandatangani data hak keuangan yang terdapat pada tabel. Penandatanganan hanya dapat dilakukan oleh Kepala Sekretariat atau pejabat yang berwenang.</li>
                                <li>Hanya akan menandatangani data hak keuangan yang belum bertanda tangan.</li>
                                <li>Proses ini mungkin memakan waktu, harap tunggu hingga selesai.</li>
                            </ul>
                            <!-- <hr> -->
                            <div class="input-group">
                                <b class="input-group-text fw-bold">Passphrase* :</b>
                                <input type="password" name="passphrase" id="passphrase" placeholder="Passphrase TTE" class="form-control" autofocus>
                                <label class="input-group-text">
                                    <i id="iPassword" class="fa fa-eye" onclick="showHidePassword('#passphrase', '#iPassword')"></i>
                                    <i class="fa fa-question-circle text-warning ms-2" title="Passphrase/Password Tanda Tangan Elektronik (TTE)"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="modal_tte_footer">
                    <button type="button" class="btn color-red" onclick="sign_file()"><i class="fas fa-pencil-alt"></i> Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function sign_file()
        {
            var pass = $('#passphrase').val()
            if(pass!=''){
                $('#passphrase').val('')
                $('#modal_tte').modal('hide')
                showModalInfo('<div class="fs-1 text-center fw-bold text-primary"><i class="fas fa-spinner fa-spin"></i></div>Proses penandatanganan slip gaji sedang berlangsung. Harap tunggu dan jangan tutup atau perbarui halaman ini.', 0)
                $.post('<?=site_url('api/kepegawaian/skp_master/tte')?>', {
                    unit_kerja: $('#input_unit_kerja').val(),
                    jabatan: $('#input_jabatan').val(),
                    periode: $('#tahun').val()+'-'+$('#bulan').val(),
                    search: $('#search').val(),
                    passphrase: pass
                }, function(r){
                    $('#modal1_body').html('<div class="">'+ r.message +'</div>')
                    $('#modal1_header').show()
                    load_data()
                })
            }else{
                alert('Passphrase tidak boleh kosong!')
                $('#passphrase').focus()
            }
        }
        $('#passphrase').on('keypress', function(event) {
            if (event.which === 13) {
                sign_file()
            }
        });
    </script>
<?php }?>
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
            bFilter: false,
            bInfo: true,
            bAutoWidth: false,
            processing: true,
            serverSide: true,
            pageLength: 25,
            ajax: {
                url: "<?=site_url('api/kepegawaian/skp_master')?>",
                type: "POST",
                data: {
                    unit_kerja: $('#input_unit_kerja').val(),
                    jabatan: $('#input_jabatan').val(),
                    tahun: $('#tahun').val(),
                    bulan: $('#bulan').val(),
                    search: $('#search').val(),
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
                            txt_view += '<a href="#" onclick="window.open(\'<?=site_url('file/viewer?file=skp&id=')?>'+row.unix_id+'\', \'\', \'top=100,left=300,width=700,height=700\')" title="Lihat file yang belum ditanda tangani." class="m-1 text-warning fs-5"><i class="fa fa-eye"></i></a>'
                        }else if(row.file_sign!=0){
                            txt_view += '<a href="#" onclick="window.open(\'<?=site_url('file/viewer?file=skp&id=')?>'+row.unix_id+'\', \'\', \'top=100,left=300,width=700,height=700\')" title="Lihat file yang sudah ditanda tangani." class="m-1 text-success fs-5"><i class="fa fa-eye"></i></a>'
                            txt_view += '<a href="<?=site_url('file/download?file=skp&id=')?>'+row.unix_id+'" target="_blank" title="unduh slip gaji yang sudah ditanda tangani." class="m-1 text-success fs-5"><i class="fa fa-file-pdf"></i></a>'
                        }else{
                            txt_view += '<a href="#" title="File belum tersedia." class="m-1 text-danger fs-5"><i class="fa fa-question-circle"></i></a>'
                        }
                        txt_view += '</div>'
                        return txt_view
                    }
                },
                {
                    data: 'PERIODE',
                    className: 'fw-bold',
                    orderable: false,
                    render: function(data, type, row, index){
                        var txt_view = data
                        return formatWaktu(txt_view,3)
                    }
                },
                {
                    data: 'NMPPNPN',
                    className: 'fw-bold',
                    orderable: true,
                    render: function(data, type, row, index){
                        return data
                    }
                },
                {
                    data: 'JABATAN',
                    className: '',
                    orderable: true,
                    render: function(data, type, row, index){
                        return data
                    }
                },
                {
                    data: 'STATUS',
                    className: '',
                    orderable: true,
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
            order: [[ 0, 'asc' ]]
        })
    }
</script>