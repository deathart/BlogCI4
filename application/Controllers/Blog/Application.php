<?php namespace App\Controllers\Blog;

use App\Models\Blog\ConfigModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\Response;
use Config\App;
use Config\Services;
use App\Libraries\Twig\Twig;
use App\Libraries\CSRFToken;

/**
 * Our base controller.
 */
class Application extends Controller
{

    /**
     * @var \CodeIgniter\Session\Session
     */
    protected $session;
    /**
     * @var \CodeIgniter\HTTP\IncomingRequest
     */
    protected $request;
    /**
     * @var \CodeIgniter\HTTP\Response
     */
    protected $response;
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
     * @var \App\Libraries\CSRFToken
     */
    protected $csrf;
    /**
     * @var \App\Models\Blog\ConfigModel
     */
    protected $config_model;

    /**
     * Application constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        //Declare class
        $config       = new App();
        $this->session      = Services::session($config);
        $this->request      = Services::request();
        $this->response     = new Response($config);
        $this->twig         = new Twig('blog');
        $this->csrf         = new CSRFToken();
        $this->config_model = new ConfigModel();

        //Set header
        $this->response->setStatusCode(200);
        $this->response->setHeader('Content-type', 'text/html');
        $this->response->noCache();
        // Prevent some security threats, per Kevin
        // Turn on IE8-IE9 XSS prevention tools
        $this->response->setHeader('X-XSS-Protection', '1; mode=block');
        // Don't allow any pages to be framed - Defends against CSRF
        $this->response->setHeader('X-Frame-Options', 'DENY');
        // prevent mime based attacks
        $this->response->setHeader('X-Content-Type-Options', 'nosniff');
    }

    /**
     * Set js & css file
     *
     * @return string $this
     */
    private function base_template()
    {

        //Set css file
        $this->set_css(base_url('assets/css/blog/style.css'));
        $this->set_css(base_url('assets/css/font-awesome.css'));
        $this->set_css('//fonts.googleapis.com/css?family=Roboto:100,300,400|Roboto+Condensed:100,300');

        //Set JS
        $this->set_js('//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js');
        $this->set_js(base_url('assets/js/cookie.js'));
        $this->set_js(base_url('assets/js/blog/app.js'));

        //Set by page
        if ($this->request->uri->getSegment(1) == 'article') {
            $this->set_js(base_url('assets/js/blog/article.js'));
            $this->set_css(base_url('assets/css/prism.css'));
            $this->set_js(base_url('assets/js/prism.js'));
        } elseif ($this->request->uri->getSegment(1) == 'contact') {
            $this->set_js(base_url('assets/js/blog/contact.js'));
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
     * @param int $type
     * @param string $keyword set keyword by article
     *
     * @return string $h return all meta
     * @internal param int $ype set if type is website or article
     */
    public function meta(string $title, int $type, string $keyword = null): string
    {
        if ($type == 1) {
            $titre = $this->config_model->GetConfig('site_title') . ' | ' . $this->config_model->GetConfig('site_description');
            $oritype = 'Website';
        } elseif ($type == 2) {
            $titre = $title . ' | ' . $this->config_model->GetConfig('site_title');
            $oritype = 'Article';
        }

        $h = '<meta charset="UTF-8">';
        $h .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $h .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">';
        $h .= '<title>' . $titre . '</title>';
        $h .= '<meta name="Description" content="' . $this->config_model->GetConfig('site_description') . '">';
        $h .= '<meta name="apple-mobile-web-app-capable" content="yes">';
        $h .= '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">';
        $h .= '<meta name="Robots" content="noindex, follow">';
        if ($keyword != null) {
            $h .= '<meta name="news_keywords" content="' . $keyword . '" />';
            $h .= '<meta name="Keywords" content="' . $keyword . '">';
        } else {
            $h .='<meta name="keywords" content="' . $this->config_model->GetConfig('site_keyword') . '" />';
        }
        $h .= '<meta name="original-source" content="' . current_url() . '" />';
        $h .= '<link rel="canonical" href="' . current_url() . '" />';
        $h .= '<meta property="og:title" content="' . $titre . '"/>';
        $h .= '<meta property="og:url" content="' . current_url() . '"/>';
        $h .= '<meta property="og:description" content="' . $this->config_model->GetConfig('site_description') . '"/>';
        $h .= '<meta property="og:type" content="' . $oritype . '" />';
        $h .= '<meta property="og:image" content="' . base_url('assets/images/logo.png') . '" />';
        $h .= '<meta property="og:image:secure_url" content="' . base_url('assets/images/logo.png') . '" />';
        $h .= '<meta name="twitter:card" content="summary_large_image" />';
        $h .= '<meta name="twitter:description" content="' . $this->config_model->GetConfig('site_description') . '" />';
        $h .= '<meta name="twitter:title" content="' . $titre . '" />';
        $h .= '<meta name="twitter:site" content="' . base_url() . '" />';
        $h .= '<meta name="twitter:image" content="' . base_url('assets/images/logo.png') . '" />';
        $h .= '<meta name="_token" content="'.$this->csrf->getToken().'">';

        return $h;
    }

    /**
     * @param string $page set page
     * @param string $title set title page
     * @param string|null $keyword
     *
     * @return string $this
     */
    public function render(string $page, string $title = null, string $keyword = null)
    {
        $this->base_template();

        $this->data['css'] = $this->css;
        $this->data['js'] = $this->js;

        if ($title != null) {
            $this->data['meta'] = $this->meta($title, 2, $keyword);
        } else {
            $this->data['meta'] = $this->meta($this->stitle, 1, $keyword);
        }

        $this->data['page_title'] = $this->stitle;
        $this->data['page_stitle'] = $this->tpage;

        $this->response->setBody($this->twig->Rendered($page, $this->data));
        $this->response->send();

        return $this;
    }
}
