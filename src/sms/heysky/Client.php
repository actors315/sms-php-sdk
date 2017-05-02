<?php
/**
 * Created by PhpStorm.
 * User: xiehuanjin
 * Date: 2016/11/5
 * Time: 14:27
 */

namespace sms\heysky;


class Client
{
    /**
     * API Endpoints
     *
     * @var array
     */
    private static $api = array(
        "heysky" => "https://api2.santo.cc/submit",
    );

    /**
     * 第三方发送平台
     *
     * @var string
     */
    private static $plat = 'heysky';

    /**
     * 短信平台用户名
     *
     * @var string
     */
    private static $appId;

    /**
     * 短信平台用户密码
     *
     * @var string
     */
    private static $appSecret;

    /**
     * 自定义发送者号码
     *
     * @var string
     */
    private static $sa;

    public static function initialize($appId, $appSecret, $sa)
    {
        self::$appId = $appId;
        self::$appSecret = $appSecret;
        self::$sa = $sa;
    }

    public static function getAPIEndPoint()
    {
        return self::$api[self::$plat];
    }

    public static function get($path, $data)
    {
        $url = self::getAPIEndPoint();
        $url .= $path;

        $data['cpid'] = self::$appId;
        $data['cppwd'] = self::$appSecret;
        $data['sa'] = self::$sa;

        $url .= '?';
        foreach ($data as $key => $item){
            $url.= $key . '=' . $item . '&';
        }
        $url = rtrim($url,'&');
        $response = file_get_contents($url);
        parse_str($response, $response_array);
        return $response_array;
    }
}