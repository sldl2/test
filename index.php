<?php
session_start();
require_once("connect.php");


if(isset($_POST["submit"])){
$fname=$_POST['fname'];
$email=$_POST['email'];
$password=$_POST['password'];
// $picture=explode('.',$_FILES['picture']['name']);
// $picture_ex=end($picture);
// $picture_name=time().'.'.$picture_ex;
// move_uploaded_file($_FILES['picture']['tmp_name'],'user_images/'.$picture_name);
// print_r($picture_name);

$image_stack=array();   
$valid_formats = array("jpg", "png", "gif", "bmp", "pdf");
$max_file_size = 1024*4200; //4000 kb / 4MB
$path = "user_images/";
$image_name_in_arry="";
var_dump($path);
var_dump($image_name_in_arry);
// Loop $_FILES to execute all files
if ($_FILES['fils']['size'] != 0 && $_FILES['fils']['error'] != 0){

foreach ($_FILES['fils']['name'] as $f => $name) 
{  
    $file_tmp =$_FILES['fils']['tmp_name'][$f];
    $file_name = $_FILES['fils']['name'][$f];
    $file_type = $_FILES['fils']['type'][$f];
    $file_size = $_FILES['fils']['size'][$f];

    $ext = pathinfo($file_name, PATHINFO_EXTENSION); // get the file extension name like png jpg
   var_dump($ext);
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
    var_dump($image_name_in_arry);
   
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
$old_image=$_POST['old_slide'];
@unlink($path.$old_image);
}
$data = array(
  'firstname' => $fname,
  'email'=>$email,
  'password'=>$password,
  'picture'=>$attach
);
QB::table('user')->insert($data);
// $_SESSION['status']="Data Add Sucessfully";

}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <!-- <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  </head>
  <body>
   

 <div class="container">
    <div class="row">
      
      
        <div class="col-md-6">
        
            <form action="" enctype="multipart/form-data" method="post">
    <!-- <script type="text/javascript">
    $(document).ready(function() {
        swal({
            title: "User created!",
            text: "Suceess message sent!!",
            icon: "success",
            button: "Ok",
            timer: 2000
        });
    });
</script> -->
                <div class="mb-3">
                  <label>Name</label>
                  <input type="text" class="form-control" name="fname" >
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" >
                  </div>

                  <div class="mb-3">
                    <label>password</label>
                    <input type="password" class="form-control" name="password">
                  </div>
                  <div class="mb-3">
                    <label>Upload Image</label>
                    <input type="file" class="form-control" name="fils[]" multiple>
                  </div>

                <button type="submit" class="btn btn-primary" name="submit" onclick="sucess_toast()">Submit</button>
              </form>
        </div>
        <div class="col-md-6">
        <?php
          if(isset($_SESSION['statu'])){
            ?>
            <div class="alert alert-success text-center" role="alert">
          <?php echo $_SESSION['statu'];?>
        </div>
            <?php
            unset($_SESSION['statu']);
          }
          ?>
          <h4 class="text-center bg bg-info mt-2">View All Data</h4>
        <table class="table">
  <thead>
  
 <tr>
  <th>Name</th>
  <th>Email</th>
  <th>Password</th>
  <th>Image</th>
  <th>Action</th>
 </tr>
  </thead>
  <?php
          $query = QB::table('user')->get();
          //$query1 = QB::query("SELECT * FROM user")->get();
        //  $query="SELECT * FROM 'user'";
        //var_dump($query1);
          ?>
  <tbody>
  <?php
    foreach($query as $row){
   ?>
    <tr>
      <td><?php echo $row->firstname; ?></td>
      <td><?php echo $row->email;?></td>
      <td><?php echo $row->password;?></td> 
      <td><img src="user_images/<?php echo $row->picture;?>" alt="" width="100px" height="50px"></td>
      <td><a href="edit.php?id=<?php echo $row->id;?>"  class="btn btn-primary">Edit</a>
  <a type="button" href="delete.php?id=<?php echo $row->id;?>" class="btn btn-danger">Delete</a>
    </tr>
<?php
    }
    ?>
  </tbody>
</table>
        </div>
    </div>
 </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
  </body>
</html>

