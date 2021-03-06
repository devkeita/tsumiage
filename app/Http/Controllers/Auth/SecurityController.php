<?php

namespace App\Http\Controllers\Auth;

use App\Config;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SecurityController extends Controller
{
    /**
     * ユーザーをTwitterの認証ページにリダイレクトする
     */
    public function redirectToProvider()
    {
        if(Auth::check()) {
            return redirect()->route('mypage');
        }

        return Socialite::driver('twitter')->redirect();
    }

    /**
     * Twitterからユーザー情報を取得する
     */
    public function handleProviderCallback()
    {
        if(Auth::check()) {
            redirect()->route('mypage');
        }

        try {
            $user = Socialite::driver('twitter')->user();
        } catch (\Exception $e) {
            return redirect()->route('home');
        }

        $authUser = $this->findOrCreateUser($user);
        Auth::login($authUser, true);

        return redirect()->route('mypage');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('home');
    }

    private function findOrCreateUser($user)
    {
        $newUser = false;
        if (!$authUser = User::where('twitter_id', $user->id)->first()) {
            $authUser = new User();
            $newUser = true;
        }
        $authUser->name = $user->name;
        $authUser->nickname = $user->nickname;
        $authUser->twitter_id = $user->id;
        $authUser->avatar = $user->avatar;
        $authUser->twitter_token = $user->token;
        $authUser->twitter_token_secret = $user->tokenSecret;
        $authUser->save();

        if ($newUser) {
            $authUser->config()->save(new Config());
        }

        return $authUser;
    }
}
