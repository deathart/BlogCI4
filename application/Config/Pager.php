<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Class Pager
 *
 * @package Config
 */
class Pager extends BaseConfig
{
    /*
    |--------------------------------------------------------------------------
    | Templates
    |--------------------------------------------------------------------------
    |
    | Pagination links are rendered out using views to configure their
    | appearance. This array contains aliases and the view names to
    | use when rendering the links.
    |
    | Within each view, the Pager object will be available as $pager,
    | and the desired group as $pagerGroup;
    |
    */
    public $templates = [
        'default_full'   => 'CodeIgniter\Pager\Views\default_full',
        'default_simple' => 'CodeIgniter\Pager\Views\default_simple',
        'categories'     => 'App\Views\Pagers\categories'
    ];

    /*
    |--------------------------------------------------------------------------
    | Items Per Page
    |--------------------------------------------------------------------------
    |
    | The default number of results shown in a single page.
    |
    */
    public $perPage = 20;
}
