<template>
  <div class="wrap">
      <!-- jsonのlengthで判定 -->
      <div v-if="Object.keys(this.jsons).length == 0">
        <ul>
          <li>1軒目：no store</li>
          <li>2軒目：no store</li>
          <li>3軒目：no store</li>
        </ul>
      </div>
      <div v-else class="ul_wrap">
        <ul>
          <li v-for="(json,id) in jsons" :key="id">
            <div class="hashigo_date">{{ json.date }}</div>
            <div class="hashigo_li">
              <div class="test">1軒目：{{ json.first?json.first.name:'no store'}}<br>{{json.first.open}}</div><img v-bind:src="json.first.photo.pc.m"></div>
            <div class="hashigo_li">
              <div class="test">2軒目：{{ json.second?json.second.name:'no store'}}<br>{{json.second.open}}</div><img v-bind:src="json.second.photo.pc.m"></div>
            <div class="hashigo_li">
              <div class="test">3軒目：{{ (json.third)?json.third.name:'no store'}}<br>{{json.third?json.third.open:''}}</div>
              <span v-if="json.third"><img v-bind:src="json.third.photo.pc.m"></span>
              <span v-else><button>3軒目を探す</button></span>
            </div>
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

<style scoped>
html body {
  margin:0;
}

.wrap {
  width: 100%;
  padding: 0;
  /* background-color: tomato; */
}

.ul_wrap {
  /* リストの幅、wrapを基準に70％、 */
  /* width: 70%; */
  margin: auto;
  /* background-color: orange; */
}

.ul_wrap ul {
  margin: 0;
  padding: 0;
}

.ul_wrap ul .hashigo_date {
  width: 100px;
  /* background-color: aquamarine; */
}

.ul_wrap li {
  list-style-type: none;
  /* background-color: turquoise; */
  /* margin-bottom: 20px; */
  /* padding-left: 30px; */
  padding: 5px;
  display: flex;
}

.ul_wrap li .hashigo_li {
  width: 468px;
  /* background-color: aqua; */
  display: flex;
}

.ul_wrap li .test {
  display: block;
  /* background-color: palegoldenrod; */
  width: 250px;
}

.hashigo_li img {
  object-fit: cover;
  /* background-color: yellowgreen; */
  display: block;
}


</style>