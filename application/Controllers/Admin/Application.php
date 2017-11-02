<?php namespace App\Controllers\Admin;

use App\Libraries\CSRFToken;
use App\Libraries\Twig\Twig;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\Response;
use App\Helpers;
use Config\App;
use Config\Services;
use App\Models\Admin\ConfigModel;

/**
 * Our base controller.
 */
class Application extends Controller
{

    /**
     * @var array
     */
    protected $helpers = ['cookie', 'text'];
    /**
     * @var array
     */
    protected $data = [];
    /**
     * @var array
     */
    protected $css = [];
    /**
     * @var array
     */
    protected $js = [];
    /**
     * @var \CodeIgniter\Session\Session
     */
    protected $session;
    /**
     * @var
     */
    protected $stitle;
    /**
     * @var
     */
    protected $tpage;
    /**
     * @var \App\Libraries\Twig\Twig
     */
    protected $twig;

    /**
     * @var \App\Controllers\Admin\ConfigModel
     */
    protected $config_model;

    /**
     * Application constructor.
     *
     * @param array ...$params
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        //Declare class
        $this->config        = new App();
        $this->session        = Services::session($this->config);
        $this->request        = Services::request();
        $this->response    = new Response($this->config);
        $this->twig            = new Twig('admin');
        $this->csrf         = new CSRFToken();
        $this->config_model = new ConfigModel();

        //Set header
        $this->response->setStatusCode(Response::HTTP_OK);
        $this->response->setHeader('Content-type', 'text/html');
        $this->response->noCache();
        // Prevent some security threats, per Kevin
        // Turn on IE8-IE9 XSS prevention tools
        $this->response->setHeader('X-XSS-Protection', '1; mode=block');
        // Don't allow any pages to be framed - Defends against CSRF
        $this->response->setHeader('X-Frame-Options', 'DENY');
        // prevent mime based attacks
        $this->response->setHeader('X-Content-Type-Options', 'nosniff');
        // Prevent google
        $this->response->setHeader('X-robots-tag', 'noindex');

        if (!$this->isConnected() &&  uri_string() != 'admin/auth/login' && uri_string() != 'admin/auth/login_ajax') {
            header('Location: ' . base_url('admin/auth/login'));
            exit();
        }
    }

    /**
     * Set js & css file
     *
     * @return string $this
     */
    private function base_template()
    {

        //Set css file
        $this->set_css(base_url('assets/css/font-awesome.css'));
        $this->set_css('//fonts.googleapis.com/css?family=RobotoDraft:300,400,500');
        $this->set_css(base_url('assets/css/admin/bootstrap.css'));

        //Set JS
        $this->set_js('//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js');
        $this->set_js(base_url('assets/js/cookie.js'));
        $this->set_js('//cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js');
        $this->set_js('//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js');
        $this->set_js(base_url('assets/js/jquery.toast.js'));

        //Set by page
        if ($this->request->uri->getSegment(2) == 'auth' && $this->request->uri->getSegment(3) == 'login') {
            $this->set_css(base_url('assets/css/admin/auth.css'));
            $this->set_js(base_url('assets/js/admin/auth.js'));
        } else {
            $this->set_css(base_url('assets/css/admin/style.css'));
            $this->set_js(base_url('assets/js/admin/app.js'));

            if ($this->request->uri->getSegment(2) == 'article') {
                $this->set_css(base_url('assets/css/admin/article.css'));
                $this->set_js(base_url('assets/js/admin/article.js'));
            } elseif ($this->request->uri->getSegment(2) == 'comments') {
                $this->set_js(base_url('assets/js/admin/comments.js'));
            } elseif ($this->request->uri->getSegment(2) == 'config') {
                $this->set_js(base_url('assets/js/admin/config.js'));
            } elseif ($this->request->uri->getSegment(2) == 'cat') {
                $this->set_js(base_url('assets/js/admin/cat.js'));
            } elseif ($this->request->uri->getSegment(2) == 'contact') {
                $this->set_js(base_url('assets/js/admin/contact.js'));
            }
        }

        return $this;
    }
    /**
     * @param string $file link
     *
     * @return string $this
     */
    public function set_css(string $file)
    {
        $this->css[]['file'] = $file;
        return $this;
    }

    /**
     * @param string $file link
     *
     * @return string $this
     */
    public function set_js(string $file)
    {
        $this->js[]['file'] = $file;
        return $this;
    }

    /**
     * @param string $title set title page
     * @param string $ype set if type is website or article
     *
     * @return string $h return all meta
     */
    public function meta(string $title)
    {
        $titre = $title . ' | Administration | ' . $this->config_model->GetConfig('site_title');

        $h = '<meta charset="UTF-8">';
        $h .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $h .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">';
        $h .= '<title>' . $titre . '</title>';
        $h .= '<meta name="_token" content="'.$this->csrf->getToken().'">';
        //$h .= '<meta name="Robots" content="noindex, nofollow">';

        return $h;
    }

    /**
     * @param string $page set page
     * @param string $title set title page
     *
     * @return string $this
     */
    public function render(string $page, string $title = null)
    {
        $this->base_template();

        $this->data['css'] = $this->css;
        $this->data['js'] = $this->js;

        if ($title != null) {
            $this->data['meta'] = $this->meta($title);
        } else {
            $this->data['meta'] = $this->meta($this->stitle);
        }

        $this->data['page_title'] = $this->stitle;
        $this->data['page_stitle'] = $this->tpage;

        $this->data['breadcrumb'] = $this->breadcrumb();

        $this->response->setBody($this->twig->Rendered($page, $this->data));
        $this->response->send();

        return $this;
    }

    protected function breadcrumb()
    {
        $bread = '<div class="bread">';
        if ($this->request->uri->getTotalSegments() >= 2) {
            $bread .= "<a href='".base_url('admin')."'>Accueil</a>";
            if ($this->request->uri->getTotalSegments() >= 3) {
                $bread .= '<a href="'.base_url($this->request->uri->getSegment(2)).'">'.$this->stitle.'</a>';
                $bread .= '<span class="active">' . $this->tpage . '</span>';
            } else {
                $bread .= '<span class="active">' . $this->stitle . '</span>';
            }
        } else {
            $bread .= 'Accueil';
        }
        $bread .= '</div>';
        return $bread;
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        if (!$this->session->has('logged_in')) {
            if (get_cookie('remember_me', true) != null) {
                $this->session->set('Account_ip', $this->request->getIPAddress());
                $this->session->set('Account_id', get_cookie('remember_me', true));
                $this->session->set('logged_in', true);
                delete_cookie('remember_me');
                set_cookie(['name' => 'remember_me', 'value' =>  get_cookie('remember_me', true), 'expire' => '32140800'], true);
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit();
            } else {
                return false;
            }
        } else {
            if (get_cookie('remember_me', true) != null) {
                $this->session->set('Account_ip', $this->request->getIPAddress());
                $this->session->set('Account_id', get_cookie('remember_me', true));
                $this->session->set('logged_in', true);
                delete_cookie('remember_me');
                set_cookie(['name' => 'remember_me', 'value' =>  get_cookie('remember_me', true), 'expire' => '32140800'], true);
            }
            return true;
        }
    }
}
