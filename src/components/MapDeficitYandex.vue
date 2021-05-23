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
  <div class="points_list" v-if="points_list_visible">
  <div class="info_point" v-on:click="point_menu"  :id="point.id_point" v-for="(point,index) in points"
:key="index"
  >{{index+1}}.{{point.name}}<div class="wrap_dropdown_info" v-show="point.isShow_point_menu">{{point.id_point}} <div class="wrap_close_and_addinfo">x add edit</div>
<div class="wrap_note_this" v-for="(pur_desc,ind) in point.purchase_desc" :key="ind" ><div class="note_this">{{pur_desc.purchase_descr}}</div><div class="data_note">{{pur_desc.data_note}}</div><div class="last_price">{{pur_desc.params_value}}</div><div class="delete_this_note"></div></div>
</div></div>
  </div>
</div>
</template>

<script>
import axios from 'axios';
export default {
	props:['deficit'], 
  data() { 
  return{
  points_list_visible: false,  
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
        this.points_list_visible=true;
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
    // let point={};
     let point={purchase_desc:{}};
     let size = Object.keys(value).length;
     for(var  i=(size-5);i >= 0;i--){
     console.log('ниже значения value['+i+']');
        // point.[i]=value[i];// здесь описания покупок, время описания...
              //  note+= html_wrap_note_this(value[i]);
               // описание покупки для  показа в балоне маркера (описание покупки, время, цена)
             
point.purchase_desc.[i]=value[i];
                 } 
      point.id_point=value['id_point'];
     //console.log("id_point="+value['id_point']);
     point.name=value['name'];
     console.log("name= "+point.name);
     point.coords=[Number(value['lan']),Number(value['lng'])];
     //console.log("lan="+value['lan'] +" lng="+value['lng']);
     point.isShow_point_menu=false;//по умолчанию менюю ппоинта не видно
     points_temp.push(point);
  });
      this.points=points_temp;
    console.log("это массив компонента points=");
    console.log(this.points);
      });
			}
		},
   methods: {
    point_menu(e){
      const attrValue = e.currentTarget.getAttribute('id');
      //stop here 1705 isShow=true
      this.points.some(function(value){
        if (value.id_point==attrValue) {
           value.isShow_point_menu=true;// stop here 1705
           return
         // value.isShow_point_menu=true;
        }
      console.log("i am click to point's menu="+attrValue);
    });
    },
    onClick(e) {
      this.coords = e.get('coords');
      this.name_cat=this.deficit;
    }
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
.wrap_dropdown_info {
   position: absolute;
  background-color:#bada55;;
  z-index: 1;
  width: 220px;
  padding:5px;
}
.wrap_note_this{
	display: flex;
	border:1px solid #246e1f;
	line-height: 1.1;
	font-size: 15px;
	padding: 3px;
	background-color:#bada55;
	justify-content: space-between;
}
.note_this{

}
.data_note {
	font-size: 10px;
	display: flex;
    justify-content: center; /*Центрирование по горизонтали*/
    align-items: center;     /*Центрирование по вертикали */
    margin-left: 4px;
}
.last_price {
	display: flex;
    justify-content: center; /*Центрирование по горизонтали*/
    align-items: center;     /*Центрирование по вертикали */
    margin-left:4px;
}
</style>