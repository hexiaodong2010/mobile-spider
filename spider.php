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

    protected static $_code_map = null;

    public static function getAreaNoInfo($code = null, $type = 'json')
    {
        $html = file_get_contents('http://www.stats.gov.cn/tjsj/tjbz/xzqhdm/201703/t20170310_1471429.html');
        if (!$out = self::$_code_map) {
            preg_match_all('/<p[^[class]]*class=\"MsoNormal\"[^>]*>[<b>]?<span[^>]*>[^\d{6}]*(?<code>.*?)<span>[^<>]*<\/span><\/span>[<\/b>]?[<b>]?<span[^>]*>(?<name>.*?)<\/span>[<\/b>]?<\/p>/', $html, $out);
            self::$_code_map = array_combine($out['code'], $out['name']);
        }
        if ($type == 'json') {
            return json_encode(self::$_code_map, JSON_UNESCAPED_UNICODE);
        } else {
            return self::$_code_map;
        }
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