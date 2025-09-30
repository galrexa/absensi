<?php
create_new_qrcode($QRCodeUrl, $QRName, true);
$QR = create_file_to_base64($QRName);
@unlink($QRName);
?>
<div class="row">
    <div class="col-12 mb-2">
        QR & Kode TOTP: <span class="fw-bold"><?=$secret?></span>
    </div>
</div>
<!-- <div class="row">
    <div class="col-12">
        QR
    </div>
</div> -->
<div class="row">
    <div class="col-md-4 col-sm-12 mb-2">
        <img src="<?=$QR?>" class="img-thumbnail" width="150">
    </div>
    <div class="col-md-8 col-sm-12 mb-2">
        <div class="input-group mb-2">
            <div class="input-group-text" style="width:100px">Kode</div>
            <input type="text" name="kode_2fa" id="kode_2fa" class="form-control" placeholder="Input 6 digit kode OTP">
        </div>
        <div class="input-group mb-2">
            <div class="input-group-text" style="width:100px">Password</div>
            <input type="password" name="password" id="password" class="form-control" placeholder="Input Password">
            <label class="input-group-text"><i id="addon-eye" class="fa fa-eye" onclick="showHidePassword('#password', '#addon-eye')"></i></label>
        </div>
        <input type="hidden" name="id" id="id" value="<?=$id?>">
        <button id="btn_submit_totp" class="" style="width:150px; color:green"><i class="fa fa-check-circle"></i> Verifikasi</button>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="mt-3 alert alert-warning" style="font-size:10pt">
            <ol>
                <li>Pindai QR atau input kode TOPTP menggunakan aplikasi otentikator (Ex. Google Otentikator, dan sejenisnya)</li>
                <li>Input kode dengan 6 digit kode OTP yang terdapat pada aplikasi otentikator</li>
                <li>Input password dengan password yang anda gunakan untuk login</li>
                <li>Klik tombol <b>Verifikasi</b></li>
            </ol>
        </div>
    </div>
</div>