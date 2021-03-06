<?php

include '../functions/functions.php';
include "../functions/connect.php";
include "../functions/create_tables.php";

if ($_POST['label']=='control_new_login'){
  $query = mysqli_query($link, "SELECT user_id FROM deficit_users WHERE user_login='".mysqli_real_escape_string($link, $_POST['login'])."'");
    if(mysqli_num_rows($query) > 0)
    {
        //Пользователь с таким логином уже существует в базе данных
        echo json_encode(array('login_have' => true ));
    }

}
if ($_POST['label']=='register_new_user'){ 
  $err = [];
      // проверяем, не сущестует ли пользователя с таким именем
    $query = mysqli_query($link, "SELECT user_id FROM deficit_users WHERE user_login='".mysqli_real_escape_string($link, $_POST['login'])."'");
    if(mysqli_num_rows($query) > 0)
    {
        $err[] = "login_have";//Пользователь с таким логином уже существует в базе данных
        echo json_encode(array('login_have' => true, 'saved'=>false ));
    }

     if(count($err) == 0)
    {

        $login = $_POST['login'];
        // Убераем лишние пробелы и делаем двойное хеширование
        $password = md5(md5(trim($_POST['password'])));

        mysqli_query($link,"INSERT INTO deficit_users SET user_login='".$login."', user_password='".$password."'");
        echo json_encode(array('login_have' => false, 'saved'=> true));
    }

}

if ($_POST['label']=='enter_log_pass'){
	  $login=$_POST['login'];
	   $password=$_POST['password'];

	   // Вытаскиваем из БД запись, у которой логин равняеться введенному
    $query = mysqli_query($link,"SELECT user_id, user_password FROM deficit_users WHERE user_login='".mysqli_real_escape_string($link,$login)."' LIMIT 1");
    
    $data = mysqli_fetch_assoc($query);
    
    // Сравниваем пароли
    if($data['user_password'] === md5(md5($password))) {
    	//echo "пароли совпали";

    	  // Генерируем случайное число и шифруем его
        $hash = md5(generateCode(10));

        // Записываем в БД новый хеш авторизации и IP
        @mysqli_query($link, "UPDATE deficit_users SET user_hash='".$hash."' "." WHERE user_id='".$data['user_id']."'");
          
         echo json_encode(array('user_hash' => $hash,'user_login' => $login ,'wrong_log_pass'=>false));

    } else { echo json_encode(array('wrong_log_pass' => true ));}
}

 if ($_POST['label'] == 'check_user_hash') {
 	$login = $_POST['user_login'];
 	 $query = mysqli_query($link,"SELECT user_hash FROM deficit_users WHERE user_login='".mysqli_real_escape_string($link,$login)."' LIMIT 1");
      $data = mysqli_fetch_assoc($query);
       if ($data['user_hash'] == $_POST['user_hash']) {
       	echo json_encode(array('user_hash_match'=>true,'user_login'=>$login));
       } else {echo json_encode(array('user_hash_match'=>false));}
 }

/*считывает все наименования дефицитов*/
 if ($_POST['label'] == 'read_deficit_products') {
  $sql = "SELECT name_of_product FROM deficit_products";
               $res = mysqli_query($link,$sql);
               $all_products = MysqliFetchAll($res);
               echo json_encode($all_products);
 }

if ($_POST['label'] =='read_markers_sql'){
   $hash=$_POST['user_hash'];
    $user_login=$_POST['user_login'];
     $response = check_hash($link,$hash,$user_login); 
  if (($response == $user_login)&&isset($_POST['product'])) {
   $product=$_POST['product'];
    $sql = "SELECT id_point,lan,lng,name FROM deficit_points WHERE product='".$product."'";
     $res = mysqli_query($link,$sql);
          $all_points = MysqliFetchAll($res);
       

       
     for($j=0;$j<count($all_points);$j++) {
      
     
        $id_point = $all_points[$j]['id_point'];
          $sql = "SELECT purchase_descr,data_note,id_note,id_point FROM deficit_note WHERE id_point=".$id_point;
          $res = mysqli_query($link,$sql);
          $all_this_note = MysqliFetchAll($res);
            
          
          for($i=0; $i< count($all_this_note);$i++){
             $sql = "SELECT params_value FROM deficit_products_parametrs WHERE id_note=".$all_this_note[$i]['id_note'];
           $res = mysqli_query($link,$sql);
           $this_note_price=MysqliFetchAll($res);
           //$all_this_note[$i]['price']= $this_note_price[0]['params_value'];
            $all_this_note[$i]['params_value']= $this_note_price[0]['params_value'];
            array_push($all_points[$j],$all_this_note[$i]);
          }
         
     }
   
  
 echo json_encode($all_points);
 }  else {
  if (!isset($_POST['product'])) {$product=false;}
  echo json_encode(array('not_autorization'=>false,'product'=>$product));
}
}

?>