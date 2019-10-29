<template>
  <div class="wrap">
    <div class="second_wrap">
      <h2 class="mypage_h2">はしご履歴</h2>
      <!-- jsonのlengthで判定 -->
      <div v-if="Object.keys(this.jsons).length == 0">
        はしご登録がされていません
      </div>
      <div v-else class="ul_wrap">
        <ul>
          <li v-for="(json,id) in jsons" :key="id">

            <div class="date">{{ json.date }}</div>

            <div class="hashigo_li">
              <div class="hashigo_shop">1軒目：{{ json.first?json.first.name:'no store'}}<br>
              定休日：{{json.first.close}}<br>
              <a v-bind:href="json.first.urls.pc" target="_blank">{{ json.first.name }}の公式</a>
              </div>
              <img v-bind:src="json.first.photo.pc.m">
            </div><!-- hashigo_li -->

            <div class="hashigo_li">
              <div class="hashigo_shop">2軒目：{{ json.second?json.second.name:'no store'}}<br>
              定休日：{{json.second.close}}<br>
              <a v-bind:href="json.second.urls.pc" target="_blank">{{ json.second.name }}の公式</a>
              </div>
              <img v-bind:src="json.second.photo.pc.m">
            </div><!-- hashigo_li -->

            <div class="hashigo_li third_shop">
              <div class="hashigo_shop">3軒目：{{ (json.third)?json.third.name:'no store'}}<br>
              <span v-if="json.third">定休日：{{json.third?json.third.close:''}}</span><br>
              <span v-if="json.third"><a v-bind:href="json.second.urls.pc" target="_blank">{{ json.second.name }}の公式</a></span>
              </div>
              <span v-if="json.third"><img v-bind:src="json.third.photo.pc.m"></span>
              <a v-else class="third_shop_search" @click="thirdSearch(id)" v-bind:href="detail + shopid + f_lat + lat + f_lng + lng + li + listid + his + hisname">3軒目を探す</a>
            </div><!-- hashigo_li -->
          </li>
        </ul>
      </div><!-- ul_wrap -->
      <span class="hot_text">Powered by <a href="http://webservice.recruit.co.jp/">ホットペッパー Webサービス</a></span>
      <span class="hot_text">画像提供：ホットペッパー グルメ</span>
    </div><!-- second wrap -->
  </div><!-- wrap -->
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
      shopid:"",
      lat:"",
      lng:"",
      //送る情報
      detail:"/detail2?id=",
      f_lat:"&lat=",
      f_lng:"&lng=",
      li : "&listid=",
      listid: "",
      his: "&hisname=",
      hisname: "",
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
    thirdSearch(id){
      this.hisname = this.jsons[id].first.name;
      this.shopid = this.jsons[id].second.id;
      this.lat = this.jsons[id].second.lat;
      this.lng = this.jsons[id].second.lng;
      this.listid = id
    }

  },
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  color: #3e3e3e;
}

.wrap {
  width: 100%;
  height: 100%;
  padding: 0;
  margin-top: 30px;
}

.second_wrap {
  width: 80%;
  margin: auto;
}

.mypage_h2{
  color: #3e3e3e;
  text-align: center;
  margin-bottom: 2rem;
  border-bottom: solid #3e3e3e;
}

.ul_wrap {
  margin: auto;
}


li {
  list-style-type: none;
  display: flex;
  margin-bottom: 10px;
  background-color: rgba(255,255,255,0.4);
  box-shadow: 0 0 3px 0 rgba(0, 0, 0, 0.25);
  transition: all 0.3s ease 0s;
  justify-content: space-between;
}
li:hover {
  box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.25);
  transform: translateY(-0.1875em);
}

li .hashigo_li {
  width: 468px;
  display: flex;
}

li .hashigo_shop {
  display: block;
  width: 250px;
}

.hashigo_li img {
  object-fit: cover;
  display: block;
}

.third_shop{
  position: relative;
}

.third_shop a {
  text-decoration: none;
}

.third_shop_search{
  text-decoration: none;
  display: flex;
  text-align: center;
  width: 100%;
  height: 100%;
  /* background-color: aqua; */
  position: absolute;
  align-items: center;
  justify-content: center; 
}

.third_shop_search,
.third_shop_search::before,
.third_shop_search::after {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-transition: all .3s;
  transition: all .3s;
}
.third_shop_search:hover {
  background-color: rgba(0, 0, 0, 0.09);
}

.hot_text {
  font-size: 0.3rem;
}

@media screen and (max-width:768px){
  *{
    margin: 0;
    padding: 0;
    width: auto;
    height: auto;
  }

  .wrap {
    /* background-color: aqua; */
    width: 100%;
    margin-top: 10px;
  }

  .second_wrap {
    width: 100%;
  }

  .ul_wrap {
    width: 100%;
  }

  .ul_wrap ul {
    width: 100%;
    display: flex;
    flex-direction: column;
  }

  .ul_wrap ul li {
    display: flex;
    /* width: 49%; */
    width: 100%;
    flex-direction: column;
  }

  li .hashigo_li {
    width: 100%;
    justify-content: space-between;
    /* background-color: blue; */
    padding: 5px;
  }

}

</style>