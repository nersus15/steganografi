<?php

use PhpOffice\PhpWord\Element\Text;
use Xrsa\XRsa;

class Steganografi  extends CI_Model{
    private $fpath = null;
    private $vpath = null;
    public function __construct() {
        $this->load->helpers(['download', 'file_reader_helper', 'file_upload_helper']);
        require_once get_path(APPPATH . '/third_party/liamylian/Xrsa.php');
        $this->fpath = get_path(DOCS_PATH . 'tmp/docs/');
        $this->vpath = get_path(DOCS_PATH . 'tmp/video/');
    }
    
    function enkripsi($public_key, $data, $finalName){
        $xrsa = new Xrsa($public_key);
        $content = $xrsa->publicEncrypt($data);
        $this->writeFile($finalName, $content);
    }

    function deskripsi($private_key, $dataName, $finalName){
        $data = $this->readFile($dataName);
        $descrypted = XRsa::privateDecryptCustom($data, $private_key);
        try {
            write_file($this->fpath . $finalName, $descrypted);
        } catch (\Throwable $th) {
            response(['err' => $th->getMessage(), 'trace' => $th->getTrace()], 401);
        }
    }

    function embed($files, $enkrip = false, $pub_key = null){
        try {
            $vExt = getExt($files['video']['name']);
            $fExt = getExt($files['file']['name']);
            // response($vExt['extension']);
            $finalName = random(5) . ' '. $vExt['filename'] . ' embeded.' . $vExt['extension'];
            $contentName = $files['file']['name'];

            if(!in_array($vExt['extension'], ['mp4', 'mkv']))
                response("Format video tidak diizinkan, hanya mp4 dan mkv", 403);

            if(!in_array($fExt['extension'], ['pdf', 'docx', 'doc', 'txt']))
                response("Format file tidak diizinkan, hanya pdf, docx, doc dan txt", 403);

            if ($files['video']['error'] == UPLOAD_ERR_OK && is_uploaded_file($files['video']['tmp_name'])) {
                $content2 = file_get_contents($files['video']['tmp_name']);
            } else {
                response("Terjadi kesalahan, periksa kembali video yang di upload");
            }            

            if ($files['file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($files['file']['tmp_name'])) {
                $content1 = file_get_contents($files['file']['tmp_name']);
            } else {
                response("Terjadi kesalahan, periksa kembali file yang di upload");
            }

            if($enkrip){
                if(empty($pub_key))
                    response("Public key harus dikirimkan", 403);
                $xrsa = new Xrsa($pub_key);
                $content1 = $xrsa->publicEncrypt($content1);
            }
            write_file(get_path($this->vpath . $finalName), $content2 . '-separator-' . $contentName . '-separator-' . $content1);
            return $finalName;
        } catch (\Throwable $th) {
            response(['err' => $th->getMessage(), 'trace' => $th->getTrace()], 401);
        }
    }

    function ekstract($videoContent, $deskrip = false, $priv_key = null){
        $tmp = explode('-separator-', $videoContent);
        if(count($tmp) != 3)
            response("File vide yang ingin di ekstrak tidak valid", 403);

        $fileName = $tmp[1];
        $fileData = $tmp[2];
        $fileExt = getExt($fileName);
        $finalName = random(5) . ' '. $fileExt['filename'] . ' extracted.'. $fileExt['extension'];

        if($deskrip){
            if(empty($priv_key))
                response("Private key harus dikirimkan", 403);
            
            $fileData = XRsa::privateDecryptCustom($fileData, $priv_key);
        }

        write_file(get_path($this->fpath . $finalName), $fileData);

        return $finalName;
    }

    private function readFile($fname){
        $ext = strtolower(substr(strrchr($fname, '.'), 1));
        $content = null;
        $fpath = in_array($ext, ['mp4', 'mkv']) ? $this->vpath : $this->fpath;

        if($ext == 'pdf'){
            $c = readPdf(get_path($fpath . $fname));
            $content = $c['text'];
        }elseif($ext == 'docx'){
            $this->load->library('Phpword');
            $objReader = \PhpOffice\PhpWord\IOFactory::createReader();
            $phpWord = $objReader->load(get_path($fpath . $fname)); // instance of \PhpOffice\PhpWord\PhpWord
            $text = '';
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if ($element instanceof Text) {
                        $text .= $element->getText();
                   }
                   // and so on for other element types (see src/PhpWord/Element)
                }
            }
            $content = $text;
        }else{
            $content = file_get_contents(get_path($fpath . $fname));
        }
        
        return $content;
    }

    private function writeFile($fname, $data){
        $ext = strtolower(substr(strrchr($fname, '.'), 1));
        $fpath = in_array($ext, ['mp4', 'mkv']) ? $this->vpath : $this->fpath;
        try {
            if($ext == 'pdf'){
                require_once get_path(APPPATH . '/third_party/fpdf/fpdf.php');
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetFont('Arial',);
                $pdf->Write(20, $data);
                $pdf->Output('F', get_path($fpath . $fname));
            }elseif($ext == 'docx'){
                $this->load->library('Word');
                $PHPWord = $this->word;
                $section = $PHPWord->createSection();
                $section->addText($data);
                $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
                $objWriter->save(get_path($fpath . $fname));
            }else{
                write_file(get_path($fpath . $fname), $data);
            }
            return true;
        } catch (\Throwable $th) {
            response(['err' => $th->getMessage(), 'trace' => $th->getTrace()], 401);
        }
    }
}