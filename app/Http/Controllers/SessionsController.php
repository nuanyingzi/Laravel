<?php
// 会话控制器
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        //只让未登录用户访问登录页面
        $this->middleware('guest',[
            'only'  =>  ['create'] 
        ]);
    }
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
        if(Auth::attempt($credentials, $request->has('remember')))
        {   
            //成功
            session()->flash('success', '欢迎回来！');
            $fallback = route('users.show',Auth::user());
            return redirect()->intended($fallback);
        } else {
            //失败
            session()->flash('danger', '抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }

    //注销
    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '注销成功!');
        return redirect('login');
    }
}
