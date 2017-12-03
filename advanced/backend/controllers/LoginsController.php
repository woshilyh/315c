<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\Users;
/**
 * Login controller
 */
class LoginsController extends Controller
{

        public $enableCsrfValidation = false;


        // public function init(){

        //        $this->token="1508phpb";

        // }

        // public function createSignature($token){

        //        $noncestr=Yii::$app->security->generateRandomString();
        //        $timestamp=time();
        //        $signature=sha1(implode('',[$noncestr,$timestamp,$token]));
        //        return ['noncestr'=>$noncestr,'stamp'=>$timestamp,'signature'=>$signature];
        // }
        
        
// 登录        
        public function actionLogin(){
            if(!filter_has_var(INPUT_POST,'token')){
                $res = array(
                    'code'  =>  900,
                    'data'  =>  'TOKEN NOTFOUND'
                 ); 
            }else{
                $code = 'usernamepwd';
                $time = date('Y-m-d H:i');
                $arr = [$code,$time];
                sort($arr);
                $strs = md5($arr['0'].$arr['1']);
                if($strs == yii::$app->request->post('token')){

                    $username=Yii::$app->request->post('username');
                    $password=Yii::$app->request->post('password'); 
                    $db=new Users();
                    $res=$db->find()->where("username='$username' and password='$password'")->one();  
                    if($res){
                         $res = array(
                            'code'  =>  200,
                            'data'  =>   array(
                                    'username'=>$username,
                                    'password'=>$password,
                                )
                         );
                    }else{
                          $res = array(
                            'code'  =>  0,
                            'data'=>'登录失败'
                          );
                    }
                }

            }
              echo json_encode($res);  
        }   
        
// 注册
        public function actionReg(){
            $username=Yii::$app->request->post('username');
            $password=Yii::$app->request->post('password');     
            $tel=Yii::$app->request->post('tel');
            $ema=Yii::$app->request->post('ema');
            $img=Yii::$app->request->post('img');
            $db=new Users();  
            $success=$db->find()->where("username='$username'")->one();  
            if($success){
               //该用户已存在
                $res = array(
                    'code'  =>  0,
                    'data'  =>  "该用户已存在，请重新注册"
                 );
            }else{
                $db->username=$username;
                $db->tel=$tel;
                $db->img=$img;
                $db->ema=$ema;
                $db->password=$password;
                $success=$db->save();
                if($success){
                   $res = array(
                    'code'  =>  200,
                    'data'  =>  array(
                            'username'=>$username,
                            'password'=>$password,
                            'tel'=>$tel,
                            'img'=>$img,
                            'ema'=>$ema
                        )
                 );
                }else{
                    $res = array(
                    'code'  =>  0,
                    'data'  =>  "注册失败"
                    );
                }
            }
               echo json_encode($res);  
        }   

// 修改密码
        public function actionUp(){
            // $username=Yii::$app->request->post('username');
            // $password=Yii::$app->request->post('password');         
            // $newpsd=Yii::$app->request->post('newpsd');
            $username="刘真";
            $password="123456";
            $newpsd="111111";
            $db=new Users();  
            $success=$db->find()->where("username='$username' and password='$password'")->one();
            $uid=$success['uid'];
            $db = new Users();
            $sql=$db->findOne($uid);
            $sql->password=$newpsd;
            $success=$sql->save();
            if($success){
                $res = array(
                    'code'  =>  200,
                    'data'  =>  array(
                            'username'=>$username,
                            'password'=>$newpsd,
                        )
                 );
            }else{
                 $res = array(
                    'code'  =>  0,
                    'data'  =>  "修改失败"
                    );
            }
              echo json_encode($res);  
        }

//测试接口
         public function actionIndex(){
                     if(Yii::$app->request->isPost){
                                $username=Yii::$app->request->post("username");
                                $password=Yii::$app->request->post("password");
                                $ch=curl_init();
                                 curl_setopt_array($ch,[CURLOPT_URL=>"http://localhost/usedcar/backend/web/index.php?r=login/login",CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>['username'=>$username,'password'=>$password],CURLOPT_RETURNTRANSFER=>true]);
                                $res=curl_exec($ch);
                                #var_dump(curl_error($ch));
                                curl_close($ch);
                                echo "登录成功"; 
                    }else{

                                $model=new Users();
                                return $this->render('index',['model'=>$model]);
                    }


         }  


        public function actionLogins(){
           $code = 'usernamepwd';
            $time = date('Y-m-d H:i');
            $arr = [$code,$time];
            sort($arr);
            $str = md5($arr['0'].$arr['1']);
            return $this->render('login',['token'=>$str]);
        } 

}
