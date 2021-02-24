<template>
	<div>
		<div class="container mt-2 ">
				<div class="row row_of_avtirization" v-if="row_of_avtorization">
		<div class="col">
	<button type="button" class="btn btn-outline-primary " v-on:click="registration()">регистрация</button>
</div>
    <div class="col">
    <button type="button" class="btn btn-outline-primary" v-on:click="avtoriz_login_pass()">авторизация</button>
    </div>
    
</div>

   <div class="col reg_and_avt m-3 p-2" v-if="registration_on" > 
             <div class="reg_form avtoriz_form">
	<div class="system_tablo_mess"></div>
	<div class="mess_create_log_and_pass">Создайте логин и пароль:</div>
<p>Логин <input  type="text" v-model="reg_login"  placeholder="придумать логин" ><b-badge class="ml-2" variant = "danger"  v-if="!control_length_of_login"> Слишком короткий. </b-badge > <b-badge class="ml-2" variant = "danger"  v-if="login_have"> Такой логин уже существует.</b-badge >  </p>

<p>Пароль <input type="password" v-model="reg_password"  placeholder="придумать пароль"><b-badge class="ml-2" variant = "danger"  v-if="!control_length_of_pass"> Слишком короткий. </b-badge ></p>

<button name="submit"  class="btn btn-secondary reg_button" v-on:click="enter_registration_log_pass" v-if="control_length_of_login && control_length_of_pass" > Зарегистрироваться</button>
</div>
                <div v-on:click="close_form_registration" class="close_x" ><b-icon icon="x-circle" scale="2" variant="primary"></b-icon></div>
             </div>


<div class="reg_and_avt m-3 p-2" v-if="avtorization_on">
	<p>Логин <input  type="text" v-model="login"  placeholder=" логин" ><b-badge class="ml-2" variant = "danger"  >  </b-badge > </p>

<p>Пароль <input type="password" v-model="password"  placeholder=" пароль"><b-badge class="ml-2" variant = "danger" > </b-badge ></p>
<button name="submit"  class="btn btn-secondary reg_button" v-on:click="enter_avt_log_pass" v-if="control_length_of_avt_login && control_length_of_avt_pass" >   вход   </button>
 <div v-on:click="close_form_registration" class="close_x" ><b-icon icon="x-circle" scale="2" variant="primary"></b-icon></div>
</div>

<div class="row row_of_user" v-if="login_on">
	<div class="col">
      <div class="wrap_user_name">
      <button type="button" class="btn btn-primary user_login">{{user_login}}</button>
      <button type="button" class="btn btn-link log_out" v-on:click="exit">выход</button>
    </div>
    </div>
</div><!--end row_of_user-->
		</div>
		
	</div>
</template>
<script type="text/javascript">
	import axios from 'axios';
	export	default {
		data() {
			return {
				registration_on: false,
				avtorization_on: false,
				reg_login: null,
				reg_password: null,
				login: null,
				password: null,
				control_length_of_login: false,
				control_length_of_pass: false,
				control_length_of_avt_login: false,
				control_length_of_avt_pass: false,
				row_of_avtorization: true,
				login_on: false,
				user_login: null,
				login_have: false
			}
		},
		mounted() {
 this.user_login=localStorage.getItem('user_login');
 let user_hash=localStorage.getItem('user_hash');
 console.log(user_hash+' '+this.user_login);
 var params = new URLSearchParams();
     params.append('label', 'check_user_hash'); //записать  логин\пароль user's
     params.append('user_login', this.user_login);
     params.append('user_hash', user_hash);
      axios.post('http://avtorizmap/ajax/ajaxrequest.php', params).then(response => {console.log(response);
      if (response.data.user_hash_match) {
      this.row_of_avtorization=false;
      this.login_on=true;
      this.user_login=response.data.user_login;
      }
      });
		},
		watch: {
			reg_login:function()     {
				if (this.reg_login.length < 5) {
					this.control_length_of_login=false;


				} else {this.control_length_of_login=true; 
					
				}
				
			},
			reg_password:function(){
				if (this.reg_password.length < 6) {this.control_length_of_pass=false;} else {this.control_length_of_pass=true; }
			},
			login:function(){
				if(this.login.length<5) {this.control_length_of_avt_login=false;} else {this.control_length_of_avt_login=true;}
			},
			password:function(){
				if(this.password.length<6){this.control_length_of_avt_pass=false;}else {this.control_length_of_avt_pass=true;}
			}
		},
       methods:{
		registration(){
		this.registration_on=true;
		this.avtorization_on=false;
		},
		avtoriz_login_pass(){
			console.log("login_pass");
			this.registration_on=false;
			this.avtorization_on=true;
		},
		enter_registration_log_pass(){
			console.log("click-- Зарегистрироваться");
			console.log("v-model login="+this.reg_login);
			console.log("pass="+this.reg_password);
			var params = new URLSearchParams();
     params.append('label', 'register_new_user'); //записать  логин\пароль user's
     params.append('login', this.reg_login);
     params.append('password',this.reg_password);
      axios.post('http://avtorizmap/ajax/ajaxrequest.php', params).then(response => {console.log(response);
     if (response.data.login_have) {
     this.login_have=true;
     }
     if (response.data.saved){
     this.login_have=false;
     this.row_of_avtorization=false;
     this.registration_on=false;
      this.login=this.reg_login;
      this.password=this.reg_password;
      this.enter_avt_log_pass();
     }
      })

		},
		enter_avt_log_pass(){
			console.log(this.login +' '+this.password);
			var params = new URLSearchParams();
     params.append('label', 'enter_log_pass'); //записать  логин\пароль user's
     params.append('login', this.login);
     params.append('password',this.password);
      axios.post('http://avtorizmap/ajax/ajaxrequest.php', params).then(response => {console.log(response.data.wrong_log_pass);
      if (!response.data.wrong_log_pass) {
      console.log('user_hash= '+ response.data.user_hash + ' login= '+response.data.user_login);
      this.row_of_avtorization=false;
      this.avtorization_on=false;
      this.login_on=true;
      this.user_login=response.data.user_login;
      localStorage.setItem('user_login', response.data.user_login);
      localStorage.setItem('user_hash', response.data.user_hash); 

      } else {console.log('логин/пароль ошибочен');}
      })
		},
		close_form_registration(){
			console.log("close_form_registration");
this.registration_on=false;
this.avtorization_on=false;
		},
		exit(){
		localStorage.removeItem('user_login');
		localStorage.removeItem('user_hash');
		this.registration_on=false;
		this.row_of_avtorization=true;
		this.login_on=false;
        this.login=null;
		}
		

	}
}
</script>
<style type="text/css">
	.reg_and_avt {
		position: relative;
		min-width: 500px;
		border:2px solid #007bff ;
		border-radius: 12px;
	}
	.close_x{
		position: absolute;
		top:0;
		right: 1%;
	}
</style>