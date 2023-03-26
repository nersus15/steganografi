<?php defined('BASEPATH') OR exit('No direct script access allowed');
$currentUrl;
$menuWithCurrentUrl;
class PersiapanHalaman {
    public function __construct() {
        $this->currentUrl = str_replace(base_url('index.php/'), '', current_url());
    }
    function handleMenu(){
        /** @var CI_Controller $ci */
        $ci =& get_instance();
        // Cek apakah user sudah login dan sesuai hak akses
        $userdata = $ci->session->userdata('login');
        if(!is_null($userdata)){
            // Sync user permission from database to app
            // store cache
            $ci->db->cache_on();
            $userPerm = $ci->db->select('permission_id')->where('username', $userdata['username'])->get('user_permission')->result();
            $ci->db->cache_off();
            $userdata['permission'] = [];
            foreach($userPerm as $v)
                $userdata['permission'][] = $v->permission_id;

            $ci->session->set_userdata('login', $userdata);
        }
        $allowedPermission = [];
        if(isset($userdata['permission']) && !empty($userdata['permission']))
            $allowedPermission = $userdata['permission'];

        $allowedPermission[] = 6;
        $allowedPermission[] = 3;
        $allowedPermission[] = 5;

        // $ci->db->cache_on();        
        $allMenu = $ci->db->select('menu.*, menu_permission.permission')
            ->join('menu_permission',  'menu_permission.menu= menu.id')
            ->where('aktif', 1)
            ->where_in('menu_permission.permission', $allowedPermission, false)
            ->order_by('menu.bobot', 'ASC')
            ->get('menu')
            ->result();
        // $ci->db->cache_off();      
        
        

        $current_routes = [];
        if(isset($ci->uri->routes['dir']) && !empty( $ci->uri->routes['dir'])) $current_routes[] = $ci->uri->routes['dir'];

        $current_routes[] = $ci->uri->routes['class'];
        $current_routes[] = $ci->uri->routes['method'] == 'index' ? '' : $ci->uri->routes['method'];
        $currentUrl = join('/', $current_routes);
        if(endWith($currentUrl, '/')) $currentUrl = substr($currentUrl, 0, strlen($currentUrl) - 1);
        $menuWithCurrentUrl = array_filter($allMenu, function($menu) use ($currentUrl){
            return $currentUrl == $menu->url;
        });
        $this->menuWithCurrentUrl = $menuWithCurrentUrl;
        list($harusLogin, $perm)= $this->mustLogin($menuWithCurrentUrl);

        log_message("DEBUG", "=== Allowed Permission ===". print_r($allowedPermission, true));
        log_message("DEBUG", "=== All Menu With Allowed Permission ===". print_r($allMenu, true));
        log_message("DEBUG", "=== Menu (Current Url) ===". print_r($menuWithCurrentUrl, true));
        if(!$this->isWebService()){
            if(is_null($perm)){
                $ci->load->view('errors/html/error_404', ['heading' => 'ACCESS DENIED', 'message' => 'You dont have permission to access this page']);
            }

            $ci->session_info = array(
                'permission' => $perm,
                'harusLogin' => $harusLogin,
                'menus' => []
            );
            if($harusLogin){    
                if(is_null($userdata))
                    $ci->load->view('errors/html/error_404', ['heading' => 'ACCESS DENIED', 'message' => 'You dont have permission to access this page']);

                $tidakAdaSama = true;
                foreach($perm as $p){
                    if(in_array($p, $userdata['permission'])){
                        $tidakAdaSama = false;
                        break;
                    }elseif($p == 6){ // permission 6 = login (bisa diakses semua asal sudah login)
                        $tidakAdaSama = false;
                    }
                }
                if($tidakAdaSama)
                    $ci->load->view('errors/html/error_404', ['heading' => 'ACCESS DENIED', 'message' => 'You dont have permission to access this page']);
                    
            }else if($this->dontLogin())
                $ci->load->view('errors/html/error_404', ['heading' => 'Denied', 'message' => 'Please logout before access this page']);

            $m = array();
            foreach($allMenu as $menu){
                // if($menu->permission)
                if($menu->aktif == 1 && !isset($m[$menu->id])){
                    if(!empty($menu->parent)){
                        if(!isset($ci->session_info['subMenus'])){
                            $ci->session_info['subMenus'] = [];
                        }
                        if(!isset($ci->session_info['subMenus'][$menu->parent])){
                            $ci->session_info['subMenus'][$menu->parent] = array(
                                'induk' => $menu->parent,
                                'menus' =>array(
                                    array(
                                        'lvl' => $menu->lvl,
                                        'text' => $menu->nama,
                                        'icon' => $menu->icon,
                                        'link' => $menu->url,
                                        'parrent_element' => $menu->parrent_element,
                                        'active' => $currentUrl == $menu->url
                                    )
                                )
                            );
                        }else{
                            $ci->session_info['subMenus'][$menu->parent]['menus'][] = array(
                                'lvl' => $menu->lvl,
                                'text' => $menu->nama,
                                'icon' => $menu->icon,
                                'link' => $menu->url,
                                'parrent_element' => $menu->parrent_element,
                                'active' => $currentUrl == $menu->url
                            );
                        }
                        
                    }else{
                        $ci->session_info['menus'][$menu->id] = array(
                            'id' => $menu->id,
                            'induk' => $menu->parent,
                            'lvl' => $menu->lvl,
                            'text' => $menu->nama,
                            'icon' => $menu->icon,
                            'link' => $menu->url,
                            'parrent_element' => $menu->parrent_element,
                            'active' => $currentUrl == $menu->url
                        );
                    }
                }
                
            }
            if(isset($ci->session_info['subMenus']) && !empty($ci->session_info['subMenus'])){
                $subMenuAktif = array_map(function($arr){
                    foreach($arr['menus'] as $m){
                        if($m['active']){
                            return $m;
                        }
                    }
                }, $ci->session_info['subMenus']);
                if(count($subMenuAktif) == 1){
                    $key = array_key_first($subMenuAktif);
                    if(!empty($subMenuAktif[$key]))
                        $ci->session_info['menus'][$key]['active'] = true;
                }
            }
            log_message("DEBUG", " ======= MENU this PAGE " . print_r($ci->session_info, true));
        }else{
            // TODO: handle webservice path
        }

    }

    private function isWebService(){
        $ci = &get_instance();
        $ci->load->config('config');
        $url = $this->currentUrl;
        $web_servcices = $ci->config->item('web_services');

        if(!empty($web_servcices)) return in_array($url, $web_servcices);
        else return false;
    }
    private function mustLogin($array){
        $harusLogin = true;
        if(!empty($array)){
            $p = [];
            foreach($array as $v){
                $p[] = $v->permission;
                if(in_array($v->permission, [5, 3])) $harusLogin = false; //permission 5 = without login (bisa diakses jika tidak login), 3 = default (bisa diakses dengan atau tanpa login)
            }
            return [$harusLogin, $p];
        }else{
            return [false, null];
        }

    }

    private function dontLogin(){
       $ci =& get_instance();
       $userdata = $ci->session->userdata('login');
       foreach($this->menuWithCurrentUrl as $m){
            if($m->permission == 5 && !empty($userdata)) 
                return true;
        }
        return false;
    }
}