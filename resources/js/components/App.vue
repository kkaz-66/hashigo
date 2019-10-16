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
                <button type="button">詳細</button>
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
            //位置情報＆検索
            map:{},
            // marker: null,
            geocode:{},
            address: '',
            center: {lat: 36.71, lng: 139.72},
            zoom: 14,
            marker_items: [
            {position: {lat: 35.71, lng: 139.72}, title: 'marker_1'},
            // {position: {lat: 35.72, lng: 139.73}, title: 'marker_2'},
            // {position: {lat: 35.70, lng: 139.71}, title: 'marker_3'},
            // {position: {lat: 35.71, lng: 139.70}, title: 'marker_4'}
            ],
            //クリックアクション
            infoOptions: {
                pixelOffset: {
                width: 0,
                height: -35
                }
            },
            infoWindowPos: null,
            infoWinOpen: false,
            infoContent: {
                imageurl: null,
                title: null,
                address: null
            }
        }
    },

    methods: {
        //現在地取得
        currentPosition () {
            return new Promise(function(resolve,reject){
                navigator.geolocation.getCurrentPosition((position)=>{resolve(position.coords)})
            })
        },
        getCurrentPositionSuccess (position) {
                 let lat = position.coords.latitude
                 let lng = position.coords.longitude
                 this.$refs.map.panTo({lat: lat, lng: lng})
                 this.marker_items.push({position: {lat: lat, lng: lng}, title: 'marker_5'})
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
        
        //クリックアクション
        toggleInfoWindow (marker, id) {
            this.infoWinOpen = false
            this.infoWindowPos = marker.position
            this.infoContent = marker.content
            this.infoWinOpen = true
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
