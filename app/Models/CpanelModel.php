<?php 
namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Tools;
// use App\Models\CpanelModel;

class CpanelModel extends Model
{
	function __construct()
	{
		$this->db = \Config\Database::connect();
		$this->request = \Config\Services::request();
		// $this->session = \Config\Services::session();
        $this->tools = new Tools();
        // $this->key = (session()->get('key'))?:$this->request->getHeaderLine('Key');
        // $this->token = (session()->get('token'))?:$this->request->getHeaderLine('Token');
        $this->user = (session()->get('id'))?:$this->request->getHeaderLine('User');
    }


	/*
	*	MASTER
	*/
	function konfigurasi_list($data_=false)
	{
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = $this->filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'konfigurasi'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " status in (0,1) ";
		if($search_value<>''){
			$where .= " AND (name like ? or description like ? ) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q = $this->db->query("SELECT id, name, value, status, description, last_change, user_id FROM app_options 
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?, ? ", $arrWhere)->getResult();
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(id)) as field FROM app_options
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function konfigurasi_save($data_, $id=0)
	{
		if($id > 0)
        {
            $this->db->table('app_options')->update($data_, ['id'=>$id]);
        }else{
            $this->db->table('app_options')->insert($data_);
            $id = $this->db->insertID();
        }
        return $this->db->affectedRows();
	}

	function konfigurasi_get_row($id)
	{
		return $this->db->table('app_options')->getWhere(['id'=>$id])->getRow();
	}


	function referensi_list($data_=false)
	{
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = $this->filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'referensi'));
		}
		$search_value = $_POST['search']['value'];
		$ref = $this->request->getPost('ref');
		$arrWhere = [];
		$where = " ref_status in (0,1) ";
		if($search_value<>''){
			$where .= " AND (ref_name like ? or ref_description like ? or ref like ? ) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, $search_value);
		}
		if($ref<>''){
			$where .= " and ref=? ";
			array_push($arrWhere, $ref);
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q = $this->db->query("SELECT id, ref, ref_code, ref_name, ref_description, ref_status, last_change, user_id, ref_value, flag1 FROM app_referensi 
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?, ? ", $arrWhere)->getResult();
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(id)) as field FROM app_referensi
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function referensi_save($data_, $id=0)
	{
		if($id > 0)
        {
            $this->db->table('app_referensi')->update($data_, ['id'=>$id]);
        }else{
            $this->db->table('app_referensi')->insert($data_);
            $id = $this->db->insertID();
        }
        return $this->db->affectedRows();
	}

	function referensi_get_row($id)
	{
		return $this->db->table('app_referensi')->getWhere(['id'=>$id])->getRow();
	}

	function referensi_get_row_by($ref, $code)
	{
		return $this->db->table('app_referensi')->getWhere(['ref'=>$ref, 'ref_code'=>$code])->getRow();
	}

	function referensi_get_result($id)
	{
		return $this->db->table('app_referensi')->getWhere(['ref'=>$id, 'ref_status'=>1])->getResult();
	}


	function pengguna_list($data_=false)
	{
		$start = (int) (($_POST['start']) ? : 0);
		$draw = (int) (($_POST['draw']) ? : 1);
		$length = (int) (($_POST['length']) ? : 10);
		$order_column = (int) (($_POST['order'][0]['column']) ? : 0);
		$order_dir = $this->filterFieldOrder(($_POST['order'][0]['dir']) ? : 'asc');
		$columns_name = array();
		for ($i=0; $i < count($_POST['columns']); $i++) { 
			array_push($columns_name, $this->filterFieldData($_POST['columns'][$i]['data'],'pengguna'));
		}
		$search_value = $_POST['search']['value'];
		$arrWhere = [];
		$where = " status in (0,1) ";
		if($search_value<>''){
			$where .= " AND (username like ? or email like ? ) ";
			array_push($arrWhere, '%'.$search_value.'%');
			array_push($arrWhere, '%'.$search_value.'%');
		}
		switch ($data_) {
			case true:
				array_push($arrWhere, $start);
				array_push($arrWhere, $length);
		    	$q = [];
		    	$qx = $this->db->query("SELECT id, username, email, status, activation_key, password, last_change, user_id FROM app_users 
		    		WHERE ".$where." ORDER BY ".$columns_name[$order_column]." ".$order_dir." limit ?, ? ", $arrWhere)->getResult();
		    	foreach ($qx as $k) {
		    		$qxh = [
		    			'hash' => string_to($k->id, 'encode'),
						'id' => $k->id, 
						'username' => $k->username, 
						'email' => $k->email, 
						'status' => $k->status, 
						'activation_key' => $k->activation_key, 
						'password' => $k->password, 
						'last_change' => $k->last_change, 
						'user_id' => $k->user_id
		    		];
		    		array_push($q, $qxh);
		    	}
				break;
			default:
				$q = $this->db->query("SELECT count(distinct(id)) as field FROM app_users
		    		WHERE ".$where, $arrWhere)->getRow()->field;
				break;
		}
	    return $q;
	}

	function pengguna_save($data_, $id=0)
	{
		if($id > 0)
        {
            $this->db->table('app_users')->update($data_, ['id'=>$id]);
        }else{
            $this->db->table('app_users')->insert($data_);
            $id = $this->db->insertID();
        }
        return $this->db->affectedRows();
	}

	function pengguna_get_row($id)
	{
		return $this->db->table('app_users')->getWhere(['id'=>$id])->getRow();
	}


	function filterFieldData($field, $name)
	{
		switch ($name) {
			case 'konfigurasi':
				$r_field = 'id';
				$array_field = ['id', 'name', 'value', 'status', 'description', 'last_change', 'user_id'];
				break;
			case 'referensi':
				$r_field = 'id';
				$array_field = ['id', 'ref', 'ref_code', 'ref_name', 'ref_description', 'ref_status', 'last_change', 'user_id'];
				break;
			case 'pengguna':
				$r_field = 'id';
				$array_field = ['id', 'username', 'email', 'status', 'activation_key', 'password', 'logs', 'last_change', 'user_id'];
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