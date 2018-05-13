<?php namespace App\Controllers\Blog;

use App\Libraries\CSRFToken;
use App\Libraries\Twig\Twig;
use App\Models\Blog\ConfigModel;
use CodeIgniter\Controller;
use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\HTTP\Response;
use Config\App;
use Config\Services;

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
     * /**
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
     * @var string
     */
    protected $stitle;
    /**
     * @var string
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
     * @throws \InvalidArgumentException
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        //Declare class
        $config        = new App();
        $this->session = Services::session($config);
        $this->request = Services::request();

        if ($this->request->uri->getSegment(1) != 'admin') {
            $this->session->start();
        }

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
        $this->set_css($this->asset_path('css/blog/style.css'));
        $this->set_css($this->asset_path('css/vendor/fontawesome.css'));
        $this->set_css('//fonts.googleapis.com/css?family=Roboto:100,300,400|Roboto+Condensed:100,300');
        $this->set_css($this->asset_path('css/vendor/bootstrap.css'));

        //Set JS
        $this->set_js($this->asset_path('js/vendor/jquery.min.js'));
        $this->set_js($this->asset_path('js/vendor/bootstrap.bundle.js'));
        $this->set_js($this->asset_path('js/vendor/cookie.min.js'));
        $this->set_js($this->asset_path('js/blog/app.js'));

        //Set by page
        if ($this->request->uri->getSegment(1) == 'article') {
            $this->set_js($this->asset_path('js/blog/article.js'));
            $this->set_css($this->asset_path('css/vendor/prism.css'));
            $this->set_js($this->asset_path('js/vendor/prism.min.js'));
        } elseif ($this->request->uri->getSegment(1) == 'contact') {
            $this->set_js($this->asset_path('js/blog/contact.js'));
        } elseif ($this->request->uri->getSegment(1) == 'page') {
            $this->set_css($this->asset_path('css/vendor/prism.css'));
            $this->set_js($this->asset_path('js/vendor/prism.min.js'));
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
        }

        if ($type == 2) {
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
        $h .= '<meta property="og:image" content="' . $this->asset_path('/images/logo.png') . '" />';
        $h .= '<meta property="og:image:secure_url" content="' . $this->asset_path('/images/logo.png') . '" />';
        $h .= '<meta name="twitter:card" content="summary_large_image" />';
        $h .= '<meta name="twitter:description" content="' . $this->config_model->GetConfig('site_description') . '" />';
        $h .= '<meta name="twitter:title" content="' . $titre . '" />';
        $h .= '<meta name="twitter:site" content="' . base_url() . '" />';
        $h .= '<meta name="twitter:image" content="' . $this->asset_path('/images/logo.png') . '" />';
        $h .= '<meta name="_token" content="'.$this->csrf->getToken().'" />';

        return $h;
    }

    /**
     * @param string $page set page
     * @param string $title set title page
     * @param string|null $keyword
     *
     * @return string $this
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
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

    /**
     * @param $filename
     * @return mixed
     */
    private function asset_path($filename)
    {
        $manifest_path = FCPATH . 'assets/rev-manifest.json';

        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
        } else {
            throw new FileNotFoundException('File "' . FCPATH . 'assets\rev-manifest.json" is not found');
        }

        if (array_key_exists($filename, $manifest)) {
            return base_url('assets/' . $manifest[$filename]);
        }

        return base_url('assets/' . $filename);
    }
}
