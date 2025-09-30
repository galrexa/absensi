<?php 
namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Tools;
// use App\Models\LoadModel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

class KepegawaianModel extends Model
{
	function __construct()
	{
		$this->db = \Config\Database::connect();
		$this->request = \Config\Services::request();
		// $this->session = \Config\Services::session();
        $this->tools = new Tools();
        $this->key = (session()->get('key'))?:$this->request->getHeaderLine('Key');
        $this->token = (session()->get('token'))?:$this->request->getHeaderLine('Token');
        $this->user = (session()->get('id'))?:$this->request->getHeaderLine('User');
    }



    function pegawai_get_row($id)
    {
    	return $this->db->query("SELECT p.pegawai_id
				, p.nik
				, p.nip
				, p.npwp
				, p.nama
				, p.tempat_lahir
				, p.tanggal_lahir
				, p.kelamin
				, ifnull(ar.ref_name, '-') as kelamin_name
				, p.agama
				, ifnull(ar2.ref_name, '-') as agama_name
				, p.gelar_depan
				, p.gelar_belakang
				, p.unit_kerja_id
				, uk.unit_kerja_name as unit_kerja
				, uk.unit_kerja_name_alt as unit_kerja_alt
				, p.jabatan_id
				, j.jabatan_name as jabatan
				, p.status
				, ifnull(ar4.ref_name, '-') as status_name
				, p.status_perkawinan
				, ifnull(CONCAT(ar6.ref_name,' (',ar6.ref_description,')'), '-') as status_perkawinan_name
				, p.status_bpjs
				, p.status_lhkpn
				, p.status_jenis_pegawai
				, ifnull(ar7.ref_name, '-') as status_jenis_pegawai_name
				, p.status_pns
				, ifnull(ar3.ref_name, '-') as status_pns_name
				, p.asal_instansi
				, p.nip_lama
				, p.pangkat
				, p.gol
				, p.eselon
				, p.tmt_pang_gol
				, p.pendidikan
				, ifnull(ar5.ref_name, '-') as pendidikan_name
				, p.universitas
				, ifnull(mpt.nama_pt, '-') as universitas_name
				, p.email
				, p.email_pribadi
				, p.hp
				, p.telp
				, p.foto_pegawai
				, p.foto_pegawai_temp
				, p.kode_absen
				, p.idcard1
				, p.idcard2
				, p.gugustugas
				, ifnull(mgt.gugustugas, '-') as gugustugas_name
				, ifnull((SELECT psk.periode_akhir from pegawai_sk psk WHERE psk.pegawai_id=p.pegawai_id order by psk.periode_akhir desc limit 1), '-') as masa_berakhir_tugas
				, p.bank_name, p.bank_region, p.bank_account, p.bank_account_name
			FROM pegawai p
				left join ms_unit_kerja uk on uk.unit_kerja_id=p.unit_kerja_id
				left join ms_jabatan j on j.jabatan_id=p.jabatan_id
				left join app_referensi ar on ar.ref='gender' and ar.ref_status=1 and ar.ref_code=p.kelamin
				left join app_referensi ar2 on ar2.ref='agama' and ar2.ref_status=1 and ar2.ref_code=p.agama
				left join app_referensi ar3 on ar3.ref='pegawai_status_pns' and ar3.ref_status=1 and ar3.ref_code=p.status_pns
				left join app_referensi ar4 on ar4.ref='pegawai_status' and ar4.ref_status=1 and ar4.ref_code=p.status
				left join app_referensi ar5 on ar5.ref='pendidikan' and ar5.ref_status=1 and ar5.ref_code=p.pendidikan
				left join app_referensi ar6 on ar6.ref='pegawai_status_kawin' and ar6.ref_status=1 and ar6.ref_code=p.status_perkawinan
				left join app_referensi ar7 on ar7.ref='pegawai_status_jenis' and ar7.ref_status=1 and ar7.ref_code=p.status_jenis_pegawai
				left join ms_perguruan_tinggi mpt on mpt.id_pt=p.universitas
				left join ms_gugus_tugas mgt on mgt.id=p.gugustugas
			WHERE p.pegawai_id=? "
			, [$id]
		)->getRow();
    }

    function prepare_download_foto()
    {
    	$where = "";
    	$array_value = [];
        $status = ($this->request->getGet('status'))?:1;
        $unit_kerja = $this->request->getGet('unit_kerja');
        $jabatan = $this->request->getGet('jabatan');
        $status_pns = $this->request->getGet('status_pns');
        $kelamin = $this->request->getGet('kelamin');
        $page = $this->request->getGet('page');
    	$where .= " p.status=? ";
    	array_push($array_value, $status);
        if($jabatan<>''){
        	$where .= " and p.jabatan_id in ? ";
        	array_push($array_value, explode(',', $jabatan));
        }
        if($status_pns<>''){
        	$where .= " and p.status_pns in ? ";
        	array_push($array_value, explode(',', $status_pns));
        }
        if($kelamin<>''){
        	$where .= " and p.kelamin in ? ";
        	array_push($array_value, explode(',', $kelamin));
        }
		if($page<>'')
		{
			switch ($page) {
				case 1:
				case 9:
					$where .= "AND p.unit_kerja_id=? AND p.jabatan_id in ? ";
					array_push($array_value, $page);
					array_push($array_value, [10,55,49,50]);
					break;
				case 8:
					$where .= "AND p.unit_kerja_id=? AND p.jabatan_id in ? ";
					array_push($array_value, $page);
					array_push($array_value, [35]);
					break;
				case 2:
				case 3:
				case 4:
				case 5:
				case 6:
				case 10:
				case 11:
					$where .= "AND p.unit_kerja_id=? AND p.jabatan_id in ? ";
					array_push($array_value, $page);
					array_push($array_value, [1,2,3,4,5,6,7,8,9,67,68,70,71,72,73,74,75,76,77,78,79,80,81,82,83]);
					break;
				case 7:
					$where .= "AND p.unit_kerja_id=? ";
					array_push($array_value, $page);
					if(array_keys([7], $page))
					{
						$where .= " AND p.status_pns in ? ";
						array_push($array_value, [1,2]);
					}
					break;
				case 'nonpns':
					$where .= "AND p.jabatan_id in ? ";
					array_push($array_value, [40,47,48]);
					if($unit_kerja<>'')
					{
						$where .= "AND p.unit_kerja_id in ? ";
						array_push($array_value, explode(',', $unit_kerja));
					}
					break;
				case 'rekanan':
					$where .= "AND p.jabatan_id in ? ";
					array_push($array_value, [44,45,46,51]);
					if($unit_kerja<>'')
					{
						$where .= "AND p.unit_kerja_id in ? ";
						array_push($array_value, explode(',', $unit_kerja));
					}
					break;
				case 'sementara':
					$where .= "AND p.jabatan_id in ? ";
					array_push($array_value, [41]);
					if($unit_kerja<>'')
					{
						$where .= "AND p.unit_kerja_id in ? ";
						array_push($array_value, explode(',', $unit_kerja));
					}
					break;
				case 'gugustugas':
					$where .= "AND p.jabatan_id in ? ";
					array_push($array_value, [42]);
					if($unit_kerja<>'')
					{
						$where .= "AND p.unit_kerja_id in ? ";
						array_push($array_value, explode(',', $unit_kerja));
					}
					break;
				case 'magang':
					$where .= "AND p.jabatan_id in ? ";
					array_push($array_value, [43]);
					if($unit_kerja<>'')
					{
						$where .= "AND p.unit_kerja_id in ? ";
						array_push($array_value, explode(',', $unit_kerja));
					}
					break;
			}
		}else{
			if($unit_kerja<>'')
			{
				$where .= "AND p.unit_kerja_id in ? ";
				array_push($array_value, explode(',', $unit_kerja));
			}
		}
    	return $this->db->query("SELECT p.pegawai_id, p.nik, p.nip, p.nama, p.unit_kerja_id, uk.unit_kerja_name, p.jabatan_id, j.jabatan_name, p.status, p.email, p.foto_pegawai, p.foto_pegawai_temp
			FROM pegawai p
				left join ms_unit_kerja uk on uk.unit_kerja_id=p.unit_kerja_id
				left join ms_jabatan j on j.jabatan_id=p.jabatan_id
			WHERE ".$where
			, $array_value
		)->getResult();
    }

    function prepare_download_data()
    {
    	$where = "";
    	$array_value = [];
        $status = ($this->request->getGet('status'))?:1;
        $unit_kerja = $this->request->getGet('unit_kerja');
        $jabatan = $this->request->getGet('jabatan');
        $status_pns = $this->request->getGet('status_pns');
        $kelamin = $this->request->getGet('kelamin');
        $page = $this->request->getGet('page');
    	$where .= " p.status=? ";
    	array_push($array_value, $status);
        if($jabatan<>''){
        	$where .= " and p.jabatan_id in ? ";
        	array_push($array_value, explode(',', $jabatan));
        }
        if($status_pns<>''){
        	$where .= " and p.status_pns in ? ";
        	array_push($array_value, explode(',', $status_pns));
        }
        if($kelamin<>''){
        	$where .= " and p.kelamin in ? ";
        	array_push($array_value, explode(',', $kelamin));
        }
		if($page<>'')
		{
			switch ($page) {
				case 1:
				case 9:
					$where .= "AND p.unit_kerja_id=? AND p.jabatan_id in ? ";
					array_push($array_value, $page);
					array_push($array_value, [10,55,49,50]);
					break;
				case 8:
					$where .= "AND p.unit_kerja_id=? AND p.jabatan_id in ? ";
					array_push($array_value, $page);
					array_push($array_value, [35]);
					break;
				case 2:
				case 3:
				case 4:
				case 5:
				case 6:
				case 10:
				case 11:
					$where .= "AND p.unit_kerja_id=? AND p.jabatan_id in ? ";
					array_push($array_value, $page);
					array_push($array_value, [1,2,3,4,5,6,7,8,9,67,68,70,71,72,73,74,75,76,77,78,79,80,81,82,83]);
					break;
				case 7:
					$where .= "AND p.unit_kerja_id=? ";
					array_push($array_value, $page);
					if(array_keys([7], $page))
					{
						$where .= " AND p.status_pns in ? ";
						array_push($array_value, [1,2]);
					}
					break;
				case 'nonpns':
					$where .= "AND p.jabatan_id in ? ";
					array_push($array_value, [40,47,48]);
					if($unit_kerja<>'')
					{
						$where .= "AND p.unit_kerja_id in ? ";
						array_push($array_value, explode(',', $unit_kerja));
					}
					break;
				case 'rekanan':
					$where .= "AND p.jabatan_id in ? ";
					array_push($array_value, [44,45,46,51]);
					if($unit_kerja<>'')
					{
						$where .= "AND p.unit_kerja_id in ? ";
						array_push($array_value, explode(',', $unit_kerja));
					}
					break;
				case 'sementara':
					$where .= "AND p.jabatan_id in ? ";
					array_push($array_value, [41]);
					if($unit_kerja<>'')
					{
						$where .= "AND p.unit_kerja_id in ? ";
						array_push($array_value, explode(',', $unit_kerja));
					}
					break;
				case 'gugustugas':
					$where .= "AND p.jabatan_id in ? ";
					array_push($array_value, [42]);
					if($unit_kerja<>'')
					{
						$where .= "AND p.unit_kerja_id in ? ";
						array_push($array_value, explode(',', $unit_kerja));
					}
					break;
				case 'magang':
					$where .= "AND p.jabatan_id in ? ";
					array_push($array_value, [43]);
					if($unit_kerja<>'')
					{
						$where .= "AND p.unit_kerja_id in ? ";
						array_push($array_value, explode(',', $unit_kerja));
					}
					break;
			}
		}else{
			if($unit_kerja<>'')
			{
				$where .= "AND p.unit_kerja_id in ? ";
				array_push($array_value, explode(',', $unit_kerja));
			}
		}
		$where .= " group by p.pegawai_id ORDER BY j.jabatan_id ASC ";
    	return $this->db->query("SELECT p.pegawai_id, p.nik, p.nip, p.nip_lama, p.nama, p.unit_kerja_id, uk.unit_kerja_name, p.jabatan_id, j.jabatan_name, p.eselon, p.pangkat, p.gol, p.status, p.status_pns, ifnull(ar3.ref_name, '-') as status_pns_name, p.email, p.foto_pegawai, p.foto_pegawai_temp
    			, case when p.jabatan_id in (44,40,47,48) then concat('Tenaga ', j.jabatan_name) else j.jabatan_name end as keterangan
				, (SELECT d.periode_awal FROM pegawai_sk d WHERE d.pegawai_id=p.pegawai_id ORDER BY d.periode_awal DESC limit 1 ) AS mulai
				, (SELECT d.periode_akhir FROM pegawai_sk d WHERE d.pegawai_id=p.pegawai_id ORDER BY d.periode_awal DESC limit 1 ) AS akhir
				, (SELECT d.nomor FROM pegawai_sk d WHERE d.pegawai_id=p.pegawai_id ORDER BY d.periode_awal DESC limit 1 ) AS nomor_sk
				, (SELECT d.tanggal FROM pegawai_sk d WHERE d.pegawai_id=p.pegawai_id ORDER BY d.periode_awal DESC limit 1 ) AS tanggal_sk
				, (SELECT d.keterangan FROM pegawai_sk d WHERE d.pegawai_id=p.pegawai_id ORDER BY d.periode_awal DESC limit 1 ) AS keterangan2
				, mpt.nama_pt as universitas
			FROM pegawai p
				left join ms_unit_kerja uk on uk.unit_kerja_id=p.unit_kerja_id
				left join ms_jabatan j on j.jabatan_id=p.jabatan_id
				left join app_referensi ar on ar.ref='gender' and ar.ref_status=1 and ar.ref_code=p.kelamin
				left join app_referensi ar2 on ar2.ref='agama' and ar2.ref_status=1 and ar2.ref_code=p.agama
				left join app_referensi ar3 on ar3.ref='pegawai_status_pns' and ar3.ref_status=1 and ar3.ref_code=p.status_pns
				left join app_referensi ar4 on ar4.ref='pegawai_status' and ar4.ref_status=1 and ar4.ref_code=p.status
				left join app_referensi ar5 on ar5.ref='pendidikan' and ar5.ref_status=1 and ar5.ref_code=p.pendidikan
				left join app_referensi ar6 on ar6.ref='pegawai_status_kawin' and ar6.ref_status=1 and ar6.ref_code=p.status_perkawinan
				left join app_referensi ar7 on ar7.ref='pegawai_status_jenis' and ar7.ref_status=1 and ar7.ref_code=p.status_jenis_pegawai
				left join ms_perguruan_tinggi mpt on mpt.id_pt=p.universitas
				left join pegawai_hash_link phl on phl.pegawai_id=p.pegawai_id
			WHERE ".$where
			, $array_value
		)->getResult();
    }


	function pegawai_aktif($data_=false)
	{
		$unit = $this->request->getGet('id');
		$unit_kerja = $this->request->getPost('unit_kerja');
		$jabatan = $this->request->getPost('jabatan');
		$kelamin = $this->request->getPost('kelamin');
		$status_pns = $this->request->getPost('status_pns');
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'pegawai_aktif'));
		}
		$search_value = $this->request->getPost('search');//$_POST['search']['value'];
		$arrWhere = [];
		$where = " p.status not in (0,2) ";
		if($search_value<>''){
			$where .= " AND (p.nama like ? or p.nik like ? or p.nip like ? or p.hp like ? or p.tempat_lahir like ? or p.email like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		if($unit<>'')
		{
			$where .= "AND p.unit_kerja_id=? ";
			array_push($arrWhere, $unit);
		}else{
			if(!empty($unit_kerja))
			{
				$where .= "AND p.unit_kerja_id in ? ";
				array_push($arrWhere, $unit_kerja);
			}
		}
		if(!empty($jabatan))
		{
			$where .= "AND p.jabatan_id in ? ";
			array_push($arrWhere, $jabatan);
		}
		if(!empty($kelamin))
		{
			$where .= "AND p.kelamin in ? ";
			array_push($arrWhere, $kelamin);
		}
		if(!empty($status_pns))
		{
			$where .= "AND p.status_pns in ? ";
			array_push($arrWhere, $status_pns);
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q = $this->db->query("SELECT p.pegawai_id
						, p.nik
						, p.nip
						, p.npwp
						, p.nama
						, p.tempat_lahir
						, p.tanggal_lahir
						, p.kelamin
						, ifnull(ar.ref_name, '-') as kelamin_name
						, p.agama
						, ifnull(ar2.ref_name, '-') as agama_name
						, p.gelar_depan
						, p.gelar_belakang
						, p.unit_kerja_id
						, uk.unit_kerja_name as unit_kerja
						, p.jabatan_id
						, j.jabatan_name as jabatan
						, p.status
						, ifnull(ar4.ref_name, '-') as status_name
						, p.status_perkawinan
						, ifnull(CONCAT(ar6.ref_name,' (',ar6.ref_description,')'), '-') as status_perkawinan_name
						, p.status_bpjs
						, p.status_lhkpn
						, p.status_jenis_pegawai
						, ifnull(ar7.ref_name, '-') as status_jenis_pegawai_name
						, p.status_pns
						, ifnull(ar3.ref_name, '-') as status_pns_name
						, p.asal_instansi
						, p.nip_lama
						, p.pangkat
						, p.gol
						, p.eselon
						, p.tmt_pang_gol
						, p.pendidikan
						, ifnull(ar5.ref_name, '-') as pendidikan_name
						, p.universitas
						, p.email
						, p.email_pribadi
						, p.hp
						, p.telp
						, p.foto_pegawai
						, p.foto_pegawai_temp
						, p.kode_absen
						, p.idcard1
						, p.idcard2
						, p.gugustugas
						, ifnull((SELECT psk.periode_akhir from pegawai_sk psk WHERE psk.pegawai_id=p.pegawai_id order by psk.periode_akhir desc limit 1), '-') as masa_berakhir_tugas
						, ifnull(phl.qrcode, '') as qrcode_link
					FROM pegawai p
						left join ms_unit_kerja uk on uk.unit_kerja_id=p.unit_kerja_id
						left join ms_jabatan j on j.jabatan_id=p.jabatan_id
						left join app_referensi ar on ar.ref='gender' and ar.ref_status=1 and ar.ref_code=p.kelamin
						left join app_referensi ar2 on ar2.ref='agama' and ar2.ref_status=1 and ar2.ref_code=p.agama
						left join app_referensi ar3 on ar3.ref='pegawai_status_pns' and ar3.ref_status=1 and ar3.ref_code=p.status_pns
						left join app_referensi ar4 on ar4.ref='pegawai_status' and ar4.ref_status=1 and ar4.ref_code=p.status
						left join app_referensi ar5 on ar5.ref='pendidikan' and ar5.ref_status=1 and ar5.ref_code=p.pendidikan
						left join app_referensi ar6 on ar6.ref='pegawai_status_kawin' and ar6.ref_status=1 and ar6.ref_code=p.status_perkawinan
						left join app_referensi ar7 on ar7.ref='pegawai_status_jenis' and ar7.ref_status=1 and ar7.ref_code=p.status_jenis_pegawai
						left join pegawai_hash_link phl on phl.pegawai_id=p.pegawai_id
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?, ? ", $arrWhere)->getResult();
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(p.pegawai_id)) as field FROM pegawai p
						/* left join ms_unit_kerja uk on uk.unit_kerja_id=p.unit_kerja_id
						left join ms_jabatan j on j.jabatan_id=p.jabatan_id */
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}


	function pegawai_non_aktif($data_=false)
	{
		$unit_kerja = $this->request->getPost('unit_kerja');
		$jabatan = $this->request->getPost('jabatan');
		$kelamin = $this->request->getPost('kelamin');
		$status_pns = $this->request->getPost('status_pns');
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'pegawai_aktif'));
		}
		$search_value = $this->request->getPost('search');//$_POST['search']['value'];
		$arrWhere = [];
		$where = " p.status in (0,2) ";
		if($search_value<>''){
			$where .= " AND (p.nama like ? or p.nik like ? or p.nip like ? or p.hp like ? or p.tempat_lahir like ? or p.email like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		if(!empty($unit_kerja))
		{
			$where .= "AND p.unit_kerja_id in ? ";
			array_push($arrWhere, $unit_kerja);
		}
		if(!empty($jabatan))
		{
			$where .= "AND p.jabatan_id in ? ";
			array_push($arrWhere, $jabatan);
		}
		if(!empty($kelamin))
		{
			$where .= "AND p.kelamin in ? ";
			array_push($arrWhere, $kelamin);
		}
		if(!empty($status_pns))
		{
			$where .= "AND p.status_pns in ? ";
			array_push($arrWhere, $status_pns);
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q = $this->db->query("SELECT p.pegawai_id
						, p.nik
						, p.nip
						, p.npwp
						, p.nama
						, p.tempat_lahir
						, p.tanggal_lahir
						, p.kelamin
						, ifnull(ar.ref_name, '-') as kelamin_name
						, p.agama
						, ifnull(ar2.ref_name, '-') as agama_name
						, p.gelar_depan
						, p.gelar_belakang
						, p.unit_kerja_id
						, uk.unit_kerja_name as unit_kerja
						, p.jabatan_id
						, j.jabatan_name as jabatan
						, p.status
						, ifnull(ar4.ref_name, '-') as status_name
						, p.status_perkawinan
						, ifnull(CONCAT(ar6.ref_name,' (',ar6.ref_description,')'), '-') as status_perkawinan_name
						, p.status_bpjs
						, p.status_lhkpn
						, p.status_jenis_pegawai
						, ifnull(ar7.ref_name, '-') as status_jenis_pegawai_name
						, p.status_pns
						, ifnull(ar3.ref_name, '-') as status_pns_name
						, p.asal_instansi
						, p.nip_lama
						, p.pangkat
						, p.gol
						, p.eselon
						, p.tmt_pang_gol
						, p.pendidikan
						, ifnull(ar5.ref_name, '-') as pendidikan_name
						, p.universitas
						, p.email
						, p.email_pribadi
						, p.hp
						, p.telp
						, p.foto_pegawai
						, p.foto_pegawai_temp
						, p.kode_absen
						, p.idcard1
						, p.idcard2
						, p.gugustugas
						, ifnull((SELECT psk.periode_akhir from pegawai_sk psk WHERE psk.pegawai_id=p.pegawai_id order by psk.periode_akhir desc limit 1), '-') as masa_berakhir_tugas
						, ifnull(phl.qrcode, '') as qrcode_link
					FROM pegawai p
						left join ms_unit_kerja uk on uk.unit_kerja_id=p.unit_kerja_id
						left join ms_jabatan j on j.jabatan_id=p.jabatan_id
						left join app_referensi ar on ar.ref='gender' and ar.ref_status=1 and ar.ref_code=p.kelamin
						left join app_referensi ar2 on ar2.ref='agama' and ar2.ref_status=1 and ar2.ref_code=p.agama
						left join app_referensi ar3 on ar3.ref='pegawai_status_pns' and ar3.ref_status=1 and ar3.ref_code=p.status_pns
						left join app_referensi ar4 on ar4.ref='pegawai_status' and ar4.ref_status=1 and ar4.ref_code=p.status
						left join app_referensi ar5 on ar5.ref='pendidikan' and ar5.ref_status=1 and ar5.ref_code=p.pendidikan
						left join app_referensi ar6 on ar6.ref='pegawai_status_kawin' and ar6.ref_status=1 and ar6.ref_code=p.status_perkawinan
						left join app_referensi ar7 on ar7.ref='pegawai_status_jenis' and ar7.ref_status=1 and ar7.ref_code=p.status_jenis_pegawai
						left join pegawai_hash_link phl on phl.pegawai_id=p.pegawai_id
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?, ? ", $arrWhere)->getResult();
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(p.pegawai_id)) as field FROM pegawai p
						/* left join ms_unit_kerja uk on uk.unit_kerja_id=p.unit_kerja_id
						left join ms_jabatan j on j.jabatan_id=p.jabatan_id */
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}


	function pegawai_berulang_tahun($data_=false)
	{
		$unit_kerja = $this->request->getPost('unit_kerja');
		$jabatan = $this->request->getPost('jabatan');
		$kelamin = $this->request->getPost('kelamin');
		$status_pns = $this->request->getPost('status_pns');
		$usia = $this->request->getPost('usia');
		$bulan = ($this->request->getPost('bulan'))?:date('m');
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'pegawai_aktif'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " p.status in (1) ";
		$where .= " and substring(p.tanggal_lahir,6,2)=? ";
		array_push($arrWhere, $bulan);
		if($search_value<>''){
			$where .= " AND (p.nama like ? or p.nik like ? or p.nip like ? or p.hp like ? or p.tempat_lahir like ? ) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		if(!empty($unit_kerja))
		{
			$where .= "AND p.unit_kerja_id in ? ";
			array_push($arrWhere, $unit_kerja);
		}
		if(!empty($jabatan))
		{
			$where .= "AND p.jabatan_id in ? ";
			array_push($arrWhere, $jabatan);
		}
		if(!empty($kelamin))
		{
			$where .= "AND p.kelamin in ? ";
			array_push($arrWhere, $kelamin);
		}
		if(!empty($status_pns))
		{
			$where .= "AND p.status_pns in ? ";
			array_push($arrWhere, $status_pns);
		}
		if($usia<>''){
			if($usia=='<='){
				$where .= "AND TIMESTAMPDIFF(YEAR, p.tanggal_lahir, CURDATE()) <= 45 ";
			}else{
				$where .= "AND TIMESTAMPDIFF(YEAR, p.tanggal_lahir, CURDATE()) > 45 ";
			}
			// array_push($arrWhere, $usia);
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q = $this->db->query("SELECT p.pegawai_id
						, p.nama, p.tempat_lahir, p.tanggal_lahir, p.kelamin, ifnull(ar.ref_name, '-') as kelamin_name, p.gelar_depan, p.gelar_belakang, p.unit_kerja_id, uk.unit_kerja_name as unit_kerja, p.jabatan_id, j.jabatan_name as jabatan, p.status, p.foto_pegawai, p.foto_pegawai_temp
			    		, TIMESTAMPDIFF(YEAR, p.tanggal_lahir, CURDATE() ) AS umur
			    		, DATEDIFF(curdate(), CONCAT(YEAR(CURDATE()), SUBSTR(p.tanggal_lahir,5))) as countdown
					FROM pegawai p
						left join ms_unit_kerja uk on uk.unit_kerja_id=p.unit_kerja_id
						left join ms_jabatan j on j.jabatan_id=p.jabatan_id
						left join app_referensi ar on ar.ref='gender' and ar.ref_status=1 and ar.ref_code=p.kelamin
		    		WHERE ".$where." ORDER BY countdown ".$order_dir." limit ?, ? ", $arrWhere)->getResult();
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(p.pegawai_id)) as field FROM pegawai p
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function pegawai_save($data_, $id=0)
	{
		if($id > 0)
        {
            $this->db->table('pegawai')->update($data_, ['pegawai_id'=>$id]);
        }else{
            $this->db->table('pegawai')->insert($data_);
            $id = $this->db->insertID();
        }
        return $id;
	}

	function pegawai_save_array($field_data, $field_condition=[])
	{
		if(empty($field_condition))
		{
			$this->db->table('pegawai')->insert($field_data);
		}else{
			$this->db->table('pegawai')->update($field_data, $field_condition);
		}
		return $this->db->insertID();
	}



	function alamat_by_pegawai_id($id)
	{
		return $this->db->query("SELECT a.pegawai_id
			, a.alamat_id
			, a.alamat_name
			, a.kodepos
			, a.provinsi
			, ifnull(b.name, '-') as provinsi_name
			, a.kabupaten
			, ifnull(c.name, '-') as kabupaten_name
			, a.kecamatan
			, ifnull(d.name, '-') as kecamatan_name
			, a.kelurahan
			, ifnull(e.name, '-') as kelurahan_name
			, a.rw
			, a.rt
			, a.alamat
			, a.long
			, a.lat
			, a.owner
			, a.last_change
			, a.user_id
		FROM ms_alamat a 
			left join ms_wilayah b on a.provinsi=b.id
			left join ms_wilayah c on a.kabupaten=c.id
			left join ms_wilayah d on a.kecamatan=d.id
			left join ms_wilayah e on a.kelurahan=e.id
		WHERE a.pegawai_id=? and a.owner=? ", [$id, 'pegawai'])->getResult();
	}

	function alamat_get_row($id)
	{
		return $this->db->query("SELECT a.pegawai_id
			, a.alamat_id
			, a.alamat_name
			, a.kodepos
			, a.provinsi
			, ifnull(b.name, '-') as provinsi_name
			, a.kabupaten
			, ifnull(c.name, '-') as kabupaten_name
			, a.kecamatan
			, ifnull(d.name, '-') as kecamatan_name
			, a.kelurahan
			, ifnull(e.name, '-') as kelurahan_name
			, a.rw
			, a.rt
			, a.alamat
			, a.long
			, a.lat
			, a.owner
			, a.last_change
			, a.user_id
		FROM ms_alamat a 
			left join ms_wilayah b on a.provinsi=b.id
			left join ms_wilayah c on a.kabupaten=c.id
			left join ms_wilayah d on a.kecamatan=d.id
			left join ms_wilayah e on a.kelurahan=e.id
		WHERE a.alamat_id=? ", [$id])->getRow();
	}

	function alamat_save($data_, $id=0)
	{
		if($id > 0)
        {
            $this->db->table('ms_alamat')->update($data_, ['alamat_id'=>$id]);
        }else{
            $this->db->table('ms_alamat')->insert($data_);
            $id = $this->db->insertID();
        }
        return $id;
	}



	function sk_by_pegawai_id($id)
	{
		return $this->db->query("SELECT a.pegawai_id
			, a.unit_kerja_id
			, ifnull(b.unit_kerja_name, '-') as unit_kerja_name
			, a.jabatan_id
			, ifnull(c.jabatan_name, '-') as jabatan_name
			, a.id
			, a.jenis
			, ifnull(d.ref_name, '-') as jenis_name
			, a.nomor
			, a.tanggal
			, a.periode_awal
			, a.periode_akhir
			, a.keterangan
			, a.dokumen
			, a.status
			, case when a.status=1 then 'Aktif' else 'Berakhir' end as status_name
			, a.last_change
			, a.user_id
		FROM pegawai_sk a
			left join ms_unit_kerja b on a.unit_kerja_id=b.unit_kerja_id
			left join ms_jabatan c on a.jabatan_id=c.jabatan_id
			left join app_referensi d on d.ref_status=1 and d.ref='pegawai_sk' and a.jenis=d.ref_code
		WHERE a.pegawai_id=? ORDER BY a.id desc ", [$id])->getResult();
	}

	function sk_get_row($id)
	{
		return $this->db->query("SELECT a.pegawai_id
			, a.unit_kerja_id
			, ifnull(b.unit_kerja_name, '-') as unit_kerja_name
			, a.jabatan_id
			, ifnull(c.jabatan_name, '-') as jabatan_name
			, a.id
			, a.jenis
			, ifnull(d.ref_name, '-') as jenis_name
			, a.nomor
			, a.tanggal
			, a.periode_awal
			, a.periode_akhir
			, a.keterangan
			, a.dokumen
			, a.status
			, case when a.status=1 then 'Aktif' else 'Berakhir' end as status_name
			, a.last_change
			, a.user_id
		FROM pegawai_sk a
			left join ms_unit_kerja b on a.unit_kerja_id=b.unit_kerja_id
			left join ms_jabatan c on a.jabatan_id=c.jabatan_id
			left join app_referensi d on d.ref_status=1 and d.ref='pegawai_sk' and a.jenis=d.ref_code
		WHERE a.id=? ", [$id])->getRow();
	}

	function sk_save($data_, $id=0)
	{
		if($id > 0)
        {
            $this->db->table('pegawai_sk')->update($data_, ['id'=>$id]);
        }else{
            $this->db->table('pegawai_sk')->insert($data_);
            $id = $this->db->insertID();
        }
        return $id;
	}


	function fasilitas_by_pegawai_id($id)
	{
		return $this->db->query("SELECT a.pegawai_id
			, a.fasilitas_id
			, a.ref_fasilitas_id
			, ifnull(b.ref_name, '-') as fasilitas_name
			, a.fasilitas_tgl
			, a.tgl_dikembalikan
			, a.status
			, case when a.status=1 then 'Aktif' else 'Sudah dikembalikan' end as status_name
			, a.fasilitas_value
			, a.fasilitas_ket
			, a.last_change
			, a.user_id
		FROM pegawai_fasilitas a
			left join app_referensi b on b.ref_status=1 and b.ref='pegawai_fasilitas' and a.ref_fasilitas_id=b.ref_code
		WHERE a.pegawai_id=? ORDER BY a.fasilitas_tgl DESC", [$id])->getResult();
	}

	function fasilitas_get_row($id)
	{
		return $this->db->query("SELECT a.pegawai_id
			, a.fasilitas_id
			, a.ref_fasilitas_id
			, ifnull(b.ref_name, '-') as fasilitas_name
			, a.fasilitas_tgl
			, a.tgl_dikembalikan
			, a.status
			, case when a.status=1 then 'Aktif' else 'Sudah dikembalikan' end as status_name
			, a.fasilitas_value
			, a.fasilitas_ket
			, a.last_change
			, a.user_id
		FROM pegawai_fasilitas a
			left join app_referensi b on b.ref_status=1 and b.ref='pegawai_fasilitas' and a.ref_fasilitas_id=b.ref_code
		WHERE a.fasilitas_id=? ", [$id])->getRow();
	}

	function fasilitas_save($data_, $id=0)
	{
		if($id > 0)
        {
            $this->db->table('pegawai_fasilitas')->update($data_, ['fasilitas_id'=>$id]);
        }else{
            $this->db->table('pegawai_fasilitas')->insert($data_);
            $id = $this->db->insertID();
        }
        return $id;
	}


	function files_by_pegawai_id($id)
	{
		return $this->db->query("SELECT b.ref_code
			, a.pegawai_id
			, a.file_jenis
			, b.ref_name as file_jenis_name
			, a.file_path
			, a.file_id
			, a.last_change
			, a.user_id
			, case when a.pegawai_id is null then 0 else 1 end as status
		FROM app_referensi b
			left join pegawai_files a on a.file_jenis=b.ref_code and a.pegawai_id=?
		WHERE b.ref_status=1 and b.ref='pegawai_files' ORDER BY b.ref_code ASC", [$id])->getResult();
	}

	function files_return_path_by_pegawaiid_jenis($id, $jenis)
	{
		$rs = '';
		$q = $this->db->query("SELECT file_path as field FROM pegawai_files WHERE pegawai_id=? and file_jenis=? ", [$id, $jenis])->getRow();
		if(!empty($q))
		{
			$rs = $q->field;
		}
		return $rs;
	}

	function files_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('pegawai_files')->update($data_, $where);
        }else{
            $this->db->table('pegawai_files')->insert($data_);
            $id = $this->db->insertID();
        }
        return $id;
	}


	function hash_link_by_pegawai_id($id)
	{
		return $this->db->query("SELECT a.pegawai_id
			, b.nip, b.nama, b.gelar_depan, b.gelar_belakang, b.kelamin, b.jabatan_id, c.jabatan_name, b.foto_pegawai, e.gugustugas
			, (SELECT d.periode_awal FROM pegawai_sk d WHERE d.pegawai_id=b.pegawai_id ORDER BY d.periode_awal DESC limit 1 ) AS periode_awal
			, (SELECT d.periode_akhir FROM pegawai_sk d WHERE d.pegawai_id=b.pegawai_id ORDER BY d.periode_awal DESC limit 1 ) AS periode_akhir
			, a.url
			, a.id_hash
			, a.qrcode
			, a.last_change
			, a.user_id
		FROM pegawai_hash_link a
			left join pegawai b on a.pegawai_id=b.pegawai_id
			left join ms_jabatan c on b.jabatan_id=c.jabatan_id
			left join ms_gugus_tugas e on e.id=b.gugustugas
		WHERE a.pegawai_id=? ", [$id])->getRow();
	}

	function hash_link_get_row($id)
	{
		return $this->db->query("SELECT a.pegawai_id
			, a.url
			, a.id_hash
			, a.qrcode
			, a.last_change
			, a.user_id
		FROM pegawai_hash_link a
			left join pegawai b on a.pegawai_id=b.pegawai_id
		WHERE a.id_hash=? ", [$id])->getRow();
	}

	function hash_link_save($data_)
	{
     	$this->db->table('pegawai_hash_link')->replace($data_);
        return $this->db->insertID();
	}

	function hash_link_log_save($data_)
	{
     	$this->db->table('pegawai_hash_link_log')->insert($data_);
        return $this->db->insertID();
	}


	function user_pegawai_get_row($id)
	{
		$rs = [];
		$peg = $this->db->query("SELECT email FROM pegawai where pegawai_id=? ", [$id])->getRow();
		if(!empty($peg))
		{
			$rs = $this->db->query("SELECT id, username, email, status, activation_key, password FROM app_users where email=? ", [$peg->email])->getRow();
		}
		return $rs;
	}

	function user_pegawai_save($data_, $id=0)
	{
		if($id > 0)
        {
            $this->db->table('app_users')->update($data_, ['id'=>$id]);
        }else{
            $this->db->table('app_users')->insert($data_);
            $id = $this->db->insertID();
            $this->db->table('app_users_roles')->insert([
            	'id_user'=>$id,
            	'id_role'=>3,
            	'user_id'=>session()->get('id')
            ]);
        }
        return $id;
	}



	function pegawai_sk_tim_datatable($data_=false)
	{
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'tim_kerja'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = "a.status_sk in (0,1,2) ";
		if($search_value<>''){
			$where .= " AND (a.nomor_sk like ? or a.keterangan like ? ) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q = $this->db->query("SELECT a.id_sk_tim, a.nomor_sk, a.tgl_sk, a.tgl_awal, a.tgl_akhir, a.status_sk, case when a.status_sk=1 then 'Aktif' else 'Berakhir' end as status_sk_name, a.keterangan, a.file, (SELECT count(b.id) from pegawai_sk_tim_detail b WHERE id_sk_tim=a.id_sk_tim) as anggota
		    	 FROM pegawai_sk_tim a
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?, ? ", $arrWhere)->getResult();
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(a.id_sk_tim)) as field FROM pegawai_sk_tim a
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function pegawai_sk_tim_save($data_, $id=0)
	{
		if($id > 0)
        {
            $this->db->table('pegawai_sk_tim')->update($data_, ['id_sk_tim'=>$id]);
        }else{
            $this->db->table('pegawai_sk_tim')->insert($data_);
            $id = $this->db->insertID();
        }
        return $this->db->affectedRows();
	}

	function pegawai_sk_tim_get_row($id)
	{
		return $this->db->table('pegawai_sk_tim')->getWhere(['id_sk_tim'=>$id])->getRow();
	}

	function pegawai_sk_tim_detail_save($data_, $id=0)
	{
		if($id > 0)
        {
            $this->db->table('pegawai_sk_tim_detail')->update($data_, ['id'=>$id]);
        }else{
            $this->db->table('pegawai_sk_tim_detail')->insert($data_);
            $id = $this->db->insertID();
        }
        return $this->db->affectedRows();
	}

	function pegawai_sk_tim_detail_by_skid($id)
	{
		return $this->db->query("SELECT a.id, a.id_sk_tim, a.pegawai_id, case when a.source=1 then b.nama else e.nama end as nama, a.jabatan_id, case when a.source=1 then c.jabatan_name else e.jabatan end as jabatan, a.unit_kerja_id, case when a.source=1 then d.unit_kerja_name else e.instansi end as unit_kerja, case when a.source=1 then b.email else e.email end as email, case when a.source=1 then concat(b.hp,', ',b.telp) else e.kontak end as kontak, a.status, a.source, a.last_change, a.user_id 
			FROM pegawai_sk_tim_detail a
				left join pegawai b on a.pegawai_id=b.pegawai_id and a.source=1
				left join ms_jabatan c on b.jabatan_id=c.jabatan_id
				left join ms_unit_kerja d on b.unit_kerja_id=d.unit_kerja_id
				left join member_eksternal e on a.pegawai_id=e.id and a.source=2
			WHERE id_sk_tim=?",
			[$id])->getResult();
	}

	function pegawai_sk_tim_detail_get_row($id)
	{
		return $this->db->table('pegawai_sk_tim_detail')->getWhere(['id'=>$id])->getRow();
	}


	// HAK KEUANGAN/SKP
	function keuangan_datatable($data_=false)
	{
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'skp'));
		}
		$search_value = $this->request->getPost('search');//$_POST['search']['value'];
		// $periode = $this->request->getPost('periode');
		$tahun = $this->request->getPost('tahun');
		$bulan = $this->request->getPost('bulan');
		$unit_kerja = $this->request->getPost('unit_kerja');
		$jabatan = $this->request->getPost('jabatan');
		$arrWhere = [];
		$where = " 1 ";
		if($search_value<>''){
			$where .= " AND (a.NMPPNPN like ? or a.NIKPPNPN like ? ) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		if(/*$periode<>'' &&*/ $tahun<>'' && $bulan<>''){
			$where .= " and a.PERIODE=? ";
			array_push($arrWhere, $tahun .'-'. $bulan);
		}else{
			$where .= " and a.PERIODE=? ";
			array_push($arrWhere, date('Y-m'));
		}
		if(!empty($unit_kerja)){
			$where .= " and b.unit_kerja_id in ? ";
			array_push($arrWhere, $unit_kerja);
		}
		if(!empty($jabatan)){
			$where .= " and b.jabatan_id in ? ";
			array_push($arrWhere, $jabatan);
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q = $this->db->query("SELECT a.NIKPPNPN, a.NMPPNPN, a.NPWP, a.STATUS, a.NOMORSK, a.TGLSK, a.PENGHASILAN, a.PPH, a.TUNJPPH, a.IURAN, a.IURANKEL, a.JMLKEL, a.STSPAJAK, a.CURUT, a.CKALI, a.KDJNS, a.NOREK, a.NILTERIMA, a.KDSATKER, a.KDANAK, a.KDDEPT, a.KDUNIT, a.NMSATKER, a.NMANAK, a.NMDEPT, a.NMUNIT, a.JMLPOTONGAN, a.NMSATKERLAP, a.NMANAKSATKERLAP, a.NMDEPTLAP, a.NMJABATANTTD, a.KOTATTD, a.KOTATTD1, a.NIPTTD, a.NAMATTD, a.PERIODE, a.file, a.file_sign, a.unix_id 
		    			, UPPER(c.jabatan_name) as JABATAN, b.pegawai_id, d.ref_description as status_kawin
		    		FROM skp_import a
		    			left join pegawai b on b.nik=a.NIKPPNPN
		    			left join ms_jabatan c on c.jabatan_id=b.jabatan_id
		    			left join app_referensi d on d.ref='pegawai_status_kawin' and d.ref_code=a.STATUS
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?, ? ", $arrWhere)->getResult();
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(NIKPPNPN)) as field FROM skp_import a
		    			left join pegawai b on b.nik=a.NIKPPNPN
		    			left join ms_jabatan c on c.jabatan_id=b.jabatan_id
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function keuangan_client_datatable($data_=false)
	{
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'skp'));
		}
		$search_value = $_POST['search']['value'];
		$pegawai_id = $this->request->getPost('pegawai_id');
		$periode = $this->request->getPost('periode');
		$arrWhere = [];
		$where = " b.pegawai_id=? ";
		array_push($arrWhere, $pegawai_id);
		if($search_value<>''){
			$where .= " AND (a.STATUS like ? or a.NIKPPNPN like ? ) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		if($periode<>'' && $this->request->getPost('check_filter')==1){
			$where .= " and a.PERIODE=? ";
			array_push($arrWhere, $periode);
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q = $this->db->query("SELECT a.NIKPPNPN, a.NMPPNPN, a.NPWP, a.STATUS, a.NOMORSK, a.TGLSK, a.PENGHASILAN, a.PPH, a.TUNJPPH, a.IURAN, a.IURANKEL, a.JMLKEL, a.STSPAJAK, a.CURUT, a.CKALI, a.KDJNS, a.NOREK, a.NILTERIMA, a.KDSATKER, a.KDANAK, a.KDDEPT, a.KDUNIT, a.NMSATKER, a.NMANAK, a.NMDEPT, a.NMUNIT, a.JMLPOTONGAN, a.NMSATKERLAP, a.NMANAKSATKERLAP, a.NMDEPTLAP, a.NMJABATANTTD, a.KOTATTD, a.KOTATTD1, a.NIPTTD, a.NAMATTD, a.PERIODE, a.file, a.file_sign, a.unix_id 
		    			, UPPER(c.jabatan_name) as JABATAN, b.pegawai_id, d.ref_description as status_kawin
		    		FROM skp_import a
		    			left join pegawai b on b.nik=a.NIKPPNPN
		    			left join ms_jabatan c on c.jabatan_id=b.jabatan_id
		    			left join app_referensi d on d.ref='pegawai_status_kawin' and d.ref_code=a.STATUS
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?, ? ", $arrWhere)->getResult();
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(NIKPPNPN)) as field FROM skp_import a
		    			left join pegawai b on b.nik=a.NIKPPNPN
		    			left join ms_jabatan c on c.jabatan_id=b.jabatan_id
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function keuangan_save($data_, $where)
	{
		if(!empty($where))
        {
            $this->db->table('skp_import')->update($data_, $where);
        }else{
            $this->db->table('skp_import')->insert($data_);
            $id = $this->db->insertID();
        }
        return $this->db->affectedRows();
	}

	function keuangan_get_row($id, $periode='')
	{
		return $this->db->table('skp_import')->getWhere(['NIKPPNPN'=>$id, 'PERIODE'=>$periode])->getRow();
	}

	function keuangan_result($search_value, $periode, $unit_kerja, $jabatan)
	{
		$arrWhere = [];
		$where = " 1 ";
		if($search_value<>''){
			$where .= " AND (a.NMPPNPN like ? or a.NIKPPNPN like ? ) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		if($periode<>'' /*&& $this->request->getPost('check_filter')==1*/){
			$where .= " and a.PERIODE=? ";
			array_push($arrWhere, $periode);
		}
		if(!empty($unit_kerja)){
			$where .= " and b.unit_kerja_id in ? ";
			array_push($arrWhere, $unit_kerja);
		}
		if(!empty($jabatan)){
			$where .= " and b.jabatan_id in ? ";
			array_push($arrWhere, $jabatan);
		}
    	$q = $this->db->query("SELECT a.NIKPPNPN, a.NMPPNPN, a.NPWP, a.STATUS, a.NOMORSK, a.TGLSK, a.PENGHASILAN, a.PPH, a.TUNJPPH, a.IURAN, a.IURANKEL, a.JMLKEL, a.STSPAJAK, a.CURUT, a.CKALI, a.KDJNS, a.NOREK, a.NILTERIMA, a.KDSATKER, a.KDANAK, a.KDDEPT, a.KDUNIT, a.NMSATKER, a.NMANAK, a.NMDEPT, a.NMUNIT, a.JMLPOTONGAN, a.NMSATKERLAP, a.NMANAKSATKERLAP, a.NMDEPTLAP, a.NMJABATANTTD, a.KOTATTD, a.KOTATTD1, a.NIPTTD, a.NAMATTD, a.PERIODE, a.file, a.file_sign, a.unix_id 
    			, UPPER(c.jabatan_name) as JABATAN, b.pegawai_id, d.ref_description as status_kawin
    		FROM skp_import a
    			left join pegawai b on b.nik=a.NIKPPNPN
    			left join ms_jabatan c on c.jabatan_id=b.jabatan_id
    			left join app_referensi d on d.ref='pegawai_status_kawin' and d.ref_code=a.STATUS
    		WHERE ".$where, $arrWhere)->getResult();
	    return $q;
	}


	// BUKTI POTONG PAJAK
	function pajak_datatable($data_=false)
	{
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'bpp'));
		}
		$search_value = $this->request->getPost('search');//$_POST['search']['value'];
		$periode = $this->request->getPost('periode');
		$unit_kerja = $this->request->getPost('unit_kerja');
		$jabatan = $this->request->getPost('jabatan');
		$arrWhere = [];
		$where = " 1 ";
		array_push($arrWhere, $periode);
		if($search_value<>''){
			$where .= " AND (b.nama like ? or b.nik like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		if($periode<>'' && $this->request->getPost('check_filter')==1){
			$where .= " and a.periode=? ";
			array_push($arrWhere, $periode);
		}
		if(!empty($unit_kerja)){
			$where .= " and b.unit_kerja_id in ? ";
			array_push($arrWhere, $unit_kerja);
		}
		if(!empty($jabatan)){
			$where .= " and b.jabatan_id in ? ";
			array_push($arrWhere, $jabatan);
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q = $this->db->query("SELECT 
		    		ifnull(a.id, 0) as id, b.pegawai_id, b.nik, b.nama, b.unit_kerja_id, c.unit_kerja_name, c.unit_kerja_name_alt, b.jabatan_id, d.jabatan_name
		    		, ifnull(a.periode, 0) as periode, ifnull(a.file_id, 0) as file_id, ifnull(a.file_path, 0) as file_path, ifnull(a.create_at, 0) as create_at, ifnull(a.status, 0) as status, ifnull(a.user_id , 0) as user_id
		    		FROM pegawai b
		    			left join bukti_potong_pajak a on b.pegawai_id=a.pegawai_id and a.periode=?
		    			join ms_unit_kerja c on c.unit_kerja_id=b.unit_kerja_id
		    			join ms_jabatan d on  d.jabatan_id=b.jabatan_id
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?, ? ", $arrWhere)->getResult();
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(b.pegawai_id)) as field FROM pegawai b
		    			left join bukti_potong_pajak a on b.pegawai_id=a.pegawai_id and a.periode=?
		    			join ms_unit_kerja c on c.unit_kerja_id=b.unit_kerja_id
		    			join ms_jabatan d on  d.jabatan_id=b.jabatan_id
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function pajak_client_datatable($data_=false)
	{
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'bpp'));
		}
		$search_value = $_POST['search']['value'];
		$pegawai_id = $this->request->getPost('pegawai_id');
		$periode = $this->request->getPost('periode');
		$arrWhere = [];
		$where = " a.pegawai_id=? ";
		array_push($arrWhere, $pegawai_id);
		if($search_value<>''){
			$where .= " AND (b.nama like ? or b.nik like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		if($periode<>'' && $this->request->getPost('check_filter')==1){
			$where .= " and a.periode=? ";
			array_push($arrWhere, $periode);
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q = $this->db->query("SELECT 
		    		ifnull(a.id, 0) as id, b.pegawai_id, b.nik, b.nama, b.unit_kerja_id, c.unit_kerja_name, c.unit_kerja_name_alt, b.jabatan_id, d.jabatan_name
		    		, ifnull(a.periode, 0) as periode, ifnull(a.file_id, 0) as file_id, ifnull(a.file_path, 0) as file_path, ifnull(a.create_at, 0) as create_at, ifnull(a.status, 0) as status, ifnull(a.user_id , 0) as user_id
		    		FROM bukti_potong_pajak a
		    			left join pegawai b on b.pegawai_id=a.pegawai_id 
		    			join ms_unit_kerja c on c.unit_kerja_id=b.unit_kerja_id
		    			join ms_jabatan d on  d.jabatan_id=b.jabatan_id
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?, ? ", $arrWhere)->getResult();
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(b.pegawai_id)) as field FROM bukti_potong_pajak a
		    			left join pegawai b on b.pegawai_id=a.pegawai_id 
		    			join ms_unit_kerja c on c.unit_kerja_id=b.unit_kerja_id
		    			join ms_jabatan d on  d.jabatan_id=b.jabatan_id
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function pajak_save($data_, $id=0)
	{
		if($id > 0)
        {
            $this->db->table('bukti_potong_pajak')->update($data_, ['id'=>$id]);
        }else{
            $this->db->table('bukti_potong_pajak')->insert($data_);
            $id = $this->db->insertID();
        }
        return $this->db->affectedRows();
	}

	function pajak_get_row($id)
	{
		return $this->db->table('bukti_potong_pajak')->getWhere(['id'=>$id])->getRow();
	}


	/*
	*	PEGAWAI RESULT & ROW
	*/
	function pegawai_result_in_jabatanid($id)
	{
		return $this->db->query("SELECT a.pegawai_id, a.nik, a.npwp, a.nip, a.nama, a.gelar_depan, a.gelar_belakang, a.email, a.hp
			, a.unit_kerja_id
			, ifnull(b.unit_kerja_name, '-') as unit_kerja_name
			, ifnull(b.unit_kerja_name_alt, '-') as unit_kerja_name_alt
			, a.jabatan_id
			, ifnull(c.jabatan_name, '-') as jabatan_name
		FROM pegawai a
			left join ms_unit_kerja b on a.unit_kerja_id=b.unit_kerja_id
			left join ms_jabatan c on a.jabatan_id=c.jabatan_id
		WHERE a.status=? and a.jabatan_id in ? ORDER BY c.jabatan_id asc ", [1, $id])->getResult();
	}

	function pegawai_row_by_pegawaiid($id)
	{
		return $this->db->query("SELECT a.pegawai_id, a.nik, a.npwp, a.nip, a.nama, a.gelar_depan, a.gelar_belakang, a.email, a.hp
			, a.unit_kerja_id
			, ifnull(b.unit_kerja_name, '-') as unit_kerja_name
			, ifnull(b.unit_kerja_name_alt, '-') as unit_kerja_name_alt
			, a.jabatan_id
			, ifnull(c.jabatan_name, '-') as jabatan_name
		FROM pegawai a
			left join ms_unit_kerja b on a.unit_kerja_id=b.unit_kerja_id
			left join ms_jabatan c on a.jabatan_id=c.jabatan_id
		WHERE a.status=? and a.pegawai_id=? ORDER BY c.jabatan_id asc ", [1, $id])->getRow();
	}

	function pegawai_aktif_result_in_pegawaiid($id)
	{
		return $this->db->query("SELECT a.pegawai_id, a.nik, a.npwp, a.nip, a.nama, a.gelar_depan, a.gelar_belakang, a.email, a.hp
			, a.unit_kerja_id
			, ifnull(b.unit_kerja_name, '-') as unit_kerja_name
			, ifnull(b.unit_kerja_name_alt, '-') as unit_kerja_name_alt
			, a.jabatan_id
			, ifnull(c.jabatan_name, '-') as jabatan_name
		FROM pegawai a
			left join ms_unit_kerja b on a.unit_kerja_id=b.unit_kerja_id
			left join ms_jabatan c on a.jabatan_id=c.jabatan_id
		WHERE a.status=? and a.pegawai_id in ? ORDER BY c.jabatan_id asc ", [1, $id])->getResult();
	}

	function pegawai_aktif_result_in_unit($id)
	{
		return $this->db->query("SELECT a.pegawai_id, a.nik, a.npwp, a.nip, a.nama, a.gelar_depan, a.gelar_belakang, a.email, a.hp
			, a.unit_kerja_id
			, ifnull(b.unit_kerja_name, '-') as unit_kerja_name
			, ifnull(b.unit_kerja_name_alt, '-') as unit_kerja_name_alt
			, a.jabatan_id
			, ifnull(c.jabatan_name, '-') as jabatan_name
		FROM pegawai a
			left join ms_unit_kerja b on a.unit_kerja_id=b.unit_kerja_id
			left join ms_jabatan c on a.jabatan_id=c.jabatan_id
		WHERE a.status=? and a.unit_kerja_id in ? ORDER BY c.jabatan_id asc ", [1, $id])->getResult();
	}

	function pegawai_aktif_result_all()
	{
		return $this->db->query("SELECT a.pegawai_id, a.nik, a.npwp, a.nip, a.nama, a.gelar_depan, a.gelar_belakang, a.email, a.hp
			, a.unit_kerja_id
			, ifnull(b.unit_kerja_name, '-') as unit_kerja_name
			, ifnull(b.unit_kerja_name_alt, '-') as unit_kerja_name_alt
			, a.jabatan_id
			, ifnull(c.jabatan_name, '-') as jabatan_name
		FROM pegawai a
			left join ms_unit_kerja b on a.unit_kerja_id=b.unit_kerja_id
			left join ms_jabatan c on a.jabatan_id=c.jabatan_id
		WHERE a.status=? ORDER BY c.jabatan_id asc ", [1])->getResult();
	}




	/*
	*	MASTER
	*/
	function unit_kerja_datatable($data_=false)
	{
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'unit_kerja'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " unit_kerja_status in (0,1) ";
		if($search_value<>''){
			$where .= " AND (unit_kerja_name like ? or unit_kerja_description like ? ) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q = $this->db->query("SELECT unit_kerja_id, unit_kerja_name, unit_kerja_name_alt, unit_kerja_description, unit_kerja_status, case when unit_kerja_status=1 then 'Aktif' else 'Non Aktif' end as status_name, urutan, menu_link, last_change, user_id FROM ms_unit_kerja
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?, ? ", $arrWhere)->getResult();
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(unit_kerja_id)) as field FROM ms_unit_kerja
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function unit_kerja_save($data_, $id=0)
	{
		if($id > 0)
        {
            $this->db->table('ms_unit_kerja')->update($data_, ['unit_kerja_id'=>$id]);
        }else{
            $this->db->table('ms_unit_kerja')->insert($data_);
            $id = $this->db->insertID();
        }
        return $this->db->affectedRows();
	}

	function unit_kerja_get_row($id)
	{
		return $this->db->table('ms_unit_kerja')->getWhere(['unit_kerja_id'=>$id])->getRow();
	}

	function return_unit_kerja($id, $opt=1)
	{
		$rs = '-';
		$q = $this->db->table('ms_unit_kerja')->getWhere(['unit_kerja_id'=>$id])->getRow();
		if(!empty($q)){
			switch ($opt) {
				case 1:
					$rs = $q->unit_kerja_name;
					break;
				case 2:
					$rs = $q->unit_kerja_name_alt;
					break;
				default:
					$rs = $q->unit_kerja_name_alt .' ('. $q->unit_kerja_name .')';
					break;
			}
		}
		return $rs;
	}



	function jabatan_datatable($data_=false)
	{
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'jabatan'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " jabatan_status in (0,1) ";
		if($search_value<>''){
			$where .= " AND (jabatan_name like ? or jabatan_description like ? ) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q = $this->db->query("SELECT jabatan_id, jabatan_name, jabatan_description, jabatan_setara, jabatan_status, case when jabatan_status=1 then 'Aktif' else 'Non Aktif' end as status_name, jabatan_slot, jabatan_slot_terpakai, jabatan_slot_kosong, last_change, user_id FROM ms_jabatan 
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?, ? ", $arrWhere)->getResult();
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(jabatan_id)) as field FROM ms_jabatan
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function jabatan_save($data_, $id=0)
	{
		if($id > 0)
        {
            $this->db->table('ms_jabatan')->update($data_, ['jabatan_id'=>$id]);
        }else{
            $this->db->table('ms_jabatan')->insert($data_);
            $id = $this->db->insertID();
        }
        return $this->db->affectedRows();
	}

	function jabatan_get_row($id)
	{
		return $this->db->table('ms_jabatan')->getWhere(['jabatan_id'=>$id])->getRow();
	}

	function return_jabatan($id)
	{
		$rs = '-';
		$q = $this->db->table('ms_jabatan')->getWhere(['jabatan_id'=>$id])->getRow();
		if(!empty($q)){
			$rs = $q->jabatan_name;
		}
		return $rs;
	}



	function gugus_tugas_datatable($data_=false)
	{
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'gugus_tugas'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " status in (0,1) ";
		if($search_value<>''){
			$where .= " AND (gugustugas like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q = $this->db->query("SELECT id, gugustugas, status, case when status=1 then 'Aktif' else 'Non Aktif' end as status_name, last_chage, user_id FROM ms_gugus_tugas
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?, ? ", $arrWhere)->getResult();
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(id)) as field FROM ms_gugus_tugas
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function gugus_tugas_save($data_, $id=0)
	{
		if($id > 0)
        {
            $this->db->table('ms_gugus_tugas')->update($data_, ['id'=>$id]);
        }else{
            $this->db->table('ms_gugus_tugas')->insert($data_);
            $id = $this->db->insertID();
        }
        return $this->db->affectedRows();
	}

	function gugus_tugas_get_row($id)
	{
		return $this->db->table('ms_gugus_tugas')->getWhere(['id'=>$id])->getRow();
	}



	function perguruan_tinggi_datatable($data_=false)
	{
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'perguruan_tinggi'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " 1 ";
		if($search_value<>''){
			$where .= " AND (nama_pt like ? or alamat_pt like ? or kota_pt like ? or negara_pt like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q = $this->db->query("SELECT id_pt, nama_pt, alamat_pt, telp_pt, kota_pt, negara_pt FROM ms_perguruan_tinggi
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?, ? ", $arrWhere)->getResult();
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(id_pt)) as field FROM ms_perguruan_tinggi
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function perguruan_tinggi_save($data_, $id=0)
	{
		if($id > 0)
        {
            $this->db->table('ms_perguruan_tinggi')->update($data_, ['id_pt'=>$id]);
        }else{
            $this->db->table('ms_perguruan_tinggi')->insert($data_);
            $id = $this->db->insertID();
        }
        return $this->db->affectedRows();
	}

	function perguruan_tinggi_get_row($id)
	{
		return $this->db->table('ms_perguruan_tinggi')->getWhere(['id_pt'=>$id])->getRow();
	}



	function filterFieldData($field, $name)
	{
		switch ($name) {
			case 'bpp':
				$r_field = 'id';
				$array_field = ['id', 'pegawai_id', 'nik', 'file_id', 'periode', 'create_at', 'status', 'user_id'];
				break;
			case 'skp':
				$r_field = 'NMPPNPN';
				$array_field = ['NIKPPNPN', 'NMPPNPN', 'NPWP', 'STATUS', 'NOMORSK', 'TGLSK', 'PENGHASILAN', 'PPH', 'TUNJPPH', 'IURAN', 'IURANKEL', 'JMLKEL', 'STSPAJAK', 'CURUT', 'CKALI', 'KDJNS', 'NOREK', 'NILTERIMA', 'KDSATKER', 'KDANAK', 'KDDEPT', 'KDUNIT', 'NMSATKER', 'NMANAK', 'NMDEPT', 'NMUNIT', 'JMLPOTONGAN', 'NMSATKERLAP', 'NMANAKSATKERLAP', 'NMDEPTLAP', 'NMJABATANTTD', 'KOTATTD', 'KOTATTD1', 'NIPTTD', 'NAMATTD', 'PERIODE' ];
				break;
			case 'tim_kerja':
				$r_field = 'id_sk_tim';
				$array_field = ['id_sk_tim', 'nomor_sk', 'tgl_sk', 'tgl_awal', 'tgl_akhir', 'status_sk', 'keterangan', 'file'];
				break;
			case 'perguruan_tinggi':
				$r_field = 'id_pt';
				$array_field = ['id_pt', 'nama_pt', 'alamat_pt', 'telp_pt', 'kota_pt', 'negara_pt'];
				break;
			case 'gugus_tugas':
				$r_field = 'id';
				$array_field = ['id', 'gugustugas', 'status', 'last_chage', 'user_id'];
				break;
			case 'jabatan':
				$r_field = 'jabatan_id';
				$array_field = ['jabatan_id', 'jabatan_name', 'jabatan_description', 'jabatan_setara', 'jabatan_status', 'jabatan_slot', 'jabatan_slot_terpakai', 'jabatan_slot_kosong', 'last_change', 'user_id'];
				break;
			case 'unit_kerja':
				$r_field = 'unit_kerja_id';
				$array_field = ['unit_kerja_id', 'unit_kerja_name', 'unit_kerja_description', 'unit_kerja_status', 'urutan', 'menu_link', 'last_change', 'user_id'];
				break;
			case 'pegawai_aktif':
			case 'pegawai_non_ktif':
				$r_field = 'pegawai_id';
				$array_field = ['pegawai_id', 'nik', 'nip', 'npwp', 'nama', 'tempat_lahir', 'tanggal_lahir', 'kelamin', 'agama', 'gelar_depan', 'gelar_belakang', 'unit_kerja_id', 'unit_kerja_name', 'jabatan_id', 'jabatan_name', 'status', 'status_perkawinan', 'status_bpjs', 'status_lhkpn', 'status_pns', 'asal_instansi', 'pangkat', 'gol', 'eselon', 'pendidikan', 'universitas', 'email', 'email_pribadi', 'hp', 'telp', 'foto_pegawai', 'foto_pegawai_temp', 'kode_absen', 'idcard1', 'idcard2', 'gugustugas', 'tmt_pang_gol '];
				break;
			default:
				// code...
				break;
		}
		if (in_array($field, $array_field, true))
		{
			$r_field = $field;
		}
		return $r_field;
	}



	/*
	*	generate file
	*/
	function create_file_slip_gaji_pegawai($data) {
		$rs = false;
		$path_file_new = '';
		if(!empty($data))
		{
			@unlink($data->file);
			$ttd = $this->kepala_sekretariat_get_data();
			$file_yg_di_proses = WRITEPATH.'templates/slip_gaji.docx';
			$pathfile_replace = WRITEPATH.'files_skp/'.str_replace([' ', '.'], '-', tanggal($data->PERIODE,2)).'_'.str_replace([' ','.','"',"'"], '-', trim($data->NMPPNPN)).'_'.$data->NIKPPNPN.'.docx';
			// require APPPATH.'Libraries/PHPWord-develop/bootstrap.php';
			// $phpWord = new \PhpOffice\PhpWord\PhpWord();
			// $document = $phpWord->loadTemplate($file_yg_di_proses);
			$document = new TemplateProcessor($file_yg_di_proses);
			$document->setValue('nama', str_replace(["'", '"'], '-', ucwords(strtolower(trim($data->NMPPNPN)))));
			$document->setValue('jabatan', ucwords(strtolower($data->JABATAN)));
			$document->setValue('nik', $data->NIKPPNPN);
			$document->setValue('npwp', $data->NPWP);
			$document->setValue('rekening', $data->NOREK);
			$document->setValue('periode', tanggal($data->PERIODE,2));
			$document->setValue('hak_keuangan', formatRupiah($data->PENGHASILAN,0));
			$document->setValue('jumlah_penghasilan', formatRupiah($data->PENGHASILAN,0));
			$document->setValue('pph21', formatRupiah($data->PPH,0));
			$document->setValue('iuran_bpjs', formatRupiah($data->IURAN,0));
			$document->setValue('jumlah_potongan', formatRupiah($data->JMLPOTONGAN,0));
			$document->setValue('penghasilan_bersih', formatRupiah($data->NILTERIMA));
			$document->setValue('terbilang', nilai_terbilang($data->NILTERIMA));
			$document->setValue('tanggal', tanggal(date('Y-m-d'),3));
			$document->setValue('nama_pejabat', $ttd->nama);
			$document->saveAs($pathfile_replace);
			ob_clean();
			// EXPORT PDF USING LIBRE/OPENOFFICE
			$path_file_new = str_replace(['.docx', '.DOCX'], '.pdf', $pathfile_replace);
			putenv('PATH=/usr/local/bin:/bin:/usr/bin:/usr/local/sbin:/usr/sbin:/sbin');
			putenv('HOME=/tmp');
			$txt_shell = 'soffice --headless --convert-to pdf --outdir "'.WRITEPATH.'files_skp/" '.$pathfile_replace.' ';
			$shell_exec = shell_exec($txt_shell);
			if($shell_exec==NULL)
			{
				$shell_exec = 'gagal membuat pdf';
			}else{
				$rs = true;
				$this->keuangan_save(['file'=>$path_file_new, 'unix_id'=>string_to($data->PERIODE.'#'.$data->NIKPPNPN, 'encode')], ['NIKPPNPN'=>$data->NIKPPNPN, 'PERIODE'=>$data->PERIODE]);
			}
			testaaa(['content'=>$txt_shell .' <===||===> '. $shell_exec]);
			@unlink($pathfile_replace);
		}
		return $rs;
	}

	function kepala_sekretariat_get_data()
	{
		return $this->db->table('pegawai')->getWhere(['jabatan_id'=>10, 'status'=>1])->getRow();
	}
}