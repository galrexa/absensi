<?php 
namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Tools;
// use App\Models\LoadModel;

class AuthModel extends Model
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


	/*NUMPANG NARO DULU*/
	public function app_list()
	{
		return $this->db->query("SELECT * FROM app WHERE status in ? and id not in ?", [[1], [1]])->getResult();
	}

	public function app_get_row($id)
	{
		return $this->db->query("SELECT * FROM app WHERE status in ? and id=? ", [[1], $id])->getRow();
	}

    public function units()
    {
    	return $this->db->query("SELECT * from ms_unit_kerja where unit_kerja_status=1 order by urutan asc ")->getResult();
    }
    /*END*/



	function verifyGoogleCaptchaV2()
	{
        $secret = '6Lfbn20fAAAAAAGqXEbF8qQZhZnwVau_a7sDELJY';
        $config_api_captcha = return_value_in_options('google');
        if(!empty($config_api_captcha)){
        	$secret = $config_api_captcha['captcha']['secretkey'];
        }
        $recaptchaResponse = trim($this->request->getVar('g-recaptcha-response'));
        $credential = array(
            'secret' => $secret,
            'response' => $this->request->getVar('g-recaptcha-response')
        );
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($credential));
        curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
        $status = json_decode($response, true);
        return $status;
	}


	function get_app_options_by_name($id)
	{
		return $this->db->query("SELECT id, name, value, status, description FROM app_options WHERE name=? ", [$id])->getRow();
	}

	function check_password_user($id_user, $password)
	{
		$rs = false;
		$q = $this->db->query("SELECT status, password FROM app_users WHERE id=? ", [$id_user])->getRow();
		if(!empty($q))
		{
            $encrypter = \Config\Services::encrypter();
            if($password==$encrypter->decrypt(hex2bin($q->password)) && $q->status==1)
			{
				$rs = true;
			}
		}
		return $rs;
	}



	function app_2fa_user($id_user, $status=1, $service='App Development')
	{
		return $this->db->query("SELECT `id`, `id_user`, `username`, `service_name`, `secret`, `path_url`, `path_qr`, `status`, `kanggo`, `create_at`, `update_at` FROM `app_2fa` WHERE id_user=? and service_name=? and status=? order by id desc limit 1 ", [$id_user, $service, $status])->getRow();
	}



	function app_options_users($id_user)
	{
		return $this->db->query("SELECT * FROM app_options_users where id_user=? ", [$id_user])->getRow();
	}



	function app_options_users_toptp($id_user)
	{
		$rs = false;
		$q = $this->app_options_users($id_user);
		if(!empty($q))
		{
			if($q->toptp==1)
				$rs = true;
		}
		return $rs;
	}




    function validasi_token()
    {
        $rs = false;
        $q = $this->db->query("SELECT `id`, `key`, `token`, `user_id`, `expired_time`, `status`, `device`, `ip_address` FROM `app_access` WHERE `key`=? and `token`=? and `user_id`=? and status=?", [$this->key, $this->token, $this->user, 1])->getRow();
        if(!empty($q))
        {
        	switch (true) {
        		case $q->expired_time > date('Y-m-d H:i:s'):
        			$rs = true;
        			break;
        		default:
        			break;
        	}
        }
        return $rs;
    }



    function validasi_key()
    {
        $rs = false;
        $q = $this->db->query("SELECT id, address FROM `app_keys` WHERE `key`=? and status=?", [$this->key, 1])->getRow();
        if(!empty($q))
        {
        	switch (true) {
        		case $q->address=='0.0.0.0':
        			$rs = true;
        			break;
        		default:
        			if($q->address==$this->request->getIPAddress()){
        				$rs = true;
        			}
        			break;
        	}
        }
        return $rs;
    }



	function return_roles_user($arr)
	{
		$rs = false;
		$i = 0;
		$roles = $this->load_user_roles();
		if(!empty($arr)){
			foreach($arr as $k=>$v){
				if(array_keys($roles, $v)){
					$i = +1;
				}
			}
		}
		if($i>0){ $rs = true; }
		return $rs;
	}



    function user_roles($id_user='')
    {
    	if($id_user=='')
    	{
    		$id_user = $this->user;
    	}
    	return $this->db->query("SELECT DISTINCT id_role, name_role from user_base_access_controll WHERE id_user=? ", [$id_user])->getResult();
    }
    function remove_user_role($arr_where)
    {
    	$this->db->table('app_users_roles')->delete($arr_where);
    }
	function user_roles_save($data_)
	{
        $this->db->table('app_users_roles')->insert($data_);
        return $this->db->insertID();
	}

    function list_roles()
    {
    	return $this->db->query("SELECT * FROM `app_roles` where status=? ", [1])->getResult();
    }



    function load_user_roles()
    {
    	$arr = [];
    	$q = $this->user_roles();
    	foreach ($q as $k) {
    		array_push($arr, $k->id_role);
    	}
    	return $arr;
    }



    function user_base_access()
    {
        return $this->db->query("SELECT DISTINCT name_module as url_path FROM `user_base_access_controll` WHERE id_user=? ", [$this->user])->getResult();
    }



    function load_user_base_access()
    {
        $user_base_access = [];
        $q = $this->user_base_access();
        if(!empty($q))
        {
            foreach ($q as $k) {
                array_push($user_base_access, strtolower($k->url_path));
            }
        }
        session()->set('user_base_access', $user_base_access);
        return $user_base_access;
    }


	function app_users_save($data_, $key=[])
	{
		$id = 0;
		if(!empty($key))
        {
           	$this->db->table('app_users')->update($data_, $key);
        }else{
            $this->db->table('app_users')->insert($data_);
        	$id = $this->db->insertID();
            $this->db->table('app_users_roles')->insert(['id_user'=>$id, 'id_role'=>3, 'user_id'=>$this->user]);
        }
        return $id;
	}


	function check_user_by_email($email)
	{
		return $this->db->query("SELECT id, username, email FROM app_users where email=? ", [$email])->getRow();
	}

	function check_user_by_username($username)
	{
		return $this->db->query("SELECT id, username, email FROM app_users where username=? ", [$username])->getRow();
	}
	function get_user_by_id($username)
	{
		return $this->db->query("SELECT * FROM app_users where id=? ", [$username])->getRow();
	}


	function check_pegawai_by_email($email)
	{
		return $this->db->query("SELECT pegawai_id, nama, unit_kerja_id, jabatan_id, foto_pegawai, foto_pegawai_temp, kelamin FROM pegawai where email=? ", [$email])->getRow();
	}


	function session_pegawai($email)
	{
		$q = $this->check_pegawai_by_email($email);
		if(!empty($q))
		{
			if(file_exists($q->foto_pegawai_temp)){
				$icon_foto = create_file_to_base64($q->foto_pegawai_temp);
			}else{
				$icon_foto = get_foto_default_pegawai($q->kelamin);
			}
			session()->set(['pegawai_id'=>$q->pegawai_id, 'nama'=>$q->nama, 'unit_kerja_id'=>$q->unit_kerja_id, 'jabatan_id'=>$q->jabatan_id, 'pegawai_foto'=>$icon_foto]);
		}
	}


	function hash_pegawai($id)
	{
		$hash = str_replace(['/','='], '', base64_encode(md5($id) . microtime() . $this->key . random_bytes(16)));
		$q = $this->db->query("SELECT id_hash from pegawai_hash_link where id_hash=? ", [$hash])->getRow();
		if(!empty($q))
		{
			return $this->hash_pegawai($id);
		}else{
			return $hash;
		}
	}



    function new_token_access($user_data)
    {
		$addTime = '+ 1 day';
		$now_start_time = date('Y-m-d H:i:s');
		$expired_time = date('Y-m-d H:i:s', strtotime($now_start_time. ' ' . $addTime));
		$token = base64_encode(md5($user_data->id) . microtime() . $this->key . random_bytes(64));
		$this->db->table('app_access')->insert([
		    'key' => $this->key,
		    'token' => $token,
		    'user_id' => $user_data->id,
		    'created_time' => $now_start_time,
		    'expired_time' => $expired_time,
		    'device' => $this->request->getHeaderLine('User-Agent'),
		    'ip_address' => $this->request->getIPAddress(),
		    'headers' => json_encode($this->tools->read_headers()),
		]);
		$this->db->query("UPDATE app_access SET status=?, expired_time=? WHERE `key`=? and user_id=? and status=? and token!=?", [0, $now_start_time, $this->key, $user_data->id, 1, $token]);
		return $token;
    }



	function save_log($arr)
	{
		helper('toolshelp');
		$log_data = [
			'user' => $this->user,
			'date_time' => date('Y-m-d H:i:s'),
			'ipaddress' => $this->request->getIPAddress(),
			'url' => current_url(true),
			'method' => $this->request->getMethod(true),
			'headers' => json_encode($this->tools->read_headers()),
			'data' => json_encode(remove_key_in_array(($_POST)?:$_GET, ['password', 'passphrase'])),
			'message' => (isset($arr['message']))?$arr['message']:'-',
			'reff' => (isset($arr['reff']))?$arr['reff']:'-',
			'reff_id' => (isset($arr['id']))?$arr['id']:0,
			'api_key' => $this->key,
			'token' => $this->token,
			'module' => (isset($arr['module']))?$arr['module']:0,
		];
		$this->db->table('app_logs')->insert($log_data);
		$id = $this->db->insertID();
		return $id;
	}
}