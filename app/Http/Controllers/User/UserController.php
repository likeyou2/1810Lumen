<?php

namespace App\Http\Controllers\User;


use App\Model\UserModel;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //登录
    public function login(Request $request){
        
        $name = $request->input('account');
        $pwd = $request->input('password');
        if(empty($name)){
            return '账号不能为空';
        }
        if(empty($pwd)){
            return '密码不能为空';
        }
        var_dump($name);die;
        $data = UserModel::where('name',$name)->first();
        $data = json_decode($data,true);
        if($data['name'] == $name){
            if($data['pwd'] == $pwd){
                echo '登录成功';
            }else{
                echo "账号或密码错误";
                exit;
            }
        }else{
            echo "账号或密码错误";
            exit;
        }
    }

    //注册
    public function register(Request $request){

        $name = $request->input('username');
        $pwd = $request->input('password');
        $pwd_con = $request->input('password_confirm');
        $email = $request->input('email');

        if(empty($name)){ return '账号不能为空'; }
        if(empty($pwd)){ return '密码不能为空'; }  //判断密码不能为空
        if(empty($pwd_con)){ return '确认密码不能为空'; }  //判断密码不能为空
        if(empty($email)){ return '邮箱不能为空'; }  //判断密码不能为空
        if($pwd != $pwd_con){ return '两次密码不一致'; }
       /* echo '成功';
        exit;*/
        $data = UserModel::where('name',$name)->first();
        $data = json_decode($data,true);
        if($data){
            return '账号已存在 请从新输入账号';
        }else{
            $arr = [
                'name' => $name,
                'pwd' => $pwd,
                'email' =>$email
            ];
            $res = UserModel::insertGetId($arr);
            if($res){
                return '注册成功';
            }else{
                return '注册失败';
            }
        }
    }

    public function test(){
        print_r($_POST);
    }
    //修改密码
    public function updatePwd(Request $request){
        $name = $request->input('name'); //账号
        $pwd = $request->input('pwd'); //旧密码
        $newPwd = $request->input('newPwd'); //新密码
        
    }

    public function cuPost(){
        $url = 'http://vm.1810.api.com/register';
        $postData = [
            'name' => 'shuai',
            'pwd' => 123123,
        ];
        //初始化
        $ch = curl_init($url);
        //参数
        curl_setopt($ch,CURLOPT_PORT,0); //指定 提交是post
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0); //TRUE返回到浏览器页面  FALSE返回到变量
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postData); //传输POSRT数据
        //执行
        curl_exec($ch);
        //错误信息
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        var_dump($errno);
        var_dump($error);
        //关闭
        curl_close($ch);
    }

    //解密
    public function decode(){
        $data = file_get_contents('php://input');
        $method = 'AES-128-CBC';
        $key = '1';
        $options = OPENSSL_RAW_DATA;
        $iv = 'qwertyuiopasdfgh';
        $dec_data = openssl_decrypt($data,$method,$key,$options,$iv);
        echo $dec_data;
    }

    //非对称解密
    public function rsa(){
        $data = file_get_contents('php://input');
        $key = openssl_get_publickey('file://'.storage_path('keys/rsa_public_key.pem'));
        openssl_public_decrypt($data,$img,$key);
        return $img;
    }
    //私钥解密
    public function st(){
        $data = $_POST;
        $sign = $data['sign'];
        $sign = base64_decode($sign);
        unset($data['sign']);
        $str = '';
        foreach ($data as $k=>$v){
            $str .= $k .'=' .$v .'&';
        }
        $str = rtrim($str,'&');
        $res = openssl_verify($str,$sign,openssl_get_publickey('file://'.storage_path('keys/rsa_public_key.pem')));
        var_dump($res);
    }

}
