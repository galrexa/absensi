<?php

namespace App\Controllers;

use App\Libraries\TwoFA;
use Google\Client;

class Auth extends BaseController
{

    function __construct()
    {
        $this->twoFA = new TwoFA();
    }



    // public function index(): string
    // {
    //     $arr_view = [
    //         'title' => 'Welcome',
    //         'test_uri' => $this->uri->getSegments(),
    //     ];
    //     return view('welcome_message', $arr_view);
    // }



    function activate2fa()
    {
        if($_POST)
        {
            $this->array_response['code'] = 200;
            $this->validation->setRules([
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'kode_2fa' => [
                    'label' => 'Kode Verifikasi 2FA', 
                    'rules' => 'required|integer|min_length[6]|max_length[6]',
                ],
                'password' => [
                    'label' => 'Password', 
                    'rules' => 'required|max_length[100]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                $id = $this->request->getPost('id');
                $kode_2fa = $this->request->getPost('kode_2fa');
                $password = $this->request->getPost('password');
                if($this->AuthModel->check_password_user($this->session->id, $password))
                {
                    $app_2fa = $this->AuthModel->app_2fa_user($this->session->id, 0);
                    if(!empty($app_2fa))
                    {
						$checkResult = $this->twoFA->verifyCode($app_2fa->secret, $kode_2fa, 2);
                        if($checkResult)
                        {
							$this->session->set(['2fa_status'=>1]);
							$this->db->table('app_2fa')->update([
								'status' => 1,
								'kanggo' => 1,
								'update_at' => date('Y-m-d H:i:s')
							], ['id'=>$app_2fa->id]);
                            $this->db->table('app_options_users')->replace([
								'id_user' => $this->session->id,
								'toptp' => 1
                            ]);
                            $this->array_response['status'] = true;
                            $this->array_response['message'] = 'Berhasil';
                        }else{
                            $this->array_response['message'] = 'Token tidak valid';
                        }
                    }else{
                        $this->array_response['message'] = '2FA tidak valid';
                    }
                }else{
                    $this->array_response['message'] = 'Kata sandi salah';
                }
            }else{
                $this->array_response['message'] = strip_tags($this->validation->listErrors());
            }
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Aktivasi 2FA',
                'page' => 'Client/2fa_activate',
                'totp' => $this->AuthModel->app_options_users_toptp($this->session->id),
            ];
            return view('tBaseBox', $arr);
        }
    }



	function totp()
	{
		if($_POST)
		{
			echo '<h3>Akses ditolak.</h3>';
		}else{
			$status = $this->request->getGet('status');
			if($status==1)
			{
				$status = 1;
				$secret = $this->twoFA->createSecret();
				$QRCodeUrl = $this->twoFA->getQRCodeUrl('App Development', $this->session->email, $secret);
				$QRName = WRITEPATH.'uploads/'.$this->session->id.date('YmdHis').'.png';
				$this->db->table('app_2fa')->insert([
					'id_user' => $this->session->id,
					'username' => $this->session->username,
					'service_name' => 'App Development',
					'secret' => $secret,
					'path_url' => $QRCodeUrl,
					'path_qr' => $QRName,
					'create_at' => date('Y-m-d H:i:s')
				]);
				$id = ($this->db->insertID())?:0;
                $array_view = [
                    'title' => 'QRCode 2FA',
                    'id' => $id,
                    'secret' => $secret,
                    'QRCodeUrl' => $QRCodeUrl,
                    'QRName' => $QRName,
                ];
                return view('Client/2fa_totp', $array_view);
			}else{
				$status = 0;
				$id = 0;
				$this->db->table('app_2fa')->update(['status'=>0, 'update_at'=>date('Y-m-d H:i:s')], ['id_user'=>$this->session->id, 'status'=>1]);
				$this->db->table('app_options_users')->update(['toptp'=>0], ['id_user'=>$this->session->id]);
				echo '<div class="text-danger">Tidak diaktifkan.</div>';	
			}
		}
	}



    public function otp()
    {
        if($_POST)
        {
            $this->array_response['code'] = 200;
            $this->validation->setRules([
                'kode_2fa' => [
                    'label' => 'Kode Verifikasi 2FA', 
                    'rules' => 'required|integer|min_length[6]|max_length[6]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                $kode_2fa = $this->request->getPost('kode_2fa');
                $app_2fa = $this->AuthModel->app_2fa_user($this->session->id);
                if(!empty($app_2fa))
                {
                    $checkResult = $this->twoFA->verifyCode($app_2fa->secret, $kode_2fa, 2);
                    if($checkResult)
                    {
                        $this->session->set(['2fa_status'=>1]);
                        $this->array_response['status'] = true;
                        $this->array_response['message'] = 'Berhasil';
                    }else{
                        $this->array_response['message'] = 'Token tidak valid';
                    }
                }else{
                    $this->array_response['message'] = '2FA tidak valid';
                }
            }else{
                $this->array_response['message'] = strip_tags($this->validation->listErrors());
            }
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'OTP 2FA',
                'page' => 'Client/2fa_otp',
            ];
            return view('tBaseBox', $arr);
        }
    }



    // form login
    public function login()
    {
        $config_api_captcha = return_value_in_options('google');
        // print_r($config_api_captcha);
        if($_POST)
        {
            $this->array_response['code'] = 200;
            $this->validation->setRules([
                'username' => [
                    'label' => 'Username', 
                    'rules' => 'required|max_length[100]',
                ],
                'password' => [
                    'label' => 'Password', 
                    'rules' => 'required|max_length[100]|min_length[6]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                $username = $this->request->getPost('username');
                $password = $this->request->getPost('password');
                $key = $this->request->getHeaderLine('Key');
                switch ($config_api_captcha['captcha']['status']) {
                    case "true":
                        // google captcha
                        $status = $this->AuthModel->verifyGoogleCaptchaV2();
                        if($status['success'])
                        {
                            // return true;
                        }else{
                            $this->session->setFlashdata('message', 'Captcha tidak valid');
                            $this->arr['message'] = 'Captcha tidak valid';
                            $this->arr['csrf'] = csrf_hash();
                            return $this->response
                                ->setHeader('Access-Control-Allow-Origin', '*')
                                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                                ->setHeader('Access-Control-Allow-Headers', 'X-Api-Key')
                                ->setStatusCode(406)
                                ->setJSON($this->arr);
                        }
                        break;
                    default:
                        break;
                }
                $user_data = $this->db->query("SELECT * FROM app_users where LOWER(username)=? or LOWER(email)=? ", [strtolower($username),strtolower($username)])->getRow();
                if(!empty($user_data))
                {
                    $logs = json_decode($user_data->logs, true);
                    if($user_data->status==1)
                    {
                        $encrypter = \Config\Services::encrypter();
                        if($password==$encrypter->decrypt(hex2bin($user_data->password)))
                        {
                            $token = $this->AuthModel->new_token_access($user_data);
                            $session = [
                                'login' => TRUE,
                                'id' => $user_data->id, 
                                'username' => $user_data->username, 
                                'email' => $user_data->email, 
                                'status' => $user_data->status,
                                'activation_key' => $user_data->activation_key,
                                'key' => $key,
                                'token' => $token,
                            ];
                            $this->AuthModel->session_pegawai($user_data->email);
                            $optionUser = $this->AuthModel->app_options_users($user_data->id);
                            if(!empty($optionUser))
                            {
                                $session['options'] = $optionUser;
                            }
                            $this->session->set($session);
                            session()->set('roles', $this->AuthModel->load_user_roles());
                            $this->array_response['status'] = true;
                            $this->array_response['message'] = 'Successful';
                            $this->array_response['data'] = $session;
                        }else{
                            $this->array_response['message'] = 'User or Password not match';
                        }
                    }else{
                        $this->array_response['message'] = 'User or Password not Match';
                    }
                    array_push($logs, ['action' => 'login', 'status' => $this->array_response['status'], 'datetime' => date('Y-m-d H:i:s'), 'ip_address' => $this->request->getIPAddress(), 'message'=>$this->array_response['message']]);
                    $this->db->table('app_users')->update(['logs'=>json_encode($logs)], ['id'=>$user_data->id]);
                }else{
                    $this->array_response['message'] = 'User or Password Not match';
                }
            }else{
                $this->array_response['message'] = strip_tags($this->validation->listErrors());
            }
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $array_view = [
                'title' => 'Form Login',
                'page' => 'login',
                'status' => $config_api_captcha['captcha']['status'],
                'sitekey' => $config_api_captcha['captcha']['sitekey'],
                'oauth_start' => $config_api_captcha['oauth']['status'],
                'api_key' => $config_api_captcha['api_key'],
            ];
            return view($array_view['page'], $array_view);   
        }
    }



    // logout
    public function logout()
    {
        $this->array_response['code'] = 200;
        $key = (session()->get('key'))?:$this->request->getHeaderLine('Key');
        $token = (session()->get('token'))?:$this->request->getHeaderLine('Token');
        $id = (session()->get('id'))?:$this->request->getHeaderLine('User');
        $user_data = $this->db->table('app_users')->getWhere(['id'=>$id])->getRow();
        if(!empty($user_data))
        {
            $logs = json_decode($user_data->logs, true);
            $this->array_response['status'] = true;
            $this->array_response['message'] = 'Successful';
            // array_push($logs, ['action' => 'logout', 'status' => $this->array_response['status'], 'datetime' => date('Y-m-d H:i:s'), 'ip_address' => $this->request->getIPAddress(), 'message'=>$this->array_response['message']]);
            // $this->db->table('app_users')->update(['logs'=>json_encode($logs)], ['id'=>$user_data->id]);
            $this->session->destroy();
            // session()->destroy();
            $this->db->table('app_access')->update(['status'=>0, 'expired_time'=>date('Y-m-d H:i:s')], ['key'=>$key, 'token'=>$token, 'user_id'=>$user_data->id, 'status'=>1]);
        }else{
            $this->array_response['message'] = 'Session expired or toket not valid';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'GET, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }


    /*
    *   google OAuth
    */
    public function googleLogin()
    {
        $config_api = return_value_in_options('google');
        session()->set('key', $this->request->getGet('key'));
        $client = new Client();
        $client->setClientId($config_api['oauth']['client_id']);
        $client->setClientSecret($config_api['oauth']['client_secret']);
        $client->setRedirectUri(site_url('auth/google/callback'));
        $client->addScope('email');
        $client->addScope('profile');
        return redirect()->to($client->createAuthUrl());
    }

    public function googleCallback()
    {
        $config_api = return_value_in_options('google');
        $key = session()->get('key');
        $client = new Client();
        $client->setClientId($config_api['oauth']['client_id']);
        $client->setClientSecret($config_api['oauth']['client_secret']);
        $client->setRedirectUri(site_url('auth/google/callback'));
        $code = $this->request->getVar('code');
        if ($code) {
            $token = $client->fetchAccessTokenWithAuthCode($code);
            $client->setAccessToken($token);

            $google_oauth = new \Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();

            // Dapatkan email dan ID dari data Google
            $email = $google_account_info->email;
            $google_id = $google_account_info->id;
            $name = $google_account_info->name;
            $user_data = $this->db->query("SELECT * FROM app_users where LOWER(username)=? or LOWER(email)=? ", [strtolower($name),strtolower($email)])->getRow();
            if(!empty($user_data))
            {
                $logs = json_decode($user_data->logs, true);
                if($user_data->status==1)
                {
                    $token = $this->AuthModel->new_token_access($user_data);
                    $session = [
                        'login' => TRUE,
                        'id' => $user_data->id, 
                        'username' => $user_data->username, 
                        'email' => $user_data->email, 
                        'status' => $user_data->status,
                        'activation_key' => $user_data->activation_key,
                        'key' => $key,
                        'token' => $token,
                    ];
                    $this->AuthModel->session_pegawai($user_data->email);
                    $optionUser = $this->AuthModel->app_options_users($user_data->id);
                    if(!empty($optionUser))
                    {
                        $session['options'] = $optionUser;
                    }
                    $this->session->set($session);
                    session()->set('roles', $this->AuthModel->load_user_roles());
                    session()->setFlashdata('message', 'Successful');
                }else{
                    session()->setFlashdata('message', 'User or Password not Match');
                }
            }else{
                session()->setFlashdata('message', 'Akun '.$email.' tidak tervalidasi');
                return redirect()->to('auth/login');
            }
        }
        session()->setFlashdata('message', 'Key tidak valid => '.$code);
        return redirect()->to('auth/login');
    }


}
