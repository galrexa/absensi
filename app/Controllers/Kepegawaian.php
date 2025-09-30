<?php
namespace App\Controllers;
use App\Models\AuthModel;
use App\Models\KepegawaianModel;
use App\Models\PresensiModel;
// use ZipArchive;
// use RecursiveIteratorIterator;
// use RecursiveDirectoryIterator;

class Kepegawaian extends BaseController
{
    public function __construct()
    {
        $this->AuthModel = new AuthModel();
        session()->set('units', $this->AuthModel->units());
        $this->KepegawaianModel = new KepegawaianModel();
        $this->PresensiModel = new PresensiModel();
        $this->pegawai_id = session()->get('pegawai_id');
        session()->set('id_app', 2);
    }



    public function index()
    {
        $arr = [
            'title' => 'Kepegawaian',
            'page' => 'Kepegawaian/index',
            'data' => $this->db->query("SELECT * FROM app a where a.id=? ", [2])->getRow(),
        ];
        return view('tBase', $arr);
    }



    public function profile()
    {
        $id = ($this->request->getGet('id'))?:session()->get('pegawai_id');
        $tab = ($this->request->getGet('tab'))?:'';
        $data = $this->KepegawaianModel->pegawai_get_row($id);
        if(!empty($data) && (return_roles([1,2]) || $data->pegawai_id==$this->pegawai_id))
        {
            $arr = [
                'title' => 'Profile',
                'page' => 'Kepegawaian/profile',
                'data' => $data,
                'tab' => $tab,
            ];
            return view('tBase', $arr);
        }else{
            $this->session->setFlashdata('message', 'ID Anda tidak valid atau tidak mendapat akses');
            return redirect()->to('/');
        }
    }



    public function list_aktif()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->KepegawaianModel->pegawai_aktif();
            $q = $this->KepegawaianModel->pegawai_aktif(true);
            if(!empty($q)){
                $this->array_response['message'] = 'Successful';
                foreach ($q as $key){
                    array_push($data, $key);
                }
            }
            $this->array_response["draw"] = $draw;
            $this->array_response["recordsTotal"] = $jml;
            $this->array_response["recordsFiltered"] = $jml;
            $this->array_response['data'] = $data;
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Pegawaian Aktif',
                'page' => 'Kepegawaian/pegawai_list_aktif',
                'unit' => strip_tags($this->request->getGet('id')),
            ];
            return view('tBase', $arr);
        }
    }



    public function form()
    {
        $this->array_response['code'] = 200;
        $id = (string_to($this->request->getGet('id'),'decode'))?:0;
        $data = $this->KepegawaianModel->pegawai_get_row($id);
        $my_roles = $this->AuthModel->load_user_roles();
        if($_POST)
        {
            $setRules = [
                'nik' => [
                    'label' => 'NIK', 
                    'rules' => 'required|max_length[45]'
                ],
                'npwp' => [
                    'label' => 'NPWP', 
                    'rules' => 'required|max_length[25]'
                ],
                'nama' => [
                    'label' => 'Nama', 
                    'rules' => 'required|max_length[100]'
                ],
                'gelar_depan' => [
                    'label' => 'Gelar Depan', 
                    'rules' => 'max_length[45]'
                ],
                'gelar_belakang' => [
                    'label' => 'Gelar Belakang', 
                    'rules' => 'max_length[45]'
                ],
                'tanggal_lahir' => [
                    'label' => 'Tanggal Lahir', 
                    'rules' => 'required|max_length[10]'
                ],
                'tempat_lahir' => [
                    'label' => 'Tempat Lahir', 
                    'rules' => 'required|max_length[50]'
                ],
                'pendidikan' => [
                    'label' => 'Pendidikan', 
                    'rules' => 'required|integer|max_length[11]'
                ],
                'kelamin' => [
                    'label' => 'Kelamin', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'agama' => [
                    'label' => 'Agama', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'status_perkawinan' => [
                    'label' => 'Status Perkawinan', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'hp' => [
                    'label' => 'Nomor Handphone', 
                    'rules' => 'required|max_length[20]',
                ],
                'telp' => [
                    'label' => 'Telepon', 
                    'rules' => 'max_length[20]',
                ],
                'email' => [
                    'label' => 'Email Kantor', 
                    'rules' => 'required|max_length[120]|valid_email'
                ],
                'email_pribadi' => [
                    'label' => 'Email Pribadi', 
                    'rules' => 'max_length[120]',
                ],
            ];
            if(return_roles([1,2]))
            {
                $setRules['nip'] = ['label' => 'NIP', 'rules' => 'max_length[45]'];
                $setRules['unit_kerja_id'] = ['label' => 'Unit Kerja', 'rules' => 'required|integer|max_length[11]'];
                $setRules['jabatan_id'] = ['label' => 'Jabatan', 'rules' => 'required|integer|max_length[11]'];
                $setRules['eselon'] = ['label' => 'Eselon/Setingkat', 'rules' => 'max_length[50]'];
                $setRules['status_jenis_pegawai'] = ['label' => 'Status Jenis Pegawai', 'rules' => 'required|integer|max_length[11]'];
                $setRules['status_pns'] = ['label' => 'Status ASN', 'rules' => 'required|integer|max_length[11]'];
                $setRules['asal_instansi'] = ['label' => 'Instansi Asal', 'rules' => 'max_length[100]'];
                $setRules['nip_lama'] = ['label' => 'NIP Lama', 'rules' => 'max_length[45]'];
                $setRules['pangkat'] = ['label' => 'Pangkat', 'rules' => 'max_length[45]'];
                $setRules['gol'] = ['label' => 'Golongan', 'rules' => 'max_length[45]'];
                $setRules['tmt_pang_gol'] = ['label' => 'TMT', 'rules' => 'max_length[50]'];
                $setRules['universitas'] = ['label' => 'Universitas', 'rules' => 'max_length[11]'];
                // $setRules['kode_absen'] = ['label' => 'Kode Absen', 'rules' => 'max_length[10]'];
                // $setRules['idcard1'] = ['label' => 'IDCard1', 'rules' => 'integer|max_length[5]'];
                // $setRules['idcard2'] = ['label' => 'IDCard2', 'rules' => 'integer|max_length[5]'];
                // $setRules['gugustugas'] = ['label' => 'Tim Gugus Tugas', 'rules' => 'max_length[11]'];
                $setRules['status'] = ['label' => 'Status', 'rules' => 'required|integer|max_length[1]'];
                $setRules['bank_name'] = ['label' => 'Nama Bank', 'rules' => 'max_length[25]'];
                $setRules['bank_account'] = ['label' => 'Nomor Rekening', 'rules' => 'max_length[25]'];
                $setRules['bank_account_name'] = ['label' => 'Pemilik Rekening', 'rules' => 'max_length[250]'];
                $setRules['bank_region'] = ['label' => 'Kantor Cabang', 'rules' => 'max_length[250]'];
            }
            $this->validation->setRules($setRules);
            if($this->validation->run($_POST))
            {
                $pegawai_id = $this->request->getPost('pegawai_id');
                $status = $this->request->getPost('status');
                if(((return_roles([1,2])) || (!return_roles([1,2]) && $this->session->pegawai_id==$pegawai_id)) /*&& $id==$pegawai_id*/)
                {
                    $data_field = [
                        'nik' => strip_tags($this->request->getPost('nik')),
                        'npwp' => strip_tags($this->request->getPost('npwp')),
                        'nama' => strip_tags($this->request->getPost('nama')),
                        'gelar_depan' => strip_tags($this->request->getPost('gelar_depan')),
                        'gelar_belakang' => strip_tags($this->request->getPost('gelar_belakang')),
                        'tanggal_lahir' => strip_tags($this->request->getPost('tanggal_lahir')),
                        'tempat_lahir' => strip_tags($this->request->getPost('tempat_lahir')),
                        'pendidikan' => strip_tags($this->request->getPost('pendidikan')),
                        'agama' => $this->request->getPost('agama'),
                        'kelamin' => $this->request->getPost('kelamin'),
                        'status_perkawinan' => $this->request->getPost('status_perkawinan'),
                        'telp' => $this->request->getPost('telp'),
                        'hp' => $this->request->getPost('hp'),
                        'email' => strip_tags($this->request->getPost('email')),
                        'email_pribadi' => $this->request->getPost('email_pribadi'),
                    ];
                    if(return_roles([1,2]))
                    {
                        $data_field['nip'] = strip_tags($this->request->getPost('nip'));
                        $data_field['status_jenis_pegawai'] = strip_tags($this->request->getPost('status_jenis_pegawai'));
                        $data_field['unit_kerja_id'] = strip_tags($this->request->getPost('unit_kerja_id'));
                        $data_field['jabatan_id'] = strip_tags($this->request->getPost('jabatan_id'));
                        $data_field['eselon'] = strip_tags($this->request->getPost('eselon'));
                        $data_field['status_pns'] = strip_tags($this->request->getPost('status_pns'));
                        $data_field['asal_instansi'] = strip_tags($this->request->getPost('asal_instansi'));
                        $data_field['nip_lama'] = strip_tags($this->request->getPost('nip_lama'));
                        $data_field['pangkat'] = strip_tags($this->request->getPost('pangkat'));
                        $data_field['gol'] = strip_tags($this->request->getPost('gol'));
                        $data_field['tmt_pang_gol'] = strip_tags($this->request->getPost('tmt_pang_gol'));
                        $data_field['universitas'] = strip_tags($this->request->getPost('universitas'));
                        // $data_field['kode_absen'] = strip_tags($this->request->getPost('kode_absen'));
                        // $data_field['idcard1'] = strip_tags($this->request->getPost('idcard1'));
                        // $data_field['idcard2'] = strip_tags($this->request->getPost('idcard2'));
                        // $data_field['gugustugas'] = strip_tags($this->request->getPost('gugustugas'));
                        $data_field['status'] = $status;
                        $data_field['bank_name'] = strip_tags($this->request->getPost('bank_name'));
                        $data_field['bank_account'] = strip_tags($this->request->getPost('bank_account'));
                        $data_field['bank_account_name'] = strip_tags($this->request->getPost('bank_account_name'));
                        $data_field['bank_region'] = strip_tags($this->request->getPost('bank_region'));
                        $encrypter = \Config\Services::encrypter();
                        $username = (explode('@',$data_field['email']))[0];
                        $data_field_user = [
                            'username' => $username,
                            'email' => $data_field['email'],
                            'status' => $status,
                            'password' => bin2hex($encrypter->encrypt(str_replace(['-'], '', $data_field['tanggal_lahir']))),
                        ];
                    }
                    if(!empty($data))
                    {
                        $this->KepegawaianModel->pegawai_save($data_field, ['id'=>$id]);
                    }else{
                        $pegawai_id = $this->KepegawaianModel->pegawai_save($data_field);
                    }
                    if($this->db->affectedRows()>0)
                    {
                        $this->array_response['status'] = TRUE;
                        $this->array_response['message'] = 'Successful';
                        $this->array_response['id'] = $pegawai_id;
                        if(return_roles([1,2]))
                        {
                            if(!empty($data))
                            {
                                $cus = $this->AuthModel->check_user_by_email($data->email);
                                if(!empty($cus))
                                {
                                    $this->AuthModel->app_users_save(remove_key_in_array($data_field_user, ['password']), ['email'=>$data->email]);
                                }else{
                                    $data_field_user['activation_key'] = 1;
                                    $data_field_user['logs'] = '[]';
                                    $this->AuthModel->app_users_save($data_field_user);
                                }
                            }else{
                                $check_user = $this->AuthModel->check_user_by_username($username);
                                if(empty($check_user))
                                {
                                    $data_field_user['activation_key'] = 1;
                                    $data_field_user['logs'] = '[]';
                                    $this->AuthModel->app_users_save($data_field_user);
                                }else{

                                }
                            }
                        }
                    }else{
                        $this->array_response['message'] = 'Save failed';
                    }
                }else{
                    $this->array_response['message'] = 'ID Anda tidak valid atau tidak mendapat akses';
                }
            }else{
                $this->array_response['message'] = str_replace(['\n','\t'], '',strip_tags(json_encode($this->validation->listErrors())));
            }
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Form',
                'page' => 'Kepegawaian/pegawai_form',
                'data' => $data,
                'my_roles' => $my_roles,
            ];
            return view('tBase', $arr);
        }
    }



    public function list_non_aktif()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->KepegawaianModel->pegawai_non_aktif();
            $q = $this->KepegawaianModel->pegawai_non_aktif(true);
            if(!empty($q)){
                $this->array_response['message'] = 'Successful';
                foreach ($q as $key){
                    array_push($data, $key);
                }
            }
            $this->array_response["draw"] = $draw;
            $this->array_response["recordsTotal"] = $jml;
            $this->array_response["recordsFiltered"] = $jml;
            $this->array_response['data'] = $data;
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Pegawaian Non Aktif',
                'page' => 'Kepegawaian/pegawai_list_non_aktif',
            ];
            return view('tBase', $arr);
        }
    }



    public function alamat()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        $id = (string_to($this->request->getGet('id'),'decode'))?:session()->get('pegawai_id');
        $data = $this->KepegawaianModel->alamat_by_pegawai_id($id);
        if($_POST){}else{
            $arr = [
                'title' => 'List Alamat',
                'page' => 'Kepegawaian/pegawai_alamat',
                'id' => $id,
                'data' => $data,
            ];
            return view($arr['page'], $arr);
        }
    }

    public function alamat_form()
    {
        $this->array_response['code'] = 200;
        $id = (string_to($this->request->getGet('id'),'decode'))?:0;
        $pegawai_id = string_to($this->request->getGet('pegawai_id'),'decode');
        $data = $this->KepegawaianModel->alamat_get_row($id);
        if($_POST)
        {
            $this->validation->setRules([
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'integer|max_length[11]',
                ],
                'alamat_name' => [
                    'label' => 'Nama', 
                    'rules' => 'required|max_length[50]',
                ],
                'provinsi' => [
                    'label' => 'Provinsi', 
                    'rules' => 'required|max_length[13]',
                ],
                'kabupaten' => [
                    'label' => 'Kabupaten/Kota', 
                    'rules' => 'required|max_length[13]',
                ],
                'kecamatan' => [
                    'label' => 'Kecamatan', 
                    'rules' => 'required|max_length[13]',
                ],
                'kelurahan' => [
                    'label' => 'Kelurahan', 
                    'rules' => 'required|max_length[13]',
                ],
                'kodepos' => [
                    'label' => 'Kode Pos', 
                    'rules' => 'required|integer|max_length[6]',
                ],
                'rt' => [
                    'label' => 'RT', 
                    'rules' => 'required|integer|min_length[1]|max_length[5]',
                ],
                'rw' => [
                    'label' => 'RW', 
                    'rules' => 'required|integer|min_length[1]|max_length[5]',
                ],
                'alamat' => [
                    'label' => 'Alamat', 
                    'rules' => 'required|max_length[250]',
                ],
                'pegawai_id' => [
                    'label' => 'ID Pegawai', 
                    'rules' => 'required|integer|min_length[1]|max_length[11]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                if($pegawai_id==$this->request->getPost('pegawai_id') && $id==$this->request->getPost('id'))
                {
                    $data_field = [
                        'alamat_name' => strip_tags($this->request->getPost('alamat_name')),
                        'provinsi' => strip_tags($this->request->getPost('provinsi')),
                        'kabupaten' => strip_tags($this->request->getPost('kabupaten')),
                        'kecamatan' => strip_tags($this->request->getPost('kecamatan')),
                        'kelurahan' => strip_tags($this->request->getPost('kelurahan')),
                        'kodepos' => strip_tags($this->request->getPost('kodepos')),
                        'rt' => strip_tags($this->request->getPost('rt')),
                        'rw' => strip_tags($this->request->getPost('rw')),
                        'alamat' => strip_tags($this->request->getPost('alamat')),
                        'pegawai_id' => strip_tags($this->request->getPost('pegawai_id')),
                        'user_id' => $this->session->id,
                    ];
                    if(!empty($data))
                    {
                        $save_ = $this->KepegawaianModel->alamat_save($data_field, $id);
                    }else{
                        $save_ = $this->KepegawaianModel->alamat_save($data_field);
                    }
                    if($save_>0)
                    {
                        $this->array_response['status'] = TRUE;
                        $this->array_response['message'] = 'Successful';
                    }else{
                        $this->array_response['message'] = 'Save failed';
                    }
                }else{
                    $this->array_response['message'] = 'Maaf Anda tidak mendapat hak akses';
                }
            }else{
                $this->array_response['message'] = str_replace(['\n','\t'], '',strip_tags(json_encode($this->validation->listErrors())));
            }
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Form Alamat',
                'page' => 'Kepegawaian/pegawai_alamat_form',
                'pegawai_id' => $pegawai_id,
                'data' => $data,
            ];
            return view('tBase', $arr);
        }
    }



    public function sk()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        $id = (string_to($this->request->getGet('id'),'decode'))?:session()->get('pegawai_id');
        $data = $this->KepegawaianModel->sk_by_pegawai_id($id);
        if($_POST){}else{
            $arr = [
                'title' => 'List SK',
                'page' => 'Kepegawaian/pegawai_sk',
                'id' => $id,
                'data' => $data,
            ];
            return view($arr['page'], $arr);
        }
    }

    public function sk_form()
    {
        $this->array_response['code'] = 200;
        $id = (string_to($this->request->getGet('id'),'decode'))?:0;
        $pegawai_id = string_to($this->request->getGet('pegawai_id'),'decode');
        $data = $this->KepegawaianModel->sk_get_row($id);
        if($_POST)
        {
            $this->validation->setRules([
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'integer|max_length[11]',
                ],
                'jenis' => [
                    'label' => 'Jenis SK', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'nomor' => [
                    'label' => 'Nomor SK', 
                    'rules' => 'required|max_length[50]',
                ],
                'tanggal' => [
                    'label' => 'Tanggal SK', 
                    'rules' => 'required|max_length[10]',
                ],
                'periode_awal' => [
                    'label' => 'Tanggal Mulai', 
                    'rules' => 'required|max_length[10]',
                ],
                'periode_akhir' => [
                    'label' => 'Tanggal Berakhir', 
                    'rules' => 'required|max_length[10]',
                ],
                'unit_kerja_id' => [
                    'label' => 'Unit Kerja', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'jabatan_id' => [
                    'label' => 'Jabatan', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'pegawai_id' => [
                    'label' => 'ID Pegawai', 
                    'rules' => 'required|integer|min_length[1]|max_length[11]',
                ],
                'status' => [
                    'label' => 'Status', 
                    'rules' => 'required|integer|min_length[1]|max_length[1]',
                ],
                'dokumen_id' => [
                    'label' => 'Dokumen', 
                    'rules' => 'max_length[100]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                if($pegawai_id==$this->request->getPost('pegawai_id') && $id==$this->request->getPost('id'))
                {
                    $data_field = [
                        'jenis' => strip_tags($this->request->getPost('jenis')),
                        'nomor' => strip_tags($this->request->getPost('nomor')),
                        'tanggal' => strip_tags($this->request->getPost('tanggal')),
                        'periode_awal' => strip_tags($this->request->getPost('periode_awal')),
                        'periode_akhir' => strip_tags($this->request->getPost('periode_akhir')),
                        'keterangan' => strip_tags($this->request->getPost('keterangan')),
                        'dokumen' => trim($this->request->getPost('dokumen_id'),","),
                        'unit_kerja_id' => strip_tags($this->request->getPost('unit_kerja_id')),
                        'jabatan_id' => strip_tags($this->request->getPost('jabatan_id')),
                        'pegawai_id' => strip_tags($this->request->getPost('pegawai_id')),
                        'status' => strip_tags($this->request->getPost('status')),
                        'user_id' => $this->session->id,
                    ];
                    if(!empty($data))
                    {
                        $save_ = $this->KepegawaianModel->sk_save($data_field, $id);
                    }else{
                        $save_ = $this->KepegawaianModel->sk_save($data_field);
                    }
                    if($save_>0)
                    {
                        $this->array_response['status'] = TRUE;
                        $this->array_response['message'] = 'Successful';
                        if($this->request->getPost('status')==1)
                        {
                            $this->KepegawaianModel->pegawai_save_array(['unit_kerja_id'=>$this->request->getPost('unit_kerja_id'), 'jabatan_id'=>$this->request->getPost('jabatan_id')], ['pegawai_id'=>$this->request->getPost('pegawai_id')]);
                        }
                    }else{
                        $this->array_response['message'] = 'Save failed';
                    }
                }else{
                    $this->array_response['message'] = 'Maaf Anda tidak mendapat hak akses';
                }
            }else{
                $this->array_response['message'] = str_replace(['\n','\t'], '',strip_tags(json_encode($this->validation->listErrors())));
            }
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Form SK',
                'page' => 'Kepegawaian/pegawai_sk_form',
                'pegawai_id' => $pegawai_id,
                'data' => $data,
            ];
            return view('tBase', $arr);
        }
    }



    public function fasilitas()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        $id = (string_to($this->request->getGet('id'),'decode'))?:session()->get('pegawai_id');
        $data = $this->KepegawaianModel->fasilitas_by_pegawai_id($id);
        if($_POST){}else{
            $arr = [
                'title' => 'List Fasilitas',
                'page' => 'Kepegawaian/pegawai_fasilitas',
                'id' => $id,
                'data' => $data,
            ];
            return view($arr['page'], $arr);
        }
    }

    public function fasilitas_form()
    {
        $this->array_response['code'] = 200;
        $id = (string_to($this->request->getGet('id'),'decode'))?:0;
        $pegawai_id = string_to($this->request->getGet('pegawai_id'),'decode');
        $data = $this->KepegawaianModel->fasilitas_get_row($id);
        if($_POST)
        {
            $this->validation->setRules([
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'integer|max_length[11]',
                ],
                'ref_fasilitas_id' => [
                    'label' => 'Jenis Fasilitas', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'fasilitas_tgl' => [
                    'label' => 'Tanggal Pemberian', 
                    'rules' => 'required|max_length[10]',
                ],
                'fasilitas_value' => [
                    'label' => 'Catatan Pemberian', 
                    'rules' => 'required|max_length[250]',
                ],
                'status' => [
                    'label' => 'Status', 
                    'rules' => 'required|integer|max_length[1]',
                ],
                'tgl_dikembalikan' => [
                    'label' => 'Tanggal Pengembalian', 
                    'rules' => 'max_length[10]',
                ],
                'fasilitas_ket' => [
                    'label' => 'Catatan Pengembalian', 
                    'rules' => 'max_length[250]',
                ],
                'pegawai_id' => [
                    'label' => 'ID Pegawai', 
                    'rules' => 'required|integer|min_length[1]|max_length[11]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                if($pegawai_id==$this->request->getPost('pegawai_id') && $id==$this->request->getPost('id'))
                {
                    $data_field = [
                        'ref_fasilitas_id' => strip_tags($this->request->getPost('ref_fasilitas_id')),
                        'fasilitas_tgl' => strip_tags($this->request->getPost('fasilitas_tgl')),
                        'fasilitas_value' => strip_tags($this->request->getPost('fasilitas_value')),
                        'status' => strip_tags($this->request->getPost('status')),
                        'pegawai_id' => strip_tags($this->request->getPost('pegawai_id')),
                        'user_id' => $this->session->id,
                    ];
                    if($this->request->getPost('status')==2)
                    {
                        $data_field['tgl_dikembalikan'] = $this->request->getPost('tgl_dikembalikan');
                        $data_field['fasilitas_ket'] = $this->request->getPost('fasilitas_ket');
                    }
                    if(!empty($data))
                    {
                        $save_ = $this->KepegawaianModel->fasilitas_save($data_field, $id);
                    }else{
                        $save_ = $this->KepegawaianModel->fasilitas_save($data_field);
                    }
                    if($save_>0)
                    {
                        $this->array_response['status'] = TRUE;
                        $this->array_response['message'] = 'Successful';
                    }else{
                        $this->array_response['message'] = 'Save failed';
                    }
                }else{
                    $this->array_response['message'] = 'Maaf Anda tidak mendapat hak akses';
                }
            }else{
                $this->array_response['message'] = str_replace(['\n','\t'], '',strip_tags(json_encode($this->validation->listErrors())));
            }
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Form Fasilitas',
                'page' => 'Kepegawaian/pegawai_fasilitas_form',
                'pegawai_id' => $pegawai_id,
                'data' => $data,
            ];
            return view('tBase', $arr);
        }
    }



    public function files()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        $id = (string_to($this->request->getGet('id'),'decode'))?:session()->get('pegawai_id');
        $data = $this->KepegawaianModel->files_by_pegawai_id($id);
        if($_POST){}else{
            $arr = [
                'title' => 'List Files',
                'page' => 'Kepegawaian/pegawai_files',
                'id' => $id,
                'data' => $data,
            ];
            return view($arr['page'], $arr);
        }
    }



    public function qrcode_hash()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        $id = (string_to($this->request->getGet('id'),'decode'))?:session()->get('pegawai_id');
        $data = $this->KepegawaianModel->hash_link_by_pegawai_id($id);
        if($_POST){
            if(!empty($data)){
                @unlink($data->qrcode);
            }
            $x_pegawai = return_value_in_options('kepegawaian');
            $url_verifikasi = $x_pegawai['url_verifikasi'];//'https://ver.ksp.go.id/h/';
            $hash_pegawai_id = string_to($id.microtime(), 'encode');
            $qrcode_path_file = FCPATH.'assets/img/icons/pegawai/QR_Hash_'.$hash_pegawai_id.'.png';
            create_new_qrcode($url_verifikasi.$hash_pegawai_id, $qrcode_path_file, true);
            $this->KepegawaianModel->hash_link_save(['pegawai_id'=>$id,'url'=>$url_verifikasi,'id_hash'=>$hash_pegawai_id,'qrcode'=>$qrcode_path_file,'user_id'=>session()->get('id')]);
            $this->KepegawaianModel->hash_link_log_save(['pegawai_id'=>$id,'data_old'=>json_encode($data),'deskripsi'=>$this->request->getPost('alasan'),'user_id'=>session()->get('id')]);
            $this->array_response['POST']=$_POST;
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'ID-Card',
                'page' => 'Kepegawaian/pegawai_idcard',
                'id' => $id,
                'data' => $data,
            ];
            return view($arr['page'], $arr);
        }
    }

    public function idcard()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        $file = ($this->request->getGet('file'))?:'pdf';
        $id = (string_to($this->request->getGet('id'),'decode'))?:session()->get('pegawai_id');
        $data = $this->KepegawaianModel->hash_link_by_pegawai_id($id);
        if(!empty($data)){
            $arr = [
                'title' => 'ID-Card',
                'page' => 'Kepegawaian/pegawai_idcard_file',
                'id' => $id,
                'file' => $file,
                'data' => $data,
            ];
            return view($arr['page'], $arr);
        }else{
            $this->session->setFlashdata('message', 'Maaf, Data tidak tersedia');
            return redirect()->to('kepegawaian');
        }
    }


    // USER FORM
    public function user_form()
    {
        $this->array_response['code'] = 200;
        $id = (string_to($this->request->getGet('id'),'decode'))?:0;
        $data = $this->KepegawaianModel->user_pegawai_get_row($id);
        if($_POST)
        {
            $this->validation->setRules([
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'integer|max_length[11]',
                ],
                'username' => [
                    'label' => 'Username', 
                    'rules' => 'required|max_length[100]|min_length[3]',
                ],
                'email' => [
                    'label' => 'Tanggal Pemberian', 
                    'rules' => 'required|max_length[120]|min_length[5]|valid_email',
                ],
                'status' => [
                    'label' => 'Status', 
                    'rules' => 'required|integer|max_length[1]',
                ],
                'tpassword' => [
                    'label' => 'Password', 
                    'rules' => 'max_length[255]|min_length[8]',
                ],
                'tpassword2' => [
                    'label' => 'Retry Password', 
                    'rules' => 'max_length[255]|min_length[8]|matches[tpassword]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                if(((!empty($data))?$data->id:0)==$this->request->getPost('id'))
                {
                    $encrypter = \Config\Services::encrypter();
                    $data_field = [
                        'password' => bin2hex($encrypter->encrypt($this->request->getPost('tpassword'))),
                        'user_id' => $this->session->id,
                    ];
                    if(return_roles([1,2]))
                    {
                        $data_field['username'] = strip_tags($this->request->getPost('username'));
                        $data_field['email'] = strip_tags($this->request->getPost('email'));
                        $data_field['status'] = strip_tags($this->request->getPost('status'));
                    }
                    if(!empty($data))
                    {
                        $save_ = $this->KepegawaianModel->user_pegawai_save($data_field, $data->id);
                    }else{
                        $data_field['logs'] = '[]';
                        $data_field['activation_key'] = 1;
                        $save_ = $this->KepegawaianModel->user_pegawai_save($data_field);
                    }
                    if($save_>0)
                    {
                        $this->array_response['status'] = TRUE;
                        $this->array_response['message'] = 'Successful';
                    }else{
                        $this->array_response['message'] = 'Save failed';
                    }
                }else{
                    $this->array_response['message'] = 'Maaf Anda tidak mendapat hak akses';
                }
            }else{
                $this->array_response['message'] = str_replace(['\n','\t'], '',strip_tags(json_encode($this->validation->listErrors())));
            }
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Form User',
                'page' => 'Kepegawaian/pegawai_user_form',
                'pegawai_id' => $id,
                'data' => $data,
                'data_pegawai' => $this->KepegawaianModel->pegawai_get_row($id),
            ];
            return view($arr['page'], $arr);
        }
    }


    public function download_foto()
    {
        $data = $this->KepegawaianModel->prepare_download_foto();
        if(!empty($data))
        {
            foreach ($data as $key) {
                if($key->foto_pegawai<>''){
                    if(file_exists($key->foto_pegawai)){
                        $img = $key->foto_pegawai;
                        $ext = pathinfo($img, PATHINFO_EXTENSION);
                        if (copy($img, WRITEPATH.'_pegawai/temp_foto/'.str_replace([' ', '  '], '_', $key->unit_kerja_name).'___'.str_replace([' ', '  '], '_', $key->nama).'.'.$ext)){

                        }
                    }
                }
            }
            $fileName = 'ZipFileFoto_'.microtime(true).'.zip';
            $pathFileTempFoto = WRITEPATH.'_pegawai/temp_foto/';
            $pathFileZip = create_zip_file($fileName, $pathFileTempFoto);
            if(file_exists($pathFileZip)){
                // return $this->response->download($pathFileZip, null)->setFileName($fileName);
                // @unlink($pathFileZip);
                header('Content-Disposition: attachment; filename='.$fileName);
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($pathFileZip));
                ob_clean();
                flush();
                readfile($pathFileZip);
                @unlink($pathFileZip);
                exit;
            }else{
                session()->setFlashdata('message', 'Maaf, Path File tidak ditemukan');
                return redirect()->to('kepegawaian');
            }
        }else{
            $this->session->setFlashdata('message', 'Maaf, Data foto tidak tersedia');
            return redirect()->to('kepegawaian');
        }
    }

    public function download_data()
    {
        $data = $this->KepegawaianModel->prepare_download_data();
        if(!empty($data))
        {
            $page = $this->request->getGet('page');
            switch ($page) {
                case 'nonpns':
                    $title = 'DAFTAR TENAGA NON PNS SEKRETARIAT KANTOR STAF PRESIDEN';
                    $listJabatan = [40,47,48];
                    $page_view = 'Kepegawaian/pegawai_export_nonpns_to_excel';
                    break;
                case 'rekanan':
                    $title = 'REKAPITULASI SEMENTARA JUMLAH TENAGA REKANAN';
                    $listJabatan = [44,45,46,51];
                    $page_view = 'Kepegawaian/pegawai_export_rekanan_to_excel';
                    break;
                case 'sementara':
                    $title = 'REKAPITULASI SEMENTARA JUMLAH PEGAWAI SEMENTARA';
                    $listJabatan = [41];
                    $page_view = 'Kepegawaian/pegawai_export_sementara_to_excel';
                    break;
                case 'gugustugas':
                    $title = 'REKAPITULASI SEMENTARA JUMLAH PEGAWAI GUGUS TUGAS';
                    $listJabatan = [42];
                    $page_view = 'Kepegawaian/pegawai_export_gugustugas_to_excel';
                    break;
                case 'magang':
                    $title = 'REKAPITULASI SEMENTARA JUMLAH PEGAWAI MAGANG';
                    $listJabatan = [43];
                    $page_view = 'Kepegawaian/pegawai_export_magang_to_excel';
                    break;
                case 7:
                    $title = 'DAFTAR PEGAWAI SEKRETARIAT KANTOR STAF PRESIDEN';
                    $listJabatan = [11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,36,37,38,39,52,53,54,56,57,58,59,60,61,62,63,64,65,66,69];
                    $page_view = 'Kepegawaian/pegawai_export_asn_to_excel';
                    break;
                default:
                    $title = 'REKAPITULASI SEMENTARA JUMLAH PEJABAT DAN TENAGA PROFESIONAL';
                    $listJabatan = [10,55,35,49,50,1,2,3,4,5,6,7,8,9,67,68,70,71,72,73,74,75,76,77,78,79,80,81,82,83];
                    $page_view = 'Kepegawaian/pegawai_export_tp_to_excel';
                    break;
            }
            $arr = [
                'title' => $title,
                'page' => $page_view,
                'jabatan_id' => $listJabatan,
                'data' => $data,
            ];
            return view($arr['page'], $arr);
        }else{
            $this->session->setFlashdata('message', 'Maaf, Data tidak tersedia');
            return redirect()->to('kepegawaian');
        }
    }


    /*
    *   HAK KEUANGAN & BUKTI POTONG PAJAk
    */
    public function skp_client()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->KepegawaianModel->keuangan_client_datatable();
            $q = $this->KepegawaianModel->keuangan_client_datatable(true);
            if(!empty($q)){
                $this->array_response['message'] = 'Successful';
                foreach ($q as $key){
                    array_push($data, $key);
                }
            }
            $this->array_response["draw"] = $draw;
            $this->array_response["recordsTotal"] = $jml;
            $this->array_response["recordsFiltered"] = $jml;
            $this->array_response['data'] = $data;
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Informasi Hak Keuangan',
                'page' => 'Kepegawaian/skp_client',
                'list_tahun' => $this->PresensiModel->presensi_list_tahun(),
            ];
            return view('tBase', $arr);
        }
    }

    public function skp_master()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->KepegawaianModel->keuangan_datatable();
            $q = $this->KepegawaianModel->keuangan_datatable(true);
            if(!empty($q)){
                $this->array_response['message'] = 'Successful';
                foreach ($q as $key){
                    array_push($data, $key);
                }
            }
            $this->array_response["draw"] = $draw;
            $this->array_response["recordsTotal"] = $jml;
            $this->array_response["recordsFiltered"] = $jml;
            $this->array_response['data'] = $data;
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Daftar Informasi Hak Keuangan',
                'page' => 'Kepegawaian/skp_master',
                'list_tahun' => $this->PresensiModel->presensi_list_tahun(),
            ];
            return view('tBase', $arr);
        }
    }

    // generate file slip gaji for admin
    public function skp_master_generate()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Tidak ada file yang di generate';
        $search_value = $this->request->getPost('search');
        $periode = $this->request->getPost('periode');
        $unit_kerja = $this->request->getPost('unit_kerja');
        $jabatan = $this->request->getPost('jabatan');
        $q = $this->KepegawaianModel->keuangan_result($search_value, $periode, $unit_kerja, $jabatan);
        $t = 0; $a = 0; $b = 0;
        if(!empty($q)){
            foreach ($q as $key) if($key->file_sign==0){
                $t +=1;
                $rs = $this->KepegawaianModel->create_file_slip_gaji_pegawai($key);
                if($rs==true){
                    $a +=1;
                }else{
                    $b +=1;
                }
            }
        }
        $this->array_response['message'] = $t .' data diproses';
        if($a>0){
            $this->array_response['message'] .= ', '. $a .' berhasil';
        }
        if($b>0){
            $this->array_response['message'] .= ', '. $b .' gagal';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }

    // tte skp for kaset
    public function skp_master_tte()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Tidak ada file yang di tte';
        $periode = $this->request->getPost('periode');
        $jabatan = $this->request->getPost('jabatan');
        $unit_kerja = $this->request->getPost('unit_kerja');
        $search_value = $this->request->getPost('search');
        $passphrase = $this->request->getPost('passphrase');
        $q = $this->KepegawaianModel->keuangan_result($search_value, $periode, $unit_kerja, $jabatan);
        $t = 0; $a = 0; $b = 0;
        $msg = [];
        if(!empty($q)){
            $x_pegawai = return_value_in_options('kepegawaian');
            $ttd = $this->KepegawaianModel->kepala_sekretariat_get_data();
            foreach ($q as $key) if($key->file_sign==0 && $key->file<>'0'){
                $t +=1;
                $url_verifikasi = $x_pegawai['url_verifikasi'] . $key->unix_id .'&t=skp';
                $image_qrcode = create_new_qrcode($url_verifikasi, WRITEPATH.'temp_zip/'.$key->unix_id.'.png', true);
                $file_sign_output = str_replace(['.pdf', '.PDF'], '_sign.pdf', $key->file);
                add_info_tte_in_footer($key->file);
                $rs = bsre_sign_pdf($ttd->nik, $passphrase, $key->file, $file_sign_output, 'VISIBLE', '#', 65, 65, $image_qrcode);
                if($rs['status']==true){
                    $a +=1;
                    $this->KepegawaianModel->keuangan_save(['file'=>$file_sign_output, 'file_sign'=>1], ['unix_id'=>$key->unix_id]);
                    @unlink($key->file);
                }else{
                    $b +=1;
                    if(array_keys([2031],$rs['message']['status_code'])){
                        array_push($msg, $rs['message']['error']);
                    }
                }
                @unlink($image_qrcode);
            }
        }
        $this->array_response['message'] = $t .' data diproses';
        if($a>0){
            $this->array_response['message'] .= ', '. $a .' berhasil';
        }
        if($b>0){
            $this->array_response['message'] .= ', '. $b .' gagal';
        }
        if(!empty($msg)){
            $this->array_response['message'] .= '. Pesan kegagalan dari sistem: {'. implode(',', array_unique($msg)) .'}';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }



    /*
    *   BUKTI POTONG PAJAK
    */
    public function bpp_client()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->KepegawaianModel->pajak_client_datatable();
            $q = $this->KepegawaianModel->pajak_client_datatable(true);
            if(!empty($q)){
                $this->array_response['message'] = 'Successful';
                foreach ($q as $key){
                    array_push($data, $key);
                }
            }
            $this->array_response["draw"] = $draw;
            $this->array_response["recordsTotal"] = $jml;
            $this->array_response["recordsFiltered"] = $jml;
            $this->array_response['data'] = $data;
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Bukti Potong Pajak',
                'page' => 'Kepegawaian/bpp_client',
                'list_tahun' => $this->PresensiModel->presensi_list_tahun(),
            ];
            return view('tBase', $arr);
        }
    }

    public function bpp_master()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->KepegawaianModel->pajak_datatable();
            $q = $this->KepegawaianModel->pajak_datatable(true);
            if(!empty($q)){
                $this->array_response['message'] = 'Successful';
                foreach ($q as $key){
                    array_push($data, $key);
                }
            }
            $this->array_response["draw"] = $draw;
            $this->array_response["recordsTotal"] = $jml;
            $this->array_response["recordsFiltered"] = $jml;
            $this->array_response['data'] = $data;
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Daftar Bukti Potong Pajak',
                'page' => 'Kepegawaian/bpp_master',
                'list_tahun' => $this->PresensiModel->presensi_list_tahun(),
            ];
            return view('tBase', $arr);
        }
    }



    /*
    *   MASTER DATA
    */
    public function unit()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->KepegawaianModel->unit_kerja_datatable();
            $q = $this->KepegawaianModel->unit_kerja_datatable(true);
            if(!empty($q)){
                $this->array_response['message'] = 'Successful';
                foreach ($q as $key){
                    array_push($data, $key);
                }
            }
            $this->array_response["draw"] = $draw;
            $this->array_response["recordsTotal"] = $jml;
            $this->array_response["recordsFiltered"] = $jml;
            $this->array_response['data'] = $data;
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'List Unit Kerja',
                'page' => 'Kepegawaian/ms_unit',
            ];
            return view('tBase', $arr);
        }
    }

    public function unit_form()
    {
        $this->array_response['code'] = 200;
        $id = $this->request->getGet('id');
        $data = $this->KepegawaianModel->unit_kerja_get_row($id);
        if($_POST)
        {
            $this->validation->setRules([
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'integer|max_length[11]',
                ],
                'name' => [
                    'label' => 'Name', 
                    'rules' => 'required|max_length[100]',
                ],
                'name_alt' => [
                    'label' => 'Name Alt', 
                    'rules' => 'required|max_length[100]',
                ],
                'status' => [
                    'label' => 'Status', 
                    'rules' => 'required|integer|max_length[1]',
                ],
                'menu_link' => [
                    'label' => 'Status', 
                    'rules' => 'required|integer|max_length[1]',
                ],
                'urutan' => [
                    'label' => 'Nomor Urut', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'description' => [
                    'label' => 'Description', 
                    'rules' => 'max_length[200]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                $id = strip_tags($this->request->getPost('id'));
                $name = strip_tags($this->request->getPost('name'));
                $name_alt = strip_tags($this->request->getPost('name_alt'));
                $status = strip_tags($this->request->getPost('status'));
                $description = strip_tags($this->request->getPost('description'));
                if(!empty($data))
                {
                    $save_ = $this->KepegawaianModel->unit_kerja_save(['unit_kerja_name'=>$name, 'unit_kerja_name_alt'=>$name_alt, 'unit_kerja_status'=>$status, 'unit_kerja_description'=>$description, 'menu_link'=>strip_tags($this->request->getPost('menu_link')), 'urutan'=>strip_tags($this->request->getPost('urutan')), 'user_id'=>$this->session->id], $id);
                }else{
                    $save_ = $this->KepegawaianModel->unit_kerja_save(['unit_kerja_name'=>$name, 'unit_kerja_name_alt'=>$name_alt, 'unit_kerja_status'=>$status, 'unit_kerja_description'=>$description, 'menu_link'=>strip_tags($this->request->getPost('menu_link')), 'urutan'=>strip_tags($this->request->getPost('urutan')), 'user_id'=>$this->session->id]);
                }
                if($save_>0)
                {
                    $this->array_response['status'] = TRUE;
                    $this->array_response['message'] = 'Successful';
                }else{
                    $this->array_response['message'] = 'Save failed';
                }
            }else{
                $this->array_response['message'] = str_replace(['\n','\t'], '',strip_tags(json_encode($this->validation->listErrors())));
            }
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Unit Kerja Form',
                'page' => 'Kepegawaian/ms_unit_form',
                'data' => $data,
            ];
            return view('tBase', $arr);
        }
    }


    public function jabatan()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->KepegawaianModel->jabatan_datatable();
            $q = $this->KepegawaianModel->jabatan_datatable(true);
            if(!empty($q)){
                $this->array_response['message'] = 'Successful';
                foreach ($q as $key){
                    array_push($data, $key);
                }
            }
            $this->array_response["draw"] = $draw;
            $this->array_response["recordsTotal"] = $jml;
            $this->array_response["recordsFiltered"] = $jml;
            $this->array_response['data'] = $data;
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'List Jabatan',
                'page' => 'Kepegawaian/ms_jabatan',
            ];
            return view('tBase', $arr);
        }
    }

    public function jabatan_form()
    {
        $this->array_response['code'] = 200;
        $id = $this->request->getGet('id');
        $data = $this->KepegawaianModel->jabatan_get_row($id);
        if($_POST)
        {
            $this->validation->setRules([
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'integer|max_length[11]',
                ],
                'name' => [
                    'label' => 'Name', 
                    'rules' => 'required|max_length[100]',
                ],
                'status' => [
                    'label' => 'Status', 
                    'rules' => 'required|integer|max_length[1]',
                ],
                'description' => [
                    'label' => 'Description', 
                    'rules' => 'max_length[200]',
                ],
                'slot' => [
                    'label' => 'Slot', 
                    'rules' => 'integer|max_length[3]',
                ],
                'slot_terpakai' => [
                    'label' => 'Slot Terpakai', 
                    'rules' => 'integer|max_length[3]',
                ],
                'slot_kosong' => [
                    'label' => 'Slot Kosong', 
                    'rules' => 'integer|max_length[3]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                $id = strip_tags($this->request->getPost('id'));
                $name = strip_tags($this->request->getPost('name'));
                $status = strip_tags($this->request->getPost('status'));
                $description = strip_tags($this->request->getPost('description'));
                $slot = strip_tags($this->request->getPost('slot'));
                $slot_terpakai = strip_tags($this->request->getPost('slot_terpakai'));
                $slot_kosong = strip_tags($this->request->getPost('slot_kosong'));
                if(!empty($data))
                {
                    $save_= $this->KepegawaianModel->jabatan_save(['jabatan_name'=>$name, 'jabatan_status'=>$status, 'jabatan_description'=>$description, 'jabatan_slot'=>$slot, 'jabatan_slot_terpakai'=>$slot_terpakai, 'jabatan_slot_kosong'=>$slot_kosong, 'user_id'=>$this->session->id], $id);
                }else{
                    $save_= $this->KepegawaianModel->jabatan_save(['jabatan_name'=>$name, 'jabatan_status'=>$status, 'jabatan_description'=>$description, 'jabatan_slot'=>$slot, 'jabatan_slot_terpakai'=>$slot_terpakai, 'jabatan_slot_kosong'=>$slot_kosong, 'user_id'=>$this->session->id]);
                }
                if($save_>0)
                {
                    $this->array_response['status'] = TRUE;
                    $this->array_response['message'] = 'Successful';
                }else{
                    $this->array_response['message'] = 'Save failed';
                }
            }else{
                $this->array_response['message'] = str_replace(['\n','\t'], '',strip_tags(json_encode($this->validation->listErrors())));
            }
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Jabatan Form',
                'page' => 'Kepegawaian/ms_jabatan_form',
                'data' => $data,
            ];
            return view('tBase', $arr);
        }
    }



    // DINONAKTIFKAN
    public function list_tim()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->KepegawaianModel->pegawai_sk_tim_datatable();
            $q = $this->KepegawaianModel->pegawai_sk_tim_datatable(true);
            if(!empty($q)){
                $this->array_response['message'] = 'Successful';
                foreach ($q as $key){
                    array_push($data, $key);
                }
            }
            $this->array_response["draw"] = $draw;
            $this->array_response["recordsTotal"] = $jml;
            $this->array_response["recordsFiltered"] = $jml;
            $this->array_response['data'] = $data;
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Tim Kerja',
                'page' => 'Kepegawaian/pegawai_tim_kerja',
            ];
            return view('tBase', $arr);
        }
    }

    public function sk_tim_form()
    {
        $this->array_response['code'] = 200;
        $id = (string_to($this->request->getGet('id'),'decode'))?:0;
        $data = $this->KepegawaianModel->pegawai_sk_tim_get_row($id);
        if($_POST)
        {
            $this->validation->setRules([
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'integer|max_length[11]',
                ],
                'nomor_sk' => [
                    'label' => 'Nomor SK', 
                    'rules' => 'required|max_length[50]',
                ],
                'tgl_sk' => [
                    'label' => 'Tanggal SK', 
                    'rules' => 'required|max_length[10]',
                ],
                'tgl_awal' => [
                    'label' => 'Tanggal Mulai', 
                    'rules' => 'required|max_length[10]',
                ],
                'tgl_akhir' => [
                    'label' => 'Tanggal Berakhir', 
                    'rules' => 'required|max_length[10]',
                ],
                'keterangan' => [
                    'label' => 'Keterangan', 
                    'rules' => 'required|min_length[5]|max_length[250]',
                ],
                'status' => [
                    'label' => 'Status', 
                    'rules' => 'required|integer|min_length[1]|max_length[1]',
                ],
                'dokumen_id' => [
                    'label' => 'Dokumen', 
                    'rules' => 'max_length[100]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                if($id==$this->request->getPost('id'))
                {
                    $data_field = [
                        'nomor_sk' => strip_tags($this->request->getPost('nomor_sk')),
                        'tgl_sk' => strip_tags($this->request->getPost('tgl_sk')),
                        'tgl_awal' => strip_tags($this->request->getPost('tgl_awal')),
                        'tgl_akhir' => strip_tags($this->request->getPost('tgl_akhir')),
                        'keterangan' => strip_tags($this->request->getPost('keterangan')),
                        'file' => trim($this->request->getPost('dokumen_id'),","),
                        'status_sk' => strip_tags($this->request->getPost('status')),
                        'user_id' => $this->session->id,
                    ];
                    if(!empty($data))
                    {
                        $save_ = $this->KepegawaianModel->pegawai_sk_tim_save($data_field, $id);
                    }else{
                        $save_ = $this->KepegawaianModel->pegawai_sk_tim_save($data_field);
                    }
                    if($save_>0)
                    {
                        $this->array_response['status'] = TRUE;
                        $this->array_response['message'] = 'Successful';
                    }else{
                        $this->array_response['message'] = 'Save failed';
                    }
                }else{
                    $this->array_response['message'] = 'Maaf Anda tidak mendapat hak akses';
                }
            }else{
                $this->array_response['message'] = str_replace(['\n','\t'], '',strip_tags(json_encode($this->validation->listErrors())));
            }
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'SK TIM Form',
                'page' => 'Kepegawaian/pegawai_tim_kerja_form',
                'data' => $data,
            ];
            return view('tBase', $arr);
        }
    }

    public function sk_tim_detail()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $id = ($this->request->getGet('id'))?:0;
        $data = $this->KepegawaianModel->pegawai_sk_tim_get_row($id);
        if(!empty($data))
        {
            $arr = [
                'title' => 'Tim Kerja',
                'page' => 'Kepegawaian/pegawai_tim_kerja_detail',
                'data_detail' => $this->KepegawaianModel->pegawai_sk_tim_detail_by_skid($id),
                'data' => $data,
            ];
            return view('tBase', $arr);
        }else{
            $this->session->setFlashdata('message', 'ID tidak valid atau tidak mendapat akses');
            return redirect()->to('/');
        }
    }

    public function sk_tim_detail_store()
    {
        $this->array_response['code'] = 200;
        if($_POST)
        {
            $this->validation->setRules([
                'id_sk' => [
                    'label' => 'SK', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'pegawai_id' => [
                    'label' => 'Pegawai', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'source' => [
                    'label' => 'Source', 
                    'rules' => 'required|integer|max_length[1]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                $id_sk = strip_tags($this->request->getPost('id_sk'));
                $pegawai_id = strip_tags($this->request->getPost('pegawai_id'));
                $source = strip_tags($this->request->getPost('source'));
                $data_field = [
                    'id_sk_tim' => $id_sk,
                    'pegawai_id' => $pegawai_id,
                    'source' => $source,
                    'user_id' => $this->session->id,
                ];
                $data_source = $this->db->query("SELECT pegawai_id, jabatan_id, unit_kerja_id, nama from pegawai WHERE pegawai_id=? and status=?", [$pegawai_id,1])->getRow();
                if(!empty($data_source))
                {
                    $data_field['jabatan_id'] = $data_source->jabatan_id;
                    $data_field['unit_kerja_id'] = $data_source->unit_kerja_id;
                }
                $save_ = $this->KepegawaianModel->pegawai_sk_tim_detail_save($data_field);
                if($save_>0)
                {
                    $this->array_response['status'] = TRUE;
                    $this->array_response['message'] = 'Successful';
                }else{
                    $this->array_response['message'] = 'Save failed';
                }
            }else{
                $this->array_response['message'] = str_replace(['\n','\t'], '',strip_tags(json_encode($this->validation->listErrors())));
            }
        }else{
            $id = (string_to($this->request->getGet('id'),'decode'))?:0;
            $data = $this->KepegawaianModel->pegawai_sk_tim_detail_get_row($id);
            if(!empty($data))
            {
                $this->db->table('pegawai_sk_tim_detail')->delete(['id'=>$data->id]);
                if($this->db->affectedRows()>0)
                {
                    $this->array_response['status'] = TRUE;
                    $this->array_response['message'] = 'Successful';
                }else{
                    $this->array_response['message'] = 'Delete failed';
                }
                return redirect()->to('kepegawaian/tim/detail?id='.$data->id_sk_tim);
            }
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'POST, GET, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }



    public function ulang_tahun()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->KepegawaianModel->pegawai_berulang_tahun();
            $q = $this->KepegawaianModel->pegawai_berulang_tahun(true);
            if(!empty($q)){
                $this->array_response['message'] = 'Successful';
                foreach ($q as $key) if($key->countdown >= 0){
                    array_push($data, $key);
                }
                foreach ($q as $key) if($key->countdown < 0){
                    array_push($data, $key);
                }
            }
            $this->array_response["draw"] = $draw;
            $this->array_response["recordsTotal"] = $jml;
            $this->array_response["recordsFiltered"] = $jml;
            $this->array_response['data'] = $data;
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Pegawai Berulang Tahun',
                'page' => 'Kepegawaian/pegawai_ulang_tahun',
                'unit' => $this->request->getGet('id'),
            ];
            return view('tBase', $arr);
        }
    }

    public function gugustugas()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->KepegawaianModel->gugus_tugas_datatable();
            $q = $this->KepegawaianModel->gugus_tugas_datatable(true);
            if(!empty($q)){
                $this->array_response['message'] = 'Successful';
                foreach ($q as $key){
                    array_push($data, $key);
                }
            }
            $this->array_response["draw"] = $draw;
            $this->array_response["recordsTotal"] = $jml;
            $this->array_response["recordsFiltered"] = $jml;
            $this->array_response['data'] = $data;
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'List Gugus Tugas',
                'page' => 'Kepegawaian/ms_gugustugas',
            ];
            return view('tBase', $arr);
        }
    }

    public function gugustugas_form()
    {
        $this->array_response['code'] = 200;
        $id = $this->request->getGet('id');
        $data = $this->KepegawaianModel->gugus_tugas_get_row($id);
        if($_POST)
        {
            $this->validation->setRules([
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'integer|max_length[11]',
                ],
                'name' => [
                    'label' => 'Name', 
                    'rules' => 'required|max_length[100]',
                ],
                'status' => [
                    'label' => 'Status', 
                    'rules' => 'required|integer|max_length[1]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                $id = strip_tags($this->request->getPost('id'));
                $name = strip_tags($this->request->getPost('name'));
                $status = strip_tags($this->request->getPost('status'));
                $description = strip_tags($this->request->getPost('description'));
                if(!empty($data))
                {
                    $save_ = $this->KepegawaianModel->gugus_tugas_save(['gugustugas'=>$name, 'status'=>$status, 'user_id'=>$this->session->id], $id);
                }else{
                    $save_ = $this->KepegawaianModel->gugus_tugas_save(['gugustugas'=>$name, 'status'=>$status, 'user_id'=>$this->session->id]);
                }
                if($save_>0)
                {
                    $this->array_response['status'] = TRUE;
                    $this->array_response['message'] = 'Successful';
                }else{
                    $this->array_response['message'] = 'Save failed';
                }
            }else{
                $this->array_response['message'] = str_replace(['\n','\t'], '',strip_tags(json_encode($this->validation->listErrors())));
            }
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Gugus Tugas Form',
                'page' => 'Kepegawaian/ms_gugustugas_form',
                'data' => $data,
            ];
            return view('tBase', $arr);
        }
    }

    public function pt()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->KepegawaianModel->perguruan_tinggi_datatable();
            $q = $this->KepegawaianModel->perguruan_tinggi_datatable(true);
            if(!empty($q)){
                $this->array_response['message'] = 'Successful';
                foreach ($q as $key){
                    array_push($data, $key);
                }
            }
            $this->array_response["draw"] = $draw;
            $this->array_response["recordsTotal"] = $jml;
            $this->array_response["recordsFiltered"] = $jml;
            $this->array_response['data'] = $data;
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'List Perguruan Tinggi',
                'page' => 'Kepegawaian/ms_pt',
            ];
            return view('tBase', $arr);
        }
    }

    public function pt_form()
    {
        $this->array_response['code'] = 200;
        $id = $this->request->getGet('id');
        $data = $this->KepegawaianModel->perguruan_tinggi_get_row($id);
        if($_POST)
        {
            $this->validation->setRules([
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'integer|max_length[11]',
                ],
                'name' => [
                    'label' => 'Name', 
                    'rules' => 'required|max_length[350]',
                ],
                'alamat' => [
                    'label' => 'Alamat', 
                    'rules' => 'required|max_length[350]',
                ],
                'telp' => [
                    'label' => 'Telepon', 
                    'rules' => 'required|max_length[35]',
                ],
                'kota' => [
                    'label' => 'Kota', 
                    'rules' => 'required|max_length[100]',
                ],
                'negara' => [
                    'label' => 'Negara', 
                    'rules' => 'required|max_length[50]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                $id = $this->request->getPost('id');
                if(!empty($data))
                {
                    $save_ = $this->KepegawaianModel->perguruan_tinggi_save(['nama_pt'=>strip_tags($this->request->getPost('name')), 'alamat_pt'=>strip_tags($this->request->getPost('alamat')), 'telp_pt'=>strip_tags($this->request->getPost('telp')), 'kota_pt'=>strip_tags($this->request->getPost('kota')), 'negara_pt'=>strip_tags($this->request->getPost('negara')), 'user_id'=>$this->session->id], $id);
                }else{
                    $save_ = $this->KepegawaianModel->perguruan_tinggi_save(['nama_pt'=>strip_tags($this->request->getPost('name')), 'alamat_pt'=>strip_tags($this->request->getPost('alamat')), 'telp_pt'=>strip_tags($this->request->getPost('telp')), 'kota_pt'=>strip_tags($this->request->getPost('kota')), 'negara_pt'=>strip_tags($this->request->getPost('negara')), 'user_id'=>$this->session->id]);
                }
                if($save_>0)
                {
                    $this->array_response['status'] = TRUE;
                    $this->array_response['message'] = 'Successful';
                }else{
                    $this->array_response['message'] = 'Save failed';
                }
            }else{
                $this->array_response['message'] = str_replace(['\n','\t'], '',strip_tags(json_encode($this->validation->listErrors())));
            }
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Perguruan Tinggi Form',
                'page' => 'Kepegawaian/ms_pt_form',
                'data' => $data,
            ];
            return view('tBase', $arr);
        }
    }
    /*
    * END
    */
}