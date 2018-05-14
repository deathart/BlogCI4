<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

defined('BASEPATH') or exit('No direct script access allowed');

use CodeIgniter\CLI\CLI;

CLI::error('ERROR: '.$heading);
CLI::write($message);
CLI::newLine();
