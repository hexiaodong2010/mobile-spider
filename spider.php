<?php

/**
 *
 * @link https://www.dianhua.cn/search/beijing?key=$model
 */

/**
 * Class Utils
 * @property  mobileQuery
 */
class Util
{
    private static $_url_map = [
        'dianhua' => "https://www.dianhua.cn/search/beijing?key=MOBIL",
    ];

    private static $_model = null;

    private static $_instance;

    private static $_handel;

    private function __construct()
    {
    }

    static function mobileQuery($mobile = null)
    {
        if (null == $mobile) {
            return [];
        }
        self::$_model = $mobile;
        self::$_handel = 'dianhua';
        return self::getInstance()->dianhuaQuery();
    }

    public static function getInstance()
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    private function dianhuaQuery()
    {
        $resHtml = file_get_contents(self::getUrl());
        $resHtml = preg_replace('/[\t]/', '', $resHtml);
        $reg = "#<div class=\"c_right_list\">[^<>]+<dl>[\S\s]+</dl>[^<>]+</div>#";
        preg_match_all($reg, $resHtml, $resHH);
        header("Content-Type:Application/json");
        $res = [];
        if (!empty($resHH)){
            $str = $resHH[0];
        }
        $res = $resHH;
       return [
            'mobile' => self::$_model,
            'urlSource' => $res
        ];
    }

    private function getUrl()
    {
        return str_replace('MOBIL', self::$_model, self::$_url_map[self::$_handel]);
    }

    private function out()
    {

    }

    public function __call($name, $arguments)
    {
        die($name);
        // TODO: Implement __call() method.
    }
}

if ($_GET['q']) {
    $mobile = $_GET['q'];
}else{

    $mobile = 110;
}
echo json_encode(Util::mobileQuery($mobile));
