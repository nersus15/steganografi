<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * CI_Loader
	 *
	 * @var	CI_Loader
	 */
	public $load;
	/**
	 * CI_Loader
	 *
	 * @var	CI_DB_query_builder
	 */
	public $db;
	/**
	 * CI_Loader
	 *
	 * @var	CI_Session
	 */
	public $session;
	/**
	 * CI_Loader
	 *
	 * @var	Datatables
	 */
	public $datatables;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */

	private $views = [];
	private $params = [
		'extra_js' => [],
		'extra_css' => [],
		'removedFromGroup' => []
	];
	public function __construct()
	{
		self::$instance =& $this;
		if(IS_CORS_ACTIVE)
        	$_POST = json_decode(file_get_contents('php://input'), true); // ini untuk 

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		// $this->load->library('renderer');
		log_message('info', 'Controller Class Initialized');
	}
	function setObject($nama, $value){
		$this->okasu= $value;
		self::$instance->okasu = $value;  
	}
	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}
	function setView($views){
		$this->views[] = $views;
	}
	function setParams($params, $key, $arrayOfArray = false){
		if($arrayOfArray)
			$this->params[$key][] = $params;
		else
			$this->params[$key] = $params;
	}
	public function addViews($views, $params = null){
		if(is_array($views)){
			foreach($views as $v)
				$this->views[] = $v;
		}
		else
			$this->views[] = $views;
		if(!is_array($params))
			$this->params['params'] = $params;
		else{
			foreach($params as $k => $v){
				$this->params[$k] = $v;
			}
		}
			
		
	}
	/**
	 * fungsi ini digunakan untuk menambahkan file JS ke halaman yang akan diload
	 * @param String $posisi Opsi untuk nilai $posisi ['head'|'body:end'] default 'body:end'
	 * @param String $tipe Opsi untuk nilai $tipe ['file'|'cdn'] default 'file'
	 * @return void
	 */
	function add_javascript($js, $posisi = 'body:end', $tipe = 'file'){
		if(!in_array($tipe, ['file', 'cdn']))
			throw new Exception("Tipe Harus CDN atau File", 1);
		if(is_array($js)){
			foreach($js as $c){
				$this->params['extra_js'][] = $tipe == 'cdn' ? $c : ($c . '.js');
			}
		}else{
			$this->params['extra_js'][] = array(
				'src' => $tipe =='cdn' ? $js : ($js . '.js'),
				'pos' => $posisi,
				'type' => $tipe
			);
		}
	}
	function error_page($file, $params, $type = 'html'){
		$this->load->view('errors/' . $type . '/' . $file, $params);
	}
	function add_cachedJavascript($js, $type = 'file', $pos = "body:end", $data = array())
    {
        /** @var CI_Controller */
		$CI =& get_instance();
		$CI->load->library('Minifier');
		try {
            $params = array(
                'script' => $type == 'file' ? $CI->minifier->minify($this->load->js($js, $data, true)) : $js,
                'type' => 'inline',
                'pos' => 'body:end'
            );
            $this->params['extra_js'][] = $params;
			
        } catch (\Throwable $th) {
            print_r($th);
        }
		
    }
    function add_cachedStylesheet($css, $type = 'file', $pos = 'head', $data = array())
    {
        if ($type == 'file') {
            ob_start();
            if (!empty($data))
                extract($data);
            try {
                include_once get_path(ASSETS_PATH . 'css/' . $css . '.css');
            } catch (\Throwable $th) {
                print_r($th);
            }
        }

        $params = array(
            'style' => $type == 'file' ? ob_get_contents() : $css,
            'type' => 'inline',
            'pos' => $pos
        );
		$this->params['extra_css'][] = $params;
        if ($type == 'file')
            ob_end_clean();
    }
	/**
	 * fungsi ini digunakan untuk menambahkan file CSS ke halaman yang akan diload
	 * @param String $posisi Opsi untuk nilai $posisi ['head'|'body:end'] default 'head'
	 * @param String $tipe Opsi untuk nilai $tipe ['file'|'cdn'] default 'file'
	 * @return void
	 */
	function add_stylesheet($css, $posisi = 'head', $tipe = 'file'){
		if(!in_array($tipe, ['file', 'cdn']))
			throw new Exception("Tipe Harus CDN atau File", 1);
			
		if(is_array($css)){
			foreach($css as $c){
				$this->params['extra_css'][] = $tipe == 'cdn' ? $c : ($c . '.css');
			}
		}else{
			$this->params['extra_css'][] = array(
				'src' => $tipe == 'cdn' ? $css : ($css . '.css'),
				'pos' => $posisi,
				'type' => $tipe
			);
		}
	}

	function removeFromResourceGroup($group, $asset){
		if(isset($this->params['removedFromGroup'][$group])){
			$this->params['removedFromGroup'][$group][] = $asset;
		}else{
			$this->params['removedFromGroup'][$group] = [$asset];
		}
	}

	function getContentView ($path, $data = [], $return = false){
		$html = $this->load->view(get_path( $path . '.php'), $data, true);
		if(!empty($data)){
			foreach($data as $k => $d) {
				unset($$k);
			}
		}
		if($return)
			return $html;
		else
			echo $html;
		
	}
	public function render(){
		foreach($this->views as $view){
			$this->load->view($view, $this->params);
		}
		$this->views = [];
		$this->params = [];
	}


}
