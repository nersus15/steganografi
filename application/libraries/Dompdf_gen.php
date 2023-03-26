<?php


 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Name:  DOMPDF
* 
* Author: Jd Fiscus
* 	 	  jdfiscus@gmail.com
*         @iamfiscus
*          
*
* Origin API Class: http://code.google.com/p/dompdf/
* 
* Location: http://github.com/iamfiscus/Codeigniter-DOMPDF/
*          
* Created:  06.22.2010 
* 
* Description:  This is a Codeigniter library which allows you to convert HTML to PDF with the DOMPDF library
* 
*/
require_once APPPATH.'third_party/dompdf-1.0.2/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;
class Dompdf_gen {
	/** @var CI_Controller */
	var $CI;

	private $dompdf;
	public function __construct() {
		$options = new Options();
        $options->set('isRemoteEnabled', TRUE);
        $this->dompdf = new Dompdf($options);
	}

	function display_pdf($html, $title = null){
		$this->dompdf->loadHtml($html);
		// (Opsional) Mengatur ukuran kertas dan orientasi kertas
		$this->dompdf->setPaper('A4', 'potrait');
		// Menjadikan HTML sebagai PDF
		$this->dompdf->render();
		// Output akan menghasilkan PDF ke Browser
		$this->dompdf->stream($title);
	}

    function toPdfFile($html){
        $this->dompdf->loadHtml($html);
		$this->dompdf->setPaper('A4', 'potrait');
		$this->dompdf->render();
		return $this->dompdf->output();
    }
	
}