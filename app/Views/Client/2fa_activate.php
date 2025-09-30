<div class="row">
    <div class="col-md-6 col-sm-12">
        <?=form_open('', ['id'=>'form_totp_2fa_activation'])?>
            <div class="card">
                <div class="card-header">
                    <h4><?=$title?></h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col border-bottom">
                            <label class="fw-bold mb-2" <?php if($totp){echo 'title="2FA Aktif"';}else{echo 'title="2FA Tidak Aktif"';}?>>
                                <input type="checkbox" value="1" name="checkbox_2fa_active" id="checkbox_2fa_active" <?php if($totp){echo 'checked';}?>> Aktifkan 2FA
                            </label>
                        </div>
                    </div>
                    <div id="div_totp_qrcode" class="mt-2">
                        <?php if($totp){echo '<span class="text-success">2FA Aktif</span>';}else{echo '<span class="text-danger">2FA Tidak Aktif</span>';}?>
                    </div>
                </div>
            </div>
        <?=form_close()?>
    </div>
</div>
<script type="text/javascript">
    $('#checkbox_2fa_active').on('click', function(){
        var status = 0
        var kode = ''
        switch(this.checked) {
            case true:
                status = 1
                get_totp(status)
                break;
            default:
                var cf = confirm('Apakah Anda ingin tidak mengaktifkan 2FA?')
                if(cf==true){
                    get_totp(status)
                }else{
                    $(this).prop('checked', true)
                }
                break;
        }
    })


    function get_totp(status)
    {
        $('#div_totp_qrcode').html('<i class="fa fa-spinner"></i> Loading')
        $.get('<?=site_url('auth/totp/')?>', {status: status}, function(rs){
            $('#div_totp_qrcode').html(rs)
        })
    }


    $('#form_totp_2fa_activation').on('submit', function(e)
    {
        $('#btn_submit_totp').attr('disabled', true).html('<i class="fa fa-spinner"></i> Loading');
        let data = $(this);
        e.preventDefault();
        $.ajax({
            crossDomain: true,
            crossOrigin: true,
            dataType: 'json',
            type: "POST",
            data: data.serialize(),
            success: function(responseData, textStatus, jqXHR) {
                var dt = JSON.parse(JSON.stringify(responseData));
                $('input[name=<?=csrf_token()?>]').val(dt.csrf)
                $('#btn_submit_totp').prop('disabled', false).html('<i class="fa fa-check-circle"></i> Verify')
                if(dt.status==true){
                    window.location.assign('<?=base_url()?>');
                }else{
                    $('#kode_2fa').val('')
                    alert(dt.message)
                }
            },
            error: function(jqXHR, textStatus){
                alert('Error: '+textStatus);
                window.location.reload(true);
            },
        });
    });
</script>