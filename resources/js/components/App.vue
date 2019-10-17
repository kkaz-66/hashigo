<template>
<div class="body">
    <div class="app">
        <div class="row">
            <div class="col-md-12">
               <p>Hashigo</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                 <button @click="currentsearch">現在地へ移動</button>
                 <input type="text" v-model="address">
                 <button type="button" @click="keywordSearch">検索</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
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
            <div class="shop">
                <div v-if="isActive">

                </div>
                <div class="col-md-2" style="white-space: nowrap" v-else>
                    <img v-bind:src="photo"><br>
                    {{name}} <br>
                    <a v-bind:href="url">店情報</a><br>
                    <button type="button">詳細</button>
                </div>
            </div>
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
            //追加事項
            name: "",
            url: "",
            photo: "",
            map:{},
            isActive: true,
            // marker: null,
            geocode:{},
            address: '',
            center: {lat: 37.71, lng: 139.72},
            zoom: 14,
            marker_items: [],
            icon: {url: "", scaledSize:"", scaledColor: ""},
        }
    },

    methods: {
        //現在地取得
        currentPosition () {
            // if(marker_items != null){
            //     marker_items.setMap(null)
            // }
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

        //ピン立て 現在地
        setcentermarker(lat,lng){
            this.$refs.map.panTo({lat: lat, lng: lng})
            this.marker_items.push({position: {lat: lat, lng: lng}, title: '現在地', 
                                    icon: {url: 'http://pictogram2.com/p/p0957/3.png', scaledSize: new google.maps.Size(50, 55),scaledColor: '#0000'}})
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
            if(this.marker_items[id].title == '現在地'){
                this.isActive = true
            }else{
                this.isActive = false
            }
        },
    }

}
</script>

<style scoped>
#map {
    width: 100%;
    height: 855px;
} 

</style>