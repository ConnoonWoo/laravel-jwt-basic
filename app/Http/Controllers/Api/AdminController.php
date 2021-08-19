<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AdminRequest;
use App\Http\Resources\Api\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AdminController extends Controller
{
    //
    public function index() {
        $admin = Admin::paginate(3);
        return AdminResource::collection($admin);

        // 这里不能用$this->success(AdminResource::collection($admin))
        // 否则不能返回分页标签信息
        //return $this->success(AdminResource::collection($admin));
    }

    public function show(Admin $admin) {
        return $this->success(new AdminResource($admin));
    }

    public function store(AdminRequest $request) {
        Admin::create($request->all());
       return $this->setStatusCode(201)->success('注册成功');
    }

    public function login(Request $request) {
        // 获取当前守护的名称
        $defaultDriver = Auth::getDefaultDriver();
        $token = Auth::claims(['guard' => $defaultDriver])->attempt(['name' => $request->name, 'password' => $request->password]);
        if ($token) {
            // 如果登陆，先检查原先是否有存token，有的话先失效，然后再存入最新的token
            $user = Auth::user();
            if ($user->last_token) {
                try {
                    Auth::setToken($user->last_token)->invalidate();
                } catch (TokenExpiredException $e) {
                    // 因为让一个过期的token再失效，会抛出异常，所以我们捕捉异常，不需要做任何处理
                }
            }
            $user->last_token = $token;
            $user->save();
            return $this->setStatusCode(201)->success(['token' => 'bearer ' . $token]);
        }

        return $this->failed('登录fail',401);
    }

    public function logout() {
        Auth::logout();
        return $this->success('登出成功。。。');
    }

    public function info() {
        $admin = Auth::user();
        return $this->success(new AdminResource($admin));
    }
}
