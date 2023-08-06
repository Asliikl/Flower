<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $details = mysqli_real_escape_string($conn, $_POST['details']);
   $barcod = mysqli_real_escape_string($conn, $_POST['barcod']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folter = 'uploaded_img/'.$image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_product_name) > 0){
      $message[] = 'ürün adı zaten var!';
   }else{
      $insert_product = mysqli_query($conn, "INSERT INTO `products`(name, details, image,barcod) VALUES('$name', '$details', '$image','$barcod');") or die('query failed');

      if($insert_product){
         if($image_size > 2000000){
            $message[] = 'fotoğraf boyutu büyük!';
         }else{
            move_uploaded_file($image_tmp_name, $image_folter);
            $message[] = 'ürün başarıyla eklendi!';
         }
      }
   }

}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>ürünler</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'admin_header.php'; ?>

<section class="add-products">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Yeni Ürün Ekle</h3>
      <input type="text" class="box" required placeholder="ürün adı girin" name="name">
      <input type="text" class="box" required placeholder="barkod kodu girin" name="barcod">
      <textarea name="details" class="box" required placeholder="ürün detaylarını girin" cols="30" rows="10"></textarea>
      <input type="file" accept="image/jpg, image/jpeg, image/png" required class="box" name="image">
      <input type="submit" value="ürün ekle" name="add_product" class="btnn">
   </form>

</section>

<section class="show-products">

   <div class="box-container">

      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <div class="box">
         <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_products['name']; ?> ( <?php echo $fetch_products['barcod']; ?> )</div>
         <div class="details"><?php echo $fetch_products['details']; ?></div>
      
         <a href="admin_update_product.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">güncelle</a>
         <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('bu ürün silinsin mi?');">sil</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">henüz ürün eklenmemiş!</p>';
      }
      ?>
   </div>
   

</section>


<script src="js/admin_script.js"></script>

</body>
</html>