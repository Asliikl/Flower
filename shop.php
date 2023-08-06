<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];

    $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `shopping_cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($check_wishlist_numbers) > 0){
        $message[] = 'istek listesine zaten eklendi';
    }elseif(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'zaten sepete eklendi';
    }else{
        mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
        $message[] = 'favori listesine eklendi';
    }

}

if(isset($_POST['add_to_cart'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
  
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `shopping_cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'zaten sepete eklendi';
    }else{

        $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE user_id = '$user_id'") or die('query failed');

        if(mysqli_num_rows($check_wishlist_numbers) > 0){
            mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
        }

        mysqli_query($conn, "INSERT INTO `shopping_cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'Ürün alışveriş sepetine eklendi.';
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>MAĞAZAMIZ</h3>
    <p> <a href="home.php">anasayfa</a> / mağaza </p>
</section>

<section class="products">

<h1 class="title">Ürünlerimiz</h1>

<div class="box-container">

   <?php
      $select_products = mysqli_query($conn, "SELECT stock.id, stock.product_id, stock.sprice, stock.stok, products.name, products.details, products.image
      FROM products
      INNER JOIN stock  ON stock.product_id=products.id WHERE stock.stok > 0") or die('query failed');
      if(mysqli_num_rows($select_products) > 0){
         while($fetch_products = mysqli_fetch_assoc($select_products)){
   ?>
   <form action="" method="POST" class="box">
      <div class="price">₺<?php echo $fetch_products['sprice']; ?>/-</div>
      <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
      <div class="name"><?php echo $fetch_products['name']; ?></div>
      
      <input type="number" style="width: 100px;" name="product_quantity" value="1" min="0" class="qty">
      <input type="hidden" name="product_id" value="<?php echo $fetch_products['product_id']; ?>">
      <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
      <h1><?php echo $fetch_products['details']; ?></h1>
      <input type="hidden" name="product_price" value="<?php echo $fetch_products['sprice']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
      
      <input type="submit" value="favorilere ekle" name="add_to_wishlist" class="option-btn">
      <input type="submit" value="sepete ekle" name="add_to_cart" class="btn">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">ürünler tükendi!</p>';
   }
   ?>

</div>
</section>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>