<?php

require 'Browser.php';
// ini_set('display_errors',1);
// error_reporting(E_ALL);

class Api {

    private $api_url = "http://lagoon.me/api/";
    private $api_key;
    private $base_url;
    private $dop_info = "";
    private $comment;
    private $country;
    private $email;
    public $hash;
    public $flow_hash;
    private $goal_id = 0;
    private $postClick = 30;
    private $ya_metrika;
    private $flow_info;
    public $transit_info;
	public $route_info;
    private $ip = "";
    private $platform = "";
    private $browser = "";
    private $referer = "";
    private $data1 = "";
    private $data2 = "";
	private $data3 = "";
	private $data4 = "";
	private $data5 = "";
	private $vcode = "";
    private $landing_domen;
    private $landing_folder;
    public $page_url;
	private $shopfront;

    function __construct($api_key) {
        // устанавливаем API-HASH рекламодателя
        $this->api_key = $api_key;

        // определяем IP посетителя
        $this->ip = $_SERVER["REMOTE_ADDR"];
		
		$user_agent = new Browser();
        $this->browser = $user_agent->getBrowser();
        $this->platform = $user_agent->getPlatform();
		
        // определяем реферера
		if (isset($_SERVER["HTTP_REFERER"]))
			$this->referer = $_SERVER["HTTP_REFERER"];

        // суббакаунты
        if ($_SERVER['REQUEST_URI'] != '') {
            preg_match("#data1=(.*)(&|$)#isU", $_SERVER['REQUEST_URI'], $data1);
            preg_match("#data2=(.*)(&|$)#isU", $_SERVER['REQUEST_URI'], $data2);
			preg_match("#data3=(.*)(&|$)#isU", $_SERVER['REQUEST_URI'], $data3);
			preg_match("#data4=(.*)(&|$)#isU", $_SERVER['REQUEST_URI'], $data4);
			preg_match("#data5=(.*)(&|$)#isU", $_SERVER['REQUEST_URI'], $data5);
            preg_match("#vcode=(.*)(&|$)#isU", $_SERVER['REQUEST_URI'], $vcode);
			preg_match("#shopfront=(.*)(&|$)#", $_SERVER['REQUEST_URI'], $shopfront);
            if (count($data1) > 1)
                $this->data1 = $data1[1];
            if (count($data2) > 1)
                $this->data2 = $data2[1];
			if (count($data3) > 1)
                $this->data3 = $data3[1];
			if (count($data4) > 1)
                $this->data4 = $data4[1];
			if (count($data5) > 1)
                $this->data5 = $data5[1];
            if (count($vcode) > 1)
                $this->vcode = $vcode[1];
			if (count($shopfront) > 1)
                $this->shopfront = $shopfront[1];
			
        }
    }

    public function setBaseUrl($base_url = '') {
        if ($base_url[strlen($base_url) - 1] !== '/')
            $base_url = $base_url . '/';
        $this->base_url = $base_url;
        return $this;
    }

    public function setFlowHash() {
        $uri = $_SERVER['REQUEST_URI'];
        $uri_array = explode('/', $uri);

        $script_name = $_SERVER['SCRIPT_NAME'];
        $script_name_arrray = explode('/', $script_name);
        if (count($script_name_arrray) >= 3) {
            // в директории домена
			if($uri_array[2]{0}!='?'){
				$url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $script_name_arrray[1] . '/';
				$flow_hash = $uri_array[2];
			}
			$this->landing_domen = 0;
			$this->landing_folder = $script_name_arrray[1];
        } else {
            // на домене или на поддомене
			if($uri_array[1]{0}!='?'){
				$url = 'http://' . $_SERVER['HTTP_HOST'] . '/';
				$flow_hash = $uri_array[1];
			}
			$this->landing_domen = 1;
			
        }
		if($flow_hash)
			$this->flow_hash = $flow_hash;
		else
			$this->set_page_url();
        return $this;
    }
	
	public function set_page_url() {
		$script_name = $_SERVER['SCRIPT_NAME'];
        $script_name_arrray = explode('/', $script_name);
        $this->page_url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $script_name_arrray[1] . '/';
        return $this;
    }
    
    public function set_flow_by_data($flow) {
        $this->flow_hash = $flow;
    }
    
    public function set_page_by_data($url) {
        $this->page_url = $url;
    }

    public function set_dop_info($dop_info = '') {
        $this->dop_info = $this->json_encode_cyr($dop_info);
        return $this;
    }    
	public function set_comment($comment = '') {
		$this->comment = $comment;
        return $this;
    }
	public function set_email($email = '') {
		$this->email = $email;
        return $this;
    }

    public function set_hash($hash = '') {
        $this->hash = $hash;
        return $this;
    }
    //я
    public function set_country($country = ''){
        $this->country = $country;
        return $this;
    }
    //-я

    public function set_goal_id($id = '') {
        $this->goal_id = $id;
        return $this;
    }

    public function setHashToCookie($time = 30) {
        if ($this->landing_domen == 1)
            $path = '/';
        else
            $path = '/' . $this->landing_folder . '/';
        setcookie("hash", $this->hash, time() + $time * 86400, $path); /*86400*/
    }

    public function insertRequest() {
        return $this->goCurl();
    }

    public function getPostClick() {
        return $this->postClick;
    }

    public function getYaMetrikaId() {
        return $this->ya_metrika;
    }
	public function getMetrikaId() {
        return $this->flow_info["metrika"];
    }

    public function getMailId() {
        return $this->flow_info["mail"];
    }
    //я
        public function getFlow(){
            return  $this->flow_info;
        }
    //-я

    public function getGoogleId() {
        return $this->flow_info["google"];
    }
    public function getFacebookId() {
        return $this->flow_info["facebook"];
    }  
	public function getFloats() {
        return $this->flow_info["floats"];
    }  

	public function getPageUrl() {
        return $this->route_info["page_url"];
    }
	public function getGasketUrl() {
        return $this->route_info["gasket_url"];
    }

    public function insertTransit() {
       
            $result = $this->goCurl("insert_transit");
            $json = json_decode($result);
            return $json;
        
    }
	
	public function insertPenetration(){
		$this->goCurl("insert_penetration");
	}
	
	public function insertComment() {
		
            $result = $this->goCurl("insert_comment");
            $json = json_decode($result);
            return $json;
    }
	
	public function insertEmail() {
            $result = $this->goCurl("insert_email");
            $json = json_decode($result);
            return $json;
    }


    public function getInfo() {
        $json = json_decode($this->goCurl("get_info"));
        $this->postClick = $json->postclick;
        $this->ya_metrika = $json->ya_metrika;
        $this->flow_info["metrika"] = $json->metrika_id;
        $this->flow_info["mail"] = $json->mail_id;
        $this->flow_info["google"] = $json->google_id;
        $this->flow_info["facebook"] = $json->facebook_id;
        $this->flow_info["floats"]['freeze'] = $json->freeze;
        $this->flow_info["floats"]['popup'] = $json->popup;
        $this->flow_info["floats"]['popup_bottom'] = $json->popup_bottom;
         //я
            $this->flow_info["price"]['price'] = $json->price;
            $this->flow_info["price"]['currency_name'] = $json->currency_name;
        //-я
    }

    public function getInfoTransit() {
        $json = json_decode($this->goCurl("get_info_transit/" . $this->hash));
        $this->postClick = $json->postclick;
        $this->transit_info["comebacker"] = $json->comebacker;
        $this->transit_info["newwindow"] = $json->newwindow;
        $this->transit_info["url"] = $json->url;
        $this->transit_info["ya_metrika"] = $json->ya_metrika;
    }
	
	public function getInfoRoute() {
        $json = json_decode($this->goCurl("get_info_route/" . $this->flow_hash));
        $this->route_info["gasket_url"] = $json->gasket_url;
        $this->route_info["page_url"] = $json->page_url;
    }
	
    private function goCurl($url = '') {
        $postFields = "api_key=" . $this->api_key
                . "&dop_info=" . $this->dop_info
                . "&comment=" . $this->comment
                . "&email=" . $this->email
                . "&hash=" . $this->hash
                . "&country=" . $this->country
                . "&flow_hash=" . $this->flow_hash
                . "&ip=" . $this->ip
                . "&user_agent=" . $this->browser
                . "&platform=" . $this->platform
                . "&data1=" . $this->data1
                . "&data2=" . $this->data2
				. "&data3=" . $this->data3
				. "&data4=" . $this->data4
				. "&data5=" . $this->data5
				. "&vcode=" . $this->vcode
				. "&shopfront=".$this->shopfront
                . "&referer=" . $this->referer
                . "&goal_id=" . $this->goal_id
                . "&page_url=" . $this->page_url
				. "&shopfront=".$this->shopfront;
        $ch = curl_init($this->api_url . $url);
		curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_REFERER, $_SERVER["HTTP_HOST"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    function json_encode_cyr($str) {
        $arr_replace_utf = array('\u0410', '\u0430', '\u0411', '\u0431', '\u0412', '\u0432',
            '\u0413', '\u0433', '\u0414', '\u0434', '\u0415', '\u0435', '\u0401', '\u0451', '\u0416',
            '\u0436', '\u0417', '\u0437', '\u0418', '\u0438', '\u0419', '\u0439', '\u041a', '\u043a',
            '\u041b', '\u043b', '\u041c', '\u043c', '\u041d', '\u043d', '\u041e', '\u043e', '\u041f',
            '\u043f', '\u0420', '\u0440', '\u0421', '\u0441', '\u0422', '\u0442', '\u0423', '\u0443',
            '\u0424', '\u0444', '\u0425', '\u0445', '\u0426', '\u0446', '\u0427', '\u0447', '\u0428',
            '\u0448', '\u0429', '\u0449', '\u042a', '\u044a', '\u042b', '\u044b', '\u042c', '\u044c',
            '\u042d', '\u044d', '\u042e', '\u044e', '\u042f', '\u044f');
        $arr_replace_cyr = array('А', 'а', 'Б', 'б', 'В', 'в', 'Г', 'г', 'Д', 'д', 'Е', 'е',
            'Ё', 'ё', 'Ж', 'ж', 'З', 'з', 'И', 'и', 'Й', 'й', 'К', 'к', 'Л', 'л', 'М', 'м', 'Н', 'н', 'О', 'о',
            'П', 'п', 'Р', 'р', 'С', 'с', 'Т', 'т', 'У', 'у', 'Ф', 'ф', 'Х', 'х', 'Ц', 'ц', 'Ч', 'ч', 'Ш', 'ш',
            'Щ', 'щ', 'Ъ', 'ъ', 'Ы', 'ы', 'Ь', 'ь', 'Э', 'э', 'Ю', 'ю', 'Я', 'я');
        $str1 = json_encode($str);
        $str2 = str_replace($arr_replace_utf, $arr_replace_cyr, $str1);
        return $str2;
    }

}

function getTel($tele) {
    $ar_tele = array(' ', '(', ')', '+', '-', '_');
    $tele = str_replace($ar_tele, '', $tele);

    return $tele;
}
