<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Panel</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title"></h1>

   <div class="box-container">

      <div class="box">
         <?php
            $total_pendings = 0;   // toplamda bekleyen sayısı
            $select_pendings = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'beklemede'") or die('query failed');
            while($fetch_pendings = mysqli_fetch_assoc($select_pendings)){  //ödeme durumu 
               $total_pendings += $fetch_pendings['total_price']; //toplam fiyat
            };
         ?>
         <h3>₺<?php echo $total_pendings; ?>/-</h3>
         <p> beklenen ödeme tutarı</p>
      </div>

      <div class="box">
         <?php
         //tamamlanmış ödeme tutar
            $total_completes = 0;
            $select_completes = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'tamamlanmış'") or die('query failed');
            while($fetch_completes = mysqli_fetch_assoc($select_completes)){
               $total_completes += $fetch_completes['total_price'];
            };
         ?>
         <h3>₺<?php echo $total_completes; ?>/-</h3>
         <p>tamamlanmış ödeme tutarı</p>
      </div>

      <div class="box">
         <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
         ?>
         <h3><?php echo $number_of_orders; ?></h3>
         <p>verilen siparişler</p>
      </div>

      <div class="box">
         <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `stock`") or die('query failed');
            $number_of_products = mysqli_num_rows($select_products);
         ?>
         <h3><?php echo $number_of_products; ?></h3>
         <p>stoktaki ürünler</p>
      </div>

      <div class="box">
         <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
            $number_of_users = mysqli_num_rows($select_users);
         ?>
         <h3><?php echo $number_of_users; ?></h3>
         <p>normal Kullanıcılar</p>
      </div>

      <div class="box">
         <?php
            $select_admin = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
            $number_of_admin = mysqli_num_rows($select_admin);
         ?>
         <h3><?php echo $number_of_admin; ?></h3>
         <p>admin Kullanıcılar</p>
      </div>

      <div class="box">
         <?php
            $select_account = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
            $number_of_account = mysqli_num_rows($select_account);
         ?>
         <h3><?php echo $number_of_account; ?></h3>
         <p>toplam hesaplar</p>
      </div>

      <div class="box">
            <?php
              //en çok satan ürün bulma
               $select_favorite = mysqli_query($conn, "SELECT p.name AS product_name, p.barcod, COUNT(*) as quantity 
                                                      FROM orders o 
                                                      LEFT JOIN products p ON o.product_id = p.id 
                                                      GROUP BY o.product_id 
                                                      ORDER BY quantity DESC 
                                                      LIMIT 1");

               // Sorgu sonucunda en çok satılan ürün varsa, sonucu yazdırmadan önce kontrol eder
               if (mysqli_num_rows($select_favorite) > 0) {
                  $row = mysqli_fetch_assoc($select_favorite);
                  $product_name = $row["product_name"];
                  $barcod = $row["barcod"];
               } else {
                  $product_name = "Kayıt bulunamadı.";
                  $barcod = "";
               }
            ?>
            <h3 style="font-size: 25px;  color:deeppink;"><?php echo $product_name ?></h3> <h3 style="font-size: 15px;">( <?php echo $barcod ?>)</h3>
            <p> Favori Ürün</p>
         </div>

         <div class="box">
            <?php
               $select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
               $number_of_messages = mysqli_num_rows($select_messages);
            ?>
            <h3><?php echo $number_of_messages; ?></h3>
            <p> mesajlar</p>
         </div>

   </div>

</section>













<script src="js/admin_script.js"></script>

</body>
</html>