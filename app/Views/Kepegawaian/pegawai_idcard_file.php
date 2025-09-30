<?php 
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

print_r($data);
$Nama = ($data->nama)?:'-';
$Nip = ($data->nip)?:'-';
$Jabatan = ($data->jabatan_name)?:'-';
$Gugus = ($data->gugustugas)?:'-';
$Masa_berlaku = '-';
if($data->periode_awal<>'' && $data->periode_akhir<>''){
	$Masa_berlaku = tanggal_range($data->periode_awal, $data->periode_akhir);
}
$Foto = FCPATH.'assets/img/pegawai_default_male.png';
if($data->kelamin==2){
	$Foto = FCPATH.'assets/img/pegawai_default_female.png';
}
$QRCode = FCPATH.'assets/img/qr_default.png';
if(file_exists($data->foto_pegawai)){
	$Foto = $data->foto_pegawai;
}
if(file_exists($data->qrcode)){
	$QRCode = $data->qrcode;
}

$idcard_name = str_replace([' ','  ','(',')','`','"'], '_', $data->nama).'__IDCard.docx';
$idcard_path_folder = WRITEPATH.'_pegawai/temp_idcard/';
$idcard_fullpath = $idcard_path_folder . $idcard_name;
switch (true) {
	// case (array_keys([10,55,35,49,50,1,2,3,4,5,6,7,8,9,67,68,70,71,72,73,74,75,76,77,78,79,80,81,82,83], $data->jabatan_id)):
	// 	$file_template = WRITEPATH.'templates/idcard_pns+prof.docx';
	// 	break;
	default:
		$file_template = WRITEPATH.'templates/idcard1.docx';
		break;
}

if(file_exists($file_template)){
	// $phpWord = new \PhpOffice\PhpWord\PhpWord();
	// $document = $phpWord->loadTemplate($file_template);
	$document = new TemplateProcessor($file_template);
	$document->setValue('nama', strtoupper($Nama));
	$document->setValue('nip', $Nip);
	$document->setValue('jabatan', ucfirst($Jabatan));
	$document->setValue('gugus', ucfirst($Gugus));
	$document->setValue('masa_berlaku', ucfirst($Masa_berlaku));
	// $document->setImageValue('image2.png', $Foto);
	// $document->setImageValue('image3.png', $QRCode);
	$document->setImageValue('foto', array('path'=>$Foto,'width'=>200,'height'=>150,'ratio'=>false,'positioning'=>'relative','marginTop'=>0,'marginLeft'=>0));
	$document->setImageValue('qrcode', array('path'=>$QRCode,'width'=>50,'height'=>50,'ratio'=>false,'positioning'=>'relative','marginTop'=>0,'marginLeft'=>0));
	ob_clean();
	$document->saveAs($idcard_fullpath);

	/*	CREATE PDF USING DOMPDF	*/
	// $pathDom = realpath(APPPATH.'Libraries/PHPWord-develop/vendor/dompdf');
	// \PhpOffice\PhpWord\Settings::setPdfRendererPath($pathDom);
	// \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
	// $document2 = IOFactory::load($idcard_fullpath);
	// $document2->save($idcard_fullpath.'.pdf', 'PDF');

	/*	CREATE PDF USING LIBREOFFICE/OPENOFFICE  */
    putenv('PATH=/usr/local/bin:/bin:/usr/bin:/usr/local/sbin:/usr/sbin:/sbin');
    putenv('HOME=/tmp');
	$tSys = system("soffice  --headless --convert-to pdf --outdir \"".$idcard_path_folder."\" ".$idcard_fullpath." ");
	$idcardPDF_FullPath = $idcard_path_folder . str_replace(['docx','DOCX'], 'pdf', $idcard_name);
	$idcardFront_FullPath = $idcard_path_folder . str_replace(['.docx','.DOCX'], '_front.jpg', $idcard_name);
	$idcardEnd_FullPath = $idcard_path_folder.'/'.str_replace(['.docx','.DOCX'], '_end.jpg', $idcard_name);
	shell_exec("convert -density 300 -trim ".$idcardPDF_FullPath."[0] -quality 100 ".$idcardFront_FullPath." ");
	shell_exec("convert -density 300 -shave \"0%x0%\" -trim ".$idcardPDF_FullPath."[1] -quality 100 ".$idcardEnd_FullPath." ");

	switch ($file) {
		case 'master':
			$fileZip = create_zip_file($idcard_name.'.zip', $idcard_path_folder);
	        header('Content-Disposition: attachment; filename='.$idcard_name.'.zip');
	        header('Content-Transfer-Encoding: binary');
	        header('Expires: 0');
	        header('Cache-Control: must-revalidate');
	        header('Pragma: public');
	        header('Content-Length: ' . filesize($fileZip));
	        ob_clean();
	        flush();
	        readfile($fileZip);
	        @unlink($fileZip);
	        exit;
			break;
		default:
	        header('Content-Disposition: attachment; filename='.str_replace(['docx','DOCX'], 'pdf', $idcard_name));
	        header('Content-Transfer-Encoding: binary');
	        header('Expires: 0');
	        header('Cache-Control: must-revalidate');
	        header('Pragma: public');
	        header('Content-Length: ' . filesize($idcardPDF_FullPath));
	        ob_clean();
	        flush();
	        readfile($idcardPDF_FullPath);
	        @unlink($idcard_fullpath);
	        @unlink($idcard_fullpath.'.pdf');
			@unlink($idcardPDF_FullPath);
			@unlink($idcardFront_FullPath);
			@unlink($idcardEnd_FullPath);
	        exit;
			break;
	}
}