import Vue from 'vue'
import * as VueGoogleMaps from 'vue2-google-maps'
Vue.use(VueGoogleMaps, {
load: {
    key: '自分のキーを入れてね',
    libraries: 'places'
    }
})