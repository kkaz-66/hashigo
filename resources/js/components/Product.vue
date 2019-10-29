<template>
<div class="body">
    <div class="app">
        <!--詳細表示-->
        <div class="row">
            <!--店画像-->
            <div class="col-xs-4 col-lg-4">
                <div id="photo">
                    <img v-bind:src="f_photo"><br>
                </div>
                <div id="name">
                    <p>{{ shop_name }}</p>
                    <span class="pan_space"> URL：<a v-bind:href="o_url" target="_blank">{{ shop_name }}の公式</a></span>
                </div>
            </div>
            <!--店詳細-->
            <div class="col-xs-8 col-lg-8">
                <div v-if="!hisname">
                    <span class="pan_name">{{ f_name }}</span> 
                    <span class="pan_space">></span>
                    <span class="pan_name">{{ s_name }}</span><br>
                    <!--非ログインとログイン-->
                    <div v-if="isActive">
                        <button id="hashigo_save" v-on:click="alert" v-bind:disabled="alert_click">はしご保存</button>
                    </div>
                    <div v-else>
                        <button id="hashigo_save" v-bind:disabled="insertClick" v-on:click="insertList(f_id,s_id,userid)">はしご保存</button>
                    </div>
                </div>
                <div v-else>
                    <span class="pan_name">{{ f_name }}</span> 
                    <span class="pan_space">></span>
                    <span class="pan_name">{{ second_name }}</span>
                    <span class="pan_space">></span>
                    <span class="pan_name">{{ s_name }}</span><br>
                    <div v-if="isActive"></div>
                    <div v-else>
                        <button id="hashigo_save" v-bind:disabled="insertClick" v-on:click="t_save(s_id,listid)">三軒目保存</button>
                    </div>
                </div>
                <br>
                <div id="shop_info">
                    住所：{{ tel_add }}<br><hr>
                    営業時間：{{ time }}<br><hr>
                    収容人数：{{ capa }}  /  クレジット：{{ credit }}<br><hr>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-9 col-lg-9">
                <div id="map">
                    <GmapMap :center="center" :zoom="zoom" style="width: 100%; height: 100%;" ref="map">
                        <GmapMarker  v-for="(m,id) in marker_items"
                            :position="m.position"
                            :title="m.title"
                            :url="m.url"
                            :icon="m.icon"
                            :clickable="true" :draggable="false" :key="id" @click="clickMarker(id)" ref ="icon">
                        </GmapMarker>
                    </GmapMap>
                </div>
            </div>
            <div class="col-xs-3 col-lg-3" >
                <div id="tape"></div>
                <div id="products">
                    <table>
                    <tr v-for="(s,id) in marker_items" :key="id">
                        <div v-if="id >= 2">
                            <img v-bind:src="s.photo"><img><br>
                            <button  id="detail" v-on:click="s_click(id)">{{ s.title }}</button><hr>
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
        arr:[],
        userid:String,
        listid:String,
        hisname: String,
    },

    data () {
        return {
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
            credit:"",
            //一件目
            f_name:"",
            //二件目と三件目
            s_name:"",
            //URL＆アイコン関連
            o_url:"",
            b_id:null,
            //postするid
            f_id:"",
            s_id:"",
            t_id:"",
            //ボタンのクリックアクション
            position_id:0,
            insertClick:true,
            isActive:true,
            alert_click:false,
            second_name:"",
        }
    },

    //1件目の詳細
    mounted (){
        let json = JSON.parse(this.product)
        this.center = {lat:parseFloat(json[0].lat), lng:parseFloat(json[0].lng)}
        this.setcentermarker(parseFloat(json[0].lat),parseFloat(json[0].lng))
        this.setshopmarker(JSON.parse(this.place))
        this.f_photo = json[0].photo.pc.l
        this.shop_name = json[0].name
        //２件目
        this.second_name= json[0].name

        this.tel_add = json[0].address
        this.time = json[0].open
        this.capa = json[0].capacity
        this.credit = json[0].card
        this.o_url = json[0].urls.pc
        //パンくずリスト一件目（固定）
        if(!this.hisname){
            this.f_name = json[0].name
        }else{
            this.f_name = this.hisname
        }
        //ボタンの可視化
        this.f_id =json[0].id
        if(this.userid !== ""){
            this.isActive = false
        }
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
            //2軒目と3軒目で、現在地のicon変更
            if(!this.hisname){
                this.marker_items.push({position: {lat: lat, lng: lng}, title: '現在地', icon: {url: 'http://pictogram2.com/p/p0115/1.png',scaledSize:{width:70,height:75} ,scaledColor: '#0000'}})
            }else{
                this.marker_items.push({position: {lat: lat, lng: lng}, title: '現在地', icon: {url: 'http://pictogram2.com/p/p0958/3.png',scaledSize:{width:70,height:75} ,scaledColor: '#0000'}})
            }
        },

        // hotpepperから店情報取得
        getList(lat,lng){
            return axios.post('/api/list',{
                lng: lng,
                lat: lat
            }).then((res)=>{
                return res.data
            })
        },

        // shoplistピン立て
        setshopmarker(shoplist){
            shoplist.map((shopdata)=>{
            let name = shopdata.name
            let url = shopdata.urls.pc
            let photo = shopdata.photo.pc.l
            let lat = shopdata.lat
            let lng = shopdata.lng
            this.marker_items.push({position: {lat: parseFloat(lat), lng: parseFloat(lng)}, title: name, url: url, photo: photo,
                address:shopdata.address, open:shopdata.open, capacity:shopdata.capacity, card:shopdata.card, id:shopdata.id,
                icon: {url: 'http://maps.google.co.jp/mapfiles/ms/icons/red-dot.png',scaledSize:{width:40,height:40} ,scaledColor: '#0000'}
                , button:false})
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
        
        //非ログイン時のボタン
        alert(){
            alert('ログインしてください')
            this.alert_click = true
        },

        //2件目
        s_click(id){
            this.shop_name = this.marker_items[id].title
            this.f_photo = this.marker_items[id].photo
            this.o_url = this.marker_items[id].url
            this.tel_add = this.marker_items[id].address
            this.time = this.marker_items[id].open
            this.capa = this.marker_items[id].capacity
            this.credit = this.marker_items[id].card
            this.s_name = this.marker_items[id].title
            this.s_id = this.marker_items[id].id
            //ボタンの押す押せない
            this.position_id = id
            this.insertClick = this.marker_items[id].button

            //2件目、マーカー色チェンジ
            if(this.b_id !== null){
                this.$refs.icon[this.b_id].$markerObject.icon.url = 'http://maps.google.co.jp/mapfiles/ms/icons/red-dot.png'
            }
            this.$refs.icon[id].$markerObject.icon.url = 'http://maps.google.co.jp/mapfiles/ms/icons/ltblue-dot.png'
            this.$refs.map.panTo({lat: this.marker_items[id].position.lat, lng: this.marker_items[id].position.lng})
            this.b_id = id
        },

        //保存ボタン
        insertList(f_id, s_id,userid){
            //ボタンを連続で押せなくする
            this.marker_items[this.position_id].button = true
            this.insertClick=true
            alert(this.f_name + 'と' + this.s_name + 'を保存しました')
            //非同期通信
            return axios.post('/api/insert',{
                f_id:f_id,
                s_id:s_id,
                userid:userid,
            }).then((res)=>{
                console.log(res.data);
                return res.data
            })
        },
        //3軒目保存
         t_save(s_id,listid){
            //ボタンを連続で押せなくする
            this.marker_items[this.position_id].button = true
            this.insertClick=true
            alert(this.s_name + 'を3軒目に追加')
            //非同期通信
            return axios.post('/api/update',{
                s_id:s_id,
                listid:listid,
               
            }).then((res)=>{
                console.log(res.data);
                return res.data
            })
        }
    }

}
</script>

<style scoped>
.body {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    z-index: -1;
    background-size: contain;
    background-image: url('https://i.pinimg.com/564x/5e/4e/ab/5e4eab5e15f0f7b38ce23b91ef28c49f.jpg');
}
.app {
    padding:0px 50px;
    margin-top: 60px;
    width: 100%;
}
#map {
    width: 100%;
    height: 500px;
} 
.col-md-4 {
    /* 左上の写真・店名の設定 */
    width: 100%;
    height: 350px;
    text-align: center;
    border-radius: 3px;
    color: rgb(0, 0, 0);
    letter-spacing: 2px;
    margin: 0 auto;
    padding: 10px;
    white-space: pre-line;
}
.col-md-4 p {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: -5px;
}
#photo img {
    border: 4px #ff0000 solid;
}
#name {
    height: 76px;
    display: block;
    font-size: 16px;
    font-weight: bold;
    margin-top: 10px;
    padding: 0 0 10px 0;
    overflow-y: scroll;
    white-space: pre-line;
}
.col-md-8 {
    width: 100%;
    height: 350px;
    border-radius: 3px;
    color: rgb(0, 0, 0);
    font-size: 18px;
    font-weight: bold;
    letter-spacing: 2px;
    margin: -10px auto;
    white-space: pre-line;
}
.col-md-8 hr {
    background-color: #000;
}
.pan_name{
    white-space: nowrap;
    margin-top: 20px;
    max-width: 180px;
    min-width: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
    font-weight: bold;
}
.pan_space {
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
    font-weight: bold;
}
.pan_space a {
    color: rgb(0, 47, 255);
    font-weight: bold;
}
#hashigo_save {
    white-space: nowrap;
    display: inline-block;
    padding: 0.5em 1em;
    text-decoration: none;
    background: blue;/*ボタン色*/
    color: rgb(255, 255, 255);
    border-bottom: solid 4px #627295;
    border-radius: 3px;
}
#hashigo_save:disabled {
    white-space: nowrap;
    display: inline-block;
    padding: 0.5em 1em;
    text-decoration: none;
    background: grey;/*ボタン色*/
    color: rgb(255, 255, 255);
    border-bottom: solid 4px #627295;
    border-radius: 3px;
}
#shop_info {
    width: 100%;
    height: 200px;
    margin: -10px;
    padding: 30px;
    overflow-y: scroll; 
}
.col-md-9 {
    position: relative;
    padding: 5px 5px 15px 5px;
    width: 90%; /* ボックス幅 */
    height: 100%;
    background-color: #fffff9; /* ボックス背景色 */
    color: #000; /* 文章色 */
    border: 5px solid #bd1818; /* 枠線 */
    border-radius: 3px; /* 角の丸み */
    box-shadow: 0 0 8px #333, 0 0 2px #555 inset;
}
.col-md-9:before, .col-md-9:after {
    position: absolute;
    content: '';
    width: 25px; 
    bottom: 3px;
    border-radius: 2px;
    box-shadow: 1px 1px 3px #666;
}
.col-md-9:before {
    right: 55px;
    border: solid 3px #333333; /*飾ペン黒 */
}
.col-md-9:after {
    right: 20px;
    border: solid 3px #ff42a0; /*飾ペンピンク */
    transform: rotate(8deg); /*飾ペン角度 */
}
.col-mid-3 {
    position: relative;
}
#products {
    text-align: center;
    position: relative;
    background: #77777734;
    padding: 1em;
    border: 5px solid #bd1818;
    box-shadow: 2px 2px 4px #999, 2px 2px 2px #020 inset;
    margin-left: 70px;
    width: 300px;
    height: 520px;
    overflow-y: scroll;
}
::-webkit-scrollbar {
    width: 5px;
}
::-webkit-scrollbar-track {
    border-radius:  30px;
    background: #eee;
}
::-webkit-scrollbar-thumb {
    border-radius: 30px;
    background: #ff0000;
}
#products hr {
    background-color: #FFF;
}
#detail {
    margin-top: 10px;
    max-width: 200px;
    min-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: inline-block;
    padding: 0.5em 1em;
    text-decoration: none;
    background: blue;/*ボタン色*/
    color: rgb(255, 255, 255);
    border-bottom: solid 4px #627295;
    border-radius: 3px;
}
#area:active {
    /*ボタンを押したとき*/
    -webkit-transform: translateY(4px);
    transform: translateY(4px);/*下に動く*/
    box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.2);/*影*/
    border-bottom: none;
}

#hot a {
    display: inline-block;
    height: 60px;
    background-color: tomato;
}
</style>