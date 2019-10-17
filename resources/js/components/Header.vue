<template>
  <div class="header">
    <div class="logo">hashigo</div>
    <div class="button_box">
      <div class="user_name"></div>
      <div class="login"></div>
    </div>
    
  </div>
</template>

<script>
export default {

}
</script>

<style>
.header {
    width: 100%;
    height: 60px;
    background-color: wheat;
    position: relative;
}
.logo {
  width: 200px;
  height: 60px;
  background-color: aquamarine;
}
.button_box{
  position: absolute;
  flex-wrap: wrap;
  top:0;
  right: 0;
  width: 400px;
  height: 60px;
}
.user_name {
  width: 200px;
  height: 60px;
  background-color: beige;
}
.login {
  width: 200px;
  height: 60px;
  background-color: thistle;
}


</style>