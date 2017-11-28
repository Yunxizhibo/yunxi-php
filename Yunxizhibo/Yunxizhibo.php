<?php
/**
 * Created by PhpStorm.
 * User: holger
 * Date: 2016/8/3
 * Time: 15:39
 */

namespace Yunxizhibo;

/**
 *	云犀直播PHP-SDK, 官方API部分
 *  @author  holger <whjsjq@gmail.com>
 *  @link
 *  @version 1.0
 *  usage:
 *   $accessKey = 'accessKey';  //填写加密用的AccessKey
 *   $secretKey = 'secretKey';  //填写解密用的SecretKey
 *	 $yxObj = new Yunxizhibo($accessKey, $secretKey);
 *
 *   //获取活动列表
 *   $data = $yxObj->getActivityList();
 *
 *  if(empty($data)) {
 *     echo $yxObj->$errCode;
 *     echo $yxObj->$errMsg;
 *  }
 *
 *  $list = $data["activitys"];  //列表
 *
 *  $pageCount = $data["pageCount"]; //页数
 *
 *   //获取活动资料
 *   $data = $yxObj->getActivityInfo();
 *
 *  if(empty($data)) {
 *     echo $yxObj->$errCode;
 *     echo $yxObj->$errMsg;
 *  }
 *
 *  $activity = $data["activity"]; //活动详情
 *
 *
 *
 *   //获取直播视频资料
 *   $data = $yxObj->getLivestreamInfo();
 *
 *  if(empty($data)) {
 *     echo $yxObj->$errCode;
 *     echo $yxObj->$errMsg;
 *  }
 *
 *  $livestream = $data["livestream"];  //直播视频详情
 *
 *  $wechatPlayUrl = $data["wechatPlayUrl"]; //微信嵌入地址
 *
 *  $webPlayUrl = $data["webPlayUrl"]; //网页嵌入地址
 *
 *  $appPlayUrl = $data["appPlayUrl"]; //app嵌入地址
 *
 *  $embedPlayerUrl = $data["embedPlayerUrl"]; //播放器嵌入地址
 *
 * $totalNum = $data["totalNum"]; //围观人数
 *
 * ...
 *
 */
class Yunxizhibo
{
    const API_URL_PREFIX =   'http://b.yunxi.tv/developer/api';
    const ACTIVITY_lIST_URL = '/activity-list?';
    const ACTIVITY_INFO_URL = '/activity-info?';
    const LIVESTREAM_INFO_URL = '/livestream-info?';

    protected $accessKey;
    protected $secretKey;

    public $httpCode;
    public $errCode;
    public $errMsg;

    

    public function __construct($accessKey, $secretKey) {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }


    /**
     * 获取活动列表
     * @param int $page
     * @param int $pageSize
     * @return boolean|array
     */
    public function getActivityList($page=0,$pageSize=20){

        $param = array(
            'page' => $page,
            'pageSize' => $pageSize
        );

        $result = $this->http_post(self::API_URL_PREFIX.self::ACTIVITY_lIST_URL, $param);

        if ($result)
        {
            $json = json_decode($result,true);

            if (!$json || $json['statusCode'] != 200) {
                $this->errCode = $json['statusCode'];
                $this->errMsg = $json['msg'];
                return false;
            }

            return $json['data'];
        }

        return false;
    }


    /**
     * 获取活动资料
     * @param $activityId
     * @return bool|array [id,title,startTime]
     */
    public function getActivityInfo($activityId){
        $param = array(
           'activityId' => $activityId
        );
        $result = $this->http_post(self::API_URL_PREFIX.self::ACTIVITY_INFO_URL, $param);

        if ($result)
        {

            $json = json_decode($result,true);
            if (!$json || $json['statusCode'] != 200) {
                $this->errCode = $json['statusCode'];
                $this->errMsg = $json['msg'];
                return false;
            }


            return $json['data'];

        }
        return false;
    }


    /**
     * 获取直播视频资料
     * @param string $activityId
     * @return boolean|array [id,title,businessId,paid,startTime,createdAt,updatedAt,status]
     */
    public function getLivestreamInfo($activityId){
        $param = array(
            'activityId' => $activityId
        );

        $result = $this->http_post(self::API_URL_PREFIX.self::LIVESTREAM_INFO_URL, $param);

        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || $json['statusCode'] != 200) {
                $this->errCode = $json['statusCode'];
                $this->errMsg = $json['msg'];
                return false;
            }


            return $json['data'];

        }
        return false;
    }

    /**
     * 获取评论列表
     * @param int $page
     * @param int $pageSize
     * @return boolean|array
     */
    public function getCommentsList($lsId, $page=0,$pageSize=20){

        $param = array(
            'lsId' => $lsId,
            'page' => $page,
            'pageSize' => $pageSize
        );

        $result = $this->http_post(self::API_URL_PREFIX.self::COMMENTS_LIST, $param);

        if ($result)
        {
            $json = json_decode($result,true);

            if (!$json || $json['statusCode'] != 200) {
                $this->errCode = $json['statusCode'];
                $this->errMsg = $json['msg'];
                return false;
            }

            return $json['data'];
        }

        return false;
    }


    /**
     * 保存评论
     * @param $lsId
     * @param $content
     * @param $userId
     * @param $avatar
     * @param $username
     * @return bool
     */
    public function saveComment($lsId, $content ,$userId, $avatar, $username){

        $param = array(
            'lsId' => $lsId,
            'content' => $content,
            'userId' => $userId,
            'avatar' => $avatar,
            'username' => $username
        );

        $result = $this->http_post(self::API_URL_PREFIX.self::COMMENTS_LIST, $param);

        if ($result)
        {
            $json = json_decode($result,true);

            if (!$json || $json['statusCode'] != 200) {
                $this->errCode = $json['statusCode'];
                $this->errMsg = $json['msg'];
                return false;
            }

            return $json['data'];
        }

        return false;
    }


    /**
     * 云犀直播api不支持中文转义的json结构
     * @param array $arr
     */
    static function json_encode($arr) {
        $parts = array ();
        $is_list = false;
        //Find out if the given array is a numerical array
        $keys = array_keys ( $arr );
        $max_length = count ( $arr ) - 1;
        if (($keys [0] === 0) && ($keys [$max_length] === $max_length )) { //See if the first key is 0 and last key is length - 1
            $is_list = true;
            for($i = 0; $i < count ( $keys ); $i ++) { //See if each key correspondes to its position
                if ($i != $keys [$i]) { //A key fails at position check.
                    $is_list = false; //It is an associative array.
                    break;
                }
            }
        }
        foreach ( $arr as $key => $value ) {
            if (is_array ( $value )) { //Custom handling for arrays
                if ($is_list)
                    $parts [] = self::json_encode ( $value ); /* :RECURSION: */
                else
                    $parts [] = '"' . $key . '":' . self::json_encode ( $value ); /* :RECURSION: */
            } else {
                $str = '';
                if (! $is_list)
                    $str = '"' . $key . '":';
                //Custom handling for multiple data types
                if (!is_string ( $value ) && is_numeric ( $value ) && $value<2000000000)
                    $str .= $value; //Numbers
                elseif ($value === false)
                    $str .= 'false'; //The booleans
                elseif ($value === true)
                    $str .= 'true';
                else
                    $str .= '"' . addslashes ( $value ) . '"'; //All other things
                // :TODO: Is there any more datatype we should be in the lookout for? (Object?)
                $parts [] = $str;
            }
        }
        $json = implode ( ',', $parts );
        if ($is_list)
            return '[' . $json . ']'; //Return numerical JSON
        return '{' . $json . '}'; //Return associative JSON
    }


    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @return string content
     */
    protected function http_post($url,$params){
        $params['accessKey'] = $this->accessKey;
        $params["timestamp"]  = time();

        $params['sign'] = $this->getSignature($params);

        $oCurl = curl_init();

        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST,true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $params);

        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);

        $this->httpCode = intval($aStatus["http_code"]);

        if($this->httpCode == 200 || $this->httpCode == 400 || $this->httpCode == 401){
            return $sContent;
        }else{
            return false;
        }
    }



    /**
     * 数据签名
     * @param array $arrdata
     * @return string
     */

    public function getSignature($arrdata) {

        ksort($arrdata);
        $paramstring = "";
        foreach($arrdata as $key => $value)
        {
            if(strlen($paramstring) == 0)
                $paramstring .= $key . "=" . $value;
            else
                $paramstring .= "&" . $key . "=" . $value;
        }

        $paramstring.= '&secretKey=' . $this->secretKey;
        $sign = md5($paramstring);
        $sign = strtoupper($sign);

        return $sign;
    }


}
