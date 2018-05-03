<?php
/**
 * Created by PhpStorm.
 * User: xiehuanjin
 * Date: 2016/11/5
 * Time: 14:28
 */

namespace sms\heysky;

/**
 * Class SMS
 *
 * 封装Heysky国际短信API
 * @package Heysky
 */
class SMS
{
    /**
     * 操作命令
     * MT_REQUEST 单目标号码信息下行
     * MULTI_MT_REQUEST 多目标号码信息下行
     *
     * @var string
     */
    private $command = 'MT_REQUEST';

    /**
     * 目标号码，MSISDN
     *
     * @var array
     */
    private $da = [];

    /**
     * 消息内容编码
     * 15: GBK
     * 8: Unicode
     * 0: ISO8859-1
     *
     * @var string
     */
    private $dc = 15;

    /**
     * @var 消息内容
     */
    private $sm;

    function setCommand($command){
        $this->command = $command;
    }

    function setDa($da){
        $this->da[] = $da;
    }

    function setDc($dc){
        if (in_array($dc, [15, 8, 0])) {
            $this->dc = $dc;
        }
    }

    function setSm($sm){
        $this->sm = $sm;
    }

    function send(){
        $data = $this->encode();
        $resp = Client::get('submit', $data);
        return $resp;
    }

    function verifyPhoneNumber(){
        $phoneNumber = current($this->da);
        $resp = Client::get('verifyPhoneNumber', [
            'da' => $phoneNumber
        ],'json');
        return $resp;
    }

    function getSmsStatus($msgid){
        $resp = Client::get('get-rptstatus', [
            'msgid' => $msgid
        ],'json');
        return $resp[0];
    }

    private function encode(){
        $this->da = array_unique($this->da);
        count($this->da) > 1 && $this->setCommand('MULTI_MT_REQUEST');

        return [
            'command'   => $this->command,
            'da' => implode(',',$this->da),
            'sm' => urlencode($this->sm)
        ];
    }

    /**
     *  decode Hex String
     *
     * @param string $dataCoding       charset
     * @param string $hexStr      convert a hex string to binary string
     * @return string binary string
     */
    public static function decodeHexStr($dataCoding, $hexStr)
    {
        $hexLenght = strlen($hexStr);

        $binString = '';
        for ($x = 1; $x <= $hexLenght/2; $x++)
        {
            $binString .= chr(hexdec(substr($hexStr,2 * $x - 2,2)));
        }

        return $binString;
    }

    /**
     * encode Hex String
     *
     * @param string $dataCoding
     * @param string $realStr
     * @return string hex string
     */
    public function encodeHexStr($dataCoding, $realStr) {
        return bin2hex($realStr);
    }
}