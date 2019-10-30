<template>
<div class="body">
    <div class="app">
        <div class="row">
            <div class="col-xs-12 col-lg-12">
                    <button id="search" type="submit" @click="currentsearch">現在地へ移動</button>
                    <input  id="textbox" type="text" v-model="address" placeholder="エリア検索">
                    <button id="area" type="submit" @click="keywordSearch"></button>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-lg-9">
                <div id="map">
                    <GmapMap :center="center" :zoom="zoom" style="width: 100%; height: 100%;" ref="map">
                        <GmapMarker  v-for="(m,id) in marker_items"
                            :position="m.position"
                            :title="m.title"
                            :url="m.url"
                            :icon="m.icon"
                            :clickable="true" :draggable="false" :key="id" @click="clickMarker(id)">
                        </GmapMarker>
                    </GmapMap>
                </div>
            </div>
            <div class="col-xs-2 col-lg-3">
                <div>
                    <div v-if="isActive">
                    <!--初期値の店情報を隠す-->
                    </div>

                    <div id="shop" v-else>
                        <div id ="box">
                            <img v-bind:src="photo"><br>
                            <h3><span class="shopname">{{name}}</span></h3><br>
                            <a v-bind:href="url" target="_blank">ホットぺッパー</a><br>
                            予算：{{ budget }}<br>
                            交通：{{ access }}<br>
                            <a v-bind:href="detail + id + f_lat + lat + f_lng + lng">{{name}} の詳細ヘ</a>
                        </div>
                    </div>
                </div>
            </div>
            <a id="hot" href="http://webservice.recruit.co.jp/"><img src="http://webservice.recruit.co.jp/banner/hotpepper-s.gif" alt="ホットペッパー Webサービス" width="135" height="17" border="0" title="ホットペッパー Webサービス"></a>
        </div>
    </div>
</div>
</template> 
<script>
import { METHODS } from 'http';
import axios from 'axios';

export default {
    
    data () {
        return {
            name: "",
            url: "",
            photo: "",
            map:{},
            isActive: true,
            id:"",
            lat:"",
            lng:"",
            //送る情報
            detail:"/detail?id=",
            f_lat:"&lat=",
            f_lng:"&lng=",
            //緯度経度
            geocode:{},
            address: '',
            center: {lat: 35.6581, lng: 139.7017},
            zoom: 18,
            marker_items: [],
            icon: {url: "", scaledSize:"", scaledColor: ""},
            //お店表示内容
            budget:"",
            access:"",
        }
    },
    methods: {
        //現在地取得
        currentPosition () {
            return new Promise(function(resolve,reject){
                navigator.geolocation.getCurrentPosition((position)=>{resolve(position.coords)})
            })
        },

        // キーワード位置取得
        keywordPosition () {
            return new Promise((resolve,reject)=>{ 
                this.geocoder = new google.maps.Geocoder();
                this.geocoder.geocode({'address': this.address},(results, status)=>{resolve(results[0].geometry.location)})
                this.isActive = true;
            })
         },

        //ピン立て 中央
        setcentermarker(lat,lng){
            this.marker_items=[];
            this.$refs.map.panTo({lat: lat, lng: lng})
            this.marker_items.push({position: {lat: lat, lng: lng}, title: '中心地', 
            icon: {url: 'http://pictogram2.com/p/p0957/3.png', scaledSize: new google.maps.Size(70, 75),scaledColor: '#0000'}})
        },

        // hotpepperから店情報取得(ほんとはPOSTlist)
        getList(lat,lng){
            return axios.post('/api/location',{
                lng: lng,
                lat: lat
            }).then((res)=>{
                console.log(res.data);
                return res.data
            })
        },

        // 現在位置取得
        async currentsearch(){
            let position = await this.currentPosition()
            let lat = position.latitude
            let lng = position.longitude
            let shoplist = await this.getList(lat,lng)
            this.setcentermarker(lat,lng)
            this.setshopmarker(shoplist)
        },

        // shoplistピン立て
        setshopmarker(shoplist){
            shoplist.map((shopdata)=>{
                let name = shopdata.name
                let url = shopdata.urls.pc
                let photo = shopdata.photo.pc.l
                let lat = shopdata.lat
                let lng = shopdata.lng
                let id = shopdata.id

                this.marker_items.push({position: {lat: parseFloat(lat), lng: parseFloat(lng)},id:id, title: name, url: url, photo: photo,
                budget:shopdata.budget.name, access:shopdata.mobile_access,
                icon: {url: 'http://maps.google.co.jp/mapfiles/ms/icons/red-dot.png',scaledSize:{width:40,height:40} ,scaledColor: '#0000'}})
            });
        },
        // 検索ボタンclick発火
       async keywordSearch(){
            let keyword_position = await this.keywordPosition()
            let lat = keyword_position.lat()
            let lng = keyword_position.lng()
            let shoplist = await this.getList(lat,lng)
            this.setcentermarker(lat,lng)
            this.setshopmarker(shoplist)
        },

        //マーカーの表示内容
        clickMarker(id){
            this.name = this.marker_items[id].title
            this.url = this.marker_items[id].url
            this.photo = this.marker_items[id].photo
            this.id =this.marker_items[id].id
            this.lat =this.marker_items[id].position.lat
            this.lng =this.marker_items[id].position.lng
            this.budget = this.marker_items[id].budget
            this.access = this.marker_items[id].access
            if(this.marker_items[id].title == '中心地'){
                this.isActive = true
            }else{
                this.isActive = false
            }
        },
    }
}
</script>

<style scoped>
button{
    border:none;
    outline:none;
}

.body {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    z-index: -1;
    /* background-size: contain; */
    /* background-image: url('https://i.pinimg.com/564x/5e/4e/ab/5e4eab5e15f0f7b38ce23b91ef28c49f.jpg'); */
}

.app {
    padding: 0 50px;
    margin-top: 60px;
    width: 100%;
}

.col-lg-12 {
    position: relative;
    margin: 10px 0;
}
/* 現在地ボタン */
.col-lg-12 #search {
    display: inline-block;
    width: 10%;
    margin-right: -10px;
    padding: 0.5em 1em;
    text-decoration: none;
    background: #668ad8;/*ボタン色*/
    color: #FFF;
    /* border-bottom: solid 4px #627295;
    border-radius: 3px; */
}
.col-lg-12 #search:active {
    /*ボタンを押したとき*/
     -webkit-transform: translateY(4px);
    transform: translateY(4px);/*下に動く*/
    box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.2);/*影を小さく*/
    border-bottom: none; 
}
/* エリア入力欄 */
.col-lg-12 #textbox {
    width: 30%;
    padding: 5px 8px;
    /* border-radius: 6px; */
    border-top: 1px solid #aaa;
    border-left: 1px solid #aaa;
    border-right: 1px solid #aaa;
    border-bottom: 1px solid #aaa;
    background-image: none;
    background-color: rgb(255, 254, 254);
    font-size: 16px;
}
/* エリア検索ボタン */
.col-lg-12 #area {
    position: absolute;
    /* right: 0; */
    width: -50%;
    display: inline-block;
    padding: 0.5em 1em;
    text-decoration: none;
    background: #668ad8;/*ボタン色*/
    color: #FFF;
    /* border-bottom: solid 4px #627295; */
    /* border-radius: 3px; */
}
.col-lg-12 #area:active {
    /*ボタンを押したとき*/
    -webkit-transform: translateY(4px);
    transform: translateY(4px);/*下に動く*/
    box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.2);/*影を小さく*/
    border-bottom: none;
}

/* マップ表示欄 */
.col-lg-9 {
    margin-top: 10px;
    margin-left: 20px;
    margin-right: -20px;
    position: relative;
    padding: 5px 5px 15px 5px;;
    width: 90%; /* ボックス幅 */
    height: 780px;
    background-color: #ffffff; /* ボックス背景色 */
    color: #000; /* 文章色 */
    border: 5px solid #bd1818; /* 枠線 */
    border-radius: 3px; /* 角の丸み */
    box-shadow: 0 0 8px #333, 0 0 2px #555 inset;
}
.col-lg-9:before, .col-lg-9:after {
    position: absolute;
    content: '';
    width: 25px; 
    bottom: 3px;
    border-radius: 2px;
    box-shadow: 1px 1px 3px #666;
}
.col-lg-9:before {
    right: 55px;
    border: solid 3px #333333; /*飾ペン黒 */
}
.col-lg-9:after {
    right: 20px;
    border: solid 3px #ff42a0; /*飾ペンピンク */
    transform: rotate(8deg); /*飾ペン角度 */
}
/* マップ設定 */
.col-lg-9 #map {
    width: 100%;
    height: 750px;
}

/* 店舗情報表示欄 */
.col-lg-3 #box {
    text-align: center;
    position: relative;
    background: #ffd000;
    padding: 10px;
    border: 5px solid #bd1818;
    box-shadow: 2px 2px 4px #999, 2px 2px 2px #020 inset;
    margin-top: 10px;
    margin-left: 50px;
    font-size: 1rem;
    font-weight: bold;
    width: 350px;
    height: 550px;
    white-space: pre-line;
}

/* 店舗名設定  */
.shopname {
    font-weight: bold;
    margin-top: 10px;
    background: linear-gradient(transparent 70%, #ff99ff 70%);
}

/* ホットペッパーロゴ */
#hot {
    padding-left: 35px;
    padding-top: 10px;
}


/* スマホ対応 */
@media screen and (max-width:781px){
    .app {
        padding: 0 20px;
        margin-top: 60px;
        width: 100%;
    }

    .col-xs-12 {
        width: 100%;
        margin: 10px 0;
    }

    /* 現在地検索ボタン */
    .col-xs-12 #search {
        width: 10%;
        display: inline-block;
        padding: 0.5em 1em;
        text-decoration: none;
        background: #668ad8;/*ボタン色*/
        color: #FFF;
        /* border-bottom: solid 4px #627295;
        border-radius: 3px; */
    }
    /* .col-xs-12 #search:active {
        /*ボタンを押したとき*/
        /* -webkit-transform: translateY(4px); */
        /* transform: translateY(4px);下に動く */
        /* box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.2);影を小さく */
        /* border-bottom: none; */
    /* }  */

    /* エリア入力欄 */
    .col-xs-12 #textbox {
        width: 30%;
        padding: 5px 8px;
        /* border-radius: 6px; */
        border-top: 1px solid #aaa;
        border-left: 1px solid #aaa;
        border-right: 2px solid #aaa;
        border-bottom: 2px solid #aaa;
        background-image: none;
        background-color: rgb(255, 254, 254);
        font-size: 16px;
    }

    /* エリア検索ボタン */
    .col-xs-12 #area {
        position: absolute;
        /* right: 0; */
        width: -50%;
        display: inline-block;
        padding: 0.5em 1em;
        text-decoration: none;
        background: #668ad8;/*ボタン色*/
        color: #FFF;
        /* border-bottom: solid 4px #627295;
        border-radius: 3px; */
    }
    .col-xs-12 #area:active {
        /*ボタンを押したとき*/
        -webkit-transform: translateY(4px);
        transform: translateY(4px);/*下に動く*/
        box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.2);/*影を小さく*/
        border-bottom: none;
    }

    /* マップ表示欄設定 */
    .col-xs-6 {
        margin-top: 10px;
        margin-left: 20px;
        margin-right: -20px;
        position: relative;
        padding: 5px 5px 15px 5px;;
        width: 90%; /* ボックス幅 */
        height: 350px;
        background-color: #ffffff; /* ボックス背景色 */
        color: #000; /* 文章色 */
        border: 5px solid #bd1818; /* 枠線 */
        border-radius: 3px; /* 角の丸み */
        box-shadow: 0 0 8px #333, 0 0 2px #555 inset;
    }
    .col-xs-6:before , .col-xs-6:after {
        position: absolute;
        content: '';
        width: 25px; 
        bottom: 3px;
        border-radius: 2px;
        box-shadow: 1px 1px 3px #666;
    }
    .col-xs-6:before {
        right: 55px;
        border: solid 3px #333333; /*飾ペン黒 */
    }
    .col-xs-6:after {
        right: 20px;
        border: solid 3px #ff42a0; /*飾ペンピンク */
        transform: rotate(8deg); /*飾ペン角度 */
    }
    /* マップ設定 */
    .col-xs-6 #map {
        width: 100%;
        height: 330px;
        
    } 

    /* 店舗情報表示欄 */
    .col-xs-2 #box {
        text-align: center;
        position: relative;
        background: #ffd000;
        padding: 10px;
        border: 5px solid #bd1818;
        box-shadow: 2px 2px 4px #999, 2px 2px 2px #020 inset;
        margin-top: 10px;
        margin-left: 5px;
        font-size: 1rem;
        font-weight: bold;
        width: 100%;
        height: 550px;
        white-space: pre-line;
    }
}
</style>