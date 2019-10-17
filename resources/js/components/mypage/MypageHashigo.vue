<template>
  <div class="hashigo">
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() { //データの初期値を設定
    return {
      id: null,
      userid: "",
      }
  },
  // methods: {
  //     getProfiles() {
  //       const data = {
  //       userid: '1' //今回投げるuserid
  //     }
  //     axios.post('/api/user',data)
  //     .then(res =>  {
  //     console.log(res.data);
  //   })
  // },
  // created(){
  //   this.getProfiles()
  // },
  mounted(){
     axios.get('/api/user')
      .then(res =>  {
      console.log(res.data);
    })
  },
  // }
  

  // mounted() {
  //     axios
  //     .get('/api/user')
  //     .then((res)=>{
  //       console.log("id");
  //       console.log(res.data);
  //     })
  //     axios
  //     .get('/api/user')
  //     .then(response => (this.id = response))

  //     axios.post('/api/user',data)
  //     .then(res =>  {
  //     console.log(res.data);
  //   })
  // },
 

}
</script>

<style>
.hashigo {
    width: 100px;
    height: 100px;
    background-color: aqua;
}

</style>