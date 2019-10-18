<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;


class MypageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('mypage',compact('user'));
    }

    public function seach_shop($id)
    {
        $hpg_key = config('apikey.hpg-key');
        $url = "http://webservice.recruit.co.jp/hotpepper/gourmet/v1/?key=".$hpg_key."&id=".$id."&format=json";

        return $this->create_list($url);
    }

    public function create_list($url)
    {
        $json = array();
        $json = file_get_contents($url);
        $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $arr = json_decode($json,true);
        return $arr['results']['shop'];
    }

    public function hashigo_history()
    {
        $user = Auth::user();
        $hashigos = DB::table('hashigo_lists')->get();
        return view('mypage',compact('user','hashigos'));
    }

}
