import Vue from 'vue'
import * as VueGoogleMaps from 'vue2-google-maps'
import { callbackify } from 'util'
Vue.use(VueGoogleMaps, {
load: {
    key: process.env.MIX_API_KEY,
    libraries: 'places'
    }
})