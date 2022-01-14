<?php
namespace catcher\enums;

enum Code : int
{
    case Success = 10000; // 成功
    case Lost_Login = 10001; //  登录失效
    case Validate_Failed = 10002; // 验证错误
    case Permission_Forbidden = 10003; // 权限禁止
    case Login_Failed = 10004; // 登录失败
    case Failed = 10005; // 操作失败
    case Login_Expired = 10006; // 登录失效
    case Login_BlackList = 10007; // 黑名单
    case User_Forbidden = 10008; // 账户被禁
    case Wechat_Response_Error = 40000;
}
