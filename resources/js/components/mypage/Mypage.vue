<template>
  <div class="wrap">
      <!-- <ul>
        <li v-for="(json,id) in jsons" :key="id">
          1軒目：{{ json.first?json.first.name:'no store'}}<br>
          2軒目：{{ json.second?json.second.name:'no store'}}<br>
          3軒目：{{ json.third?json.third.name:'no store'}}<br>
        </li>
      </ul> -->

      <!-- jsonのlengthで判定 -->
      <div v-if="Object.keys(this.jsons).length == 0">
        <ul>
          <li>1軒目：no store</li>
          <li>2軒目：no store</li>
          <li>3軒目：no store</li>
        </ul>
      </div>
      <div v-else>
        <ul>
          <li v-for="(json,id) in jsons" :key="id">
            1軒目：{{ json.first?json.first.name:'no store'}}<img v-bind:src="json.first.photo.pc.m"><br>
            2軒目：{{ json.second?json.second.name:'no store'}}<img v-bind:src="json.second.photo.pc.m"><br>
            3軒目：{{ (json.third)?json.third.name:'no store'}}
            <span v-if="json.third"><img v-bind:src="json.third.photo.pc.m"></span>
            <span v-else><button>3軒目を探す</button></span>
          </li>
        </ul>
      </div>
  </div>
</template>

<script>
// import MypageHashigo from './MypageHashigo.vue'

export default {
  props:{
    history: String,
  },
  data () {
    return {
      jsons: [],
      date: [],
      first_store: "",
      second_store: "",
      thirs_store: "",
    }
  },
  components: {
    // MypageHashigo,
  },
  mounted (){
    let json = JSON.parse(this.history)
    console.log(json)
    this.jsons = json
    console.log(this.jsons)
    console.log(Object.keys(this.jsons).length);
  },

  methods: {

  },

}
</script>

<style>
.wrap {
  width: 100%;
  margin: auto;
  background-color: tan;
}

.wrap ul li {
  list-style-type: none;
  margin: auto;
  padding: 5px;
  width: 900px;
  height: 150px;
  background-color: aquamarine;
}

.li_span {
  width: 300px;
  background-color: violet;
}

.date.first.second.third {
  width: 600px;
  height: 600px;
}


</style>