<template>
<div class="app">
<div id="map">
<GmapMap :center="center" :zoom="zoom" style="width: 100%; height: 100%;">
<GmapMarker v-for="(m,id) in marker_items"
:position="m.position"
:title="m.title"
:clickable="true" :draggable="false" :key="id">
</GmapMarker>
</GmapMap>
 
<button @click="currentPosition">現在地へ移動</button>
</div>
</div>
</template>
<script>
import { METHODS } from 'http';

export default {
    data () {
        return {
            center: {lat: 35.71, lng: 139.72},
            zoom: 14,
            marker_items: [
            {position: {lat: 35.71, lng: 139.72}, title: 'marker_1'},
            {position: {lat: 35.72, lng: 139.73}, title: 'marker_2'},
            {position: {lat: 35.70, lng: 139.71}, title: 'marker_3'},
            {position: {lat: 35.71, lng: 139.70}, title: 'marker_4'}
            ],

            // currentPosition () {
            // navigator.geolocation.getCurrentPosition(this.getCurrentPositionSuccess)
            // },
            // getCurrentPositionSuccess (position) {
            //      let lat = position.coords.latitude
            //      let lng = position.coords.longitude
            //      this.$refs.map.panTo({lat: lat, lng: lng})
            //     console.log("aaa");
            // }
        }
    },

    methods: {
        currentPosition () {
            navigator.geolocation.getCurrentPosition(this.getCurrentPositionSuccess)
            console.log("ccc");
        },
            getCurrentPositionSuccess (position) {
                 let lat = position.coords.latitude
                 let lng = position.coords.longitude
                 this.$refs.map.panTo({lat: lat, lng: lng})
                console.log(lat);
                console.log(lng);
            
            }
    }

}
</script>

<style scoped>
#map {
    width: 100%;
    height: 500px;
    /* background-color:aquamarine; */
} 
</style>