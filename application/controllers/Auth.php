<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    function login()
    {
        if(is_login())
            redirect(base_url());
        $data = array(
            'resource' => array('main', 'form', 'login'),
            'content' => array('forms/login2'),
            'hideSpinner' => true,
            'loading_animation' => true,
        );
        $this->add_cachedJavascript('pages/auth', 'file', 'body:end', array(
            'formid' => '#form-login',
            'submitSukses' => "function(){
                setTimeout(function(){
                    location.href = path;
                }, 1000)
            }"
        ));
        $this->removeFromResourceGroup('main', 'vendor/fontawesome/css/all.min.css');
        $this->addViews('template/blank', $data);
        $this->render();
    }
    function register(){
        
    }
    function logout(){
        // if (!httpmethod())
        //     response(["message" => "Error, Tidak ada method logout[GET]", "type" => 'error'], 405);

        if (!is_login())
            response(['message' => 'Anda belum login', 'type' => 'error'], 401);

        try {
            $this->session->unset_userdata('login');
            redirect(base_url('login'));
        } catch (\Throwable $th) {
            response(['message' => 'Gagal, Terjadi kesalahan', 'type' => 'error', 'err' => $th], 500);
        }
    }
}
