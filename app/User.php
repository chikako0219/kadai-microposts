<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function microposts(){
    
        return $this->hasMany(Micropost::class);
    }

    public function feed_microposts()
    {
        $favorite_user_ids = $this->favorite()-> pluck('users.id')->toArray();
        return Micropost::whereIn('user_id', $favorite_user_ids);
    }

//Follow機能の追加
    public function followings(){
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    public function followers(){
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }

    public function follow($userId){
    
    // 既にフォローしているかの確認
    $exist = $this->is_following($userId);
    // 自分自身ではないかの確認
    $its_me = $this->id == $userId;

    if ($exist || $its_me) {
        // 既にフォローしていれば何もしない
        return false;
    } else {
        // 未フォローであればフォローする
        $this->followings()->attach($userId);
        return true;
    }
    }

    public function unfollow($userId){

    // 既にフォローしているかの確認
    $exist = $this->is_following($userId);
    // 自分自身ではないかの確認
    $its_me = $this->id == $userId;

    if ($exist && !$its_me) {
        // 既にフォローしていればフォローを外す
        $this->followings()->detach($userId);
        return true;
    } else {
        // 未フォローであれば何もしない
        return false;
    }
    }

    public function is_following($userId) {
    return $this->followings()->where('follow_id', $userId)->exists();
    }




//Favoriteの追加
    public function favorite()
    {
        return $this->belongsToMany(User::class, 'favorite', 'user_id', 'post_id')->withTimestamps();
    }

    public function tofavorite($userId)
    {
    // 既にフォローしているかの確認
    $exist = $this->is_favorite($userId);
    // 自分自身ではないかの確認
    //$its_me = $this->id == $postId;

    if ($exist || $its_me) {
        // 既にフォローしていれば何もしない
        return false;
    } else {
        // 未フォローであればフォローする
        $this->favorite()->attach($postId);
        return true;
    }
    }

    public function unfavorite($userId)
    {
    // 既にフォローしているかの確認
    $exist = $this->is_favorite($postId);
    // 自分自身ではないかの確認
    //$its_me = $this->id == $userId;

    if ($exist && !$its_me) {
        // 既にフォローしていればフォローを外す
        $this->favorite()->detach($postId);
        return true;
    } else {
        // 未フォローであれば何もしない
        return false;
    }
    }

    public function is_favorite($userId) {
    return $this->favorites()->where('post_id', $userId)->exists();
    }


}
