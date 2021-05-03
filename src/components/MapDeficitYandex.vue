<template>
	<div>
		{{deficit}} 
	<yandex-map 
    :coords="coords"
    :zoom="10" 
    @click="onClick"
  >
  <ymap-marker v-for="(point,index) in points" 
 :key="index"
:coords="point.coords"
:marker-id="point.id_point"
:hint-content="point.name"
   ></ymap-marker>
    <!--<ymap-marker 
      :coords="coords" 
      marker-id="123" 
      hint-content="some hint" 
    />-->

  </yandex-map>
  <div class="points_list">
  <div class="info_point" v-for="(point,index) in points"
:key="index"
  >{{index+1}}.{{point.name}}</div>
  </div>
</div>
</template>

<script>
import axios from 'axios';
export default {
	props:['deficit'], 
  data() { 
  return{
points: [],  
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
     this.points=[];//очистка массива для профилактики
     axios.post('http://avtorizmap/ajax/ajaxrequest.php', params).then(response => {

console.log("расставляем маркеры");
     console.log(response);//расставить все маркеры
     console.log("i am after all_markers");
     let points_temp=[];
       response.data.forEach(function(value){
      let point={};
     let size = Object.keys(value).length;
     for(var  i=(size-5);i >= 0;i--){
     //console.log(value[i]);
     point.[i]=value[i];// здесь описания покупокупок, время описания...
              //  note+= html_wrap_note_this(value[i]);
               // описание покупки для  показа в балоне маркера (описание покупки, время, цена)
             } 
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
    console.log(this.points);
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
.points_list {
	display: flex;
	flex-wrap: wrap;
	border: 1px solid red;
	background-color:#79c142;
	min-height: 15px;
}
.info_point {
	border: 1px solid green;
	margin: 3px;
	position: relative;
	padding: 3px;
	background-color: #7fdb39;
}
</style>