<?php

use PhpOffice\PhpWord\PhpWord;
use PHPWord as GlobalPHPWord;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once get_path(APPPATH . "/third_party/PHPWord.php");

class Word extends GlobalPHPWord { 
    public function __construct() { 
        parent::__construct(); 
    } 
}