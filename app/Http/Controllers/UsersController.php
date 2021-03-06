<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Mail;

class UsersController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);
        //只让未登录用户访问注册页面
        $this->middleware('guest', [
            'only'  =>  ['create']
        ]);
    }

    //创建用户
    public function create()
    {
        return view('users.create');
    }

    //展示个人信息
    //我的微博动态
    public function show(User $user)
    {
        $statuses = $user->statuses()->orderBy('created_at', 'desc')->paginate(10);
        return view('users.show', compact('user', 'statuses'));
    }

    //用户创建
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|max:50',
            'email'     => 'required|email|unique:users|max:255',
            'password'  => 'required|confirmed|min:6'
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>  bcrypt($request->password)
        ]);

        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮箱已发送到您的注册邮箱，请注意查收。');
        return redirect('/');
        // Auth::login($user);
        // session()->flash('success','注册成功，请开始你的表演~');
        // return redirect()->route('users.show', [$user]);
    }

    //用户编辑页面
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    //用户编辑功能
    public function update(User $user, Request $request)
    {
        $this->authorize('update', $user);
        $this->validate($request, [
            'name'      => 'required|max:50',
            'password'  => 'nullable|confirmed|min:6'
        ]);
        
        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user->id);
    }

    //用户列表
    public function index()
    {
        $users = User::paginate(5);
        return view('users.index',compact('users'));
    }

    //用户删除
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户');
        return back();
    }

    //确认邮箱
    protected function sendEmailConfirmationTo($user)
    {
        $view    =   'emails.confirm';
        $data    =   compact('user');
        $from    =   '852947475@qq.com';
        $name    =   'Tao';
        $to      =   $user->email;
        $subject =   "感谢注册Weibo应用，请确认您的邮箱。";
        
        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject){
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    //用户激活
    public function confirmEmail($token) 
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated  = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，账号激活成功！');
        return redirect()->route('users.show', [$user]);
    }

    //用户关注人列表
    public function followings(User $user)
    {
        $users = $user->followings()->paginate(10);
        $title = $user->name.'关注的人';
        return view('users.show_follow', compact('users', 'title'));
    }

    //粉丝列表
    public function followers(User $user)
    {
        $users = $user->followers()->paginate(10);
        $title = $user->name.'的粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }


}
