<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <?=$title?>
            </div>
            <div class="col"></div>
        </div>
        <hr>
        <div class="row">
            <div class="col-6 mb-2">
                Unit Kerja:
                <select name="input_unit_kerja[]" id="input_unit_kerja" class="form-control" multiple onchange="load_data()"></select>
            </div>
            <div class="col-6 mb-2">
                Jabatan:
                <select name="input_jabatan[]" id="input_jabatan" class="form-control" multiple onchange="load_data()"></select>
            </div>
        </div>
        <div class="row">
            <div class="col-6 mb-2">
                JenisKelamin:
                <select name="input_kelamin[]" id="input_kelamin" class="form-control" multiple onchange="load_data()"></select>
            </div>
            <div class="col-6 mb-2">
                StatusPNS:
                <select name="input_status_pns[]" id="input_status_pns" class="form-control" multiple onchange="load_data()"></select>
            </div>
        </div>
        <div class="row">
            <div class="col-6 mb-2">
                Usia:
                <select name="input_usia" id="input_usia" onchange="load_data()" class="form-control">
                    <option value="">Semua</option>
                    <option value="<=">Usia Sampai 45</option>
                    <option value=">">Usia Lebih 45</option>
                </select>
            </div>
            <div class="col-6 mb-2">
                Bulan Kelahiran:
                <select name="input_bulan" id="input_bulan" onchange="load_data()" class="form-control">
                    <option value="">Semua</option>
                        <?php
                            foreach (array_bulan() as $key => $value) {
                                echo '<option value="'.$key.'" ';
                                if(date('m')==$key){echo 'selected';}
                                echo '>'.$value.'</option>';
                            }
                        ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-2">
                <table id="example1" class="table table-striped table-hover">
                	<thead class="bg-light">
                		<tr>
                            <th width="3%">#</th>
                            <th width="8%">FOTO</th>
                            <th width="">NAMA</th>
                		</tr>
                	</thead>
                	<tbody style="vertical-align: top;"></tbody>
                </table>
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
        select2_jabatan('#input_jabatan', '<?=$unit?>')
        select2_referensi('#input_kelamin', 'gender')
        select2_referensi('#input_status_pns', 'pegawai_status_pns')
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
            layout: {
                topEnd: {
                    search: {
                        placeholder: 'NIK, NIP, Nama, HP & Tempat Lahir'
                    }
                }
            },
            ajax: {
                url: '<?=site_url('api/kepegawaian/ulang_tahun?id='.$unit)?>',
                type: 'POST',
                data: {
                    unit_kerja: $('#input_unit_kerja').val(),
                    jabatan: $('#input_jabatan').val(),
                    kelamin: $('#input_kelamin').val(),
                    status_pns: $('#input_status_pns').val(),
                    usia: $('#input_usia').val(),
                    bulan: $('#input_bulan').val(),
                }
            },
            columns: [
                {
                    data: 'pegawai_id',
                    className: 'text-center fw-bold',
                    orderable: false,
                    render: function (data, type, row, index) {
                        return index.row + index.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'nip',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row, index){
                        var foto_peg = thumbnail_default_pegawai(row.kelamin)
                        if(row.foto_pegawai_temp)
                        {
                            var xqr = row.foto_pegawai_temp
                            foto_peg = (xqr.split('icon_p/'))[1]
                        }
                        return '<img src="<?=base_url('assets/img/icons/pegawai/')?>'+foto_peg+'" class="img-thumbnail">'
                    }
                },
                {
                    data: 'countdown',
                    className: '',
                    orderable: true,
                    render: function(data, type, row){
                        var countdown = '<b class="bg-warning ps-1 pe-1 text-danger"><i class="fa fa-bell"></i> Hari ini Berulang tahun</b>';
                        if(row.countdown<0)
                        {
                            countdown = '<b class="bg-danger ps-1 pe-1 text-white">H '+row.countdown+'</b>';
                        }else if(row.countdown >0){
                            countdown = '<b class="bg-success ps-1 pe-1 text-white">H +'+row.countdown+'</b>';
                        }
                        var txt_view = ''+
                            '<div class="row">'+
                            '    <div class="col-12 fw-bold" style="font-size:14pt;">'+
                            '       '+row.gelar_depan+' '+row.nama+' '+row.gelar_belakang+
                            '    </div>'+
                            '</div>'+
                            '<div class="row">'+
                            '    <div class="col-12">'+
                            '        <b>Kelamin:</b> '+
                            '       '+row.kelamin_name+''+
                            '    </div>'+
                            '</div>'+
                            '<div class="row">'+
                            '    <div class="col-12">'+
                            '        <b>Tempat, Tanggal Lahir:</b> '+
                            '       '+row.tempat_lahir+', '+row.tanggal_lahir+''+
                            '    </div>'+
                            '</div>'+
                            '<div class="row">'+
                            '    <div class="col-12">'+
                            '        <b>Jabatan:</b> '+
                            '       '+row.jabatan+''+
                            '    </div>'+
                            '</div>'+
                            '<div class="row">'+
                            '    <div class="col-12">'+
                            '        <b>Unit Kerja:</b> '+
                            '       '+row.unit_kerja+''+
                            '    </div>'+
                            '</div>'+
                            '<div class="row">'+
                            '    <div class="col-12">'+
                            '        <b>Usia:</b> '+
                            '       '+row.umur+''+ ' => '+countdown+
                            '    </div>'+
                            '</div>'+
                            ''
                        return txt_view
                    }
                },
            ],
            order: [[ 0, 'asc' ]]
        })
    }


    var unit_kerja_internal = ''
    <?php $ck=0; foreach(session()->get('units') as $k) if($k->unit_kerja_id==$unit) {$ck+=1;?>
        unit_kerja_internal += '<option value="<?=$k->unit_kerja_id?>" selected="selected"><?=$k->unit_kerja_name?></option>'
    <?php } ?>
    $('#input_unit_kerja').append(unit_kerja_internal).trigger('change')
    <?php if($unit<>''){?>
        <?php if($ck>0){?>
            .prop('disabled', true)
        <?php }else{?>
            // .append('<option value="" selected="selected"><?=$unit?></option>').trigger('change')
        <?php }?>
    <?php }?>
</script>