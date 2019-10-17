<template>
  <div class="wrap">
      mypage 
      <MypageHashigo />
  </div>
</template>

<script>
import MypageHashigo from './MypageHashigo.vue'

export default {
  components: {
    MypageHashigo,
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