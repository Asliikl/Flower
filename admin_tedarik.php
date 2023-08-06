<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_tedarik'])){

   $tedarik_name = mysqli_real_escape_string($conn, $_POST['tedarik_name']);
   $phone = mysqli_real_escape_string($conn, $_POST['phone']);
   $mail = mysqli_real_escape_string($conn, $_POST['mail']);
   $adres = mysqli_real_escape_string($conn, $_POST['adres']);
 
   $select_tedarik_name = mysqli_query($conn, "SELECT tedarik_name FROM `tedarik` WHERE tedarik_name = '$tedarik_name'") or die('query failed');

   if(mysqli_num_rows($select_tedarik_name) > 0){
      $message[] = 'tedarikçi zaten var!';
   }else{
      $insert_tedarik = mysqli_query($conn, "INSERT INTO `tedarik`(tedarik_name, phone,mail,adres) VALUES('$tedarik_name', '$phone', '$mail','$adres')") or die('query failed');
      $message[] = 'tedarikçi eklendi!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'admin_header.php'; ?>

<section class="add-products">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Tedarikci Ekle</h3>
      <input type="text" class="box" required placeholder="tedarikçi adı girin" name="tedarik_name">
      <input type="text" name="mail" class="box" required placeholder="mail adresi girin">
      <input type="tel" name="phone" class="box" required placeholder="telefon girin">
      <input type="text" name="adres" class="box" required placeholder="adres girin">
      <input type="submit" value="tedarikçi ekle" name="add_tedarik" class="btnn">
   </form>

</section>

<?php
if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
  
   mysqli_query($conn, "DELETE FROM `tedarik` WHERE id = '$delete_id'") or die('query failed');

   header('location:admin_tedarik.php');

}

?>
<section class="show-tedarik">

   <div class="box-container">

      <?php
         $select_tedarik = mysqli_query($conn, "SELECT * FROM `tedarik`") or die('query failed');
         if(mysqli_num_rows($select_tedarik) > 0){
            while($fetch_tedarik = mysqli_fetch_assoc($select_tedarik)){
      ?>
      <div class="box">
         <div class="tedarik_name"><?php echo $fetch_tedarik['tedarik_name']; ?></div>
         <div class="phone"><?php echo $fetch_tedarik['mail']; ?></div>
         <div class="phone"><?php echo $fetch_tedarik['phone']; ?></div>
         <div class="adres"><?php echo $fetch_tedarik['adres']; ?></div>
      
         <a href="admin_tedarik.php?delete=<?php echo $fetch_tedarik['id']; ?>" class="delete-btn" onclick="return confirm('bu ürünü sil?');">sil</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">henüz ürün eklenmemiş!</p>';
      }
      ?>
   </div>
   

</section>
   

</section>












<script src="js/admin_script.js"></script>

</body>
</html>