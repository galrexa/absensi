<?php
namespace App\Controllers;
use App\Models\PersuratanModel;
use App\Models\KepegawaianModel;

class Persuratan extends BaseController
{
    public function __construct()
    {
        $this->PersuratanModel = new PersuratanModel();
        $this->KepegawaianModel = new KepegawaianModel();
        $this->user_id = session()->get('id');
        $this->pegawai_id = session()->get('pegawai_id');
        $this->jabatan_id = session()->get('jabatan_id');
        $this->unit_kerja_id = session()->get('unit_kerja_id');
        $this->date_now = date('Y-m-d');
        $this->datetime_now = date('Y-m-d H:i:s');
        $this->kode_hari = date('D');
        session()->set('id_app', 5);
    }

    public function index()
    {
        $arr = [
            'title' => 'Persuratan',
            'page' => 'Persuratan/index',
            // 'data' => $this->db->query("SELECT * FROM app a where a.id=? ", [4])->getRow(),
            'data_saldo' => $this->db->query("SELECT * FROM cuti_saldo a where a.pegawai_id=?  order by a.tahun desc ", [$this->pegawai_id])->getResult(),
        ];
        return view('tBase', $arr);
    }

    /*
    *   detail cuti
    */
    public function detail()
    {
        $this->array_response['code'] = 200;
        $link = $this->request->getGet('link');
        $tab = $this->request->getGet('tab');
        $id = string_to($this->request->getGet('id'), 'decode');
        $data = $this->PersuratanModel->surat_get_row($id, $this->pegawai_id);
        if(!empty($data))
        {
            $vPage = 'Persuratan/detail';
            switch ($link) {
                case 'inbox':
                    $vPage = 'Persuratan/inbox_detail';
                    break;
            }
            $arr = [
                'title' => 'Informasi Naskah',
                'page' => $vPage,
                'data' => $data,
                'data_tindaklanjut' => $this->PersuratanModel->surat_tindaklanjut_result_by_surat_id_and_ref_id($id, 0),
                'link' => $link,
                'tab' => $tab,
                'datetime_now' => $this->datetime_now,
                'pegawai_id' => $this->pegawai_id,
                'jabatan_id' => $this->jabatan_id,
                'unit_kerja_id' => $this->unit_kerja_id,
            ];
            return view('tBase', $arr);
        }else{
            session()->setFlashdata('message', 'Maaf, ID tidak valid atau Anda tidak mendapat akses');
            return $this->index();
        }
    }


    /*
    * list draf
    */
    public function draft()
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
                $jml = $this->PersuratanModel->draft_datatable();
                $q = $this->PersuratanModel->draft_datatable(true);
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
                    'title' => 'Daftar Draf Surat',
                    'page' => 'Persuratan/draft',
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
    *   form cuti
    */
    public function compose()
    {
        $this->array_response['code'] = 200;
        $id = ($this->request->getGet('id'))?string_to($this->request->getGet('id'),'decode'):0;
        $data = $this->PersuratanModel->surat_get_row($id, $this->pegawai_id);
        // $data_pegawai = $this->KepegawaianModel->pegawai_get_row($this->pegawai_id);
        if($_POST)
        {
            $setRules = [
                'draf_for' => [
                    'label' => 'Tujuan pembuatan', 
                    'rules' => 'required|integer|max_length[1]',
                ],
                'jenis' => [
                    'label' => 'Jenis naskah', 
                    'rules' => 'required|integer|min_length[1]|max_length[2]',
                ],
                'draf_type' => [
                    'label' => 'Jenis draf', 
                    'rules' => 'required|integer|min_length[1]|max_length[1]',
                ],
            ];
            if($this->request->getPost('draf_for')==2)
            {
                $setRules['draf_ref_id'] = [
                    'label' => 'Referensi naskah/surat', 
                    'rules' => 'required|integer|min_length[1]|max_length[11]',
                ];
            }
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
                if(/*$data_pegawai->pegawai_id==$this->request->getPost('pegawai_id') &&*/ $id==$this->request->getPost('id'))
                {
                    $id = $this->request->getPost('id');
                    $draf_for = $this->request->getPost('draf_for');
                    $jenis = $this->request->getPost('jenis');
                    $draf_type = $this->request->getPost('draf_type');
                    $draf_ref_id = $this->request->getPost('draf_ref_id');

                    // $saldo_cuti = $this->PersuratanModel->return_sisa_cuti_terakhir_by_pegawaiid($pegawai_id);
                    // if(count(explode(',', $lamanya)) <= $saldo_cuti)
                    // {
                        $data_field = [
                            'draf_for' => $draf_for,
                            'jenis' => $jenis,
                            'draf_type' => $draf_type,
                            'draf_ref_id' => $draf_ref_id,
                            'last_change' => json_encode(remove_key_in_array($_POST, ['password', 'passphrase'])),
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
                            $save_ = $this->PersuratanModel->cuti_save($data_field, ['id'=>$id]);

                            /*
                            *   for detail cuti
                            */
                            $this->PersuratanModel->cuti_detail_delete(['id'=>$id]);
                            $jenis_cuti_data = return_referensi_row_by('cuti', $jenis_cuti);
                            $tanggal = explode(',', $lamanya );
                            if(!empty($tanggal))
                            {
                                foreach ($tanggal as $key => $value) {
                                    $this->PersuratanModel->cuti_detail_save([
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
                            $cuti_approved_check = $this->PersuratanModel->cuti_approved_row_by_id($id);
                            if(!empty($cuti_approved_check))
                            {
                                $this->PersuratanModel->cuti_approved_save([
                                    'id'=>$id,
                                    'pegawai_id'=>$pejabat_berwenang_data->pegawai_id,
                                    'jabatan_id'=>$pejabat_berwenang_data->jabatan_id,
                                    'unit_kerja_id'=>$pejabat_berwenang_data->unit_kerja_id,
                                ], ['id'=>$id]);
                            }else{
                                $this->PersuratanModel->cuti_approved_save([
                                    'id'=>$id,
                                    'pegawai_id'=>$pejabat_berwenang_data->pegawai_id,
                                    'jabatan_id'=>$pejabat_berwenang_data->jabatan_id,
                                    'unit_kerja_id'=>$pejabat_berwenang_data->unit_kerja_id,
                                ]);
                            }

                            /*
                            *   generate file pdf
                            */
                            $this->PersuratanModel->replace_text_in_docx_file_and_export_to_pdf($id, 0, true);
                        }else{
                            /*
                            *   for new insert
                            */
                            $data_field['sumber_bentuk'] = 2;
                            $data_field['register_number'] = $this->PersuratanModel->create_register_number();
                            $data_field['register_time'] = $this->datetime_now;
                            $data_field['create_by'] = $this->user_id;
                            $save_ = $this->PersuratanModel->surat_save($data_field);
                            $id = $save_;
                            // trace save
                            $this->PersuratanModel->surat_trace_save(['id'=>$id, 'status'=>1, 'status_name'=>return_referensi_row_by('surat_status_in', 1)->ref_name, 'proccess_by'=>$this->user_id, 'description'=>'Sdr/i '.session()->get('nama').' membuat draf naskah', 'proccess_at'=>$this->datetime_now]);
                            $this->PersuratanModel->surat_save(['unix_id'=>string_to($id, 'encode')], ['id'=>$id]);
                        }
                        if($save_>0)
                        {
                            $this->array_response['status'] = TRUE;
                            $this->array_response['message'] = 'Successful';
                        }else{
                            $this->array_response['message'] = 'Save failed';
                        }
                    // }else{
                    //     $this->array_response['message'] = 'Maaf, Saldo cuti Anda tidak mencukupi untuk jumlah hari yang dipilih. Silahkan pilih ulang tanggal yang sesuai agar dapat melanjutkat proses.';   
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
                'title' => 'Form Surat',
                'page' => 'Persuratan/compose',
                'id' => $id,
                'data' => $data,
                // 'data_pegawai' => $data_pegawai,
                // 'list_pejabat' => $this->KepegawaianModel->pegawai_result_in_jabatanid([1,2,3,4,10]),
                'link' => $this->request->getGet('link'),
            ];
            return view('tBase', $arr);
        }
    }


    /*
    *   inbox for user
    */
    public function inbox()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        $tahun = ($this->request->getGet('tahun'))?:date('Y');
        $bulan = ($this->request->getGet('bulan'))?:date('m');
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->PersuratanModel->inbox_datatable();
            $q = $this->PersuratanModel->inbox_datatable(true);
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
                'title' => 'Surat Masuk',
                'page' => 'Persuratan/inbox',
                'pegawai_id' => string_to($this->pegawai_id, 'encode'),
            ];
            return view('tBase', $arr);
        }
    }

    /*
    *   form tindaklanjut
    */
    public function tindaklanjut_form()
    {
        $this->array_response['code'] = 200;
        $id = ($this->request->getGet('id'))?string_to($this->request->getGet('id'),'decode'):0;
        $data = $this->PersuratanModel->surat_get_row($id, $this->pegawai_id);
        if($_POST)
        {
            $setRules = [
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'required|max_length[350]',
                ],
                'status' => [
                    'label' => 'Status Respon', 
                    'rules' => 'required|integer|max_length[1]',
                ],
                'catatan' => [
                    'label' => 'Catatan', 
                    'rules' => 'max_length[65000]',
                ],
                'file_lampiran' => [
                    'label' => 'Lampiran', 
                    'rules' => 'max_length[65000]',
                ],
            ];
            if($this->request->getPost('status')==2)
            {
                $setRules['disposisi2.*'] = [
                        'label' => 'Tindaklanjut Referensi', 
                        'rules' => 'required|max_length[65000]',
                    ];
            }else{
                $setRules['disposisi.*'] = [
                        'label' => 'Disposisi Referensi', 
                        'rules' => 'max_length[65000]',
                    ];
                $setRules['optional'] = [
                        'label' => 'Tambah Untuk', 
                        'rules' => 'required|integer|max_length[1]',
                    ];
                switch ($this->request->getPost('optional')) {
                    case 2:
                        $setRules['unit_kerja.*'] = [
                                'label' => 'Unit Kerja', 
                                'rules' => 'required|integer|max_length[500]',
                            ];
                        break;
                    case 3:
                        $setRules['pegawai_id.*'] = [
                                'label' => 'Pegawai', 
                                'rules' => 'required|integer|max_length[500]',
                            ];
                        break;
                }
            }
            $this->validation->setRules($setRules);
            if($this->validation->run($_POST))
            {
                if(!empty($data) && array_keys([6,7],$data['status']) && (return_roles([1]) || return_check_penerima_by_surat_id_and_pegawai_id($data['id'], $this->pegawai_id)))
                {
                    // $id = string_to(strip_tags($this->request->getPost('id')),'decode');
                    $status = $this->request->getPost('status');
                    $catatan = strip_tags($this->request->getPost('catatan'), ['ul','ol','li','b','i','u','br','hr','p']);
                    $optional = $this->request->getPost('optional');
                    $unit_kerja = $this->request->getPost('unit_kerja');
                    $pegawai_id = $this->request->getPost('pegawai_id');
                    $disposisi = $this->request->getPost('disposisi');
                    $disposisi2 = $this->request->getPost('disposisi2');
                    $lampiran = $this->request->getPost('file_lampiran');
                    if($disposisi<>'' || $disposisi2<>'' || $catatan<>'')
                    {
                        $this->array_response['POST__'] = $_POST;
                        $data_field = [
                            'surat_id' => $data['id'],
                            'pengirim_id' => $this->pegawai_id,
                            'pengirim_jabatan' => $this->jabatan_id,
                            'pengirim_unit' => $this->unit_kerja_id,
                            'sent_time' => $this->datetime_now,
                            'status' => $status,
                            'catatan' => $catatan,
                            'lampiran' => trim($lampiran,','),
                            'create_at' => $this->datetime_now,
                            'create_by' => $this->user_id,
                        ];
                        if($status==2)
                        {
                            $data_field['value'] = implode(', ', $disposisi2);
                            $data_field['read'] = 1;
                            $data_field['read_time'] = $this->datetime_now;
                            $data_field['respon'] = 1;
                            $data_field['respon_time'] = $this->datetime_now;
                            $save_ = $this->PersuratanModel->surat_tindaklanjut_save($data_field);
                            if($save_>0)
                            {
                                if($data['status']<7)
                                {
                                    $this->PersuratanModel->surat_save(['status'=>7, 'respon'=>1], ['id'=>$data['id']]);
                                }
                                $this->PersuratanModel->surat_trace_save(['id'=>$data['id'], 'status'=>7, 'status_name'=>return_referensi_row_by('surat_status_ext', 7)->ref_name, 'proccess_by'=>$this->user_id, 'description'=>'Menindaklanjuti surat masuk', 'proccess_at'=>$this->datetime_now]);
                            }
                        }else{
                            $save_ = 0;
                            $dataPegawai = [];
                            $data_field['value'] = implode(', ', $disposisi);
                            switch ($optional) {
                                case 1:
                                    // all pegawai...
                                    $dataPegawai = $this->KepegawaianModel->pegawai_aktif_result_all();
                                    break;
                                case 2:
                                    // perunit kerja...
                                    $dataPegawai = $this->KepegawaianModel->pegawai_aktif_result_in_unit($unit_kerja);
                                    break;
                                case 3:
                                    // perorangan...
                                    $dataPegawai = $this->KepegawaianModel->pegawai_aktif_result_in_pegawaiid($pegawai_id);
                                    break;
                            }
                            if(!empty($dataPegawai))
                            {
                                foreach ($dataPegawai as $k) {
                                    $data_field['penerima_id'] = $k->pegawai_id;
                                    $data_field['penerima_jabatan'] = $k->jabatan_id;
                                    $data_field['penerima_unit'] = $k->unit_kerja_id;
                                    $save_rs = $this->PersuratanModel->surat_tindaklanjut_save($data_field);
                                    if($save_rs>0)
                                    {
                                        $save_ += $save_rs;
                                    }
                                }
                            }
                            if($save_>0)
                            {
                                if($data['status']<7)
                                {
                                    $this->PersuratanModel->surat_save(['status'=>7, 'respon'=>1], ['id'=>$data['id']]);
                                }
                                $this->PersuratanModel->surat_trace_save(['id'=>$data['id'], 'status'=>7, 'status_name'=>return_referensi_row_by('surat_status_ext', 7)->ref_name, 'proccess_by'=>$this->user_id, 'description'=>'Mendisposisi surat masuk', 'proccess_at'=>$this->datetime_now]);
                            }
                        }
                        if($save_>0)
                        {
                            $this->array_response['status'] = TRUE;
                            $this->array_response['message'] = 'Successful';
                            return_update_status_respon_surat($data['pos_id'], $this->datetime_now, $this->pegawai_id);
                        }else{
                            $this->array_response['message'] = 'Save failed';
                        }
                    }else{
                        $this->array_response['message'] = 'Referensi Disposisi atau keterangan/disposisi/catatan tidak boleh kosong';
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
                'title' => 'Tindak lanjut Surat Masuk',
                'page' => 'Persuratan/tindak_lanjut_form',
                'data' => $data,
                'link' => $this->request->getGet('link'),
                'datetime_now' => $this->datetime_now
            ];
            return view('tBase', $arr);
        }
    }

    /*
    *   form tindaklanjut_disposisi
    */
    public function tindaklanjut_disposisi_form()
    {
        $this->array_response['code'] = 200;
        $id = ($this->request->getGet('id'))?string_to($this->request->getGet('id'),'decode'):0;
        $id_tl = ($this->request->getGet('id_tl'))?string_to($this->request->getGet('id_tl'),'decode'):0;
        $data = $this->PersuratanModel->surat_get_row($id, $this->pegawai_id);
        $data_tindaklanjut = $this->PersuratanModel->surat_tindaklanjut_row_by_id($id_tl);
        if($_POST)
        {
            $setRules = [
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'required|max_length[350]',
                ],
                'id_tl' => [
                    'label' => 'ID Tindak lanjut', 
                    'rules' => 'required|max_length[350]',
                ],
                'status' => [
                    'label' => 'Status Respon', 
                    'rules' => 'required|integer|max_length[1]',
                ],
                'catatan' => [
                    'label' => 'Catatan', 
                    'rules' => 'max_length[65000]',
                ],
                'file_lampiran' => [
                    'label' => 'Lampiran', 
                    'rules' => 'max_length[65000]',
                ],
            ];
            if($this->request->getPost('status')==2)
            {
                $setRules['disposisi2.*'] = [
                        'label' => 'Tindaklanjut Referensi', 
                        'rules' => 'required|max_length[65000]',
                    ];
            }else{
                $setRules['disposisi.*'] = [
                        'label' => 'Disposisi Referensi', 
                        'rules' => 'max_length[65000]',
                    ];
                $setRules['optional'] = [
                        'label' => 'Tambah Untuk', 
                        'rules' => 'required|integer|max_length[1]',
                    ];
                switch ($this->request->getPost('optional')) {
                    case 2:
                        $setRules['unit_kerja.*'] = [
                                'label' => 'Unit Kerja', 
                                'rules' => 'required|integer|max_length[500]',
                            ];
                        break;
                    case 3:
                        $setRules['pegawai_id.*'] = [
                                'label' => 'Pegawai', 
                                'rules' => 'required|integer|max_length[500]',
                            ];
                        break;
                }
            }
            $this->validation->setRules($setRules);
            if($this->validation->run($_POST))
            {
                if(!empty($data) && !empty($data_tindaklanjut) && array_keys([6,7],$data['status']) && (return_roles([1]) || $data_tindaklanjut->penerima_id==$this->pegawai_id))
                {
                    // $id = string_to(strip_tags($this->request->getPost('id')),'decode');
                    // $id_tl = string_to(strip_tags($this->request->getPost('id_tl')),'decode');
                    $status = $this->request->getPost('status');
                    $catatan = strip_tags($this->request->getPost('catatan'), ['ul','ol','li','b','i','u','br','hr','p']);
                    $optional = $this->request->getPost('optional');
                    $unit_kerja = $this->request->getPost('unit_kerja');
                    $pegawai_id = $this->request->getPost('pegawai_id');
                    $disposisi = $this->request->getPost('disposisi');
                    $disposisi2 = $this->request->getPost('disposisi2');
                    $lampiran = $this->request->getPost('file_lampiran');
                    if($disposisi<>'' || $disposisi2<>'' || $catatan<>'')
                    {
                        $this->array_response['POST__'] = $_POST;
                        $data_field = [
                            'surat_id' => $data['id'],
                            'ref_id' => $id_tl,
                            'pengirim_id' => $this->pegawai_id,
                            'pengirim_jabatan' => $this->jabatan_id,
                            'pengirim_unit' => $this->unit_kerja_id,
                            'sent_time' => $this->datetime_now,
                            'status' => $status,
                            'catatan' => $catatan,
                            'lampiran' => trim($lampiran,','),
                            'create_at' => $this->datetime_now,
                            'create_by' => $this->user_id,
                        ];
                        if($status==2)
                        {
                            $data_field['value'] = implode(', ', $disposisi2);
                            $data_field['read'] = 1;
                            $data_field['read_time'] = $this->datetime_now;
                            $data_field['respon'] = 1;
                            $data_field['respon_time'] = $this->datetime_now;
                            $save_ = $this->PersuratanModel->surat_tindaklanjut_save($data_field);
                            if($save_>0)
                            {
                                if($data['status']<7)
                                {
                                    $save_ = $this->PersuratanModel->surat_save(['status'=>7, 'respon'=>1], ['id'=>$data['id']]);
                                }
                                $this->PersuratanModel->surat_trace_save(['id'=>$data['id'], 'status'=>7, 'status_name'=>return_referensi_row_by('surat_status_ext', 7)->ref_name, 'proccess_by'=>$this->user_id, 'description'=>'Sdr/i '.$data_tindaklanjut->penerima_nama.' menindaklanjuti disposisi', 'proccess_at'=>$this->datetime_now]);
                            }
                        }else{
                            $save_ = 0;
                            $dataPegawai = [];
                            $data_field['value'] = implode(', ', $disposisi);
                            switch ($optional) {
                                case 1:
                                    // all pegawai...
                                    $dataPegawai = $this->KepegawaianModel->pegawai_aktif_result_all();
                                    break;
                                case 2:
                                    // perunit kerja...
                                    $dataPegawai = $this->KepegawaianModel->pegawai_aktif_result_in_unit($unit_kerja);
                                    break;
                                case 3:
                                    // perorangan...
                                    $dataPegawai = $this->KepegawaianModel->pegawai_aktif_result_in_pegawaiid($pegawai_id);
                                    break;
                            }
                            if(!empty($dataPegawai))
                            {
                                foreach ($dataPegawai as $k) {
                                    $data_field['penerima_id'] = $k->pegawai_id;
                                    $data_field['penerima_jabatan'] = $k->jabatan_id;
                                    $data_field['penerima_unit'] = $k->unit_kerja_id;
                                    $save_rs = $this->PersuratanModel->surat_tindaklanjut_save($data_field);
                                    if($save_rs>0)
                                    {
                                        $save_ += $save_rs;
                                    }
                                }
                            }
                            if($save_>0)
                            {
                                if($data['status']<7)
                                {
                                    $save_ = $this->PersuratanModel->surat_save(['status'=>7, 'respon'=>1], ['id'=>$data['id']]);
                                }
                                $this->PersuratanModel->surat_trace_save(['id'=>$data['id'], 'status'=>7, 'status_name'=>return_referensi_row_by('surat_status_ext', 7)->ref_name, 'proccess_by'=>$this->user_id, 'description'=>'Sdr/i '.$data_tindaklanjut->penerima_nama.' mendisposisi disposisi', 'proccess_at'=>$this->datetime_now]);
                            }
                        }
                        if($save_>0)
                        {
                            $this->array_response['status'] = TRUE;
                            $this->array_response['message'] = 'Successful';
                            return_update_status_respon_surat($data['pos_id'], $this->datetime_now, $this->pegawai_id);
                        }else{
                            $this->array_response['message'] = 'Save failed';
                        }
                    }else{
                        $this->array_response['message'] = 'Referensi Disposisi atau keterangan/disposisi/catatan tidak boleh kosong';
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
                'title' => 'Tindak lanjut Disposisi',
                'page' => 'Persuratan/tindak_lanjut_disposisi_form',
                'data' => $data,
                'data_tindaklanjut' => $data_tindaklanjut,
                'link' => $this->request->getGet('link'),
                'datetime_now' => $this->datetime_now
            ];
            return view('tBase', $arr);
        }
    }




    public function sent()
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
                $jml = $this->PersuratanModel->draft_datatable();
                $q = $this->PersuratanModel->draft_datatable(true);
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
                    'page' => 'Persuratan/sent',
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






    public function review()
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
                $jml = $this->PersuratanModel->draft_datatable();
                $q = $this->PersuratanModel->draft_datatable(true);
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
                    'page' => 'Persuratan/review',
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
    *   hapus cuti 
    */
    function hapus()
    {
        $this->array_response['code'] = 200;
        $this->array_response['message'] = 'Gagal dihapus';
        $id = ($this->request->getGet('id'))?string_to($this->request->getGet('id'), 'decode'):0;
        $data = $this->PersuratanModel->surat_get_row($id, $this->pegawai_id);
        if(!empty($data) && array_keys([1],$data['status']) && (return_roles([1]) || $data['create_by']==$this->user_id))
        {
            $this->PersuratanModel->surat_delete(['id'=>$data['id']]);
            $this->PersuratanModel->surat_penerima_delete(['surat_id'=>$data['id']]);
            $this->PersuratanModel->surat_tembusan_delete(['surat_id'=>$data['id']]);
            $this->PersuratanModel->surat_reviewer_delete(['surat_id'=>$data['id']]);
            $this->PersuratanModel->surat_signer_delete(['surat_id'=>$data['id']]);
            $this->PersuratanModel->surat_pelaksana_delete(['surat_id'=>$data['id']]);
            $this->PersuratanModel->surat_in_tu_delete(['surat_id'=>$data['id']]);
            $this->PersuratanModel->surat_trace_delete(['id'=>$data['id']]);
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Hapus data surat berhasil';
        }else{
            $this->array_response['message'] = 'Data tidak ditemukan atau tidak mendapat akses';
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
    *   register surat masuk
    */
    public function register()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        $tahun = ($this->request->getGet('tahun'))?:date('Y');
        $bulan = ($this->request->getGet('bulan'))?:date('m');
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->PersuratanModel->register_datatable();
            $q = $this->PersuratanModel->register_datatable(true);
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
                'title' => 'Daftar Register Surat Masuk KL',
                'page' => 'Persuratan/register',
                'tab' => $this->request->getGet('tab'),
            ];
            return view('tBase', $arr);
        }
    }

    /*
    *   register form
    */
    public function register_form()
    {
        $this->array_response['code'] = 200;
        $id = ($this->request->getGet('id'))?string_to($this->request->getGet('id'),'decode'):0;
        $data = $this->PersuratanModel->surat_get_row($id);
        if($_POST)
        {
            $setRules = [
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'required|max_length[350]',
                ],
                'sumber_bentuk' => [
                    'label' => 'Sumber', 
                    'rules' => 'required|integer|min_length[1]|max_length[1]',
                ],
                'sifat' => [
                    'label' => 'Sifat', 
                    'rules' => 'required|integer|min_length[1]|max_length[1]',
                ],
                'urgensi' => [
                    'label' => 'Urgensi', 
                    'rules' => 'required|integer|min_length[1]|max_length[1]',
                ],
                'nomor' => [
                    'label' => 'Nomor Surat', 
                    'rules' => 'required|min_length[1]|max_length[100]',
                ],
                'tanggal' => [
                    'label' => 'Tanggal Surat', 
                    'rules' => 'required|min_length[10]|max_length[10]',
                ],
                'hal' => [
                    'label' => 'Hal Surat', 
                    'rules' => 'required|min_length[3]|max_length[350]',
                ],
                'pengirim' => [
                    'label' => 'Pengirim', 
                    'rules' => 'required|min_length[3]|max_length[500]',
                ],
                'pengirim_alamat' => [
                    'label' => 'Alamat Pengirim', 
                    'rules' => 'required|min_length[1]|max_length[500]',
                ],
                'penerima[]' => [
                    'label' => 'Penerima', 
                    'rules' => 'max_length[500]',
                ],
                'path_file' => [
                    'label' => 'Surat/Naskah', 
                    'rules' => 'required|integer|min_length[1]|max_length[11]',
                ],
                'lampiran' => [
                    'label' => 'Lampiran', 
                    'rules' => 'max_length[350]',
                ],
                'pengirim_alamat' => [
                    'label' => 'Alamat Pengirim', 
                    'rules' => 'max_length[500]',
                ],
            ];
            $this->validation->setRules($setRules);
            if($this->validation->run($_POST))
            {
                // $id = string_to(strip_tags($this->request->getPost('id')),'decode');
                $penerima = $this->request->getPost('penerima');
                if(!empty($penerima))
                {
                    $path_file = strip_tags($this->request->getPost('path_file'));
                    $data_field = [
                        'sumber_ext' => 1, 
                        'sumber_bentuk' => $this->request->getPost('sumber_bentuk'), 
                        'sifat' => $this->request->getPost('sifat'), 
                        'urgensi' => $this->request->getPost('urgensi'), 
                        'nomor' => strip_tags($this->request->getPost('nomor')), 
                        'tanggal' => strip_tags($this->request->getPost('tanggal')), 
                        'hal' => strip_tags($this->request->getPost('hal')), 
                        'pengirim' => strip_tags($this->request->getPost('pengirim')), 
                        'pengirim_alamat' => strip_tags($this->request->getPost('pengirim_alamat')), 
                        'penerima_sebagai' => strip_tags($this->request->getPost('penerima_sebagai')), 
                        'catatan' => strip_tags($this->request->getPost('catatan'), ['ul','ol','li','b','i','u','br','hr','p']), 
                        'path' => $path_file, 
                        'path_sign' => files_path_by_id($path_file), 
                        'lampiran' => strip_tags($this->request->getPost('lampiran_file')), 
                        'last_change' => json_encode(remove_key_in_array($_POST, ['password', 'passphrase'])),
                    ];
                    if(!empty($data))
                    {
                        $data_field['update_at'] = $this->datetime_now;
                        $data_field['update_by'] = $this->user_id;
                        $save_ = $this->PersuratanModel->surat_save($data_field, ['id'=>$id]);
                        $this->PersuratanModel->surat_trace_save(['id'=>$id, 'status'=>1, 'status_name'=>'Ubah data', 'proccess_by'=>$this->user_id, 'description'=>'Ubah data surat masuk', 'proccess_at'=>$this->datetime_now]);
                    }else{
                        $data_field['register_number'] = $this->PersuratanModel->create_register_number();
                        $data_field['register_time'] = $this->datetime_now;
                        $data_field['create_by'] = $this->user_id;
                        $save_ = $this->PersuratanModel->surat_save($data_field);
                        $id = $save_;
                        $this->PersuratanModel->surat_trace_save(['id'=>$id, 'status'=>1, 'status_name'=>return_referensi_row_by('surat_status_ext', 1)->ref_name, 'proccess_by'=>$this->user_id, 'description'=>'Register surat masuk', 'proccess_at'=>$this->datetime_now]);
                    }
                    if($save_>0)
                    {
                        $this->array_response['status'] = TRUE;
                        $this->array_response['message'] = 'Successful';
                        $this->PersuratanModel->surat_penerima_delete(['surat_id'=>$id, 'read'=>0]);
                        $penerima = array_unique($penerima);
                        if(!empty($penerima))
                        {
                            foreach ($penerima as $key => $value) {
                                $dpeg = mini_field_pegawai_by_id($value);
                                if(!empty($dpeg))
                                {
                                    $this->PersuratanModel->surat_penerima_save(
                                        [
                                            'surat_id' => $id,
                                            'pegawai_id' => $dpeg->pegawai_id,
                                            'jabatan_id' => $dpeg->jabatan_id,
                                            'unit_kerja_id' => $dpeg->unit_kerja_id,
                                        ]
                                    );
                                }
                            }
                        }
                    }else{
                        $this->array_response['message'] = 'Save failed';
                    }
                }else{
                    $this->array_response['message'] = 'Field penerima tidak boleh kosong';
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
                'title' => 'Form Register Surat Masuk',
                'page' => 'Persuratan/register_form',
                'id' => $id,
                'data' => $data,
                'link' => $this->request->getGet('link'),
            ];
            return view('tBase', $arr);
        }
    }


    /*
    *   teruskan kepenerima
    */
    public function teruskan_form()
    {
        $this->array_response['code'] = 200;
        $id = ($this->request->getGet('id'))?string_to($this->request->getGet('id'),'decode'):0;
        $data = $this->PersuratanModel->surat_get_row($id);
        if($_POST)
        {
            $setRules = [
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'required|max_length[350]',
                ],
                'catatan' => [
                    'label' => 'Catatan', 
                    'rules' => 'max_length[65000]',
                ],
                'penerima[]' => [
                    'label' => 'Penerima', 
                    'rules' => 'max_length[500]',
                ],
            ];
            $this->validation->setRules($setRules);
            if($this->validation->run($_POST))
            {
                if(!empty($data) && array_keys([1,2,3,4,5],$data['status']) /*&& (return_roles([1]))*/)
                {
                    // $id = string_to(strip_tags($this->request->getPost('id')),'decode');
                    $penerima = $this->request->getPost('penerima');
                    $catatan = strip_tags($this->request->getPost('catatan'), ['ul','ol','li','b','i','u','br','hr','p']);
                    if(!empty($penerima))
                    {
                        $save_ = $this->PersuratanModel->surat_save(['status'=>6], ['id'=>$data['id']]);
                        $this->PersuratanModel->surat_trace_save(['id'=>$data['id'], 'status'=>6, 'status_name'=>return_referensi_row_by('surat_status_ext', 6)->ref_name, 'proccess_by'=>$this->user_id, 'description'=>'Meneruskan surat masuk', 'proccess_at'=>$this->datetime_now]);
                        if($save_>0)
                        {
                            $this->array_response['status'] = TRUE;
                            $this->array_response['message'] = 'Berhasil';
                            $penerima = array_unique($penerima);
                            if(!empty($penerima))
                            {
                                foreach ($penerima as $key => $value) {
                                    $checkSuratPenerima = $this->PersuratanModel->surat_penerima_getWhere_row(['surat_id'=>$data['id'], 'pegawai_id'=>$value]);
                                    $dpeg = mini_field_pegawai_by_id($value);
                                    if(!empty($dpeg))
                                    {
                                        if(empty($checkSuratPenerima))
                                        {
                                            $this->PersuratanModel->surat_penerima_save(
                                                [
                                                    'surat_id' => $id,
                                                    'pegawai_id' => $dpeg->pegawai_id,
                                                    'jabatan_id' => $dpeg->jabatan_id,
                                                    'unit_kerja_id' => $dpeg->unit_kerja_id,
                                                    'catatan' => $catatan,
                                                    'status' => 2,
                                                    'sent' => 1,
                                                    'sent_time' => $this->datetime_now,
                                                ]
                                            );
                                        }else{
                                            $this->PersuratanModel->surat_penerima_save(
                                                [
                                                    'catatan' => $catatan,
                                                    'sent' => 1,
                                                    'sent_time' => $this->datetime_now,
                                                ],
                                                ['id'=>$checkSuratPenerima->id]
                                            );
                                        }
                                    }
                                }
                            }
                        }else{
                            $this->array_response['message'] = 'Gagal';
                        }
                    }else{
                        $this->array_response['message'] = 'Field penerima tidak boleh kosong';
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
                'title' => 'Meneruskan Surat Masuk',
                'page' => 'Persuratan/teruskan_form',
                'data' => $data,
                'link' => $this->request->getGet('link'),
            ];
            return view('tBase', $arr);
        }
    }

}