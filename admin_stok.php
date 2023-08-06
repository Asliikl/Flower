<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};


if(isset($_POST['add_stok'])){

   $tedarik_id=mysqli_real_escape_string($conn, $_POST['tedarik_id']);
   $product_id=mysqli_real_escape_string($conn, $_POST['product_id']);

   $aprice=mysqli_real_escape_string($conn, $_POST['aprice']);
   $sprice=mysqli_real_escape_string($conn, $_POST['sprice']);
   $stok = mysqli_real_escape_string($conn, $_POST['stok']);
   $firststok = mysqli_real_escape_string($conn, $_POST['stok']);
   $date_added = mysqli_real_escape_string($conn, $_POST['date_added']);
  
   $insert_stok = mysqli_query($conn, "INSERT INTO `stock`(product_id,tedarik_id,aprice,sprice,stok,firststok,date_added) VALUES('$product_id','$tedarik_id','$aprice','$sprice','$stok','$firststok','$date_added')") or die('query failed');

   $message[] = 'stok başarıyla eklendi!';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Stok</title>

   <!-- font awesome link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!--admin css   -->
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
   

<?php @include 'admin_header.php'; ?>

<section class="add-products">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Stok Giriş</h3>

      <select class="box" name="product_id">
         <option value="0" disabled selected>Ürün seçin</option>
         <?php
         $query="SELECT * FROM products ORDER BY id DESC";
         $result=mysqli_query($conn,$query);
         while($row=mysqli_fetch_assoc($result)) { ?>
         <option value="<?php echo $row['id']?>"><?php echo $row['name']?>&nbsp;(<?php echo $row['barcod']?>)
         <?php } ?>
      </select>

      <select class="box" name="tedarik_id">
         <option value="0" disabled selected>Tedarikçi seçin</option>
         <?php
         $query="SELECT * FROM tedarik ORDER BY id DESC";
         $result=mysqli_query($conn,$query);
      
         while($row=mysqli_fetch_assoc($result)) { ?>
         <option value="<?php echo $row['id']?>"><?php echo $row['tedarik_name']?>
         <?php } ?>
      </select>

      <input type="number" class="box" required placeholder="alış fiyatını girin" name="aprice">
      <input type="number" class="box" required placeholder="satış fiyatını girin" name="sprice">
      <input type="number" class="box" required placeholder="stok adedi girin" name="stok">
      <input type="date" class="box" required placeholder="tarihi girin" name="date_added">
    
      <input type="submit" value="Stoğa ekle" class="btnn" name="add_stok" >
   </form>

</section>



<section class="show-stok">

   <div class="box-container">
    
      <!--Seçenekler için bir form  -->
         <div class="box" >
            <form method="POST">
            <label style="font-size: 14px;" >Başlangıç tarihi girin</label><br><br><input class="box" type="date" name="start_date"><br><br>
            <label style="font-size: 14px;"> Bitiş tarihi girin</label><br><br>  <input class="box" type="date" name="end_date">
            <br>
            <select style="font-size: 14px; width:170px" class="box" name="product_id">
               <option  style="font-size: 14px;" value="" disabled selected>Ürün seçin</option>
               <?php
               $query="SELECT * FROM products ORDER BY id DESC";
               $result=mysqli_query($conn,$query);
               while($row=mysqli_fetch_assoc($result)) { ?>
               <option style="font-size: 14px;" value="<?php echo $row['id']?>"><?php echo $row['name']?>&nbsp;(<?php echo $row['barcod']?>)
               <?php } ?>
            </select>

            <select style="font-size: 14px;" class="box" name="tedarik_id">
               <option value="" disabled selected>Tedarikçi seçin</option>
               <?php
               $query="SELECT * FROM tedarik ORDER BY id DESC";
               $result=mysqli_query($conn,$query);
            
               while($row=mysqli_fetch_assoc($result)) { ?>
               <option value="<?php echo $row['id']?>"><?php echo $row['tedarik_name']?>
               <?php } ?>
            </select>
            
            <input type="submit" class="btnn" value="Filtrele">
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btnn btn-info">Filtreyi Temizle</a>
            </form>
         </div>

         <?php
               if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
                  $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
                  $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : NULL;
                  $tedarik_id = isset($_POST['tedarik_id']) ? $_POST['tedarik_id'] : NULL;
               
                  $query = "SELECT stock.id, stock.aprice, stock.sprice, stock.stok, stock.date_added, tedarik_name, products.name, products.barcod
                           FROM stock
                           LEFT JOIN tedarik ON stock.tedarik_id = tedarik.id
                           INNER JOIN products ON stock.product_id = products.id";
                  
                  if (!empty($start_date) && !empty($end_date)) {
                     $query .= " WHERE date_added BETWEEN '$start_date' AND '$end_date'";
                  }
                  
                  if (!is_null($tedarik_id)) {
                     $query .= " AND stock.tedarik_id = '$tedarik_id'";
                  }
                  if (!is_null($product_id)) {
                     $query .= " AND stock.product_id = '$product_id'";
                  }
                  
                  $select_stock = mysqli_query($conn, $query) or mysqli_error($conn);
                  if(mysqli_num_rows($select_stock) > 0){
                     while($fetch_stok = mysqli_fetch_assoc($select_stock)){
                        ?>
                        <div class="box">
                           <div class="phone"><label> Tedarikçi: </label><?php echo $fetch_stok['tedarik_name']; ?></div>
                           <div class="phone"><label>Ürün Adı :</label> <?php echo $fetch_stok['name']; ?>  (<?php echo $fetch_stok['barcod']; ?>)</div>
                           <div class="phone"><label>Ürün alış Fiyatı :</label> ₺<?php echo $fetch_stok['aprice']; ?></div>
                           <div class="phone"><label> Ürün satış Fiyatı :</label> ₺<?php echo $fetch_stok['sprice']; ?></div>
                           <div class="phone"><label>Ürün stok miktarı :</label> <?php echo $fetch_stok['stok']; ?></div>
                           <div class="phone"><label>Ürün giriş tarihi :</label><?php echo $fetch_stok['date_added']; ?></div>
                        </div>
                        <?php
                     }
                  } else {
                  echo '<p class="empty">Ürün bulunamadı!</p>';
               }
            
         }
      ?>
   </div>
   
</section>



<script src="js/admin_script.js"></script>

</body>
</html>