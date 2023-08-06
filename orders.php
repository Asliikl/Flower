<?php
@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php @include 'header.php'; ?>

   <section class="heading">
      <h3>siparişleriniz</h3>
      <p> <a href="home.php">anasayfa</a> / siparişler </p>
   </section>

   <section class="placed-orders">

      <?php
      $where_clause = '';

      if (isset($_POST['filter_submit'])) {
         $start_date = $_POST['start_date'];
         $end_date = $_POST['end_date'];
         $filter_payment = $_POST['filter_payment'];

         if (!empty($filter_payment)) {
            $where_clause .= " AND orders.payment_status = '$filter_payment'";
         }

         if (!empty($start_date) && !empty($end_date)) {
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            $where_clause .= " AND DATE(orders.placed_on) BETWEEN '$start_date' AND '$end_date'";
         }
      }
      ?>

      <h1 class="title">verilen siparişler</h1>

      <div class="box1" style="padding: 2rem; border: var(--border); background-color: var(--white); box-shadow: var(--box-shadow); border-radius: .5rem; display: inline-block; margin-left: 38%; vertical-align: top;">
         <form method="POST" style="font-size: large;">
            <label for="filter_payment" >Sipariş Durumu:</label>&nbsp;&nbsp;
            <select id="filter_payment" name="filter_payment" class="box1"  style="font-size: medium;">
               <option value="">Tümü</option>
               <option value="beklemede" <?php if (isset($_POST['filter_payment']) && $_POST['filter_payment'] === 'beklemede') echo 'selected'; ?>>Beklemede</option>
               <option value="tamamlanmış" <?php if (isset($_POST['filter_payment']) && $_POST['filter_payment'] === 'tamamlanmış') echo 'selected'; ?>>Tamamlanmış</option>
            </select> 

            <div> <br>
               <label style="font-size: large;" >Başlangıç tarihi girin</label>&nbsp;&nbsp;
               <input class="box" type="date" name="start_date" value="<?php echo isset($start_date) ? $start_date : ''; ?>"><br><br>
            </div>
            <div>
               <label style="font-size: large;">Bitiş tarihi girin</label>&nbsp;&nbsp;
               <input class="box" type="date" name="end_date" value="<?php echo isset($end_date) ? $end_date : ''; ?>">
            </div> <br> &nbsp; 
            <button type="submit" name="filter_submit" class="btn btn-danger" style="margin-left:28%;">Göster</button>
         </form>
      </div>  <br><br>


      <div class="box-container">
         <?php 
         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
               
         $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE 1=1 $where_clause " . (!empty($user_id) ? "AND orders.user_id = '$user_id'" : "") . " ORDER BY orders.placed_on DESC") or die(mysqli_error($conn));

         if (mysqli_num_rows($select_orders) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
         ?>
            <div class="box">
               <p> işlem tarihi: <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
               <p> adres: <span><?php echo !empty($fetch_orders['currentadres']) ? $fetch_orders['currentadres'] : $fetch_orders['address']; ?></span></p>
               <p> ödeme yöntemi: <span><?php echo $fetch_orders['method']; ?></span> </p>
               <p> aldıkların: <span><?php echo $fetch_orders['total_products']; ?></span> </p>
               <p> toplam fiyat: <span>₺<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
               <p> sipariş durumu: <span style="color:<?php if ($fetch_orders['payment_status'] == 'beklemede') {
                                       echo 'tomato';
                                    } else {
                                       echo 'green';
                                    } ?>"><?php echo $fetch_orders['payment_status']; ?></span> </p>
            </div>
            <?php
                  }
               } else {
                  echo '<p class="empty">henüz sipariş verilmedi!</p>';
               }
               }
            ?>
      </div>
   </section>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>

</html>

