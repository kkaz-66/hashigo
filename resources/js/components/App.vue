<template>
    <div class="app">
        <div id="map">
        <button @click="currentPosition">現在地へ移動</button>
        <input type="text" v-model="address">
        <button type="button" @click="mapSearch">検索</button>
        <GmapMap :center="center" :zoom="zoom" style="width: 100%; height: 100%;" ref="map">

        <GmapInfoWindow :options="infoOptions" :position="infoWindowPos" :opened="infoWinOpen" @closeclick="infoWinOpen=false">
            <table>
                <tr>
                        <td>Sample</td>
                        <td style="text-align:left;">
                        <p>Windows</p>
                    </td>
                </tr>
            </table>
            </GmapInfoWindow>

        <GmapMarker v-for="(m,id) in marker_items"
        :position="m.position"
        :title="m.title"
        @click="toggleInfoWindow(m,id)"
        :clickable="true" :draggable="false" :key="id">
        </GmapMarker>
        </GmapMap>
        </div>
    </div>
</template> 
<script>
import { METHODS } from 'http';

export default {
    
    data () {
        return {
            //位置情報＆検索
            map:{},
            marker: null,
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
            navigator.geolocation.getCurrentPosition(this.getCurrentPositionSuccess)
        },
        getCurrentPositionSuccess (position) {
                 let lat = position.coords.latitude
                 let lng = position.coords.longitude
                 this.$refs.map.panTo({lat: lat, lng: lng})
                 this.marker_items.push({position: {lat: lat, lng: lng}, title: 'marker_5'})
        },

        //エリア検索
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
                this.marker_items.push({position: results[0].geometry.location, title: 'marker_6'})
           　 }   
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