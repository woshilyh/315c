<?php
namespace frontend\controllers;
use Yii;
use yii\web\controller;
use yii\web\Response;
class MyappController extends Controller{
       private $token;
       public function init(){
            Yii::$app->response->format = Response::FORMAT_JSON;
             $this->token='123456';
             file_put_contents('./myapp',json_encode($_GET));
       }
      protected function checkSignature(){
         $noncestr=Yii::$app->request->get("noncestr");
         $timestamp=Yii$app->request->get("stamp");
         $fromsignature=Yii::$app->request->get("signature");
         $signature=sha1(implode('',[$noncestr,$timestamp,$this->token]));
         if($fromsignature!=$signature){
            return false;
         }
            return true;
     }
      public function actionLogin(){
          if(!$this->checkSignature()){
             return ['code'=>500,'message'=>'no answers','data'=>''];
           }  
          $username=Yii::$app->request->post('username');
          $password=Yii::$app->request->post('password');


          return [
                'code'=>200,
               'message'=>'success',
               'data'=>['uis'=>5]

           ];
        }   
      public function actionMessage(){
         if(!$this->checkSignature()){
           return ['code'=>500,'message'=>'no','data'=>''];
         }
        $username=Yii$app->request->post('username');
        $result=Yii::$app->db->createCommand("select * from userinfo where username='$username'")->queryOne();
        var_dump($result);die;
        return [
             'code'=>200,
             'message'=>'success',
             'data'=>$result
           ];
      }
}
?>
