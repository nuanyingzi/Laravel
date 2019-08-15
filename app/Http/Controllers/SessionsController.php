<?php
// 会话控制器
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    //登录界面
    public function create()
    {
        return view('sessions.create');
    }

    //验证用户提交的表单数据
    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password'  =>  'required'
        ]);
        //验证信息是否正确
        if(Auth::attempt($credentials))
        {   
            //成功
            session()->flash('success', '欢迎回来！');
            return redirect()->route('users.show',[Auth::user()]);
        } else {
            //失败
            session()->flash('danger', '抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }
}
