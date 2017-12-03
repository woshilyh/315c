<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SywcController extends Controller
{
  
    public $enableCsrfValidation = false;
    public $layout = 'lay';
    public function actionIndex()
    {
        $code = 'file';
        $time = date('Y-m-d H:i');
        $arr = [$code,$time];
        sort($arr);
        // var_dump($arr);die;
        $str = md5($arr['0'].$arr['1']);
        // echo $str;die;
        // echo '<pre>';
        // print_r($arr);die;
        return $this->render('index',['token'=>$str]);
    }
    //  状态码说明
    const notfound = 360; // 未上传文件
    const type = 361;   // 类型错误
    const success = 200;  //上传成功
    const error = 362;   //上传错误
    //  文件上传的方法
    public function actionFile(){
        if(!yii::$app->request->post('token')){
            $this->return['code'] = self::notfound;
            $this->return['msg'] = 'error';
            $this->return['data']['msg'] = 'TOKEN UNDEFINE'; 
            return $this->jsonss($this->return);die;
        }
        $code = 'file';
        $time = date('Y-m-d H:i');
        $arr = [$code,$time];
        sort($arr);
        $str = md5($arr['0'].$arr['1']);
        if($str == yii::$app->request->post('token')){

            if(empty($_FILES['file']['name'])){
                $this->return['code'] = self::notfound;
                $this->return['msg'] = 'error';
                $this->return['data']['msg'] = 'FILE NOT FOUND'; 
                return $this->jsonss($this->return);
            }else{
                $path = './uploads/'.$_FILES['file']['name'];
                $res = move_uploaded_file($_FILES['file']['tmp_name'], $path);
                if($res){
                    $this->return['data']['msg'] = $path;
                    return $this->jsonss($this->return);
                }else{
                    $this->return['code'] = self::error;
                    $this->return['msg'] = 'error';
                    $this->return['data']['msg'] = 'UPLOAD ERROR'; 
                    return $this->jsonss($this->return);
                }
            }
         
        }


    }
    //  默认的状态
    public $return = array(
        'code'=>'200',
        'msg'=>'success',
        'data'=>array()
    );
    //  返回json数据
    public function jsonss($arr){
        return json_encode($arr);
    }
    


     //  多文件上传
    public function actionMorefile(){
        return $this->render('indexs');
    }
    public function actionMorefiles(){
        if(empty($_FILES['file']['name']['0'])){
            $this->return['code'] = self::notfound;
            $this->return['msg'] = 'error';
            $this->return['data']['msg'] = 'FILE NOT FOUND'; 
            return $this->jsonss($this->return);
        }else{
            $str = '';
            foreach ($_FILES['file']['name'] as $key => $value) {
                $url = "./uploads/".$value;
                $res = move_uploaded_file($_FILES['file']['tmp_name'][$key],$url); 
                if($res){
                    $str .= $url."|";
                }
            }
            if(!empty($str)){
                // rtrim($str,"|");
                $str = substr($str,0,-1);
                // echo $str;die;
                $this->return['data']['msg'] = $str;
                return $this->jsonss($this->return); 
            }else{
                $this->return['code'] = self::error;
                $this->return['msg'] = 'error';
                $this->return['data']['msg'] = 'UPLOAD ERROR'; 
                return $this->jsonss($this->return);
            }
        }
    }
    
}
