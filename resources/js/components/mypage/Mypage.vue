<template>
  <div class="wrap">
      <MypageHashigo />
  </div>
</template>

<script>
import MypageHashigo from './MypageHashigo.vue'

export default {
  props:{
    history: String,
  },
  components: {
    MypageHashigo,
  },
  mounted (){
    let json = JSON.parse(this.history)
    console.log(json)
  }

}
</script>

<style>
.wrap {
  width: 100%;
  height: 300px;
  background-color: tan;
}

</style>