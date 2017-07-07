<?php
header('Content-type:text');

define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest;

if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
}else{
    $wechatObj->valid();
}

class wechatCallbackapiTest
{
    //验证签名
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
            echo $echoStr;
            exit;
        }
    }

    //响应消息
    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            //$this->logger("R \r\n".$postStr);
            $postObj = simplexml_load_string($postStr);
            $RX_TYPE = trim($postObj->MsgType);

            //消息类型分离

                    $result = $this->receiveText($postObj);

            //$this->logger("T \r\n".$result);
             echo $result;
        }else {
            echo "";
            exit;
        }
    }



    //接收文本消息
    private function receiveText($object)
    {



        //自动回复模式

        //$content = date("Y-m-d H:i:s",time())."\n\n".'<a href="http://m.cnblogs.com/?u=txw1958">技术支持 uuuuuu</a>';
        $content = $object->FromUserName."   ".$object->ToUserName;


        $result = $this->transmitText($object, $content);

        return $result;
    }







    //回复文本消息
    private function transmitText($object, $content)
    {

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);

        return $result;
    }

}

// 第一次添加代码
?>
