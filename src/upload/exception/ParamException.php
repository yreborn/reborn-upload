<?php
/**
 * Created by PhpStorm.
 * User: Reborn
 * Date: 2019/4/18
 * Time: 9:38
 */

namespace reborn\upload\exception;

class ParamException extends Exception
{
    public function __construct()
    {
        parent::__construct("数据错误");
    }
}