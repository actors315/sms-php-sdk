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
        "heysky" => "http://api2.santo.cc/submit",
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

    public static function request($method, $path, $data)
    {
        $url = self::getAPIEndPoint();
        $url .= $path;

        $data['cpid'] = self::$appId;
        $data['cppwd'] = self::$appSecret;
        $data['sa'] = self::$sa;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        switch ($method) {
            case "GET":
                    $url .= '?'.http_build_query($data);
                    curl_setopt($ch, CURLOPT_URL, $url);
                break;
        }
        $response = curl_exec($ch);
        curl_close($ch);
        parse_str($response, $response_array);
        return $response_array;
    }

    public static function post($path, $data)
    {
        return self::request('POST', $path, $data);
    }

    public static function get($path, $data)
    {
        return self::request('GET', $path, $data);
    }
}