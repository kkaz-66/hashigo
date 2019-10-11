<template>
<div class="app">
<div id="map">
 
<button @click="currentPosition">現在地へ移動</button>
<input type="text" v-model="address">
<button type="button" @click="mapSearch">検索</button>
<GmapMap :center="center" :zoom="zoom" style="width: 100%; height: 100%;" ref="map">
<GmapMarker v-for="(m,id) in marker_items"
:position="m.position"
:title="m.title"
:clickable="true" :draggable="false" :key="id">
</GmapMarker>
</GmapMap>
</div>
</div>
</template> 
<script>
import { METHODS } from 'http';

export default {
    //追加事項（エリア検索）
    mounted() {
        // this.map = new google.maps.Map(document.getElementById('map'));
        // this.geocoder = new google.maps.Geocoder();
        // console.log("aaa")
    },
    data () {
        return {
            //追加事項
            map:{},
            marker: null,
            geocode:{},
            address: '',

            center: {lat: 36.71, lng: 139.72},
            zoom: 14,
            marker_items: [
            {position: {lat: 35.71, lng: 139.72}, title: 'marker_1'},
            {position: {lat: 35.72, lng: 139.73}, title: 'marker_2'},
            {position: {lat: 35.70, lng: 139.71}, title: 'marker_3'},
            {position: {lat: 35.71, lng: 139.70}, title: 'marker_4'}
            ],

        }
    },

    methods: {
        currentPosition () {
            navigator.geolocation.getCurrentPosition(this.getCurrentPositionSuccess)
        },
            getCurrentPositionSuccess (position) {
                 let lat = position.coords.latitude
                 let lng = position.coords.longitude
                 this.$refs.map.panTo({lat: lat, lng: lng})
            },
            //追加事項(検索)
            mapSearch() {
            this.geocoder = new google.maps.Geocoder();
            this.geocoder.geocode({
                    'address': this.address
                }, 
                (results, status) => {
                if (status === google.maps.GeocoderStatus.OK) {
                this.$refs.map.panTo(results[0].geometry.location)

                this.marker = new google.maps.Marker({
                    map: this.map,
                    position: results[0].geometry.location
                })
           　 }   
            })
        }
    }

}
</script>

<style scoped>
#map {
    width: 100%;
    height: 500px;
} 
</style>