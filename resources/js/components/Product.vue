<template>
<div class="body">
    <div class="app">
        <!--詳細表示-->
        <div class="row">
            <!--店画像-->
            <div class="col-md-4"  style="white-space: nowrap">
                <div id="photo">
                    <img v-bind:src="f_photo"><br><br>
                </div>
                <div id="name">
                    <h4>{{ shop_name }}</h4>
                </div>
            </div>
            <!--店詳細-->
            <div class="col-md-8" style="white-space: nowrap">
                <br>
                <p>{{ f_name }} > {{ s_name }}　　<a href="">はしご保存</a></p>
                住所：{{ tel_add }}<br><hr>
                営業時間：{{ time }}<br><hr>
                収容人数：{{ capa }}<br><hr>
                ペット連れ込み：{{ pet }}<br><hr>
                クレジット：{{ credit }}<br><hr>
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
            <div class="col-md-3" style="white-space: nowrap" >
                <div id="tape"></div>
                <div id="products">
                    <table>
                    <tr v-for="(s,id) in marker_items" :key="id">
                        <div v-if="id !== 0 && id !==1">
                            <img v-bind:src="s.photo"><br>
                            <button v-on:click="s_click(id)">{{ s.title }}</button>
                        </div>
                    </tr>
                </table>
                </div>
            </div>
        </div>
            <a id="hot" href="http://webservice.recruit.co.jp/"><img src="http://webservice.recruit.co.jp/banner/hotpepper-s.gif" alt="ホットペッパー Webサービス" width="135" height="17" border="0" title="ホットペッパー Webサービス"></a>
    </div>
</div>
</template> 
<script>
import { METHODS } from 'http';
import axios from 'axios';

export default {
    props:{
        product:String,
        place:String,
        arr:[]
    },

    data () {
        return {
            //追加事項
            name: "",
            url: "",
            photo: "",
            map:{},
            geocode:{},
            address: '',
            center: {lat: 36.71, lng: 139.72},
            zoom: 18,
            marker_items: [],
            icon: {url: "", scaledSize:"", scaledColor: ""},
            id:"",
            lat:"",
            lng:"",
            //送る情報
            detail:"/detail?id=",
            f_lat:"&lat=",
            f_lng:"&lng=",
            //店詳細
            f_photo:"",
            shop_name:"",
            tel_add:"",
            time:"",
            capa:"",
            pet:"",
            credit:"",
            f_name:"",
            s_name:"",
            t_name:""
        }
    },

    //1件目の詳細
    mounted (){
        let json = JSON.parse(this.product)
        console.log(json[0])
        this.center = {lat:parseFloat(json[0].lat), lng:parseFloat(json[0].lng)}
        this.setcentermarker(parseFloat(json[0].lat),parseFloat(json[0].lng))
        this.setshopmarker(JSON.parse(this.place))
        this.f_photo = json[0].photo.pc.l
        this.shop_name = json[0].name
        this.tel_add = json[0].address
        this.time = json[0].open
        this.capa = json[0].capacity
        this.pet= json[0].pet
        this.credit = json[0].card
        //this.setCurrentMarker()
        this.f_name = json[0].name
    },

    methods: {

        // キーワード位置取得
        keywordPosition () {
            return new Promise((resolve,reject)=>{
                this.geocoder = new google.maps.Geocoder();
                this.geocoder.geocode({'address': this.address},(results, status)=>{resolve(results[0].geometry.location)})
            })
         },

        //ピン立て 中央
        setcentermarker(lat,lng){
            this.$refs.map.panTo({lat: lat, lng: lng})
            this.marker_items.push({position: {lat: lat, lng: lng}, title: '現在地'})
        },

        // hotpepperから店情報取得
        getList(lat,lng){
            return axios.post('/api/list',{
                lng: lng,
                lat: lat
            }).then((res)=>{
                console.log(res.data);
                return res.data
            })
        },

        // 現在位置更新
        async setCurrentMarker(){
            let position = await this.currentPosition()
            let lat = position.latitude
            let lng = position.longitude
            this.marker_items.push({position: {lat: lat, lng: lng}, title: '中心地', 
            icon: {url: 'http://pictogram2.com/p/p0957/3.png', scaledSize: new google.maps.Size(50, 55),scaledColor: '#0000'}})
            //this.setcentermarker(lat,lng)
        },
        
        // shoplistピン立て
        setshopmarker(shoplist){
            shoplist.map((shopdata)=>{
            let name = shopdata.name
            let url = shopdata.urls.pc
            let photo = shopdata.photo.pc.l
            let lat = shopdata.lat
            let lng = shopdata.lng
            this.marker_items.push({position: {lat: parseFloat(lat), lng: parseFloat(lng)},        title: name, url: url, photo: photo,
                address:shopdata.address, open:shopdata.open, capacity:shopdata.capacity, pet:shopdata.pet, card:shopdata.card})
            });
        },

        //マーカーの表示内容
        clickMarker(id){
           this.name = this.marker_items[id].title
           this.url = this.marker_items[id].url
           this.photo = this.marker_items[id].photo
           this.id =this.marker_items[id].id
           this.lat =this.marker_items[id].position.lat
           this.lng =this.marker_items[id].position.lng
        },

        //2件目
        s_click(id){
            this.shop_name = this.marker_items[id].title
            this.f_photo = this.marker_items[id].photo
            this.tel_add = this.marker_items[id].address
            this.time = this.marker_items[id].open
            this.capa = this.marker_items[id].capacity
            this.pet = this.marker_items[id].pet
            this.credit = this.marker_items[id].card
            this.s_name = this.marker_items[id].title
        }
    }

}
</script>

<style scoped>
.app {
    margin: 10px 50px;
}
#map {
    width: 100%;
    height: 520px;
} 
.col-md-4 {
    /* 左上の写真・店名の設定 */
    text-align: center;
    padding-left: 100px;
}
#name {
    padding: 10px;
    overflow: auto;
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
.col-mid-3 {
    position: relative;
}
#products {
    width: 100%;
    height: 460px;
    text-align: center;
    background-color: rgb(255, 247, 170);	/* 背景色 */
    border: 1px solid rgb(255, 255, 255); /* 線の太さ・種類・色 */
    box-shadow:1px 1px 6px 0px #ccc;
    -moz-box-shadow:1px 1px 6px 0px #ccc;
    -webkit-box-shadow:1px 1px 6px 0px #ccc;
    -o-box-shadow:1px 1px 6px 0px #ccc;
    margin: 20px 0px; /* 外側の余白 上下・左右 */
    padding: 10px 60px; /* 内側の余白 上下・左右 */
    position: relative;
    z-index: 0;
    overflow-y: scroll;
    
}
#products:before {
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
#tape:after {
    background-color: rgba(250, 178, 232, 0.979);  /* テープ背景色 */
    background-image: radial-gradient(#fff 20%, transparent 0), radial-gradient(#fff 20%, transparent 0); /* 水玉の色 */
    background-position: 0 0, 8px 8px;  /* 水玉の距離 */
    background-size: 15px 15px; /* 水玉の大きさ */
    border-left: 2px dotted rgba(0,0,0,0.1);
    border-right: 2px dotted rgba(0,0,0,0.1);
    box-shadow: 0 0 5px rgba(0,0,0,0.2);
    content: 'はしごリスト';
    display: block;
    margin-left: 110px;
    padding: 5px 20px;  
    text-align: center;
    position: absolute;
    top: 10px;
    left: 20px;
    transform: rotate(-3deg);
    -moz-transform: rotate(-3deg);
    -webkit-transform: rotate(-3deg);
    -o-transform: rotate(-3deg);
    position: absolute;
    z-index: 2;
}
#hot {
    padding-left: 35px;
    padding-top: 10px;
}
</style>