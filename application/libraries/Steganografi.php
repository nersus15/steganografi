<?php

use Xrsa\XRsa;

/** @var CI_Controller */

class Steganografi {

    private $ci;
    public function __construct() {
        $this->ci =& get_instance();
    }

    function embedVideo($files, $pub_key = null){
        try {
            $this->ci->load->helper('file_upload_helper');
            $vpath = get_path(DOCS_PATH . 'tmp/video/');
            $fpath = get_path(DOCS_PATH . 'tmp/docs/');

            $eRName= $files["file"]["name"];
            $tmp = explode(".", $eRName);
            $eExt = end($tmp);

            $eName = uploadFile($files, 'file');
            $content = $this->readfile($fpath . $eName, $eExt);

            $rVName = $files["video"]["name"];
            $tmp = explode(".", $rVName);
            $rVExt = end($tmp);

            $salt = random(3);
            $sVname = str_replace('.' . $rVExt, '', $rVName);
            $fname = $salt . ' ' . $sVname . ' embeded.' . $rVExt;

            // Get Content Of Video
            if ($files['video']['error'] == UPLOAD_ERR_OK && is_uploaded_file($files['video']['tmp_name'])) {
                $content2 = file_get_contents($files['video']['tmp_name']);
            } else {
                response("Terjadi kesalahan, periksa kembali video yang di upload");
            }

            if(!empty($pub_key)){
                require_once get_path(APPPATH . '/third_party/liamylian/Xrsa.php');

                // Enkrip File Content
                $xrsa = new XRsa($pub_key);
                $content = $xrsa->publicEncrypt($content);
            }


            file_put_contents(get_path(DOCS_PATH . 'tmp/video/' . $fname), $content2 . '-separator-' . $eRName . '-separator-' . $content);
            
            response(['url' => base_url('public/docs/tmp/video/' . $fname)]);
        } catch (\Throwable $th) {
            response("Terjadi kesalahan", 500);
        }
    }

    function ekstractFile($files, $priv_key = null){
        try {
            if ($files['video_enkripted']['error'] == UPLOAD_ERR_OK && is_uploaded_file($files['video_enkripted']['tmp_name'])) {
                $videoContent = file_get_contents($files['video_enkripted']['tmp_name']);
            } else {
                response("Terjadi kesalahan, periksa kembali video yang di upload");
            }
            $tmp = explode('-separator-', $videoContent);
            if(count($tmp) != 3)
                response("File vide yang ingin di ekstrak tidak valid", 403);

            $fpath = get_path(DOCS_PATH . 'tmp/docs/');
            $fName = $tmp[1];
            $fData = $tmp[2];
            $fExt = getExt($fName);
            $fname = random(8);

            if(in_array($fExt['extension'],['doc', 'docx'])){
                $finalExt = '.doc';
            }elseif($fExt['extension'] == 'pdf'){
                $finalExt = '.pdf';
            }
            if(!empty($priv_key)){
                require_once get_path(APPPATH . '/third_party/liamylian/Xrsa.php');

                // Enkrip File Content
                $fData = XRsa::privateDecryptCustom($fData, $priv_key);
            }

            file_put_contents($fpath . $fname . '.txt', $fExt['filename'] . ' extracted'. $finalExt .'-separator-' . $fData);

        } catch (\Throwable $th) {
            response(['err' => $th->getMessage()], 500);
        }

        response(['name' => $fname . '.txt']);
    }

    function readfile($fpath, $ext){
        require_once APPPATH . 'libraries/FileReader.php';

        if($ext == 'pdf'){
            $this->ci->load->helper('file_reader_helper');
            $c = readPdf($fpath)['text'];     
        }else{
            $fileReader = new FileReader($fpath);
            $c = $fileReader->convertToText();
        }

        return $c;
    }
}