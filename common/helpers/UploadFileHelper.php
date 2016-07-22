<?php 
namespace common\helpers;
use Yii;
use yii\web\UploadedFile;

/**
* 文件上传助手类
*/
class UploadFileHelper
{
	public $dir = '/';

	private $_model;
	

	public function uploadImage($form, $attribute, $model = null) {
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $image = UploadedFile::getInstance($form, $attribute);

        // if no image was uploaded abort the upload
        if (empty($image)) {
            return false;
        }

        // store the source file name
        $form->{$attribute} = $image->name;
        $ext = end((explode(".", $image->name)));

        // generate a unique file name
        $form->{$attribute} = Yii::$app->security->generateRandomString().".{$ext}";
        if(!empty($model)){
            $model->{$attribute} = $form->{$attribute};
        }
        
        // the uploaded image instance
        return $image;
    }

    /**
     * fetch stored image file name with complete path 
     * @return string
     */
    public function getImageFile($form, $attribute) 
    {
    	try {
    		$this->createDir(Yii::$app->params['uploadPath'] . $this->dir);
        	return isset($form->{$attribute}) ? Yii::$app->params['uploadPath'] . $this->dir . $form->{$attribute} : null;
    	} catch (\Exception $e) {
    		return $e->getMessage();
    	}
    	
    }

    public function deleteImage($model, $attribute) {
        $file = $this->getImageFile($model, $attribute);

        // check if file exists on server
        if (empty($file) || !file_exists($file)) {
            return false;
        }

        // check if uploaded file can be deleted on server
        if (!unlink($file)) {
            return false;
        }

        // if deletion successful, reset your file attributes
        $model->avatar = null;

        return true;
    }

    private function createDir($path){ 
        if (!file_exists($path)){ 
            $this->createDir(dirname($path)); 
            mkdir($path, 0777); 
        } 
    } 
}