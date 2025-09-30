<?php 
namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Tools;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

class CutiModel extends Model
{
	function __construct()
	{
		$this->db = \Config\Database::connect();
		$this->request = \Config\Services::request();
        $this->tools = new Tools();
        $this->key = (session()->get('key'))?:$this->request->getHeaderLine('Key');
        $this->token = (session()->get('token'))?:$this->request->getHeaderLine('Token');
        $this->user = (session()->get('id'))?:$this->request->getHeaderLine('User');
    }

	function cuti_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('cuti')->update($data_, $where);
            $id = $this->db->affectedRows();
        }else{
            $this->db->table('cuti')->insert($data_);
            $id = $this->db->insertID();
        }
        return $id;
	}
	function cuti_delete($where=[])
	{
		$id = 0;
		if(!empty($where))
        {
        	$check = $this->db->table('cuti')->getWhere($where)->getRow();
        	if(!empty($check)){
	            $this->db->table('cuti')->delete($where);
	            $id = $this->db->affectedRows();
	            @unlink($check->path);
	            files_delete_by_id($check->lampiran);
	        }   
        }
        return $id;
	}


	function cuti_detail_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('cuti_detail')->update($data_, $where);
            $id = $this->db->affectedRows();
        }else{
            $this->db->table('cuti_detail')->insert($data_);
            $id = $this->db->affectedRows();
        }
        return $id;
	}
	function cuti_detail_delete($where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('cuti_detail')->delete($where);
            $id = $this->db->affectedRows();
        }
        return $id;
	}
	function cuti_detail_result_by_id($id)
	{
		return $this->db->query("SELECT * FROM cuti_detail where id=? ", [$id])->getResult();
	}


	function cuti_approved_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('cuti_approved')->update($data_, $where);
            $id = $this->db->affectedRows();
        }else{
            $this->db->table('cuti_approved')->insert($data_);
            $id = $this->db->affectedRows();
        }
        return $id;
	}
	function cuti_approved_delete($where=[])
	{
		$id = 0;
		if(!empty($where))
        {
        	$check = $this->db->table('cuti_approved')->getWhere($where)->getRow();
        	if(!empty($check)){
	            $this->db->table('cuti_approved')->delete($where);
	            $id = $this->db->affectedRows();
	            @unlink($check->path);
	        }
        }
        return $id;
	}
	function cuti_approved_row_by_id($id)
	{
		return $this->db->query("SELECT * FROM cuti_approved where id=? ", [$id])->getRow();
	}


	function cuti_proccess_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('cuti_proccess')->update($data_, $where);
            $id = $this->db->affectedRows();
        }else{
            $this->db->table('cuti_proccess')->insert($data_);
            $id = $this->db->affectedRows();
        }
        return $id;
	}
	function cuti_proccess_delete($where=[])
	{
		$id = 0;
		if(!empty($where))
        {
        	$check = $this->db->table('cuti_proccess')->getWhere($where)->getRow();
        	if(!empty($check)){
	            $this->db->table('cuti_proccess')->delete($where);
	            $id = $this->db->affectedRows();
	            @unlink($check->path);
	        }
        }
        return $id;
	}


	function cuti_trace_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('cuti_trace')->update($data_, $where);
            $id = $this->db->affectedRows();
        }else{
            $this->db->table('cuti_trace')->insert($data_);
            $id = $this->db->affectedRows();
        }
        return $id;
	}
	function cuti_trace_delete($where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('cuti_trace')->delete($where);
            $id = $this->db->affectedRows();
        }
        return $id;
	}


	function cuti_in_riwayat($data_=false)
	{
		// helper('toolshelp');
		$pegawai_id = string_to($this->request->getPost('pegawai_id'), 'decode');
		$check_filter = $this->request->getPost('check_filter');
		$status = $this->request->getPost('status');
		$periode = $this->request->getPost('periode');
		$jenis_cuti = $this->request->getPost('jenis_cuti');
		$status_proses = $this->request->getPost('status_proses');
		$status_persetujuan = $this->request->getPost('status_persetujuan');
		$status_proses_kepeg = $this->request->getPost('status_proses_kepeg');
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 50);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = $this->filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'riwayat'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " a.pegawai_id=? ";
		array_push($arrWhere, $pegawai_id);
		if($search_value<>''){
			$where .= " AND (a.keterangan like ? or a.alamat like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		if($check_filter==1){
			if($periode<>''){
				$where .= " and ? in (select substr(tanggal, 1,7) as periode  from cuti_detail g where g.id=a.id ) ";
				array_push($arrWhere, $periode);
			}
		}
		if(!empty($jenis_cuti)){
			$where .= " and a.jenis_cuti in ? ";
			array_push($arrWhere, $jenis_cuti);
		}
		if(!empty($status_proses)){
			$where .= " and a.status in ? ";
			array_push($arrWhere, $status_proses);
		}else{
			if(!empty($status)){
				$where .= " and a.status in ? ";
				array_push($arrWhere, $status);
			}
		}
		if(!empty($status_persetujuan)){
			$where .= " and h.status in ? ";
			array_push($arrWhere, $status_persetujuan);
		}
		if(!empty($status_proses_kepeg)){
			$where .= " and i.status in ? ";
			array_push($arrWhere, $status_proses_kepeg);
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q_rs = $this->db->query("SELECT a.id, a.pegawai_id, b.nama, b.nik, a.jabatan_id, c.jabatan_name, a.unit_kerja_id, d.unit_kerja_name, d.unit_kerja_name_alt, a.nomor_surat
						, a.jenis_cuti, f.ref_name as jenis_cuti_name, f.ref_description as jenis_cuti_name_alt, a.keterangan, a.alamat, a.telpon, a.status, e.ref_name as status_name, a.create_by, a.create_at, a.path, a.lampiran, a.flag1, a.flag2, a.nomor, a.nomor_mundur, a.instansi, a.satker, a.type, a.hari, a.bulan, a.tahun, a.tanggal_lengkap, a.unix_id, h.status as status_approval, ifnull(m.ref_name, '-') as status_approval_name, h.catatan as catatan_pimpinan, h.pegawai_id as pegawai_id_pimpinan, j.nama as nama_pimpinan, j.nik as nik_pimpinan, h.jabatan_id as jabatan_id_pimpinan, k.jabatan_name as jabatan_name_pimpinan, h.unit_kerja_id as unit_kerja_id_pimpinan, l.unit_kerja_name as unit_kerja_name_pimpinan, l.unit_kerja_name_alt as unit_kerja_name_alt_pimpinan, h.sent_time as sent_time_pimpinan, h.read as read_pimpinan, h.read_time as read_time_pimpinan, h.respon as respon_pimpinan, h.respon_time as respon_time_pimpinan, h.path as path_pimpinan, h.tte as tte_pimpinan, i.status as status_kepeg, ifnull(n.ref_name, '-') as status_kepeg_name, i.catatan as catatan_kepeg, i.sent_time as sent_time_kepeg, i.read as read_kepeg, i.read_time as read_time_kepeg, i.respon as respon_kepeg, i.respon_time as respon_time_kepeg, i.path as path_kepeg, i.tte as tte_kepeg
		    		FROM cuti a
						left join pegawai b on b.pegawai_id=a.pegawai_id
						left join ms_jabatan c on c.jabatan_id=a.jabatan_id
						left join ms_unit_kerja d on d.unit_kerja_id=a.unit_kerja_id
						left join app_referensi e on e.ref='cuti_status_proses' and e.ref_code=a.status
						left join app_referensi f on f.ref='cuti' and f.ref_code=a.jenis_cuti
						left join cuti_approved h on h.id=a.id and h.status not in (0)
						left join pegawai j on j.pegawai_id=h.pegawai_id
						left join ms_jabatan k on k.jabatan_id=h.jabatan_id
						left join ms_unit_kerja l on l.unit_kerja_id=h.unit_kerja_id
						left join cuti_proccess i on i.id=a.id and i.status not in (0)
						left join app_referensi m on m.ref='cuti_status_approval' and m.ref_code=h.status
						left join app_referensi n on n.ref='cuti_status_proses_kepeg' and n.ref_code=i.status
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?,? ", $arrWhere)->getResult();
		    	$q = [];
		    	foreach ($q_rs as $key){
		    		$array_tanggal = [];
		    		$list_tanggal = $this->cuti_detail_result_by_id($key->id);
		    		if(!empty($list_tanggal)){
		    			foreach($list_tanggal as $k)
		    				array_push($array_tanggal, $k->tanggal);
		    		}
		    		$cuti['id'] = $key->id;
		    		$cuti['hash'] = string_to($key->id, 'encode');
		    		$cuti['pegawai_id'] = $key->pegawai_id;
					$cuti['nama'] = $key->nama;
					$cuti['nik'] = $key->nik;
					$cuti['jabatan_id'] = $key->jabatan_id;
					$cuti['jabatan_name'] = $key->jabatan_name;
					$cuti['unit_kerja_id'] = $key->unit_kerja_id;
					$cuti['unit_kerja_name'] = $key->unit_kerja_name;
					$cuti['unit_kerja_name_alt'] = $key->unit_kerja_name_alt;
					$cuti['jumlah'] = count($array_tanggal);
					$cuti['tanggal'] = $array_tanggal;
					// $cuti['tanggal_tag'] = $key->tanggal_tag;
					$cuti['nomor_surat'] = $key->nomor_surat;
					// $cuti['file'] = $key->file;
					$cuti['jenis_cuti'] = $key->jenis_cuti;
					$cuti['jenis_cuti_name'] = $key->jenis_cuti_name;
					$cuti['jenis_cuti_name_alt'] = $key->jenis_cuti_name_alt;
					$cuti['keterangan'] = $key->keterangan;
					$cuti['alamat'] = $key->alamat;
					$cuti['telpon'] = $key->telpon;
					$cuti['status'] = $key->status;
					$cuti['status_name'] = $key->status_name;
					$cuti['create_by'] = $key->create_by;
					$cuti['create_at'] = $key->create_at;
					$cuti['path'] = $key->path;
					$cuti['lampiran'] = $key->lampiran;
					$cuti['flag1'] = $key->flag1;
					$cuti['flag2'] = $key->flag2;
					$cuti['nomor'] = $key->nomor;
					$cuti['nomor_mundur'] = $key->nomor_mundur;
					$cuti['instansi'] = $key->instansi;
					$cuti['satker'] = $key->satker;
					$cuti['type'] = $key->type;
					$cuti['hari'] = $key->hari;
					$cuti['bulan'] = $key->bulan;
					$cuti['tahun'] = $key->tahun;
					$cuti['tanggal_lengkap'] = $key->tanggal_lengkap;
					$cuti['unix_id'] = $key->unix_id;
					$cuti['status_approval'] = $key->status_approval;
					$cuti['status_approval_name'] = $key->status_approval_name;
					$cuti['catatan_pimpinan'] = $key->catatan_pimpinan;
					$cuti['pegawai_id_pimpinan'] = $key->pegawai_id_pimpinan;
					$cuti['nama_pimpinan'] = $key->nama_pimpinan;
					$cuti['nik_pimpinan'] = $key->nik_pimpinan;
					$cuti['jabatan_id_pimpinan'] = $key->jabatan_id_pimpinan;
					$cuti['jabatan_name_pimpinan'] = $key->jabatan_name_pimpinan;
					$cuti['unit_kerja_id_pimpinan'] = $key->unit_kerja_id_pimpinan;
					$cuti['unit_kerja_name_pimpinan'] = $key->unit_kerja_name_pimpinan;
					$cuti['unit_kerja_name_alt_pimpinan'] = $key->unit_kerja_name_alt_pimpinan;
					$cuti['sent_time_pimpinan'] = $key->sent_time_pimpinan;
					$cuti['read_pimpinan'] = $key->read_pimpinan;
					$cuti['read_time_pimpinan'] = $key->read_time_pimpinan;
					$cuti['respon_pimpinan'] = $key->respon_pimpinan;
					$cuti['respon_time_pimpinan'] = $key->respon_time_pimpinan;
					$cuti['path_pimpinan'] = $key->path_pimpinan;
					$cuti['tte_pimpinan'] = $key->tte_pimpinan;
					$cuti['status_kepeg'] = $key->status_kepeg;
					$cuti['status_kepeg_name'] = $key->status_kepeg_name;
					$cuti['catatan_kepeg'] = $key->catatan_kepeg;
					$cuti['sent_time_kepeg'] = $key->sent_time_kepeg;
					$cuti['read_kepeg'] = $key->read_kepeg;
					$cuti['read_time_kepeg'] = $key->read_time_kepeg;
					$cuti['respon_kepeg'] = $key->respon_kepeg;
					$cuti['respon_time_kepeg'] = $key->respon_time_kepeg;
					$cuti['path_kepeg'] = $key->path_kepeg;
					$cuti['tte_kepeg'] = $key->tte_kepeg;
		    		array_push($q, $cuti);
		    	}
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(a.id)) as field FROM cuti a
						left join cuti_approved h on h.id=a.id and h.status not in (0)
						left join cuti_proccess i on i.id=a.id and i.status not in (0)
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function cuti_in_permohonan($data_=false)
	{
		// helper('toolshelp');
		$status = $this->request->getPost('status');
		$periode = $this->request->getPost('periode');
		$jabatan = $this->request->getPost('jabatan');
		$status_persetujuan = $this->request->getPost('status_persetujuan');
		$check_filter = $this->request->getPost('check_filter');
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 50);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = $this->filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'riwayat'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " 1 ";
		if($search_value<>''){
			$where .= " AND (a.keterangan like ? or a.alamat like ? or b.nama like ? or b.nik like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		if(!empty($status)){
			$where .= " and a.status in ? ";
			array_push($arrWhere, $status);
		}
		if(!empty($jabatan)){
			$where .= " and a.jabatan_id in ? ";
			array_push($arrWhere, $jabatan);
		}
		if(!empty($status_persetujuan)){
			$where .= " and h.status in ? ";
			array_push($arrWhere, $status_persetujuan);
		}
		if($check_filter==1){
			if($periode<>''){
				$where .= " and ? in (select substr(tanggal, 1,7) as periode  from cuti_detail g where g.id=a.id ) ";
				array_push($arrWhere, $periode);
			}
		}
		if(!return_roles([1,2])){
			$where .= " and (h.pegawai_id=? or h.unit_kerja_id=? ) ";
			array_push($arrWhere, session()->get('pegawai_id'));
			array_push($arrWhere, session()->get('unit_kerja_id'));
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q_rs = $this->db->query("SELECT a.id, a.pegawai_id, b.nama, b.nik, a.jabatan_id, c.jabatan_name, a.unit_kerja_id, d.unit_kerja_name, d.unit_kerja_name_alt, a.nomor_surat
						, a.jenis_cuti, f.ref_name as jenis_cuti_name, f.ref_description as jenis_cuti_name_alt, a.keterangan, a.alamat, a.telpon, a.status, e.ref_name as status_name, a.create_by, a.create_at, a.path, a.lampiran, a.flag1, a.flag2, a.nomor, a.nomor_mundur, a.instansi, a.satker, a.type, a.hari, a.bulan, a.tahun, a.tanggal_lengkap, a.unix_id, h.status as status_approval, m.ref_name as status_approval_name, h.catatan as catatan_pimpinan, h.pegawai_id as pegawai_id_pimpinan, j.nama as nama_pimpinan, j.nik as nik_pimpinan, h.jabatan_id as jabatan_id_pimpinan, k.jabatan_name as jabatan_name_pimpinan, h.unit_kerja_id as unit_kerja_id_pimpinan, l.unit_kerja_name as unit_kerja_name_pimpinan, l.unit_kerja_name_alt as unit_kerja_name_alt_pimpinan, h.sent_time as sent_time_pimpinan, h.read as read_pimpinan, h.read_time as read_time_pimpinan, h.respon as respon_pimpinan, h.respon_time as respon_time_pimpinan, h.path as path_pimpinan, h.tte as tte_pimpinan, i.status as status_kepeg, n.ref_name as status_kepeg_name, i.catatan as catatan_kepeg, i.sent_time as sent_time_kepeg, i.read as read_kepeg, i.read_time as read_time_kepeg, i.respon as respon_kepeg, i.respon_time as respon_time_kepeg, i.path as path_kepeg, i.tte as tte_kepeg
		    		FROM cuti a
						left join pegawai b on b.pegawai_id=a.pegawai_id
						left join ms_jabatan c on c.jabatan_id=a.jabatan_id
						left join ms_unit_kerja d on d.unit_kerja_id=a.unit_kerja_id
						left join app_referensi e on e.ref='cuti_status_proses' and e.ref_code=a.status
						left join app_referensi f on f.ref='cuti' and f.ref_code=a.jenis_cuti
						left join cuti_approved h on h.id=a.id and h.status not in (0)
						left join pegawai j on j.pegawai_id=h.pegawai_id
						left join ms_jabatan k on k.jabatan_id=h.jabatan_id
						left join ms_unit_kerja l on l.unit_kerja_id=h.unit_kerja_id
						left join cuti_proccess i on i.id=a.id /*and i.status not in (0)*/
						left join app_referensi m on m.ref='cuti_status_approval' and m.ref_code=h.status
						left join app_referensi n on n.ref='cuti_status_proses_kepeg' and n.ref_code=i.status
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?,? ", $arrWhere)->getResult();
		    	$q = [];
		    	foreach ($q_rs as $key){
		    		$array_tanggal = [];
		    		$list_tanggal = $this->cuti_detail_result_by_id($key->id);
		    		if(!empty($list_tanggal)){
		    			foreach($list_tanggal as $k)
		    				array_push($array_tanggal, $k->tanggal);
		    		}
		    		$cuti['id'] = $key->id;
		    		$cuti['hash'] = string_to($key->id, 'encode');
		    		$cuti['pegawai_id'] = $key->pegawai_id;
					$cuti['nama'] = $key->nama;
					$cuti['nik'] = $key->nik;
					$cuti['jabatan_id'] = $key->jabatan_id;
					$cuti['jabatan_name'] = $key->jabatan_name;
					$cuti['unit_kerja_id'] = $key->unit_kerja_id;
					$cuti['unit_kerja_name'] = $key->unit_kerja_name;
					$cuti['unit_kerja_name_alt'] = $key->unit_kerja_name_alt;
					$cuti['jumlah'] = count($array_tanggal);
					$cuti['tanggal'] = $array_tanggal;
					// $cuti['tanggal_tag'] = $key->tanggal_tag;
					$cuti['nomor_surat'] = $key->nomor_surat;
					// $cuti['file'] = $key->file;
					$cuti['jenis_cuti'] = $key->jenis_cuti;
					$cuti['jenis_cuti_name'] = $key->jenis_cuti_name;
					$cuti['jenis_cuti_name_alt'] = $key->jenis_cuti_name_alt;
					$cuti['keterangan'] = $key->keterangan;
					$cuti['alamat'] = $key->alamat;
					$cuti['telpon'] = $key->telpon;
					$cuti['status'] = $key->status;
					$cuti['status_name'] = $key->status_name;
					$cuti['create_by'] = $key->create_by;
					$cuti['create_at'] = $key->create_at;
					$cuti['path'] = $key->path;
					$cuti['lampiran'] = $key->lampiran;
					$cuti['flag1'] = $key->flag1;
					$cuti['flag2'] = $key->flag2;
					$cuti['nomor'] = $key->nomor;
					$cuti['nomor_mundur'] = $key->nomor_mundur;
					$cuti['instansi'] = $key->instansi;
					$cuti['satker'] = $key->satker;
					$cuti['type'] = $key->type;
					$cuti['hari'] = $key->hari;
					$cuti['bulan'] = $key->bulan;
					$cuti['tahun'] = $key->tahun;
					$cuti['tanggal_lengkap'] = $key->tanggal_lengkap;
					$cuti['unix_id'] = $key->unix_id;
					$cuti['status_approval'] = $key->status_approval;
					$cuti['status_approval_name'] = $key->status_approval_name;
					$cuti['catatan_pimpinan'] = $key->catatan_pimpinan;
					$cuti['pegawai_id_pimpinan'] = $key->pegawai_id_pimpinan;
					$cuti['nama_pimpinan'] = $key->nama_pimpinan;
					$cuti['nik_pimpinan'] = $key->nik_pimpinan;
					$cuti['jabatan_id_pimpinan'] = $key->jabatan_id_pimpinan;
					$cuti['jabatan_name_pimpinan'] = $key->jabatan_name_pimpinan;
					$cuti['unit_kerja_id_pimpinan'] = $key->unit_kerja_id_pimpinan;
					$cuti['unit_kerja_name_pimpinan'] = $key->unit_kerja_name_pimpinan;
					$cuti['unit_kerja_name_alt_pimpinan'] = $key->unit_kerja_name_alt_pimpinan;
					$cuti['sent_time_pimpinan'] = $key->sent_time_pimpinan;
					$cuti['read_pimpinan'] = $key->read_pimpinan;
					$cuti['read_time_pimpinan'] = $key->read_time_pimpinan;
					$cuti['respon_pimpinan'] = $key->respon_pimpinan;
					$cuti['respon_time_pimpinan'] = $key->respon_time_pimpinan;
					$cuti['path_pimpinan'] = $key->path_pimpinan;
					$cuti['tte_pimpinan'] = $key->tte_pimpinan;
					$cuti['status_kepeg'] = $key->status_kepeg;
					$cuti['status_kepeg_name'] = $key->status_kepeg_name;
					$cuti['catatan_kepeg'] = $key->catatan_kepeg;
					$cuti['sent_time_kepeg'] = $key->sent_time_kepeg;
					$cuti['read_kepeg'] = $key->read_kepeg;
					$cuti['read_time_kepeg'] = $key->read_time_kepeg;
					$cuti['respon_kepeg'] = $key->respon_kepeg;
					$cuti['respon_time_kepeg'] = $key->respon_time_kepeg;
					$cuti['path_kepeg'] = $key->path_kepeg;
					$cuti['tte_kepeg'] = $key->tte_kepeg;
		    		array_push($q, $cuti);
		    	}
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(a.id)) as field FROM cuti a
						left join pegawai b on b.pegawai_id=a.pegawai_id
						left join cuti_approved h on h.id=a.id and h.status not in (0)
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function cuti_in_proses($data_=false)
	{
		// helper('toolshelp');
		$status = $this->request->getPost('status');
		$periode = $this->request->getPost('periode');
		$jabatan = $this->request->getPost('jabatan');
		$unit_kerja = $this->request->getPost('unit_kerja');
		$status_proses_kepeg = $this->request->getPost('status_proses_kepeg');
		$check_filter = $this->request->getPost('check_filter');
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 50);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = $this->filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'riwayat'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " 1 ";
		if($search_value<>''){
			$where .= " AND (a.keterangan like ? or a.alamat like ? or b.nama like ? or b.nik like ? or a.nomor_surat like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		if(!empty($status)){
			$where .= " and a.status in ? ";
			array_push($arrWhere, $status);
		}
		if(!empty($jabatan)){
			$where .= " and a.jabatan_id in ? ";
			array_push($arrWhere, $jabatan);
		}
		if(!empty($unit_kerja)){
			$where .= " and a.unit_kerja_id in ? ";
			array_push($arrWhere, $unit_kerja);
		}
		if(!empty($status_proses_kepeg)){
			$where .= " and i.status in ? ";
			array_push($arrWhere, $status_proses_kepeg);
		}
		if($check_filter==1){
			if($periode<>''){
				$where .= " and ? in (select substr(tanggal, 1,7) as periode  from cuti_detail g where g.id=a.id ) ";
				array_push($arrWhere, $periode);
			}
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q_rs = $this->db->query("SELECT a.id, a.pegawai_id, b.nama, b.nik, a.jabatan_id, c.jabatan_name, a.unit_kerja_id, d.unit_kerja_name, d.unit_kerja_name_alt, a.nomor_surat
						, a.jenis_cuti, f.ref_name as jenis_cuti_name, f.ref_description as jenis_cuti_name_alt, a.keterangan, a.alamat, a.telpon, a.status, e.ref_name as status_name, a.create_by, a.create_at, a.path, a.lampiran, a.flag1, a.flag2, a.nomor, a.nomor_mundur, a.instansi, a.satker, a.type, a.hari, a.bulan, a.tahun, a.tanggal_lengkap, a.unix_id, h.status as status_approval, m.ref_name as status_approval_name, h.catatan as catatan_pimpinan, h.pegawai_id as pegawai_id_pimpinan, j.nama as nama_pimpinan, j.nik as nik_pimpinan, h.jabatan_id as jabatan_id_pimpinan, k.jabatan_name as jabatan_name_pimpinan, h.unit_kerja_id as unit_kerja_id_pimpinan, l.unit_kerja_name as unit_kerja_name_pimpinan, l.unit_kerja_name_alt as unit_kerja_name_alt_pimpinan, h.sent_time as sent_time_pimpinan, h.read as read_pimpinan, h.read_time as read_time_pimpinan, h.respon as respon_pimpinan, h.respon_time as respon_time_pimpinan, h.path as path_pimpinan, h.tte as tte_pimpinan, i.status as status_kepeg, n.ref_name as status_kepeg_name, i.catatan as catatan_kepeg, i.sent_time as sent_time_kepeg, i.read as read_kepeg, i.read_time as read_time_kepeg, i.respon as respon_kepeg, i.respon_time as respon_time_kepeg, i.path as path_kepeg, i.tte as tte_kepeg
		    		FROM cuti a
						left join pegawai b on b.pegawai_id=a.pegawai_id
						left join ms_jabatan c on c.jabatan_id=a.jabatan_id
						left join ms_unit_kerja d on d.unit_kerja_id=a.unit_kerja_id
						left join app_referensi e on e.ref='cuti_status_proses' and e.ref_code=a.status
						left join app_referensi f on f.ref='cuti' and f.ref_code=a.jenis_cuti
						left join cuti_approved h on h.id=a.id and h.status not in (0)
						left join pegawai j on j.pegawai_id=h.pegawai_id
						left join ms_jabatan k on k.jabatan_id=h.jabatan_id
						left join ms_unit_kerja l on l.unit_kerja_id=h.unit_kerja_id
						left join cuti_proccess i on i.id=a.id and i.status not in (0)
						left join app_referensi m on m.ref='cuti_status_approval' and m.ref_code=h.status
						left join app_referensi n on n.ref='cuti_status_proses_kepeg' and n.ref_code=i.status
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?,? ", $arrWhere)->getResult();
		    	$q = [];
		    	foreach ($q_rs as $key){
		    		$array_tanggal = [];
		    		$list_tanggal = $this->cuti_detail_result_by_id($key->id);
		    		if(!empty($list_tanggal)){
		    			foreach($list_tanggal as $k)
		    				array_push($array_tanggal, $k->tanggal);
		    		}
		    		$cuti['id'] = $key->id;
		    		$cuti['hash'] = string_to($key->id, 'encode');
		    		$cuti['pegawai_id'] = $key->pegawai_id;
					$cuti['nama'] = $key->nama;
					$cuti['nik'] = $key->nik;
					$cuti['jabatan_id'] = $key->jabatan_id;
					$cuti['jabatan_name'] = $key->jabatan_name;
					$cuti['unit_kerja_id'] = $key->unit_kerja_id;
					$cuti['unit_kerja_name'] = $key->unit_kerja_name;
					$cuti['unit_kerja_name_alt'] = $key->unit_kerja_name_alt;
					$cuti['jumlah'] = count($array_tanggal);
					$cuti['tanggal'] = $array_tanggal;
					// $cuti['tanggal_tag'] = $key->tanggal_tag;
					$cuti['nomor_surat'] = $key->nomor_surat;
					// $cuti['file'] = $key->file;
					$cuti['jenis_cuti'] = $key->jenis_cuti;
					$cuti['jenis_cuti_name'] = $key->jenis_cuti_name;
					$cuti['jenis_cuti_name_alt'] = $key->jenis_cuti_name_alt;
					$cuti['keterangan'] = $key->keterangan;
					$cuti['alamat'] = $key->alamat;
					$cuti['telpon'] = $key->telpon;
					$cuti['status'] = $key->status;
					$cuti['status_name'] = $key->status_name;
					$cuti['create_by'] = $key->create_by;
					$cuti['create_at'] = $key->create_at;
					$cuti['path'] = $key->path;
					$cuti['lampiran'] = $key->lampiran;
					$cuti['flag1'] = $key->flag1;
					$cuti['flag2'] = $key->flag2;
					$cuti['nomor'] = $key->nomor;
					$cuti['nomor_mundur'] = $key->nomor_mundur;
					$cuti['instansi'] = $key->instansi;
					$cuti['satker'] = $key->satker;
					$cuti['type'] = $key->type;
					$cuti['hari'] = $key->hari;
					$cuti['bulan'] = $key->bulan;
					$cuti['tahun'] = $key->tahun;
					$cuti['tanggal_lengkap'] = $key->tanggal_lengkap;
					$cuti['unix_id'] = $key->unix_id;
					$cuti['status_approval'] = $key->status_approval;
					$cuti['status_approval_name'] = $key->status_approval_name;
					$cuti['catatan_pimpinan'] = $key->catatan_pimpinan;
					$cuti['pegawai_id_pimpinan'] = $key->pegawai_id_pimpinan;
					$cuti['nama_pimpinan'] = $key->nama_pimpinan;
					$cuti['nik_pimpinan'] = $key->nik_pimpinan;
					$cuti['jabatan_id_pimpinan'] = $key->jabatan_id_pimpinan;
					$cuti['jabatan_name_pimpinan'] = $key->jabatan_name_pimpinan;
					$cuti['unit_kerja_id_pimpinan'] = $key->unit_kerja_id_pimpinan;
					$cuti['unit_kerja_name_pimpinan'] = $key->unit_kerja_name_pimpinan;
					$cuti['unit_kerja_name_alt_pimpinan'] = $key->unit_kerja_name_alt_pimpinan;
					$cuti['sent_time_pimpinan'] = $key->sent_time_pimpinan;
					$cuti['read_pimpinan'] = $key->read_pimpinan;
					$cuti['read_time_pimpinan'] = $key->read_time_pimpinan;
					$cuti['respon_pimpinan'] = $key->respon_pimpinan;
					$cuti['respon_time_pimpinan'] = $key->respon_time_pimpinan;
					$cuti['path_pimpinan'] = $key->path_pimpinan;
					$cuti['tte_pimpinan'] = $key->tte_pimpinan;
					$cuti['status_kepeg'] = $key->status_kepeg;
					$cuti['status_kepeg_name'] = $key->status_kepeg_name;
					$cuti['catatan_kepeg'] = $key->catatan_kepeg;
					$cuti['sent_time_kepeg'] = $key->sent_time_kepeg;
					$cuti['read_kepeg'] = $key->read_kepeg;
					$cuti['read_time_kepeg'] = $key->read_time_kepeg;
					$cuti['respon_kepeg'] = $key->respon_kepeg;
					$cuti['respon_time_kepeg'] = $key->respon_time_kepeg;
					$cuti['path_kepeg'] = $key->path_kepeg;
					$cuti['tte_kepeg'] = $key->tte_kepeg;
		    		array_push($q, $cuti);
		    	}
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(a.id)) as field FROM cuti a
						left join pegawai b on b.pegawai_id=a.pegawai_id
						left join cuti_proccess i on i.id=a.id and i.status not in (0)
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function cuti_get_row($id)
	{
		$q_rs = $this->db->query("SELECT a.id, a.pegawai_id, b.nama, b.nik, b.nip, b.status_pns, a.jabatan_id, c.jabatan_name, a.unit_kerja_id, d.unit_kerja_name, d.unit_kerja_name_alt, a.nomor_surat, a.jenis_cuti, f.ref_name as jenis_cuti_name, f.ref_description as jenis_cuti_name_alt, a.keterangan, a.alamat, a.telpon, a.status, e.ref_name as status_name, a.create_by, a.create_at, a.path, a.lampiran, a.flag1, a.flag2, a.nomor, a.nomor_mundur, a.instansi, a.satker, a.type, a.hari, a.bulan, a.tahun, a.tanggal_lengkap, a.unix_id, h.status as status_approval, m.ref_name as status_approval_name, h.catatan as catatan_pimpinan, h.pegawai_id as pegawai_id_pimpinan, j.nama as nama_pimpinan, j.nik as nik_pimpinan, j.nip as nip_pimpinan, h.jabatan_id as jabatan_id_pimpinan, k.jabatan_name as jabatan_name_pimpinan, h.unit_kerja_id as unit_kerja_id_pimpinan, l.unit_kerja_name as unit_kerja_name_pimpinan, l.unit_kerja_name_alt as unit_kerja_name_alt_pimpinan, h.sent_time as sent_time_pimpinan, h.read as read_pimpinan, h.read_time as read_time_pimpinan, h.respon as respon_pimpinan, h.respon_time as respon_time_pimpinan, h.path as path_pimpinan, h.tte as tte_pimpinan, i.status as status_kepeg, n.ref_name as status_kepeg_name, i.catatan as catatan_kepeg, i.sent_time as sent_time_kepeg, i.read as read_kepeg, i.read_time as read_time_kepeg, i.respon as respon_kepeg, i.respon_time as respon_time_kepeg, i.path as path_kepeg, i.tte as tte_kepeg
			FROM cuti a
				left join pegawai b on b.pegawai_id=a.pegawai_id
				left join ms_jabatan c on c.jabatan_id=a.jabatan_id
				left join ms_unit_kerja d on d.unit_kerja_id=a.unit_kerja_id
				left join app_referensi e on e.ref='cuti_status_proses' and e.ref_code=a.status
				left join app_referensi f on f.ref='cuti' and f.ref_code=a.jenis_cuti
				left join cuti_approved h on h.id=a.id and h.status not in (0)
				left join pegawai j on j.pegawai_id=h.pegawai_id
				left join ms_jabatan k on k.jabatan_id=h.jabatan_id
				left join ms_unit_kerja l on l.unit_kerja_id=h.unit_kerja_id
				left join cuti_proccess i on i.id=a.id and i.status not in (0)
				left join app_referensi m on m.ref='cuti_status_approval' and m.ref_code=h.status
				left join app_referensi n on n.ref='cuti_status_proses_kepeg' and n.ref_code=i.status
			WHERE a.id=? ", [$id])->getRow();
    	$q = [];
    	if (!empty($q_rs)){
    		$array_tanggal = [];
    		$list_tanggal = $this->cuti_detail_result_by_id($q_rs->id);
    		if(!empty($list_tanggal)){
    			foreach($list_tanggal as $k)
    				array_push($array_tanggal, $k->tanggal);
    		}
    		$cuti['id'] = $q_rs->id;
    		$cuti['hash'] = string_to($q_rs->id, 'encode');
    		$cuti['pegawai_id'] = $q_rs->pegawai_id;
			$cuti['nama'] = $q_rs->nama;
			$cuti['nik'] = $q_rs->nik;
			$cuti['nip'] = $q_rs->nip;
			$cuti['status_pns'] = $q_rs->status_pns;
			$cuti['jabatan_id'] = $q_rs->jabatan_id;
			$cuti['jabatan_name'] = $q_rs->jabatan_name;
			$cuti['unit_kerja_id'] = $q_rs->unit_kerja_id;
			$cuti['unit_kerja_name'] = $q_rs->unit_kerja_name;
			$cuti['unit_kerja_name_alt'] = $q_rs->unit_kerja_name_alt;
			$cuti['jumlah'] = count($array_tanggal);
			$cuti['tanggal'] = $array_tanggal;
			// $cuti['tanggal_tag'] = $q_rs->tanggal_tag;
			$cuti['nomor_surat'] = $q_rs->nomor_surat;
			// $cuti['file'] = $q_rs->file;
			$cuti['jenis_cuti'] = $q_rs->jenis_cuti;
			$cuti['jenis_cuti_name'] = $q_rs->jenis_cuti_name;
			$cuti['jenis_cuti_name_alt'] = $q_rs->jenis_cuti_name_alt;
			$cuti['keterangan'] = $q_rs->keterangan;
			$cuti['alamat'] = $q_rs->alamat;
			$cuti['telpon'] = $q_rs->telpon;
			$cuti['status'] = $q_rs->status;
			$cuti['status_name'] = $q_rs->status_name;
			$cuti['create_by'] = $q_rs->create_by;
			$cuti['create_at'] = $q_rs->create_at;
			$cuti['path'] = $q_rs->path;
			$cuti['lampiran'] = $q_rs->lampiran;
			$cuti['flag1'] = $q_rs->flag1;
			$cuti['flag2'] = $q_rs->flag2;
			$cuti['nomor'] = $q_rs->nomor;
			$cuti['nomor_mundur'] = $q_rs->nomor_mundur;
			$cuti['instansi'] = $q_rs->instansi;
			$cuti['satker'] = $q_rs->satker;
			$cuti['type'] = $q_rs->type;
			$cuti['hari'] = $q_rs->hari;
			$cuti['bulan'] = $q_rs->bulan;
			$cuti['tahun'] = $q_rs->tahun;
			$cuti['tanggal_lengkap'] = $q_rs->tanggal_lengkap;
			$cuti['unix_id'] = $q_rs->unix_id;
			$cuti['status_approval'] = $q_rs->status_approval;
			$cuti['status_approval_name'] = $q_rs->status_approval_name;
			$cuti['catatan_pimpinan'] = $q_rs->catatan_pimpinan;
			$cuti['pegawai_id_pimpinan'] = $q_rs->pegawai_id_pimpinan;
			$cuti['nama_pimpinan'] = $q_rs->nama_pimpinan;
			$cuti['nik_pimpinan'] = $q_rs->nik_pimpinan;
			$cuti['nip_pimpinan'] = $q_rs->nip_pimpinan;
			$cuti['jabatan_id_pimpinan'] = $q_rs->jabatan_id_pimpinan;
			$cuti['jabatan_name_pimpinan'] = $q_rs->jabatan_name_pimpinan;
			$cuti['unit_kerja_id_pimpinan'] = $q_rs->unit_kerja_id_pimpinan;
			$cuti['unit_kerja_name_pimpinan'] = $q_rs->unit_kerja_name_pimpinan;
			$cuti['unit_kerja_name_alt_pimpinan'] = $q_rs->unit_kerja_name_alt_pimpinan;
			$cuti['sent_time_pimpinan'] = $q_rs->sent_time_pimpinan;
			$cuti['read_pimpinan'] = $q_rs->read_pimpinan;
			$cuti['read_time_pimpinan'] = $q_rs->read_time_pimpinan;
			$cuti['respon_pimpinan'] = $q_rs->respon_pimpinan;
			$cuti['respon_time_pimpinan'] = $q_rs->respon_time_pimpinan;
			$cuti['path_pimpinan'] = $q_rs->path_pimpinan;
			$cuti['tte_pimpinan'] = $q_rs->tte_pimpinan;
			$cuti['status_kepeg'] = $q_rs->status_kepeg;
			$cuti['status_kepeg_name'] = $q_rs->status_kepeg_name;
			$cuti['catatan_kepeg'] = $q_rs->catatan_kepeg;
			$cuti['sent_time_kepeg'] = $q_rs->sent_time_kepeg;
			$cuti['read_kepeg'] = $q_rs->read_kepeg;
			$cuti['read_time_kepeg'] = $q_rs->read_time_kepeg;
			$cuti['respon_kepeg'] = $q_rs->respon_kepeg;
			$cuti['respon_time_kepeg'] = $q_rs->respon_time_kepeg;
			$cuti['path_kepeg'] = $q_rs->path_kepeg;
			$cuti['tte_kepeg'] = $q_rs->tte_kepeg;
    		$q = $cuti;
    	}
	    return $q;
	}


	// master saldo
	function master_saldo_cuti($data_=false)
	{
		$tahun = $this->request->getPost('tahun');
		$unit_kerja = $this->request->getPost('unit_kerja');
		$jabatan = $this->request->getPost('jabatan');
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 50);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = $this->filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'cuti_saldo'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " 1 ";
		if($search_value<>''){
			$where .= " AND (b.nama like ? or a.sisa_saat_ini like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		if($tahun<>''){
			$where .= " and a.tahun=? ";
			array_push($arrWhere, $tahun);
		}
		if(!empty($unit_kerja)){
			$where .= " and a.unit_kerja_id in ? ";
			array_push($arrWhere, $unit_kerja);
		}
		if(!empty($jabatan)){
			$where .= " and a.jabatan_id in ? ";
			array_push($arrWhere, $jabatan);
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q_rs = $this->db->query("SELECT a.id, a.pegawai_id, a.jabatan_id, a.unit_kerja_id, a.tahun, a.jatah, a.sisa_sebelumnya, a.total, a.digunakan, a.sisa_saat_ini, a.status, a.create_at, a.create_by, a.update_at, a.update_by 
						, b.nip, b.nik, b.nama, b.gelar_depan, b.gelar_belakang, c.jabatan_name, d.unit_kerja_name, d.unit_kerja_name_alt
					FROM cuti_saldo a
						left join pegawai b on b.pegawai_id=a.pegawai_id
						left join ms_jabatan c on c.jabatan_id=a.jabatan_id
						left join ms_unit_kerja d on d.unit_kerja_id=a.unit_kerja_id
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?,? ", $arrWhere)->getResult();
		    	$q = $q_rs;
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(a.id)) as field FROM cuti_saldo a
						left join pegawai b on b.pegawai_id=a.pegawai_id
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function row_cuti_saldo($id)
	{
		return $this->db->query("SELECT a.id, a.pegawai_id, a.jabatan_id, a.unit_kerja_id, a.tahun, a.jatah, a.sisa_sebelumnya, a.total, a.digunakan, a.sisa_saat_ini, a.status, a.create_at, a.create_by, a.update_at, a.update_by 
				, b.nip, b.nik, b.nama, b.gelar_depan, b.gelar_belakang, c.jabatan_name, d.unit_kerja_name, d.unit_kerja_name_alt
			FROM cuti_saldo a
				left join pegawai b on b.pegawai_id=a.pegawai_id
				left join ms_jabatan c on c.jabatan_id=a.jabatan_id
				left join ms_unit_kerja d on d.unit_kerja_id=a.unit_kerja_id
    		WHERE a.id=? ", [$id])->getRow();
	}

	function result_saldo_cuti_pegawai($id)
	{
		return $this->db->query("SELECT * FROM cuti_saldo a where a.pegawai_id=?  order by a.tahun desc ", [$id])->getResult();
	}

	function result_cuti_saldo_tahun($id='desc')
	{
		return $this->db->query("SELECT distinct a.tahun as field FROM cuti_saldo a
    		WHERE 1 order by a.tahun $id ", [$id])->getResult();
	}

	function cuti_saldo_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('cuti_saldo')->update($data_, $where);
            $id = $this->db->affectedRows();
        }else{
            $this->db->table('cuti_saldo')->insert($data_);
            $id = $this->db->affectedRows();
        }
        return $id;
	}

	function cuti_saldo_delete_in_id($where=[])
	{
		$id = 0;
		if(!empty($where))
        {
        	$check = $this->db->query("select id from cuti_saldo where id in ?", [$where])->getResult();
        	if(!empty($check)){
	            $this->db->query("delete from cuti_saldo where id in ? ", [$where]);
	            $id = $this->db->affectedRows();
	        }   
        }
        return $id;
	}
	function return_sisa_cuti_terakhir_by_pegawaiid($id)
	{
		$rs = 0;
		$q = $this->db->query("SELECT SUM(sisa_saat_ini) as field FROM cuti_saldo where pegawai_id=? ", [$id])->getRow();
		if(!empty($q))
		{
			$rs = $q->field;
		}
		return $rs;
	}
	function return_saldo_cuti_by_pegawaiid_tahun($id, $tahun)
	{
		$rs = 0;
		$q = $this->db->query("SELECT sisa_saat_ini as field FROM cuti_saldo where pegawai_id=? and tahun=? ", [$id, $tahun])->getRow();
		if(!empty($q))
		{
			$rs = (int)$q->field;
		}
		return $rs;
	}
	function return_saldo_cuti_digunakan_by_pegawaiid_tahun($id, $tahun)
	{
		$rs = 0;
		$q = $this->db->query("SELECT digunakan as field FROM cuti_saldo where pegawai_id=? and tahun=? ", [$id, $tahun])->getRow();
		if(!empty($q))
		{
			$rs = (int)$q->field;
		}
		return $rs;
	}

	function cuti_saldo_update_saldo($pegawai_id, $jumlah, $jenis_cuti)
	{
		$exTahun = date('Y');
		$arTahun = [];
		for ($i=$exTahun; $i > ($exTahun-3); $i--) { 
			array_push($arTahun, $i);
		}
		if(!empty($arTahun) && array_keys([1], $jenis_cuti)){
			$jml_yg = $jumlah;
			$xn_ = $this->return_saldo_cuti_by_pegawaiid_tahun($pegawai_id, $arTahun[0]);
			$xn_1 = $this->return_saldo_cuti_by_pegawaiid_tahun($pegawai_id, $arTahun[1]);
			$xn_2 = $this->return_saldo_cuti_by_pegawaiid_tahun($pegawai_id, $arTahun[2]);
			$xdgn_ = $this->return_saldo_cuti_digunakan_by_pegawaiid_tahun($pegawai_id, $arTahun[0]);
			$xdgn_1 = $this->return_saldo_cuti_digunakan_by_pegawaiid_tahun($pegawai_id, $arTahun[1]);
			$xdgn_2 = $this->return_saldo_cuti_digunakan_by_pegawaiid_tahun($pegawai_id, $arTahun[2]);
			if($xn_2>0){
				if($jml_yg<=$xn_2)
				{
					$n_2 = $xn_2 - $jml_yg;
					$digunakan_n2 = $xdgn_2+$jml_yg;
					$jml_yg -= $jml_yg;
				}else{
					$n_2 = 0;
					$jml_yg = $jml_yg-$xn_2;
					$digunakan_n2 = $xdgn_2+$xn_2;
				}
				$this->cuti_saldo_save(['digunakan'=>$digunakan_n2, 'sisa_saat_ini'=>$n_2], ['pegawai_id'=>$pegawai_id, 'tahun'=>$arTahun[2]]);
			}
			if($xn_1>0){
				if($jml_yg<=$xn_1)
				{
					$n_1 = $xn_1 - $jml_yg;
					$digunakan_n1 = $xdgn_1+$jml_yg;
					$jml_yg -= $jml_yg;
				}else{
					$n_1 = 0;
					$jml_yg = $jml_yg-$xn_1;
					$digunakan_n1 = $xdgn_1+$xn_1;
				}
				$this->cuti_saldo_save(['digunakan'=>$digunakan_n1, 'sisa_saat_ini'=>$n_1], ['pegawai_id'=>$pegawai_id, 'tahun'=>$arTahun[1]]);
			}
			if($xn_>0){
				if($jml_yg<=$xn_)
				{
					$n_ = $xn_ - $jml_yg;
					$digunakan_n = $xdgn_+$jml_yg;
					$jml_yg -= $jml_yg;
				}else{
					$n_ = 0;
					$jml_yg = $jml_yg-$xn_;
					$digunakan_n = $xdgn_+$xn_;
				}
				$this->cuti_saldo_save(['digunakan'=>$digunakan_n, 'sisa_saat_ini'=>$n_], ['pegawai_id'=>$pegawai_id, 'tahun'=>$arTahun[0]]);
			}
		}
	}

	function result_pegawai_before_insert_master_saldo($optional, $unit_kerja_id, $pegawai_id)
	{
        $whereValue = [];
        $where = " status=? ";
        array_push($whereValue, 1);
        if($optional==2){
            $where .= " and unit_kerja_id in ? ";
            array_push($whereValue, $unit_kerja_id);
        }
        if($optional==3){
            $where .= " and pegawai_id in ? ";
            array_push($whereValue, $pegawai_id);
        }
    	return $this->db->query("SELECT * FROM pegawai WHERE ".$where, $whereValue)->getResult();
	}




	/*
	*	nanti di cek2 atau ditambahin lagi sesuai kondisi
	*/
	function filterFieldData($field, $name)
	{
		switch ($name) {
			case 'riwayat':
				$r_field = 'pegawai_id';
				$array_field = ['id','pegawai_id', 'nama', 'nik', 'jabatan_id', 'jabatan_name', 'unit_kerja_id', 'unit_kerja_name', 'unit_kerja_name_alt', 'nomor_surat', 'file', 'jenis_cuti', 'ref_name', 'ref_description', 'keterangan', 'alamat', 'telpon', 'status', 'ref_name', 'create_by', 'create_at', 'path', 'lampiran', 'flag1', 'flag2', 'nomor', 'nomor_mundur', 'instansi', 'satker', 'type', 'hari', 'bulan', 'tahun', 'tanggal_lengkap'];
				break;
			case 'cuti_saldo':
				$r_field = 'id';
				$array_field = ['id', 'pegawai_id', 'jabatan_id', 'unit_kerja_id', 'tahun', 'jatah', 'sisa_sebelumnya', 'total', 'digunakan', 'sisa_saat_ini', 'status', 'create_at', 'create_by', 'update_at', 'update_by', 'nip', 'nik', 'nama', 'gelar_depan', 'gelar_belakang', 'jabatan_name', 'unit_kerja_name', 'unit_kerja_name_alt'];
				break;
			// case 'tanggal_libur':
			// 	$r_field = 'tanggal';
			// 	$array_field = ['tanggal', 'keterangan'];
			// 	break;
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


	function return_tanggal_awal_cuti_by_id($id)
	{
		$rs = '';
		$q = $this->db->query("SELECT tanggal as field FROM cuti_detail where id=? order by tanggal asc limit 1 ", [$id])->getRow();
		if(!empty($q))
		{
			$rs = $q->field;
		}
		return $rs;
	}


	/*
	*	create file form cuti
	*/
	function replace_text_in_docx_file_and_export_to_pdf($id, $level=0, $test=false) {
		$path_file_new = '';
		$data_cuti = $this->cuti_get_row($id);
		if(!empty($data_cuti))
		{
			$image_qrcode = '';
			$footer_text = '';

			$sisa_cuti = $this->return_sisa_cuti_terakhir_by_pegawaiid($data_cuti['pegawai_id']);
			$sisa_cuti_diambil = $sisa_cuti;
			$f_jumlah1 = '';
			$f_jumlah2 = '';
			$f_jumlah3 = '';
			$f_jumlah4 = '';
			$f_jumlah5 = '';
			$f_cuti_nama = $data_cuti['jenis_cuti_name'];
			switch ($data_cuti['jenis_cuti']) {
				case 'CT':
					$sisa_cuti_diambil = $sisa_cuti - $data_cuti['jumlah'];
					$f_jumlah1 = $data_cuti['jumlah'].' hari';
					$f_cuti_nama = $data_cuti['jenis_cuti_name'].' '.date('Y');
					break;
				case 'CM':
					$f_jumlah3 = $data_cuti['jumlah'].' hari';
					break;
				case 'CS':
					$f_jumlah2 = $data_cuti['jumlah'].' hari';
					break;
			}
			$check1 = '';
			if(array_keys([1], $data_cuti['jenis_cuti'])){
				$check1 = '&#x2713;';
			}
			$check2 = '';
			if(array_keys([3], $data_cuti['jenis_cuti'])){
				$check2 = '&#x2713;';
			}
			$check3 = '';
			if(array_keys([2], $data_cuti['jenis_cuti'])){
				$check3 = '&#x2713;';
			}
			$check4 = '';
			if(array_keys([5], $data_cuti['jenis_cuti'])){
				$check4 = '&#x2713;';
			}
			$check5 = '';
			if(array_keys([4], $data_cuti['jenis_cuti'])){
				$check5 = '&#x2713;';
			}
			$check6 = '';
			if(array_keys([6], $data_cuti['jenis_cuti'])){
				$check6 = '&#x2713;';
			}

			$statusCheck2 = '';
			$txtCheck2 = '';
			if(array_keys([2], $data_cuti['status_approval']))
			{
				$statusCheck2 = '&#x2713;';
				// $txtCheck2 = $data_cuti['catatan_pimpinan'];
			}
			$statusCheck3 = '';
			$txtCheck3 = '';
			if(array_keys([3], $data_cuti['status_approval']))
			{
				$statusCheck3 = '&#x2713;';
				$txtCheck3 = $data_cuti['catatan_pimpinan'];
			}
			$statusCheck4 = '';
			$txtCheck4 = '';
			if(array_keys([4], $data_cuti['status_approval']))
			{
				$statusCheck4 = '&#x2713;';
				$txtCheck4 = $data_cuti['catatan_pimpinan'];
			}
			$statusCheck5 = '';
			$txtCheck5 = '';
			if(array_keys([5], $data_cuti['status_approval']))
			{
				$statusCheck5 = '&#x2713;';
				$txtCheck5 = $data_cuti['catatan_pimpinan'];
			}

			// ISI KEPUTUSAN JIKA STATUS PEGAWAI PNS/ASN SEKRE
			// $statusCheck2_ver = '';
			// $txtCheck2_ver = '';
			// if(array_keys([2], $data_cuti['status_ver']))
			// {
			// 	$statusCheck2_ver = '&#x2713;';
			// 	// $txtCheck2 = $data_cuti['catatan_ver'];
			// }
			// $statusCheck3_ver = '';
			// $txtCheck3_ver = '';
			// if(array_keys([3], $data_cuti['status_ver']))
			// {
			// 	$statusCheck3_ver = '&#x2713;';
			// 	$txtCheck3_ver = $data_cuti['catatan_ver'];
			// }
			// $statusCheck4_ver='';
			// $txtCheck4_ver = '';
			// if(array_keys([4], $data_cuti['status_ver']))
			// {
			// 	$statusCheck4_ver = '&#x2713;';
			// 	$txtCheck4_ver = $data_cuti['catatan_ver'];
			// }
			// $statusCheck5_ver = '';
			// $txtCheck5_ver = '';
			// if(array_keys([5], $data_cuti['status_ver']))
			// {
			// 	$statusCheck5_ver = '&#x2713;';
			// 	$txtCheck5_ver = $data_cuti['catatan_ver'];
			// }
			$n_ = '';
			$n_1 = '';
			$n_2 = '';
			if((array_keys([1,2], $data_cuti['status_pns']) && array_keys([5], $data_cuti['unit_kerja_id'])))
			{
				$exTahun = date('Y');
				$arTahun = [];
				for ($i=$exTahun; $i > ($exTahun-3); $i--) { 
					array_push($arTahun, $i);
				}
				if(!empty($arTahun)){
					$jml_yg = $data_cuti['jumlah'];
					$n_ = (int)$this->return_saldo_cuti_by_pegawaiid_tahun($data_cuti['pegawai_id'], $arTahun[0]);
					$n_1 = (int)$this->return_saldo_cuti_by_pegawaiid_tahun($data_cuti['pegawai_id'], $arTahun[1]);
					$n_2 = (int)$this->return_saldo_cuti_by_pegawaiid_tahun($data_cuti['pegawai_id'], $arTahun[2]);
				}
			}
			$f_tanggalnya = '';
			$tgl = [];
			$d_tanggal = $data_cuti['tanggal'];
			if(!empty($d_tanggal))
			{
				foreach($d_tanggal as $k=>$v){
					array_push($tgl, $v);
				}
				$f_tanggalnya = groupTanggalInMonth($tgl);
			}
			if($data_cuti['nomor_surat']<>'')
			{
				$f_nomor_surat = $data_cuti['nomor_surat'];
			}else{
				$f_nomor_surat = $this->create_nomor_surat($data_cuti['id'], 'BKP', $test);
				// $f_nomor_surat = 'TEST-NOMOR-SURAT-000';
			}
			/*	LOAD TEMPLATE FORM CUTI FOR EMPLOYEE */
			$file_yg_di_proses = WRITEPATH.'templates/form_cuti_tp.docx';
			// require APPPATH.'Libraries/PHPWord-develop/bootstrap.php';
			// $phpWord = new \PhpOffice\PhpWord\PhpWord();
			// $document = $phpWord->loadTemplate($file_yg_di_proses);
			$document = new TemplateProcessor($file_yg_di_proses);
			$document->setValue('f_nomor_surat', $f_nomor_surat);
			$document->setValue('f_tahun', date('Y'));
			$document->setValue('f_tanggal_', tanggal(substr($data_cuti['create_at'],0,10),3));
			$document->setValue('f_nip', $data_cuti['nip']);
			$document->setValue('f_nama', $data_cuti['nama']);
			$document->setValue('f_jabatan', $data_cuti['jabatan_name']);
			$document->setValue('f_satker', $data_cuti['unit_kerja_name_alt']);
			$document->setValue('f_check1', $check1);
			$document->setValue('f_check2', $check2);
			$document->setValue('f_check3', $check3);
			$document->setValue('f_check4', $check4);
			$document->setValue('f_check5', $check5);
			$document->setValue('f_check6', $check6);
			$document->setValue('f_n_', $n_);
			$document->setValue('f_n_1', $n_1);
			$document->setValue('f_n_2', $n_2);

			$document->setValue('f_cuti_nama', $f_cuti_nama);
			$document->setValue('f_keterangan', $data_cuti['keterangan']);
			$document->setValue('f_jumlah', $data_cuti['jumlah']);
			$document->setValue('f_jumlah_terbilang', trim(nilai_terbilang($data_cuti['jumlah'])));
			$document->setValue('f_tanggalnya', $f_tanggalnya);			// rtrim($f_tanggalnya, ', '));
			$document->setValue('f_jumlah_sisa_awal', $sisa_cuti);
			$document->setValue('f_jumlah_sisa', $sisa_cuti_diambil);
			$document->setValue('f_alamat', $data_cuti['alamat']);
			$document->setValue('f_telpon', $data_cuti['telpon']);

			$document->setValue('f_jumlah1', $f_jumlah1);
			$document->setValue('f_jumlah2', $f_jumlah2);
			$document->setValue('f_jumlah3', $f_jumlah3);
			$document->setValue('f_jumlah4', $f_jumlah4);
			$document->setValue('f_jumlah5', $f_jumlah5);

			$document->setValue('f_txtCheck2', $txtCheck2);
			$document->setValue('f_txtCheck3', $txtCheck3);
			$document->setValue('f_txtCheck4', $txtCheck4);
			$document->setValue('f_txtCheck5', $txtCheck5);
			$document->setValue('f_statusCheck2', $statusCheck2);
			$document->setValue('f_statusCheck3', $statusCheck3);
			$document->setValue('f_statusCheck4', $statusCheck4);
			$document->setValue('f_statusCheck5', $statusCheck5);
			// $document->setValue('f_txtCheck2_ver', $txtCheck2_ver);
			// $document->setValue('f_txtCheck3_ver', $txtCheck3_ver);
			// $document->setValue('f_txtCheck4_ver', $txtCheck4_ver);
			// $document->setValue('f_txtCheck5_ver', $txtCheck5_ver);
			// $document->setValue('f_statusCheck2_ver', $statusCheck2_ver);
			// $document->setValue('f_statusCheck3_ver', $statusCheck3_ver);
			// $document->setValue('f_statusCheck4_ver', $statusCheck4_ver);
			// $document->setValue('f_statusCheck5_ver', $statusCheck5_ver);
			$document->setValue('f_nip_pejabat', $data_cuti['nip_pimpinan']);
			$document->setValue('f_nama_pejabat', $data_cuti['nama_pimpinan']);
			$document->setValue('f_jabatan_pejabat', $data_cuti['jabatan_name_pimpinan']);
			$document->setValue('f_respon_time', tanggal(substr($data_cuti['respon_time_pimpinan'],0,10),1));
			// $document->setValue('f_nip_ver', $data_cuti['nip_ver']);
			// $document->setValue('f_nama_ver', $data_cuti['pegawai_ver_name']);
			// $document->setValue('f_jabatan_ver', $data_cuti['jabatan_ver_name']);
			// $spesimen_tte_pegawai = return_files_path_by($data_cuti['pegawai_id'], 6);
			$spesimen_tte_pegawai = create_new_qrcode('{"Nomor":"'.$f_nomor_surat.'","Dokumen":"'.$data_cuti['jenis_cuti_name'].' ('.$data_cuti['jenis_cuti_name_alt'].')","Nama":"'.$data_cuti['nama'].'","Jabatan":"'.$data_cuti['jabatan_name'].'","Unit-Kerja":"'.$data_cuti['unit_kerja_name'].'"}', WRITEPATH.'uploads/tmp_qrcode_'.$data_cuti['pegawai_id'].date('YmdHis').'.png');
			switch ($level) {
				case 2:
					if($spesimen_tte_pegawai<>''){
						$document->setImageValue('ttdPegawai', array('path'=>$spesimen_tte_pegawai,'width'=>55,'height'=>55,'ratio'=>false,'positioning'=>'relative','marginTop'=>0,'marginLeft'=>0));
					}
					$document->setValue('f_footer_text', '');
					break;
				case 3:
					if($spesimen_tte_pegawai<>''){
						$document->setImageValue('ttdPegawai', array('path'=>$spesimen_tte_pegawai,'width'=>55,'height'=>55,'ratio'=>false,'positioning'=>'relative','marginTop'=>0,'marginLeft'=>0));
					}
					$options = return_value_in_options('kepegawaian');
					if(array_keys(['','true'],$options['esign_cuti']) && $test==false)
					{
						$document->setValue('f_footer_text', '');
					}else{
						$document->setValue('f_footer_text', '');
						$unix_id = $data_cuti['unix_id'];
						$url = $options['url_verifikasi'] . $unix_id .'&opt=cuti';
						$qr_path = WRITEPATH.'temp_zip/qr_cuti_'.$unix_id.'.png';
						$image_qrcode = create_new_qrcode($url, $qr_path, true);
						$document->setImageValue('#', array('path'=>$image_qrcode,'width'=>60,'height'=>60,'ratio'=>false,'positioning'=>'inline','marginTop'=>0,'marginLeft'=>0));
					}
					break;
				case 1:
					if($spesimen_tte_pegawai<>''){
						$document->setImageValue('ttdPegawai', array('path'=>$spesimen_tte_pegawai,'width'=>55,'height'=>55,'ratio'=>false,'positioning'=>'relative','marginTop'=>0,'marginLeft'=>0));
					}
					$document->setValue('f_footer_text', '');
					break;
				default:
					$document->setValue('f_footer_text', '');
					break;
			}
			$pathfile_replace = WRITEPATH.'files_cuti/'.$data_cuti['id'].'_'.date('YmdHis').'_pemohon'.'.docx';
			if($data_cuti['status'] > 1)
			{
				$pathfile_replace = WRITEPATH.'files_cuti/'.$data_cuti['id'].'_'.date('YmdHis').'_pimpinan'.'.docx';
			}
			$document->saveAs($pathfile_replace);
			ob_clean();
			// EXPORT PDF USING LIBRE/OPENOFFICE
			$path_file_new = str_replace(['.docx', '.DOCX'], '.pdf', $pathfile_replace);
			putenv('PATH=/usr/local/bin:/bin:/usr/bin:/usr/local/sbin:/usr/sbin:/sbin');
			putenv('HOME=/tmp');
			$txt_shell = 'soffice --headless --convert-to pdf --outdir "'.WRITEPATH.'files_cuti/" '.$pathfile_replace.' ';
			$shell_exec = shell_exec($txt_shell);
			if($shell_exec==NULL)
			{
				$shell_exec = 'gagal membuat pdf';
			}else{
				switch ($level) {
					case 2:
						// $this->db->table('cuti_verify')->update(['f_path'=>$path_file_new], ['id'=>$data_cuti['id']]);
						break;
					case 3:
						$this->cuti_approved_save(['path'=>$path_file_new], ['id'=>$data_cuti['id']]);
						break;
					default:
						$this->cuti_save(['path'=>$path_file_new], ['id'=>$data_cuti['id']]);
						break;
				}
			}
			$this->db->table('aaa')->insert(['content'=>$txt_shell .' <===||===> '. $shell_exec]);
			if(array_keys([1,2,3,4,5,6],$data_cuti['status']))
			{
				@unlink($data_cuti['path']);
			}else{
				@unlink($image_qrcode);
			}
			@unlink($pathfile_replace);
			@unlink($spesimen_tte_pegawai);
		}
		return $path_file_new;
	}



	/*
	*	MEMBUAT PENOMORAN NASKAH
	*/
	function create_nomor_surat($id, $instansi, $review=false)
	{
		$data_cuti = $this->cuti_get_row($id);
		if(!empty($data_cuti))
		{
			$type = 'C';
			$tanggal = ($this->return_tanggal_awal_cuti_by_id($data_cuti['id']))?:date('Y-m-d');
			$unit = $data_cuti['unit_kerja_id_pimpinan'];
			$reff = map_satker_to_nomor_naskah($unit);
			if(array_keys([1,2], $data_cuti['status_pns']) && array_keys([5], $data_cuti['unit_kerja_id'])){
				$type = 'CT';
				$reff = 'NP';
			}
			$xDate = explode('-', $tanggal);
			$tgl = $xDate[2];
			$bln = $xDate[1];
			$thn = $xDate[0];
			$nomor_terakhir = 0;
			$nomor_mundur_baru = '';
			if($tanggal < date('Y-m-d'))
			{
				// CHECK NOMOR MUNDUR
				$abjad = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
				$nm = $this->nomor_surat_terakhir_mundur($thn, $tanggal, $reff, $type, $instansi);
				$nomor_terakhir_live = $this->nomor_surat_terakhir_live($thn, $reff, $type, $instansi);
				switch (true) {
					case $tanggal >= $nomor_terakhir_live['tanggal']:
						$nomor_baru = $nomor_terakhir_live['nomor'] + 1;
						$nomor_baru_ = $nomor_baru;
						if(strlen($nomor_baru)==1){$nomor_baru_ = "00$nomor_baru";}else{if(strlen($nomor_baru)==2){$nomor_baru_= "0".$nomor_baru;}}
						$nomor_baru_ = $nomor_baru_.$nomor_mundur_baru;
						break;
					default:
						$abjad_terakhir = $nm['abjad2'];
						$abjad_pos = array_search($abjad_terakhir, $abjad);
						$abjadKey = 0;
						if($abjad_terakhir <> '' /*!array_keys([''], $abjad_pos)*/){
							$abjadKey = intval($abjad_pos) + 1 ;
						}
						$nomor_mundur_baru = $abjad[$abjadKey];
						$nomor_terakhir = $nm['nomor'];
						$nomor_baru = $nomor_terakhir;
						$nomor_baru_ = $nomor_baru;
						if(strlen($nomor_baru)==1){$nomor_baru_ = "00$nomor_baru";}else{if(strlen($nomor_baru)==2){$nomor_baru_= "0".$nomor_baru;}}
						$nomor_baru_ = $nomor_baru_.$nomor_mundur_baru;
						break;
				}
			}else{
				// NOMOR LANJUT
				$nomor_terakhir_live = $this->nomor_surat_terakhir_live($thn, $reff, $type, $instansi);
				$nomor_baru = $nomor_terakhir_live['nomor'] + 1;
				$nomor_baru_ = $nomor_baru;
				if(strlen($nomor_baru)==1){$nomor_baru_ = "00$nomor_baru";}else{if(strlen($nomor_baru)==2){$nomor_baru_= "0".$nomor_baru;}}
				$nomor_baru_ = $nomor_baru_.$nomor_mundur_baru;
			}
			switch (true) {
				case $data_cuti['jabatan_id_pimpinan']==1:
					$nomor_baru_lengkap = $type.'-'.$nomor_baru_.'/'.$reff.'/KP.05.02/'.$bln.'/'.$thn;
					break;
				case array_keys([2,3,4,10], $data_cuti['jabatan_id_pimpinan']):
					$nomor_baru_lengkap = $type.'-'.$nomor_baru_.'/'.$instansi.'/'.$reff.'/KP.05.02/'.$bln.'/'.$thn;
					break;
				default:
					// $nomor_baru_lengkap = 'Internal';
					// $nomor_baru = 0;
					$nomor_baru_lengkap = $type.'-'.$nomor_baru_.'/'.$instansi.'/'.$reff.'/KP.05.02/'.$bln.'/'.$thn;
					break;
			}
			if($review==false)
			{
				// SAVE NOMOR SURAT
				$this->cuti_save([
					'nomor' => $nomor_baru
					, 'nomor_mundur' => $nomor_mundur_baru
					, 'instansi' => $instansi
					, 'satker' => $reff
					, 'type' => $type
					, 'hari' => $tgl
					, 'bulan' => $bln
					, 'tahun' => $thn
					, 'tanggal_lengkap' => $thn.'-'.$bln.'-'.$tgl
					, 'nomor_surat' => $nomor_baru_lengkap
				],[
					'id'=>$data_cuti['id']
				]);
				return $nomor_baru_lengkap;
			}else{
				return 'TEST-'.$nomor_baru_lengkap;
			}
		}
	}


	function nomor_surat_terakhir_mundur($thn, $date, $reff, $type='M', $instansi='BKP')
	{
		$rs = 0;
		$rsa = '';
		$rsa2 = '';
		$tgl = '';
		$q = $this->db->query("SELECT ifnull(tsn.nomor,0) no, tsn.nomor_mundur as no_mundur, (SELECT nomor_mundur from cuti 
						where instansi=tsn.instansi 
							and type=tsn.type 
							and satker=tsn.satker 
							and tanggal_lengkap=tsn.tanggal_lengkap 
							order by nomor_mundur DESC limit 1
					) as no_mundur2, tsn.tanggal_lengkap as tanggal
				FROM cuti tsn
				WHERE tsn.tanggal_lengkap <= ? and tsn.instansi = ? and tsn.type = ? and tsn.satker = ? and tsn.status > ? and tsn.tahun = ? 
				order by tsn.nomor desc limit 1 ",
				[$date, $instansi, $type, $reff, 0, $thn])->getRow();
		if(!empty($q))
		{
			$rs = $q->no;
			$rsa = $q->no_mundur;
			$rsa2 = $q->no_mundur2;
			$tgl = $q->tanggal;
		}else{
			$nomor_terakhir_memo = return_value_in_options('persuratan');
			// $nomor_terakhir_memo = json_decode(return_value_in_options('persuratan'),true);
			// testaaa(json_encode($nomor_terakhir_memo));
			$rs = $nomor_terakhir_memo[$reff][$type]+1;
		}
		return ['nomor'=>$rs, 'abjad'=>$rsa, 'abjad2'=>$rsa2, 'tanggal'=>$tgl];
	}

	function nomor_surat_terakhir_live($year, $reff, $type='M', $instansi='BKP')
	{
		$nomor_terakhir_memo = return_value_in_options('persuratan');
		// $nomor_terakhir_memo = json_decode(return_value_in_options('persuratan'),true);
		// testaaa(json_encode($nomor_terakhir_memo));
		$nomor_terakhirnya = $nomor_terakhir_memo[$reff][$type];
		$rs = 0;
		$tgl = '';
		$q = $this->db->query("SELECT ifnull(nomor,0) as field, tanggal_lengkap as tanggal, hari, bulan, tahun, type
				from cuti 
				where satker=? and type=? and instansi=? and tahun=? and status > ?
					order by nomor desc
					limit 1 ", 
				[$reff, $type, $instansi, $year, 0])->getRow();
		if(!empty($q))
		{
			$rs = $q->field;
			$tgl = $q->tanggal;
		}
		if($nomor_terakhirnya > 0 && $nomor_terakhirnya > $rs){
			$rs = $nomor_terakhirnya;
		}
		return ['nomor'=>$rs, 'tanggal'=>$tgl];
	}
}