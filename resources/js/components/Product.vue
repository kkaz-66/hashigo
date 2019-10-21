<template>
<div class="body">
    <div class="app">
        <div class="row">
            <div class="col-md-12">
               <p>Hashigo</p>
               <!--<button @click="setCurrentMarker">現在地更新</button>-->
            </div>
        </div>
        <!--詳細表示-->
        <div class="row">
            <!--店画像-->
            <div class="col-md-3"  style="white-space: nowrap">
                <img v-bind:src="f_photo"><br>
               <h3>{{ shop_name }}</h3>
            </div>
            <!--店詳細-->
            <div class="col-md-9" style="white-space: nowrap">
                <p>パンくずリスト -> <a href="">はしご保存</a></p>
                住所：{{ tel_add }}<br><hr>
                営業時間：{{ time }}<br><hr>
                収容人数：{{ capa }}<br><hr>
                ペット連れ込み：{{ pet }}<br><hr>
                クレジット：{{ credit }}<br><hr>
                <!--URL：<a v-bind:href="url">お店TOP</a><hr>-->
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
                <table>
                    <tr v-for="(s,id) in marker_items" :key="id">
                        <div v-if="id !== 0">
                            <img v-bind:src="s.photo"><br>
                            <button v-on:click="s_click(id)">店舗名</button>
                        </div>
                    </tr>
                </table>
            </div>
        </div>
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
            zoom: 17,
            marker_items: [],
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
            credit:""
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
    },

    methods: {
        //現在地取得
        // currentPosition () {
        //     return new Promise(function(resolve,reject){
        //         navigator.geolocation.getCurrentPosition((position)=>{resolve(position.coords)})
        //     })
        // },

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
        // async setCurrentMarker(){
        //     let position = await this.currentPosition()
        //     let lat = position.latitude
        //     let lng = position.longitude
        //     this.marker_items.push({position: {lat: lat, lng: lng}, title: 'ANSJXN'})
        //     //this.setcentermarker(lat,lng)
        // },

        // shoplistピン立て
        setshopmarker(shoplist){
            shoplist.map((shopdata)=>{
            let name = shopdata.name
            let url = shopdata.urls.pc
            let photo = shopdata.photo.pc.l
            let lat = shopdata.lat
            let lng = shopdata.lng
            this.marker_items.push({position: {lat: parseFloat(lat), lng: parseFloat(lng)}, title: name, url: url, photo: photo,
             address:shopdata.address, open:shopdata.open, capacity:shopdata.capacity, pet:shopdata.pet, card:shopdata.card})
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
        }
    }

}
</script>

<style scoped>
#map {
    width: 100%;
    height: 910px;
} 
</style>