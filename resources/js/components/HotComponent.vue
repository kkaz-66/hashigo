<template>
  <div class="test-wrap"> 
    <p class="title">test component</p>
    <p v-bind="$attrs">attrs: {{$attrs}}</p>
  </div>
</template>

<script>
export default {
  mounted() {
    inheritAttrs: false;
    let hot = this.$attrs;
    let shop = Object.keys(hot);
    console.log('aaa'+ shop);
    console.log(hot.data.results.shop);
    console.log(this.$attrs);
    console.log('test data get');
  },
}
</script>

<style scoped>
.test-wrap{
  width: 100%;
  height: 500px;
  background-color: antiquewhite;
}
.title {
  color: blue;
  font-weight: bold;
  font-size: 1.5rem;
}
</style>