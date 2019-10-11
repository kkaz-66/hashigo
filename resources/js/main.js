import Vue from 'vue'
import * as VueGoogleMaps from 'vue2-google-maps'
Vue.use(VueGoogleMaps, {
load: {
    key: 'AIzaSyD1JaTO4Yyyax8Mz6u5LK7OdW_5JB4Yicw',
    libraries: 'places'
    }
})