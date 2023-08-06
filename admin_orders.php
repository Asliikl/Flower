<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};




if(isset($_POST['update_order'])){
   //sipariş durumu güncellee
   $order_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_id'") or die('query failed');

   //stok durumu güncelle
   if($update_payment == 'tamamlanmış') {
      // Siparişin ürünlerini bul
      $query = "SELECT * FROM orders WHERE id = '$order_id'";
      $result = mysqli_query($conn, $query);

      while($row = mysqli_fetch_assoc($result)) {
         $product_id = $row['product_id'];
         $quantity = $row['quantity'];

         // Mevcut stok miktarını sorgula
         $query2 = "SELECT stok FROM stock WHERE product_id = '$product_id'";
         $result2 = mysqli_query($conn, $query2);
         $row2 = mysqli_fetch_assoc($result2);
         $current_quantity = $row2['stok'];

         // Yeni stok miktarını hesapla
         $new_quantity = $current_quantity - $quantity;

         // Stok miktarını güncelle
         $update_query = "UPDATE stock SET stok = '$new_quantity' WHERE product_id = '$product_id'";
         mysqli_query($conn, $update_query);
      }
   
      $message[] = 'Stok ve sipariş durumu güncellendi!';
   }         
}


if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'admin_header.php'; ?>

<section class="placed-orders">

   <h1 class="title">Verilen Siparişler</h1>
   <div class="box-container">

    <section class="placed-orders">
      <div class="box" >
         <?php 
               $current_date = date('Y-m-d');

               $where_clause = '';
               $user_id = '';
               $product_id = '';

               if (isset($_POST['filter_submit'])) {
                  $start_date = $_POST['start_date'];
                  $end_date = $_POST['end_date'];
                  $filter_payment = $_POST['filter_payment'];

                  if (!empty($_POST['product_id'])) {
                     $product_id = $_POST['product_id'];
                     $where_clause .= " AND orders.product_id = '$product_id'";
                  }

                  if (!empty($filter_payment)) {
                     $where_clause .= " AND orders.payment_status = '$filter_payment'";
                  }

                  if (!empty($start_date) && !empty($end_date)) {
                     $where_clause .= " AND orders.placed_on BETWEEN '$start_date' AND '$end_date'";
                  }

                  if (isset($_POST['user_id'])) {
                     $user_id = $_POST['user_id'];
                     if (!empty($user_id)) {
                           $where_clause .= " AND orders.user_id = '$user_id'";
                     }
                  }
               }
         ?>
     
         
         <form method="POST">

            <label for="filter_payment" style="font-size: 16px; ">Sipariş Durumu:</label>
            <select id="filter_payment" name="filter_payment" class="box1">
               <option value="">Tümü</option>
               <option value="beklemede" <?php if(isset($_POST['filter_payment']) && $_POST['filter_payment'] === 'beklemede') echo 'selected'; ?>>Beklemede</option>
               <option value="tamamlanmış" <?php if(isset($_POST['filter_payment']) && $_POST['filter_payment'] === 'tamamlanmış') echo 'selected'; ?>>Tamamlanmış</option>
            </select>

            <select name="product_id">
                        <option value="" disabled selected>Ürün seçin</option>
                        <?php
                        $query = "SELECT * FROM products ORDER BY id DESC";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                              ?>
                              <option value="<?php echo $row['id'] ?>" <?php if (isset($_POST['product_id']) && $_POST['product_id'] == $row['id']) echo 'selected'; ?>><?php echo $row['name'] ?>&nbsp;(<?php echo $row['barcod'] ?>)</option>
                        <?php } ?>
            </select>

            <select name="user_id" id="">
               <option value="" disabled selected>Müşteri seçin</option>
               <?php
               $query="SELECT * FROM users where user_type='user' ORDER BY id ASC";
               $result=mysqli_query($conn,$query);

               while($row=mysqli_fetch_assoc($result)) { ?>
               <option value="<?php echo $row['id']?>" <?php if(isset($_POST['user_id']) && $_POST['user_id'] == $row['id']) echo 'selected'; ?>><?php echo $row['name']?></option>
               <?php } ?>
            </select>
         
            <div>
               <label style="font-size: large;">Başlangıç tarihi girin</label><br><br>
               <input class="box" type="date" name="start_date"><br><br>
            </div>
            <div>
               <label style="font-size: large;">Bitiş tarihi girin</label><br><br>  
               <input class="box" type="date" name="end_date">
            </div>
            &nbsp; <button type="submit" name="filter_submit" class="btnn">Filtrele</button>
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btnn btn-info">Filtreyi Temizle</a>
         </form>
      </div> <br><br>

     
      <div class="box-container ">
               <?php
                     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  $select_orders = mysqli_query($conn, "SELECT 
                  orders.id, orders.user_id, orders.method, orders.total_products, orders.currentadres, orders.total_price, 
                  users.name, orders.payment_status, users.number, users.email, orders.address, orders.placed_on
                  FROM orders
                  INNER JOIN products ON orders.product_id=products.id
                  INNER JOIN users ON orders.user_id=users.id
                  $where_clause
                  " . (!empty($user_id) ? "AND orders.user_id = '$user_id'" : "") . "
                  ORDER BY orders.placed_on DESC") or die(mysqli_error($conn));
                     if(mysqli_num_rows($select_orders) > 0){
                        while($fetch_orders = mysqli_fetch_assoc($select_orders)){
               ?>
            <div class="box">
               <p> işlem tarihi : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
               <p> ad : <span><?php echo $fetch_orders['name']; ?></span> </p>
               <p> telefon : <span><?php echo $fetch_orders['number']; ?></span> </p>
               <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
               <p> adres : <span><?php echo !empty($fetch_orders['currentadres']) ? $fetch_orders['currentadres'] : $fetch_orders['address']; ?></span></p>
               <p> toplam ürün : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
               <p> toplam fiyat: <span>₺<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
               <p> ödeme yöntemi: <span><?php echo $fetch_orders['method']; ?></span> </p>
               <form action="" method="post">
                  <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                  <select name="update_payment"  id="order-status" >
                     <option disabled selected><?php echo $fetch_orders['payment_status']; ?></option>
                     <option value="beklemede">beklemede</option>
                     <option value="tamamlanmış">tamamlanmış</option>
                 </select>
                 <input type="submit" name="update_order" value="Güncelle" class="option-btn"> <br><br><br>
               </form>
           </div>
           <?php } }  }?>
      </div>
      </section>
   </div>

</section>


<script src="js/admin_script.js"></script>

</body>
</html> 