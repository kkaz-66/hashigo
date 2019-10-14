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
    // $url = "http://webservice.recruit.co.jp/hotpepper/gourmet/v1/?key=".$hpg_key."&large_area=Z011&id=".$shopid."&name=".$name."&address=".$address."&lat=".$lat."&lng=".$lng."&format=json";
    // let url = 'http://webservice.recruit.co.jp/hotpepper/gourmet/v1/?key=' + process.env.MIX_HOT_KEY + '&large_area=Z011&format=json';
    // console.log(url);

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