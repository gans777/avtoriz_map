<template>
	<div>
		{{deficit}} 
	<yandex-map 
    :coords="center_coords"
    :zoom="11" 
    @click="onClick"
    @map-was-initialized="mapInstance"
  >
  <ymap-marker v-for="(point,index) in points" 
 :key="index"
:coords="point.coords"
:marker-id="point.id_point"
:hint-content="point.name"
:options="point.isShow_point_menu_marker_color"
:properties="{iconContent: '22'}"
   ></ymap-marker>

  </yandex-map>
  <div class="points_list" v-if="points_list_visible">
  <div class="info_point" :class="{info_point_active: point.isShow_point_menu}"  :id="point.id_point" v-for="(point,index) in points"
:key="index"
  ><span v-on:click="point_menu" :id="point.id_point" >{{index+1}}.{{point.name}} id_point={{point.id_point}}</span><div class="wrap_dropdown_info" v-if="point.isShow_point_menu"> <div class="wrap_close_and_addinfo"><button type="button" class="close_wrap_dropdown_info btn btn-success" v-on:click="close_this_point_menu"><i class="fa fa-times fa-lg" aria-hidden="true"></i></button> <button type='button' class='add_info btn btn-info' title='записать отзыв о покупке/наличии дефицита'><i class="fa fa-cart-plus fa-lg" aria-hidden="true"></i> куплено</button> <button type='button' class='btn btn-secondary edit_point'><i class="fa fa-cog fa-lg" aria-hidden="true"></i></button></div>
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
    map_collection:{},
  points_list_visible: false,  
points: [],  
  center_coords: [47.23470683868971,39.72326724340817],
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
       // this.mapInstance.myMap.geoObjects.removeAll();
      // this.map_collection.geoObjects.removeAll();//вроде сработало 
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
this.map_collection.geoObjects.removeAll();//удаляет все маркеры
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
     point.isShow_point_menu_marker_color= {'iconColor': '#79c142',}//цвет маркера поинта
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
      console.log("координаты центра карты:");
      console.log(this.center_coords);
      let center_coords_temp=[];
      this.points.forEach(function(value){
        if (value.id_point==attrValue) {
           value.isShow_point_menu=true;
           value.isShow_point_menu_marker_color={'iconColor': '#79f142'};
           // теперь выделить маркер другим цветом 0406
           //this.center_coords=value.coords;
          // console.log("координаты центра карты:");
          // console.log(this.center_coords);
           console.log("координаты этой точки:");
           console.log(value.coords);
           center_coords_temp=value.coords;
           } else {value.isShow_point_menu=false;
            value.isShow_point_menu_marker_color={'iconColor': '#79c142'};}
    });
     this.center_coords=center_coords_temp;
    },
    close_this_point_menu(e){
    const attrValue = e.currentTarget.closest(".info_point").getAttribute('id');
    this.points.some(function(value){
    if(value.id_point==attrValue){
	value.isShow_point_menu=false;
  value.isShow_point_menu_marker_color={'iconColor': '#79c142'};//восстановление цвета маркера
	return
}
    });

    },
    mapInstance(e){
      this.map_collection=e;
      console.log("i am instans");
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
.info_point_active {
  background-color: #bada55;
}
.wrap_dropdown_info {
   position: absolute;
  background-color:#bada55;
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
.wrap_close_and_addinfo{
	display:flex;
	padding: 0px;
}
.wrap_close_and_addinfo>button {
	margin-left: 2px;
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