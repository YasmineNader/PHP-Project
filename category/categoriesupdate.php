<?php
require "../connection/dbconnection.php";

$id=filter_var($_GET["id"],FILTER_SANITIZE_NUMBER_INT);
$query="select * from categories where id=".$id;
$sql=mysqli_query($con,$query);
if($sql){
    $operation=mysqli_fetch_assoc($sql);
}else{
    echo "no data related to this id";

}

$errorcategory=$errorimage="";
function clear($field){
    return trim(htmlspecialchars(stripslashes($field)));
  }
  
function validateCategory($category){

   if(empty($category)){

        return "Please Enter Category";
  
  
      }else{
        $category=filter_var($category,FILTER_SANITIZE_STRING);
        $pattern  = "/^[a-zA-Z\s*]+$/";

        if(!preg_match($pattern,$category)){

        
        return "Invalid Category";
         
          
  
          
      }

}
}


$imagname="";
function validateImage($image){

   if(empty($image)){

        return "Please Enter Image";
  
  
      }else{
          
          $fileTmpName=$_FILES["image"]["tmp_name"];
          $fileName=$_FILES["image"]["name"];
          $fileSize=$_FILES["image"]["size"];
          $fileType=$_FILES["image"]["type"];
          $fileExt=explode(".",$fileName);
          $count=count($fileExt);
          $extention=strtolower($fileExt[$count-1]);
          $ext_allow=array("png","jpg","jpeg","gif");
          global $imagname;
          $imagname=time().$fileExt[0].".".$extention;
      
          if(in_array($extention,$ext_allow)){
          $fileUploads="../uploads/";
          $imgPath=$fileUploads.$imagname;
          move_uploaded_file($fileTmpName,$imgPath);
         
          
  
          }
      }

}


if($_SERVER["REQUEST_METHOD"]=="POST"){
    $id=$_POST["id"];
    $category=clear($_POST['category']);
    $errorcategory=validateCategory($category);
    $oldimage=$_POST['oldimage'];
    $errorimage=validateImage($_FILES["image"]["name"]);
    $image=$imagname;
  
 

   if(empty($errorcategory)&& empty($errorimage)){
    if(file_exists('../uploads/'.$oldimage)){
      unlink('../uploads/'.$oldimage);
  }

    $sql="update categories set name = '$category',image ='$image' where id= " .$id;
    $query=mysqli_query($con,$sql);
    
    if($query){
      
        echo "<div class='alert alert-success' role='alert'>Category Updated</div>";
    }else{
      
        echo "<div class='alert alert-danger' role='alert'>Error in Updating Category</div> ";
    
    }

}


}

?>



<?php include "../layout/headeradmin.php";?>
<?php include "../layout/navbaradmin.php";?>
  
  <?php if(isset($_SESSION["Name"])){?>
<div class="container ">
<h3>Update Category</h3>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$id?>" enctype='multipart/form-data' >
        
<input type="hidden" name="id" value="<?php echo $operation['id']?>">
<div class="form-floating mb-3">
              <input type="text" class="form-control" id="category" placeholder="Category" name="category" value="<?php echo $operation['name'] ?>">
              <label for="Category" class="ms-2">Category</label>
              <div class="error"><?php echo $errorcategory;?></div>

            </div>
 
            <div class="mb-3">
      <label for="image" class=" form-label ms-2">Image</label>
       <input type="file" class="form-control" name="image" id="image" >

      
       <div><img class="imageadmin" src="<?php echo ('../uploads/'.$operation['image']);?>"></div>
      
       <input type="hidden" name="oldimage" value="<?php echo $operation['image']?>">
       
        <div class="error"><?php echo $errorimage;?></div>

      </div>
    <button type="submit"  class="btn btn-primary mt-3 w-100 " >Update</button>
        
    <div>
    <a href="categoriesdisplay.php" class="btn btn-dark mt-3 w-100">Back</a>
</div>
</form>
</div>
</div>
<?php include '../layout/footer.php'?>
<?php } else{header("location: ../admins/login.php");} ?>