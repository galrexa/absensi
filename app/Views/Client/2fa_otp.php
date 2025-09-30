<?=form_open('', ['id'=>'form_otp_2fa', 'class'=>'m-2'])?>
    <div class="card col-md-6 col-sm-12">
        <div class="card-header">
            <h4><?=$title?></h4>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4 col-sm-12">
                    <span class="fw-bold">KodeAuth:</span>
                </div>
                <div class="col-md-8 col-sm-12">
                    <input type="text" name="kode_2fa" class="form-control" placeholder="Kode 2FA">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn_submit_otp">Submit</button>
        </div>
    </div>
<?=form_close()?>
<script type="text/javascript">
    $('#form_otp_2fa').on('submit', function(e)
    {
        $('.btn_submit_otp').prop('disabled', true).html('Wait Proccess...');
        let data = $(this);
        e.preventDefault();
        $.ajax({
            crossDomain: true,
            crossOrigin: true,
            dataType: 'json',
            type: "POST",
            data: data.serialize(),
            success: function(responseData, textStatus, jqXHR) {
                var dt = responseData
                if(dt.status==1)
                {
                    window.location.assign('<?=base_url()?>');
                }else{
                    alert(dt.message.replace(/<p>|<\/p>/g, ""));
                }
                $('.btn_submit_otp').prop('disabled', false).html('Submit');
                $('input[name=<?=csrf_token()?>]').val(dt.csrf);
            },
            error: function(jqXHR, textStatus){
                alert('Error: '+textStatus);
                window.location.reload(true);
            },
        });
    });
</script>