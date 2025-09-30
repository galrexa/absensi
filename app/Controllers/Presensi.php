<?php
namespace App\Controllers;
use App\Models\PresensiModel;
use App\Models\KepegawaianModel;

class Presensi extends BaseController
{
    public function __construct()
    {
        $this->PresensiModel = new PresensiModel();
        $this->KepegawaianModel = new KepegawaianModel();
        $this->user_id = session()->get('id');
        $this->pegawai_id = session()->get('pegawai_id');
        $this->jabatan_id = session()->get('jabatan_id');
        $this->unit_kerja_id = session()->get('unit_kerja_id');
        $this->date_now = date('Y-m-d');
        $this->datetime_now = date('Y-m-d H:i:s');
        $this->kode_hari = date('D');
        session()->set('id_app', 3);
    }



    public function index()
    {
        $arr = [
            'title' => 'Presensi',
            'page' => 'Presensi/index',
            'data' => $this->db->query("SELECT * FROM presensi_final a where a.pegawai_id=? and a.tanggal=? ", [$this->pegawai_id, $this->date_now])->getRow(),
            'list_area' => $this->PresensiModel->list_ms_area(),
        ];
        return view($arr['page'], $arr);
    }



    /*
    *   CHECK STATUS PRESENSI SAAT INI
    */
    public function check_now()
    {
        $this->array_response['code'] = 200;
        $data_presensi = $this->PresensiModel->presensi_final_check_now($this->pegawai_id, $this->date_now);
        if(!empty($data_presensi))
        {
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = 'Data Presensi';
            $this->array_response['data'] = $data_presensi;
        }else{
            $this->array_response['message'] = 'Maaf, Anda belum melakukan Presensi pada hari ini.';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'GET, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Bearer')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }



    /*
    *   PRESENSI MULAI 
    */
    public function start()
    {
        $this->array_response['code'] = 200;
        $this->opt_pre = return_value_in_options('presensi');
        if($this->pegawai_id<>0)
        {
            if(date('Y-m-d').' '.$this->opt_pre['open'] < $this->datetime_now)
            {
                if($_POST)
                {
                    $setRules = [
                        'latlong' => [
                            'label' => 'LatLong', 
                            'rules' => 'required|max_length[100]',
                        ],
                    ];
                    $latlong = strip_tags($this->request->getPost('latlong'));
                    $xlatlong = explode(',', $latlong);
                    $start_log = check_location_in_radius_absen($xlatlong[0], $xlatlong[1])['status'];
                    if($start_log==false)
                    {
                        $setRules['keterangan'] = ['label' => 'Keterangan', 'rules' => 'required|max_length[65535]|min_length[3]'];
                        $setRules['file_foto'] = ['label' => 'Foto', 'rules' => 'required|max_length[50]|min_length[1]'];
                    }
                    $this->validation->setRules($setRules);
                    if($this->validation->run($_POST))
                    {
                        $ip = $this->request->getIPAddress();
                        $catatan = strip_tags($this->request->getPost('keterangan'));
                        $file_foto = $this->request->getPost('file_foto');
                        $data_presensi = $this->PresensiModel->presensi_final_check_in_start($this->pegawai_id, $this->date_now);
                        $this->array_response['data'] = $data_presensi;
                        if(empty($data_presensi))
                        {
                            $this->PresensiModel->presensi_save([
                                'pegawai_id' => $this->pegawai_id, 
                                'jabatan_id' => $this->jabatan_id, 
                                'unit_kerja_id' => $this->unit_kerja_id, 
                                'tanggal' => $this->date_now, 
                                'start' => $this->datetime_now, 
                                'start_ip' => $ip, 
                                'start_latlong' => $latlong, 
                                'start_catatan' => $catatan, 
                                'start_log' => text_log_area($start_log),
                                'start_cam' => $file_foto,
                                'kode_hari' => $this->kode_hari,
                                'start_user' => $this->user_id
                            ]);
                            if($this->db->affectedRows() > 0)
                            {
                                $this->array_response['status'] = TRUE;
                                $this->array_response['message'] = 'Absen mulai berhasil disimpan.';
                                $data_presensi_final = $this->PresensiModel->presensi_final_get($this->pegawai_id, $this->date_now);
                                if(empty($data_presensi_final))
                                {
                                    $data_jam_kerja = $this->PresensiModel->presensi_jam_kerja_detail_get(1, $this->kode_hari);
                                    $data_presensi_final_source = [
                                        'pegawai_id' => $this->pegawai_id,
                                        'jabatan_id' => $this->jabatan_id,
                                        'unit_kerja_id' => $this->unit_kerja_id,
                                        'tanggal' => $this->date_now,
                                        'kode_hari' => $this->kode_hari,
                                        'df_jam_masuk' => $data_jam_kerja->jam_masuk,
                                        'df_jam_flexi' => $data_jam_kerja->jam_flexi,
                                        'df_jam_pulang' => $data_jam_kerja->jam_pulang,
                                        'df_durasi_absen' => $data_jam_kerja->durasi_absen,
                                        'df_durasi_istirahat' => $data_jam_kerja->durasi_istirahat,
                                        'df_durasi_kerja' => $data_jam_kerja->durasi_kerja,
                                        'df_durasi_flexi' => $data_jam_kerja->durasi_flexi,
                                        'start' => $this->datetime_now,
                                        'start_ip' => $ip,
                                        'start_latlong' => $latlong,
                                        'start_catatan' => $catatan,
                                        'start_log' => text_log_area($start_log),
                                        'start_cam' => $file_foto,
                                        'start_user' => $this->user_id,
                                    ];
                                    // if(date('Y-m-d H:i:s', strtotime($this->datetime_now) <= date('Y-m-d H:i:s', strtotime($this->date_now.' '.$data_jam_kerja->jam_istirahat_selesai))
                                    // {
                                    //     $data_presensi_final_source['start'] = $this->datetime_now;
                                    //     $data_presensi_final_source['start_ip'] = $ip;
                                    //     $data_presensi_final_source['start_latlong'] = $latlong;
                                    //     $data_presensi_final_source['start_catatan'] = $catatan;
                                    //     $data_presensi_final_source['start_log'] = text_log_area($start_log);
                                    //     $data_presensi_final_source['start_user'] = $this->user_id;
                                    // }else{
                                    //     $data_presensi_final_source['stop'] = $this->datetime_now;
                                    //     $data_presensi_final_source['stop_ip'] = $ip;
                                    //     $data_presensi_final_source['stop_latlong'] = $latlong;
                                    //     $data_presensi_final_source['stop_catatan'] = $catatan;
                                    //     $data_presensi_final_source['stop_log'] = text_log_area($start_log);
                                    //     $data_presensi_final_source['stop_user'] = $this->user_id;
                                    // }
                                    $this->PresensiModel->presensi_final_save($data_presensi_final_source);
                                    $this->PresensiModel->check_insert_pelanggaran_in_start($this->pegawai_id, $this->date_now);
                                }
                            }else{
                                $this->array_response['message'] = 'Absen mulai gagal disimpan.';
                            }
                        }else{
                            $this->array_response['message'] = 'Sudah melakukan absen mulai.';
                        }
                    }else{
                        $this->array_response['message'] = stripcslashes(strip_tags($this->validation->listErrors()));
                    }
                }else{
                    $this->array_response['code'] = 405;
                    $this->array_response['message'] = 'Method tidak diizinkan';
                }
            }else{
                $this->array_response['message'] = 'Maaf, Jam absen belum dimulai.';
            }
        }else{
            // $this->array_response['code'] = 401;
            $this->array_response['message'] = 'Maaf, Anda bukan user pegawai';
        }
        $this->session->setFlashdata('message', $this->array_response['message']);
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Bearer')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }



    /*
    *   PRESENSI SELESAI
    */
    public function stop()
    {
        $this->array_response['code'] = 200;
        if($this->pegawai_id<>0)
        {
            if($_POST)
            {
                $setRules = [
                    'id' => [
                        'label' => 'ID', 
                        'rules' => 'integer|max_length[11]',
                    ],
                    'latlong' => [
                        'label' => 'LatLong', 
                        'rules' => 'required|max_length[100]',
                    ],
                ];
                $latlong = strip_tags($this->request->getPost('latlong'));
                $xlatlong = explode(',', $latlong);
                $stop_log = check_location_in_radius_absen($xlatlong[0], $xlatlong[1])['status'];
                if($stop_log==false)
                {
                    $setRules['keterangan'] = ['label' => 'Keterangan', 'rules' => 'required|max_length[65535]|min_length[3]'];
                    $setRules['file_foto'] = ['label' => 'Foto', 'rules' => 'required|max_length[50]|min_length[1]'];
                }
                $this->validation->setRules($setRules);
                if($this->validation->run($_POST))
                {
                    $id = strip_tags($this->request->getPost('id'));
                    $ip = $this->request->getIPAddress();
                    $catatan = strip_tags($this->request->getPost('keterangan'));
                    $file_foto = $this->request->getPost('file_foto');
                    $data_presensi = $this->PresensiModel->presensi_final_check_in_stop($this->pegawai_id, $id);
                    if(!empty($data_presensi))
                    {
                        $this->PresensiModel->presensi_save([
                            'stop' => $this->datetime_now,
                            'stop_ip' => $ip,
                            'stop_latlong' => $latlong,
                            'stop_catatan' => $catatan,
                            'stop_log' => text_log_area($stop_log),
                            'stop_cam' => $file_foto,
                            'status' => 0,
                            'stop_user' => $this->user_id
                        ], ['id'=>$data_presensi->id]);
                        if($this->db->affectedRows() > 0)
                        {
                            $this->array_response['status'] = TRUE;
                            $this->array_response['message'] = 'Absen selesai berhasil disimpan.';
                            $data_presensi_final = $this->PresensiModel->presensi_final_get($this->pegawai_id, $this->date_now);
                            if(!empty($data_presensi_final))
                            {
                                $file_foto2 = explode(',', ($data_presensi_final->stop_cam)?$data_presensi_final->stop_cam:'');
                                array_push($file_foto2, $file_foto);
                                $this->PresensiModel->presensi_final_save([
                                    'stop' => $this->datetime_now,
                                    'stop_ip' => $ip,
                                    'stop_latlong' => $latlong,
                                    'stop_catatan' => ($data_presensi_final->stop_catatan)?$data_presensi_final->stop_catatan.', '.$catatan:$catatan,
                                    'stop_log' => text_log_area($stop_log),
                                    'stop_cam' => implode(',', $file_foto2),
                                    'status' => 1,
                                    'stop_user' => $this->user_id
                                ], ['pegawai_id' => $data_presensi->pegawai_id, 'tanggal' => $data_presensi->tanggal]);
                                $this->PresensiModel->presensi_final_update_durasi_in_stop($data_presensi->pegawai_id, $data_presensi->tanggal);
                                $this->PresensiModel->check_insert_pelanggaran_in_stop($data_presensi->pegawai_id, $data_presensi->tanggal);
                            }
                        }else{
                            $this->array_response['message'] = 'Absen selesai gagal disimpan.';
                        }
                    }else{
                        $this->array_response['message'] = 'Belum melakukan absen mulai.';
                    }
                }else{
                    $this->array_response['message'] = stripcslashes(strip_tags($this->validation->listErrors()));
                }
            }else{
                $this->array_response['code'] = 405;
                $this->array_response['message'] = 'Method tidak diizinkan.';
            }
        }else{
            // $this->array_response['code'] = 401;
            $this->array_response['message'] = 'Maaf, Anda bukan user pegawai';
        }
        $this->session->setFlashdata('message', $this->array_response['message']);
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Bearer')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }



    /*
    *   RIWAYAT PRESENSI
    */
    public function riwayat()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        $tahun = ($this->request->getGet('tahun'))?:date('Y');
        $bulan = ($this->request->getGet('bulan'))?:date('m');
        $pegawai_id = ($this->request->getGet('id'))?string_to($this->request->getGet('id'),'decode'):$this->pegawai_id;
        if($pegawai_id<>0 && (return_roles([1,2]) || $pegawai_id==$this->pegawai_id))
        {
            if($_POST)
            {
                $data = [];
                $draw = (int) (($this->request->getPost('draw')) ? : 1);
                $jml = $this->PresensiModel->presensi_final_in_riwayat();
                $q = $this->PresensiModel->presensi_final_in_riwayat(true);
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
                    'title' => 'Riwayat Presensi',
                    'page' => 'Presensi/riwayat',
                    'pegawai_id' => string_to($pegawai_id, 'encode'),
                    'data_pegawai' => $this->KepegawaianModel->pegawai_get_row($pegawai_id),
                    'list_tahun' => $this->PresensiModel->presensi_list_tahun($pegawai_id),
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                    'id' => $this->request->getGet('id'),
                ];
                if(return_roles([1,2]) && $this->request->getGet('id')<>''){
                    $arr['order'] = 'asc';
                    return view('tBase', $arr);
                }else{
                    $arr['order'] = 'desc';
                    return view('Presensi/tBaseClient', $arr);
                }
            }
        }else{
            session()->setFlashdata('message', 'Maaf, Anda bukan user pegawai atau tidak mendapat akses');
            return $this->index();
        }
    }

    public function riwayat_unduh()
    {
        $file = $this->request->getGet('file');
        $periode = ($this->request->getGet('periode'))?:date('Y-m');
        $pegawai_id = ($this->request->getGet('id'))?string_to($this->request->getGet('id'),'decode'):$this->pegawai_id;
        if($pegawai_id<>0 && (return_roles([1,2]) || $pegawai_id==$this->pegawai_id))
        {
            $data_pegawai = $this->KepegawaianModel->pegawai_get_row($pegawai_id);
            $q = $this->PresensiModel->presensi_final_in_riwayat_by_pegawai_id_periode($pegawai_id, $periode);
            $arr = [
                'title' => 'Riwayat Presensi ',
                'periode' => $periode,
                'data' => $q,
                'data_pegawai' => $data_pegawai,
                'response' => $this->response,
            ];
            switch ($file) {
                case 'print':
                    return view('Presensi/riwayat_report2', $arr);
                    break;
                case 'pdf':
                    return view('Presensi/riwayat_report3', $arr);
                    break;
                default:
                    return view('Presensi/riwayat_report1', $arr);
                    break;
            }
        }else{
            session()->setFlashdata('message', 'Maaf, File tidak ditemukan atau tidak mendapat akses');
            return $this->index();
        }
    }



    /*
    *   LAPORAN KEGIATAN HARIAN
    */
    public function laporan_kegiatan_form()
    {
        $this->array_response['code'] = 200;
        // $id = $this->request->getGet('id');
        $pegawai_id_ = ($this->request->getGet('id'))?string_to($this->request->getGet('id'),'decode'):$this->pegawai_id;
        $tanggal_ = $this->request->getGet('tanggal');
        $data = $this->PresensiModel->get_laporan_kegiatan_pegawai_in_day($pegawai_id_, $tanggal_);
        if($_POST)
        {
            $this->validation->setRules([
                'id' => [
                    'label' => 'ID', 
                    'rules' => 'integer|max_length[11]',
                ],
                'tanggal' => [
                    'label' => 'Tanggal', 
                    'rules' => 'required|max_length[10]',
                ],
                'pegawai_id' => [
                    'label' => 'Pegawai', 
                    'rules' => 'required|integer|max_length[11]',
                ],
                'laporan' => [
                    'label' => 'Laporan Kegiatan', 
                    'rules' => 'required|max_length[65535]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                $id = strip_tags($this->request->getPost('id'));
                $tanggal = strip_tags($this->request->getPost('tanggal'));
                $pegawai_id = strip_tags($this->request->getPost('pegawai_id'));
                $laporan = $this->request->getPost('laporan');
                if($pegawai_id_==$pegawai_id && $tanggal_==$tanggal)
                {
                    if(!empty($data))
                    {
                        $this->PresensiModel->laporan_kegiatan_save([
                            'tanggal'=>$tanggal, 
                            'pegawai_id'=>$pegawai_id, 
                            'laporan'=>$laporan,
                            'create_at' => date('Y-m-d H:i:s'),
                            'create_by'=>$this->session->id,
                            'lampiran' => trim($this->request->getPost('lampiran_file'), ','),
                        ], ['pegawai_id' => $pegawai_id, 'tanggal' => $tanggal]);
                    }else{
                        $this->PresensiModel->laporan_kegiatan_save([
                            'tanggal'=>$tanggal, 
                            'pegawai_id'=>$pegawai_id, 
                            'laporan'=>$laporan,
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by'=>$this->session->id,
                            'lampiran' => trim($this->request->getPost('lampiran_file'), ','),
                        ]);
                    }
                    if($this->db->affectedRows() > 0)
                    {
                        $this->array_response['status'] = TRUE;
                        $this->array_response['message'] = 'Laporan kegiatan berhasil disimpan.';
                    }else{
                        $this->array_response['message'] = 'Laporan kegiatan gagal disimpan.';
                    }
                }else{
                    $this->array_response['message'] = 'Maaf, Anda tidak mendapatkan akses atau ada data yang tidak valid.';
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
                'title' => 'Laporan Kegiatan Form',
                'page' => 'Presensi/laporan_form',
                'data' => $data,
                'tanggal' => $tanggal_,
                'pegawai_id' => $pegawai_id_,
            ];
            return view('Presensi/tBaseClient', $arr);
        }
    }

    public function laporan_kegiatan_view()
    {
        $this->array_response['code'] = 200;
        $id = string_to($this->request->getGet('id'), 'decode');
        $tanggal = $this->request->getGet('tanggal');
        $data = $this->PresensiModel->get_laporan_kegiatan_pegawai_in_day($id, $tanggal);
        $presensi = $this->PresensiModel->presensi_final_get($id, $tanggal);
        $arr = [
            'title' => 'Laporan Kegiatan',
            'page' => 'Presensi/laporan_view',
            'data' => $data,
            'presensi' => $presensi,
            'list_pelanggaran' => $this->PresensiModel->result_pelanggaran_pegawai_in_day($id, $tanggal),
            'link' => $this->request->getGet('link'),
       ];
        return view('tBase', $arr);
    }



    /*
    *   LIST PRESENSI HARIAN BY ADMIN
    */
    public function harian()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->PresensiModel->presensi_final_in_harian();
            $q = $this->PresensiModel->presensi_final_in_harian(true);
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
                'title' => 'Presensi Harian',
                'page' => 'Presensi/harian',
                'unit' => ''
            ];
            return view('tBase', $arr);
        }
    }

    public function harian_unduh()
    {
        $tanggal = $this->request->getGet('tanggal');
        $unit_kerja_id = $this->request->getGet('unit_kerja_id');
        $jabatan_id = $this->request->getGet('jabatan_id');
        $search = $this->request->getGet('search');
        $file = $this->request->getGet('file');
        $q = $this->PresensiModel->presensi_final_in_harian_unduh($tanggal, $unit_kerja_id, $jabatan_id, $search);
        if(!empty($q))
        {
            $arr = [
                'title' => 'Presensi Harian',
                'tanggal' => $tanggal,
                'data' => $q
            ];
            switch ($file) {
                case 'pdf':
                    return view('Presensi/harian_report2', $arr);
                    break;
                default:
                    return view('Presensi/harian_report1', $arr);
                    break;
            }
        }else{
            session()->setFlashdata('message', 'Maaf, File tidak ditemukan atau tidak mendapat akses');
            return $this->index();
        }
    }



    /*
    *   RESUME PRESENSI BULANAN BY ADMIN
    */
    public function bulanan()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->PresensiModel->presensi_final_in_bulanan();
            $q = $this->PresensiModel->presensi_final_in_bulanan(true);
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
                'title' => 'Resume Presensi Bulanan',
                'page' => 'Presensi/bulanan',
                'list_tahun' => $this->PresensiModel->presensi_list_tahun(),
                'unit' => ''
            ];
            return view('tBase', $arr);
        }
    }

    public function bulanan_unduh()
    {
        $periode = $this->request->getGet('periode');
        $unit_kerja_id = $this->request->getGet('unit_kerja_id');
        $jabatan_id = $this->request->getGet('jabatan_id');
        $search = $this->request->getGet('search');
        $file = $this->request->getGet('file');
        $q = $this->PresensiModel->presensi_final_in_bulanan_unduh($periode, $unit_kerja_id, $jabatan_id, $search);
        if(!empty($q))
        {
            $arr = [
                'title' => 'Resume Presensi Bulanan',
                'periode' => $periode,
                'data' => $q
            ];
            switch ($file) {
                case 'print':
                    return view('Presensi/bulanan_report2', $arr);
                    break;
                case 'pdf':
                    return view('Presensi/bulanan_report3', $arr);
                    break;
                default:
                    return view('Presensi/bulanan_report1', $arr);
                    break;
            }
        }else{
            session()->setFlashdata('message', 'Maaf, File tidak ditemukan atau tidak mendapat akses');
            return $this->bulanan();
        }
    }



    /*
    *   MASTER HARI LIBUR
    */
    public function hari_libur()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->PresensiModel->tanggal_libur();
            $q = $this->PresensiModel->tanggal_libur(true);
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
                'title' => 'Hari Libur Nasional',
                'page' => 'Presensi/hari_libur',
                'list_tahun' => $this->PresensiModel->list_tahun_in_hari_libur(),
            ];
            return view('tBase', $arr);
        }
    }

    public function hari_libur_form()
    {
        $this->array_response['code'] = 200;
        $id = $this->request->getGet('id');
        $trash = $this->request->getGet('trash');
        $data = $this->PresensiModel->result_hari_libur_in_tanggal(explode(',', $id));
        if($_POST)
        {
            $this->validation->setRules([
                'tanggal' => [
                    'label' => 'tanggal', 
                    'rules' => 'required|min_length[10]|max_length[250]',
                ],
                'keterangan' => [
                    'label' => 'Keterangan', 
                    'rules' => 'required|max_length[250]',
                ],
            ]);
            if($this->validation->run($_POST))
            {
                $tanggal_old = explode(',', $this->request->getPost('tanggal_old'));
                $tanggal = explode(',', $this->request->getPost('tanggal'));
                $keterangan = $this->request->getPost('keterangan');
                if(!empty($data))
                {
                    $this->PresensiModel->hapus_hari_libur_in_tanggal($tanggal_old);
                    foreach ($tanggal as $key => $value) {
                        $this->PresensiModel->tanggal_libur_save(['tanggal'=>$value, 'keterangan'=>$keterangan]);
                    }
                }else{
                    foreach ($tanggal as $key => $value) {
                        $this->PresensiModel->tanggal_libur_save(['tanggal'=>$value, 'keterangan'=>$keterangan]);
                    }
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
            session()->setFlashdata('message', $this->array_response['message']);
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Method', 'POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Key')
                ->setStatusCode($this->array_response['code'])
                ->setJSON($this->array_response);
        }else{
            if($trash==1)
            {
                $hs = $this->PresensiModel->hapus_hari_libur_in_tanggal(explode(',', $id));
                if($hs>0){
                    $this->array_response['status'] = true;
                    $this->array_response['message'] = 'Berhasil dihapus';
                }else{
                    $this->array_response['message'] = 'Gagal dihapus';
                }
                $this->session->setFlashdata('message', $this->array_response['message']);
                return redirect()->to('presensi/hari_libur');
            }else{
                $arr = [
                    'title' => 'Form Hari/tanggal libur',
                    'page' => 'Presensi/hari_libur_form',
                    'data' => $data,
                ];
                return view('tBase', $arr);
            }
        }
    }



    /*
    *   MASTER JAMKERJA
    */
    public function jam_kerja()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->PresensiModel->tanggal_libur();
            $q = $this->PresensiModel->tanggal_libur(true);
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
                'title' => 'Jam Kerja',
                'page' => 'Presensi/jam_kerja',
                // 'list_tahun' => $this->PresensiModel->presensi_list_tahun(),
            ];
            return view('tBase', $arr);
        }
    }


    /*
    *   MASTER LOKASI/AREA
    */
    public function lokasi()
    {
        $this->array_response['code'] = 200;
        $this->array_response['status'] = true;
        $this->array_response['message'] = 'Empty';
        if($_POST)
        {
            $data = [];
            $draw = (int) (($this->request->getPost('draw')) ? : 1);
            $jml = $this->PresensiModel->area();
            $q = $this->PresensiModel->area(true);
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
                'title' => 'Lokasi atau Area',
                'page' => 'Presensi/lokasi',
                // 'list_tahun' => $this->PresensiModel->presensi_list_tahun(),
            ];
            return view('tBase', $arr);
        }
    }


    /*
    *   API CHECK POSISI SAAT INI
    */
    public function check_location_in_radius_absen()
    {
        $this->array_response['code'] = 200;
        $clat = $this->request->getGet('lat');
        $clong = $this->request->getGet('long');
        $data_area = $this->PresensiModel->list_ms_area();
        if(!empty($data_area))
        {
            $info = ['status'=>false,'message'=>'Anda berada di luar area kantor.'];
            $array_check = [];
            foreach ($data_area as $k) {
                $xll = explode(',', $k->latlong);
                $centerLat = $xll[0];
                $centerLong = $xll[1];
                $radius = $k->range;
                $rs = cekAreaRadiusMeter($clat, $clong, $centerLat, $centerLong, $radius);
                if($rs==true){
                    $info['status'] = $rs;
                    $info['message'] = 'Anda berada dalam area '.$k->name;
                }
                array_push($array_check, $rs);
            }
            $this->array_response['status'] = TRUE;
            $this->array_response['message'] = $info['message'];
            $this->array_response['data'] = $info;
            $this->array_response['check'] = $array_check;
        }else{
            $this->array_response['message'] = 'Tidak ditemukan area kantor.';
        }
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Method', 'GET, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Bearer')
            ->setStatusCode($this->array_response['code'])
            ->setJSON($this->array_response);
    }

}