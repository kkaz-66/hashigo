<template>
<div class="app">
    <div class="body">
        <div class="row">
            <div class="col-md-12">HashiGo!!</div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <div id="map">
                <button @click="currentsearch">現在地へ移動</button>
                <input type="text" v-model="address">
                <button type="button" @click="keywordSearch">検索</button>
                <GmapMap :center="center" :zoom="zoom" style="width: 100%; height: 100%;" ref="map">
                <GmapMarker  v-for="(m,id) in marker_items"
                :position="m.position"
                :title="m.title"
                :url="m.url"
                :clickable="true" :draggable="false" :key="id" @click="clickMarker(id)">
                </GmapMarker>
                </GmapMap>
                </div>
            </div>
            <div class="col-md-2" style="white-space: pre-line">
                <img v-bind:src="photo"><br>
                {{name}}<br>
                <a v-bind:href="url">店情報</a><br>
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
            zoom: 14,
            marker_items: [],
        }
    },

    //1件目の場所から
    mounted (){
        console.log(this.place[0]['name_kana'])
        console.log(this.place)
        console.log(this.arr)
        this.center = {lat:35.6497371091, lng:139.7026642849}
        this.setcentermarker(35.6497371091,139.7026642849)
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
            })
         },

        getCurrentPositionSuccess (position) {
            let lat = position.coords.latitude
            let lng = position.coords.longitude
            this.$refs.map.panTo({lat: lat, lng: lng})
            this.marker_items.push({position: {lat: lat, lng: lng}, title: 'marker_5'})
        },

        //ピン立て 現在地
        setcentermarker(lat,lng){
            this.$refs.map.panTo({lat: lat, lng: lng})
            this.marker_items.push({position: {lat: lat, lng: lng}, title: 'marker_5'})
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
            let photo = shopdata.photo.pc.m
            let lat = shopdata.lat
            let lng = shopdata.lng
            this.marker_items.push({position: {lat: parseFloat(lat), lng: parseFloat(lng)}, title: name, url: url, photo: photo})
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

        clickMarker(id){
           this.name = this.marker_items[id].title
           this.url = this.marker_items[id].url
           this.photo = this.marker_items[id].photo
            
        },
    }

}
</script>

<style scoped>
#map {
    width: 100%;
    height: 910px;
} 
</style>