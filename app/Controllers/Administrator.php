<?php
namespace App\Controllers;
use App\Models\CpanelModel;
use App\Models\KepegawaianModel;
class Administrator extends BaseController
{
    public function __construct()
    {
        $this->CpanelModel = new CpanelModel();
        $this->KepegawaianModel = new KepegawaianModel();
    }


    public function index()
    {
        $arr = [
            'title' => 'Daftar Aplikasi',
            'page' => 'Cpanel/app',
            'id_app' => $this->request->getGet('id'),
            'data' => $this->db->query("SELECT * FROM app a where a.status in (0,1,2) ")->getResult(),
        ];
        return view('Cpanel/tBase', $arr);
    }


    public function app_form()
    {
        $this->array_response['code'] = 200;
        $id = $this->request->getGet('id');
        $data = $this->db->table('app')->getWhere(['id'=>$id])->getRow();
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
                    'rules' => 'max_length[65535]',
                ],
                'path' => [
                    'label' => 'ID Parent', 
                    'rules' => 'required|max_length[250]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                $id = $this->request->getPost('id');
                $name = $this->request->getPost('name');
                $status = $this->request->getPost('status');
                $description = $this->request->getPost('description');
                $path = $this->request->getPost('path');
                if(!empty($data))
                {
                    $this->db->table('app')->update(['name'=>$name, 'status'=>$status, 'description'=>$description, 'path'=>$path, 'user_id'=>$this->session->id], ['id'=>$id]);
                }else{
                    $this->db->table('app')->insert(['name'=>$name, 'status'=>$status, 'description'=>$description, 'path'=>$path, 'user_id'=>$this->session->id]);
                    $id = $this->db->insertID();
                }
                if($this->db->affectedRows()>0)
                {
                    $this->array_response['status'] = TRUE;
                    $this->array_response['message'] = 'Berhasil menyimpan data';
                }else{
                    $this->array_response['message'] = 'Gagal menyimpan data, atau tidak ada data yang diubah';
                }
            }else{
                $this->array_response['message'] = stripcslashes(strip_tags($this->validation->listErrors()));
            }
            $this->array_response['id'] = $id;
            session()->setFlashdata('message', $this->array_response['message']);
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'App Form & Module Sistem',
                'page' => 'Cpanel/app_form',
                'data' => $data,
            ];
            return view('Cpanel/tBase', $arr);
        }
    }


    public function roles()
    {
        $this->array_response['code'] = 200;
        $data = $this->db->table('app_roles')->get()->getResult();
        if(!empty($data))
        {
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Successful';
            $this->array_response['data'] = $data;
        }else{
            $this->array_response['message'] = 'Not found';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }

    public function role_form()
    {
        $this->array_response['code'] = 200;
        $id = $this->request->getGet('id');
        $data = $this->db->table('app_roles')->getWhere(['id'=>$id])->getRow();
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
                /*'id_role[]' => [
                    'label' => 'ID Roles', 
                    'rules' => 'max_length[200]',
                ],*/
            ]);
            if($this->validation->run($_POST))
            {
                $id_app = $this->request->getPost('id_app');
                $id_parent = $this->request->getPost('id_parent');
                $id = $this->request->getPost('id');
                $name = $this->request->getPost('name');
                $status = $this->request->getPost('status');
                $description = $this->request->getPost('description');
                if(!empty($data))
                {
                    $this->db->table('app_roles')->update(['name'=>$name, 'status'=>$status, 'description'=>$description, 'user_id'=>$this->session->id], ['id'=>$id]);
                }else{
                    $this->db->table('app_roles')->insert(['name'=>$name, 'status'=>$status, 'description'=>$description, 'user_id'=>$this->session->id]);
                    $id = $this->db->insertID();
                }
                if($this->db->affectedRows()>0)
                {
                    $this->array_response['status'] = TRUE;
                    $this->array_response['message'] = 'Berhasil menyimpan data';
                }else{
                    $this->array_response['message'] = 'Gagal menyimpan data, atau tidak ada data yang diubah';
                }

            }else{
                $this->array_response['message'] = stripcslashes(strip_tags($this->validation->listErrors()));
            }
            $this->array_response['id'] = $id;
            session()->setFlashdata('message', $this->array_response['message']);
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Role Form',
                'page' => 'Cpanel/role_form',
                'data' => $data,
            ];
            return view('Cpanel/tBase', $arr);
        }
    }


    public function modules()
    {
        $this->array_response['code'] = 200;
        $id = $this->request->getGet('id');
        $data = $this->db->table('app_modules')->getWhere(['id_app'=>$id])->getResult();
        if(!empty($data))
        {
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Successful';
            $this->array_response['data'] = $data;
        }else{
            $this->array_response['message'] = 'Not found';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }

    public function module_form()
    {
        $this->array_response['code'] = 200;
        $id = $this->request->getGet('id');
        $app = $this->request->getGet('app');
        $data = $this->db->table('app_modules')->getWhere(['id'=>$id])->getRow();
        if($_POST)
        {
            $this->validation->setRules([
                'id_app' => [
                    'label' => 'ID App', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'id_parent' => [
                    'label' => 'ID Parent', 
                    'rules' => 'required|integer|max_length[11]',
                ],
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
                'id_role[]' => [
                    'label' => 'ID Roles', 
                    'rules' => 'max_length[200]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                $id_app = $this->request->getPost('id_app');
                $id_parent = $this->request->getPost('id_parent');
                $id = $this->request->getPost('id');
                $name = $this->request->getPost('name');
                $status = $this->request->getPost('status');
                $description = $this->request->getPost('description');
                if(!empty($data))
                {
                    $this->db->table('app_modules')->update(['id_app'=>$id_app, 'id_parent'=>$id_parent, 'name'=>$name, 'status'=>$status, 'description'=>$description, 'user_id'=>$this->session->id], ['id'=>$id]);
                }else{
                    $this->db->table('app_modules')->insert(['id_app'=>$id_app, 'id_parent'=>$id_parent, 'name'=>$name, 'status'=>$status, 'description'=>$description, 'user_id'=>$this->session->id]);
                    $id = $this->db->insertID();
                }
                // set role bac
                $id_role = $this->request->getPost('id_role');
                if(!empty($id_role))
                {
                    $this->db->query("DELETE FROM `app_roles_bac` WHERE id_module=? ", [$id]);
                    foreach ($id_role as $key => $value) {
                        $this->db->table('app_roles_bac')->insert(['id_role'=>$value, 'id_app'=>$id_app, 'id_module'=>$id, 'user_id'=>$this->session->id]);
                    }
                }else{
                    $this->db->query("DELETE FROM `app_roles_bac` WHERE id_module=? ", [$id]);
                }
                if($this->db->affectedRows()>0)
                {
                    $this->array_response['status'] = TRUE;
                    $this->array_response['message'] = 'Berhasil menyimpan data';
                }else{
                    $this->array_response['message'] = 'Gagal menyimpan data, atau tidak ada data yang diubah';
                }

            }else{
                $this->array_response['message'] = stripcslashes(strip_tags($this->validation->listErrors()));
            }
            $this->array_response['id'] = $id;
            session()->setFlashdata('message', $this->array_response['message']);
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Module Form',
                'page' => 'Cpanel/module_form',
                'dataListRoles' => $this->db->query("SELECT id, name FROM app_roles ar where ar.status in (1) ")->getResult(),
                'dataListParent' => $this->db->query("SELECT * FROM app_modules am where am.id_app=? and am.status in (1) and am.id_parent in (0) ", [$app])->getResult(),
                'data' => $data,
                'id_app' => $app,
                'dataRolesAccess' => $this->db->query("SELECT id_role as id FROM app_roles_bac arb where arb.id_module=? ", [$id])->getResult(),
            ];
            return view('Cpanel/tBase', $arr);
        }
    }

    /*
    * data
    */
    public function konfigurasi()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->CpanelModel->konfigurasi_list();
            $q = $this->CpanelModel->konfigurasi_list(true);
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
                'title' => 'List Konfigurasi',
                'page' => 'Cpanel/konfigurasi',
            ];
            return view('Cpanel/tBase', $arr);
        }
    }

    public function konfigurasi_form()
    {
        $this->array_response['code'] = 200;
        $id = $this->request->getGet('id');
        $data = $this->CpanelModel->konfigurasi_get_row($id);
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
                'value' => [
                    'label' => 'konfigurasi', 
                    'rules' => 'required|max_length[65535]',
                ],
                'description' => [
                    'label' => 'Description', 
                    'rules' => 'max_length[200]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                $id = $this->request->getPost('id');
                $name = $this->request->getPost('name');
                $status = $this->request->getPost('status');
                $description = $this->request->getPost('description');
                if(!empty($data))
                {
                    $save_ = $this->CpanelModel->konfigurasi_save([
                            'name'=>$name, 
                            'status'=>$status, 
                            'description'=>$description, 
                            'value'=>$this->request->getPost('value'), 
                            'user_id'=>$this->session->id
                        ], $id);
                }else{
                    $save_ = $this->CpanelModel->konfigurasi_save([
                            'name'=>$name, 
                            'status'=>$status, 
                            'description'=>$description, 
                            'value'=>$this->request->getPost('value'), 
                            'user_id'=>$this->session->id
                        ]);
                }
                if($save_>0)
                {
                    $this->array_response['status'] = TRUE;
                    $this->array_response['message'] = 'Successful';
                }else{
                    $this->array_response['message'] = 'Save failed';
                }
            }else{
                $this->array_response['message'] = stripcslashes(strip_tags($this->validation->listErrors()));
            }
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Konfigurasi Form',
                'page' => 'Cpanel/konfigurasi_form',
                'data' => $data,
            ];
            return view('Cpanel/tBase', $arr);
        }
    }


    public function referensi()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->CpanelModel->referensi_list();
            $q = $this->CpanelModel->referensi_list(true);
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
                'title' => 'List Referensi',
                'page' => 'Cpanel/referensi',
                'list_ref' => $this->db->query("SELECT distinct(ref) as field from app_referensi ")->getResult(),
            ];
            return view('Cpanel/tBase', $arr);
        }
    }

    public function referensi_form()
    {
        $this->array_response['code'] = 200;
        $id = $this->request->getGet('id');
        $data = $this->CpanelModel->referensi_get_row($id);
        if($_POST)
        {
            $this->validation->setRules([
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'integer|max_length[11]',
                ],
                'ref_name' => [
                    'label' => 'Name', 
                    'rules' => 'required|max_length[100]',
                ],
                'ref_status' => [
                    'label' => 'Status', 
                    'rules' => 'required|integer|max_length[1]',
                ],
                'ref' => [
                    'label' => 'Referensi', 
                    'rules' => 'required|max_length[25]',
                ],
                'ref_code' => [
                    'label' => 'Code', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'ref_value' => [
                    'label' => 'Value', 
                    'rules' => 'max_length[11]',
                ],
                'ref_description' => [
                    'label' => 'Description', 
                    'rules' => 'max_length[250]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                $id = $this->request->getPost('id');
                $ref_name = $this->request->getPost('ref_name');
                $ref_status = $this->request->getPost('ref_status');
                $ref_description = $this->request->getPost('ref_description');
                if(!empty($data))
                {
                    $save_ = $this->CpanelModel->referensi_save([
                            'ref_name'=>$ref_name, 
                            'ref_status'=>$ref_status, 
                            'ref_description'=>$ref_description, 
                            'ref'=>$this->request->getPost('ref'), 
                            'ref_code'=>$this->request->getPost('ref_code'), 
                            'ref_value'=>$this->request->getPost('ref_value'), 
                            'user_id'=>$this->session->id
                        ], $id);
                }else{
                    $save_ = $this->CpanelModel->referensi_save([
                            'ref_name'=>$ref_name, 
                            'ref_status'=>$ref_status, 
                            'ref_description'=>$ref_description, 
                            'ref'=>$this->request->getPost('ref'), 
                            'ref_code'=>$this->request->getPost('ref_code'), 
                            'ref_value'=>$this->request->getPost('ref_value'), 
                            'user_id'=>$this->session->id
                        ]);
                }
                if($save_>0)
                {
                    $this->array_response['status'] = TRUE;
                    $this->array_response['message'] = 'Successful';
                }else{
                    $this->array_response['message'] = 'Save failed';
                }
            }else{
                $this->array_response['message'] = stripcslashes(strip_tags($this->validation->listErrors()));
            }
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Referensi Form',
                'page' => 'Cpanel/referensi_form',
                'data' => $data,
            ];
            return view('Cpanel/tBase', $arr);
        }
    }


    public function pengguna()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->CpanelModel->pengguna_list();
            $q = $this->CpanelModel->pengguna_list(true);
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
                'title' => 'List Pengguna',
                'page' => 'Cpanel/pengguna',
            ];
            return view('Cpanel/tBase', $arr);
        }
    }

    public function pengguna_form()
    {
        $this->array_response['code'] = 200;
        $id = (string_to($this->request->getGet('id'),'decode'))?:0;
        // $id = $this->request->getGet('id');
        $data = $this->AuthModel->get_user_by_id($id);
        if($_POST)
        {
            $min_length_pw = 8;
            if(return_roles([1,2])){
                $min_length_pw = 0;
            }
            $setRules = [
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'integer|max_length[11]',
                ],
                'tpassword' => [
                    'label' => 'Password', 
                    'rules' => 'max_length[255]|min_length['.$min_length_pw.']',
                ],
                'tpassword2' => [
                    'label' => 'Retry Password', 
                    'rules' => 'max_length[255]|min_length['.$min_length_pw.']|matches[tpassword]',
                ],
            ];
            if(return_roles([1,2]))
            {
                $setRules['username'] = ['label' => 'Username', 'rules' => 'required|max_length[100]|min_length[3]',];
                $setRules['email'] = ['label' => 'Tanggal Pemberian', 'rules' => 'required|max_length[120]|min_length[5]|valid_email',];
                $setRules['status'] = ['label' => 'Status', 'rules' => 'required|integer|max_length[1]',];
                $setRules['role[]'] = ['label' => 'Role', 'rules' => 'max_length[50]',];
            }
            $this->validation->setRules($setRules);
            if($this->validation->run($_POST))
            {
                if(((!empty($data))?$data->id:0)==$this->request->getPost('id'))
                {
                    $encrypter = \Config\Services::encrypter();
                    $data_field = [
                        'user_id' => $this->session->id,
                        'last_change' => date('Y-m-d H:i:s'),
                    ];
                    if($this->request->getPost('tpassword')<>'')
                    {
                        $data_field['password'] = bin2hex($encrypter->encrypt($this->request->getPost('tpassword')));
                    }
                    if(return_roles([1,2]))
                    {
                        $data_field['username'] = strip_tags($this->request->getPost('username'));
                        $data_field['email'] = strip_tags($this->request->getPost('email'));
                        $data_field['status'] = strip_tags($this->request->getPost('status'));
                    }
                    if(!empty($data))
                    {
                        session()->set('activation_key', 0);
                        $data_field['activation_key'] = 0;
                        $save_ = $this->KepegawaianModel->user_pegawai_save($data_field, $data->id);
                    }else{
                        $data_field['logs'] = '[]';
                        $data_field['activation_key'] = 1;
                        $save_ = $this->KepegawaianModel->user_pegawai_save($data_field);
                    }
                    if($this->db->affectedRows() > 0)
                    {
                        $this->array_response['status'] = TRUE;
                        $this->array_response['message'] = 'Successful';
                        if(return_roles([1,2]))
                        {
                            $roles = $this->request->getPost('role');
                            $this->AuthModel->remove_user_role(['id_user'=>$save_]);
                            if(!empty($roles))
                            {
                                foreach($roles as $k=>$v){
                                    $this->AuthModel->user_roles_save(['id_user'=>$save_, 'id_role'=>$v, 'user_id'=>session()->get('id')]);
                                }
                            }
                        }
                    }else{
                        $this->array_response['message'] = 'Save failed';
                    }
                }else{
                    $this->array_response['message'] = 'Maaf Anda tidak mendapat hak akses';
                }
            }else{
                $this->array_response['message'] = stripcslashes(strip_tags($this->validation->listErrors()));
            }
            $this->session->setFlashdata('message', $this->array_response['message']);
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'User Form',
                'page' => 'Client/user_form',
                'data' => $data,
                'user_role' => $this->AuthModel->user_roles($id),
                'list_role' => $this->AuthModel->list_roles(),
            ];
            return view('tBaseBox', $arr);
        }
    }
}