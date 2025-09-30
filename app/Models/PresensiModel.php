<?php 
namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Tools;
// use App\Models\LoadModel;

class PresensiModel extends Model
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


	function presensi_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('presensi')->update($data_, $where);
            $id = $this->db->insertID();
        }else{
            $this->db->table('presensi')->insert($data_);
            $id = $this->db->insertID();
        }
        return $id;
	}


	function presensi_final_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('presensi_final')->update($data_, $where);
            $id = $this->db->insertID();
        }else{
            $this->db->table('presensi_final')->insert($data_);
            $id = $this->db->insertID();
        }
        return $id;
	}


	function presensi_final_in_riwayat($data_=false)
	{
		// helper('toolshelp');
		$pegawai_id = string_to($this->request->getPost('pegawai_id'), 'decode');
		$periode = $this->request->getPost('periode');
		// $start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		// $length = (int) (($_POST['length']) ? : 50);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = $this->filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'riwayat'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " pf.pegawai_id=? and substr(pf.tanggal, 1, 7)=? ";
		array_push($arrWhere, $pegawai_id);
		array_push($arrWhere, $periode);
		if($search_value<>''){
			// $where .= " AND (p.nama like ? or p.nik like ? or p.nip like ? or p.hp like ? or p.tempat_lahir like ? ) ";
			// array_push($arrWhere, '%'.$search_value.'%');
			// array_push($arrWhere, '%'.$search_value.'%');
			// array_push($arrWhere, '%'.$search_value.'%');
			// array_push($arrWhere, '%'.$search_value.'%');
			// array_push($arrWhere, '%'.$search_value.'%');
		}
		switch ($data_) {
			case true:
				// array_push($arrWhere, $start);
				// array_push($arrWhere, $length);
		    	$q_rs = $this->db->query("SELECT pf.tanggal, pf.pegawai_id, pf.start, pf.start_ip, pf.start_latlong, pf.start_user, pf.start_catatan, pf.start_log, pf.stop, pf.stop_ip, pf.stop_latlong, pf.stop_user, pf.stop_catatan, pf.stop_log, pf.status, pf.pjk_id, pf.kode_hari, pf.total_durasi, pf.total_durasi_kerja, pf.keterangan, pf.df_jam_masuk, pf.df_jam_flexi, pf.df_jam_pulang, pf.df_durasi_absen, pf.df_durasi_istirahat, pf.df_durasi_kerja, pf.df_durasi_flexi,
						SUBSTR(TIMEDIFF(pf.start, concat(pf.tanggal,' ',pf.df_jam_masuk)),1,8) as durasi_flexi, 
						SUBSTR(TIMEDIFF(pf.start, concat(pf.tanggal,' ',pf.df_jam_masuk)),1,8) as durasi_terlambat, 
						case when pf.stop is null then '-' else 
							case when a.pegawai_id is null then 
								SUBSTR(TIMEDIFF(concat(pf.tanggal,' ',pf.df_jam_pulang), pf.stop),1,8)
							else  
								TIMEDIFF(pf.df_durasi_kerja, pf.total_durasi_kerja)
							end
						end as durasi_mendahului, 
						case when a.tanggal is null then 0 else 1 end as flexi, 
						case when substr(pf.start,11) > pf.df_jam_flexi then 1 else 0 end as terlambat, 
						case when a.tanggal is null then 
							case when substr(pf.stop,11) < pf.df_jam_pulang then 1 else 0 end
						else 
							case when pf.total_durasi < pf.df_durasi_absen then 1 else 0 end
						end as mendahului, 
						a.keterangan as flexi_desc, 
						case when b.id is null then 0 else 1 end as laporan,
						ifnull(b.laporan, '-') as laporan_kegiatan
					FROM presensi_final pf
						left join presensi_flexi a on a.pegawai_id=pf.pegawai_id and a.tanggal=pf.tanggal
						left join presensi_laporan b on b.pegawai_id=pf.pegawai_id and b.tanggal=pf.tanggal
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." ", $arrWhere)->getResult();
		    	$q = [];
		    	foreach (list_tanggal_in_periode_bulan($periode, $order_dir) as $key => $value) if($value <= date('Y-m-d')){
		    		$array_list_pelanggaran = [];
		    		$presensi['tanggal'] = $value;
		    		$presensi['dayname'] = date('D', strtotime($value));
		    		$libur = 0;
		    		if(array_keys($this->array_libur_default(), date('D', strtotime($value)))){ 
		    			$libur = 1;
		    		}elseif (array_keys($this->array_tanggal_libur(), $value)) {
		    			$libur =  1;
		    		}
		    		if($libur==1){
		    			array_push($array_list_pelanggaran, '*');
		    		}
		    		$presensi['libur'] = $libur;
		    		$presensi['pegawai_id'] = $pegawai_id;
		    		$presensi['pegawai_id_hash'] = string_to($pegawai_id, 'encode');
					$presensi['start'] = '-';
					$presensi['start_ip'] = '-';
					$presensi['start_latlong'] = '-';
					$presensi['start_user'] = '-';
					$presensi['start_catatan'] = '-';
					$presensi['start_log'] = '-';
					$presensi['stop'] = '-';
					$presensi['stop_ip'] = '-';
					$presensi['stop_latlong'] = '-';
					$presensi['stop_user'] = '-';
					$presensi['stop_catatan'] = '-';
					$presensi['stop_log'] = '-';
					$presensi['status'] = '-';
					$presensi['pjk_id'] = '-';
					$presensi['kode_hari'] = '-';
					$presensi['total_durasi'] = '-';
					$presensi['total_durasi_kerja'] = '-';
					$presensi['terlambat'] = 0;
					$presensi['mendahului'] = 0;
					$presensi['durasi_flexi'] = '-';
					$presensi['durasi_terlambat'] = '-';
					$presensi['durasi_mendahului'] = '-';
					$presensi['keterangan'] = '-';
					$presensi['df_jam_masuk'] = '-';
					$presensi['df_jam_flexi'] = '-';
					$presensi['df_jam_pulang'] = '-';
					$presensi['df_durasi_absen'] = '-';
					$presensi['df_durasi_istirahat'] = '-';
					$presensi['df_durasi_kerja'] = '-';
					$presensi['df_durasi_flexi'] = '-';
					$presensi['flexi'] = '-';
					$presensi['flexi_desc'] = '-';
					$presensi['laporan'] = 0;
					$presensi['laporan_kegiatan'] = '-';
					foreach ($q_rs as $key2) if($key2->tanggal==$value){
						$presensi['start'] = $key2->start;
						$presensi['start_ip'] = $key2->start_ip;
						$presensi['start_latlong'] = $key2->start_latlong;
						$presensi['start_user'] = $key2->start_user;
						$presensi['start_catatan'] = $key2->start_catatan;
						if($key2->start_log==null)
						{
							$start_latlong = explode(',', $key2->start_latlong);
							$presensi['start_log'] = text_log_area(check_location_in_radius_absen($start_latlong[0], $start_latlong[1])['status']);
						}else{
							$presensi['start_log'] = $key2->start_log;
						}
						$presensi['stop'] = $key2->stop;
						$presensi['stop_ip'] = $key2->stop_ip;
						$presensi['stop_latlong'] = $key2->stop_latlong;
						$presensi['stop_user'] = $key2->stop_user;
						$presensi['stop_catatan'] = $key2->stop_catatan;
						if($key2->stop==null){}else{
							if($key2->stop_log==null)
							{
								$stop_latlong = explode(',', $key2->stop_latlong);
								$presensi['stop_log'] = text_log_area(check_location_in_radius_absen($stop_latlong[0], $stop_latlong[1])['status']);
							}else{
								$presensi['stop_log'] = $key2->stop_log;
							}
						}
						$presensi['status'] = $key2->status;
						$presensi['pjk_id'] = $key2->pjk_id;
						$presensi['kode_hari'] = $key2->kode_hari;
						$presensi['total_durasi'] = $key2->total_durasi;
						$presensi['total_durasi_kerja'] = $key2->total_durasi_kerja;
						if($key2->flexi==1 && $key2->start > $key2->tanggal.' '.$key2->df_jam_masuk && $key2->start <= $key2->tanggal.' '.$key2->df_jam_flexi){
							$presensi['durasi_flexi'] = substr($key2->durasi_flexi,0,8);
						}
						if($key2->flexi==0 && ($key2->start > $key2->tanggal.' '.$key2->df_jam_flexi /*&& $key2->start <= $key2->tanggal.' '.$key2->df_jam_pulang*/)){
							$presensi['durasi_terlambat'] = substr($key2->durasi_terlambat,0,8);
						}
						if(($key2->flexi==1 && $key2->total_durasi < $key2->df_durasi_absen) || ($key2->flexi==0 && $key2->stop < $key2->tanggal.' '.$key2->df_jam_pulang)){
							$presensi['durasi_mendahului'] = substr($key2->durasi_mendahului,0,8);
						}
						$presensi['df_jam_masuk'] = $key2->df_jam_masuk;
						$presensi['df_jam_flexi'] = $key2->df_jam_flexi;
						$presensi['df_jam_pulang'] = $key2->df_jam_pulang;
						$presensi['df_durasi_absen'] = $key2->df_durasi_absen;
						$presensi['df_durasi_istirahat'] = $key2->df_durasi_istirahat;
						$presensi['df_durasi_kerja'] = $key2->df_durasi_kerja;
						$presensi['df_durasi_flexi'] = $key2->df_durasi_flexi;
						$presensi['flexi'] = $key2->flexi;
						$presensi['terlambat'] = $key2->terlambat;
						$presensi['mendahului'] = $key2->mendahului;
						$presensi['flexi_desc'] = $key2->flexi_desc;
						$presensi['laporan'] = $key2->laporan;
						$presensi['laporan_kegiatan'] = $key2->laporan_kegiatan;
						if($key2->start<>'' && $key2->stop<>'')
						{
							array_push($array_list_pelanggaran, 'H');
						}
						if(($key2->start<>'' && $key2->stop=='') || ($key2->start=='' && $key2->stop<>''))
						{
							array_push($array_list_pelanggaran, '?');
						}
						if($key2->flexi_desc<>'')
						{
							array_push($array_list_pelanggaran, $key2->flexi_desc);
						}
					}
		    		$list_pelanggaran = $this->result_pelanggaran_pegawai_in_day($pegawai_id, $value);
		    		foreach ($list_pelanggaran as $k) {
		    			array_push($array_list_pelanggaran, $k->kode);
		    		}
		    		$presensi['keterangan'] = implode(',', $array_list_pelanggaran);
		    		array_push($q, $presensi);
		    	}
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(pf.tanggal)) as field FROM presensi_final pf
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function presensi_final_in_riwayat_by_pegawai_id_periode($pegawai_id, $periode, $order_dir='asc')
	{
    	$q_rs = $this->db->query("SELECT pf.tanggal, pf.pegawai_id, pf.start, pf.start_ip, pf.start_latlong, pf.start_user, pf.start_catatan, pf.start_log, pf.stop, pf.stop_ip, pf.stop_latlong, pf.stop_user, pf.stop_catatan, pf.stop_log, pf.status, pf.pjk_id, pf.kode_hari, pf.total_durasi, pf.total_durasi_kerja, pf.keterangan, pf.df_jam_masuk, pf.df_jam_flexi, pf.df_jam_pulang, pf.df_durasi_absen, pf.df_durasi_istirahat, pf.df_durasi_kerja, pf.df_durasi_flexi,
				SUBSTR(TIMEDIFF(pf.start, concat(pf.tanggal,' ',pf.df_jam_masuk)),1,8) as durasi_flexi, 
				SUBSTR(TIMEDIFF(pf.start, concat(pf.tanggal,' ',pf.df_jam_masuk)),1,8) as durasi_terlambat, 
				case when pf.stop is null then '-' else 
					case when a.pegawai_id is null then 
						SUBSTR(TIMEDIFF(concat(pf.tanggal,' ',pf.df_jam_pulang), pf.stop),1,8)
					else  
						TIMEDIFF(pf.df_durasi_kerja, pf.total_durasi_kerja)
					end
				end as durasi_mendahului, 
				case when a.tanggal is null then 0 else 1 end as flexi, 
				case when substr(pf.start,11) > pf.df_jam_flexi then 1 else 0 end as terlambat, 
				case when a.tanggal is null then 
					case when substr(pf.stop,11) < pf.df_jam_pulang then 1 else 0 end
				else 
					case when pf.total_durasi < pf.df_durasi_absen then 1 else 0 end
				end as mendahului, 
				a.keterangan as flexi_desc, 
				case when b.id is null then 0 else 1 end as laporan,
				ifnull(b.laporan, '-') as laporan_kegiatan
			FROM presensi_final pf
				left join presensi_flexi a on a.pegawai_id=pf.pegawai_id and a.tanggal=pf.tanggal
				left join presensi_laporan b on b.pegawai_id=pf.pegawai_id and b.tanggal=pf.tanggal
    		WHERE pf.pegawai_id=? and SUBSTR(pf.tanggal,1,7)=? ", [$pegawai_id, $periode])->getResult();
    	$q = [];
    	foreach (list_tanggal_in_periode_bulan($periode, $order_dir) as $key => $value){
    		$array_list_pelanggaran = [];
    		$presensi['tanggal'] = $value;
    		$presensi['dayname'] = date('D', strtotime($value));
    		$libur = 0;
    		if(array_keys($this->array_libur_default(), date('D', strtotime($value)))){ 
    			$libur = 1;
    		}elseif (array_keys($this->array_tanggal_libur(), $value)) {
    			$libur =  1;
    		}
    		if($libur==1){
    			array_push($array_list_pelanggaran, '*');
    		}
    		$presensi['libur'] = $libur;
    		$presensi['pegawai_id'] = $pegawai_id;
		    $presensi['pegawai_id_hash'] = string_to($pegawai_id, 'encode');
			$presensi['start'] = '-';
			$presensi['start_ip'] = '-';
			$presensi['start_latlong'] = '-';
			$presensi['start_user'] = '-';
			$presensi['start_catatan'] = '-';
			$presensi['start_log'] = '-';
			$presensi['stop'] = '-';
			$presensi['stop_ip'] = '-';
			$presensi['stop_latlong'] = '-';
			$presensi['stop_user'] = '-';
			$presensi['stop_catatan'] = '-';
			$presensi['stop_log'] = '-';
			$presensi['status'] = '-';
			$presensi['pjk_id'] = '-';
			$presensi['kode_hari'] = '-';
			$presensi['total_durasi'] = '-';
			$presensi['total_durasi_kerja'] = '-';
			$presensi['terlambat'] = 0;
			$presensi['mendahului'] = 0;
			$presensi['durasi_flexi'] = '-';
			$presensi['durasi_terlambat'] = '-';
			$presensi['durasi_mendahului'] = '-';
			$presensi['keterangan'] = '-';
			$presensi['df_jam_masuk'] = '-';
			$presensi['df_jam_flexi'] = '-';
			$presensi['df_jam_pulang'] = '-';
			$presensi['df_durasi_absen'] = '-';
			$presensi['df_durasi_istirahat'] = '-';
			$presensi['df_durasi_kerja'] = '-';
			$presensi['df_durasi_flexi'] = '-';
			$presensi['flexi'] = '-';
			$presensi['flexi_desc'] = '-';
			$presensi['laporan'] = '-';
			$presensi['laporan_kegiatan'] = '-';
			foreach ($q_rs as $key2) if($key2->tanggal==$value){
				$presensi['start'] = $key2->start;
				$presensi['start_ip'] = $key2->start_ip;
				$presensi['start_latlong'] = $key2->start_latlong;
				$presensi['start_user'] = $key2->start_user;
				$presensi['start_catatan'] = $key2->start_catatan;
				if($key2->start_log==null)
				{
					$start_latlong = explode(',', $key2->start_latlong);
					$presensi['start_log'] = text_log_area(check_location_in_radius_absen($start_latlong[0], $start_latlong[1])['status']);
				}else{
					$presensi['start_log'] = $key2->start_log;
				}
				$presensi['stop'] = $key2->stop;
				$presensi['stop_ip'] = $key2->stop_ip;
				$presensi['stop_latlong'] = $key2->stop_latlong;
				$presensi['stop_user'] = $key2->stop_user;
				$presensi['stop_catatan'] = $key2->stop_catatan;
				if($key2->stop==null){}else{
					if($key2->stop_log==null)
					{
						$stop_latlong = explode(',', $key2->stop_latlong);
						$presensi['stop_log'] = text_log_area(check_location_in_radius_absen($stop_latlong[0], $stop_latlong[1])['status']);
					}else{
						$presensi['stop_log'] = $key2->stop_log;
					}
				}
				$presensi['status'] = $key2->status;
				$presensi['pjk_id'] = $key2->pjk_id;
				$presensi['kode_hari'] = $key2->kode_hari;
				$presensi['total_durasi'] = $key2->total_durasi;
				$presensi['total_durasi_kerja'] = $key2->total_durasi_kerja;
				if($key2->flexi==1 && $key2->start > $key2->tanggal.' '.$key2->df_jam_masuk && $key2->start <= $key2->tanggal.' '.$key2->df_jam_flexi){
					$presensi['durasi_flexi'] = substr($key2->durasi_flexi,0,8);
				}
				if($key2->flexi==0 && ($key2->start > $key2->tanggal.' '.$key2->df_jam_flexi /*&& $key2->start <= $key2->tanggal.' '.$key2->df_jam_pulang*/)){
					$presensi['durasi_terlambat'] = substr($key2->durasi_terlambat,0,8);
				}
				if(($key2->flexi==1 && $key2->total_durasi < $key2->df_durasi_absen) || ($key2->flexi==0 && $key2->stop < $key2->tanggal.' '.$key2->df_jam_pulang)){
					$presensi['durasi_mendahului'] = substr($key2->durasi_mendahului,0,8);
				}
				$presensi['df_jam_masuk'] = $key2->df_jam_masuk;
				$presensi['df_jam_flexi'] = $key2->df_jam_flexi;
				$presensi['df_jam_pulang'] = $key2->df_jam_pulang;
				$presensi['df_durasi_absen'] = $key2->df_durasi_absen;
				$presensi['df_durasi_istirahat'] = $key2->df_durasi_istirahat;
				$presensi['df_durasi_kerja'] = $key2->df_durasi_kerja;
				$presensi['df_durasi_flexi'] = $key2->df_durasi_flexi;
				$presensi['flexi'] = $key2->flexi;
				$presensi['terlambat'] = $key2->terlambat;
				$presensi['mendahului'] = $key2->mendahului;
				$presensi['flexi_desc'] = $key2->flexi_desc;
				$presensi['laporan'] = $key2->laporan;
				$presensi['laporan_kegiatan'] = $key2->laporan_kegiatan;
				if($key2->start<>'' && $key2->stop<>'')
				{
					array_push($array_list_pelanggaran, 'H');
				}
				if(($key2->start<>'' && $key2->stop=='') || ($key2->start=='' && $key2->stop<>''))
				{
					array_push($array_list_pelanggaran, '?');
				}
				if($key2->flexi_desc<>'')
				{
					array_push($array_list_pelanggaran, $key2->flexi_desc);
				}
			}
    		$list_pelanggaran = $this->result_pelanggaran_pegawai_in_day($pegawai_id, $value);
    		foreach ($list_pelanggaran as $k) {
    			array_push($array_list_pelanggaran, $k->kode);
    		}
    		$presensi['keterangan'] = implode(',', $array_list_pelanggaran);
    		array_push($q, $presensi);
    	}
	    return $q;
	}


	function presensi_final_in_harian($data_=false)
	{
		$periode = $this->request->getPost('periode');
		$unit_kerja = $this->request->getPost('unit_kerja');
		$jabatan = $this->request->getPost('jabatan');
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = $this->filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'harian'));
		}
		$search_value = $this->request->getPost('search');
		$arrWhere = [];
		$where = " pf.tanggal=? ";
		array_push($arrWhere, $periode);
		if(!empty($unit_kerja))
		{
			$where .= " and pf.unit_kerja_id in ? ";
			array_push($arrWhere, $unit_kerja);
		}
		if(!empty($jabatan))
		{
			$where .= " and pf.jabatan_id in ? ";
			array_push($arrWhere, $jabatan);
		}
		if($search_value<>''){
			$where .= " AND (p.nama like ? or p.nik like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q_rs = $this->db->query("SELECT pf.pegawai_id, pf.jabatan_id, mj.jabatan_name, pf.unit_kerja_id, muk.unit_kerja_name, muk.unit_kerja_name_alt, p.nama, p.nip, pf.tanggal, pf.start, pf.start_ip, pf.start_latlong, pf.start_user, pf.start_catatan, pf.start_log, pf.stop, pf.stop_ip, pf.stop_latlong, pf.stop_user, pf.stop_catatan, pf.stop_log, pf.status, pf.pjk_id, pf.kode_hari, pf.total_durasi, pf.total_durasi_kerja, pf.keterangan, pf.df_jam_masuk, pf.df_jam_flexi, pf.df_jam_pulang, pf.df_durasi_absen, pf.df_durasi_istirahat, pf.df_durasi_kerja, pf.df_durasi_flexi,
						SUBSTR(TIMEDIFF(pf.start, concat(pf.tanggal,' ',pf.df_jam_masuk)),1,8) as durasi_flexi, 
						SUBSTR(TIMEDIFF(pf.start, concat(pf.tanggal,' ',pf.df_jam_masuk)),1,8) as durasi_terlambat, 
						case when pf.stop is null then '-' else 
							case when a.pegawai_id is null then 
								SUBSTR(TIMEDIFF(concat(pf.tanggal,' ',pf.df_jam_pulang), pf.stop),1,8)
							else  
								TIMEDIFF(pf.df_durasi_kerja, pf.total_durasi_kerja)
							end
						end as durasi_mendahului, 
						case when a.tanggal is null then 0 else 1 end as flexi, 
						case when substr(pf.start,11) > pf.df_jam_flexi then 1 else 0 end as terlambat, 
						case when a.tanggal is null then 
							case when substr(pf.stop,11) < pf.df_jam_pulang then 1 else 0 end
						else 
							case when pf.total_durasi < pf.df_durasi_absen then 1 else 0 end
						end as mendahului, 
						a.keterangan as flexi_desc, 
						case when b.id is null then 0 else 1 end as laporan,
						ifnull(b.laporan, '-') as laporan_kegiatan
					FROM presensi_final pf
						left join presensi_flexi a on a.pegawai_id=pf.pegawai_id and a.tanggal=pf.tanggal
						left join presensi_laporan b on b.pegawai_id=pf.pegawai_id and b.tanggal=pf.tanggal
						inner join pegawai p on p.pegawai_id=pf.pegawai_id
						left join ms_jabatan mj on mj.jabatan_id=p.jabatan_id
						left join ms_unit_kerja muk on muk.unit_kerja_id=p.unit_kerja_id
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?,? ", $arrWhere)->getResult();
		    	$q = [];
		    	foreach ($q_rs as $key) {
		    		$array_list_pelanggaran = [];
					$presensi['tanggal'] = $key->tanggal;
	    			$presensi['pegawai_id'] = $key->pegawai_id;
		    		$presensi['pegawai_id_hash'] = string_to($key->pegawai_id, 'encode');
	    			$presensi['jabatan_id'] = $key->jabatan_id;
	    			$presensi['jabatan_name'] = $key->jabatan_name;
	    			$presensi['unit_kerja_id'] = $key->unit_kerja_id;
	    			$presensi['unit_kerja_name'] = $key->unit_kerja_name;
	    			$presensi['unit_kerja_name_alt'] = $key->unit_kerja_name_alt;
	    			$presensi['nama'] = $key->nama;
	    			$presensi['nip'] = $key->nip;
					$presensi['start'] = $key->start;
					$presensi['start_ip'] = $key->start_ip;
					$presensi['start_latlong'] = $key->start_latlong;
					$presensi['start_user'] = $key->start_user;
					$presensi['start_catatan'] = $key->start_catatan;
					if($key->start_log==null)
					{
						$start_latlong = explode(',', $key->start_latlong);
						$presensi['start_log'] = text_log_area(check_location_in_radius_absen($start_latlong[0], $start_latlong[1])['status']);
					}else{
						$presensi['start_log'] = $key->start_log;
					}
					$presensi['stop'] = $key->stop;
					$presensi['stop_ip'] = $key->stop_ip;
					$presensi['stop_latlong'] = $key->stop_latlong;
					$presensi['stop_user'] = $key->stop_user;
					$presensi['stop_catatan'] = $key->stop_catatan;
					if($key->stop==null){}else{
						if($key->stop_log==null)
						{
							$stop_latlong = explode(',', $key->stop_latlong);
							$presensi['stop_log'] = text_log_area(check_location_in_radius_absen($stop_latlong[0], $stop_latlong[1])['status']);
						}else{
							$presensi['stop_log'] = $key->stop_log;
						}
					}
					$presensi['status'] = $key->status;
					$presensi['pjk_id'] = $key->pjk_id;
					$presensi['kode_hari'] = $key->kode_hari;
					$presensi['total_durasi'] = $key->total_durasi;
					$presensi['total_durasi_kerja'] = $key->total_durasi_kerja;
					if($key->flexi==1 && $key->start > $key->tanggal.' '.$key->df_jam_masuk && $key->start <= $key->tanggal.' '.$key->df_jam_flexi){
						$presensi['durasi_flexi'] = substr($key->durasi_flexi,0,8);
					}else{
						$presensi['durasi_flexi'] = '-';
					}
					if($key->flexi==0 && ($key->start > $key->tanggal.' '.$key->df_jam_flexi /*&& $key->start <= $key->tanggal.' '.$key->df_jam_pulang*/)){
						$presensi['durasi_terlambat'] = substr($key->durasi_terlambat,0,8);
					}else{
						$presensi['durasi_terlambat'] = '-';
					}
					if(($key->flexi==1 && $key->total_durasi < $key->df_durasi_absen) || ($key->flexi==0 && $key->stop < $key->tanggal.' '.$key->df_jam_pulang)){
						$presensi['durasi_mendahului'] = substr($key->durasi_mendahului,0,8);
					}else{
						$presensi['durasi_mendahului'] = '-';
					}
					$presensi['df_jam_masuk'] = $key->df_jam_masuk;
					$presensi['df_jam_flexi'] = $key->df_jam_flexi;
					$presensi['df_jam_pulang'] = $key->df_jam_pulang;
					$presensi['df_durasi_absen'] = $key->df_durasi_absen;
					$presensi['df_durasi_istirahat'] = $key->df_durasi_istirahat;
					$presensi['df_durasi_kerja'] = $key->df_durasi_kerja;
					$presensi['df_durasi_flexi'] = $key->df_durasi_flexi;
					$presensi['flexi'] = $key->flexi;
					$presensi['terlambat'] = $key->terlambat;
					$presensi['mendahului'] = $key->mendahului;
					$presensi['flexi_desc'] = $key->flexi_desc;
					$presensi['laporan'] = $key->laporan;
					$presensi['laporan_kegiatan'] = $key->laporan_kegiatan;
					if($key->start<>'' && $key->stop<>'')
					{
						array_push($array_list_pelanggaran, 'H');
					}
					if(($key->start<>'' && $key->stop=='') || ($key->start=='' && $key->stop<>''))
					{
						array_push($array_list_pelanggaran, '?');
					}
					if($key->flexi_desc<>'')
					{
						array_push($array_list_pelanggaran, $key->flexi_desc);
					}
		    		$list_pelanggaran = $this->result_pelanggaran_pegawai_in_day($key->pegawai_id, $key->tanggal);
		    		foreach ($list_pelanggaran as $k) {
		    			array_push($array_list_pelanggaran, $k->kode);
		    		}
		    		$presensi['keterangan'] = implode(',', $array_list_pelanggaran);
					array_push($q, $presensi);
		    	}
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(pf.pegawai_id)) as field FROM presensi_final pf
						inner join pegawai p on p.pegawai_id=pf.pegawai_id
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function presensi_final_in_harian_unduh($tanggal, $unit_kerja='', $jabatan='', $search_value='')
	{
		$arrWhere = [];
		$where = " pf.tanggal=? ";
		array_push($arrWhere, $tanggal);
		if($unit_kerja<>'')
		{
			$where .= " and pf.unit_kerja_id in ? ";
			array_push($arrWhere, explode(',', $unit_kerja));
		}
		if($jabatan<>'')
		{
			$where .= " and pf.jabatan_id in ? ";
			array_push($arrWhere, explode(',', $jabatan));
		}
		if($search_value<>''){
			$where .= " AND (p.nama like ? or p.nik like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
    	$q_rs = $this->db->query("SELECT pf.pegawai_id, pf.jabatan_id, mj.jabatan_name, pf.unit_kerja_id, muk.unit_kerja_name, muk.unit_kerja_name_alt, p.nama, p.nip, pf.tanggal, pf.start, pf.start_ip, pf.start_latlong, pf.start_user, pf.start_catatan, pf.start_log, pf.stop, pf.stop_ip, pf.stop_latlong, pf.stop_user, pf.stop_catatan, pf.stop_log, pf.status, pf.pjk_id, pf.kode_hari, pf.total_durasi, pf.total_durasi_kerja, pf.keterangan, pf.df_jam_masuk, pf.df_jam_flexi, pf.df_jam_pulang, pf.df_durasi_absen, pf.df_durasi_istirahat, pf.df_durasi_kerja, pf.df_durasi_flexi,
				SUBSTR(TIMEDIFF(pf.start, concat(pf.tanggal,' ',pf.df_jam_masuk)),1,8) as durasi_flexi, 
				SUBSTR(TIMEDIFF(pf.start, concat(pf.tanggal,' ',pf.df_jam_masuk)),1,8) as durasi_terlambat, 
				case when pf.stop is null then '-' else 
					case when a.pegawai_id is null then 
						SUBSTR(TIMEDIFF(concat(pf.tanggal,' ',pf.df_jam_pulang), pf.stop),1,8)
					else  
						TIMEDIFF(pf.df_durasi_kerja, pf.total_durasi_kerja)
					end
				end as durasi_mendahului, 
				case when a.tanggal is null then 0 else 1 end as flexi, 
				case when substr(pf.start,11) > pf.df_jam_flexi then 1 else 0 end as terlambat, 
				case when a.tanggal is null then 
					case when substr(pf.stop,11) < pf.df_jam_pulang then 1 else 0 end
				else 
					case when pf.total_durasi < pf.df_durasi_absen then 1 else 0 end
				end as mendahului, 
				a.keterangan as flexi_desc, 
				case when b.id is null then 0 else 1 end as laporan,
				ifnull(b.laporan, '-') as laporan_kegiatan
			FROM presensi_final pf
				left join presensi_flexi a on a.pegawai_id=pf.pegawai_id and a.tanggal=pf.tanggal
				left join presensi_laporan b on b.pegawai_id=pf.pegawai_id and b.tanggal=pf.tanggal
				inner join pegawai p on p.pegawai_id=pf.pegawai_id
				left join ms_jabatan mj on mj.jabatan_id=p.jabatan_id
				left join ms_unit_kerja muk on muk.unit_kerja_id=p.unit_kerja_id
    		WHERE ".$where, $arrWhere)->getResult();
    	$q = [];
    	foreach ($q_rs as $key) {
    		$array_list_pelanggaran = [];
			$presensi['tanggal'] = $key->tanggal;
			$presensi['pegawai_id'] = $key->pegawai_id;
    		$presensi['pegawai_id_hash'] = string_to($key->pegawai_id, 'encode');
			$presensi['jabatan_id'] = $key->jabatan_id;
			$presensi['jabatan_name'] = $key->jabatan_name;
			$presensi['unit_kerja_id'] = $key->unit_kerja_id;
			$presensi['unit_kerja_name'] = $key->unit_kerja_name;
			$presensi['unit_kerja_name_alt'] = $key->unit_kerja_name_alt;
			$presensi['nama'] = $key->nama;
			$presensi['nip'] = $key->nip;
			$presensi['start'] = $key->start;
			$presensi['start_ip'] = $key->start_ip;
			$presensi['start_latlong'] = $key->start_latlong;
			$presensi['start_user'] = $key->start_user;
			$presensi['start_catatan'] = $key->start_catatan;
			if($key->start_log==null)
			{
				$start_latlong = explode(',', $key->start_latlong);
				$presensi['start_log'] = text_log_area(check_location_in_radius_absen($start_latlong[0], $start_latlong[1])['status']);
			}else{
				$presensi['start_log'] = $key->start_log;
			}
			$presensi['stop'] = $key->stop;
			$presensi['stop_ip'] = $key->stop_ip;
			$presensi['stop_latlong'] = $key->stop_latlong;
			$presensi['stop_user'] = $key->stop_user;
			$presensi['stop_catatan'] = $key->stop_catatan;
			if($key->stop==null){
				$presensi['stop_log'] = '-';
			}else{
				if($key->stop_log==null)
				{
					$stop_latlong = explode(',', $key->stop_latlong);
					$presensi['stop_log'] = text_log_area(check_location_in_radius_absen($stop_latlong[0], $stop_latlong[1])['status']);
				}else{
					$presensi['stop_log'] = $key->stop_log;
				}
			}
			$presensi['status'] = $key->status;
			$presensi['pjk_id'] = $key->pjk_id;
			$presensi['kode_hari'] = $key->kode_hari;
			$presensi['total_durasi'] = $key->total_durasi;
			$presensi['total_durasi_kerja'] = $key->total_durasi_kerja;
			if($key->flexi==1 && $key->start > $key->tanggal.' '.$key->df_jam_masuk && $key->start <= $key->tanggal.' '.$key->df_jam_flexi){
				$presensi['durasi_flexi'] = substr($key->durasi_flexi,0,8);
			}else{
				$presensi['durasi_flexi'] = '-';
			}
			if($key->flexi==0 && ($key->start > $key->tanggal.' '.$key->df_jam_flexi /*&& $key->start <= $key->tanggal.' '.$key->df_jam_pulang*/)){
				$presensi['durasi_terlambat'] = substr($key->durasi_terlambat,0,8);
			}else{
				$presensi['durasi_terlambat'] = '-';
			}
			if(($key->flexi==1 && $key->total_durasi < $key->df_durasi_absen) || ($key->flexi==0 && $key->stop < $key->tanggal.' '.$key->df_jam_pulang)){
				$presensi['durasi_mendahului'] = substr($key->durasi_mendahului,0,8);
			}else{
				$presensi['durasi_mendahului'] = '-';
			}
			$presensi['df_jam_masuk'] = $key->df_jam_masuk;
			$presensi['df_jam_flexi'] = $key->df_jam_flexi;
			$presensi['df_jam_pulang'] = $key->df_jam_pulang;
			$presensi['df_durasi_absen'] = $key->df_durasi_absen;
			$presensi['df_durasi_istirahat'] = $key->df_durasi_istirahat;
			$presensi['df_durasi_kerja'] = $key->df_durasi_kerja;
			$presensi['df_durasi_flexi'] = $key->df_durasi_flexi;
			$presensi['flexi'] = $key->flexi;
			$presensi['terlambat'] = $key->terlambat;
			$presensi['mendahului'] = $key->mendahului;
			$presensi['flexi_desc'] = $key->flexi_desc;
			$presensi['laporan'] = $key->laporan;
			$presensi['laporan_kegiatan'] = $key->laporan_kegiatan;
			if($key->start<>'' && $key->stop<>'')
			{
				array_push($array_list_pelanggaran, 'H');
			}
			if(($key->start<>'' && $key->stop=='') || ($key->start=='' && $key->stop<>''))
			{
				array_push($array_list_pelanggaran, '?');
			}
			if($key->flexi_desc<>'')
			{
				array_push($array_list_pelanggaran, $key->flexi_desc);
			}
    		$list_pelanggaran = $this->result_pelanggaran_pegawai_in_day($key->pegawai_id, $key->tanggal);
    		foreach ($list_pelanggaran as $k) {
    			array_push($array_list_pelanggaran, $k->kode);
    		}
    		$presensi['keterangan'] = implode(',', $array_list_pelanggaran);
			array_push($q, $presensi);
    	}
	    return $q;
	}


	function presensi_final_in_bulanan($data_=false)
	{
		$periode = $this->request->getPost('periode');
		$unit_kerja = $this->request->getPost('unit_kerja');
		$jabatan = $this->request->getPost('jabatan');
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = $this->filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'harian'));
		}
		$search_value = $this->request->getPost('search');
		$arrWhere = [];
		$where = " substr(pf.tanggal, 1, 7)=? ";
		array_push($arrWhere, $periode);
		if(!empty($unit_kerja))
		{
			$where .= " and pf.unit_kerja_id in ? ";
			array_push($arrWhere, $unit_kerja);
		}
		if(!empty($jabatan))
		{
			$where .= " and pf.jabatan_id in ? ";
			array_push($arrWhere, $jabatan);
		}
		if($search_value<>''){
			$where .= " AND (p.nama like ? or p.nik like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		switch ($data_) {
			case true:
				$hari_kerja = $this->jumlah_hari_kerja_dalam_bulan($periode);
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q_rs = $this->db->query("SELECT 
						pf.tanggal,
		    			pf.pegawai_id,
		    			pf.jabatan_id,
		    			mj.jabatan_name,
		    			pf.unit_kerja_id,
		    			muk.unit_kerja_name,
		    			muk.unit_kerja_name_alt,
		    			p.nama, p.nip
						, sum(
							case when pf.tanggal <= curdate() then 1 else 0 end
							) as hari_berjalan
						, sum(
							case when pf.start is not null and (pf.stop is not null and pf.stop > concat(pf.tanggal, ' ', pf.df_jam_masuk)) then 1 else 0 end
							) as hadir
						, sum(
							case when pf.start > concat(pf.tanggal, ' ', pf.df_jam_flexi) then 1 else 0 end
							) as terlambat
						, sum(
							case when a.pegawai_id is null then 
								case when pf.stop < concat(pf.tanggal, ' ', pf.df_jam_pulang) then 1 else 0 end
							else
								case when pf.total_durasi_kerja < pf.df_durasi_kerja then 1 else 0 end
							end
							) as mendahului
					FROM presensi_final pf
						left join presensi_flexi a on a.pegawai_id=pf.pegawai_id and a.tanggal=pf.tanggal
						inner join pegawai p on p.pegawai_id=pf.pegawai_id
						left join ms_jabatan mj on mj.jabatan_id=p.jabatan_id
						left join ms_unit_kerja muk on muk.unit_kerja_id=p.unit_kerja_id
		    		WHERE ".$where." group by pf.pegawai_id ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?,? ", $arrWhere)->getResult();
		    	$q = [];
		    	foreach ($q_rs as $key) {
					$cuti = 0;
					$dinas = 0;
					$tidak_hadir = ($hari_kerja-($cuti+$dinas+$key->hadir));
					$keterangan = '-';
		    		$presensi['hash'] = string_to($key->pegawai_id, 'encode');
		    		$presensi['hari_kerja'] = $hari_kerja;
		    		$presensi['hari_berjalan'] = $key->hari_berjalan;
					$presensi['pegawai_id'] = $key->pegawai_id;
					$presensi['pegawai_id_hash'] = string_to($key->pegawai_id, 'encode');
					$presensi['nip'] = $key->nip;
					$presensi['nama'] = $key->nama;
					$presensi['unit_kerja_id'] = $key->unit_kerja_id;
					$presensi['unit_kerja_name'] = $key->unit_kerja_name;
					$presensi['unit_kerja_name_alt'] = $key->unit_kerja_name_alt;
					$presensi['jabatan_id'] = $key->jabatan_id;
					$presensi['jabatan_name'] = $key->jabatan_name;
					$presensi['hadir'] = $key->hadir;
					$presensi['terlambat'] = $key->terlambat;
					$presensi['mendahului'] = $key->mendahului;
					$presensi['cuti'] = $cuti;
					$presensi['dinas'] = $dinas;
					$presensi['tidak_hadir'] = $tidak_hadir;
					$presensi['keterangan'] = $keterangan;
					$presensi['potongan'] = $this->sum_potongan_pelanggaran_by($key->pegawai_id, substr($key->tanggal, 0,7));
		    		array_push($q, $presensi);
		    	}
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(pf.pegawai_id)) as field FROM presensi_final pf
						inner join pegawai p on p.pegawai_id=pf.pegawai_id
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function presensi_final_in_bulanan_unduh($periode, $unit_kerja='', $jabatan='', $search_value='')
	{
		$arrWhere = [];
		$where = " substr(pf.tanggal, 1, 7)=? ";
		array_push($arrWhere, $periode);
		if($unit_kerja<>'')
		{
			$where .= " and pf.unit_kerja_id in ? ";
			array_push($arrWhere, explode(',', $unit_kerja));
		}
		if($jabatan<>'')
		{
			$where .= " and pf.jabatan_id in ? ";
			array_push($arrWhere, explode(',', $jabatan));
		}
		if($search_value<>''){
			$where .= " AND (p.nama like ? or p.nik like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		$hari_kerja = $this->jumlah_hari_kerja_dalam_bulan($periode);
    	$q_rs = $this->db->query("SELECT 
				pf.tanggal,
    			pf.pegawai_id,
    			pf.jabatan_id,
    			mj.jabatan_name,
    			pf.unit_kerja_id,
    			muk.unit_kerja_name,
    			muk.unit_kerja_name_alt,
    			p.nama, p.nip
				, sum(
					case when pf.tanggal <= curdate() then 1 else 0 end
					) as hari_berjalan
				, sum(
					case when pf.start is not null and (pf.stop is not null and pf.stop > concat(pf.tanggal, ' ', pf.df_jam_masuk)) then 1 else 0 end
					) as hadir
				, sum(
					case when pf.start > concat(pf.tanggal, ' ', pf.df_jam_flexi) then 1 else 0 end
					) as terlambat
				, sum(
					case when a.pegawai_id is null then 
						case when pf.stop < concat(pf.tanggal, ' ', pf.df_jam_pulang) then 1 else 0 end
					else
						case when pf.total_durasi_kerja < pf.df_durasi_kerja then 1 else 0 end
					end
					) as mendahului
			FROM presensi_final pf
				left join presensi_flexi a on a.pegawai_id=pf.pegawai_id and a.tanggal=pf.tanggal
				inner join pegawai p on p.pegawai_id=pf.pegawai_id
				left join ms_jabatan mj on mj.jabatan_id=p.jabatan_id
				left join ms_unit_kerja muk on muk.unit_kerja_id=p.unit_kerja_id
    		WHERE ".$where." group by pf.pegawai_id ", $arrWhere)->getResult();
    	$q = [];
    	foreach ($q_rs as $key) {
			$cuti = 0;
			$dinas = 0;
			$tidak_hadir = ($hari_kerja-($cuti+$dinas+$key->hadir));
			$keterangan = '-';
    		$presensi['hash'] = string_to($key->pegawai_id, 'encode');
    		$presensi['hari_kerja'] = $hari_kerja;
    		$presensi['hari_berjalan'] = $key->hari_berjalan;
			$presensi['pegawai_id'] = $key->pegawai_id;
			$presensi['pegawai_id_hash'] = string_to($key->pegawai_id, 'encode');
			$presensi['nip'] = $key->nip;
			$presensi['nama'] = $key->nama;
			$presensi['unit_kerja_id'] = $key->unit_kerja_id;
			$presensi['unit_kerja_name'] = $key->unit_kerja_name;
			$presensi['unit_kerja_name_alt'] = $key->unit_kerja_name_alt;
			$presensi['jabatan_id'] = $key->jabatan_id;
			$presensi['jabatan_name'] = $key->jabatan_name;
			$presensi['hadir'] = $key->hadir;
			$presensi['terlambat'] = $key->terlambat;
			$presensi['mendahului'] = $key->mendahului;
			$presensi['cuti'] = $cuti;
			$presensi['dinas'] = $dinas;
			$presensi['tidak_hadir'] = $tidak_hadir;
			$presensi['keterangan'] = $keterangan;
			$presensi['potongan'] = $this->sum_potongan_pelanggaran_by($key->pegawai_id, substr($key->tanggal, 0,7));
    		array_push($q, $presensi);
    	}
	    return $q;
	}


	function presensi_final_get($pegawai_id, $tanggal)
	{
		return $this->db->query("SELECT pf.tanggal, pf.pegawai_id, pf.jabatan_id, pf.unit_kerja_id, pf.start, pf.start_ip, pf.start_latlong, pf.start_user, pf.start_catatan, pf.start_log, pf.start_cam, pf.stop, pf.stop_ip, pf.stop_latlong, pf.stop_user, pf.stop_catatan, pf.stop_log, pf.stop_cam, pf.status, pf.pjk_id, pf.kode_hari, pf.total_durasi, pf.total_durasi_kerja, pf.keterangan, pf.df_jam_masuk, pf.df_jam_flexi, pf.df_jam_pulang, pf.df_durasi_absen, pf.df_durasi_istirahat, pf.df_durasi_kerja, pf.df_durasi_flexi, 
				case when substr(pf.start,11) > pf.df_jam_masuk and substr(pf.start,11) <= pf.df_jam_flexi then SUBSTR(TIMEDIFF(pf.start, concat(pf.tanggal,' ',pf.df_jam_masuk)),1,8) else '-' end as durasi_flexi, 
				case when substr(pf.start,11) > pf.df_jam_masuk and substr(pf.start,11) <= pf.df_jam_flexi then TIME_TO_SEC(SUBSTR(TIMEDIFF(pf.start, concat(pf.tanggal,' ',pf.df_jam_masuk)),1,8)) / 60 else '-' end as durasi_flexi_menit, 
				case when substr(pf.start,11) > pf.df_jam_flexi then SUBSTR(TIMEDIFF(pf.start, concat(pf.tanggal,' ',pf.df_jam_masuk)),1,8) else '-' end as durasi_terlambat, 
				case when substr(pf.start,11) > pf.df_jam_flexi then TIME_TO_SEC(SUBSTR(TIMEDIFF(pf.start, concat(pf.tanggal,' ',pf.df_jam_masuk)),1,8)) / 60 else '-' end as durasi_terlambat_menit, 
				case when pf.stop is null or (substr(pf.stop,11) > pf.df_jam_pulang) then '-' else 
					case when a.pegawai_id is null then 
						SUBSTR(TIMEDIFF(concat(pf.tanggal,' ',pf.df_jam_pulang), pf.stop),1,8)
					else  
						TIMEDIFF(pf.df_durasi_kerja, pf.total_durasi_kerja)
					end
				end as durasi_mendahului, 
				/*SUBSTR(TIMEDIFF(pf.start, concat(pf.tanggal,' ',pf.df_jam_masuk)),1,8) as durasi_flexi, 
				TIME_TO_SEC(SUBSTR(TIMEDIFF(pf.start, concat(pf.tanggal,' ',pf.df_jam_masuk)),1,8)) / 60 as durasi_flexi_menit, 
				SUBSTR(TIMEDIFF(pf.start, concat(pf.tanggal,' ',pf.df_jam_masuk)),1,8) as durasi_terlambat, 
				TIME_TO_SEC(SUBSTR(TIMEDIFF(pf.start, concat(pf.tanggal,' ',pf.df_jam_masuk)),1,8)) / 60 as durasi_terlambat_menit, 
				case when pf.stop is null or (substr(pf.stop,11) > pf.df_jam_pulang) then '-' else 
					case when a.pegawai_id is null then 
						SUBSTR(TIMEDIFF(concat(pf.tanggal,' ',pf.df_jam_pulang), pf.stop),1,8)
					else  
						TIMEDIFF(pf.df_durasi_kerja, pf.total_durasi_kerja)
					end
				end as durasi_mendahului, */
				case when a.tanggal is null then 0 else 1 end as flexi, 
				case when substr(pf.start,11) > pf.df_jam_flexi then 1 else 0 end as terlambat, 
				case when a.tanggal is null then 
					case when substr(pf.stop,11) < pf.df_jam_pulang then 1 else 0 end
				else 
					case when pf.total_durasi < pf.df_durasi_absen then 1 else 0 end
				end as mendahului, 
				a.keterangan as flexi_desc, 
				case when b.id is null then 0 else 1 end as laporan,
				ifnull(b.laporan, '-') as laporan_kegiatan
				, c.nip, c.nik, c.nama, d.jabatan_name, e.unit_kerja_name, e.unit_kerja_name_alt
			FROM presensi_final pf
				left join presensi_flexi a on a.pegawai_id=pf.pegawai_id and a.tanggal=pf.tanggal
				left join presensi_laporan b on b.pegawai_id=pf.pegawai_id and b.tanggal=pf.tanggal
				left join pegawai c on pf.pegawai_id=c.pegawai_id
				left join ms_jabatan d on pf.jabatan_id=d.jabatan_id
				left join ms_unit_kerja e on pf.unit_kerja_id=e.unit_kerja_id
			WHERE pf.pegawai_id=? and pf.tanggal=? ", 
			[$pegawai_id, $tanggal])->getRow();
	}

	function presensi_final_check_in_start($pegawai_id, $date_now)
	{
		return $this->db->query("SELECT *, TIME_TO_SEC(TIMEDIFF(NOW(), `start`)) as durasi_in_second FROM `presensi` WHERE pegawai_id=? and tanggal=? and status=? order by id desc limit 1 ", [$pegawai_id, $date_now, 1])->getRow();
	}

	function presensi_final_check_in_stop($pegawai_id, $id)
	{
		return $this->db->query("SELECT *, TIME_TO_SEC(TIMEDIFF(NOW(), `start`)) as durasi_in_second FROM `presensi` WHERE pegawai_id=? and id=? order by id desc limit 1 ", [$pegawai_id, $id])->getRow();
	}


	function presensi_final_check_now($pegawai_id, $date_now)
	{
		return $this->db->query("SELECT *
            -- , TIME_TO_SEC(TIMEDIFF(NOW(), concat(tanggal, ' ', `start`))) as durasi_in_second 
            , TIME_TO_SEC(TIMEDIFF(NOW(), `start`)) as durasi_in_second 
            FROM `presensi` WHERE pegawai_id=? and tanggal=? order by id desc limit 1 ", [$pegawai_id, $date_now])->getRow();
	}


	function presensi_final_update_durasi_in_stop($pegawai_id, $tanggal)
	{
        $this->db->query("UPDATE presensi_final df SET df.total_durasi=TIMEDIFF(df.stop, df.`start`), 
                df.total_durasi_kerja=SUBTIME(TIMEDIFF(df.stop, df.`start`), df.df_durasi_istirahat)
            WHERE df.pegawai_id=? and df.tanggal=? ", [$pegawai_id, $tanggal]);
	}


	/*
	*	INSERT FLEXI, PELANGGARAN MULAI & SELESAI
	*/
	function count_flexi_pegawai_in_periode($pegawai_id, $periode)
	{
		return $this->db->query("SELECT count(pegawai_id) as jml from presensi_flexi WHERE pegawai_id=? and substr(tanggal, 1, 7)=? ", [$pegawai_id, $periode])->getRow()->jml;
	}

	function result_flexi_pegawai_in_day($pegawai_id, $tanggal)
	{
		return $this->db->query("SELECT * from presensi_flexi WHERE pegawai_id=? and tanggal=? ", [$pegawai_id, $tanggal])->getResult();
	}

	function result_pelanggaran_pegawai_in_day($pegawai_id, $tanggal)
	{
		return $this->db->query("SELECT * from presensi_pelanggaran WHERE pegawai_id=? and tanggal=? ", [$pegawai_id, $tanggal])->getResult();
	}

	function check_insert_pelanggaran_in_start($pegawai_id, $tanggal)
	{
		$h1 = $this->presensi_final_get($pegawai_id, $tanggal);
		if(!empty($h1))
		{
			$df_tanggal = $h1->tanggal;
			$df_jam_masuk = $h1->df_jam_masuk;
			$df_jam_flexi = $h1->df_jam_flexi;
			$df_jam_pulang = $h1->df_jam_pulang;
			$df_durasi_kerja = $h1->df_durasi_kerja;
			$start = $h1->start;
			$durasi_flexi = $h1->durasi_flexi;
			$durasi_flexi_menit = $h1->durasi_flexi_menit;
			$durasi_terlambat = $h1->durasi_terlambat;
			$durasi_terlambat_menit = $h1->durasi_terlambat_menit;
			// $jml_flexi = $this->count_flexi_pegawai_in_periode($pegawai_id, substr($df_tanggal, 0, 7));
			switch (true) {
				case ((date('Y-m-d H:i:s', strtotime($start)) > date('Y-m-d H:i:s', strtotime($df_tanggal.' '.$df_jam_masuk))) && (date('Y-m-d H:i:s', strtotime($start)) <= date('Y-m-d H:i:s', strtotime($df_tanggal.' '.$df_jam_flexi))) ):
					$this->db->table('presensi_flexi')->replace([
						'pegawai_id' => $pegawai_id,
						'tanggal' => $df_tanggal,
						'durasi' => $durasi_flexi,
						'keterangan' => 'FL',//.($jml_flexi+1)
						'flag1' => $durasi_flexi_menit,
					]);
					break;
				case ((date('Y-m-d H:i:s', strtotime($start)) > date('Y-m-d H:i:s', strtotime($df_tanggal.' '.$df_jam_flexi))) /*&& (date('Y-m-d H:i:s', strtotime($start)) <= date('Y-m-d H:i:s', strtotime($df_tanggal.' '.$df_jam_pulang)))*/ ):
					$data = [
						'pegawai_id' => $pegawai_id,
						'tanggal' => $df_tanggal,
						'flag1' => substr($durasi_terlambat,0,8),
						'flag2' => hours_tofloat($durasi_terlambat),
						'flag3' => hours_tofloat($durasi_terlambat)/hours_tofloat($df_durasi_kerja),
						'flag4' => $durasi_terlambat_menit
					];
					switch (true) {
						case $durasi_terlambat_menit >= 1 && $durasi_terlambat_menit < 61:
							$data['id_reff'] = 11;
							$data['kode'] = $this->return_referensi_pelanggaran('pelanggaran', 11);
							$data['keterangan'] = $this->return_referensi_pelanggaran('pelanggaran', 11, 'ref_description');
							$data['flag5'] = $this->return_referensi_pelanggaran('pelanggaran', 11, 'ref_value');
							break;
						case $durasi_terlambat_menit >= 61 && $durasi_terlambat_menit < 91:
							$data['id_reff'] = 12;
							$data['kode'] = $this->return_referensi_pelanggaran('pelanggaran', 12);
							$data['keterangan'] = $this->return_referensi_pelanggaran('pelanggaran', 12, 'ref_description');
							$data['flag5'] = $this->return_referensi_pelanggaran('pelanggaran', 12, 'ref_value');
							break;
						case $durasi_terlambat_menit >= 91 && $durasi_terlambat_menit <= 120:
							$data['id_reff'] = 13;
							$data['kode'] = $this->return_referensi_pelanggaran('pelanggaran', 13);
							$data['keterangan'] = $this->return_referensi_pelanggaran('pelanggaran', 13, 'ref_description');
							$data['flag5'] = $this->return_referensi_pelanggaran('pelanggaran', 13, 'ref_value');
							break;
						case $durasi_terlambat_menit > 120:
						// default:
							$data['id_reff'] = 14;
							$data['kode'] = $this->return_referensi_pelanggaran('pelanggaran', 14);
							$data['keterangan'] = $this->return_referensi_pelanggaran('pelanggaran', 14, 'ref_description');
							$data['flag5'] = $this->return_referensi_pelanggaran('pelanggaran', 14, 'ref_value');
							break;
					}
					// $data['id_reff'] = 11;
					// $data['kode'] = $this->return_referensi_pelanggaran('pelanggaran', 11);
					// $data['keterangan'] = $this->return_referensi_pelanggaran('pelanggaran', 11, 'ref_description');
					// $data['flag5'] = $this->return_referensi_pelanggaran('pelanggaran', 11, 'ref_value');
					$this->db->table('presensi_pelanggaran')->replace($data);
					break;
				default:
					break;
			}
		}
	}

	function check_insert_pelanggaran_in_stop($pegawai_id, $tanggal)
	{
		$h1 = $this->presensi_final_get($pegawai_id, $tanggal);
		if(!empty($h1))
		{
			$df_tanggal = $h1->tanggal;
			$df_jam_pulang = $h1->df_jam_pulang;
			$df_durasi_kerja = $h1->df_durasi_kerja;
			$start = $h1->start;
			$stop = $h1->stop;
			$total_durasi_kerja = $h1->total_durasi_kerja;
			$flexi = $h1->flexi;
			switch (true) {
				case ($flexi==1 && (date('H:i:s', strtotime($total_durasi_kerja)) < date('H:i:s', strtotime($df_durasi_kerja))) ):
					$durasi_cepat_pulang = selisih_jam($df_durasi_kerja, $total_durasi_kerja);
					$durasi_cepat_pulang_menit = time_to_minute($durasi_cepat_pulang);
					if($durasi_cepat_pulang_menit >= 1 && $durasi_cepat_pulang_menit < 61)
					{
						$this->db->table('presensi_pelanggaran')->replace([
							'pegawai_id' => $pegawai_id,
							'tanggal' => $df_tanggal,
							'id_reff' => 11,
							'kode' => $this->return_referensi_pelanggaran('pelanggaran', 11),
							'keterangan' => $this->return_referensi_pelanggaran('pelanggaran', 11, 'ref_description'),
							'flag1' => substr($durasi_cepat_pulang,0,8),
							'flag2' => hours_tofloat($durasi_cepat_pulang),
							'flag3' => hours_tofloat($durasi_cepat_pulang)/hours_tofloat($df_durasi_kerja),
							'flag4' => $durasi_cepat_pulang_menit,
							'flag5' => $this->return_referensi_pelanggaran('pelanggaran', 11, 'ref_value')
						]);
					}
					break;
				case ($flexi==1 && (date('H:i:s', strtotime($total_durasi_kerja)) >= date('H:i:s', strtotime($df_durasi_kerja))) ):
					$this->db->table('presensi_pelanggaran')->delete(['pegawai_id'=>$pegawai_id, 'tanggal'=>$df_tanggal, 'id_reff'=>11]);
					break;
				case ($flexi==0 && (date('Y-m-d H:i:s', strtotime($stop)) < date('Y-m-d H:i:s', strtotime($df_tanggal.' '.$df_jam_pulang))) ):
					$durasi_cepat_pulang = selisih_jam($df_tanggal.' '.$df_jam_pulang, $stop);
					$durasi_cepat_pulang_menit = time_to_minute($durasi_cepat_pulang);
					$data = [
						'pegawai_id' => $pegawai_id,
						'tanggal' => $df_tanggal,
						'flag1' => substr($durasi_cepat_pulang,0,8),
						'flag2' => hours_tofloat($durasi_cepat_pulang),
						'flag3' => hours_tofloat($durasi_cepat_pulang)/hours_tofloat($df_durasi_kerja),
						'flag4' => $durasi_cepat_pulang_menit
					];
					switch (true) {
						case $durasi_cepat_pulang_menit >= 1 && $durasi_cepat_pulang_menit < 31:
							$data['id_reff'] = 21;
							$data['kode'] = $this->return_referensi_pelanggaran('pelanggaran', 21);
							$data['keterangan'] = $this->return_referensi_pelanggaran('pelanggaran', 21, 'ref_description');
							$data['flag5'] = $this->return_referensi_pelanggaran('pelanggaran', 21, 'ref_value');
							$this->db->query("DELETE FROM `presensi_pelanggaran` WHERE pegawai_id=? and tanggal=? and id_reff in ? ", [$pegawai_id, $df_tanggal, [22,23,24]]);
							break;
						case $durasi_cepat_pulang_menit >= 31 && $durasi_cepat_pulang_menit < 61:
							$data['id_reff'] = 22;
							$data['kode'] = $this->return_referensi_pelanggaran('pelanggaran', 22);
							$data['keterangan'] = $this->return_referensi_pelanggaran('pelanggaran', 22, 'ref_description');
							$data['flag5'] = $this->return_referensi_pelanggaran('pelanggaran', 22, 'ref_value');
							$this->db->query("DELETE FROM `presensi_pelanggaran` WHERE pegawai_id=? and tanggal=? and id_reff in ? ", [$pegawai_id, $df_tanggal, [21,23,24]]);
							break;
						case $durasi_cepat_pulang_menit >= 61 && $durasi_cepat_pulang_menit <= 90:
							$data['id_reff'] = 23;
							$data['kode'] = $this->return_referensi_pelanggaran('pelanggaran', 23);
							$data['keterangan'] = $this->return_referensi_pelanggaran('pelanggaran', 23, 'ref_description');
							$data['flag5'] = $this->return_referensi_pelanggaran('pelanggaran', 23, 'ref_value');
							$this->db->query("DELETE FROM `presensi_pelanggaran` WHERE pegawai_id=? and tanggal=? and id_reff in ? ", [$pegawai_id, $df_tanggal, [21,22,24]]);
							break;
						case $durasi_cepat_pulang_menit > 90:
						// default:
							$data['id_reff'] = 24;
							$data['kode'] = $this->return_referensi_pelanggaran('pelanggaran', 24);
							$data['keterangan'] = $this->return_referensi_pelanggaran('pelanggaran', 24, 'ref_description');
							$data['flag5'] = $this->return_referensi_pelanggaran('pelanggaran', 24, 'ref_value');
							$this->db->query("DELETE FROM `presensi_pelanggaran` WHERE pegawai_id=? and tanggal=? and id_reff in ? ", [$pegawai_id, $df_tanggal, [21,22,23]]);
							break;
					}
					$this->db->table('presensi_pelanggaran')->replace($data);
					break;
				case ($flexi==0 && (date('Y-m-d H:i:s', strtotime($stop)) >= date('Y-m-d H:i:s', strtotime($df_tanggal.' '.$df_jam_pulang))) ):
					// $this->db->table('presensi_pelanggaran')->delete(['pegawai_id'=>$pegawai_id, 'tanggal'=>$df_tanggal, 'id_reff'=>2]);
					$this->db->query("DELETE FROM `presensi_pelanggaran` WHERE pegawai_id=? and tanggal=? and id_reff in ? ", [$pegawai_id, $df_tanggal, [21,22,23,24]]);
					break;
				default:
					break;
			}
		}
	}

	function sum_potongan_pelanggaran_by($pegawai_id, $periode)
	{
		$rs = 0;
		$q = $this->db->query("SELECT sum(flag5) as potongan FROM presensi_pelanggaran where pegawai_id=? and substr(tanggal,1,7)=? ", [$pegawai_id, $periode])->getRow();
		if(!empty($q)){
			$rs = $q->potongan;
		}
		return $rs;
	}


	function return_referensi_pelanggaran($ref, $id, $field='ref_name')
	{
		$rs = '';
		$qr = $this->db->query("SELECT $field as field from app_referensi WHERE ref=? and ref_code=? ", [$ref, $id])->getRow();
		if(!empty($qr)){
			$rs = $qr->field;
		}
		return $rs;
	}

	function result_referansi_pelanggaran_by_ref($id)
	{
		return $this->db->query("SELECT * FROM app_referensi where ref=? and ref_status=? ", [$id, 1])->getResult();
	}


	/*
	*	LAPORAN KEGIATAN HARIAN
	*/
	function get_laporan_kegiatan_pegawai_in_day($pegawai_id, $tanggal)
	{
		return $this->db->query("SELECT 
			pl.id, 
			pl.pegawai_id, mj.jabatan_id, mj.jabatan_name, muk.unit_kerja_id, muk.unit_kerja_name, muk.unit_kerja_name_alt, p.nama, p.nip, 
			pl.tanggal, 
			pl.laporan, 
			pl.status, 
			pl.camera,
			pl.lampiran,
			pl.create_at, 
			pl.create_by, 
			pl.update_at, 
			pl.update_by
		from presensi_laporan pl 
			inner join pegawai p on p.pegawai_id=pl.pegawai_id
			left join ms_jabatan mj on mj.jabatan_id=p.jabatan_id
			left join ms_unit_kerja muk on muk.unit_kerja_id=p.unit_kerja_id
		WHERE pl.pegawai_id=? and pl.tanggal=? ", [$pegawai_id, $tanggal])->getRow();
	}

	function laporan_kegiatan_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('presensi_laporan')->update($data_, $where);
            $id = $this->db->insertID();
        }else{
            $this->db->table('presensi_laporan')->insert($data_);
            $id = $this->db->insertID();
        }
        return $id;
	}



	// default jam kerja
	function presensi_jam_kerja_get($pjk_id=1)
	{
		return $this->db->query("SELECT * FROM presensi_jam_kerja WHERE id=? ", [$pjk_id])->getRow();
	}

	function presensi_jam_kerja_detail_get($pjk_id, $kode_hari)
	{
		return $this->db->query("SELECT *, ADDTIME(jam_masuk, durasi_flexi) as jam_flexi FROM `presensi_jam_kerja_detail` where pjk_id=? and kode_hari=? ", [$pjk_id, $kode_hari])->getRow();
	}


	function presensi_list_tahun($pegawai_id=0)
	{
		$where = "";
		$whereValue = [];
		if($pegawai_id<>0){
			$where = "where pegawai_id=? ";
			array_push($whereValue, $pegawai_id);
		}
		return $this->db->query("SELECT distinct substr(tanggal, 1,4) tahun FROM `presensi_final` ".$where." order by substr(tanggal,1,4) desc ", $whereValue)->getResult();
	}



	public function jumlah_hari_kerja_dalam_bulan($periode)
	{
		$rs = 0;
		$dates = list_tanggal_in_periode_bulan($periode);
		$jamKerja = $this->presensi_jam_kerja_get();
		$hariLiburDefault = explode(',', $jamKerja->hari_libur);
		$tanggalLibur = $this->array_tanggal_libur();
		foreach($dates as $key=>$value){
			switch (true) {
				case array_keys($hariLiburDefault, date('D', strtotime($value))):
				case array_keys($tanggalLibur, $value):
					// code...
					break;
				default:
					$rs += 1;
					break;
			}
		}
		return $rs;
	}


	function array_libur_default($rf=1)
	{
		$jamKerja = $this->presensi_jam_kerja_get($rf);
		$hariLiburDefault = explode(',', $jamKerja->hari_libur);
		// $tanggalLibur = $this->array_tanggal_libur();
		// $gabung = array_merge($hariLiburDefault, $tanggalLibur);
		return $hariLiburDefault;
	}

	function array_tanggal_libur()
	{
		$rs = [];
		$list = $this->list_tanggal_libur();
		foreach ($list as $key) {
			array_push($rs, $key->tanggal);
		}
		return $rs;
	}

	function list_tanggal_libur()
	{
		return $this->db->query("SELECT tanggal FROM `ms_tanggal_libur` order by tanggal desc ", [])->getResult();
	}

	function list_tahun_in_hari_libur()
	{
		return $this->db->query("SELECT distinct substr(tanggal, 1,4) tahun FROM ms_tanggal_libur order by tanggal desc")->getResult();
	}

	function result_hari_libur_in_tanggal($tanggal)
	{
		return $this->db->query("SELECT * FROM ms_tanggal_libur where tanggal in ? order by tanggal desc", [$tanggal])->getResult();
	}

	function hapus_hari_libur_in_tanggal($tanggal)
	{
		$this->db->query("DELETE FROM ms_tanggal_libur where tanggal in ? ", [$tanggal]);
		return $this->db->affectedRows();
	}

	function tanggal_libur_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('ms_tanggal_libur')->update($data_, $where);
            $id = $this->db->insertID();
        }else{
            $this->db->table('ms_tanggal_libur')->insert($data_);
            $id = $this->db->insertID();
        }
        return $id;
	}

	function tanggal_libur($data_=false)
	{
		$periode = $this->request->getPost('periode');
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = $this->filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'tanggal_libur'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " ? ";
		array_push($arrWhere, 1);
		if($search_value<>''){
			$where .= " AND (keterangan like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
		}
		if($periode<>''){
			$where .= " AND substr(tanggal,1,4)=? ";
			array_push($arrWhere, $periode);
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q_rs = $this->db->query("SELECT tanggal, keterangan 
		    			, case when SUBDATE(CURDATE(), interval 90 day) < tanggal then 1 else 0 end as show_data
		    		FROM ms_tanggal_libur
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?,? ", $arrWhere)->getResult();
		    	$q = $q_rs;
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(tanggal)) as field FROM ms_tanggal_libur WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}



	/*
	*	data area
	*/
	function list_ms_area()
	{
		return $this->db->query("SELECT * FROM `ms_area` where status=? and status_presensi=? order by id desc ", [1,1])->getResult();
	}

	function hapus_ms_area_in_id($id)
	{
		$this->db->query("DELETE FROM ms_area where id in ? ", [$id]);
		return $this->db->affectedRows();
	}

	function area_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('ms_area')->update($data_, $where);
            $id = $this->db->insertID();
        }else{
            $this->db->table('ms_area')->insert($data_);
            $id = $this->db->insertID();
        }
        return $id;
	}

	function area($data_=false)
	{
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = $this->filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'area'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " ? ";
		array_push($arrWhere, 1);
		if($search_value<>''){
			$where .= " AND (a.name like ? or a.description like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q_rs = $this->db->query("SELECT a.id, a.name, a.latlong, a.status, a.status_presensi, a.description, a.create_at, a.create_by, a.timezone, a.range
		    		FROM ms_area a
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?,? ", $arrWhere)->getResult();
		    	$q = $q_rs;
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(a.id)) as field FROM ms_area a WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}


	function filterFieldData($field, $name)
	{
		switch ($name) {
			case 'riwayat':
				$r_field = 'pegawai_id';
				$array_field = ['pegawai_id', 'tanggal', 'start', 'start_ip', 'start_latlong', 'start_user', 'stop', 'stop_ip', 'stop_latlong', 'stop_user', 'status', 'pjk_id', 'kode_hari', 'total_durasi', 'total_durasi_kerja', 'keterangan', 'df_jam_masuk', 'df_jam_flexi', 'df_jam_pulang', 'df_durasi_absen', 'df_durasi_istirahat', 'df_durasi_kerja', 'df_durasi_flexi'];
				break;
			case 'harian':
				$r_field = 'pegawai_id';
				$array_field = ['pegawai_id', 'tanggal', 'start', 'start_ip', 'start_latlong', 'start_user', 'stop', 'stop_ip', 'stop_latlong', 'stop_user', 'status', 'pjk_id', 'kode_hari', 'total_durasi', 'total_durasi_kerja', 'keterangan', 'df_jam_masuk', 'df_jam_flexi', 'df_jam_pulang', 'df_durasi_absen', 'df_durasi_istirahat', 'df_durasi_kerja', 'df_durasi_flexi'];
				break;
			case 'tanggal_libur':
				$r_field = 'tanggal';
				$array_field = ['tanggal', 'keterangan'];
				break;
			case 'area':
				$r_field = 'id';
				$array_field = ['id', 'name', 'latlong', 'status', 'status_presensi', 'description', 'create_at', 'create_by', 'timezone', 'range'];
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

	function filterFieldOrder($field)
	{
		$r_field = 'asc';
		if (in_array(strtolower($field), ['asc', 'desc'], true))
		{
			$r_field = $field;
		}
		return $r_field;
	}
}