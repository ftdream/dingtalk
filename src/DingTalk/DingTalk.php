<?php
namespace DingTalk;

use Cache;

class DingTalk
{
    public function __construct($config)
    {
        $this->corpid = $config['corpid'];
        $this->corpsecret = $config['corpsecret'];
    }

    public function getDepartmentList($parentId=1)
    {
        $response = Http::get("/department/list",
            array("access_token" => $this->getAccessToken(), "id" => $parentId));
        return json_encode($response);
    }

    private function getAccessToken()
    {
        /**
         * 缓存accessToken。accessToken有效期为两小时，需要在失效前请求新的accessToken（注意：以下代码没有在失效前刷新缓存的accessToken）。
         */
        $accessToken = Cache::get('corp_access_token');
        if (!$accessToken)
        {
            $response = Http::get('/gettoken', array('corpid' => $this->corpid, 'corpsecret' => $this->corpsecret));
            $accessToken = $response->access_token;
            Cache::set('corp_access_token', $accessToken);
        }
        return $accessToken;
    }

    public static function getTicket($accessToken)
    {
        $response = Http::get('/get_jsapi_ticket', array('type' => 'jsapi', 'access_token' => $accessToken));
        return $response->ticket;
    }

    public function getConfig()
    {
        $nonceStr = '';
        $timeStamp = time();
        $url = self::getCurrentUrl();
        $accessToken = $this->getAccessToken();
        $ticket = self::getTicket($accessToken);
        $signature = self::sign($ticket, $nonceStr, $timeStamp, $url);

        $config = array(
            'url' => $url,
            'nonceStr' => $nonceStr,
            'timeStamp' => $timeStamp,
            'corpId' => $this->corpid,
            'signature' => $signature);
        return json_encode($config, JSON_UNESCAPED_SLASHES);
    }

    public static function sign($ticket, $nonceStr, $timeStamp, $url)
    {
        $plain = 'jsapi_ticket=' . $ticket .
            '&noncestr=' . $nonceStr .
            '&timestamp=' . $timeStamp .
            '&url=' . $url;
        return sha1($plain);
    }


    private static function getCurrentUrl() 
    {
        $url = "http";
        if ($_SERVER["HTTPS"] == "on") 
        {
            $url .= "s";
        }
        $url .= "://";

        if ($_SERVER["SERVER_PORT"] != "80") 
        {
            $url .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } 
        else 
        {
            $url .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $url;
    }
}
