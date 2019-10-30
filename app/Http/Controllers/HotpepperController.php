<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HotpepperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //受け取ったデータを配列に変換する
    public function create_list($url)
    {
        $json = array();
        $json = file_get_contents($url);
        $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $arr = json_decode($json,true);
        return $arr['results']['shop'];
    }
    //範囲内の居酒屋を検索する
    public function seach_range($lat,$lng)
    {
        $hpg_key = config('apikey.hpg-key');
        $url = "http://webservice.recruit.co.jp/hotpepper/gourmet/v1/?key=".$hpg_key."&lat=".$lat."&lng=".$lng."&code=g001&keyword=居酒屋&start=2&range=4&count=20&format=json";
        return $this->create_list($url);
    }
    //idから店舗を検索する
    public function seach_shop($id)
    {
        $hpg_key = config('apikey.hpg-key');
        $url = "http://webservice.recruit.co.jp/hotpepper/gourmet/v1/?key=".$hpg_key."&id=".$id."&format=json";
        return $this->create_list($url);
    }
    //位置情報を受け取り範囲検索する
    public function location(Request $request)
    {
        $lat= $request->lat;
        $lng= $request->lng;
        return $this->seach_range($lat,$lng);
    }
    //1軒目のidと1軒目周辺の店舗情報をproductに送る
    public function detail(Request $request)
    {
        $id= $request->id;
        $lat= $request->lat;
        $lng= $request->lng;
        $product = $this->seach_shop($id);
        $place = $this->seach_range($lat,$lng);
        return view('product',compact('product','place'));
    }
    //送られてきた情報を元に2軒目のidと2軒目周辺の店舗情報をproductに送る
    public function thirdSearch(Request $request)
    {
        $id= $request->id;
        $lat= $request->lat;
        $lng= $request->lng;
        $listid = $request->listid;
        $hisname = $request->hisname;
        $product = $this->seach_shop($id);
        $place = $this->seach_range($lat,$lng);
        return view('product',compact('product','place','listid','hisname'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
