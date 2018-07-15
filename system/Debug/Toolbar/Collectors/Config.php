<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace CodeIgniter\Debug\Toolbar\Collectors;

use CodeIgniter\CodeIgniter;
use Config\App;
use Config\Services;

class Config
{
	public static function display()
	{
		$config = config(App::class);

		return [
			'ciVersion'   => CodeIgniter::CI_VERSION,
			'phpVersion'  => PHP_VERSION,
			'phpSAPI'     => \PHP_SAPI,
			'environment' => ENVIRONMENT,
			'baseURL'     => $config->baseURL,
			'timezone'    => app_timezone(),
			'locale'      => Services::request()->getLocale(),
			'cspEnabled'  => $config->CSPEnabled,
			'salt'        => $config->salt,
		];
	}
}
