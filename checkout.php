<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['order'])){
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn,  $_POST['flat']. $_POST['city']. $_POST['country']. $_POST['pin_code']);
    $placed_on = date('d-M-Y');
 
    $currentadres= mysqli_real_escape_string($conn, $_POST['currentadres']);
    $cart_no = mysqli_real_escape_string($conn, $_POST['cart_no']);
    $cart_name = mysqli_real_escape_string($conn, $_POST['cart_name']);
    $cvc = mysqli_real_escape_string($conn, $_POST['cvc']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);

    $cart_total = 0;
    $cart_products = array();      
    $products_quantity = array();  
    $product_ids = array();       
    
    $cart_query = mysqli_query($conn, "SELECT * FROM `shopping_cart` WHERE user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($cart_query) > 0) {
        $cart_items = array();
        $cart_total = 0;
        $products_quantity = 0; // Initialize  total quantity
    
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_items[] = $cart_item;
            $product_id = $cart_item['pid'];
            $sub_total = $cart_item['price'] * $cart_item['quantity'];
            $cart_total += $sub_total;
            $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') '; 
            $products_quantity += $cart_item['quantity']; 
            $product_ids[] = $cart_item['pid']; 
        }
    }
    
    $placed_on = date('Y-m-d H:i:s');
    
    $total_products = implode('', $cart_products);
    $quantity = $products_quantity; 
    $product_ids = implode('', $product_ids);
    
    
    // Diğer işlemler ve veritabanına ekleme işlemi
    $product_ids = array(); 
    foreach ($cart_items as $cart_item) {
        $product_ids[] = $cart_item['pid'];
    }
    
    //ödeme işlemleri form sayfası 
    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE  method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

    if($cart_total == 0){
        $message[] = 'Sepetiniz boş!';
    }elseif(mysqli_num_rows($order_query) > 0){
        $message[] = 'sipariş zaten verildi!';
    }else{
      
         
      
    foreach ($cart_items as $cart_item) {
        $product_id = $cart_item['pid'];

        // Sipariş ekleme
        mysqli_query($conn, "INSERT INTO `orders` (user_id, product_id, quantity, method, address, currentadres, total_products, total_price, placed_on) 
            VALUES ('$user_id', '$product_id', '$quantity', '$method', '$address', '$currentadres', '$total_products', '$cart_total', '$placed_on')");
    }
}
        mysqli_query($conn, "INSERT INTO `cart`(user_id,cart_name,cart_no, cvc, date)
        VALUES('$user_id','$cart_name', '$cart_no', '$cvc', '$date')")  or die('query failed');

        mysqli_query($conn, "DELETE FROM `shopping_cart` WHERE user_id = '$user_id'") or die('query failed');
        $message[] = 'sipariş başarıyla verildi!';
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>ödeme işlemleri</h3>
    <p> <a href="home.php">Anasayfa</a> / Çıkış yap </p>
</section>

<section class="display-order">
    <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `shopping_cart` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
    ?>    
    <p> <?php echo $fetch_cart['name'] ?> <span>(<?php echo '₺'.$fetch_cart['price'].'/-'.' x '.$fetch_cart['quantity']  ?>)</span> </p>
    <?php
        }
        }else{
            echo '<p class="empty">Sepetiniz boş</p>';
        }
    ?>

    <div class="grand-total">Genel Toplam : <span>₺<?php echo $grand_total; ?>/-</span></div>
</section>

<section class="checkout">

    <form action="" method="POST">

        <h3>Siparişiniz</h3>

        <div class="flex">
    
        <div class="inputbox"> 
            <div class="inputBox">
                <span>ödeme yöntemi :</span>
                <select class="box" id="kredi-karti-sec" name="method">
                    <option value="kapıda ödeme">kapıda ödeme</option>,
                    <option value="credit_card">kredi kartı</option>
                </select>
            </div>
  
           <div class="inputBox" id="kart-bilgi-formu" style="display:none;">
               <div class="inputBox">
                   <span for="cart_name">Kart Üzerindeki İsim:</span>
                   <input type="text" id="cart_name" name="cart_name">
               </div>
               <div class="inputBox">
                   <span for="cart_no">Kart numarası:</span>
                   <input type="text" id="cart_no" name="cart_no">
               </div>
               <div class="inputBox"> 
                   <span for="date">Son kullanma tarihi:</span>
                   <input type="date" id="date" name="date">
               </div>
               <div class="inputBox"> 
                   <span for="cvc">Cvv:</span>
                   <input type="text" id="cvc" name="cvc">
               </div>
           </div>
       </div>

       <div class="inputBox"> 
        
            <!-- var olan veritabanında kayıtlı olan adres kodu -->
            <div class="inputBox">
                <select name="currentadres">
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
                    if(mysqli_num_rows($query) > 0){
                        while($cart_item = mysqli_fetch_assoc($query)){
                            echo '<option value="" disabledselected>Kayıtlı adresinizi seçin</option>';  
                            echo '<option value="'.$cart_item['adress'].'">'.$cart_item['adress'].'</option>';    
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="inputBox">
                <span>adresinizi yazın :</span>
                <input type="text" name="flat" placeholder="">
            </div>  
            <div class="inputBox">
                <span>şehir :</span>
                <input type="text" name="city" placeholder="">
            </div>   
            <div class="inputBox">
                <span>ilçe :</span>
                <input type="text" name="state" placeholder=" ">
            </div>
            <div class="inputBox">
                <span>ülke :</span>
                <input type="text" name="country" placeholder="">
            </div>
            <div class="inputBox">
                <span>il kodu:</span>
                <input type="number" min="0" name="pin_code" placeholder="">
            </div>
            
        </div>
        </div>
        <input type="submit" name="order" value="şimdi sipariş ver" class="btn">

    </form>

</section>





<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $(document).ready(function() {
    $('#kredi-karti-sec').change(function() {
      if ($(this).val() === 'credit_card') {
        $('#kart-bilgi-formu').show();
      } else {
        $('#kart-bilgi-formu').hide();
      }
    });
  });
</script>

</body>
</html>