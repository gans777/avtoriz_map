<template>
	<div>
		<div class="container mt-2 ">
				<div class="row row_of_avtirization">
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
<p>Логин <input  type="text" v-model="reg_login"  placeholder="придумать логин" ><b-badge class="ml-2" variant = "danger"  v-if="!control_length_of_login"> Слишком короткий. </b-badge > </p>

<p>Пароль <input type="password" v-model="reg_password"  placeholder="придумать пароль"><b-badge class="ml-2" variant = "danger"  v-if="!control_length_of_pass"> Слишком короткий. </b-badge ></p>

<button name="submit"  class="btn btn-secondary reg_button" v-on:click="enter_registration_log_pass" v-if="control_length_of_login && control_length_of_pass" > Зарегистрироваться</button>
</div>
                <div v-on:click="close_form_registration" class="close_x" ><b-icon icon="x-circle" scale="2" variant="primary"></b-icon></div>
             </div>

		</div>
		
	</div>
</template>
<script type="text/javascript">
	export	default {
		data() {
			return {
				registration_on: false,
				reg_login: null,
				reg_password: null,
				control_length_of_login: false,
				control_length_of_pass: false

			}
		},
		watch: {
			reg_login(){
				if (this.reg_login.length < 5) {
					this.control_length_of_login=false;


				} else {this.control_length_of_login=true; 
					
				}
				
			},
			reg_password:function(){
				if (this.reg_password.length < 6) {this.control_length_of_pass=false;} else {this.control_length_of_pass=true; }
				
				
			},
			check_control_reg_log_pass:function(){
				if ((this.control_length_of_pass)&&(this.control_length_of_login)) 
				{this.control_reg_log_pass=true;}  else {this.control_reg_log_pass=false; }
			}
		},
       methods:{
		registration(){
		console.log("click to reg but");
		this.registration_on=true;
		},
		avtoriz_login_pass(){
			console.log("login_pass");
			this.registration_on=false;
		},
		enter_registration_log_pass(){
			console.log("click-- Зарегистрироваться");
			console.log("v-model login="+this.reg_login);
			console.log("pass="+this.reg_password);

		},
		close_form_registration(){
			console.log("close_form_registration");
			this.registration_on=false;
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