<template>
  <div class="wrap">
    <ul class="ul_hashigo">
      <li v-for="(json,id) in jsons" :key="id" >
        日付：{{ json.date }}<br>
        １軒目：{{ json.first.name}}<img v-bind:src="json.first.photo.pc.m"><br>
        ２軒目：{{ json.second.name}}<img v-bind:src="json.second.photo.pc.m"><br>
        ３軒目：{{ json.third?json.third.name:'no store'}}<br>
        <!-- <div class="date">日付：{{ json.date }}</div>
        <div class="firtst">１軒目：{{ json.first.name}}<img v-bind:src="json.first.photo.pc.m"></div>
        <div class="second">２軒目：{{ json.second.name}}<img v-bind:src="json.second.photo.pc.m"></div>
        <div class="third">３軒目：{{ json.third?json.third.name:'no store'}}</div> -->
      </li>
    </ul>
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
      jsons: null,
      date: [],
      first_store: "",
      second_store: "",
      thirs_store: "",
      f_photo: "",
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

.date.first.second.third {
  width: 600px;
  height: 600px;
}
</style>