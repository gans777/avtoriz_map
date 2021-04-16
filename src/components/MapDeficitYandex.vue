<template>
	<div>
		{{deficit}} 
	<yandex-map 
    :coords="coords"
    :zoom="10" 
    @click="onClick"
  >
 <!-- <ymap-marker v-for="point in points" :key="point.coords" ></ymap-marker>-->
    <!--<ymap-marker 
      :coords="coords" 
      marker-id="123" 
      hint-content="some hint" 
    />-->

  </yandex-map>
</div>
</template>

<script>
import axios from 'axios';
export default {
	props:['deficit'], 
  data: () => ({ // stop here 1504
  
  coords: [47.23470683868971,
  39.72326724340817]
 
  }),
  mounted() {// нужене ли этот блок вообще???
 let user_login=localStorage.getItem('user_login'); 
 let user_hash=localStorage.getItem('user_hash');
 console.log(user_hash+' [hi from mapdeficityandex(mounted)] '+user_login);
 var params = new URLSearchParams();
     params.append('label', 'read_markers_sql'); 
     params.append('user_login', user_login);
     params.append('user_hash', user_hash);
     axios.post('http://avtorizmap/ajax/ajaxrequest.php', params).then(response => {console.log('ответ c базы о маркерах');
     console.log(response);
    //response.data.forEach(function(value){
    //let size = Object.keys(value).length;
   // });
     
     
      });
		},
		watch: {
			deficit:function(){
				console.log('i am props from watch '+ this.deficit);
				let user_login=localStorage.getItem('user_login'); 
 let user_hash=localStorage.getItem('user_hash');
 console.log(user_hash+' [hi from mapdeficityandex(mounted)] '+user_login);
 var params = new URLSearchParams();
     params.append('label', 'read_markers_sql'); 
     params.append('user_login', user_login);
     params.append('user_hash', user_hash);
     params.append('product', this.deficit);
     axios.post('http://avtorizmap/ajax/ajaxrequest.php', params).then(response => {console.log(response);//расставить все маркеры

     
      });
			}
		},
   methods: {
    onClick(e) {
      this.coords = e.get('coords');
      this.name_cat=this.deficit;
    },
  }
};
</script>


<style scoped>
.ymap-container {
  height: 400px;
}
</style>