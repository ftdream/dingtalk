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
}