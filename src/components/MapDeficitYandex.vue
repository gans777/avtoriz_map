<template>
	<div>
		{{deficit}} <!--stop here 0604-->
	<yandex-map 
    :coords="coords"
    :zoom="10" 
    @click="onClick"
  >

    <ymap-marker 
      :coords="coords" 
      marker-id="123" 
      hint-content="some hint" 
    />
  </yandex-map>
</div>
</template>

<script>
import axios from 'axios';
export default {
	props:['deficit'], //stop here604
  data: () => ({
      coords: [
      47.2313455,
      39.7232855
    ]
  }),
  mounted() {
 let user_login=localStorage.getItem('user_login');
 let user_hash=localStorage.getItem('user_hash');
 console.log(user_hash+' [hi from mapdeficityandex] '+user_login);
 var params = new URLSearchParams();
     params.append('label', 'read_markers_sql'); 
     params.append('user_login', user_login);
     params.append('user_hash', user_hash);
     axios.post('http://avtorizmap/ajax/ajaxrequest.php', params).then(response => {console.log(response);
     
      });
		},
		watch: {
			deficit:function(){
				console.log('i am props from watch '+ this.deficit);
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