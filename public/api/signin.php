<?php
require "cors.php";
require 'db_con.php';

$data = json_decode(file_get_contents('php://input'));


    if(!empty($data->email) && !empty($data->password)){

        $email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
    
        $password = filter_var($data->password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $sql = "SELECT * FROM users WHERE email = '$email' ";

        $result = $db_con->query($sql);

    if($result->num_rows > 0){

            $row = $result->fetch_array();

            $user_id = $row['user_id'];

            $user_email = $row['email'];

            $user_password = $row['password'];

    if($email === $user_email && md5($password) === $user_password ){

        setcookie('user_id', $user_id, time() + 86400 * 2 );

        $response = json_encode(['status'=>'success','code'=>'200','message'=>'signed in.', 'user_id' => $user_id]);
        echo $response;

    }

    }else{

        $response = json_encode(['status'=>'error','code'=>'420', 'message'=>'Invalid  Details.']);
        echo $response;

    }

    }else{

        $response = json_encode(['status'=>'error','code'=>'400','message'=>'Please Complete All Fields.']);
        echo $response;

    }
   
$db_con->close();

?>