<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>arama sayfası</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>arama sayfası</h3>
    <p> <a href="home.php">anasayfa</a> / arama </p>
</section>

<section class="search-form">
    <form action="" method="POST">
        <input type="text" class="box" placeholder="ürünleri ara..." name="search_box">
        <input type="submit" class="btn" value="Ara" name="search_btn">
    </form>
</section>

<section class="products" style="padding-top: 0;">

   <div class="box-container">

      <?php
        if(isset($_POST['search_btn'])){
         $search_box = mysqli_real_escape_string($conn, $_POST['search_box']);
         $select_products = mysqli_query($conn, "SELECT stock.id, stock.sprice, stock.date_added,products.image ,products.name, products.barcod
         FROM stock
         INNER JOIN products ON stock.product_id = products.id  WHERE products.name LIKE '%$search_box%'") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <form action="" method="POST" class="box">
        
         <div class="price">₺<?php echo $fetch_products['sprice']; ?>/-</div>
         <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
         <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_products['sprice']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
      </form>
      <?php
         }
            }else{
                echo '<p class="empty">sonuç bulunamadı!</p>';
            }
        }else{
            echo '<p class="empty">bir şey ara!</p>';
        }
      ?>

   </div>

</section>





<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>