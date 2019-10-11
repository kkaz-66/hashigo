import Vue from 'vue'
import * as VueGoogleMaps from 'vue2-google-maps'
import { callbackify } from 'util'
Vue.use(VueGoogleMaps, {
load: {
    key: '',
    libraries: 'places',
    }
})