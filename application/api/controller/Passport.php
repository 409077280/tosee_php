<?php
namespace app\api\controller;

use Firebase\JWT\JWT;
use think\facade\Config;
use app\api\commen\JwtToken;
use app\api\model\Manager as ManagerModel;

class Passport extends BaseController
{

    public function initialize()
    {
        parent::cross();
    }

    public function login()
    {
        if ($this->request->method() != 'POST') {
            return $this->formatResponseDate(1, 'Request method error.');
        }
        // login success!
        $requestData = $this->request->param();
        if (isset($requestData['username']) && isset($requestData['password'])){
            $mobile = $requestData['username'];
            $password = $requestData['password'];
            $model = new ManagerModel;
            $userInfo = $model -> getInfo($mobile, $password);
            if ($userInfo){
                $token = JwtToken::getToken($mobile);
                $data = ['username' => $mobile,'token' => $token];
                return $this->formatResponseDate(0, '', $data);
            }
            return $this->formatResponseDate(1, '用户名或密码不正确', []);
        }
        return $this->formatResponseDate(1, '你的输入的信息不完整', []);
    }

    /**
     * @param $token
     * @return \think\response\Json
     */
    public function logout($token)
    {
        $key = Config::get('jwt.key');
        $info = JWT::decode($token, $key, ["HS256"]); //解密jwt
        return json($info);
    }
}
