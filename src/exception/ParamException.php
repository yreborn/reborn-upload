<?php
/**
 * Created by PhpStorm.
 * User: Reborn
 * Date: 2019/4/18
 * Time: 15:53
 */

namespace reborn\exception;


class ParamException extends Exception
{
    public function __construct()
    {
        parent::__construct("数据错误");
    }
}