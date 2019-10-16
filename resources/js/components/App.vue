<template>
<div class="app">
<div id="map">
<button @click="currentsearch">現在地へ移動</button>
<input type="text" v-model="address">
<button type="button" @click="keywordSearch">検索</button>
{{name}}
<a v-bind:href="url">店情報</a>
<img v-bind:src="photo">
<GmapMap :center="center" :zoom="zoom" style="width: 100%; height: 100%;" ref="map">
<GmapMarker  v-for="(m,id) in marker_items"
:position="m.position"
:title="m.title"
:url="m.url"
:clickable="true" :draggable="false" :key="id" @click="clickMarker(id)">
</GmapMarker>
</GmapMap>

</div>

<div class="side"></div>

</div>
</template> 
<script>
import { METHODS } from 'http';
import axios from 'axios';

export default {
    
    data () {
        return {
            //追加事項
            name: "",
            url: "",
            photo: "",
            map:{},
            // marker: null,
            geocode:{},
            address: '',
            center: {lat: 36.71, lng: 139.72},
            zoom: 14,
            marker_items: [],
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
                console.log("geo");
                this.geocoder = new google.maps.Geocoder();
                console.log("111");
                // console.log(geocoder);
                console.log("222");
                this.geocoder.geocode({'address': this.address},(results, status)=>{resolve(results[0].geometry.location)})
            })
         },


        //エリア検索
    //     mapSearch() {
    //         this.geocoder = new google.maps.Geocoder();
    //         this.geocoder.geocode({
    //             'address': this.address
    //         }, 
    //         // results = lat,lng  status = success,or,false
    //         (results, status) => {
    //         if (status === google.maps.GeocoderStatus.OK) {
    //         this.$refs.map.panTo(results[0].geometry.location)
    //         this.marker = new google.maps.Marker({
    //             map: this.map,
    //             position: results[0].geometry.location
    //         })
    //         this.marker_items.push({position: results[0].geometry.location, title: 'marker_6'})
    //         console.log(results[0].geometry.location);
    // 　      }   
    //         })
    //     },

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
                // this.programs = res.data
                // this.history()
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
    height: 500px;
} 

</style>




