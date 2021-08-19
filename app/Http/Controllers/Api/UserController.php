<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\Api\UserResource;
use App\Jobs\APi\SaveLastTokenJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class UserController extends Controller
{
    //
    public function index() {
        $users = User::paginate(3);
        return UserResource::collection($users);

        // 这里不能用$this->success(UserResource::collection($users))
        // 否则不能返回分页标签信息
        //return $this->success(UserResource::collection($users));
    }

    public function show(User $user) {
        return $this->success(new UserResource($user));
    }

    public function store(UserRequest $request) {
        User::create($request->all());
       return $this->setStatusCode(201)->success('注册成功');
    }

    public function login(Request $request) {
        // 获取当前守护的名称
        $defaultDriver = Auth::getDefaultDriver();
        $token = Auth::claims(['guard' => $defaultDriver])->attempt(['name' => $request->name, 'password' => $request->password]);
        if ($token) {
            $user = Auth::user();
            if ($user->last_token) {
                try {
                    Auth::setToken($user->last_token)->invalidate();
                } catch (TokenExpiredException $e) {
                }
            }

            //$user->last_token = $token;
            //$user->save();
            SaveLastTokenJob::dispatch($user,$token);
            return $this->setStatusCode(201)->success(['token' => 'bearer ' . $token]);
        }

        return $this->failed('登录fail',401);
    }

    public function logout() {
        Auth::logout();
        return $this->success('登出成功。。。');
    }

    public function info() {
        $user = Auth::user();
        return $this->success(new UserResource($user));
    }
}
