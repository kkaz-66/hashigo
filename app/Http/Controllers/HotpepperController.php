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
    public function create_list($url)
    {
        $json = array();
        $json = file_get_contents($url);
        $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $arr = json_decode($json,true);
        return $arr['results']['shop'];
    }
    public function seach_range($lat,$lng)
    {
        $hpg_key = config('apikey.hpg-key');
        $url = "http://webservice.recruit.co.jp/hotpepper/gourmet/v1/?key=".$hpg_key."&lat=".$lat."&lng=".$lng."&code=g001&keyword=居酒屋&start=2&range=4&format=json";
        return $this->create_list($url);
    }
    public function seach_shop($id)
    {
        $hpg_key = config('apikey.hpg-key');
        $url = "http://webservice.recruit.co.jp/hotpepper/gourmet/v1/?key=".$hpg_key."&id=".$id."&format=json";
        return $this->create_list($url);
    }

    public function location(Request $request)//変数名Location_clickに変更
    {
        $lat= $request->lat;
        $lng= $request->lng;
        return $this->seach_range($lat,$lng);
    }

    public function detail(Request $request)
    {
        $id= $request->id;
        $lat= $request->lat;
        $lng= $request->lng;
        $product = $this->seach_shop($id);
        $place = $this->seach_range($lat,$lng);
        return view('product',compact('product','place'));
    }

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
