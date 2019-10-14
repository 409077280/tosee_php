<?php
namespace app\api\commen;

use Firebase\JWT\JWT;
use think\Exception;
use think\facade\Config;
use think\facade\Request;

class JwtToken {

    /**
     * Get JWT token with userId.
     * @param $userId
     * @return string
     */
    public static function getToken($userId)
    {
        $key     = Config::get('jwt.key');
        $jwtData = [
            //iss => , // jwt Issued person
            //sub => , // jwt target user
            //aud => , // Receiving person
            //jti => , // Unique identification, mainly used as a one-time
            'iat' => Config::get('jwt.iat'), // The issuance of time
            'nbf' => Config::get('jwt.nbf'), // Access after a certain point in time
            'exp' => Config::get('jwt.exp'), // expire time
            'uid' => $userId,
        ];
        $jwtToken = JWT::encode($jwtData, $key, "HS256");
        return $jwtToken;
    }

    /**
     * Check and decode token.
     * @return object
     */
    public static function checkToken()
    {
        $jwt = Request::header('Authorization');
        $key = Config::get('jwt.key');
        $info = JWT::decode($jwt, $key, ["HS256"]);
        return $info;
    }

}