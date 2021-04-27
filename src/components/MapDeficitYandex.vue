<template>
	<div>
		{{deficit}} 
	<yandex-map 
    :coords="coords"
    :zoom="10" 
    @click="onClick"
  >
  <ymap-marker v-for="(point,index) in points" :key="index"
:coords="point.coords"
:marker-id="point.id_point"
   ></ymap-marker>
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
  data() { 
  return{
points: [],  //stop here 2204
  coords: [47.23470683868971,
  39.72326724340817]
 }
  },
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
     axios.post('http://avtorizmap/ajax/ajaxrequest.php', params).then(response => {
console.log("расставляем маркеры");
     console.log(response);//расставить все маркеры
     console.log("i am after all_markers");
     let points_temp=[];
       response.data.forEach(function(value){
      let point={};
      point.id_point=value['id_point'];
     //console.log("id_point="+value['id_point']);
     point.name=value['name'];
     console.log("name= "+point.name);
     point.coords=[Number(value['lan']),Number(value['lng'])];
     //console.log("lan="+value['lan'] +" lng="+value['lng']);
     points_temp.push(point);
  });
      this.points=points_temp;
    console.log("это массив компонента points=");
    console.log(this.points);//stop here 2704
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