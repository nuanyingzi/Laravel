<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class UsersController extends Controller
{
    //创建用户
    public function create()
    {
        return view('users.create');
    }

    //展示个人信息
    public function show(User $user)
    {
        return view('users.show', compact('user'));
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

        Auth::login($user);
        session()->flash('success','注册成功，请开始你的表演~');
        return redirect()->route('users.show', [$user]);
    }

    //用户编辑页面
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    //用户编辑功能
    public function update(User $user, Request $request)
    {
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
}
