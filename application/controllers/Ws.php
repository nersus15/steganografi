<?php defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Smalot\PdfParser\Parser;
use Xrsa\XRsa;

class Ws extends CI_Controller
{
    /** @var Authentication */
    var $authenticatioin;

    /** @var Word */
    var $word;

    /** @var Dompdf_gen */
    var $dompdf_gen;

    /** @var PDF2Text */
    var $pdf2text;

    /** @var Steganografi */
    var $steganografi;

    function login()
    {
        if (is_login())
            redirect('admin/login');
        $this->load->library('Authentication');

        if (!httpmethod())
            response("Metode akses ilegal");

        list($input) = $this->authentication->persiapan($_POST);
        $this->authentication->login($input);
    }
    function update_profile()
    {
        if (!httpmethod())
            response("Metode akses ilegal", 403);
        if (!is_login())
            response("Anda belum login", 403);

        $post = $_POST;
        $dataUser = fieldmapping('user', $post);
        if (is_login('member')) {
            $dataMember = fieldmapping('member', $_POST);
            $dataMember['penanggung_jawab'] = $dataUser['nama'];
            $dataUser['member'] = $post['memberid'];
        }

        if (isset($dataUser['password']) && !empty($dataUser['password']))
            $dataUser['password'] = password_hash($dataUser['password'], PASSWORD_DEFAULT);
        else
            unset($dataUser['password']);

        if (isset($_FILES['pp']) && !empty($_FILES['pp'])) {
            $this->load->helper('file_upload_helper');
            $fname = uploadImage($_FILES['pp'], 'pp', 'profile');

            if (sessiondata('login', 'photo') != 'default.jpg')
                delete_img(sessiondata('login', 'photo'));
            $dataUser['photo'] = $fname;
        }
        if (is_login('member'))
            $this->db->where('id', sessiondata('login', 'memberid'))->update('member', $dataMember);
        $this->db->where('id', sessiondata('login', 'id'))->update('user', $dataUser);

        // Update in localdata
        $tmp = sessiondata();
        if (is_login('member')) {
            foreach ($dataMember as $key => $value) {
                if (sessiondata('login', $key) != $value)
                    $tmp[$key] = $value;
            }
        }
        foreach ($dataUser as $key => $value) {
            if ($key == 'password') continue;
            if (sessiondata('login', $key) != $value)
                $tmp[$key] = $value;
        }
        $this->session->set_userdata('login', $tmp);
        response("Berhasil update profile");
    }

    function cek_username()
    {
        if (!httpmethod()) response("Ilegal Akses", 403);
        if (!is_login()) response("Anda belum login", 403);
        if (sessiondata('login', 'username') == $_POST['username']) response(['boleh' => true]);
        if (!isset($_POST['username']) || empty($_POST['username'])) response(['boleh' => false]);
        $usernameBaru = $_POST['username'];

        $user = $this->db->select('*')->where('username', $usernameBaru)->get('user')->result();
        if (!empty($user))
            response(['boleh' => false]);
        else
            response(['boleh' => true]);
    }
    function logout()
    {
        if (!is_login())
            response(['message' => 'Anda belum login', 'type' => 'error'], 401);

        try {
            $this->session->unset_userdata('login');
            response(['message' => 'Anda berhasil logout', 'type' => 'success'], 200);
        } catch (\Throwable $th) {
            response(['message' => 'Gagal, Terjadi kesalahan', 'type' => 'error', 'err' => $th], 500);
        }
    }

    function rsakey_post()
    {
        require_once get_path(APPPATH . '/third_party/liamylian/Xrsa.php');
        $key = Xrsa::createKeys();
        $nama = random(8);


        // file_put_contents(get_path(DOCS_PATH . 'pkeys/' . $nama . '-private.key'), $key['private_key']);
        // file_put_contents(get_path(DOCS_PATH . 'pkeys/' . $nama . '-public.key'), $key['public_key']);

        response($key);
    }

    function key_get($nama)
    {
        $files = array($nama . '-private.key' => get_path(DOCS_PATH . 'pkeys/' . $nama . '-private.key'), $nama . '-public.key' => get_path(DOCS_PATH . 'pkeys/' . $nama . '-public.key'));
        $zipname = $nama . '.zip';
        $zip = new ZipArchive;
        $zip->open(get_path(DOCS_PATH . 'pkeys/' . $zipname), ZipArchive::CREATE);
        foreach ($files as $key => $file) {
            $zip->addFile($file, $key);
        }
        $zip->close();

        foreach ($files as $key => $file) {
            unlink($file);
        }
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . $zipname);
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);
    }

    function enkripsi_post()
    {
        try {
            require_once get_path(APPPATH . '/third_party/liamylian/Xrsa.php');


            // $this->load->library('Word');
            $this->load->helper('file_upload_helper');
            $this->load->helper('download');
            $this->load->helper('file');
            $name = $_FILES["file"]["name"];
            $tmp = explode(".", $name);
            $ext = end($tmp);

            $tmpName = uploadFile($_FILES, 'file', 'tmp');
            $public_key = $this->input->post('public_key');
            
            $xrsa = new Xrsa($public_key);
            $fname = random(8);
            $fpath = get_path(DOCS_PATH . 'tmp/docs/');

            $newName = getExt($name);

            // Fixing bug
            $content = file_get_contents($fpath . $tmpName);
            $encrypted = $xrsa->publicEncrypt($content);

            if($newName['extension'] == 'docx')
                $newName['extension'] = 'doc';
            
            $finalName = random(5) . ' ' . str_replace(['encrypted', 'descrypted', ' encrypted', ' descrypted'], '', $newName['filename']) . ' encrypted.' . $newName['extension'];
            
            if($newName['extension'] == 'pdf'){
                require_once get_path(APPPATH . '/third_party/fpdf/fpdf.php');
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetFont('Arial',);
                $pdf->Write(20, $encrypted);
                $pdf->Output('F', $fpath . $finalName);
                // exit;
            }else{
                write_file($fpath . $finalName, $encrypted);
            }
            response(['url' => base_url('public/docs/tmp/docs/' . $finalName)]);
            force_download($finalName, $encrypted, TRUE);
            exit;
            $c = $this->readfile($fpath . $tmpName, $ext);
            
            $encrypted = $xrsa->publicEncrypt($c);
            unlink($fpath . $tmpName);

            $finalExt = '.doc';
            if($ext == 'pdf')
                $finalExt = '.pdf';

            file_put_contents($fpath . $fname . '.txt', str_replace(['.' . $ext, 'decrypted', 'encrypted', ' decrypted', ' encrypted'],'', $name) . ' encrypted'. $finalExt .'-separator-' . $encrypted);
        } catch (\Throwable $th) {
            response(['err' => $th->getMessage()], 500);
        }
        response(['name' => $fname . '.txt']);
    }

    function deskripsi_post()
    {
        try {
            require_once get_path(APPPATH . '/third_party/liamylian/Xrsa.php');
            $this->load->helper('file_upload_helper');
            $this->load->helper('download');
            $this->load->helper('file_reader_helper');


            $name = $_FILES["file"]["name"];
            $tmp = explode(".", $name);
            $ext = end($tmp);

            $tmpName = customUploadFile($_FILES['file'], 'tmp');
            $private_key = $this->input->post('private_key');
            
            $fname = random(8);
            $fpath = get_path(DOCS_PATH . 'tmp/docs/');
            $content = file_get_contents($fpath . $tmpName);

            if($ext == 'pdf'){
                $c = readPdf($fpath . $tmpName);
               $content = $c['text'];
            }
            // Fixing Bug
            $newName = getExt($name);
            $descrypted = XRsa::privateDecryptCustom($content, $private_key);
            $finalName = random(5) . ' ' . str_replace(['encrypted', 'descrypted', ' encrypted', ' descrypted'], '', $newName['filename']) . ' descrypted.' . $newName['extension'];
            write_file($fpath . $finalName, $descrypted);
            response(['url' => base_url('public/docs/tmp/docs/' . $finalName)]);
            force_download($finalName, $descrypted);
            exit;

            $c = $this->readfile($fpath . $tmpName, $ext);

            unlink($fpath . $tmpName);
            $decrypted = Xrsa::privateDecryptCustom(trim($c), $private_key);
            $finalExt = '.doc';
            if($ext == 'pdf')
                $finalExt = '.pdf';

            file_put_contents($fpath . $fname . '.txt', str_replace(['.' . $ext, 'decrypted', 'encrypted', ' decrypted', ' encrypted'],'', $name) . ' decrypted'. $finalExt .'-separator-' . $decrypted);

        } catch (\Throwable $th) {
            response(['err' => $th->getMessage()], 500);
        }
        response(['name' => $fname . '.txt']);
    }

    function embed_post()
    {
        try {
            require_once get_path(APPPATH . '/third_party/liamylian/Xrsa.php');
            $this->load->helper('file_upload_helper');
            $this->load->helper('download');

            $vpath = get_path(DOCS_PATH . 'tmp/video/');
            $fpath = get_path(DOCS_PATH . 'tmp/docs/');

            $eRName= $_FILES["file"]["name"];
            $tmp = explode(".", $eRName);
            $eExt = end($tmp);

            $eName = uploadFile($_FILES, 'file');
            $content = file_get_contents($fpath . $eName);
            // $content = $this->readfile($fpath . $eName, $eExt);

            $rVName = $_FILES["video"]["name"];
            $tmp = explode(".", $rVName);
            $rVExt = end($tmp);

            $salt = random(3);
            $sVname = str_replace('.' . $rVExt, '', $rVName);
            $fname = $salt . ' ' . $sVname . ' embeded.' . $rVExt;
            $vTmpName = uploadVideo($_FILES, 'video', 'tmp');
            $content2 = file_get_contents($vpath . $vTmpName);

            force_download($fname, $content2 . '-separator-' . $eRName . '-separator-' . $content);
            exit;

            file_put_contents(get_path(DOCS_PATH . 'tmp/video/' . $fname), $content2 . '-separator-' . $eRName . '-separator-' . $content);
            unlink($vpath . $vTmpName);
            unlink($fpath . $eName);
        } catch (\Throwable $th) {
            response(['err' => $th->getMessage()], 500);
        }
        response(['url' => base_url('public/docs/tmp/video/' . $fname)]);
    }

   

    function ekstraksi_post()
    {
        $this->load->helper('download');
        try {
            if ($_FILES['video']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['video']['tmp_name'])) {
                $videoContent = file_get_contents($_FILES['video']['tmp_name']);
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

            force_download($fExt['filename'] . ' extracted.'. $fExt['extension'], $fData);
            exit;
            if(in_array($fExt['extension'],['doc', 'docx'])){
                $finalExt = '.doc';
            }elseif($fExt['extension'] == 'pdf'){
                $finalExt = '.pdf';
            }
            file_put_contents($fpath . $fname . '.txt', $fExt['filename'] . ' extracted'. $finalExt .'-separator-' . $fData);

        } catch (\Throwable $th) {
            response(['err' => $th->getMessage()], 500);
        }
        response(['name' => $fname . '.txt']);
    }

    function rsaeof_post($act = null){
        if(empty($act))
            response("Ilegal akses", 403);
        $this->load->library('Steganografi');
        
        if($act == 'enkrip'){
            $this->steganografi->embedVideo($_FILES, $this->input->post('public_key'));
        }elseif($act == 'deskrip'){
            $this->steganografi->ekstractFile($_FILES, $this->input->post('private_key'));
        }

    }

    function file_get($name)
    {

        $name = urldecode($name);
        $tmp = explode(".", $name);
        $ext = end($tmp);

        if (!in_array($ext, ['mp4', 'mkv']))
            $fpath = get_path(DOCS_PATH . 'tmp/docs/' . $name);
        else
            $fpath = get_path(DOCS_PATH . 'tmp/video/' . $name);

        $ctype = getMimeType($ext);
        $this->load->helper('download');

        $data = file_get_contents($fpath);
        $data = explode('-separator-', $data);

        $name2 = urldecode($data[0]);
        $tmp2 = explode(".", $name2);
        $ext2 = end($tmp2);

        force_download($name2, $data[1]);
        exit;
        if($ext2 == 'pdf'){
            $this->load->library('Dompdf_gen');
            $this->dompdf_gen->display_pdf($data[1], $data[0]);
        }elseif(in_array($ext, ['mp4', 'mkv'])){
            $ctype = getMimeType($ext2);
            header("Pragma: public"); // required
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private",false); // required for certain browsers
            header("Content-Type: $ctype");
            header("Content-Disposition: attachment; filename=\"".$name."\";" );
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . filesize($fpath));
            ob_clean();
            flush();
            readfile($fpath);   
        }
        else{
            if(empty($data[1])){
                $fpath2 = get_path(DOCS_PATH . 'tmp/docs/');
                file_put_contents($fpath2 . $data[0], $data[1]);

                $ctype = getMimeType($ext2);
                header("Pragma: public"); // required
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: private",false); // required for certain browsers
                header("Content-Type: $ctype");
                header("Content-Disposition: attachment; filename=\"".$data[0]."\";" );
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: " . filesize($fpath2 . $data[0]));
                ob_clean();
                flush();
                readfile( $fpath2 . $data[0] );
                unlink($fpath2 . $data[0]);

            }else{
                force_download($data[0], $data[1]);
            }
            unlink($fpath);
        }
        
    }

    private function readfile($fpath, $ext){
        require_once APPPATH . 'libraries/FileReader.php';

        if($ext == 'pdf'){
            $this->load->helper('file_reader_helper');
            $c = readPdf($fpath)['text'];     
        }else{
            $fileReader = new FileReader($fpath);
            $c = $fileReader->convertToText();
        }

        return $c;
    }

    // private restoreFile(){}

    // function enkripsi_post(){
    //     require_once get_path(APPPATH . '/third_party/liamylian/Xrsa.php');
    //     $this->load->helper('file_upload_helper');
    //     $nama = uploadVideo($_FILES, 'video', 'sample');

    //     // Key
    //     $key = Xrsa::createKeys();
    //     // save key
    //     file_put_contents(get_path(DOCS_PATH . 'pkeys/' . str_replace(['.mp4', '.mkv'], '', $nama)), $key['private_key']);
    //     file_put_contents(get_path(DOCS_PATH . 'pkeys/' . str_replace(['.mp4', '.mkv'], '', $nama) . '.pub'), $key['public_key']);
    //     // 

    //     // RSA
    //     $xrsa = new Xrsa($key['public_key'], $key['private_key']);
    //     $data = "Hello, World";
    //     $encrypted = $xrsa->publicEncrypt($data);

    //     $content = file_get_contents(get_path(ASSETS_PATH . 'video/sample/' . $nama), false, null); 
    //     file_put_contents(get_path(ASSETS_PATH . 'video/sample/' . $nama), $content . $encrypted);
    // }
    // function deskripsi($nama){
    //     require_once get_path(APPPATH . '/third_party/liamylian/Xrsa.php');
    //     $content = file_get_contents(get_path(ASSETS_PATH . 'video/sample/' . $nama . '.mp4'), false, null, -342);
    //     $priv = file_get_contents(get_path(DOCS_PATH . 'pkeys/' . $nama), false, null);
    //     $pub = file_get_contents(get_path(DOCS_PATH . 'pkeys/' . $nama . '.pub'), false, null);
    //     $xrsa = new Xrsa($pub, $priv);
    //     echo  $xrsa->privateDecrypt($content);
    // }
}
