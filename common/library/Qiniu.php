<?php 
namespace common\library;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
use Yii;
/**
* 七牛控制类
*/
class Qiniu 
{

	private $_auth;
	private $_bucketMgr;
	function __construct()
	{
		$this->_auth = new Auth(Yii::$app->params['qiniu']['accessKey'], Yii::$app->params['qiniu']['secretKey']);
		$this->_bucketMgr = new BucketManager($this->_auth);
	}

    public function getUploadToken(){

    }

	/**
	 * 上传图片
	 * @param  string $filePath 
	 * @param  string $key
	 * @return array | boolean 
	 */
	public function uploadImage($filePath, $key = null){

		// 生成上传Token
		$token = $this->_auth->uploadToken(Yii::$app->params['qiniu']['bucket']);

		// 要上传文件的本地路径
	    // $filePath = UPLOAD.'/user/avatar/ENapQwK0Btog_TYv2_ryuG0fGsgIXkjd.jpg';

	    // 上传到七牛后保存的文件名
	    $key = empty($key) ? $this->randomString().'.'.$this->getFileFormat($filePath) : $key;

	    // 初始化 UploadManager 对象并进行文件的上传
	    $uploadMgr = new UploadManager();

	    // 调用 UploadManager 的 putFile 方法进行文件的上传
	    list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);

		if ($err !== null) {
	        return $err;
	    } 
	        
	    return $ret;
	}

    /**
     * @param $file 本地路径文件
     * @param $bucket 上传到七牛空间名
     * @param $key  上传到七牛文件名
     */
    public function uploadFile($file, $bucket, $key){
        // 生成上传Token
        $token = $this->_auth->uploadToken($bucket);

    }

	/**
	 * 获取单个文件信息
	 * @return array | boolean
	 */
	public function getFirstFile($bucket, $key){
		list($ret, $err) = $this->_bucketMgr->stat($bucket, $key);
		if ($err !== null) {
	        return $err;
	    } 
	        
	    return $ret;
	}

	/**
	 * 随机生成字符串文件名
	 * @param  integer $length
	 * @return string
	 */
	private function randomString($length = 16)
	{
	    $string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    return substr(str_shuffle(str_repeat($string, 5)), 0, $length);
	}

	private function getFileFormat($file){
		return pathinfo($file, PATHINFO_EXTENSION);
	}

}