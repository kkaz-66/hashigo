<template>
  <div class="wrap">
    <ul class="ul_hashigo">
      <li v-for="(json,id) in jsons" :key="id" >
        日付：{{ json.date }}<br>
        １軒目：{{ json.first.name}}<br>
        ２軒目：{{ json.second.name}}<br>
        ３軒目：{{ json.third?json.third.name:'no store'}}<br>
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
  width: 600px;
  height: 150px;
  background-color: aquamarine;
}

</style>