<?php namespace App\Filters;
 
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\AuthModel;
 
class AclApi implements FilterInterface
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->uri = new \CodeIgniter\HTTP\URI(current_url(true));
        $this->AuthModel = new AuthModel();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        $response = service('response');
        $data = [
            'code' => 200,
            'status' => true,
            'message' => 'Error #1000',
            'csrf' => csrf_hash()
        ];
        $segments = $this->uri->getSegments();
        // $pathURI = str_replace(['index.php/'], '', implode('/', $segments));
        $pathURIX = explode('index.php/', implode('/', $segments));
        $pathURI = (isset($pathURIX[1]))?strtolower($pathURIX[1]):strtolower(implode('/', $segments));
        switch (true) {
            case !$this->AuthModel->validasi_key():
                $data['code'] = 406;
                $data['status'] = false;
                $data['message'] = 'Key tidak valid atau harus diakses melalui alamat tertentu.' .json_encode($_SESSION);
                $this->AuthModel->save_log([
                    'reff' => 'validation',
                    'message' => $data['message'],
                ]);
                return $response
                    ->setHeader('Access-Control-Allow-Origin', '*')
                    ->setHeader('Access-Control-Allow-Method', 'POST,GET,OPTIONS')
                    ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
                    ->setStatusCode($data['code'])
                    ->setJSON($data);
                break;
            default:
                if(!$this->AuthModel->validasi_token()){
                    // $data['query::']=$this->db->getLastQuery()->getQuery();
                    if(array_keys(['auth/login'], $pathURI)){
                        $data['message'] = 'Pass.';
                        $this->AuthModel->save_log([
                            'reff' => 'validation',
                            'message' => $data['message'],
                        ]);
                    }else{
                        $data['code'] = 401;
                        $data['status'] = false;
                        $data['message'] = 'Token tidak valid atau sudah kadaluarsa.';
                        $this->AuthModel->save_log([
                            'reff' => 'validation',
                            'message' => $data['message'],
                        ]);
                        return $response
                            ->setHeader('Access-Control-Allow-Origin', '*')
                            ->setHeader('Access-Control-Allow-Method', 'POST,GET,OPTIONS')
                            ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
                            ->setStatusCode($data['code'])
                            ->setJSON($data);
                    }
                }else{
                    /*CHECK PRIVILEGE USER*/
                    $return = false;
                    $user_base_access = session()->get('user_base_access');//$this->AuthModel->user_base_access();
                    if(!empty($user_base_access))
                    {
                        $rt = 0;
                        $ts = 0;
                        foreach ($user_base_access as $key=>$value) {
                            $pathCF = strtolower($value);
                            // session()->set('SESSION_'.($ts+=1), $pathCF);
                            switch (true) {
                                case $pathCF == $pathURI:
                                    $rt += 1;
                                    // echo '__________________________CF: '.$pathCF.', URI: '.$pathURI;
                                    break;
                                default:
                                    // code...
                                    break;
                            }
                        }
                        if( $rt > 0 ){
                            $return = true;
                        }
                    }
                    switch ($return) {
                        case false:
                            // condition exclud uri
                            if(array_keys(['auth/login', 'auth/logout', 'api/pegawai', 'api/member'], $pathURI)){
                                $this->AuthModel->save_log([
                                    'reff' => 'validation',
                                    'message' => 'akses diizinkan, exclude url `'.implode('/', $this->uri->getSegments()).'`',
                                ]);
                            }else{
                                $data['code'] = 401;
                                $data['status'] = false;
                                $data['message'] = 'tidak mendapat akses ke alamat:: '.implode('/', $this->uri->getSegments());
                                $this->AuthModel->save_log([
                                    'reff' => 'validation',
                                    'message' => $data['message'],
                                ]);
                                return $response
                                    ->setHeader('Access-Control-Allow-Origin', '*')
                                    ->setHeader('Access-Control-Allow-Method', 'POST,GET,OPTIONS')
                                    ->setHeader('Access-Control-Allow-Headers', 'Key, Token, User')
                                    ->setStatusCode($data['code'])
                                    ->setJSON($data);
                            }
                            break;
                        default:
                            // $this->AuthModel->save_log([
                            //     'reff' => 'validation',
                            //     'message' => 'diizinkan',
                            // ]);
                            break;
                    }
                }
                break;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}