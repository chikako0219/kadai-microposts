<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function counts($user) {
        $count_microposts = $user->microposts()->count();
        $count_favorite = $user->favorite()->count();
        //$count_followers = $user->followers()->count();

        return [
            'count_microposts' => $count_microposts,
            'count_favorite' => $count_favorite,
            //'count_followers' => $count_followers,
        ];
    }
}