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
:properties="{iconContent: point.purchase_desc[0].params_value}"
   ></ymap-marker>
<ymap-marker v-if="coords_point_have_first_click" 
:coords="coords_new_point"
marker-id="5555"
hint-content="some hint"
></ymap-marker>
  </yandex-map>
  <div class="points_list" v-if="points_list_visible">
  <div class="info_point" :class="{info_point_active: point.isShow_point_menu}"  :id="point.id_point" v-for="(point,index) in points"
:key="index"
  ><span v-on:click="point_menu" :id="point.id_point" >{{index+1}}.{{point.name}} id_point={{point.id_point}}</span><div class="wrap_dropdown_info" v-if="point.isShow_point_menu"> <div class="wrap_close_and_addinfo"><button type="button" class="close_wrap_dropdown_info btn btn-success" v-on:click="close_this_point_menu"><i class="fa fa-times fa-lg" aria-hidden="true"></i></button> <button type='button' class='add_info btn btn-info' title='записать отзыв о покупке/наличии дефицита' @click="add_comment_about_purchase(point.id_point,point.name,deficit)"><i class="fa fa-cart-plus fa-lg" aria-hidden="true"></i> куплено</button> <button type='button' class='btn btn-secondary edit_point'><i class="fa fa-cog fa-lg" aria-hidden="true"></i></button></div>
  <div class="wrap_all_note_this">
<div class="wrap_note_this" v-for="(pur_desc,ind) in point.purchase_desc" :key="ind" ><div class="note_this">{{pur_desc.purchase_descr}}</div><div class="data_note">{{pur_desc.data_note}}</div><div class="last_price">{{pur_desc.params_value}}</div><div class="delete_this_note" @click="delete_this_comment(pur_desc,point.name)" :id="pur_desc.id_note">
  <i class="fa fa-trash" aria-hidden="true"></i>
</div></div>
</div>
</div></div>
  </div>
  
  <div class="wrap_button_point"><b-button variant="outline-primary add_point" v-on:click="add_Point" v-if="points_list_visible">Добавить точку</b-button></div>
  <div class="wrap_coord_point" v-if="wrap_coord_point_visible">
      
    <p><label for="lan_field">широта: </label> <input class="red_border" type="text"  :class="{'border border-success': control_new_point_lan_lng_full} " v-model="lan" name="lan" id="lan_field"> <label for="lng_field"> долгота: </label><input class="red_border" :class="{'border border-success': control_new_point_lan_lng_full} " type="text" v-model="lng" name="lng" id="lng_field"></p>
          
          
          <p><label for="name_point_field">название точки:</label><input class="red_border" :class="{'border border-success': control_length_of_point_name}" type="text" v-model="point_name"  id="name_point_field"><label for="name_point_field" v-if="!control_length_of_point_name" class="text-danger pl-1">min 3 символа</label></p>
          <p><label for="price_field">стоимость: </label><input class="red_border" :class="{'border border-success': control_length_of_cost_of_good}" type="text" v-model="cost_of_good"  id="price_field"><label for="price_field" class="text-danger pl-1" v-if="!control_length_of_cost_of_good">min одна цифра </label></p>
          
  <p> <label for="description_point_field">комментарий:</label><br><textarea class="red_border" :class="{'border border-success': control_length_of_comment}" v-model="comment" name="description_point_" cols="40" rows="4" id="description_point_field"></textarea> <label for="description_point_field" class="text-danger pl-1" v-if="!control_length_of_comment">min 4 символа</label></p>

    <button type="button" class="btn btn-primary save" v-if="coords_point_have_first_click&&control_length_of_point_name&&control_length_of_cost_of_good&&control_length_of_comment" @click="save_new_point">сохранить</button>
     <div class="wrap_out_add_point_x">
       <button type="button" class="btn btn-danger out_add_point mr-1 out_add_point" @click="out_add_point"><i class="fa fa-times fa-lg" aria-hidden="true"></i></button>
     </div>
     <div class="help_info_for_users_add_point">
       Кликните на карте место торговой точки.
     </div>
  </div>
  <!--модальное окно добавки отзыва о покупке-->
  <b-modal id="modal-1" header-class="header_modal" body-class="body_modal" footer-class="footer_modal" >
    <template #modal-header> <h3> <b-badge variant="secondary">{{add_comment_about_purchase_data_this.point_name}}</b-badge></h3><h3><b-badge variant="info">{{deficit}}</b-badge></h3><b-button variant="danger" @click="$bvModal.hide('modal-1')"><i class="fa fa-times fa-lg" aria-hidden="true"></i></b-button> </template>
    <div class='wrap_add_comment_into_point'>
      <div>стоимость:<input type='text' name='price' v-model="cost_of_good" id="price_field"><label for="price_field" class="text-danger pl-1" v-if="!control_length_of_cost_of_good">min одна цифра </label></div><div>комментарий</div><textarea name='description_point'  cols='40' rows='4' v-model="comment" id="description_point_field"></textarea><label for="description_point_field" class="text-danger pl-1" v-if="!control_length_of_comment">min 4 символа</label></div>
      <template #modal-footer>
        <b-button
            variant="primary"
            size="sm"
            class="float-right"
            @click="$bvModal.hide('modal-1')"
          >
            Close
          </b-button>
<b-button type="button" 
variant="success"
class="float-right"
 v-if="control_length_of_cost_of_good&&control_length_of_comment" @click="save_new_comment_about_purchase_in_out_modal">сохранить</b-button>
        </template>
      </b-modal>

      <!--модальное окно для удаления отзыва-->
      <b-modal id="bv-modal-delete-this-comment" hide-footer>
    <template #modal-title>
      <code>{{delete_this_comment_data.point_name}}</code> 
    </template>
    <div class="d-block text-center">
      <div class="wrap_note_this" ><div class="note_this">{{delete_this_comment_data.purchase_descr}}</div><div class="data_note">{{delete_this_comment_data.data_note}}</div><div class="last_price">{{delete_this_comment_data.params_value}}</div></div>
    </div>
    <b-button class="mt-3" block @click="delete_this_comment_in_mysql">удалить этот отзыв</b-button>
  </b-modal>
</div>

</template>

<script>
import axios from 'axios';
export default {
	props:['deficit'], 
  data() { 
  return{
    map_collection:{},
    delete_this_comment_data:{},
    add_comment_about_purchase_data_this:{},
    last_add_point: null,
  points_list_visible: false,
  wrap_coord_point_visible: false,
  coords_point_have_first_click: false,
  coords_new_point:[],
  lan: null,  
  lng: null,
  point_name:'',
  control_new_point_lan_lng_full:false,
  control_length_of_point_name:false,
  cost_of_good:'',
  control_length_of_cost_of_good:false,
  comment:'',
  control_length_of_comment:false,
points: [],  
  center_coords: [47.23470683868971,39.72326724340817],
  }
  },
  mounted() {// нужен ли этот блок вообще???
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
      point_name:function(){
        if (this.point_name.length<3) {
          this.control_length_of_point_name=false;
        } else {this.control_length_of_point_name=true;}
      },
      cost_of_good:function(){
        if (this.cost_of_good.length<1) {
          this.control_length_of_cost_of_good=false;
        } else {this.control_length_of_cost_of_good=true;}
      },
      comment:function(){
        if (this.comment.length<4) {
            this.control_length_of_comment=false;
        } else {this.control_length_of_comment=true;}
      },
			deficit:function(){this.drow_all_points();}
		},
   methods: {
    delete_this_comment_in_mysql(){
      console.log('hi');//stop here11.11
    },
    delete_this_comment(note,point_name){
      note.point_name=point_name;
      this.delete_this_comment_data=note;
     // let index_this_comment = e.currentTarget.getAttribute('id');
       console.log(this.delete_this_comment_data);
     
      this.$bvModal.show('bv-modal-delete-this-comment');
    },
    save_new_comment_about_purchase_in_out_modal(){
      var params = new URLSearchParams();
      let user_login=localStorage.getItem('user_login'); 
       let user_hash=localStorage.getItem('user_hash');
       let tmp_purchase_desc={};
       tmp_purchase_desc.data_note="только что";
       tmp_purchase_desc.params_value=this.cost_of_good;
       tmp_purchase_desc.purchase_descr=this.comment;
 console.log("id_point="+this.add_comment_about_purchase_data_this.id_point);
     params.append('label', 'save_new_comment_about_purchase_sql'); 
     params.append('user_login', user_login);//поставить проверку!!!
     params.append('user_hash', user_hash);
     params.append('id_point', this.add_comment_about_purchase_data_this.id_point);
     params.append('comment', this.comment);
     params.append('product_price', this.cost_of_good);     
      console.log('save_new_comment');
this.$bvModal.hide("modal-1");
      axios.post('http://avtorizmap/ajax/ajaxrequest.php', params).then(response => {
        console.log('должно записаться в базу');
        console.log(response);
        console.log("содержание массива points=");
        console.log(this.points);

        this.points.some(elem =>{ 
          if (elem.id_point==this.add_comment_about_purchase_data_this.id_point){
          console.log(elem.id_point+" =id_point ; cодержание массива elem.purchase_desc,а длина его="+Object.keys(elem.purchase_desc).length);
            elem.purchase_desc[Object.keys(elem.purchase_desc).length]=tmp_purchase_desc;
          console.log(elem.purchase_desc);
          this.point_menu_core(this.add_comment_about_purchase_data_this.id_point);
          return
          }
        });
        //this.point_menu();
      });
    },
    add_comment_about_purchase(id_point,point_name,deficit){
      console.log("comment к товару из поинта "+id_point+' '+point_name+' '+deficit);
      this.add_comment_about_purchase_data_this.id_point=id_point;
      this.add_comment_about_purchase_data_this.point_name=point_name;
      this.comment='';
      this.cost_of_good='';
      this.$bvModal.show("modal-1");

    },
    drow_all_points(){ // смена дефицита
       // this.mapInstance.myMap.geoObjects.removeAll();
      // this.map_collection.geoObjects.removeAll();//вроде сработало
      if (this.wrap_coord_point_visible) { //скрытие формы добавления поинта
        this.out_add_point();
      } 
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
      },
      point_menu_core(attrValue){
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
    point_menu(e){
      const attrValue = e.currentTarget.getAttribute('id');
       this.point_menu_core(attrValue);
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
    add_Point(){
      console.log("добавить точку");
      this.wrap_coord_point_visible=true;
      this.points_list_visible=false;
    },
    out_add_point(){
      this.wrap_coord_point_visible=false;
      this.points_list_visible=true;
      this.coords_point_have_first_click=false;//убирает временный маркер
      this.lan=null;
      this.lng=null;
      this.control_new_point_lan_lng_full=false;
      this.point_name='';
      this.control_length_of_point_name=false;
      this.cost_of_good='';
      this.control_length_of_cost_of_good=false;
      this.comment='';
      this.control_length_of_comment=false;
    },
    mapInstance(e){
      this.map_collection=e;
      console.log("i am instans");
    },
    onClick(e) {
      if (this.wrap_coord_point_visible){
      this.coords_new_point = e.get('coords');
      this.coords_point_have_first_click=true;
      //this.name_cat=this.deficit;
      this.lan=this.coords_new_point[0];
      this.lng=this.coords_new_point[1];
      console.log("координаты маркера="+ this.coords);
      this.control_new_point_lan_lng_full=true;
   this.center_coords=this.coords_new_point;   
    }
    },
    save_new_point(){
      console.log("save_new_point");
      let user_login=localStorage.getItem('user_login'); 
 let user_hash=localStorage.getItem('user_hash');
      const params = new URLSearchParams();
     params.append('label', 'save_new_marker_sql'); 
     params.append('user_login', user_login);
     params.append('user_hash', user_hash);
     params.append('description_point', this.comment);
     params.append('lan', this.lan);
     params.append('lng', this.lng);
     params.append('product_price', this.cost_of_good);
     params.append('name_point', this.point_name);
     params.append('product', this.deficit);
     axios.post('http://avtorizmap/ajax/ajaxrequest.php', params).then(response => {
     console.log(response);
     this.out_add_point();
    this.drow_all_points();
    
     });
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
.wrap_all_note_this{
   display: flex;
    flex-direction:  column-reverse ;
}
.wrap_note_this{
	display: flex;
	border:1px solid #246e1f;
	line-height: 1.1;
	font-size: 15px;
	padding: 3px;
	background-color:#bada55;
	justify-content: space-between;
  align-items: center;
}
.delete_this_note{
  padding: 4px,2px;
  margin-left:2px;
  font-size: 12px;
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
.wrap_button_point{
      height: 40px;
    text-align: center;
    margin-top: 10px;
}
/* окно добавления поинта */
.red_border{
  border: 1px solid red;
} 
.wrap_coord_point{
  border:1px solid #dc3545;
  padding: 10px;
  position: relative;

}
.wrap_coord_point input {
  margin-left: 7px;
}
.wrap_out_add_point_x {
  position: absolute;
  top:-35px;
}
.help_info_for_users_add_point {
  position: absolute;
  top:-25px;
  left: 60px;
  font-style: italic;
  border: 1px solid #ed8013;
  border-radius: 15px;
  padding-left: 5px;
  padding-right: 5px;
  background-color:#fad178;
}
/* end окно добавлния поинта */
/*модальное окно добавления отзыва*/
/deep/ .header_modal {
 background: #bad954; 
}
/deep/ .body_modal{
  background: #bad954;
}
.wrap_add_comment_into_point {
  background: #bad954;/*цвет фона меню добовления отзыва rgb(73%, 85%, 33%, 0.9)*/
  padding: 7px; 
}
/deep/ .footer_modal{
  background: #bad954;
}
/*end модальное окно добавления отзыва*/
</style>