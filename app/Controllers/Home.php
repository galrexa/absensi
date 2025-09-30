<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if(session()->get('id'))
        {
            return $this->list_modul();
        }else{
            // return view('welcome_message');
            // return view('tBaseBox');
            session()->setFlashdata('message', 'Maaf, Anda harus login terlebih dahulu.');
            return redirect()->to('auth/login');
        }
    }


    /*
    *   LIST MODUL
    */
    public function list_modul(): string
    {
        $arr_view = [
            'title' => 'List Layanan',
            'page' => 'list_modul',
            'test_uri' => $this->uri->getSegments(),
            'data_layanan' => $this->AuthModel->app_list()
        ];
        // return view('welcome_message', $arr_view);
        return view('tBaseBox', $arr_view);
    }


    public function routing()
    {
        $id = (string_to($this->request->getGet('id'),'decode'))?:'';
        $data_layanan = $this->AuthModel->app_get_row($id);
        if(!empty($data_layanan))
        {
            session()->set('id_app', $data_layanan->id);
            $this->AuthModel->save_log([
                'reff' => 'log_akses_layanan',
                'message' => 'User '.session()->get('username').' mengakses modul '.$data_layanan->name,
                'module' => $data_layanan->id,
            ]);
            session()->setFlashdata('message', 'Selamat datang di layanan '.$data_layanan->name);
            return redirect()->to($data_layanan->path);
        }else{
            session()->setFlashdata('message', 'Maaf, Layanan tidak tersedia atau tidak aktif.');
            return redirect()->to('/');
        }
    }

    public function test()
    {
        $arr_view = [
            'title' => 'Test Page',
            // 'page' => '',
            'test_uri' => $this->uri->getSegments(),
            'data_layanan' => $this->AuthModel->app_list()
        ];
        return view('tBase', $arr_view);
        // return view('tBaseBox', $arr_view);
    }


    /*
    *   UPLOAD FILE
    */
    public function do_upload_file()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = false;
        if($_POST)
        {
            $first = $this->request->getPost('first');
            $output = ($this->request->getPost('output'))?:'page';
            switch ($first) {
                case 'bpp':
                case 'surat_ext':
                    $mime_in = '|mime_in[userfile,application/pdf,application/x-pdf]';
                    break;
                case 'selfi_cam':
                case 'ktp':
                case 'bpjs_kesehatan':
                case 'bpjs_tk':
                case 'npwp':
                case 'foto_':
                case 'icon_app':
                    $mime_in = '|mime_in[userfile,image/jpg,image/jpeg,image/png,image/bmp]';
                    break;
                case 'spesimen_ttd':
                    $mime_in = '|mime_in[userfile,image/png]';
                    break;
                case 'cert_tte_':
                    $mime_in = '|mime_in[userfile,application/x-pkcs12,application/x-pkcs10,application/pkcs10,application/x-pkcs7-signature]';
                    break;
                case 'lampiran_surat':
                case 'lampiran_cuti':
                case 'kk':
                case 'sk':
                case 'sk_tim_':
                    $mime_in = '|mime_in[userfile,image/jpg,image/jpeg,image/gif,image/png,image/webp,image/bmp,application/pdf,application/x-pdf]';
                    break;
                default:
                    $mime_in = '|mime_in[userfile,image/jpg,image/jpeg,image/gif,image/png,image/webp,image/bmp,application/pdf,application/x-pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,text/csv,application/vnd.oasis.opendocument.presentation,application/vnd.oasis.opendocument.spreadsheet,application/vnd.oasis.opendocument.text,application/x-pkcs12,application/x-pkcs10,application/pkcs10,application/x-pkcs7-signature]';
                    break;
            }
            $this->validation->setRules([
                'userfile' => [
                    'label' => 'File Upload',
                    'rules' => 'uploaded[userfile]'
                        . $mime_in
                        . '|max_size[userfile,2000]'
                        // . '|ext_in[userfile,png,jpg,gif]'
                        // . '|is_image[userfile]'
                        // . '|max_dims[userfile,1024,768]'
                    ],
                'output' => ['rules'=>'required|max_length[5]'],
                'first' => ['rules'=>'max_length[25]']
            ]);
            if($this->validation->run($_POST))
            {
                $app_id = $this->request->getGet('app_id');
                $pegawai_id = $this->request->getGet('pegawai_id');
                $file_jenis = $this->request->getGet('file_jenis');
                $periode = $this->request->getGet('periode');
                $file_upload = $this->request->getFile('userfile');
                if (! $path = $file_upload->store()) {
                    $data =  ['error' => "Upload failed"];
                    $this->array_response['message'] = 'Upload failed';
                } else {
                    $data = [
                        'client_name' => $file_upload->getClientName(),
                        'temp_name' => $file_upload->getTempName(),
                        'extention' => $file_upload->getClientExtension(),
                        'mime_type' => $file_upload->getClientMimeType(),
                        'user_id' => (session()->get('id'))?:$this->request->getHeaderLine('User'),
                    ];
                    $name = $first .'_'.$pegawai_id.'_'. $file_upload->getName();
                    $source = WRITEPATH . 'uploads/' . $path;
                    $target = WRITEPATH . 'uploads/';
                    if(array_keys([/*'ktp_','kk_','bpjs_kesehatan_','bpjs_tk_','npwp_','cert_tte_',*/'foto_'/*,'sk_'*/,'sk_tim_'], $first)){
                        $target = WRITEPATH . '_pegawai/';
                    }
                    if(array_keys(['ktp','kk','bpjs_kesehatan','bpjs_tk','npwp','cert_tte','spesimen_ttd','bpp','sk','sk_tim'], $first)){
                        $target = WRITEPATH . '_pegawai/'.$first.'/';
                    }
                    if(array_keys(['icon_app'], $first)){
                        $target = FCPATH . 'assets/img/icons/apps/';
                    }
                    if(array_keys(['lampiran_cuti'], $first)){
                        $target = WRITEPATH . 'files_cuti/';
                    }
                    if(array_keys(['surat_ext', 'lampiran_surat'], $first)){
                        $target = WRITEPATH . 'files_persuratan/';
                    }
                    if(array_keys(['selfi_cam', 'lampiran_laporan_harian'], $first)){
                        $target = WRITEPATH . 'files_presensi/';
                    }
                    $file = new \CodeIgniter\Files\File($source);
                    $data['size'] = $file->getSizeByUnit('mb');
                    if( $file->move($target, $name) ){
                        $data['name'] = $name;
                        $data['path'] = $target . $name;
                        $this->db->table('app_files')->insert($data);
                        $data['id'] = $this->db->insertID();
                        $data['id_hash'] = string_to($data['id'], 'encode');
                        $this->array_response['status'] = TRUE;
                        $this->array_response['message'] = 'Successful';
                        /*  SET IN OTHER TABLE*/
                        if(array_keys(['ktp_','kk_','bpjs_kesehatan_','bpjs_tk_','npwp_','cert_tte_','ktp','kk','bpjs_kesehatan','bpjs_tk','npwp','cert_tte','spesimen_ttd'], $first))
                        {
                            $qf = $this->db->query("SELECT * FROM pegawai_files WHERE pegawai_id=? and file_jenis=? ", [$pegawai_id, $file_jenis])->getRow();
                            if(!empty($qf))
                            {
                                @unlink($qf->file_path);
                                $this->db->table('app_files')->delete(['path'=>$qf->file_path]);
                                $this->db->table('pegawai_files')->update(['file_path'=>$data['path'], 'file_id'=>$data['id'], 'user_id'=>session()->get('id')], ['pegawai_id'=>$pegawai_id, 'file_jenis'=>$file_jenis]);
                            }else{
                                $this->db->table('pegawai_files')->insert(['pegawai_id'=>$pegawai_id, 'file_jenis'=>$file_jenis, 'file_path'=>$data['path'], 'file_id'=>$data['id'], 'user_id'=>session()->get('id')]);
                            }
                        }
                        // FOTO PEGAWAI
                        if($first=='foto_')
                        {
                            $qf = $this->db->query("SELECT foto_pegawai as file_path, foto_pegawai_temp as file_path2 FROM pegawai WHERE pegawai_id=? ", [$pegawai_id])->getRow();
                            if(!empty($qf))
                            {
                                @unlink($qf->file_path);
                                @unlink($qf->file_path2);
                                $this->db->table('app_files')->delete(['path'=>$qf->file_path]);
                            }
                            $thumbnail = FCPATH.'assets/img/icons/pegawai/peg'.$pegawai_id.'.'.$data['extention'];
                            create_thumbnail_image($data['path'], $thumbnail);
                            $this->db->table('pegawai')->update(['foto_pegawai'=>$data['path'],'foto_pegawai_temp'=>$thumbnail], ['pegawai_id'=>$pegawai_id]);
                        }
                        // ICON APP
                        if($first=='icon_app')
                        {
                            $qf = $this->db->query("SELECT icon as file_path FROM app WHERE id=? ", [$app_id])->getRow();
                            if(!empty($qf))
                            {
                                @unlink($qf->file_path);
                                $this->db->table('app_files')->delete(['path'=>$qf->file_path]);
                            }
                            $this->db->table('app')->update(['icon'=>$data['path']], ['id'=>$app_id]);
                        }
                        // FOR BUKTI POTONG PAJAK
                        if($first=='bpp')
                        {
                            $qf = $this->db->query("SELECT id, file_path, file_id FROM bukti_potong_pajak WHERE pegawai_id=? and periode=?", [$pegawai_id, $periode])->getRow();
                            if(!empty($qf))
                            {
                                @unlink($qf->file_path);
                                $this->db->table('app_files')->delete(['path'=>$qf->file_path]);
                                $this->db->table('bukti_potong_pajak')->update(['file_path'=>$data['path'],'file_id'=>$data['id']], ['id'=>$qf->id]);
                            }else{
                                $this->db->table('bukti_potong_pajak')->insert(['file_path'=>$data['path'],'file_id'=>$data['id'], 'pegawai_id'=>$pegawai_id, 'periode'=>$periode, 'user_id'=>session()->get('id')]);
                            }
                        }
                        /*  END*/
                        /*
                        *   FOR PERSURATAN
                        */
                        // if($first=='surat_ext')
                        // {
                        //     // SURAT MASUK DARI EXTERNAL/KL
                        //     $qf = $this->db->query("SELECT id, path, path_sign FROM surat WHERE id=?", [$pegawai_id, $periode])->getRow();
                        //     if(!empty($qf))
                        //     {
                        //         @unlink($qf->path_sign);
                        //         $this->db->table('app_files')->delete(['path'=>$qf->path_sign]);
                        //         $this->db->table('bukti_potong_pajak')->update(['path_sign'=>$data['path'],'file_id'=>$data['id']], ['id'=>$qf->id]);
                        //     }else{
                        //         $this->db->table('bukti_potong_pajak')->insert(['path_sign'=>$data['path'],'file_id'=>$data['id'], 'pegawai_id'=>$pegawai_id, 'periode'=>$periode, 'user_id'=>session()->get('id')]);
                        //     }
                        // }
                        /*
                        *   END FOR PERSURATAN
                        */
                    }
                }
            }else{
                $data = strip_tags($this->validation->listErrors());
                $this->array_response['message'] = 'Gagal upload__ '.str_replace(['\n','\t'], '', strip_tags(json_encode($data)));
            }
            $this->array_response['data'] = $data;
            if($output=='json')
            {
                return $this->response
                    ->setHeader('Access-Control-Allow-Origin', '*')
                    ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                    ->setHeader('Access-Control-Allow-Headers', 'Key')
                    ->setStatusCode($this->array_response['code'])
                    ->setJSON($this->array_response);
            }else{
                $data_file = $this->db->query("SELECT * FROM app_files where status in ?", [[1]])->getResult();
                return view('tBaseBox', ['page'=>'form_upload', 'data_file'=>$data_file, 'data'=>$this->array_response]);
            }
        }else{
            $data_file = $this->db->query("SELECT * FROM app_files where status in ?", [[1]])->getResult();
            return view('tBaseBox', ['page'=>'form_upload', 'data_file'=>$data_file, 'data'=>[]]);
        }
    }

    public function view_file()
    {
        // $id = (string_to($this->request->getGet('id'),'decode'))?:0;
        $id = $this->request->getGet('id');
        $file = $this->request->getGet('file');
        switch ($file) {
            case 'skp':
                $data_file = $this->db->table('skp_import')->getWhere(['unix_id'=>$id])->getRow();
                if(!empty($data_file))
                {
                    return view('pdfViewer', ['page'=>'pdfViewer', 'file'=>$file, 'data_file'=>$data_file, 'data'=>[]]);
                }else{
                    session()->setFlashdata('message', 'File tidak ditemukan');
                    return redirect()->to('/');
                }
                break;
            default:
                $data_file = $this->db->table('app_files')->getWhere(['id'=>$id])->getRow();
                if(!empty($data_file))
                {
                    return view('pdfViewer', ['page'=>'pdfViewer', 'file'=>$file, 'data_file'=>$data_file, 'data'=>[]]);
                }else{
                    session()->setFlashdata('message', 'Maaf, File tidak ditemukan');
                    return redirect()->to('/');
                }
                break;
        }
    }

    public function download()
    {
        $file = $this->request->getGet('file');
        switch ($file) {
            case 'skp':
                $data_file = $this->db->table('skp_import')->getWhere(['unix_id'=>$this->request->getGet('id')])->getRow();
                if(!empty($data_file))
                {
                    if(file_exists($data_file->file)){
                        return $this->response->download($data_file->file, null)->setFileName(explode('writable/files_skp/', $data_file->file)[1]);
                    }else{
                        session()->setFlashdata('message', 'Maaf, Path File tidak ditemukan');
                        return redirect()->to('/');
                    }
                }else{
                    session()->setFlashdata('message', 'Maaf, ID File tidak ditemukan');
                    return redirect()->to('/');
                }
                break;
            default:
                // $id = (string_to($this->request->getGet('id'),'decode'))?:0;
                $id = $this->request->getGet('id');
                $data_file = $this->db->table('app_files')->getWhere(['id'=>$id])->getRow();
                if(!empty($data_file))
                {
                    if(file_exists($data_file->path)){
                        return $this->response->download($data_file->path, null)->setFileName($data_file->name);
                    }else{
                        session()->setFlashdata('message', 'Maaf, Path File tidak ditemukan');
                        return redirect()->to('/');
                    }
                }else{
                    session()->setFlashdata('message', 'Maaf, ID File tidak ditemukan');
                    return redirect()->to('/');
                }
                break;
        }
    }

    public function delete_file()
    {
        $this->array_response['code'] = 200;
        $id = (string_to($this->request->getGet('id'),'decode'))?:0;
        $data_file = $this->db->table('app_files')->getWhere(['id'=>$id])->getRow();
        if(!empty($data_file))
        {
            @unlink($data_file->path);
            $this->db->table('app_files')->delete(['id'=>$id]);
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Berhasil Menghapus File';
        }else{
            $this->array_response['message'] = 'Maaf, ID File tidak ditemukan';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }


    /*
    *   API2
    */
    public function unit_kerja()
    {
        $this->array_response['code'] = 200;
        $search = $this->request->getGet('search');
        $where = "";
        $arrWhere = [];
        if($search<>'')
        {
            $where .= " and unit_kerja_name like ? ";
            array_push($arrWhere, '%'.$search.'%');
        }
        $data = $this->db->query("SELECT * FROM ms_unit_kerja WHERE unit_kerja_status=1 ".$where, $arrWhere)->getResult();
        if(!empty($data))
        {
            $new_data = [];
            foreach ($data as $key) if(array_keys([1,9], $key->unit_kerja_id)) {
                array_push($new_data, $key);
            }
            foreach ($data as $key) if(array_keys([8], $key->unit_kerja_id)) {
                array_push($new_data, $key);
            }
            foreach ($data as $key) if(!array_keys([1,9,8], $key->unit_kerja_id)) {
                array_push($new_data, $key);
            }
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Successful';
            $this->array_response['data'] = $new_data;
        }else{
            $this->array_response['message'] = 'Not found';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'GET, POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }

    public function jabatan()
    {
        $this->array_response['code'] = 200;
        $lock = $this->request->getGet('lock');
        $search = $this->request->getGet('search');
        $where = "";
        $arrWhere = [];
        if($search<>'')
        {
            $where .= "and jabatan_name like ? ";
            array_push($arrWhere, '%'.$search.'%');
        }
        if($lock<>'')
        {
            $jabatan_id = [];
            switch ($lock) {
                case 1:
                    $jabatan_id = array_merge($jabatan_id, [2,3,4,5,10]);
                    break;
                case 2:
                case 3:
                case 4:
                    $jabatan_id = array_merge($jabatan_id, [6,7,8,9]);
                    break;
                // case 5:
                //     $jabatan_id = array_merge($jabatan_id, [43]);
                //     break;
            }
            if($lock==5)
            {
                $where .= "and jabatan_id not in ? ";
                array_push($arrWhere, [1,2,3,4,5,10,6,7,8,9]);
            }else{
                $where .= "and jabatan_id in ? ";
                array_push($arrWhere, $jabatan_id);
            }
        }
        $data = $this->db->query("SELECT * FROM ms_jabatan WHERE jabatan_status=1 ".$where, $arrWhere)->getResult();
        if(!empty($data))
        {
            // $new_data = [];
            // foreach ($data as $key) if(array_keys([10,55], $key->jabatan_id)) {
            //     array_push($new_data, $key);
            // }
            // foreach ($data as $key) if(array_keys([35], $key->jabatan_id)) {
            //     array_push($new_data, $key);
            // }
            // foreach ($data as $key) if(array_keys([1,2,3,4,5,74,75], $key->jabatan_id)) {
            //     array_push($new_data, $key);
            // }
            // foreach ($data as $key) if(array_keys([11], $key->jabatan_id)) {
            //     array_push($new_data, $key);
            // }
            // foreach ($data as $key) if(!array_keys([10,55,35,1,2,3,4,5,74,75,11], $key->jabatan_id)) {
            //     array_push($new_data, $key);
            // }
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Successful';
            $this->array_response['data'] = $data;
        }else{
            $this->array_response['message'] = 'Not found';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'GET, POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }

    public function referensi()
    {
        $this->array_response['code'] = 200;
        $id = $this->request->getGet('id');
        $search = $this->request->getGet('search');
        $where = "";
        $arrWhere = [];
        array_push($arrWhere, $id);
        if($search<>'')
        {
            $where .= " and ref_name like ? ";
            array_push($arrWhere, '%'.$search.'%');
        }
        $data = $this->db->query("SELECT * FROM app_referensi WHERE ref_status=1 and ref=? ".$where, $arrWhere)->getResult();
        if(!empty($data))
        {
            $new_data = [];
            foreach ($data as $key) {
                array_push($new_data, $key);
            }
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Successful';
            $this->array_response['data'] = $new_data;
        }else{
            $this->array_response['message'] = 'Not found';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'GET, POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }

    public function wilayah()
    {
        $this->array_response['code'] = 200;
        $id = $this->request->getGet('id');
        $search = $this->request->getGet('search');
        $where = "";
        $arrWhere = [];
        if($id<>'')
        {
            $where .= "and parent_id=? ";
            array_push($arrWhere, $id);
        }
        if($search<>'')
        {
            $where .= " and name like ? ";
            array_push($arrWhere, '%'.$search.'%');
        }
        $where .= " limit 20 ";
        $data = $this->db->query("SELECT * FROM ms_wilayah WHERE status=1 ".$where, $arrWhere)->getResult();
        if(!empty($data))
        {
            $new_data = [];
            foreach ($data as $key) {
                array_push($new_data, $key);
            }
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Successful';
            $this->array_response['data'] = $new_data;
        }else{
            $this->array_response['message'] = 'Not found';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'GET, POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }

    public function perguruan_tinggi()
    {
        $this->array_response['code'] = 200;
        $search = $this->request->getGet('search');
        $where = "";
        $arrWhere = [];
        if($search<>'')
        {
            $where .= " and (nama_pt like ? or alamat_pt like ?) ";
            array_push($arrWhere, '%'.$search.'%');
            array_push($arrWhere, '%'.$search.'%');
        }
        $where .= " limit 10 ";
        $data = $this->db->query("SELECT * FROM ms_perguruan_tinggi WHERE 1 ".$where, $arrWhere)->getResult();
        if(!empty($data))
        {
            $new_data = [];
            foreach ($data as $key) {
                array_push($new_data, $key);
            }
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Successful';
            $this->array_response['data'] = $new_data;
        }else{
            $this->array_response['message'] = 'Not found';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'GET, POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }

    public function gugus_tugas()
    {
        $this->array_response['code'] = 200;
        $search = $this->request->getGet('search');
        $where = "";
        $arrWhere = [];
        if($search<>'')
        {
            $where .= " and (gugustugas like ?) ";
            array_push($arrWhere, '%'.$search.'%');
        }
        $data = $this->db->query("SELECT * FROM ms_gugus_tugas WHERE 1 ".$where, $arrWhere)->getResult();
        if(!empty($data))
        {
            $new_data = [];
            foreach ($data as $key) {
                array_push($new_data, $key);
            }
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Successful';
            $this->array_response['data'] = $new_data;
        }else{
            $this->array_response['message'] = 'Not found';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'GET, POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }

    public function pegawai()
    {
        $this->array_response['code'] = 200;
        $search = $this->request->getGet('search');
        $where = "";
        $arrWhere = [];
        if($search<>'')
        {
            $where .= " and (a.nama like ? or c.jabatan_name like ? or d.unit_kerja_name_alt like ?) ";
            array_push($arrWhere, '%'.$search.'%');
            array_push($arrWhere, '%'.$search.'%');
            array_push($arrWhere, '%'.$search.'%');
        }
        $where .= " order by a.jabatan_id asc ";
        $data = $this->db->query("SELECT a.pegawai_id as id, case when a.jabatan_id in (1,10) then concat(a.nama, ' (', c.jabatan_name, ')') else concat(a.nama, ' (', c.jabatan_name, ' - ', d.unit_kerja_name_alt, ')') end as name 
            FROM pegawai a
                left join ms_jabatan c on c.jabatan_id=a.jabatan_id
                left join ms_unit_kerja d on d.unit_kerja_id=a.unit_kerja_id
            WHERE 1 ".$where, $arrWhere)->getResult();
        if(!empty($data))
        {
            $new_data = [];
            foreach ($data as $key) {
                array_push($new_data, $key);
            }
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Successful';
            $this->array_response['data'] = $new_data;
        }else{
            $this->array_response['message'] = 'Not found';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'GET, POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }

    public function pegawai_foto()
    {
        $this->array_response['code'] = 200;
        $id = $this->request->getGet('id');
        $thumbnail = $this->request->getGet('thumbnail');
        $data = $this->db->query("SELECT pegawai_id, foto_pegawai, foto_pegawai_temp, kelamin FROM pegawai WHERE pegawai_id=? ", [$id])->getRow();
        if(!empty($data))
        {
            $path = get_foto_default_pegawai($data->kelamin);
            if($thumbnail==1)
            {
                if(file_exists($data->foto_pegawai_temp)){
                    $path = create_file_to_base64($data->foto_pegawai_temp);
                }
            }else{
                if(file_exists($data->foto_pegawai)){
                    $path = create_file_to_base64($data->foto_pegawai);
                }
            }
        }else{
            $path = '';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'GET, POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON(['data'=>$path]);
    }

    public function member_ekstern()
    {
        $this->array_response['code'] = 200;
        $search = $this->request->getGet('search');
        $where = "";
        $arrWhere = [];
        if($search<>'')
        {
            $where .= " and (nama like ? or jabatan like ? or instansi like ?) ";
            array_push($arrWhere, '%'.$search.'%');
            array_push($arrWhere, '%'.$search.'%');
            array_push($arrWhere, '%'.$search.'%');
        }
        $data = $this->db->query("SELECT id as id, nama as name, jabatan, instansi FROM member_eksternal WHERE 1 ".$where, $arrWhere)->getResult();
        if(!empty($data))
        {
            $new_data = [];
            foreach ($data as $key) {
                array_push($new_data, $key);
            }
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Successful';
            $this->array_response['data'] = $new_data;
        }else{
            $this->array_response['message'] = 'Not found';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'GET, POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }

    public function place_in_google_maps()
    {
        $this->array_response['code'] = 200;
        $search = $this->request->getGet('search');
        // $opt_goo = return_value_in_options('google');
        // $data = json_decode(shell_exec("curl -H 'Referer: ".base_url()."' -L -X GET 'https://maps.googleapis.com/maps/api/place/textsearch/json?key=".$opt_goo['maps']."&query=".urlencode($search)."'"),true);
        $data = json_decode(shell_exec("curl -H 'Referer: ".base_url()."' -L -X GET 'https://maps.googleapis.com/maps/api/place/textsearch/json?key=AIzaSyCACsIlxK9TSu8iWBg-i85YWSJpUiAm4fw&query=".urlencode($search)."'"),true);
        if(!empty($data))
        {
            $new_data = $data;
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Successful';
            $this->array_response['data'] = $new_data;
        }else{
            $this->array_response['message'] = 'Not found';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'GET, POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }

    public function place_by_latlong_in_google_maps()
    {
        $this->array_response['code'] = 200;
        $latlng = $this->request->getGet('latlng');
        // $opt_goo = return_value_in_options('google');
        // $data = json_decode(shell_exec("curl -H 'Referer: ".base_url()."' -L -X GET 'https://maps.googleapis.com/maps/api/geocode/json?key=".$opt_goo['maps']."&latlng=".$latlng."'"),true);
        $data = json_decode(shell_exec("curl -H 'Referer: ".base_url()."' -L -X GET 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyCACsIlxK9TSu8iWBg-i85YWSJpUiAm4fw&latlng=".$latlng."'"),true);
        if(!empty($data))
        {
            $new_data = $data;
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Successful';
            $this->array_response['data'] = $new_data;
        }else{
            $this->array_response['message'] = 'Not found';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'GET, POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }

    public function maps()
    {
        $latlng = explode(',', $this->request->getGet('latlng'));
        $title = $this->request->getGet('title');
        if($_POST)
        {
        }else{
            $arr_view = [
                'title' => 'Maps',
                'page' => 'maps',
                'latlng' => $latlng,
                'title_name' => $title,
                'test_uri' => $this->uri->getSegments(),
            ];
            return view($arr_view['page'], $arr_view);
        }
    }


    /* test */
    public function crypto_test()
    {
        $id = $this->request->getGet('id');
        $act = $this->request->getGet('act');
        switch ($act) {
            case 'encode':
                $id = string_to($id, 'encode');
                break;
            case 'decode':
                $id = string_to($id, 'decode');
                break;
            default:
                // code...
                break;
        }
        return $id;
    }

    public function ngemail()
    {
        if($_POST)
        {
            $email = \Config\Services::email();
            $conf['userAgent'] = $_ENV['userAgent'];
            $conf['protocol'] = $_ENV['protocol'];
            $conf['SMTPHost'] = $_ENV['SMTPHost'];
            $conf['SMTPUser'] = $_ENV['SMTPUser'];
            $conf['SMTPPass'] = $_ENV['SMTPPass'];
            $conf['SMTPPort'] = (int)$_ENV['SMTPPort'];
            $conf['SMTPCrypto'] = $_ENV['SMTPCrypto'];
            $email->initialize($conf);
            $email->clear();
            $email->setFrom($_ENV['SMTPUser'], 'Development Test');
            $email->setTo($this->request->getPost('to'));
            // $email->setCC('another@another-example.com');
            // $email->setBCC('them@their-example.com');
            $email->setSubject($this->request->getPost('title'));
            $email->setMessage($this->request->getPost('message'));
            $file_upload = $this->request->getFile('userfile');
            // if(!empty($file_upload)){
            //     $path = $file_upload->store();
            //     $email->attach(WRITEPATH . 'uploads/' . $path);
            // }
            if($email->send())
            {
                echo 'Send Successful';
            }else{
                echo 'Send Failed';
            }
            echo '<br>'. anchor('ngemail', 'Ngemail', 'title="ngemail link"');
        }else{
            $arr_view = [
                'title' => 'Ngemail',
                'page' => 'form_email',
                'test_uri' => $this->uri->getSegments(),
                'test_env' => $_ENV,
            ];
            return view('tBaseBox', $arr_view);
        }
    }
}
