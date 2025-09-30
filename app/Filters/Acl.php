<?php
namespace App\Filters; 
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\AuthModel;
 
class Acl implements FilterInterface
{
    public function __construct()
    {
        // $this->db = \Config\Database::connect();
        $this->uri = new \CodeIgniter\HTTP\URI(current_url(true));
        $this->AuthModel = new AuthModel();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        switch (session()->get('login')) {
            case true:
                $return = FALSE;
                $app_id = 1;
                $userBAC = session()->get('user_base_access');
                if(!empty($userBAC))
                {
                    $rt = 0;
                    $segments = $this->uri->getSegments();
                    // $pathURI = str_replace(['index.php/'], '', implode('/', $segments));
                    $pathURIX = explode('index.php/', implode('/', $segments));
                    $pathURI = (isset($pathURIX[1]))?strtolower($pathURIX[1]):strtolower(implode('/', $segments));
                    foreach ($userBAC as $key => $value) {
                        $pathCF = strtolower($value);
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
                        $return = TRUE;
                    }
                }
                if( $return == FALSE ){
                    session()->setFlashdata('message', 'Maaf, tidak mendapat akses ke alamat:: '.implode('/', $this->uri->getSegments()) );
                    return redirect()->to('/');
                }else{
                    helper('toolshelp');
                    $this->AuthModel->save_log([
                        'reff' => 'validation',
                        'message' => 'diizinkan',
                    ]);
                    if(session()->get('activation_key')==1 && !check_link_module(['data/pengguna/form'])/*!array_keys(['data/pengguna/form'], $pathURI)*/)
                    {
                        session()->setFlashdata('message', 'Silahkan ubah kata sandi terlebih dahulu.');
                        return redirect()->to('data/pengguna/form?id='.string_to(session()->get('id'), 'encode'));
                    }
                }
                break;
            default:
                session()->setFlashdata('message', 'Maaf, Anda belum login');
                return redirect()->to('auth/login'); 
                break;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $session = $_SESSION;
        if(isset($session['options']) && $session['options']->toptp==1)
        {
            if(isset($session['2fa_status']) && $session['2fa_status']==1){}else{
                return redirect()->to('auth/otp');
            }
        }

    }
}