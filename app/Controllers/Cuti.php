<?php
namespace App\Controllers;
use App\Models\CutiModel;
use App\Models\KepegawaianModel;

class Cuti extends BaseController
{
    public function __construct()
    {
        $this->CutiModel = new CutiModel();
        $this->KepegawaianModel = new KepegawaianModel();
        $this->user_id = session()->get('id');
        $this->pegawai_id = session()->get('pegawai_id');
        $this->jabatan_id = session()->get('jabatan_id');
        $this->unit_kerja_id = session()->get('unit_kerja_id');
        $this->date_now = date('Y-m-d');
        $this->datetime_now = date('Y-m-d H:i:s');
        $this->kode_hari = date('D');
        session()->set('id_app', 4);
    }

    public function index()
    {
        $arr = [
            'title' => 'Cuti pegawai',
            'page' => 'Cuti/index',
            'data_saldo' => $this->CutiModel->result_saldo_cuti_pegawai($this->pegawai_id),
        ];
        return view('tBase', $arr);
    }

    /*
    *   detail cuti
    */
    public function detail()
    {
        $this->array_response['code'] = 200;
        $id = string_to($this->request->getGet('id'), 'decode');
        $data = $this->CutiModel->cuti_get_row($id);
        if(!empty($data))
        {
            $arr = [
                'title' => 'Detail Informasi Cuti Pegawai',
                'page' => 'Cuti/detail',
                'data' => $data,
                'link' => $this->request->getGet('link'),
                'tab' => $this->request->getGet('tab'),
            ];
            return view('tBase', $arr);
        }else{
            session()->setFlashdata('message', 'Maaf, ID tidak valid atau Anda tidak mendapat akses');
            return $this->index();
        }
    }

    /*
    *   form cuti
    */
    public function form()
    {
        $this->array_response['code'] = 200;
        $id = ($this->request->getGet('id'))?string_to($this->request->getGet('id'),'decode'):0;
        $data = $this->CutiModel->cuti_get_row($id);
        $data_pegawai = $this->KepegawaianModel->pegawai_get_row($this->pegawai_id);
        if($_POST)
        {
            $setRules = [
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'pegawai_id' => [
                    'label' => 'Data Pegawai', 
                    'rules' => 'required|integer|min_length[1]|max_length[11]',
                ],
                'jenis_cuti' => [
                    'label' => 'Jenis Cuti', 
                    'rules' => 'required|integer|min_length[1]|max_length[11]',
                ],
            ];
            if($this->request->getPost('id')>0)
            {

                $setRules['alamat'] = [
                        'label' => 'Alamat Saat Cuti', 
                        'rules' => 'required|max_length[5500]',
                    ];
                $setRules['telpon'] = [
                        'label' => 'Telepon or Kontak Saat Cuti', 
                        'rules' => 'required|integer|max_length[50]',
                    ];
                if($this->request->getPost('jenis_cuti')==4)
                {
                    $setRules['keterangan2'] = [
                            'label' => 'Keterangan or Alasan Cuti', 
                            'rules' => 'required|max_length[5500]',
                        ];
                }else{
                    $setRules['keterangan'] = [
                            'label' => 'Keterangan or Alasan Cuti', 
                            'rules' => 'required|max_length[5500]',
                        ];
                }
                if(array_keys([2,3,4],$this->request->getPost('jenis_cuti')))
                {
                    $setRules['file_lampiran'] = [
                            'label' => 'File Lampiran', 
                            'rules' => 'max_length[250]',
                        ];
                }
            }
            $this->validation->setRules($setRules);
            if($this->validation->run($_POST))
            {
                if($data_pegawai->pegawai_id==$this->request->getPost('pegawai_id') && $id==$this->request->getPost('id'))
                {
                    $jenis_cuti = strip_tags($this->request->getPost('jenis_cuti'));
                    $pegawai_id = strip_tags($this->request->getPost('pegawai_id'));
                    $lamanya = strip_tags($this->request->getPost('lamanya'));
                    $saldo_cuti = $this->CutiModel->return_sisa_cuti_terakhir_by_pegawaiid($pegawai_id);
                    //if(count(explode(',', $lamanya)) <= $saldo_cuti)
                    if((array_keys([1], $jenis_cuti) && count(explode(',', $lamanya)) <= $saldo_cuti) || !array_keys([1], $jenis_cuti))
                    {
                        $data_field = [
                            'pegawai_id' => $pegawai_id,
                            'jabatan_id' => $data_pegawai->jabatan_id,
                            'unit_kerja_id' => $data_pegawai->unit_kerja_id,
                            'jenis_cuti' => $jenis_cuti,
                            'perubahan_terakhir' => json_encode(remove_key_in_array($_POST, ['password', 'passphrase'])),
                        ];
                        if(!empty($data))
                        {
                            /*
                            *   for update data cuti
                            */
                            $data_field['alamat'] = strip_tags($this->request->getPost('alamat'));
                            $data_field['telpon'] = strip_tags($this->request->getPost('telpon'));
                            if($jenis_cuti==4)
                            {
                                $data_field['keterangan'] = strip_tags($this->request->getPost('keterangan2'));
                            }else{
                                $data_field['keterangan'] = strip_tags($this->request->getPost('keterangan'));
                            }
                            if(array_keys([2,3,4], $jenis_cuti))
                            {
                                $data_field['lampiran'] = strip_tags($this->request->getPost('file_lampiran'));
                            }
                            $save_ = $this->CutiModel->cuti_save($data_field, ['id'=>$id]);

                            /*
                            *   for detail cuti
                            */
                            $this->CutiModel->cuti_detail_delete(['id'=>$id]);
                            $jenis_cuti_data = return_referensi_row_by('cuti', $jenis_cuti);
                            $tanggal = explode(',', $lamanya );
                            if(!empty($tanggal))
                            {
                                foreach ($tanggal as $key => $value) {
                                    $this->CutiModel->cuti_detail_save([
                                        'id' => $id,
                                        'pegawai_id' => $pegawai_id,
                                        'ref_code' => $jenis_cuti_data->ref_name,
                                        'tanggal' => $value
                                    ]);
                                }
                            }

                            /*
                            *   for approved cuti
                            */
                            $pejabat_berwenang = strip_tags($this->request->getPost('pejabat_berwenang'));
                            $pejabat_berwenang_data = $this->KepegawaianModel->pegawai_row_by_pegawaiid($pejabat_berwenang);
                            $cuti_approved_check = $this->CutiModel->cuti_approved_row_by_id($id);
                            if(!empty($cuti_approved_check))
                            {
                                $this->CutiModel->cuti_approved_save([
                                    'id'=>$id,
                                    'pegawai_id'=>$pejabat_berwenang_data->pegawai_id,
                                    'jabatan_id'=>$pejabat_berwenang_data->jabatan_id,
                                    'unit_kerja_id'=>$pejabat_berwenang_data->unit_kerja_id,
                                ], ['id'=>$id]);
                            }else{
                                $this->CutiModel->cuti_approved_save([
                                    'id'=>$id,
                                    'pegawai_id'=>$pejabat_berwenang_data->pegawai_id,
                                    'jabatan_id'=>$pejabat_berwenang_data->jabatan_id,
                                    'unit_kerja_id'=>$pejabat_berwenang_data->unit_kerja_id,
                                ]);
                            }

                            /*
                            *   generate file pdf
                            */
                            $this->CutiModel->replace_text_in_docx_file_and_export_to_pdf($id, 0, true);
                        }else{
                            /*
                            *   for new insert cuti
                            */
                            $data_field['create_at'] = $this->datetime_now;
                            $data_field['create_by'] = $this->user_id;
                            $save_ = $this->CutiModel->cuti_save($data_field);
                            $id = $save_;
                            // trace save
                            $this->CutiModel->cuti_trace_save(['id'=>$id, 'status'=>1, 'status_name'=>return_referensi_row_by('cuti_status_proses', 1)->ref_name, 'proccess_by'=>$this->user_id, 'description'=>'Membuat draft permohonan cuti', 'proccess_at'=>$this->datetime_now]);
                        }
                        if($save_>0)
                        {
                            $this->array_response['status'] = TRUE;
                            $this->array_response['message'] = 'Successful';
                        }else{
                            $this->array_response['message'] = 'Save failed';
                        }
                    }else{
                        $this->array_response['message'] = 'Maaf, Saldo cuti Anda tidak mencukupi untuk jumlah hari yang dipilih. Silahkan pilih ulang tanggal yang sesuai agar dapat melanjutkat proses.';   
                    }
                }else{
                    $this->array_response['message'] = 'Maaf Anda tidak mendapat hak akses';
                }
            }else{
                $this->array_response['message'] = str_replace(['\n','\t'], '', strip_tags(json_encode($this->validation->listErrors())));
            }
            $this->array_response['ID'] = string_to($id, 'encode');
            session()->setFlashdata('message', $this->array_response['message']);
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Form Cuti',
                'page' => 'Cuti/form',
                'id' => $id,
                'data' => $data,
                'data_pegawai' => $data_pegawai,
                'list_pejabat' => $this->KepegawaianModel->pegawai_result_in_jabatanid([1,2,3,4,10]),
                'link' => $this->request->getGet('link'),
            ];
            return view('tBase', $arr);
        }
    }

    /*
    *   hapus cuti 
    */
    function hapus()
    {
        $this->array_response['code'] = 200;
        $this->array_response['message'] = 'Gagal dihapus';
        $id = ($this->request->getGet('id'))?string_to($this->request->getGet('id'), 'decode'):0;
        $data = $this->CutiModel->cuti_get_row($id);
        if(!empty($data) && array_keys([1],$data['status']) && (return_roles([1]) || $data['pegawai_id']==$this->pegawai_id))
        {
            $this->CutiModel->cuti_delete(['id'=>$data['id']]);
            $this->CutiModel->cuti_detail_delete(['id'=>$data['id']]);
            $this->CutiModel->cuti_approved_delete(['id'=>$data['id']]);
            $this->CutiModel->cuti_proccess_delete(['id'=>$data['id']]);
            $this->CutiModel->cuti_trace_delete(['id'=>$data['id']]);
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Successful';
        }else{
            $this->array_response['message'] = 'Data tidak titemukan';
        }
        session()->setFlashdata('message', $this->array_response['message']);
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'GET, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }


    /*
    *   history for user
    */
    public function riwayat()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        $tahun = ($this->request->getGet('tahun'))?:date('Y');
        $bulan = ($this->request->getGet('bulan'))?:date('m');
        $pegawai_id = ($this->request->getGet('id'))?string_to($this->request->getGet('id'),'decode'):$this->pegawai_id;
        if($pegawai_id>0 && (return_roles([1]) || $pegawai_id==$this->pegawai_id))
        {
            if($_POST)
            {
                $data = [];
                $draw = (int) (($this->request->getPost('draw')) ? : 1);
                $jml = $this->CutiModel->cuti_in_riwayat();
                $q = $this->CutiModel->cuti_in_riwayat(true);
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
                    'title' => 'Riwayat Cuti',
                    'page' => 'Cuti/riwayat',
                    'pegawai_id' => string_to($pegawai_id, 'encode'),
                    'data_pegawai' => $this->KepegawaianModel->pegawai_get_row($pegawai_id),
                    'tab' => $this->request->getGet('tab'),
                ];
                return view('tBase', $arr);
            }
        }else{
            session()->setFlashdata('message', 'Maaf, Anda tidak mendapat akses');
            return $this->index();
        }
    }

    /*
    *   kirim permohonan cuti
    */
    public function kirim_permohonan()
    {
        $this->array_response['code'] = 200;
        $id = ($this->request->getGet('id'))?string_to($this->request->getGet('id'),'decode'):0;
        $data = $this->CutiModel->cuti_get_row($id);
        if(!empty($data) && array_keys([1],$data['status']) && (return_roles([1]) || $data['pegawai_id']==$this->pegawai_id))
        {
            /*
            *   for update data cuti status 2
            */
            $save_ = $this->CutiModel->cuti_save(['status'=>2], ['id'=>$data['id']]);
            $this->CutiModel->cuti_trace_save(['id'=>$data['id'], 'status'=>2, 'status_name'=>return_referensi_row_by('cuti_status_proses', 2)->ref_name, 'proccess_by'=>$this->user_id, 'description'=>'Mengirim permohonan cuti kepada Pimpinan', 'proccess_at'=>$this->datetime_now]);

            /*
            *   for update data cuti status 5
            */
            $save_ = $this->CutiModel->cuti_save(['status'=>5, 'unix_id'=>string_to($data['id'], 'encode')], ['id'=>$data['id']]);
            $this->CutiModel->cuti_approved_save([
                'sent_time'=>$this->datetime_now,
            ], ['id'=>$data['id']]);

            /*
            *   generate file pdf
            */
            $this->CutiModel->replace_text_in_docx_file_and_export_to_pdf($data['id'], 1, true);
            if($save_>0)
            {
                $this->array_response['status'] = TRUE;
                $this->array_response['message'] = 'Berhasil';
            }else{
                $this->array_response['message'] = 'Gagal';
            }
        }else{
            $this->array_response['message'] = 'Maaf Anda tidak mendapat hak akses';
        }
        $this->array_response['ID'] = string_to($id, 'encode');
        // return $this->response
        //     ->setHeader('Access-Control-Allow-Origin', '*')
        //     ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
        //     ->setHeader('Access-Control-Allow-Headers', 'Key')
        //     ->setStatusCode($this->array_response['code'])
        //     ->setJSON($this->array_response);
        session()->setFlashdata('message', $this->array_response['message']);
        return redirect()->to('cuti/detail?id='.string_to($id, 'encode'));
    }



    /*
    *   FOR PIMPINAN LANGSUNG
    */
    public function permohonan()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->CutiModel->cuti_in_permohonan();
            $q = $this->CutiModel->cuti_in_permohonan(true);
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
                'title' => 'Permohonan Cuti',
                'page' => 'Cuti/permohonan',
                'unit_kerja_id' => $this->unit_kerja_id,
                'tab' => $this->request->getGet('tab'),
            ];
            return view('tBase', $arr);
        }
    }

    /*
    *   form approval cuti
    */
    public function form_approval()
    {
        $this->array_response['code'] = 200;
        $id = ($this->request->getGet('id'))?string_to($this->request->getGet('id'),'decode'):0;
        $data = $this->CutiModel->cuti_get_row($id);
        if(!empty($data))
        {
            if($_POST)
            {
                $setRules = [
                    'id' => [
                        'label' => 'ID', 
                        'rules' => 'required|integer|max_length[11]',
                    ],
                    'status_approval' => [
                        'label' => 'Status Approval', 
                        'rules' => 'required|integer|min_length[1]|max_length[1]',
                    ],
                    'passphrase' => [
                        'label' => 'Passphrase', 
                        'rules' => 'required|min_length[1]|max_length[50]',
                    ],
                ];
                if(array_keys([3,4,5],$this->request->getPost('status_approval')))
                {

                    $setRules['catatan'] = [
                            'label' => 'Catatan', 
                            'rules' => 'required|max_length[5500]',
                        ];
                }
                $this->validation->setRules($setRules);
                if($this->validation->run($_POST))
                {
                    if(array_keys([5],$data['status']) && (return_roles([1]) || $data['pegawai_id_pimpinan']==$this->pegawai_id) && $data['id']==$this->request->getPost('id'))
                    {
                        $passphrase = $this->request->getPost('passphrase');
                        // $id = strip_tags($this->request->getPost('id'));
                        $status_approval = strip_tags($this->request->getPost('status_approval'));
                        $catatan = strip_tags($this->request->getPost('catatan'));
                        // $saldo_cuti = $this->CutiModel->return_sisa_cuti_terakhir_by_pegawaiid($data['pegawai_id']);
                        // if(count(explode(',',$data['tanggal'])) <= $saldo_cuti)
                        // {
                            /*
                            *   for update data cuti 6
                            */
                            $save_ = $this->CutiModel->cuti_save(['status'=>6], ['id'=>$data['id']]);
                            $this->CutiModel->cuti_trace_save(['id'=>$data['id'], 'status'=>6, 'status_name'=>return_referensi_row_by('cuti_status_proses', 6)->ref_name, 'proccess_by'=>$this->user_id, 'description'=>'Approval permohonan cuti oleh Pimpinan', 'proccess_at'=>$this->datetime_now]);
                            $this->CutiModel->cuti_approved_save([
                                'status'=>$status_approval,
                                'respon'=>1,
                                'respon_time'=>$this->datetime_now,
                                'catatan'=>$catatan,
                            ], ['id'=>$data['id']]);

                            /*
                            *   generate file pdf
                            */
                            $file_form_cuti = $this->CutiModel->replace_text_in_docx_file_and_export_to_pdf($data['id'], 3);

                            /*
                            *   check status approval
                            */
                            $rs = [];
                            if($status_approval==2)
                            {
                                // lanjut ke cuti proses
                                $save_ = $this->CutiModel->cuti_save(['status'=>7], ['id'=>$data['id']]);

                                // tte disini
                                $x_pegawai = return_value_in_options('kepegawaian');
                                if(array_keys(['', 'true'], $x_pegawai['esign_cuti']))
                                {
                                    $url_verifikasi = $x_pegawai['url_verifikasi'] . $data['unix_id'] .'&t=cuti';
                                    $image_qrcode = create_new_qrcode($url_verifikasi, WRITEPATH.'temp_zip/'.$data['unix_id'].'.png', true);
                                    $file_sign_output = str_replace(['.pdf', '.PDF'], '_sign.pdf', $file_form_cuti);
                                    add_info_tte_in_footer($file_form_cuti);
                                    $rs = bsre_sign_pdf($data['nik_pimpinan'], $passphrase, $file_form_cuti, $file_sign_output, 'VISIBLE', '#', 60, 60, $image_qrcode);
                                    if($rs['status']==true){
                                        $this->CutiModel->cuti_save([
                                            'path'=>$file_sign_output,
                                        ], ['id'=>$data['id']]);
                                        $this->CutiModel->cuti_approved_save([
                                            'path'=>$file_sign_output,
                                            'tte'=>1,
                                        ], ['id'=>$data['id']]);
                                        $this->CutiModel->cuti_proccess_save([
                                            'id'=>$data['id'],
                                            'status'=>1,
                                            'sent_time'=>$this->datetime_now,
                                        ]);
                                    }else{
                                        $this->CutiModel->cuti_save(['status'=>5], ['id'=>$data['id']]);
                                        $this->CutiModel->cuti_approved_save([
                                            'status'=>1,
                                        ], ['id'=>$data['id']]);
                                        $this->CutiModel->replace_text_in_docx_file_and_export_to_pdf($data['id'], 3, true);
                                    }
                                    @unlink($file_form_cuti);
                                    @unlink($image_qrcode);
                                }else{
                                    $this->CutiModel->cuti_proccess_save([
                                        'id'=>$data['id'],
                                        'status'=>1,
                                        'sent_time'=>$this->datetime_now,
                                    ]);
                                }
                            }else{
                                // stop disini
                            }

                            if($save_>0)
                            {
                                $this->array_response['status'] = TRUE;
                                $this->array_response['message'] = 'Berhasil diproses :: '.json_encode($rs);
                            }else{
                                $this->array_response['message'] = 'Gagal diproses';
                            }
                        // }else{
                        //     $this->array_response['message'] = 'Maaf, Saldo cuti pegawai tidak mencukupi untuk jumlah hari yang dipilih. Silahkan pilih ulang tanggal yang sesuai agar dapat melanjutkat proses.';   
                        // }
                    }else{
                        $this->array_response['message'] = 'Maaf Anda tidak mendapat hak akses';
                    }
                }else{
                    $this->array_response['message'] = str_replace(['\n','\t'], '', strip_tags(json_encode($this->validation->listErrors())));
                }
                $this->array_response['ID'] = string_to($id, 'encode');
                session()->setFlashdata('message', $this->array_response['message']);
                return $this->response
                    ->setHeader('Access-Control-Allow-Origin', '*')
                    ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                    ->setHeader('Access-Control-Allow-Headers', 'Key')
                    ->setStatusCode($this->array_response['code'])
                    ->setJSON($this->array_response);
            }else{
                $arr = [
                    'title' => 'Form Approval Cuti',
                    'page' => 'Cuti/form_approval',
                    'data' => $data,
                ];
                if($data['read_pimpinan']==0)
                {
                    $this->CutiModel->cuti_approved_save(['read'=>1, 'read_time'=>$this->datetime_now], ['id'=>$data['id']]);
                }
                return view('tBase', $arr);
            }
        }else{
            session()->setFlashdata('message', 'Maaf, Anda tidak mendapat akses');
            return $this->permohonan();
        }
    }



    /*
    *   FOR admin kepeg
    */
    public function proses()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->CutiModel->cuti_in_proses();
            $q = $this->CutiModel->cuti_in_proses(true);
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
                'title' => 'Poses Permohonan Cuti',
                'page' => 'Cuti/proses',
                'tab' => $this->request->getGet('tab'),
            ];
            return view('tBase', $arr);
        }
    }

    /*
    *   form proses cuti
    */
    public function form_proses()
    {
        $this->array_response['code'] = 200;
        $id = ($this->request->getGet('id'))?string_to($this->request->getGet('id'),'decode'):0;
        $data = $this->CutiModel->cuti_get_row($id);
        if(!empty($data))
        {
            if($_POST)
            {
                $setRules = [
                    'id' => [
                        'label' => 'ID', 
                        'rules' => 'required|integer|max_length[11]',
                    ],
                    'status_approval' => [
                        'label' => 'Status Approval', 
                        'rules' => 'required|integer|min_length[1]|max_length[1]',
                    ],
                ];
                if(array_keys([3,4,5],$this->request->getPost('status_approval')))
                {
                    $setRules['catatan'] = [
                            'label' => 'Catatan', 
                            'rules' => 'required|max_length[5500]',
                        ];
                }
                $this->validation->setRules($setRules);
                if($this->validation->run($_POST))
                {
                    if(array_keys([7],$data['status']) && $data['id']==$this->request->getPost('id'))
                    {
                        $status_approval = strip_tags($this->request->getPost('status_approval'));
                        $catatan = strip_tags($this->request->getPost('catatan'));
                        /*
                        *   for update data cuti 8
                        */
                        $save_ = $this->CutiModel->cuti_save(['status'=>8], ['id'=>$data['id']]);
                        $this->CutiModel->cuti_trace_save(['id'=>$data['id'], 'status'=>8, 'status_name'=>return_referensi_row_by('cuti_status_proses', 8)->ref_name, 'proccess_by'=>$this->user_id, 'description'=>'Proses permohonan cuti oleh admin kepegawaian', 'proccess_at'=>$this->datetime_now]);
                        $this->CutiModel->cuti_proccess_save([
                            'status'=>$status_approval,
                            'respon'=>1,
                            'respon_time'=>$this->datetime_now,
                            'catatan'=>$catatan,
                            'path'=>$data['path_pimpinan']
                        ], ['id'=>$data['id']]);

                        /*
                        *   update saldo cuti
                        */
                        if($status_approval==2)
                        {
                            $this->CutiModel->cuti_saldo_update_saldo($data['pegawai_id'], $data['jumlah'], $data['jenis_cuti']);
                        }
                        if($save_>0)
                        {
                            $this->array_response['status'] = TRUE;
                            $this->array_response['message'] = 'Berhasil diproses';
                        }else{
                            $this->array_response['message'] = 'Gagal diproses';
                        }
                    }else{
                        $this->array_response['message'] = 'Maaf Anda tidak mendapat hak akses';
                    }
                }else{
                    $this->array_response['message'] = str_replace(['\n','\t'], '', strip_tags(json_encode($this->validation->listErrors())));
                }
                $this->array_response['ID'] = string_to($id, 'encode');
                session()->setFlashdata('message', $this->array_response['message']);
                return $this->response
                    ->setHeader('Access-Control-Allow-Origin', '*')
                    ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                    ->setHeader('Access-Control-Allow-Headers', 'Key')
                    ->setStatusCode($this->array_response['code'])
                    ->setJSON($this->array_response);
            }else{
                $arr = [
                    'title' => 'Form Proses Cuti',
                    'page' => 'Cuti/form_proses',
                    'data' => $data,
                ];
                if($data['read_kepeg']==0)
                {
                    $this->CutiModel->cuti_proccess_save(['read'=>1, 'read_time'=>$this->datetime_now], ['id'=>$data['id']]);
                }
                return view('tBase', $arr);
            }
        }else{
            session()->setFlashdata('message', 'Maaf, Anda tidak mendapat akses');
            return $this->proses();
        }
    }



    /*
    *   master data
    */
    public function master_saldo()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->CutiModel->master_saldo_cuti();
            $q = $this->CutiModel->master_saldo_cuti(true);
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
                'title' => 'Master Saldo Cuti',
                'page' => 'Cuti/master_saldo',
                'list_tahun' => $this->CutiModel->result_cuti_saldo_tahun(),
            ];
            return view('tBase', $arr);
        }
    }

    public function master_saldo_form()
    {
        $this->array_response['code'] = 200;
        $id = ($this->request->getGet('id'))?:0;
        $data = $this->CutiModel->row_cuti_saldo($id);
        if($_POST)
        {
            $setRules = [
                'tahun' => [
                    'label' => 'Tahun', 
                    'rules' => 'required|integer|max_length[4]|min_length[4]',
                ],
                'saldo' => [
                    'label' => 'Saldo', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'digunakan' => [
                    'label' => 'Digunakan', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'sisa_saat_ini' => [
                    'label' => 'Sisa', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'optional' => [
                    'label' => 'Optional', 
                    'rules' => 'required|integer|max_length[1]|min_length[1]',
                ],
            ];
            if($this->request->getPost('optional')==2){
                $setRules['unit_kerja[]'] = [
                        'label' => 'Unit kerja', 
                        'rules' => 'max_length[100]',
                    ];
            }
            if($this->request->getPost('optional')==3){
                $setRules['pegawai_id[]'] = [
                        'label' => 'Pegawai', 
                        'rules' => 'max_length[100]',
                    ];
            }
            $this->validation->setRules($setRules);
            if($this->validation->run($_POST))
            {
                $id = strip_tags($this->request->getPost('id'));
                $saldo = strip_tags($this->request->getPost('saldo'));
                $digunakan = strip_tags($this->request->getPost('digunakan'));
                $sisa_saat_ini = strip_tags($this->request->getPost('sisa_saat_ini'));
                $tahun = strip_tags($this->request->getPost('tahun'));
                $optional = $this->request->getPost('optional');
                $unit_kerja_id = $this->request->getPost('unit_kerja');
                $pegawai_id = $this->request->getPost('pegawai_id');
                $save_ = 0;
                if(!empty($data))
                {
                    /*
                    *   for update data cuti
                    */
                    $save_ += $this->CutiModel->cuti_saldo_save([
                        'tahun'=>$this->request->getPost('tahun'),
                        'jatah'=>$saldo,
                        'digunakan'=>$digunakan,
                        'sisa_saat_ini'=>$sisa_saat_ini,
                        'update_at'=>$this->datetime_now,
                        'update_by'=>$this->user_id,
                    ], ['id'=>$data->id]);
                }else{
                    /*
                    *   for new insert saldo
                    */
                    $qp = $this->CutiModel->result_pegawai_before_insert_master_saldo($optional, $unit_kerja_id, $pegawai_id);
                    if(!empty($qp))
                    {
                        foreach($qp as $k){
                            $check = $this->CutiModel->return_saldo_cuti_by_pegawaiid_tahun($k->pegawai_id, $tahun);
                            if($check==0)
                            {
                                $save_ += $this->CutiModel->cuti_saldo_save([
                                    'pegawai_id'=>$k->pegawai_id,
                                    'jabatan_id'=>$k->jabatan_id,
                                    'unit_kerja_id'=>$k->unit_kerja_id,
                                    'tahun'=>$tahun,
                                    'jatah'=>$saldo,
                                    'digunakan'=>$digunakan,
                                    'sisa_saat_ini'=>$sisa_saat_ini,
                                    'create_at'=>$this->datetime_now,
                                    'create_by'=>$this->user_id,
                                ]);
                            }
                        }
                    }
                }
                if($save_>0)
                {
                    $this->array_response['status'] = TRUE;
                    $this->array_response['message'] = 'Successful';
                }else{
                    $this->array_response['message'] = 'Save failed';
                }
            }else{
                $this->array_response['message'] = str_replace(['\n','\t'], '', strip_tags(json_encode($this->validation->listErrors())));
            }
            $this->array_response['ID'] = string_to($id, 'encode');
            session()->setFlashdata('message', $this->array_response['message']);
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            $arr = [
                'title' => 'Form Master saldo Cuti',
                'page' => 'Cuti/form_master_saldo',
                'id' => $id,
                'data' => $data,
            ];
            return view('tBase', $arr);
        }
    }

    function master_saldo_hapus()
    {
        $this->array_response['code'] = 200;
        $this->array_response['message'] = 'Gagal dihapus';
        $id = explode(',', $this->request->getGet('id'));
        $rs = $this->CutiModel->cuti_saldo_delete_in_id($id);
        if($rs>0){
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Successful';
        }else{
            $this->array_response['message'] = 'Data tidak titemukan atau gagal dihapus';
        }
        session()->setFlashdata('message', $this->array_response['message']);
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'GET, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Key')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }
}