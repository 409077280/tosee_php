<?php
namespace app\api\controller;
use think\Controller;
use app\api\commen\JwtToken;

class BaseController extends Controller{

    protected $userId;
    protected $errorMsg;
    protected function initialize()
    {
        $this->cross();
        if (!$this->getUserId()){
        }
    }

    /**
     *  Cross Domain
     */
    protected function cross(){
        if (isset($_SERVER['HTTP_ORIGIN'])){
            $origin = $_SERVER['HTTP_ORIGIN'];
            header('Content-Type:application/json; charset=utf-8');
            header("Access-Control-Allow-Origin: {$origin}");
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
            header('Access-Control-Allow-Headers: Accept, Authorization, X-Requested-With, Content-Type, X-XSRF-TOKEN, Cache-Control, Origin, token');
            header('Access-Control-Max-Age: 86400'); //CORS cache, in 24 hour not need send options access.
            header('Access-Control-Allow-Credentials: true');
        }
        if ($this->request->method() == 'OPTIONS'){
            die();
        }
        return;
    }

    private function getUserId(){
        try{
            $info = JwtToken::checkToken();
            $this->userId = $info->uid;
        }catch (\Exception $e) {
            $this->errorMsg = $e->getMessage();
            http_response_code(401);
            die();
        }
    }

    protected function formatResponseDate($code, $msg, $data = null){
        return json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
    }
}