<?php
defined('BASEPATH') or exit('No direct script access allowed');

use CodeIgniter\CLI\CLI;

CLI::error('ERROR: '.$heading);
CLI::write($message);
CLI::newLine();
