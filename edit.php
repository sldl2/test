<?php
session_start();
require_once("connect.php");
if(isset($_GET["id"])){
  $getID=$_GET['id'];
  $query = QB::table('user')->where('id', '=', $getID);
  $row = $query->first();
}
if(isset($_POST["update"])){
$getID=$_GET['id'];
$fname=$_POST['fname'];
$email=$_POST['email'];
$password=$_POST['password'];
$image_stack=array();   
$valid_formats = array("jpg", "png", "gif", "bmp", "pdf");
$max_file_size = 1024*4200; //4000 kb / 4MB
$path = "user_images/";
$image_name_in_arry="";
// Loop $_FILES to execute all files
if ($_FILES['fils']['size'] != 0 && $_FILES['fils']['error'] != 0){
foreach ($_FILES['fils']['name'] as $f => $name) 
{  
    $file_tmp =$_FILES['fils']['tmp_name'][$f];
    $file_name = $_FILES['fils']['name'][$f];
    $file_type = $_FILES['fils']['type'][$f];
    $file_size = $_FILES['fils']['size'][$f];

    $ext = pathinfo($file_name, PATHINFO_EXTENSION); // get the file extension name like png jpg
  
    if ($_FILES['fils']['error'][$f] == 4) {
        continue; // Skip file if any error found
    }          
    if ($_FILES['fils']['error'][$f] == 0) { 
            if(move_uploaded_file($file_tmp,$path.$file_name))
                $new_dir= uniqid().rand(1000, 9999).".".$ext;                
                $new_name = rename($path.$file_name,$path.$new_dir) ; // rename file name
                array_push($image_stack,$new_dir); // file name store in array
    }               
}
    $image_name_in_arry = implode(",", $image_stack);
     
} 

if(!empty($image_name_in_arry)){

$attach=$image_name_in_arry;

}elseif(!empty($_POST["old_slide"])){

$attach=$_POST["old_slide"];

}else{
$attach="";
}

if(!empty($image_name_in_arry) && !empty($_POST["old_slide"]) ){
$path ="user_images/";
$old_images=$_POST['old_slide'];
$old_image = implode(',', $old_images);

@unlink($path.$old_image);
}

$data = array(
    'firstname' =>$fname,
    'email'=>$email,
    'password'=>$password,
    'picture'=>$attach
  );
  QB::table('user')->where('id','=',$getID)->update($data);
  $_SESSION['statu']="Data Update Sucessfully";
  header("location:index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row">
          <div class="col-md-3"></div>
        <div class="col-md-6">
            <form action="" enctype="multipart/form-data" method="post">
                <div class="mb-3">
                  <label>Name</label>
                  <input type="text" class="form-control" name="fname" value="<?php echo $row->firstname ?>">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $row->email?>" >
                  </div>

                  <div class="mb-3">
                    <label>password</label>
                    <input type="text" class="form-control" name="password" value="<?php echo $row->password?>">
                  </div>

                  <div class="mb-3">
                    <label>Image</label>
                    <input type="file" class="form-control" name="fils[]" multiple>
                    <input type="text" name="old_slide[]" value="<?php echo $row->picture?>" multiple >
                    <img  src="user_images/<?php echo $row->picture?>" height="50px" class="form-control">
                  </div>

                <button type="submit" class="btn btn-primary" name="update">Submit</button>
              </form>
        </div>
        </div>
    </div>

    



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>
</html>