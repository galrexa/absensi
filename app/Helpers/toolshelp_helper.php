<?php
use CodeIgniter\CodeIgniter;
use Mpdf\Mpdf;
// use PhpOffice\PhpWord\PhpWord;
// use PhpOffice\PhpWord\TemplateProcessor;

// $uri = new \CodeIgniter\HTTP\URI(current_url(true));

function hello(){
   echo "Hello Function";
}


function testaaa($content)
{
   $db = db_connect();
   $db->table('aaa')->insert(['content'=>$content]);
}
function log_tte($content)
{
   $db = db_connect();
   $db->table('logs_tte')->insert([
      'app_id' => session()->get('id_app'),
      'user_id' => session()->get('id'),
      'ip_address' => $_SERVER['REMOTE_ADDR'],
      'log_time' => date('Y-m-d H:i:s'),
      'message' => $content
   ]);
}

/*
*  manage app_file
*/
function link_files_by_id($id, $fieldTaget='', $jml=1, $status=1, $idList='list_file')
{
   $rs = '<ol id="'.$idList.'" style="font-size:9pt;">';
   $db = db_connect();
   $q = $db->query("SELECT * FROM app_files WHERE id IN ? ", [explode(',',$id)])->getResult();
   if(!empty($q))
   {
      foreach ($q as $r) {
         // $rs .= '<li id="li-file-'.string_to($r->id,'encode').'"><a href="'.site_url('file/download?id='.string_to($r->id,'encode')).'" target="_blank" title="Unduh File">'.$r->client_name.'</a>';
         $rs .= '<li id="li-file-'.string_to($r->id,'encode').'"><a href="'.site_url('file/download?id='.$r->id).'" target="_blank" title="Unduh File">'.$r->client_name.'</a>';
         if($fieldTaget<>'' && ($status==1 || return_roles([1]))){
            $rs .= '<a href="#" title="Hapus File" onclick="file_deleted(\''.string_to($r->id,'encode').'\', \''.$fieldTaget.'\', \''.$jml.'\')" class="ml-2"><i class="fa fa-trash"></i></a>';
         }
         $rs .= '</li>';
      }
   }
   $rs .= '</ol>';
   return $rs;
}

function files_delete_by_id($id)
{
   $rs = 0;
   $db = db_connect();
   $q = $db->query("SELECT * FROM app_files WHERE id IN ? ", [explode(',',$id)])->getResult();
   if(!empty($q))
   {
      foreach ($q as $r) {
         $rs += 1;
         @unlink($r->path);
         $db->table('app_files')->delete(['id'=>$r->id]);
      }
   }
   return $rs;
}
function files_path_by_id($id)
{
   $rs = '';
   $db = db_connect();
   $q = $db->query("SELECT * FROM app_files WHERE id IN ? ", [explode(',',$id)])->getRow();
   if(!empty($q))
   {
         $rs = $q->path;
   }
   return $rs;
}
function files_camera_presensi($id, $height=100)
{
   $rs = '<div id="img_cam_'.$id.'" class="d-flex">';
   $db = db_connect();
   $q = $db->query("SELECT * FROM app_files WHERE id IN ? ", [explode(',',$id)])->getResult();
   if(!empty($q))
   {
      foreach ($q as $r) {
         $rs .= '<span id="cam-'.string_to($r->id,'encode').'" class="m-1 border rounded text-center cam_view"><img src="'.create_file_to_base64($r->path).'" class="rounded d-block" height="'.$height.'"><i style="font-size:6pt">'.$r->last_change.'</i></span>';
      }
   }
   $rs .= '</div>';
   return $rs;
}

/*
* mini data pegawai
*/
function mini_field_pegawai_by_id($id)
{
   $db = db_connect();
   return $db->query("SELECT pegawai_id, nama, nip, nik, jabatan_id, unit_kerja_id from pegawai where pegawai_id=? ", [$id])->getRow();
}


function selisih_jam($lebihBesar='', $lebihKecil='')
{
   $db = db_connect();
   return $db->query("SELECT TIMEDIFF('$lebihBesar', '$lebihKecil') as field ")->getRow()->field;
}

function time_to_minute($time)
{
   $db = db_connect();
   return $db->query("SELECT TIME_TO_SEC('$time') / 60 as field ")->getRow()->field;
}


// check radius absensi
function check_location_in_radius_absen($clat, $clong)
{
   $info = ['status'=>false,'message'=>'Anda berada di luar area absen.'];
   $rr = new App\Models\PresensiModel();
   $data_area = $rr->list_ms_area();
   if(!empty($data_area))
   {
      $status = false;
      foreach ($data_area as $k) {
         $xll = explode(',', $k->latlong);
         $centerLat = $xll[0];
         $centerLong = $xll[1];
         $radius = $k->range;
         $rs = cekAreaRadiusMeter($clat, $clong, $centerLat, $centerLong, $radius);
         if($rs==true){
            $info['status'] = $rs;
            $info['message'] = 'Anda berada di dalam area '.$k->name;
         }
      }
   }else{
      $info['message'] = 'Maaf, tidak ada area untuk absen.';
   }
   return $info;
}

function cekAreaRadius($lat, $lng, $centerLat, $centerLng, $radiusKm) {
    $distance = hitungJarakHaversine($lat, $lng, $centerLat, $centerLng);
    return $distance <= $radiusKm;
}
function cekAreaRadiusMeter($lat, $lng, $centerLat, $centerLng, $radiusMeter) {
    $distanceMeter = hitungJarakHaversineMeter($lat, $lng, $centerLat, $centerLng);
    return $distanceMeter <= $radiusMeter;
}

function hitungJarakHaversine($lat1, $lng1, $lat2, $lng2) {
    $earthRadius = 6371; // Radius bumi dalam km
    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLng/2) * sin($dLng/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $earthRadius * $c;
}
function hitungJarakHaversineMeter($lat1, $lng1, $lat2, $lng2) {
    $earthRadius = 6371000; // Radius bumi dalam meter
    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLng/2) * sin($dLng/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $earthRadius * $c;
}

function konversiJarak($distance, $from, $to) {
    // Konversi ke meter dulu
    switch (strtolower($from)) {
        case 'km':
            $meters = $distance * 1000;
            break;
        case 'mile':
            $meters = $distance * 1609.344;
            break;
        case 'm':
        default:
            $meters = $distance;
            break;
    }
    // Konversi dari meter ke satuan target
    switch (strtolower($to)) {
        case 'km':
            return $meters / 1000;
        case 'mile':
            return $meters / 1609.344;
        case 'm':
        default:
            return $meters;
    }
}

function text_log_area($id)
{
   $r = 'Luar area';
   if($id==true)
      $r = 'Dalam area';
   return $r;
}


function open_link_active_TStudio($link=[])
{
   $uri = new \CodeIgniter\HTTP\URI(current_url(true));
   $rs = '';
   $segments = $uri->getSegments();
   $pathURIX = explode('index.php/', implode('/', $segments));
   $pathURI = (isset($pathURIX[1]))?strtolower($pathURIX[1]):strtolower(implode('/', $segments));
   foreach($link as $k=>$v){
      if($pathURI==$v)
      {
         $rs = 'active expand';
      }
   }
   return $rs;
}


function open_link_active($link=[])
{
   $uri = new \CodeIgniter\HTTP\URI(current_url(true));
   $rs = '';
   $segments = $uri->getSegments();
   $pathURIX = explode('index.php/', implode('/', $segments));
   $pathURI = (isset($pathURIX[1]))?strtolower($pathURIX[1]):strtolower(implode('/', $segments));
   foreach($link as $k=>$v){
      if($pathURI==$v)
      {
         $rs = 'show';
      }
   }
   return $rs;
}


function check_link_module($link=[])
{
   $uri = new \CodeIgniter\HTTP\URI(current_url(true));
   $rs = false;
   $segments = $uri->getSegments();
   $pathURI = strtolower(implode('/', $segments));
   foreach($link as $k=>$v){
      $pos = strpos($pathURI, $v);
      if($pos > -1 )
      {
         $rs = true;
      }
   }
   return $rs;// .'<hr>'.$pathURI.'<hr>'.implode(',', $link);
}


function remove_key_in_array($array_data, $keys=[1])
{
   $return_array = $array_data;
   foreach ($keys as $key => $value) {
      unset($return_array[$value]);
      // $return_array = array_values($return_array);
   }
   return $return_array;
}


function return_array_in_array($target, $input)
{
   $rs = false;
   if(!empty($input))
   {
      foreach ($input as $key => $value) {
         if(array_keys($target, $value))
         {
            $rs = true;
         }
      }
   }
   return $rs;
}


function return_access_link($link=[])
{
   $user_base_access = session()->get('user_base_access');
   return return_array_in_array($user_base_access, $link);
}


function return_roles($arr)
{
   $rr = new App\Models\AuthModel();
   return $rr->return_roles_user($arr);
}

function return_value_in_options($id)
{
   $rs = [];
   $rr = new App\Models\AuthModel();
   $q = $rr->get_app_options_by_name($id);
   if(!empty($q))
   {
      // if(array_keys(['persuratan'], $id))
      // {
      //    $rs = $q->value;
      // }else{
         $rs = json_decode($q->value, true);
      // }
   }
   return $rs;
}

function return_referensi_list($id)
{
   $rs = [];
   $rr = new App\Models\CpanelModel();
   $rs = $rr->referensi_get_result($id);
   return $rs;
}
function return_referensi_row_by($ref, $code)
{
   $rs = [];
   $rr = new App\Models\CpanelModel();
   $rs = $rr->referensi_get_row_by($ref, $code);
   return $rs;
}

function return_sisa_cuti_pegawai($id)
{
   $rs = [];
   $rr = new App\Models\CutiModel();
   $rs = $rr->return_sisa_cuti_terakhir_by_pegawaiid($id);
   return $rs;
}

function return_files_path_by($pegawai_id, $jenis)
{
   $rs = [];
   $rr = new App\Models\KepegawaianModel();
   $rs = $rr->files_return_path_by_pegawaiid_jenis($pegawai_id, $jenis);
   return $rs;
}

function return_unit_kerja_name($id, $opt=1)
{
   $rr = new App\Models\KepegawaianModel();
   return $rr->return_unit_kerja($id, $opt);
}

function return_jabatan_name($id)
{
   $rr = new App\Models\KepegawaianModel();
   return $rr->return_jabatan($id);
}

function map_satker_to_nomor_naskah($var='1')
{
   $arr = [
      '1' => '1',    # sekre kepala staf     => kastaf
      // '5' => '2',    # stafsus
      '2' => '3',    # kedeputian i
      '3' => '4',    # kedeputian i
      '4' => '5',    # kedeputian iii
      '14' => '6',    # kedeputian 4
      '5' => '7',    # sekre
   ];
   // return $arr[$var];
   return get_name_reference_by_code($arr[$var], 'surat_kode_jabatan');
}
function get_name_reference_by_code($vr=1, $reff='surat_kode_jabatan')
{
   $arr = array_reference_by_ref($reff);
   return $arr[$vr];
}
function array_reference_by_ref($for)
{
   $arr = [];
   $q = return_referensi_list($for);
   if(!empty($q)){
      foreach($q as $k){
         $arr[$k->ref_code] = $k->ref_name;
      }
   }
   return $arr;
}

/*
*  update status read & respon surat
*/
function return_update_status_read_surat($ref, $datetime_now, $pegawai_id)
{
   $rs = 0;
   if($ref<>0)
   {
      $rr = new App\Models\PersuratanModel();
      $pos_id = explode('#', $ref);
      switch ($pos_id[0]) {
         case 'tindaklanjut':
            $rs = $rr->surat_tindaklanjut_save(
                ['read'=>1, 'read_time'=>$datetime_now],
                ['read'=>0, 'id'=>$pos_id[1], 'penerima_id'=>$pegawai_id]
            );
            break;
         case 'tembusan':
            $rs = $rr->surat_tembusan_save(
                ['read'=>1, 'read_time'=>$datetime_now],
                ['read'=>0, 'id'=>$pos_id[1], 'pegawai_id'=>$pegawai_id]
            );
            break;
         case 'penerima':
            $rs = $rr->surat_penerima_save(
                ['read'=>1, 'read_time'=>$datetime_now],
                ['read'=>0, 'id'=>$pos_id[1], 'pegawai_id'=>$pegawai_id]
            );
            break;
         case 'pelaksana':
            $rs = $rr->surat_pelaksana_save(
                ['read'=>1, 'read_time'=>$datetime_now],
                ['read'=>0, 'concat(surat_id,ref_type,ref_name,ref_id,pegawai_id)'=>$pos_id[1]]
            );
            break;
      }
   }
   return $rs;
}
function return_update_status_respon_surat($ref, $datetime_now, $pegawai_id)
{
   $rs = 0;
   if($ref<>0)
   {
      $rr = new App\Models\PersuratanModel();
      $pos_id = explode('#', $ref);
      switch ($pos_id[0]) {
         case 'tindaklanjut':
            $rs = $rr->surat_tindaklanjut_save(
                ['respon'=>1, 'respon_time'=>$datetime_now],
                ['respon'=>0, 'id'=>$pos_id[1], 'penerima_id'=>$pegawai_id]
            );
            break;
         case 'tembusan':
            $rs = $rr->surat_tembusan_save(
                ['respon'=>1, 'respon_time'=>$datetime_now],
                ['respon'=>0, 'id'=>$pos_id[1], 'pegawai_id'=>$pegawai_id]
            );
            break;
         case 'penerima':
            $rs = $rr->surat_penerima_save(
                ['respon'=>1, 'respon_time'=>$datetime_now],
                ['respon'=>0, 'id'=>$pos_id[1], 'pegawai_id'=>$pegawai_id]
            );
            break;
         case 'pelaksana':
            $rs = $rr->surat_pelaksana_save(
                ['respon'=>1, 'respon_time'=>$datetime_now],
                ['respon'=>0, 'concat(surat_id,ref_type,ref_name,ref_id,pegawai_id)'=>$pos_id[1]]
            );
            break;
      }
   }
   return $rs;
}

/*
*  check return sebagai penerima atau bukan
*/
function return_check_penerima_by_surat_id_and_pegawai_id($surat_id, $pegawai_id)
{
   $rs = false;
   $rr = new App\Models\PersuratanModel();
   $q = $rr->penerima_row_by_surat_id_and_pegawai_id($surat_id, $pegawai_id);
   if(!empty($q))
   {
      if($q->sent==1)
      {
         $rs = true;
      }
   }
   return $rs;
}

/*
*  converter string to encode & decode
*/
function string_to($string, $option=''){
   $result = '';
   $encrypt_method = "AES-256-CBC";
   $secret_key = $_ENV['encryption.key'].date('Ymd');
   $secret_iv = $_ENV['encryption.iv'];
   $key = hash('sha256', $secret_key);
   $iv = substr(hash('sha256', $secret_iv), 0, 16);
   switch ($option) {
      case 'encode':
         $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
         $result = str_replace(['='], '', base64_encode($result));
         break;
      case 'decode':
         $result = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
         break;
      default:
         $result = $string;
         break;
   }
   return $result;
}

/*
*  nilai terbilang
*/
function nilai_terbilang($nilai)
{
   $ejaan = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
   if ($nilai < 12){
      return " " . $ejaan[$nilai];
   }elseif ($nilai < 20){
      return nilai_terbilang($nilai - 10) . " Belas";
   }elseif ($nilai < 100){
      return nilai_terbilang($nilai / 10) . " Puluh" . nilai_terbilang($nilai % 10);
   }elseif ($nilai < 200){
      return " Seratus" . nilai_terbilang($nilai - 100);
   }elseif ($nilai < 1000){
      return nilai_terbilang($nilai / 100) . " Ratus" . nilai_terbilang($nilai % 100);
   }elseif ($nilai < 2000){
      return " Seribu" . nilai_terbilang($nilai - 1000);
   }elseif ($nilai < 1000000){
      return nilai_terbilang($nilai / 1000) . " Ribu" . nilai_terbilang($nilai % 1000);
   }elseif ($nilai < 1000000000){
      return nilai_terbilang($nilai / 1000000) . " Juta" . nilai_terbilang($nilai % 1000000);
   }
}



/*
*  qrcode created
*/
function create_new_qrcode($text, $file_path, $icon=false)
{
   require_once APPPATH."Libraries/phpqrcode/qrlib.php";
   QRcode::png($text, $file_path, QR_ECLEVEL_H, 2.2, 0, 0);
   if($icon==true)
   {
      /*ADD LOGO*/
      $logo = FCPATH.'assets/img/logo_k3.png';
      $QR = imagecreatefrompng($file_path);
      $logo = imagecreatefromstring(file_get_contents($logo));
      $QR_width = imagesx($QR);
      $QR_height = imagesy($QR);
      $logo_width = imagesx($logo);
      $logo_height = imagesy($logo);
      $logo_qr_width = $QR_width/3.5;
      $scale = $logo_width/$logo_qr_width;
      $logo_qr_height = $logo_height/$scale;
      imagecopyresampled($QR, $logo, $QR_width/2.7, $QR_height/2.7, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
      imagepng($QR,$file_path);
      imagedestroy($QR);
      /*END*/
   }
   return $file_path;
}

/*
* menggabungkan 2 image 
*/
function merge_two_image($id, $imagette, $qrcode)
{
   $rs = WRITEPATH.'temp_zip/'.$id.date('YmdHis').".png";
   if (!copy($imagette, $rs)) {
       echo "failed to copy";
   }
   $dest = imagecreatefrompng($rs);
   $src = imagecreatefrompng($qrcode);
   imagealphablending($dest, false);
   imagesavealpha($dest, true);
   imagecopymerge($dest, $src, 9, 15, 0, 0, 247, 247, 100);
   // header('Content-Type: image/png');
   imagepng($dest, $rs);
   imagedestroy($dest);
   imagedestroy($src);
   return $rs;
}

/*
*  create thumbnail 100x100 center
*/
function create_thumbnail_image($source, $target)
{
   $thumbnail = \Config\Services::image()
      ->withFile($source)
      ->fit(100, 100, 'center')
      ->save($target);
   return $target;
}

/*
*  create base64 from file
*/
function create_file_to_base64($file)
{
   $rs = '';
   if(file_exists($file))
   {
      // $ext = pathinfo($file, PATHINFO_EXTENSION);
      $mime = mime_content_type($file);
      $content = file_get_contents($file);
      $rs = 'data:'.$mime.';base64,'.base64_encode($content);
   }
   return $rs;
}

/*
*  create file from base64
*/
function create_base64_to_file($base64_string, $output_file) 
{
   $ifp = fopen( $output_file, 'wb' ); 
   $data = explode( ',', $base64_string );
   // fwrite( $ifp, base64_decode( $data[ 1 ] ) );
   fwrite( $ifp, base64_decode( $base64_string ) );
   fclose( $ifp ); 
   return $output_file; 
}

function get_foto_default_pegawai($id)
{
   if($id==1){
      return $foto_peg = create_file_to_base64(FCPATH.'assets/img/pegawai_default_male.png');
   }else{
      return $foto_peg = create_file_to_base64(FCPATH.'assets/img/pegawai_default_female.png');
   }
}

function create_zip_file($fileName, $pathFilesTemp)
{
   $pathFilesTemp = realpath($pathFilesTemp);
   $pathFileZip = WRITEPATH.'temp_zip/'.$fileName;
   $createMyFileZip = fopen($pathFileZip, 'w');
   fclose($createMyFileZip);
   $zip = new ZipArchive();
   $zip->open($pathFileZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);
   $files = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($pathFilesTemp),
      RecursiveIteratorIterator::LEAVES_ONLY
   );
   foreach ($files as $name => $file){
      if (!$file->isDir())
      {
         $filePath = $file->getRealPath();
         $relativePath = substr($filePath, strlen($pathFilesTemp) + 1);
         $zip->addFile($filePath, $relativePath);
         if ($file->getFilename() != 'important.txt')
         {
            $listFilesToDelete[] = $filePath;
         }
      }
   }
   $zip->close();
   foreach ($listFilesToDelete as $file)
   {
      @unlink($file);
   }
   $html_file = fopen($pathFilesTemp.'/index.html', 'w');
   fclose($html_file);
   return $pathFileZip;
}


function formatRupiah($angka, /*$dengan_simbol = true, */$desimal = 0) {
   if (!is_numeric($angka)) {
      return "Input harus berupa angka";
   }
   $format = number_format($angka, $desimal, ',', '.');
   // if ($dengan_simbol) {
   //    return 'Rp ' . $format;
   // }   
   return $format;
}


function return_tahun_list($id=0)
{
   $rs = [];
   $rr = new App\Models\PresensiModel();
   $rs = $rr->presensi_list_tahun($id);
   return $rs;
}

function tanggal_range($tgl1, $tgl2) 
{
   $rs = '';
   if($tgl1==$tgl2)
   {
      $rs = tanggal($tgl1, 1);
   }else{
      $xtgl1 = explode('-', $tgl1);
      $xtgl2 = explode('-', $tgl2);
      if($xtgl1[0].$xtgl1[1]==$xtgl2[0].$xtgl2[1])
      {
         $rs = $xtgl1[2].' s.d. '.tanggal($tgl2, 1);
      }else{
         if($xtgl1[0]==$xtgl2[0])
         {
            $rs = tanggal($tgl1, 8).' s.d. '.tanggal($tgl2, 3);
         }else{
            $rs = tanggal($tgl1, 3).' s.d. '.tanggal($tgl2, 3);
         }
      }
   }
   return $rs;
}

function groupTanggalInMonth($dates) {
   $rs = [];
   $dates_by_month = [];
   foreach ($dates as $date_str) {
      $date_obj = explode('-', $date_str);// DateTime::createFromFormat('Y-m-d', $date_str);
      $year = $date_obj[0];// $date_obj->format('Y');
      $month_year = $date_obj[0].'-'.$date_obj[1];// $date_obj->format('Y-m');
      if (!isset($dates_by_month[$year])) {
         $dates_by_month[$year] = [];
      }
      if (!isset($dates_by_month[$year][$month_year])) {
         $dates_by_month[$year][$month_year] = [];
      }
      $dates_by_month[$year][$month_year][] = $date_obj[2];//$date_obj->format('d');
   }
   foreach ($dates_by_month as $month => $dates) {
      foreach ($dates as $key => $value) {
         // echo "Bulan: $month\n";
         array_push($rs, implode(", ", $value) .' '. tanggal($key, 2));
      }
   }
   return implode(', ', $rs);
}

function tanggal($tgl, $opt='')
{
   if(!array_keys(['','0000-00-00'],$tgl)){
      $date = date_create($tgl);
      $day = date_format($date, "D");
      $hari = array_hari();
      $bln = array_bulan();
      $xtgl = explode('-', $tgl);
      if(strlen($xtgl[1])<2){
         $vbln = '0'.$xtgl[1];
      }else{
         $vbln = $xtgl[1];
      }
      switch ($opt) {
         case '1':
            $r = $xtgl[0];
            break;
         case '2':
            $r = $bln[$vbln].' '.$xtgl[0];
            break;
         case '3':
            $r = ltrim($xtgl[2], 0).' '.$bln[$vbln].' '.$xtgl[0];
            break;
         case '4':
            $r = $hari[$day].', '.$xtgl[2].' '.$bln[$vbln].' '.$xtgl[0];
            break;
         case '5':
            $r = $bln[$vbln];
            break;
         case '6':
            $r = $xtgl[2];
            break;
         case '7':
            $r = substr($xtgl[2],0,2).'/'.$xtgl[1].'/'.$xtgl[0].' '.substr($xtgl[2],3,8);
            break;
         case '8':
            $r = ltrim($xtgl[2],0).' '.$bln[$vbln];
            break;
         case '9':
            // $r = $hari[$day].', '.$xtgl[2].' '.$bln[$vbln].' '.$xtgl[0];
            break;
         default:
            $r = $tgl;
            break;
      }
      return $r;
   }else{
      return '-';
   }
}

function list_tanggal_in_periode_bulan($periode, $order='asc')
{
   $rs = [];
   $xPeriode = explode('-', $periode);
   $jml = cal_days_in_month(CAL_GREGORIAN, $xPeriode[1], $xPeriode[0]);
   for ($i=1; $i <= $jml; $i++) { 
      array_push($rs, $xPeriode[0].'-'.$xPeriode[1].'-'.returnDigitNumber($i));
   }
   if($order=='asc')
   {
      sort($rs);
   }else{
      rsort($rs);
   }
   return $rs;
}

function array_hari()
{
   return array('Sun'=>'Minggu', 'Mon'=>'Senin', 'Tue'=>'Selasa', 'Wed'=>'Rabu', 'Thu'=>'Kamis', 'Fri'=>'Jumat', 'Sat'=>'Sabtu');
}

function array_bulan()
{
   return array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
}


function returnDigitNumber($value, $digit=2){
   $rs = "";
   $lengthValue = strlen($value);
   if ($lengthValue == $digit) {
      $rs = $value;
   }else{
      for ($i = 0; $i < ($digit - $lengthValue); $i++) {
         $rs .= "0";
      }
      $rs .= $value;
   }
   return $rs;
}

function hours_tofloat($val)
{
   if (empty($val)) {
      return 0;
   }
   $parts = explode(':', $val);
   return $parts[0] + floor(($parts[1]/60)*100) / 100;
}

function convertDateTime($var='', $opt='')
{
   $tm = date_create($var);
   switch ($opt) {
      case 'datetime':
         date_timestamp_set($tm, 1171502725);
         return date_format($tm, 'Y-m-d H:i:s');
         break;
      case 'timestamp':
         return date_format($tm, 'U');
         break;
      default:
         return $var;
         break;
   }
}



function test_connection($url)
{
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_exec($ch);
   if(!curl_errno($ch)) {
   // $info = curl_getinfo($ch);
   // echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'];
   return true;
   }else{
   return false;
   }
   curl_close($ch);
}

function curl_post_data($url, $headers, $data)
{
   $client = \Config\Services::curlrequest();
   $response = $client->request('POST', $url, [
      'headers' => $headers,
      'form_params' => $data // application/x-www-form-urlencoded
   ]);
   $status_code = $response->getStatusCode();
   return json_decode($response->getBody(), true);
}

function curl_post_data_file($url, $headers, $data)
{
   $client = \Config\Services::curlrequest();
   $response = $client->request('POST', 'https://httpbin.org/post', [
       'headers' => $headers,
       'multipart' => $data, /*[
           [
               'name' => 'file',
               'contents' => fopen($filePath, 'r'),
               'filename' => 'sample.txt'
           ],
           [
               'name' => 'description',
               'contents' => 'File uploaded from CI4'
           ]
       ],*/
       'timeout' => 60 // Timeout lebih lama untuk upload
   ]);
   $status_code = $response->getStatusCode();
   return json_decode($response->getBody(), true);
}

function curl_get_data($url, $headers, $data=[])
{
   $client = \Config\Services::curlrequest();
   $response = $client->request('GET', $url, [
      'headers' => $headers,
   ]);
   $status_code = $response->getStatusCode();
   return json_decode($response->getBody(), true);
}



/*
*  check user status tte
*/
function bsre_check_user_status($nik)
{
   $bsre = return_value_in_options('bsre');
   $url = $bsre['essign']['url'] .'user/status/'. $nik;
   $authorization = $bsre['essign']['authorization'];
   $headers = [
      'Authorization' => 'Basic '.$authorization
   ];
   return curl_get_data($url, $headers);
}

/*
*  verify file sign
*/
function bsre_file_sign_verify($file_input, $filename)
{
   $bsre = return_value_in_options('bsre');
   $url = $bsre['essign']['url'] .'sign/verify';
   $authorization = $bsre['essign']['authorization'];
   $headers = [
      'Authorization' => 'Basic '.$authorization,
      'Content-Type' => 'multipart/form-data'
   ];
   $data = [
      [
         'name' => 'signed_file',
         'contents' => fopen($file_input, 'r'),
         'filename' => $filename,
      ]
   ];
   return json_decode(shell_exec("curl -X POST --url ".$url." -H 'Authorization: Basic ".$authorization."' -H 'Content-Type: multipart/form-data' --form signed_file=@".$file_input." "), true);
   // return curl_post_data_file($url, $headers, $data);
}

/*
*  sign/tte file
*/
function bsre_sign_pdf($nik, $passphrase, $file_input, $file_output, $visibility, $code_position, $width, $height, $image, $page=1, $xAxis=0, $yAxis=0)
{
   $result = false;
   $datetime = date('Y-m-d H:i:s');
   $bsre = return_value_in_options('bsre');
   $url = $bsre['essign']['url'] .'sign/pdf';
   $authorization = $bsre['essign']['authorization'];
   $headers = [
      'Authorization' => 'Basic '.$authorization,
   ];
   if(isset($bsre['nik_test']) && $bsre['nik_test']<>''){
      $nik = $bsre['nik_test'];
   }
   $cFile = '@' . $file_input;
   $cImage = '@' . $image;
   $fields = [
      'nik' => $nik,
      'passphrase' => '"'.$passphrase.'"',
      'tampilan' => strtolower($visibility),
      'jenis_response' => 'BASE64',
      'reason' => 'Ditandatangani melalui aplikasi internal Kantor Komunikasi Kepresidenan Republik Indonesia',
      'location' => $_SERVER['REMOTE_ADDR'],
      'file' => $cFile,
   ];
   if($visibility == 'VISIBLE')
   {
      $fields['image'] = true;
      $fields['height'] = $height;
      $fields['width'] = $width;
      if($code_position==0)
      {
         $fields['page'] = $page;
         // $fields['halaman'] = 'PERTAMA'; /*'TERAKHIR';*/
         // $fields['id_subscriber'] = '';
         $fields['linkQR'] = $cImage;
         // $fields['text'] = 'testing';
         $fields['xAxis'] = $xAxis;
         $fields['yAxis'] = $yAxis;
      }else{
         $fields['tag_koordinat'] = $code_position;
         $fields['imageTTD'] = $cImage;
      }
   }

   $query_fields = "";
   $i = 0;
   foreach ($fields as $key => $value) {
      $i += 1;
      $query_fields .= " --form '". $key ."=". $value."' ";
   }

   // CURL COMMAND
   $response = json_decode(shell_exec("curl -X POST --url ".$url." -H 'Authorization: Basic ".$authorization."' -H 'Content-Type: multipart/form-data' ".$query_fields." "), true);

   // LOG TTE
   switch (true) {
      case !empty($response) && array_key_exists("base64_file", $response):
         create_base64_to_file($response['base64_file'], $file_output);
         $result = true;
         break;
      case !empty($response) && array_key_exists("base64_signed_file", $response):
         create_base64_to_file($response['base64_signed_file'], $file_output);
         $result = true;
      default:
         break;
   }
   log_tte(json_encode(remove_key_in_array($response, ['base64_file', 'base64_signed_file'])));
   return ['status'=>$result, 'message'=>remove_key_in_array($response, ['base64_file', 'base64_signed_file']), 'file'=>$file_output];
}

/*
*  parsing file surat from template docx
*/
   // function replace_text_in_docx_file_and_export_to_pdf($id, $file_yg_di_proses, $test=false) {
   //    // $table_surat_file = $this->db->get_where('table_surat_file', ['id'=>$id])->row();
   //    // $pathfile_replace = $this->path_root_app.'writable/persuratan/'.$table_surat_file->id.'_'.date('YmdHis').'.docx';
   //    // REPLACE TEXT INDOCX FILE USING PHPWORD
   //    $path_file_new = '';
   //    if(array_keys(['docx', 'DOCX'], pathinfo($file_yg_di_proses, PATHINFO_EXTENSION)))
   //    {
   //       $data_surat = $this->SuratModel->row_e_persuratan($id);
   //       if($data_surat->penandatangan_oleh==1)
   //       {
   //          $penandatangan_data_bio = $this->open_model->pegawai_byid($data_surat->pengirim_id);
   //       }else{
   //          $data_penandatangan = $this->SuratModel->data_penandatangan($data_surat->id)->row();
   //          $penandatangan_data_bio = $this->open_model->pegawai_byid($data_penandatangan->id_pegawai);
   //       }
   //       $label_naskah_nama_penandatangan = $penandatangan_data_bio->nama;
   //       if($data_surat->penandatangan_display==1)
   //       {
   //          $label_naskah_nama_penandatangan = $penandatangan_data_bio->gelar_depan .' '. $penandatangan_data_bio->nama.' '.$penandatangan_data_bio->gelar_belakang;
   //       }
   //       $label_naskah_jabatan_pengirim = $data_surat->penandatangan_sebagai .' '. $this->open_model->replaceDataPimpinan($data_surat->pengirim_jabatan_name.', '.$data_surat->pengirim_satker_name);

   //       if($data_surat->nomor<>'')
   //       {
   //          $nomor_naskah = $data_surat->nomor;
   //       }else{
   //          $nomor_naskah = $this->SuratModel->create_nomor_surat($data_surat->id, 'KSP', true);
   //       }
   //       $image_qrcode = $this->create_link_qrcode_verify_file_surat($data_surat->id, $test);
   //    require_once $this->path_root_app.'vendors/PHPWord-develop/bootstrap.php';
   //    $phpWord = new \PhpOffice\PhpWord\PhpWord();
   //       $document = $phpWord->loadTemplate($file_yg_di_proses);
   //       $document->setValue('nomor_naskah', $nomor_naskah);
   //       $document->setValue('tanggal_naskah', $this->open_model->tanggal($data_surat->tanggal,1));
   //       $document->setValue('sifat_naskah', $data_surat->sifat_naskah_name);
   //       $document->setValue('hal_naskah', $data_surat->hal);
   //       // $document->setValue('jabatan_pengirim', $label_naskah_jabatan_pengirim);
   //       // $document->setValue('nama_pengirim', $label_naskah_nama_penandatangan);
   //       // $document->setImageValue('image1.png', $image_qrcode);
   //       $pathfile_replace = $this->path_root_app.'writable/persuratan/'.$data_surat->id.'_'.date('YmdHis').'.docx';
   //       $document->saveAs($pathfile_replace);
   //       ob_clean();

   //       // EXPORT PDF USING LIBRE/OPENOFFICE
   //       $path_file_new = str_replace(['.docx', '.DOCX'], '.pdf', $file_yg_di_proses);
   //    putenv('PATH=/usr/local/bin:/bin:/usr/bin:/usr/local/sbin:/usr/sbin:/sbin');
   //    putenv('HOME=/tmp');
   //    $txt_shell = 'soffice --headless --convert-to pdf --outdir "'.$this->path_root_app.'writable/persuratan/" '.$pathfile_replace.' ';
   //       $shell_exec = shell_exec($txt_shell);
   //       if($shell_exec==NULL)
   //       {
   //          $shell_exec = 'gagal membuat pdf';
   //       }
   //       $this->db->insert('aaa', ['content'=>$txt_shell .' <===||===> '. $shell_exec]);
   //       @unlink($pathfile_replace);
   //       @rename(str_replace(['.docx', '.DOCX'], '.pdf', $pathfile_replace), $path_file_new);
   //       @unlink($image_qrcode);

   //    }
   //    return $path_file_new;
   // }


/*
*  create footer text sign file 
*/
function add_info_tte_in_footer($input_file)
{
   $text_footer = '
      <table style="font-size:12pt; font-style:; color:#595959" width="100%">
         <tr>
            <td valign="top" width="90%" align="center" style="font-size:8pt">
               Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan oleh Balai Besar Sertifikasi Elektronik (BSrE), BSSN
            </td>
         </tr>
      </table>
   ';
   $mpdf = new Mpdf([
      'mode' => 'utf-8',
      'default_font' => 'FreeSerif',
      'margin_left' => 2,
      'margin_right' => 2,
      'margin_header' => 2,
      'margin_footer' => 2
   ]);
   $pagecount = $mpdf->SetSourceFile($input_file);
   for ($i = 1; $i <= $pagecount; $i++)
   {
      $tplId = $mpdf->importPage($i);
      $size = $mpdf->getTemplateSize($tplId);
      $mpdf->AddPage($size['orientation']);
      $mpdf->useTemplate($tplId, 0, 0, $size['width'], $size['height'], true);
      $mpdf->SetHTMLFooter($text_footer);
   }
   $mpdf->WriteHTML('', \Mpdf\HTMLParserMode::HTML_BODY);
   $mpdf->Output($input_file, 'F');
   return true;
}


/*
*  data surat_tindaklanjut
*/
function view_result_surat_tindaklanjut_by_surat_id_and_id($surat_id, $id)
{
   $html = '<div class="border-top border-warning pt-2 mt-2">';
   $rr = new App\Models\PersuratanModel();
   $data_tindaklanjut = $rr->surat_tindaklanjut_result_by_surat_id_and_ref_id($surat_id, $id);
   if(!empty($data_tindaklanjut)){
      foreach ($data_tindaklanjut as $k) {
         $oleh_user = '';
         if(return_roles([1])){
            $oleh_user = ', Oleh user '.$k->create_by_name;
         }
         if($k->status==1){
            $html .= '
            <div class="border rounded p-2 mb-2 color-yellow-light">
               <div class="border rounded p-2 color-yellow">
                  <b>'.$k->status_name.'</b> dari <b>'.$k->pengirim_nama.' [<i class="fw-normal">'.$k->pengirim_jabatan_name.', '.$k->pengirim_unit_name.'</i>]</b>
                  <small class="d-block">
                     <span data-bs-toggle="tooltip" data-bs-placement="top" title="Waktu kirim '.tanggal(substr($k->sent_time,0,10),4).', Pukul '.substr($k->sent_time,11) . $oleh_user.'">Pada '.tanggal(substr($k->sent_time,0,10),4).', Pukul '.substr($k->sent_time,11).'</span>
                  </small>
               </div>
               <div class="border rounded p-2 color-abu">
                  Kepada <b>'.$k->penerima_nama.' [<i class="fw-normal">'.$k->penerima_jabatan_name.', '.$k->penerima_unit_name.'</i>]</b> { ';
                  if($k->read==0 && $k->respon==0){ 
                     $html .= '<i class="fa fa-minus-circle text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Belum respon"></i> ';
                  }else{
                     if($k->read==1){
                        $html .= '<i class="far fa-registered text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Dibaca pada '.tanggal(substr($k->read_time,0,10),4).', Pukul '.substr($k->read_time,11).'"></i> ';
                     }
                     if($k->respon==1){
                        $html .= '<i class="far fa-check-circle text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Ditindaklajuti pada '.tanggal(substr($k->respon_time,0,10),4).', Pukul '.substr($k->respon_time,11).'"></i> ';
                     }else{
                        $html .= '<i class="far fa-check-circle text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Belum ditindaklajuti"></i> ';
                     }
                  }
               $html .= '}
               </div>
               <div class="border rounded p-2 color-abu">
                  <b class="d-block">Keterangan/Catatan/Disposisi:</b>
                  '.$k->value.'
                  '.$k->catatan.'';
                  if($k->lampiran){
                     $lampiranExp = explode(',', $k->lampiran);
                     $html .= '
                     <div>';
                     foreach ($lampiranExp as $key => $value) {
                        $html .= '<a href="'.site_url('file/download?id='.$value).'" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Unduh file lampiran" class="me-2"><i class="fa fa-paperclip"></i></a>';
                     }
                     $html .= '
                     </div>';
                  }
                  $html .= '
                  <div>';
                     if(return_access_link(['persuratan/form/tindaklanjut/disposisi']) && ($k->penerima_id==session()->get('pegawai_id'))){
                        $html .= '<center><a href="'.site_url('persuratan/form/tindaklanjut/disposisi?'.$_SERVER['QUERY_STRING'].'&id_tl='.string_to($k->id, 'encode')).'" class="btn btn-warning btn-sm"><i class="fa fa-check"></i> tindak lanjut</a></center>';
                     }
                     $html .= view_result_surat_tindaklanjut_by_surat_id_and_id($k->surat_id, $k->id);
                  $html .= '
                   </div>
               </div>
           </div>';
         }else{
            $html .= '
            <div class="border rounded p-2 mb-2 color-green-light">
               <div class="border rounded p-2 color-abu">
                  <b>'.$k->status_name.'</b> dari <b>'.$k->pengirim_nama.' [<i class="fw-normal">'.$k->pengirim_jabatan_name.', '.$k->pengirim_unit_name.'</i>]</b> 
                  <small class="d-block">
                     <span data-bs-toggle="tooltip" data-bs-placement="top" title="Waktu proses '.tanggal(substr($k->sent_time,0,10),4).', Pukul '.substr($k->sent_time,11) . $oleh_user.'">Pada '.tanggal(substr($k->sent_time,0,10),4).', Pukul '.substr($k->sent_time,11).'</span> 
                  </small>
               </div>
               <div class="border rounded p-2 color-abu">
                  <b class="d-block">Keterangan/Catatan:</b>
                  '.$k->value.'
                  '.$k->catatan.'';
                  if($k->lampiran){
                     $lampiranExp = explode(',', $k->lampiran);
                     $html .= '
                     <div>';
                        foreach ($lampiranExp as $key => $value) {
                           $html .= '<a href="'.site_url('file/download?id='.$value).'" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Unduh file lampiran" class="me-2"><i class="fa fa-paperclip"></i></a>';
                        }
                     $html .= '
                     </div>';
                  }
               $html .= '
               </div>
            </div>';
         }
      }
   }else{
      $html .= '<b class="d-block text-danger">Belum ada tindak lanjut <i class="fa fa-question-circle"></i></b>';
   }
   $html .= '</div>';
   return $html;
}


/*
*  filter fiel table
*/
function filterFieldOrder($field)
{
   $r_field = 'asc';
   if (in_array(strtolower($field), ['asc', 'desc'], true))
   {
      $r_field = $field;
   }
   return $r_field;
}
?>