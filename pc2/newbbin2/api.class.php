<?php
/*
 * 凯撒皇宫 api 类
 * Author:archer
 * MG AG OT PT 开户对接联系QQ：3188459903
 */
include_once 'http.class.php';
class BBIN_TZH
{
    private $url    = "http://47.88.8.241:741/BBIN";
    private $comId   = "hg66g";
    private $comKey  = "ed74f05dfadf14e2";
    public $gamePlatform = "BBIN";
    public $debug = 0;



    public function BBIN_TZH($comId,$comKey,$gamePlatform="BBIN")
    {
        $this->comId    = $comId;
        $this->comKey   = $comKey;  
        $this->gamePlatform = $gamePlatform;
    }


    /*
     * 创建账号
     */
    public function GameUserRegister($username,$password)
    {
        $array = array(
           'userName' => $username,
           'userType' => 1,
           'comId' => $this->comId,
           'gamePlatform' => $this->gamePlatform,
           'method' => "GameUserRegister",
           'userType' => 1,
           'userPwd' => $password,
        );
        $xml = $this->getXML($array);
        $http = new http;
        $http->debug = $this->debug;
        $receive = $http->post($this->url,$xml);
		return $receive;
        $receivexml = simplexml_load_string($receive);
		
        if($receivexml->statusCode=="100"||$receivexml->statusCode=="011")
        {
            return true;
        }  else {
            return FALSE;
        }
    }

    /*
     * 玩家登录
     */
    public function GameUserLogin($username,$gameName='')
    {
        $array = array(
           'userName' => $username,
           'userType' => 1,
           'comId' => $this->comId,
           'gamePlatform' => $this->gamePlatform,
           'gameType' => 0,
           'method' => "GameUserLogin",
           'playerIp' => 1,
           'gameName' => $gameName,
        );
        $xml = $this->getXML($array);
        $http = new http;
        $http->debug = $this->debug;
        $receive = $http->post($this->url,$xml);

        $receivexml = simplexml_load_string($receive);
        //var_dump($receivexml);
       
        if($receivexml->statusCode=="100")
        {
			return $receivexml->statusText;
            
        }  else {
            return false;
        }  
    }
    
    /*
     * 玩家存款
     */
    public function TransferIn($username,$password,$amount) {
        $array = array(
           'userName' => $username,
           'userType' => 1,
           'comId' => $this->comId,
           'gamePlatform' => $this->gamePlatform,
           'amount' => $amount,
           'method' => "TransferIn",
           'userPwd' => $password,
           'playerIp' => $_SERVER["REMOTE_ADDR"],
        );
        $xml = $this->getXML($array);
        $http = new http;
        $http->debug = $this->debug;
        $receive = $http->post($this->url,$xml);
		//return $receive;
        $receivexml = simplexml_load_string($receive);
        //var_dump($receivexml);
        //exit;
        if($receivexml->statusCode=="100")
        {
            return true;
        }  else {
			$output = (string)$receivexml->statusText;
			if($output)
				return (string)$receivexml->statusText;
			else
				return $receive;
        }
    }
 
    /*
     * 玩家取款
     */
    public function TransferOut($username,$password,$amount) {
        $array = array(
           'userName' => $username,
           'userType' => 1,
           'comId' => $this->comId,
           'gamePlatform' => $this->gamePlatform,
           'amount' => $amount,
           'method' => "TransferOut",
           'userPwd' => $password,
           'playerIp' => $_SERVER["REMOTE_ADDR"],
        );
        $xml = $this->getXML($array);
        $http = new http;
        $http->debug = $this->debug;
        $receive = $http->post($this->url,$xml);
        $receivexml = simplexml_load_string($receive);
        //var_dump($receivexml);
       
        if($receivexml->statusCode=="100")
        {
            return true;
        }  else {
            return (string)$receivexml->statusText;
        }       
        
    }
    /*
     * 获取余额
     */
    public function GetBalance($username,$password) {
         $array = array(
           'userName' => $username,
           'userType' => 1,
           'comId' => $this->comId,
           'gamePlatform' => $this->gamePlatform,
           'method' => "GetBalance",
           'userPwd' => $password,
           'playerIp' => $_SERVER["REMOTE_ADDR"],
        );
        $xml = $this->getXML($array);
        $http = new http;
        $http->debug = $this->debug;
        $receive = $http->post($this->url,$xml);
		//return $receive;
		//return '111';
        $receivexml = simplexml_load_string($receive);
        if($receivexml->statusCode=="100")
        {
           return floatval($receivexml->balance);
        }  else {
            return FALSE;
        }     
    }
    /*
     * 投注记录.
     */
    public function GetBetDetailByGame($username,$roundDate,$gameKind,$startTime='',$endTime='',$page='',$pageLimit='') {
        $array = array(
           'userName' => $username,
           'roundDate' => $roundDate,
           'comId' => $this->comId,
           'gamePlatform' => $this->gamePlatform,
           'method' => "GetBetDetailByGame",
           'gameKind' => $gameKind,//游戏种类，（BBIN:1：球类，3：视讯，5：机率，12：彩票，15：3D 厅 AG:固定为0）
           'startTime' => $startTime, //开始时间如：00:00:00【BB 体育无效】  AG必填
           'endTime' => $endTime,       //结束时间如：23:59:59【BB 体育无效】 AG必填
           'page' => $page,//查询页数
           'pageLimit' => $pageLimit , //每页条数
           
        );
      
        $xml = $this->getXML($array);
        $http = new http;
         $http->debug = $this->debug;
        $receive = $http->post($this->url,$xml);
        $receivexml = simplexml_load_string($receive);
        //var_dump($receivexml);
        if($receivexml->statusCode=="100")
        {
			$result = $this->simplest_xml_to_array($receive);
			return $result['Data'];
        }  else {
            return FALSE;
        }     
    }

	public function getall($report_url,$lasttime){
		$http = new http;
         $http->debug = $this->debug;
        $receive = $http->post2($report_url,$xml);
		return $receive;
	}
    
    public function getXML($array) {
        $AES_DATA = $this->EncryptAes($array);
        $xml = '<?xml version="1.0" encoding="utf-8"?><request comId="'.$this->comId.'" method="'.$array['method'].'" gamePlatform="'.$array['gamePlatform'].'">'.$AES_DATA.'</request>';
        return $xml;
    }
     
    /*
     * 加密函数
     */
   
    private function EncryptAes($array){
        // 去掉不参与加密的字段
        unset($array['comId']);
        unset($array['gamePlatform']);
        unset($array['method']);
        $string = '';
        
        foreach ($array as $key=>$val){
            $string .= "<$key>$val</$key>";
        }

        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128,$this->comKey, $string, MCRYPT_MODE_CBC, $this->comKey));
       
        //echo "加密前：<pre>".htmlentities($string)."</pre><br/>";
        //echo "加密后：".$encrypted;
       return $encrypted;
        
    }
     
     /*
     * 解密函数
     */
    
    private function DecryptAes($data){
        $data = base64_decode($data);
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->comKey, $data, MCRYPT_MODE_CBC, $this->comKey);    
        
        return $decrypted;
    }   
    
    /*
     * 处理进入游戏类别,仅限BBIN  
     * $GameType 
     * page_site  ： BB体育:ball 彩票:ltlottery 3D厅:3DHall 视讯:live 机率:game 若为空白则导入整合页
     */
    public function GameType($url,$GameType){
        $replacement = "page_site=".$GameType;
        $pattern = "/page_site=/i";
       return preg_replace($pattern, $replacement, $url);        
       
    }
    
    private function simplest_xml_to_array($xmlstring) 
	{
    	return json_decode(json_encode((array) simplexml_load_string($xmlstring)), true);
	}
}

/*
 * 使用示例
 */
//$bbinapi = new BBIN_TZH("xpj","dc77b1f6b6b24796");   //初始化对象
//$bbinapi->GameUserRegister("tzhxpj22222", "222222");
//$bbinapi->GameUserLogin("tzhxpj22222");
//$bbinapi->TransferIn("tzhxpj22222", "222222",1);
//$bbinapi->GetBalance("tzhxpj22222", "222222");


?>