<template>
  <div class="wrap">
    <ul>
      <li v-for="(json,id) in jsons" :key="id">
        {{ json.date }}
        {{ json.first.id}}
        {{ json.second.id}}
        {{ json.third.id}}
      </li>
    </ul>
  </div>
</template>

<script>
import MypageHashigo from './MypageHashigo.vue'

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
    MypageHashigo,
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
  height: 300px;
  background-color: tan;
}

ul {
  list-style: none;
  background-color: aquamarine;
}

</style>