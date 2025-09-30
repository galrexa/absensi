<?php 
namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Tools;

class PersuratanModel extends Model
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

	function surat_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('surat')->update($data_, $where);
            $id = $this->db->affectedRows();
        }else{
            $this->db->table('surat')->insert($data_);
            $id = $this->db->insertID();
        }
        return $id;
	}
	function surat_delete($where=[])
	{
		$id = 0;
		if(!empty($where))
        {
        	$check = $this->db->table('surat')->getWhere($where)->getRow();
        	if(!empty($check)){
	            $this->db->table('surat')->delete($where);
	            $id = $this->db->affectedRows();
	            @unlink($check->path);
	            @unlink($check->path_sign);
	            files_delete_by_id($check->lampiran);
	        }   
        }
        return $id;
	}

	function surat_penerima_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('surat_penerima')->update($data_, $where);
            $id = $this->db->affectedRows();
        }else{
            $this->db->table('surat_penerima')->insert($data_);
            $id = $this->db->affectedRows();
        }
        return $id;
	}
	function surat_penerima_delete($where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('surat_penerima')->delete($where);
            $id = $this->db->affectedRows();
        }
        return $id;
	}
	function surat_penerima_getWhere_row($where=[])
	{
		$rs = [];
		if(!empty($where))
        {
            $rs = $this->db->table('surat_penerima')->getWhere($where)->getRow();
        }
        return $rs;
	}
	function penerima_result_by_surat_id($id)
	{
		return $this->db->query("SELECT a.surat_id, a.id, a.pegawai_id, b.nik, b.nip, b.nama, b.gelar_depan, b.gelar_belakang, a.jabatan_id, c.jabatan_name, a.unit_kerja_id, d.unit_kerja_name, d.unit_kerja_name_alt, a.catatan, a.status, a.sent, a.sent_time, a.read, a.read_time, a.respon, a.respon_time, a.asal
				, ifnull(case when a.sent=1 then 'Ya' else 'Tidak' end, 'Tidak') as diteruskan
			FROM surat_penerima a
				left join pegawai b on b.pegawai_id=a.pegawai_id
				left join ms_jabatan c on c.jabatan_id=a.jabatan_id
				left join ms_unit_kerja d on d.unit_kerja_id=a.unit_kerja_id
			where a.surat_id=? order by a.id asc ", [$id])->getResult();
	}
	function penerima_row_by_surat_id_and_pegawai_id($id, $pegawai_id)
	{
		return $this->db->query("SELECT a.surat_id, a.id, a.pegawai_id, b.nik, b.nip, b.nama, b.gelar_depan, b.gelar_belakang, a.jabatan_id, c.jabatan_name, a.unit_kerja_id, d.unit_kerja_name, d.unit_kerja_name_alt, a.catatan, a.status, a.sent, a.sent_time, a.read, a.read_time, a.respon, a.respon_time, a.asal
				, ifnull(case when a.sent=1 then 'Ya' else 'Tidak' end, 'Tidak') as diteruskan
			FROM surat_penerima a
				left join pegawai b on b.pegawai_id=a.pegawai_id
				left join ms_jabatan c on c.jabatan_id=a.jabatan_id
				left join ms_unit_kerja d on d.unit_kerja_id=a.unit_kerja_id
			where a.surat_id=? and a.pegawai_id=? order by a.id asc ", [$id, $pegawai_id])->getRow();
	}

	function surat_tembusan_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('surat_tembusan')->update($data_, $where);
            $id = $this->db->affectedRows();
        }else{
            $this->db->table('surat_tembusan')->insert($data_);
            $id = $this->db->affectedRows();
        }
        return $id;
	}
	function surat_tembusan_delete($where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('surat_tembusan')->delete($where);
            $id = $this->db->affectedRows();
        }
        return $id;
	}
	function tembusan_result_by_surat_id($id)
	{
		return $this->db->query("SELECT a.surat_id, a.id, a.pegawai_id, b.nik, b.nip, b.nama, b.gelar_depan, b.gelar_belakang, a.jabatan_id, c.jabatan_name, a.unit_kerja_id, d.unit_kerja_name, d.unit_kerja_name_alt, a.status, a.sent_time, a.read, a.read_time, a.asal 
			FROM surat_tembusan a
				left join pegawai b on b.pegawai_id=a.pegawai_id
				left join ms_jabatan c on c.jabatan_id=a.jabatan_id
				left join ms_unit_kerja d on d.unit_kerja_id=a.unit_kerja_id
			where a.surat_id=? order by a.id asc ", [$id])->getResult();
	}

	function surat_reviewer_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('surat_reviewer')->update($data_, $where);
            $id = $this->db->affectedRows();
        }else{
            $this->db->table('surat_reviewer')->insert($data_);
            $id = $this->db->affectedRows();
        }
        return $id;
	}
	function surat_reviewer_delete($where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('surat_reviewer')->delete($where);
            $id = $this->db->affectedRows();
        }
        return $id;
	}

	function surat_signer_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('surat_signer')->update($data_, $where);
            $id = $this->db->affectedRows();
        }else{
            $this->db->table('surat_signer')->insert($data_);
            $id = $this->db->affectedRows();
        }
        return $id;
	}
	function surat_signer_delete($where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('surat_signer')->delete($where);
            $id = $this->db->affectedRows();
        }
        return $id;
	}

	/*
	*	surat_tindaklanjut
	*/
	function surat_tindaklanjut_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('surat_tindaklanjut')->update($data_, $where);
            $id = $this->db->affectedRows();
        }else{
            $this->db->table('surat_tindaklanjut')->insert($data_);
            $id = $this->db->insertID();
        }
        return $id;
	}
	function surat_tindaklanjut_delete($where=[])
	{
		$id = 0;
		if(!empty($where))
        {
        	$check = $this->db->table('surat_tindaklanjut')->getWhere($where)->getRow();
        	if(!empty($check)){
	            $this->db->table('surat_tindaklanjut')->delete($where);
	            $id = $this->db->affectedRows();
	            @unlink($check->path);
	            @unlink($check->path_sign);
	            files_delete_by_id($check->lampiran);
	        }   
        }
        return $id;
	}
	function surat_tindaklanjut_row_by_id($id)
	{
		return $this->db->query("SELECT a.surat_id, a.id, a.ref_id, a.pengirim_id, b.nip as pengirim_nip, b.nik as pengirim_nik, b.nama as pengirim_nama, a.pengirim_jabatan, c.jabatan_name as pengirim_jabatan_name, a.pengirim_unit, concat(d.unit_kerja_name_alt, ' (', d.unit_kerja_name, ')') as pengirim_unit_name, a.value, a.catatan, a.tanggal, a.tanggal_akhir, a.sent_time, a.status, case when a.status=1 then 'Disposisi' when a.status=2 then 'Tindak lanjut' else '-' end as status_name, a.penerima_id, e.nip as penerima_nip, e.nik as penerima_nik, e.nama as penerima_nama, a.penerima_jabatan, f.jabatan_name as penerima_jabatan_name, a.penerima_unit, concat(g.unit_kerja_name_alt, ' (', g.unit_kerja_name, ')') as penerima_unit_name, a.lampiran, a.read, a.read_time, a.respon, a.respon_time, a.create_at, a.create_by , h.username as create_by_name
			FROM surat_tindaklanjut a
				left join pegawai b on b.pegawai_id=a.pengirim_id
				left join ms_jabatan c on c.jabatan_id=a.pengirim_jabatan
				left join ms_unit_kerja d on d.unit_kerja_id=a.pengirim_unit
				left join pegawai e on e.pegawai_id=a.penerima_id
				left join ms_jabatan f on f.jabatan_id=a.penerima_jabatan
				left join ms_unit_kerja g on g.unit_kerja_id=a.penerima_unit
				left join app_users h on h.id=a.create_by
			WHERE a.id=?
			", [$id])->getRow();
	}
	function surat_tindaklanjut_result_by_surat_id_and_ref_id($id, $ref)
	{
		return $this->db->query("SELECT a.surat_id, a.id, a.ref_id, a.pengirim_id, b.nip as pengirim_nip, b.nik as pengirim_nik, b.nama as pengirim_nama, a.pengirim_jabatan, c.jabatan_name as pengirim_jabatan_name, a.pengirim_unit, concat(d.unit_kerja_name_alt, ' (', d.unit_kerja_name, ')') as pengirim_unit_name, a.value, a.catatan, a.tanggal, a.tanggal_akhir, a.sent_time, a.status, case when a.status=1 then 'Disposisi' when a.status=2 then 'Tindak lanjut' else '-' end as status_name, a.penerima_id, e.nip as penerima_nip, e.nik as penerima_nik, e.nama as penerima_nama, a.penerima_jabatan, f.jabatan_name as penerima_jabatan_name, a.penerima_unit, concat(g.unit_kerja_name_alt, ' (', g.unit_kerja_name, ')') as penerima_unit_name, a.lampiran, a.read, a.read_time, a.respon, a.respon_time, a.create_at, a.create_by , h.username as create_by_name
			FROM surat_tindaklanjut a
				left join pegawai b on b.pegawai_id=a.pengirim_id
				left join ms_jabatan c on c.jabatan_id=a.pengirim_jabatan
				left join ms_unit_kerja d on d.unit_kerja_id=a.pengirim_unit
				left join pegawai e on e.pegawai_id=a.penerima_id
				left join ms_jabatan f on f.jabatan_id=a.penerima_jabatan
				left join ms_unit_kerja g on g.unit_kerja_id=a.penerima_unit
				left join app_users h on h.id=a.create_by
			WHERE a.surat_id=? and a.ref_id=? order by a.id asc
			", [$id, $ref])->getResult();
	}

	function surat_pelaksana_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('surat_pelaksana')->update($data_, $where);
            $id = $this->db->affectedRows();
        }else{
            $this->db->table('surat_pelaksana')->insert($data_);
            $id = $this->db->affectedRows();
        }
        return $id;
	}
	function surat_pelaksana_delete($where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('surat_pelaksana')->delete($where);
            $id = $this->db->affectedRows();
        }
        return $id;
	}

	function surat_in_tu_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('surat_in_tu')->update($data_, $where);
            $id = $this->db->affectedRows();
        }else{
            $this->db->table('surat_in_tu')->insert($data_);
            $id = $this->db->affectedRows();
        }
        return $id;
	}
	function surat_in_tu_delete($where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('surat_in_tu')->delete($where);
            $id = $this->db->affectedRows();
        }
        return $id;
	}

	function surat_trace_save($data_, $where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('surat_trace')->update($data_, $where);
            $id = $this->db->affectedRows();
        }else{
            $this->db->table('surat_trace')->insert($data_);
            $id = $this->db->affectedRows();
        }
        return $id;
	}
	function surat_trace_delete($where=[])
	{
		$id = 0;
		if(!empty($where))
        {
            $this->db->table('surat_trace')->delete($where);
            $id = $this->db->affectedRows();
        }
        return $id;
	}

	/*
	*	get row data surat
	*/
	function surat_get_row($id, $pegawai_id=0)
	{
		$where = "";
		$whereValue = [];
		if($pegawai_id>0)
		{
			$where .= "
				left join (select j.*, Row_number() 
			              OVER ( 
			                partition BY j.surat_id 
			                ORDER BY j.sent_time DESC ) AS seqnum_j
					from surat_tindaklanjut j) j on j.status=1 and j.surat_id=a.id and j.penerima_id=?
				left join (select k.*, Row_number() 
			              OVER ( 
			                partition BY k.surat_id 
			                ORDER BY k.sent_time DESC ) AS seqnum_k 
					from surat_tembusan k) k on k.status=1 and k.surat_id=a.id and k.pegawai_id=?
				left join (select l.*, Row_number() 
			              OVER ( 
			                partition BY l.surat_id 
			                ORDER BY l.sent_time DESC ) AS seqnum_l 
					from surat_penerima l) l on l.status in (1,2) and sent=1 and l.surat_id=a.id and l.pegawai_id=?
				left join (select m.*, Row_number() 
			              OVER ( 
			                partition BY m.surat_id 
			                ORDER BY m.sent_time DESC ) AS seqnum_m 
					from surat_pelaksana m) m on m.status=1 and m.surat_id=a.id and m.ref_type in ('plt','plh') and m.ref_name in ('surat_penerima', 'surat_tembusan', 'surat_tindaklanjut') and m.pegawai_id=?
			WHERE a.id=?
			";
			array_push($whereValue, $pegawai_id);
			array_push($whereValue, $pegawai_id);
			array_push($whereValue, $pegawai_id);
			array_push($whereValue, $pegawai_id);
			array_push($whereValue, $id);
		}else{
			$where .= "
				left join (select j.*, Row_number() 
			              OVER ( 
			                partition BY j.surat_id 
			                ORDER BY j.sent_time DESC ) AS seqnum_j
					from surat_tindaklanjut j) j on j.status=1 and j.surat_id=a.id
				left join (select k.*, Row_number() 
			              OVER ( 
			                partition BY k.surat_id 
			                ORDER BY k.sent_time DESC ) AS seqnum_k 
					from surat_tembusan k) k on k.status=1 and k.surat_id=a.id
				left join (select l.*, Row_number() 
			              OVER ( 
			                partition BY l.surat_id 
			                ORDER BY l.sent_time DESC ) AS seqnum_l 
					from surat_penerima l) l on l.status in (1,2) and sent=1 and l.surat_id=a.id
				left join (select m.*, Row_number() 
			              OVER ( 
			                partition BY m.surat_id 
			                ORDER BY m.sent_time DESC ) AS seqnum_m 
					from surat_pelaksana m) m on m.status=1 and m.surat_id=a.id and m.ref_type in ('plt','plh') and m.ref_name in ('surat_penerima', 'surat_tembusan', 'surat_tindaklanjut')
			WHERE a.id=?
			";
			array_push($whereValue, $id);
		}
		$q_rs = $this->db->query("SELECT 
				a.id, 
				a.sumber_ext, 
				a.sumber_bentuk, 
				ifnull(b.ref_name, '-') as sumber_bentuk_name, 
				a.register_number, 
				a.register_time, 
				a.draf_for, 
				ifnull(c.ref_name, '-') as draf_for_name, 
				a.draf_ref_id, 
				a.draf_type, 
				ifnull(d.ref_name, '-') as draf_type_name, 
				a.jenis, 
				ifnull(e.ref_name, '-') as jenis_name, 
				a.sifat, 
				ifnull(f.ref_name, '-') as sifat_name, 
				a.urgensi,
				ifnull(g.ref_name, '-') as urgensi_name,  
				a.nomor, 
				a.tanggal, 
				a.hal, 
				a.status,
				case when a.sumber_ext=1 then ifnull(h.ref_name, '-') else ifnull(i.ref_name, '-') end as status_name,
				a.signer_opt, a.signer_alt, a.signer_show, a.pengirim, a.pengirim_id, a.pengirim_satker, a.pengirim_jabatan, a.pengirim_show, a.pengirim_alamat, a.penerima_sebagai, a.penerima_show, a.penerima_alamat, a.penerima_pada_lampiran, a.penerima_keterangan_lampiran, a.kka, a.sub_kka, a.sub_sub_kka, a.catatan, a.respon, a.no, a.no_mundur, a.no_type, a.no_instansi, a.no_reff, a.no_date, a.no_month, a.no_year, a.no_tanggal, a.no_status, a.contents, a.path, a.path_sign, a.path_sign_name,a.lampiran, a.create_by, a.update_by, a.update_at, a.last_change, a.unix_id
				, case when j.id is not null then 
						j.sent_time
					else 
						case when k.id is not null then 
							k.sent_time
						else 
							case when l.id is not null then 
								l.sent_time 
							else 
								case when m.surat_id is not null then 
									m.sent_time
								else 
									''
								end
							end
						end
					end as sent_time
				, case when j.read is not null then 
						case when j.read > 0  then 1 else 0 end
					else 
						case when k.read is not null then 
							case when k.read > 0 then 1 else 0 end
						else 
							case when l.read is not null then 
								case when l.read > 0 then 1 else 0 end 
							else 
								case when m.read is not null then 
									case when m.read > 0 then 1 else 0 end
								else 
									0 
								end
							end
						end
					end as read_user
				, case when j.respon is not null then 
						case when j.respon > 0  then 1 else 0 end
					else 
						case when k.read is not null then 
							case when k.read > 0 then 1 else 0 end
						else 
							case when l.respon is not null then 
								case when l.respon > 0 then 1 else 0 end 
							else 
								case when m.respon is not null then 
									case when m.respon > 0 then 1 else 0 end
								else 
									0 
								end
							end
						end
					end as respon_user
				, case when j.respon is not null then 
						case when j.respon IN (0,1)  then concat('tindaklanjut#', j.id) else 0 end
					else 
						case when k.read is not null then 
							case when k.read IN (0,1) then concat('tembusan#', k.id) else 0 end
						else 
							case when l.respon is not null then 
								case when l.respon IN (0,1) then concat('penerima#', l.id) else 0 end 
							else 
								case when m.respon is not null then 
									case when m.respon IN (0,1) then concat('pelaksana#', m.surat_id,m.ref_type,m.ref_name,m.ref_id,m.pegawai_id) else 0 end
								else 
									0 
								end
							end
						end
					end as pos_id
				, case 
						when (k.id is not null and j.id is not null) THEN 'Tembusan & Disposisi'
						when (l.id is not null and j.id is not null) THEN 'Surat Masuk & Disposisi'
						when j.id is not null then 'Disposisi'
						when k.id is not null then 'Tembusan'
						when (l.id is not null) then 'Surat Masuk'
						when (m.surat_id is not null and m.ref_name='surat_tembusan') then 'Tembusan (Plt/Plh. Kaset)'
						when (m.surat_id is not null and m.ref_name='surat_penerima') then 'Surat Masuk (Plt/Plh. Kaset)'
						when (m.surat_id is not null and m.ref_name='surat_tindaklanjut') then 'Disposisi (Plt/Plh. Kaset)'
					end as sebagai
			FROM surat a
				left join app_referensi b on b.ref='surat_bentuk' and b.ref_code=a.sumber_bentuk
				left join app_referensi c on c.ref='surat_create' and c.ref_code=a.draf_for
				left join app_referensi d on d.ref='surat_drafting' and d.ref_code=a.draf_type
				left join app_referensi e on e.ref='surat_jenis' and e.ref_code=a.jenis
				left join app_referensi f on f.ref='surat_sifat' and f.ref_code=a.sifat
				left join app_referensi g on g.ref='surat_urgensi' and g.ref_code=a.urgensi
				left join app_referensi h on h.ref='surat_status_ext' and h.ref_code=a.status
				left join app_referensi i on i.ref='surat_status_in' and i.ref_code=a.status 
			".$where, $whereValue)->getRow();
    	$q = [];
    	if (!empty($q_rs)){
    		$array_penerima = [];
    		$penerima_show = 0;
    		$list_penerima = $this->penerima_result_by_surat_id($q_rs->id);
    		if(!empty($list_penerima)){
    			foreach($list_penerima as $k)
    				array_push($array_penerima, $k);
    				$penerima_show = $k->view;
    		}
    		$array_tembusan = [];
    		$tembusan_show = 0;
    		$list_tembusan = $this->tembusan_result_by_surat_id($q_rs->id);
    		if(!empty($list_tembusan)){
    			foreach($list_tembusan as $k)
    				array_push($array_tembusan, $k);
    				$tembusan_show = $k->view;
    		}
		    $surat['id'] = $q_rs->id;
		    $surat['hash'] = string_to($q_rs->id, 'encode');
			$surat['sumber_ext'] = $q_rs->sumber_ext;
			$surat['sumber_bentuk'] = $q_rs->sumber_bentuk;
			$surat['sumber_bentuk_name'] = $q_rs->sumber_bentuk_name;
			$surat['register_number'] = $q_rs->register_number;
			$surat['register_time'] = $q_rs->register_time;
			$surat['draf_for'] = $q_rs->draf_for;
			$surat['draf_for_name'] = $q_rs->draf_for_name;
			$surat['draf_ref_id'] = $q_rs->draf_ref_id;
			$surat['draf_type'] = $q_rs->draf_type;
			$surat['draf_type_name'] = $q_rs->draf_type_name;
			$surat['jenis'] = $q_rs->jenis;
			$surat['jenis_name'] = $q_rs->jenis_name;
			$surat['sifat'] = $q_rs->sifat;
			$surat['sifat_name'] = $q_rs->sifat_name;
			$surat['urgensi'] = $q_rs->urgensi;
			$surat['urgensi_name'] = $q_rs->urgensi_name;
			$surat['nomor'] = $q_rs->nomor;
			$surat['tanggal'] = $q_rs->tanggal;
			$surat['hal'] = $q_rs->hal;
			$surat['status'] = $q_rs->status;
			$surat['status_name'] = $q_rs->status_name;
			$surat['signer_opt'] = $q_rs->signer_opt;
			$surat['signer_alt'] = $q_rs->signer_alt;
			$surat['signer_show'] = $q_rs->signer_show;
			$surat['pengirim'] = $q_rs->pengirim;
			$surat['pengirim_id'] = $q_rs->pengirim_id;
			$surat['pengirim_satker'] = $q_rs->pengirim_satker;
			$surat['pengirim_jabatan'] = $q_rs->pengirim_jabatan;
			$surat['pengirim_show'] = $q_rs->pengirim_show;
			$surat['pengirim_alamat'] = $q_rs->pengirim_alamat;
			$surat['penerima'] = $array_penerima;
			$surat['penerima_sebagai'] = $q_rs->penerima_sebagai;
			$surat['penerima_show'] = $penerima_show;
			$surat['penerima_alamat'] = $q_rs->penerima_alamat;
			$surat['penerima_pada_lampiran'] = $q_rs->penerima_pada_lampiran;
			$surat['penerima_keterangan_lampiran'] = $q_rs->penerima_keterangan_lampiran;
			$surat['tembusan'] = $array_tembusan;
			$surat['tembusan_show'] = $tembusan_show;
			$surat['kka'] = $q_rs->kka;
			$surat['sub_kka'] = $q_rs->sub_kka;
			$surat['sub_sub_kka'] = $q_rs->sub_sub_kka;
			$surat['catatan'] = $q_rs->catatan;
			$surat['respon'] = $q_rs->respon;
			$surat['no'] = $q_rs->no;
			$surat['no_mundur'] = $q_rs->no_mundur;
			$surat['no_type'] = $q_rs->no_type;
			$surat['no_instansi'] = $q_rs->no_instansi;
			$surat['no_reff'] = $q_rs->no_reff;
			$surat['no_date'] = $q_rs->no_date;
			$surat['no_month'] = $q_rs->no_month;
			$surat['no_year'] = $q_rs->no_year;
			$surat['no_tanggal'] = $q_rs->no_tanggal;
			$surat['no_status'] = $q_rs->no_status;
			$surat['contents'] = $q_rs->contents;
			$surat['path'] = $q_rs->path;
			$surat['path_sign'] = $q_rs->path_sign;
			$surat['path_sign_name'] = $q_rs->path_sign_name;
			$surat['lampiran'] = $q_rs->lampiran;
			$surat['create_by'] = $q_rs->create_by;
			$surat['update_by'] = $q_rs->update_by;
			$surat['update_at'] = $q_rs->update_at;
			$surat['last_change'] = $q_rs->last_change;
			$surat['unix_id'] = $q_rs->unix_id;
			$surat['sent_time'] = $q_rs->sent_time;
			$surat['read_user'] = $q_rs->read_user;
			$surat['respon_user'] = $q_rs->respon_user;
			$surat['pos_id'] = $q_rs->pos_id;
			$surat['sebagai'] = $q_rs->sebagai;
    		$q = $surat;
    	}
	    return $q;
	}


	/*
	* list inbox
	*/
	function inbox_datatable($data_=false)
	{
		// helper('toolshelp');
		$pegawai_id = string_to($this->request->getPost('pegawai_id'), 'decode');
		$check_filter = $this->request->getPost('check_filter');
		$status = $this->request->getPost('status');
		$tahun = $this->request->getPost('tahun');
		$bulan = $this->request->getPost('bulan');
		$jenis = $this->request->getPost('jenis');
		$sifat = $this->request->getPost('sifat');
		$urgensi = $this->request->getPost('urgensi');
		// $status_proses_kepeg = $this->request->getPost('status_proses_kepeg');
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 50);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'inbox'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " 1 ";
		array_push($arrWhere, $pegawai_id);
		array_push($arrWhere, $pegawai_id);
		array_push($arrWhere, $pegawai_id);
		array_push($arrWhere, $pegawai_id);
		$where .= " and (j.penerima_id=? or k.pegawai_id=? or l.pegawai_id=? or m.pegawai_id=?) ";
		array_push($arrWhere, $pegawai_id);
		array_push($arrWhere, $pegawai_id);
		array_push($arrWhere, $pegawai_id);
		array_push($arrWhere, $pegawai_id);
		if($search_value<>''){
			$where .= " AND (a.register_number like ? or a.nomor like ? or a.hal like ? or a.tanggal like ? or a.pengirim like ? or a.catatan like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		if($check_filter==1){
			if($tahun<>'' && $bulan<>''){
				$where .= " and (substr(a.register_time, 1,7)=? or substr(a.tanggal,1,7)=? ) ";
				array_push($arrWhere, $tahun.'-'.$bulan);
				array_push($arrWhere, $tahun.'-'.$bulan);
			}
		}
		if(!empty($jenis)){
			$where .= " and a.jenis in ? ";
			array_push($arrWhere, $jenis);
		}
		if(!empty($sifat)){
			$where .= " and a.sifat in ? ";
			array_push($arrWhere, $sifat);
		}
		if(!empty($urgensi)){
			$where .= " and a.urgensi in ? ";
			array_push($arrWhere, $urgensi);
		}
		if(!empty($status)){
			$where .= " and a.status in ? ";
			array_push($arrWhere, $status);
		}
		switch ($data_) {
			case true:
				$where .= " GROUP BY a.id ";
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q_rs = $this->db->query("SELECT a.id, a.sumber_ext, a.sumber_bentuk, ifnull(b.ref_name, '-') as sumber_bentuk_name, a.register_number, a.register_time, a.draf_for, ifnull(c.ref_name, '-') as draf_for_name, a.draf_ref_id, a.draf_type, ifnull(d.ref_name, '-') as draf_type_name, a.jenis, ifnull(e.ref_name, '-') as jenis_name, a.sifat, ifnull(f.ref_name, '-') as sifat_name, a.urgensi,ifnull(g.ref_name, '-') as urgensi_name,  a.nomor, a.tanggal, a.hal, a.status, case when a.sumber_ext=1 then ifnull(h.ref_name, '-') else ifnull(i.ref_name, '-') end as status_name, a.signer_opt, a.signer_alt, a.signer_show, a.pengirim, a.pengirim_id, a.pengirim_satker, a.pengirim_jabatan, a.pengirim_show, a.pengirim_alamat, a.penerima_sebagai, a.penerima_show, a.penerima_alamat, a.penerima_pada_lampiran, a.penerima_keterangan_lampiran, a.kka, a.sub_kka, a.sub_sub_kka, a.catatan, a.respon, a.create_by, a.no, a.no_mundur, a.no_type, a.no_instansi, a.no_reff, a.no_date, a.no_month, a.no_year, a.no_tanggal, a.no_status, a.contents, a.path, a.path_sign, a.lampiran, a.last_change, a.unix_id
					, case when j.id is not null then 
							j.sent_time
						else 
							case when k.id is not null then 
								k.sent_time
							else 
								case when l.id is not null then 
									l.sent_time 
								else 
									case when m.surat_id is not null then 
										m.sent_time
									else 
										''
									end
								end
							end
						end as sent_time
					, case when j.read is not null then 
							case when j.read > 0  then 1 else 0 end
						else 
							case when k.read is not null then 
								case when k.read > 0 then 1 else 0 end
							else 
								case when l.read is not null then 
									case when l.read > 0 then 1 else 0 end 
								else 
									case when m.read is not null then 
										case when m.read > 0 then 1 else 0 end
									else 
										0 
									end
								end
							end
						end as read_user
					, case when j.respon is not null then 
							case when j.respon > 0  then 1 else 0 end
						else 
							case when k.read is not null then 
								case when k.read > 0 then 1 else 0 end
							else 
								case when l.respon is not null then 
									case when l.respon > 0 then 1 else 0 end 
								else 
									case when m.respon is not null then 
										case when m.respon > 0 then 1 else 0 end
									else 
										0 
									end
								end
							end
						end as respon_user
					, case when j.respon is not null then 
							case when j.respon in (0,1)  then concat('tindaklanjut#', j.id) else 0 end
						else 
							case when k.read is not null then 
								case when k.read in (0,1) then concat('tembusan#', k.id) else 0 end
							else 
								case when l.respon is not null then 
									case when l.respon in (0,1) then concat('penerima#', l.id) else 0 end 
								else 
									case when m.respon is not null then 
										case when m.respon in (0,1) then concat('pelaksana#', m.surat_id,m.ref_type,m.ref_name,m.ref_id,m.pegawai_id) else 0 end
									else 
										0 
									end
								end
							end
						end as pos_id
					, case 
							when (k.id is not null and j.id is not null) THEN 'Tembusan & Disposisi'
							when (l.id is not null and j.id is not null) THEN 'Surat Masuk & Disposisi'
							when j.id is not null then 'Disposisi'
							when k.id is not null then 'Tembusan'
							when (l.id is not null) then 'Surat Masuk'
							when (m.surat_id is not null and m.ref_name='surat_tembusan') then 'Tembusan (Plt/Plh. Kaset)'
							when (m.surat_id is not null and m.ref_name='surat_penerima') then 'Surat Masuk (Plt/Plh. Kaset)'
							when (m.surat_id is not null and m.ref_name='surat_tindaklanjut') then 'Disposisi (Plt/Plh. Kaset)'
						end as sebagai
					FROM surat a
						left join app_referensi b on b.ref='surat_bentuk' and b.ref_code=a.sumber_bentuk
						left join app_referensi c on c.ref='surat_create' and c.ref_code=a.draf_for
						left join app_referensi d on d.ref='surat_drafting' and d.ref_code=a.draf_type
						left join app_referensi e on e.ref='surat_jenis' and e.ref_code=a.jenis
						left join app_referensi f on f.ref='surat_sifat' and f.ref_code=a.sifat
						left join app_referensi g on g.ref='surat_urgensi' and g.ref_code=a.urgensi
						left join app_referensi h on h.ref='surat_status_ext' and h.ref_code=a.status
						left join app_referensi i on i.ref='surat_status_in' and i.ref_code=a.status
						left join (select j.*, Row_number() 
					              OVER ( 
					                partition BY j.surat_id 
					                ORDER BY j.sent_time DESC ) AS seqnum_j
							from surat_tindaklanjut j) j on j.status=1 and j.surat_id=a.id and j.penerima_id=?
						left join (select k.*, Row_number() 
					              OVER ( 
					                partition BY k.surat_id 
					                ORDER BY k.sent_time DESC ) AS seqnum_k 
							from surat_tembusan k) k on k.status=1 and k.surat_id=a.id and k.pegawai_id=?
						left join (select l.*, Row_number() 
					              OVER ( 
					                partition BY l.surat_id 
					                ORDER BY l.sent_time DESC ) AS seqnum_l 
							from surat_penerima l) l on l.status in (1,2) and sent=1 and l.surat_id=a.id and l.pegawai_id=?
						left join (select m.*, Row_number() 
					              OVER ( 
					                partition BY m.surat_id 
					                ORDER BY m.sent_time DESC ) AS seqnum_m 
							from surat_pelaksana m) m on m.status=1 and m.surat_id=a.id and m.ref_type in ('plt','plh') and m.ref_name in ('surat_penerima', 'surat_tembusan', 'surat_tindaklanjut') and m.pegawai_id=?
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?,? ", $arrWhere)->getResult();
		    	$q = [];
		    	foreach ($q_rs as $key){
		    		$array_penerima = [];
		    		$penerima_show = 0;
		    		$list_penerima = $this->penerima_result_by_surat_id($key->id);
		    		if(!empty($list_penerima)){
		    			foreach($list_penerima as $k)
		    				array_push($array_penerima, $k);
		    				$penerima_show = $k->view;
		    		}
		    		$array_tembusan = [];
		    		$tembusan_show = 0;
		    		$list_tembusan = $this->tembusan_result_by_surat_id($key->id);
		    		if(!empty($list_tembusan)){
		    			foreach($list_tembusan as $k)
		    				array_push($array_tembusan, $k);
		    				$tembusan_show = $k->view;
		    		}
		    		$surat['id'] = $key->id;
		    		$surat['hash'] = string_to($key->id, 'encode');
					$surat['sumber_ext'] = $key->sumber_ext;
					$surat['sumber_bentuk'] = $key->sumber_bentuk;
					$surat['sumber_bentuk_name'] = $key->sumber_bentuk_name;
					$surat['register_number'] = $key->register_number;
					$surat['register_time'] = $key->register_time;
					$surat['draf_for'] = $key->draf_for;
					$surat['draf_for_name'] = $key->draf_for_name;
					$surat['draf_ref_id'] = $key->draf_ref_id;
					$surat['draf_type'] = $key->draf_type;
					$surat['draf_type_name'] = $key->draf_type_name;
					$surat['jenis'] = $key->jenis;
					$surat['jenis_name'] = $key->jenis_name;
					$surat['sifat'] = $key->sifat;
					$surat['sifat_name'] = $key->sifat_name;
					$surat['urgensi'] = $key->urgensi;
					$surat['urgensi_name'] = $key->urgensi_name;
					$surat['nomor'] = $key->nomor;
					$surat['tanggal'] = $key->tanggal;
					$surat['hal'] = $key->hal;
					$surat['status'] = $key->status;
					$surat['status_name'] = $key->status_name;
					$surat['signer_opt'] = $key->signer_opt;
					$surat['signer_alt'] = $key->signer_alt;
					$surat['signer_show'] = $key->signer_show;
					$surat['pengirim'] = $key->pengirim;
					$surat['pengirim_id'] = $key->pengirim_id;
					$surat['pengirim_satker'] = $key->pengirim_satker;
					$surat['pengirim_jabatan'] = $key->pengirim_jabatan;
					$surat['pengirim_show'] = $key->pengirim_show;
					$surat['pengirim_alamat'] = $key->pengirim_alamat;
					$surat['penerima'] = $array_penerima;
					$surat['penerima_sebagai'] = $key->penerima_sebagai;
					$surat['penerima_show'] = $penerima_show;
					$surat['penerima_alamat'] = $key->penerima_alamat;
					$surat['penerima_pada_lampiran'] = $key->penerima_pada_lampiran;
					$surat['penerima_keterangan_lampiran'] = $key->penerima_keterangan_lampiran;
					$surat['tembusan'] = $array_tembusan;
					$surat['tembusan_show'] = $tembusan_show;
					$surat['kka'] = $key->kka;
					$surat['sub_kka'] = $key->sub_kka;
					$surat['sub_sub_kka'] = $key->sub_sub_kka;
					$surat['catatan'] = $key->catatan;
					$surat['respon'] = $key->respon;
					$surat['create_by'] = $key->create_by;
					$surat['no'] = $key->no;
					$surat['no_mundur'] = $key->no_mundur;
					$surat['no_type'] = $key->no_type;
					$surat['no_instansi'] = $key->no_instansi;
					$surat['no_reff'] = $key->no_reff;
					$surat['no_date'] = $key->no_date;
					$surat['no_month'] = $key->no_month;
					$surat['no_year'] = $key->no_year;
					$surat['no_tanggal'] = $key->no_tanggal;
					$surat['no_status'] = $key->no_status;
					$surat['contents'] = $key->contents;
					$surat['path'] = $key->path;
					$surat['path_sign'] = $key->path_sign;
					$surat['lampiran'] = $key->lampiran;
					$surat['last_change'] = $key->last_change;
					$surat['unix_id'] = $key->unix_id;
					$surat['sent_time'] = $key->sent_time;
					$surat['read_user'] = $key->read_user;
					$surat['respon_user'] = $key->respon_user;
					$surat['pos_id'] = $key->pos_id;
					$surat['sebagai'] = $key->sebagai;
					// $surat['diteruskan'] = $key->diteruskan;
		    		array_push($q, $surat);
		    	}
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(a.id)) as field FROM surat a
						left join (select j.*, Row_number() 
					              OVER ( 
					                partition BY j.surat_id 
					                ORDER BY j.sent_time DESC ) AS seqnum_j
							from surat_tindaklanjut j) j on j.status=1 and j.surat_id=a.id and j.penerima_id=?
						left join (select k.*, Row_number() 
					              OVER ( 
					                partition BY k.surat_id 
					                ORDER BY k.sent_time DESC ) AS seqnum_k 
							from surat_tembusan k) k on k.status=1 and k.surat_id=a.id and k.pegawai_id=?
						left join (select l.*, Row_number() 
					              OVER ( 
					                partition BY l.surat_id 
					                ORDER BY l.sent_time DESC ) AS seqnum_l 
							from surat_penerima l) l on l.status in (1,2) and sent=1 and l.surat_id=a.id and l.pegawai_id=?
						left join (select m.*, Row_number() 
					              OVER ( 
					                partition BY m.surat_id 
					                ORDER BY m.sent_time DESC ) AS seqnum_m 
							from surat_pelaksana m) m on m.status=1 and m.surat_id=a.id and m.ref_type in ('plt','plh') and m.ref_name in ('surat_penerima', 'surat_tembusan', 'surat_tindaklanjut') and m.pegawai_id=?
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}


	/*
	*	draft datatable
	*/
	function draft_datatable($data_=false)
	{
		// helper('toolshelp');
		$pegawai_id = string_to($this->request->getPost('pegawai_id'), 'decode');
		$check_filter = $this->request->getPost('check_filter');
		$status = $this->request->getPost('status');
		$tahun = $this->request->getPost('tahun');
		$bulan = $this->request->getPost('bulan');
		$jenis = $this->request->getPost('jenis');
		$sifat = $this->request->getPost('sifat');
		$urgensi = $this->request->getPost('urgensi');
		// $status_proses_kepeg = $this->request->getPost('status_proses_kepeg');
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 50);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'inbox'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " a.sumber_ext in ? and (case when a.create_by=? then a.status in ? else a.status in ? and (a.pengirim_id=? or j.pegawai_id=?) end) ";
		array_push($arrWhere, [0]);
		array_push($arrWhere, session()->get('id'));
		array_push($arrWhere, [1,2,3,4,5]);
		array_push($arrWhere, [4,5]);
		array_push($arrWhere, session()->get('pegawai_id'));
		array_push($arrWhere, session()->get('pegawai_id'));
		if($search_value<>''){
			$where .= " AND (a.register_number like ? or a.nomor like ? or a.hal like ? or a.tanggal like ? or a.pengirim like ? or a.catatan like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		if($check_filter==1){
			if($tahun<>'' && $bulan<>''){
				$where .= " and (substr(a.register_time, 1,7)=? or substr(a.tanggal,1,7)=? ) ";
				array_push($arrWhere, $tahun.'-'.$bulan);
				array_push($arrWhere, $tahun.'-'.$bulan);
			}
		}
		if(!empty($jenis)){
			$where .= " and a.jenis in ? ";
			array_push($arrWhere, $jenis);
		}
		if(!empty($sifat)){
			$where .= " and a.sifat in ? ";
			array_push($arrWhere, $sifat);
		}
		if(!empty($urgensi)){
			$where .= " and a.urgensi in ? ";
			array_push($arrWhere, $urgensi);
		}
		if(!empty($status)){
			$where .= " and a.status in ? ";
			array_push($arrWhere, $status);
		}
		switch ($data_) {
			case true:
				$where .= " GROUP BY a.id ";
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q_rs = $this->db->query("SELECT a.id, a.sumber_ext, a.sumber_bentuk, ifnull(b.ref_name, '-') as sumber_bentuk_name, a.register_number, a.register_time, a.draf_for, ifnull(c.ref_name, '-') as draf_for_name, a.draf_ref_id, a.draf_type, ifnull(d.ref_name, '-') as draf_type_name, a.jenis, ifnull(e.ref_name, '-') as jenis_name, a.sifat, ifnull(f.ref_name, '-') as sifat_name, a.urgensi,ifnull(g.ref_name, '-') as urgensi_name,  a.nomor, a.tanggal, a.hal, a.status, case when a.sumber_ext=1 then ifnull(h.ref_name, '-') else ifnull(i.ref_name, '-') end as status_name, a.signer_opt, a.signer_alt, a.signer_show, a.pengirim, a.pengirim_id, a.pengirim_satker, a.pengirim_jabatan, a.pengirim_show, a.pengirim_alamat, a.penerima_sebagai, a.penerima_show, a.penerima_alamat, a.penerima_pada_lampiran, a.penerima_keterangan_lampiran, a.kka, a.sub_kka, a.sub_sub_kka, a.catatan, a.respon, a.create_by, a.no, a.no_mundur, a.no_type, a.no_instansi, a.no_reff, a.no_date, a.no_month, a.no_year, a.no_tanggal, a.no_status, a.contents, a.path, a.path_sign, a.lampiran, a.last_change, a.unix_id
		    			, j.signer_status, j.pegawai_id
					FROM surat a
						left join app_referensi b on b.ref='surat_bentuk' and b.ref_code=a.sumber_bentuk
						left join app_referensi c on c.ref='surat_create' and c.ref_code=a.draf_for
						left join app_referensi d on d.ref='surat_drafting' and d.ref_code=a.draf_type
						left join app_referensi e on e.ref='surat_jenis' and e.ref_code=a.jenis
						left join app_referensi f on f.ref='surat_sifat' and f.ref_code=a.sifat
						left join app_referensi g on g.ref='surat_urgensi' and g.ref_code=a.urgensi
						left join app_referensi h on h.ref='surat_status_ext' and h.ref_code=a.status
						left join app_referensi i on i.ref='surat_status_in' and i.ref_code=a.status
						left join surat_signer j on j.surat_id=a.id
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?,? ", $arrWhere)->getResult();
		    	$q = [];
		    	foreach ($q_rs as $key){
		    		$array_penerima = [];
		    		$penerima_show = 0;
		    		$list_penerima = $this->penerima_result_by_surat_id($key->id);
		    		if(!empty($list_penerima)){
		    			foreach($list_penerima as $k)
		    				array_push($array_penerima, $k);
		    				// $penerima_show = $k->nv;
		    		}
		    		$array_tembusan = [];
		    		$tembusan_show = 0;
		    		$list_tembusan = $this->tembusan_result_by_surat_id($key->id);
		    		if(!empty($list_tembusan)){
		    			foreach($list_tembusan as $k)
		    				array_push($array_tembusan, $k);
		    				// $tembusan_show = $k->nv;
		    		}
		    		$surat['id'] = $key->id;
		    		$surat['hash'] = string_to($key->id, 'encode');
					$surat['sumber_ext'] = $key->sumber_ext;
					$surat['sumber_bentuk'] = $key->sumber_bentuk;
					$surat['sumber_bentuk_name'] = $key->sumber_bentuk_name;
					$surat['register_number'] = $key->register_number;
					$surat['register_time'] = $key->register_time;
					$surat['draf_for'] = $key->draf_for;
					$surat['draf_for_name'] = $key->draf_for_name;
					$surat['draf_ref_id'] = $key->draf_ref_id;
					$surat['draf_type'] = $key->draf_type;
					$surat['draf_type_name'] = $key->draf_type_name;
					$surat['jenis'] = $key->jenis;
					$surat['jenis_name'] = $key->jenis_name;
					$surat['sifat'] = $key->sifat;
					$surat['sifat_name'] = $key->sifat_name;
					$surat['urgensi'] = $key->urgensi;
					$surat['urgensi_name'] = $key->urgensi_name;
					$surat['nomor'] = $key->nomor;
					$surat['tanggal'] = $key->tanggal;
					$surat['hal'] = $key->hal;
					$surat['status'] = $key->status;
					$surat['status_name'] = $key->status_name;
					$surat['signer_opt'] = $key->signer_opt;
					$surat['signer_alt'] = $key->signer_alt;
					$surat['signer_show'] = $key->signer_show;
					$surat['pengirim'] = $key->pengirim;
					$surat['pengirim_id'] = $key->pengirim_id;
					$surat['pengirim_satker'] = $key->pengirim_satker;
					$surat['pengirim_jabatan'] = $key->pengirim_jabatan;
					$surat['pengirim_show'] = $key->pengirim_show;
					$surat['pengirim_alamat'] = $key->pengirim_alamat;
					$surat['penerima'] = $array_penerima;
					$surat['penerima_sebagai'] = $key->penerima_sebagai;
					$surat['penerima_show'] = $key->penerima_show;
					$surat['penerima_alamat'] = $key->penerima_alamat;
					$surat['penerima_pada_lampiran'] = $key->penerima_pada_lampiran;
					$surat['penerima_keterangan_lampiran'] = $key->penerima_keterangan_lampiran;
					$surat['tembusan'] = $array_tembusan;
					$surat['kka'] = $key->kka;
					$surat['sub_kka'] = $key->sub_kka;
					$surat['sub_sub_kka'] = $key->sub_sub_kka;
					$surat['catatan'] = $key->catatan;
					$surat['respon'] = $key->respon;
					$surat['create_by'] = $key->create_by;
					$surat['no'] = $key->no;
					$surat['no_mundur'] = $key->no_mundur;
					$surat['no_type'] = $key->no_type;
					$surat['no_instansi'] = $key->no_instansi;
					$surat['no_reff'] = $key->no_reff;
					$surat['no_date'] = $key->no_date;
					$surat['no_month'] = $key->no_month;
					$surat['no_year'] = $key->no_year;
					$surat['no_tanggal'] = $key->no_tanggal;
					$surat['no_status'] = $key->no_status;
					$surat['contents'] = $key->contents;
					$surat['path'] = $key->path;
					$surat['path_sign'] = $key->path_sign;
					$surat['lampiran'] = $key->lampiran;
					$surat['last_change'] = $key->last_change;
					$surat['unix_id'] = $key->unix_id;
					$surat['signer_status'] = $key->signer_status;
					$surat['pegawai_id'] = $key->pegawai_id;
					// $surat['respon_user'] = $key->respon_user;
					// $surat['pos_id'] = $key->pos_id;
					// $surat['sebagai'] = $key->sebagai;
					// $surat['diteruskan'] = $key->diteruskan;
		    		array_push($q, $surat);
		    	}
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(a.id)) as field FROM surat a
						left join surat_signer j on j.surat_id=a.id
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}


	/*
	*	list register
	*/
	function register_datatable($data_=false)
	{
		// helper('toolshelp');
		$pegawai_id = string_to($this->request->getPost('pegawai_id'), 'decode');
		$check_filter = $this->request->getPost('check_filter');
		$status = $this->request->getPost('status');
		$tahun = $this->request->getPost('tahun');
		$bulan = $this->request->getPost('bulan');
		$jenis = $this->request->getPost('jenis');
		$sifat = $this->request->getPost('sifat');
		$urgensi = $this->request->getPost('urgensi');
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 50);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'inbox'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " 1 ";
		// $where .= " and a.pegawai_id=? ";
		// array_push($arrWhere, $pegawai_id);
		if($search_value<>''){
			$where .= " AND (a.nomor like ? or a.hal like ? or a.tanggal like ? or a.pengirim like ? or a.catatan like ?) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		if($check_filter==1){
			if($tahun<>'' && $bulan<>''){
				$where .= " and substr(a.register_time,1,7)=? ";
				array_push($arrWhere, $tahun.'-'.$bulan);
			}
		}
		if(!empty($jenis)){
			$where .= " and a.jenis in ? ";
			array_push($arrWhere, $jenis);
		}
		if(!empty($sifat)){
			$where .= " and a.sifat in ? ";
			array_push($arrWhere, $sifat);
		}
		if(!empty($urgensi)){
			$where .= " and a.urgensi in ? ";
			array_push($arrWhere, $urgensi);
		}
		if(!empty($status)){
			$where .= " and a.status in ? ";
			array_push($arrWhere, $status);
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q_rs = $this->db->query("SELECT 
						a.id, 
						a.sumber_ext, 
						a.sumber_bentuk, 
						ifnull(b.ref_name, '-') as sumber_bentuk_name, 
						a.register_number, 
						a.register_time, 
						a.draf_for, 
						ifnull(c.ref_name, '-') as draf_for_name, 
						a.draf_ref_id, 
						a.draf_type, 
						ifnull(d.ref_name, '-') as draf_type_name, 
						a.jenis, 
						ifnull(e.ref_name, '-') as jenis_name, 
						a.sifat, 
						ifnull(f.ref_name, '-') as sifat_name, 
						a.urgensi,
						ifnull(g.ref_name, '-') as urgensi_name,  
						a.nomor, 
						a.tanggal, 
						a.hal, 
						a.status,
						case when a.sumber_ext=1 then ifnull(h.ref_name, '-') else ifnull(i.ref_name, '-') end as status_name,
						a.signer_opt, a.signer_alt, a.signer_show, a.pengirim, a.pengirim_id, a.pengirim_satker, a.pengirim_jabatan, a.pengirim_show, a.pengirim_alamat, a.penerima_sebagai, a.penerima_show, a.penerima_alamat, a.penerima_pada_lampiran, a.penerima_keterangan_lampiran, a.kka, a.sub_kka, a.sub_sub_kka, a.catatan, a.respon, a.create_by, a.no, a.no_mundur, a.no_type, a.no_instansi, a.no_reff, a.no_date, a.no_month, a.no_year, a.no_tanggal, a.no_status, a.contents, a.path, a.path_sign, a.path_sign_name, a.lampiran, a.last_change, a.unix_id 
					FROM surat a
						left join app_referensi b on b.ref='surat_bentuk' and b.ref_code=a.sumber_bentuk
						left join app_referensi c on c.ref='surat_create' and c.ref_code=a.draf_for
						left join app_referensi d on d.ref='surat_drafting' and d.ref_code=a.draf_type
						left join app_referensi e on e.ref='surat_jenis' and e.ref_code=a.jenis
						left join app_referensi f on f.ref='surat_sifat' and f.ref_code=a.sifat
						left join app_referensi g on g.ref='surat_urgensi' and g.ref_code=a.urgensi
						left join app_referensi h on h.ref='surat_status_ext' and h.ref_code=a.status
						left join app_referensi i on i.ref='surat_status_in' and i.ref_code=a.status
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?,? ", $arrWhere)->getResult();
		    	$q = [];
		    	foreach ($q_rs as $key){
		    		$array_penerima = [];
		    		$list_penerima = $this->penerima_result_by_surat_id($key->id);
		    		if(!empty($list_penerima)){
		    			foreach($list_penerima as $k)
		    				array_push($array_penerima, $k);
		    		}
		    		$array_tembusan = [];
		    		$list_tembusan = $this->tembusan_result_by_surat_id($key->id);
		    		if(!empty($list_tembusan)){
		    			foreach($list_tembusan as $k)
		    				array_push($array_tembusan, $k);
		    		}
		    		$surat['id'] = $key->id;
		    		$surat['hash'] = string_to($key->id, 'encode');
					$surat['sumber_ext'] = $key->sumber_ext;
					$surat['sumber_bentuk'] = $key->sumber_bentuk;
					$surat['sumber_bentuk_name'] = $key->sumber_bentuk_name;
					$surat['register_number'] = $key->register_number;
					$surat['register_time'] = $key->register_time;
					$surat['draf_for'] = $key->draf_for;
					$surat['draf_for_name'] = $key->draf_for_name;
					$surat['draf_ref_id'] = $key->draf_ref_id;
					$surat['draf_type'] = $key->draf_type;
					$surat['draf_type_name'] = $key->draf_type_name;
					$surat['jenis'] = $key->jenis;
					$surat['jenis_name'] = $key->jenis_name;
					$surat['sifat'] = $key->sifat;
					$surat['sifat_name'] = $key->sifat_name;
					$surat['urgensi'] = $key->urgensi;
					$surat['urgensi_name'] = $key->urgensi_name;
					$surat['nomor'] = $key->nomor;
					$surat['tanggal'] = $key->tanggal;
					$surat['hal'] = $key->hal;
					$surat['status'] = $key->status;
					$surat['status_name'] = $key->status_name;
					$surat['signer_opt'] = $key->signer_opt;
					$surat['signer_alt'] = $key->signer_alt;
					$surat['signer_show'] = $key->signer_show;
					$surat['pengirim'] = $key->pengirim;
					$surat['pengirim_id'] = $key->pengirim_id;
					$surat['pengirim_satker'] = $key->pengirim_satker;
					$surat['pengirim_jabatan'] = $key->pengirim_jabatan;
					$surat['pengirim_show'] = $key->pengirim_show;
					$surat['pengirim_alamat'] = $key->pengirim_alamat;
					$surat['penerima'] = $array_penerima;
					$surat['penerima_sebagai'] = $key->penerima_sebagai;
					$surat['penerima_show'] = $key->penerima_show;
					$surat['penerima_alamat'] = $key->penerima_alamat;
					$surat['penerima_pada_lampiran'] = $key->penerima_pada_lampiran;
					$surat['penerima_keterangan_lampiran'] = $key->penerima_keterangan_lampiran;
					$surat['tembusan'] = $array_tembusan;
					$surat['kka'] = $key->kka;
					$surat['sub_kka'] = $key->sub_kka;
					$surat['sub_sub_kka'] = $key->sub_sub_kka;
					$surat['catatan'] = $key->catatan;
					$surat['respon'] = $key->respon;
					$surat['create_by'] = $key->create_by;
					$surat['no'] = $key->no;
					$surat['no_mundur'] = $key->no_mundur;
					$surat['no_type'] = $key->no_type;
					$surat['no_instansi'] = $key->no_instansi;
					$surat['no_reff'] = $key->no_reff;
					$surat['no_date'] = $key->no_date;
					$surat['no_month'] = $key->no_month;
					$surat['no_year'] = $key->no_year;
					$surat['no_tanggal'] = $key->no_tanggal;
					$surat['no_status'] = $key->no_status;
					$surat['contents'] = $key->contents;
					$surat['path'] = $key->path;
					$surat['path_sign'] = $key->path_sign;
					$surat['lampiran'] = $key->lampiran;
					$surat['last_change'] = $key->last_change;
					$surat['unix_id'] = $key->unix_id;
		    		array_push($q, $surat);
		    	}
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(a.id)) as field FROM surat a
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}


	/*
	*	nanti di cek2 atau ditambahin lagi sesuai kondisi
	*/
	function filterFieldData($field, $name)
	{
		switch ($name) {
			case 'inbox':
				$r_field = 'id';
				$array_field = ['id', 'sumber_ext', 'sumber_bentuk', 'register_number', 'register_time', 'draf_for', 'draf_ref_id', 'draf_type', 'jenis', 'sifat', 'urgensi', 'nomor', 'tanggal', 'hal', 'status', 'signer_opt', 'signer_alt', 'signer_show', 'pengirim', 'pengirim_id', 'pengirim_satker', 'pengirim_jabatan', 'pengirim_show', 'pengirim_alamat', 'penerima_sebagai', 'penerima_show', 'penerima_alamat', 'penerima_pada_lampiran', 'penerima_keterangan_lampiran', 'kka', 'sub_kka', 'sub_sub_kka', 'catatan', 'respon', 'create_by', 'no', 'no_mundur', 'no_type', 'no_instansi', 'no_reff', 'no_date', 'no_month', 'no_year', 'no_tanggal', 'no_status', 'contents', 'path', 'path_sign', 'lampiran', 'last_change', 'unix_id'];
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
	*	create file
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
				$f_nomor_surat = $this->create_nomor_surat($data_cuti['id'], 'KKK', $test);
				// $f_nomor_surat = 'TEST-NOMOR-SURAT-000';
			}
			/*	LOAD TEMPLATE FORM CUTI FOR EMPLOYEE */
			$file_yg_di_proses = WRITEPATH.'templates/form_cuti_tp.docx';
			require APPPATH.'Libraries/PHPWord-develop/bootstrap.php';
			$phpWord = new \PhpOffice\PhpWord\PhpWord();
			$document = $phpWord->loadTemplate($file_yg_di_proses);
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
			$spesimen_tte_pegawai = return_files_path_by($data_cuti['pegawai_id'], 6);
			switch ($level) {
				case 2:
					if($spesimen_tte_pegawai<>''){
						$document->setImageValue('image1.png', $spesimen_tte_pegawai);
					}
					$document->setValue('f_footer_text', '');
					break;
				case 3:
					if($spesimen_tte_pegawai<>''){
						$document->setImageValue('image1.png', $spesimen_tte_pegawai);
					}
					$options = return_value_in_options('kepegawaian');
					if(array_keys(['','true'],$options['esign_cuti']))
					{
						$document->setValue('f_footer_text', 'Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan oleh Balai Besar Sertifikasi Elektronik (BSrE), BSSN');
					}else{
						$document->setValue('f_footer_text', '');
						// $spesimen_tte_pimpinan = return_files_path_by($data_cuti['pegawai_id_pimpinan'], 6);
						// if($spesimen_tte_pimpinan<>''){
						// 	$document->setImageValue('image2.png', $spesimen_tte_pimpinan);
						// }
						$unix_id = $data_cuti['unix_id'];
						$url = $options['url_verifikasi'] . $unix_id .'&opt=cuti';
						$qr_path = WRITEPATH.'temp_zip/qr_cuti_'.$unix_id.'.png';
						$image_qrcode = create_new_qrcode($url, $qr_path, true);
						$document->setImageValue('image2.png', $image_qrcode);
					}
					break;
				default:
					if($spesimen_tte_pegawai<>''){
						$document->setImageValue('image1.png', $spesimen_tte_pegawai);
					}
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
			if(array_keys([1,2],$data_cuti['status']))
			{
				@unlink($data_cuti['path']);
			}else{
				@unlink($image_qrcode);
			}
			@unlink($pathfile_replace);
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
					$nomor_baru_lengkap = $type.'-'.$nomor_baru_.'/'.$reff.'/'.$bln.'/'.$thn;
					break;
				case array_keys([2,3,4,10], $data_cuti['jabatan_id_pimpinan']):
					$nomor_baru_lengkap = $type.'-'.$nomor_baru_.'/'.$instansi.'/'.$reff.'/'.$bln.'/'.$thn;
					break;
				default:
					// $nomor_baru_lengkap = 'Internal';
					// $nomor_baru = 0;
					$nomor_baru_lengkap = $type.'-'.$nomor_baru_.'/'.$instansi.'/'.$reff.'/'.$bln.'/'.$thn;
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


	function nomor_surat_terakhir_mundur($thn, $date, $reff, $type='M', $instansi='KKK')
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

	function nomor_surat_terakhir_live($year, $reff, $type='M', $instansi='KKK')
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

	/*
	*	reister number
	*/
	function create_register_number()
	{
		helper('text');
		$randomString = date('Ym') .'-'. random_string('alnum', 6);
		$q = $this->db->query("SELECT register_number from surat where register_number=? ", [$randomString])->getRow();
		if(empty($q)){
			return $randomString;
		}else{
			$this->create_register_number();
		}
	}
}