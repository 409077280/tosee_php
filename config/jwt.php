<?php

return [
    "key" => "tosee",//加盐
    "iat" => time(), //签发时间
    "nbf" => time(), //生效时间
    "exp" => time() + 3600 * 24, //token 过期时间
    //"exp" => time() + 1, //token 过期时间
];