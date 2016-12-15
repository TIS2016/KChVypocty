<?php

    $dir =  dirname(__DIR__ . "/");
    define("UPLOAD_DIR", $dir . "/sample_data/");


    if(isset($_FILES['file'])){
       $errors= array();
       $file_name = $_FILES['file']['name'];
       $file_size = $_FILES['file']['size'];
       $file_tmp = $_FILES['file']['tmp_name'];
       $file_type = $_FILES['file']['type'];
       $file_ext = strtolower(end(explode('.',$_FILES['file']['name'])));

        $uploadFile = UPLOAD_DIR . $file_name;

        if (empty($errors) == true) {
            move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile);
            chmod(UPLOAD_DIR . $file_name, 775);
            echo "Success";
       } else{
           print_r($errors);
       }
   }








