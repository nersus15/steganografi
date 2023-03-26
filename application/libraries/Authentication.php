<?php
require 'vendor/autoload.php';

use \Firebase\JWT\JWT;

class Authentication
{
    function persiapan(&$post, $mode = 'login')
    {
        if (is_login())
            response(['message' => 'Anda sudah login', 'type' => 'error'], 401);

        $input = fieldmapping('login', $post);
        // Sanitasi input
        if (!isset($input['user']) || empty($input['user']))
            response(['message' => 'Tidak ada Username atau Email yang dikirim', 'type' => 'error'], 400);
        else
            $newinput['user'] = $input['user'];

        if (!isset($input['password']) || empty($input['password'])) {
            response(['message' => 'Tidak ada Password yang dikirim', 'type' => 'error'], 400);
        } else
            $newinput['pass'] = $input['password'];

        return array($newinput);
    }
    function login($input)
    {
        if (is_login())
            response(['message' => 'Anda sudah login', 'type' => 'error'], 401);

        /** @var CI_Controller $ci */
        $ci = &get_instance();

        $user = $ci->db
            ->select('user.*, member.asal, member.dibuat, member.tim, member.penanggung_jawab, member.id as memberid')
            ->where('user.username', $input['user'])
            ->or_where('user.email', $input['user'])
            ->from('user')
            ->join('member', 'user.member = member.id', 'left')
            ->get()->row_array();
        $ci->db->reset_query();

        if (empty($user)) {
            response(['message' => 'User ' . $input['user'] . ' Tidak ditemukan', 'type' => 'error'], 400);
        } else {
            if (password_verify($input['pass'], $user['password'])) {
                // setciobject($user);
                unset($user['password']);
                $user['session_dibuat'] = time();
                
                if (JWT_AUTH)
                    $token = JWT::encode($user, 'BQNIT');
                else
                    $ci->session->set_userdata('login', $user);


                if (JWT_AUTH)
                    response(['message' => 'Berhasil Login', 'type' => 'sucsess', 'data' => $token]);
                else
                    response(['message' => 'Berhasil Login', 'type' => 'sucsess']);

            } else {
                response(['message' => 'Password untuk ' . $input['user'] . ' Salah', 'type' => 'error'], 400);
            }
        }
    }

    function loginWordpress($tokenURL, $metaUrl, $post){
        /** @var CI_Controller */
        $ci =& get_instance();

        $data = $post;
        try {
            $response = post_curl($tokenURL, $data);
            $userdata = [];
            if(isset($response->token)){ 
                list($verified, $data) = verfify_token($response->token);
                if($verified){
                    $userdata['token'] = $response->token;
                    $userdata['id'] = $data->data->user->id;
                    $metaData = get_curl($metaUrl . $userdata['id']);
    
                    if(!empty($metaData)){
                        $userdata['avatar'] = $metaData->avatar_urls->{'96'};
                        $userdata['nama_lengkap'] = $metaData->name;
                    }    
                    $ci->session->set_userdata('login', $userdata);
                }
            }
            
            response((array) $response);
        } catch (\Throwable $th) {
            response(['message' => 'Terjadi Kesalahan', 'Err' => $th->getMessage()], 500);
        }
    }
}
