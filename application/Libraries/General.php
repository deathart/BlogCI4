<?php namespace App\Libraries;

use App\Models\Admin\AuthModel;
use App\Models\Blog\ArticleModel;
use App\Models\Blog\CatModel;
use App\Models\Blog\CommentsModel;
use App\Models\Blog\PagesModel;
use Cocur\Slugify\Slugify;
use Config\App;
use Config\Services;

/**
 * Class General
 * @package App\Libraries
 */
class General
{

    /**
     * @var \CodeIgniter\Session\Session
     */
    private $session;

    /**
     * @var \App\Models\Blog\CatModel
     */
    private $cat_model;

    /**
     * @var \App\Models\Blog\ArticleModel
     */
    private $article_model;

    /**
     * @var \App\Models\Blog\CommentsModel
     */
    private $comments_model;

    /**
     * @var \App\Models\Blog\PagesModel
     */
    private $pages_model;

    /**
     * @var \App\Models\Admin\AuthModel
     */
    private $Auth_model;

    /**
     * General constructor.
     */
    public function __construct()
    {
        $this->session      = Services::session(new App());
        $this->cat_model = new CatModel();
        $this->article_model = new ArticleModel();
        $this->comments_model = new CommentsModel();
        $this->pages_model = new PagesModel();
        $this->Auth_model   = new AuthModel();
    }

    /**
     * @param string $cat
     * @param string $separator
     * @return string
     */
    public function TradCat(string $cat, string $separator = null): string
    {
        $arr = '';
        $piece = explode(';', $cat);
        $lastKey = \count($piece) - 1;
        foreach ($piece as $k =>$data) {
            $tt = $this->cat_model->GetCatNameAndLink($data);
            $arr .= "<a href='" . base_url('cat/' . $tt->slug) . "'>" . $tt->title . '</a>';
            if ($separator && $k != $lastKey) {
                $arr .= '&nbsp;' . $separator . '&nbsp;';
            }
        }

        return $arr;
    }

    /**
     * @param string $tags
     * @param mixed $list
     * @return array
     */
    public function TradTags(string $tags, $list = false): array
    {
        $tags = preg_replace('/\s+/', '', $tags);
        $arr = [];
        $piece = explode(',', $tags);
        foreach ($piece as $data) {
            $arr[] = ['title' => $data, 'slug' => $this->slugify($data)];
        }

        return $arr;
    }

    /**
     * @return array|mixed
     */
    public function GetCat()
    {
        return $this->cat_model->GetCat();
    }

    /**
     * @return array|mixed
     */
    public function GetPages()
    {
        return $this->pages_model->GetPages();
    }

    /**
     * @param int $id post ID
     * @return int
     */
    public function CountCom(int $id): int
    {
        return $this->comments_model->CountComs($id);
    }

    /**
     * @param int $cat car id
     * @return int
     */
    public function CountArticle(int $cat): int
    {
        return $this->article_model->nb_articleByCat($cat);
    }

    /**
     * @param int $post post ID
     * @return int
     */
    public function CountView(int $post): int
    {
        return $this->article_model->nb_PostView($post);
    }

    /**
     * @return string
     */
    public function getusername(): string
    {
        return $this->Auth_model->GetUsername($this->session->get('Account_id'));
    }

    /**
     * @return string
     */
    public function getavatar():string
    {
        return $this->Auth_model->GetAvatar($this->session->get('Account_id'));
    }

    /**
     * @param $data
     * @return string
     */
    public function slugify($data): string
    {
        return (new Slugify())->slugify($data);
    }
}
