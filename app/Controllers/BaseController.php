<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\Tools;
use App\Models\AuthModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['form', 'toolshelp'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $this->db = \Config\Database::connect();
        $this->uri = new \CodeIgniter\HTTP\URI(current_url(true));
        $this->session = \Config\Services::session();
        $this->security = \Config\Services::security();
        $this->validation = \Config\Services::validation();
        $this->tools = new Tools();
        $this->AuthModel = new AuthModel();

        // SESSION UBAC
        $this->AuthModel->load_user_base_access();
        // session()->set('URL__CURENT', implode('/', $this->uri->getSegments()));

        // remove session units jika tidak diperlukan
        if(!return_array_in_array($this->uri->getSegments(), ['kepegawaian']))
        {
            $this->session->remove('units');   
        }

        // RESPONSE JSON
        $this->array_response = [
            'code' => 503,
            'status' => FALSE,
            'message' => 'Error #1000',
            'csrf' => csrf_hash()
        ];
    }
}
