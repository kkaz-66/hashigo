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
        $url = "http://webservice.recruit.co.jp/hotpepper/gourmet/v1/?key=".$hpg_key.$id."&format=json";
        $hashigo_arr = $this->create_list($url);
        return $hashigo_arr;
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
        $hashigos = DB::table('hashigo_lists')->where('member_id',$user->id)->get();
        $url_ids = "";
        $user_history = array();
        if(count($hashigos) != 0){
            foreach($hashigos as $hashigo){
                
                $first = "&id=".$hashigo->first_store_id;
                $second = "&id=".$hashigo->second_store_id;
                $third = mb_strlen($hashigo->third_store_id) != 0?"&id=".$hashigo->third_store_id:'';
                // var_dump($hashigo->third_store_id);
                // var_dump(gettype($hashigo->third_store_id));
                // var_dump($third);
                // var_dump(mb_strlen($hashigo->third_store_id));
                $url_ids = $url_ids.$first.$second.$third;
            }
            // var_dump($url_ids);
            // exit;

            $hashigo_shops = $this->seach_shop($url_ids);
            // var_dump($hashigo_shops);
            // exit;
            
            foreach($hashigos as $hashigo){
                
                $user_history[$hashigo->id]["date"]= $hashigo->created_at;
                foreach($hashigo_shops as $hashigo_shop){
                
                    if($hashigo->first_store_id === $hashigo_shop["id"]){

                        $user_history[$hashigo->id]["first"] = $hashigo_shop;
                    }
                    if($hashigo->second_store_id === $hashigo_shop["id"]){
                
                        $user_history[$hashigo->id]["second"] = $hashigo_shop;
                    }
                    if($hashigo->third_store_id === $hashigo_shop["id"]){
                
                        $user_history[$hashigo->id]["third"] = $hashigo_shop;
                    }
                }
            }
        }
        // var_dump($user_history);
        // exit;
        return view('mypage',compact('user_history'));
    }
    public function insert(Request $request){
        $first=$request->f_id;
        $second=$request->s_id;
        $user_id = $request->userid;
        DB::table('hashigo_lists')->insert(['member_id'=>(int)$user_id,'first_store_id'=>$first,'second_store_id'=>$second,'created_at'=>NOW()]);
        return $user_id;
    }
    public function third_insert(){
        $third=$request->t_id;
        $id=$request->id;
        DB::table('hashigo_lists')
        ->where('id',$id)
        ->update(['third_store_id'=>$third]);
        return true;
    }
        // $created_at=DB::table('hashigo_lists')->orderby('created_at','desc')->limit(6)->get();
        // var_dump($created_at);
}
