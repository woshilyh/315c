<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\http\Http;


class LoginController extends Controller{
    private $token;
    public function init(){
       $this->token="1508phpb";
     }
    public function createSignature($token){
       $noncestr=Yii::$app->security->generateRandomString();
       var_dump($noncestr);die;
       $timestamp=time();
       $signature=sha1(implode('',[$noncestr,$timestamp,$token]));
       return ['noncestr'=>$noncestr,'stamp'=>$timestamp,'signature'=>$signature];
    }
     public function actionIndex(){

        if(Yii::$app->request->isPost){
            $username=Yii::$app->request->post('username');
            $password=Yii::$app->request->post('password');
          //定义接口
          
           $url = "http://47.94.148.71/advanced/frontend/web/index.php?r=myapp/login";
           $result=Http::getInfoByUrl($url,[CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>['username'=>$username,'password'=>$password]]);
           $result=json_decode($result);  
           if($result['code']==200){
                 Yii::$app->session->set('uid',$result['data']['uid']);
                 $data=Http::getInfoByUrl("http://47.94.148.71/advanced/frontend/web/index.php?r=myapp/message",[CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>['username'=>$username]]);
                 return $data;
                  
             }else{
                 return $this->goBack(); 
               }
        }else{


          return  $this->render("@frontend/views/login");
          }
     }
}
?>
