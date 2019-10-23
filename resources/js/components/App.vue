<template>
<div class="body">
    <div class="app">
        <!-- <div class="row">
            <div class="col-md-12">

            </div>
        </div> -->
        <div class="row">
            <div class="col-md-12">
                    <button id="search" type="submit" @click="currentsearch">現在地へ移動</button>
                    <input  id="textbox" type="text" v-model="address" placeholder="エリア検索">
                    <button id="area" type="submit" @click="keywordSearch">検索</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
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
            <div class="col-md-3">
                <div>
                    <div v-if="isActive">
                    <!--初期値の店情報を隠す-->
                    </div>

                    <div id="shop" style="white-space: nowrap" v-else>
                        <img v-bind:src="photo"><br>
                        <h3><span class="shopname">{{name}}</span><br></h3><br>
                        <a v-bind:href="url">ホットぺッパー</a><br>
                        
                        <a v-bind:href="detail + id + f_lat + lat + f_lng + lng">{{name}} の詳細ヘ</a>
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
            geocode:{},
            address: '',
            center: {lat: 35.6581, lng: 139.7017},
            zoom: 18,
            marker_items: [],
            icon: {url: "", scaledSize:"", scaledColor: ""},
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
            icon: {url: 'http://pictogram2.com/p/p0957/3.png', scaledSize: new google.maps.Size(50, 55),scaledColor: '#0000'}})
        },

        // hotpepperから店情報取得
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

                this.marker_items.push({position: {lat: parseFloat(lat), lng: parseFloat(lng)},id:id, title: name, url: url, photo: photo})
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
.row {
    padding: 10px;
}
#search {
    display: inline-block;
    padding: 0.5em 1em;
    text-decoration: none;
    background: #668ad8;/*ボタン色*/
    color: #FFF;
    border-bottom: solid 4px #627295;
    border-radius: 3px;
}
#search:active {
    /*ボタンを押したとき*/
    -webkit-transform: translateY(4px);
    transform: translateY(4px);/*下に動く*/
    box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.2);/*影を小さく*/
    border-bottom: none;
}
#textbox {
    width: 360px;
    padding: 5px 8px;
    border-radius: 6px;
    border-top: 1px solid #aaa;
    border-left: 1px solid #aaa;
    border-right: 2px solid #aaa;
    border-bottom: 2px solid #aaa;
    background-image: none;
    background-color: rgb(255, 254, 254);
    font-size: 16px;
}
#area {
    display: inline-block;
    padding: 0.5em 1em;
    text-decoration: none;
    background: #668ad8;/*ボタン色*/
    color: #FFF;
    border-bottom: solid 4px #627295;
    border-radius: 3px;
}
#area:active {
    /*ボタンを押したとき*/
    -webkit-transform: translateY(4px);
    transform: translateY(4px);/*下に動く*/
    box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.2);/*影を小さく*/
    border-bottom: none;
}
.col-md-9 {
    margin-left: 20px;
    margin-right: -20px;
    position: relative;
    border-top: solid 2px black;
    border-bottom: solid 2px black;
}
.col-md-9:before, .col-md-9:after {
    content: '';
    position: absolute;
    top: -10px;
    width: 2px;
    height: -webkit-calc(100% + 20px);
    height: calc(100% + 20px);
    background-color: black;
}
.col-md-9:before {
    left: 10px;
}
.col-md-9:after {
    right: 10px;
}
#map {
    width: 100%;
    height: 750px;
    
} 
.shopname {
    background: linear-gradient(transparent 70%, #ff99ff 70%);
}
#shop {
    text-align: center;
    background-color: rgb(255, 247, 170);	/* 背景色 */
    border: 1px solid rgb(255, 255, 255); /* 線の太さ・種類・色 */
    box-shadow:1px 1px 6px 0px #ccc;
    -moz-box-shadow:1px 1px 6px 0px #ccc;
    -webkit-box-shadow:1px 1px 6px 0px #ccc;
    -o-box-shadow:1px 1px 6px 0px #ccc;
    margin: 20px 5px; /* 外側の余白 上下・左右 */
    padding: 10px; /* 内側の余白 上・右・下・左 */
    position: relative;
    z-index: 0;
}
#shop:before {
    border: 1px solid #fff; /* 白い実線 */
    border-radius: 5px;
    content: '';
    display: block;
    margin: 4px;
    position: absolute;
    top: 0px;
    bottom: 0px;
    left: 0px;
    right: 0px;
    z-index: -1;
}
#shop:after {
    background-color: rgba(250, 178, 232, 0.979);  /* テープ背景色 */
    background-image: radial-gradient(#fff 20%, transparent 0), radial-gradient(#fff 20%, transparent 0); /* 水玉の色 */
    background-position: 0 0, 8px 8px;  /* 水玉の距離 */
    background-size: 15px 15px; /* 水玉の大きさ */
    border-left: 2px dotted rgba(0,0,0,0.1);
    border-right: 2px dotted rgba(0,0,0,0.1);
    box-shadow: 0 0 5px rgba(0,0,0,0.2);
    content: '候補';
    display: block;
    margin-left: 160px;
    padding: 5px 20px;  
    text-align: center;
    position: absolute;
    top: -10px;
    left: 20px;
    transform: rotate(-3deg);
    -moz-transform: rotate(-3deg);
    -webkit-transform: rotate(-3deg);
    -o-transform: rotate(-3deg);
}
#hot {
    padding-left: 35px;
    padding-top: 10px;
}
</style>