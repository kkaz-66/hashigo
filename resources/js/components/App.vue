<template>
<div class="app">
<div id="map">
<button @click="currentsearch">現在地へ移動</button>
<input type="text" v-model="address">
<button type="button" @click="keywordSearch">検索</button>
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
            // {position: {lat: 35.71, lng: 139.72}, title: 'marker_1'},
            // {position: {lat: 35.72, lng: 139.73}, title: 'marker_2'},
            // {position: {lat: 35.70, lng: 139.71}, title: 'marker_3'},
            // {position: {lat: 35.71, lng: 139.70}, title: 'marker_4'}
            ],
        }
    },

    methods: {
        //現在地取得
        currentPosition () {
            return new Promise(function(resolve,reject){
                navigator.geolocation.getCurrentPosition((position)=>{resolve(position.coords)})
            })
        },

         keywordPosition () {
            return new Promise(function(resolve,reject){
                console.log("geo");
                let geocoder = new google.maps.Geocoder();
                console.log("111");
                console.log(geocoder);
                console.log("222");
                this.geocoder.geocode({'address': this.address},
                (results, status)=>{resolve(results[0].geometry.location)}
                )
            })
         },


        //エリア検索
        mapSearch() {
            this.geocoder = new google.maps.Geocoder();
            this.geocoder.geocode({
                'address': this.address
            }, 
            // results = lat,lng  status = success,or,false
            (results, status) => {
            if (status === google.maps.GeocoderStatus.OK) {
            this.$refs.map.panTo(results[0].geometry.location)
            this.marker = new google.maps.Marker({
                map: this.map,
                position: results[0].geometry.location
            })
            this.marker_items.push({position: results[0].geometry.location, title: 'marker_6'})
            console.log(results[0].geometry.location);
    　      }   
            })
        },

        //ピン立て
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

        //レスポンスデータをコンソール表示
        hotlist(){
            return axios.get('/api/list').then((res)=>{
                return res.data
            })
        },

        // いらない？
        async test(){
            let test = await this.hotlist()
            let lng = test.results.shop[0].lng;
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
            shoplist.map((position)=>{
                this.marker_items.push({position: {lat: parseFloat(position.lat), lng: parseFloat(position.lng)}, title: 'marker_5'})
            });
        },

       async keywordSearch(){
           console.log("000");
           let keyword_position = await this.keywordPosition()
           console.log("001");
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




