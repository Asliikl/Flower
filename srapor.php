<?php

@include 'config.php';

session_start(); 

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

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
   <div class="box-container">

    <section>
         <div class="box" >
            <?php 
              $current_date = date('Y-m-d');
              $where_clause = '';
              $product_id = '';
              $user_id = '';
              
              if (isset($_POST['filter_submit'])) {
                  $start_date = $_POST['start_date'];
                  $end_date = $_POST['end_date'];
              
                  if (!empty($_POST['product_id'])) {
                      $product_id = $_POST['product_id'];
                      $where_clause .= " AND o.product_id = '$product_id'";
                  }
              
                  if (!empty($start_date) && !empty($end_date)) {
                      $where_clause .= " AND o.placed_on BETWEEN '$start_date' AND '$end_date'";
                  }
              
                  if (isset($_POST['user_id'])) {
                      $user_id = $_POST['user_id'];
                      if (!empty($user_id)) {
                          $where_clause .= " AND o.user_id = '$user_id'";
                      }
                  }
              }
                  
            ?>
     
         
                <form method="POST">
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
                        $query = "SELECT * FROM users where user_type='user' ORDER BY id ASC";
                        $result = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_assoc($result)) { ?>
                              <option value="<?php echo $row['id'] ?>" <?php if (isset($_POST['user_id']) && $_POST['user_id'] == $row['id']) echo 'selected'; ?>><?php echo $row['name'] ?></option>
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

                     <button type="submit" name="filter_submit" class="btnn">Filtrele</button>
                     
                     <button type="submit" name="filter_submit2" class="btnn">Ürün Kar Filtrele</button>

                     <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btnn btn-info">Filtreyi Temizle</a>
                     
                </form>
        </div> <br><br>


             <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filter_submit']) ){ ?>
            <button class="btn btn-success" onclick="location.href='server.php'" name="excel" style="font-size: medium; width:80px; margin-top: -950px; margin-left: 1100px;">Excel Listesi Çıkar</button>
            
            <div class="table-container">   
                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                       <tr class="table-active">
                                          <th scope="col">Müşteri ID</th>
                                          <th scope="col">Müşteri Ad-Soyad</th>
                                          <th scope="col">Ürün Adı</th> 
                                          <th scope="col">Ürün Alış Fiyatı</th> 
                                          <th scope="col">Satılan Miktar</th>
                                          <th scope="col">Ürün Satış Fiyatı</th>
                                          <th scope="col">Sipariş Durumu</th>
                                          <th scope="col">Toplam Kazanç</th>
                                       </tr>
                                    </thead>
                    <tbody>
                        <?php 
                            $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
                            $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
                            $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
                            $filter_payment = isset($_POST['product_id']) ? $_POST['product_id'] : '';
                        
                            $where_clause = "WHERE 1=1";
                            if (!empty($user_id)) {
                                $where_clause .= " AND o.user_id = '$user_id'";
                            }
                            if (!empty($start_date) && !empty($end_date)) {
                                $where_clause .= " AND o.placed_on BETWEEN '$start_date' AND '$end_date'";
                            }
                            if (!empty($filter_payment)) {
                                $where_clause .= " AND o.product_id = '$filter_payment'";
                            }
                        
                            $sql = "SELECT DISTINCT
                                        o.user_id,
                                        u.name AS user_name,
                                        p.name,
                                        s.aprice,
                                        o.quantity,
                                        o.total_price,
                                        o.payment_status,
                                        s.stok AS total_quantity,
                                        o.total_price - (o.quantity * s.aprice) AS total_earnings
                                    FROM orders o
                                    LEFT JOIN users u ON u.id = o.user_id
                                    LEFT JOIN products p ON p.id = o.product_id
                                    LEFT JOIN stock s ON s.product_id = o.product_id
                                    $where_clause
                                    ORDER BY o.id DESC";
                        
                            $result = mysqli_query($conn, $sql);
                        
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td class='table-danger'>" . $row['user_id'] . "</td>";
                                    echo "<td>" . $row['user_name'] . "</td>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td> ₺" . $row['aprice'] . "</td>";
                                    echo "<td>" . $row['quantity'] . "</td>";
                                    echo "<td>₺" . $row['total_price'] . "</td>";
                                    echo "<td style='color: " . ($row['payment_status'] == 'beklemede' ? 'red' : 'green') . "'>" . $row['payment_status'] . "</td>";
                                    echo "<td class='table-success'>₺" . $row['total_earnings'] . "</td>";
                                    echo "</tr>";
                                }  }
                            }
                        ?> 
                    </tbody>
                 </table>
            </div>


   
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$where_clause = '';
$start_date = '';
$end_date = '';

if (isset($_POST['filter_submit2'])) {
  

    // $where_clause değerini $_POST['where_clause'] kullanarak güncelle
    if (isset($_POST['where_clause'])) {
        $where_clause = $_POST['where_clause'];
    }

    // $start_date ve $end_date değerlerini $_POST'tan alarak güncelle
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if (!empty($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $where_clause .= " AND s.product_id = '$product_id'";
    }

    if (!empty($start_date) && !empty($end_date)) {
        $where_clause .= " AND o.placed_on BETWEEN '$start_date' AND '$end_date'";
    }
}

$sql2 = "SELECT 
            s.product_id, 
            s.stok AS total_quantity, 
            p.name AS product_name,
            s.firststok * s.aprice AS total_purchase_price,
            SUM(o.quantity) AS total_sales_quantity,
            SUM(o.quantity) * s.sprice AS total_sprice,
            (SUM(o.quantity) * s.sprice) - (s.firststok * s.aprice) AS total_earnings,
            s.sprice, s.firststok, s.aprice,
            p.name, p.barcod
         FROM stock s
         LEFT JOIN orders o ON s.product_id = o.product_id
         LEFT JOIN products p ON s.product_id = p.id  
         WHERE 1 $where_clause
         GROUP BY s.product_id";

$result = $conn->query($sql2);
?>

<div class="box-container">
    <br>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filter_submit2'])) {
      
        ?>
        <button class="btn btn-success" onclick="location.href='server2.php'" name="excel" style="font-size: medium; width:80px; margin-top: 35px; margin-left: 800px;">Excel Listesi Çıkar</button>
        
        <table class="table table-bordered" style="margin-left: -250px; margin-top:40px; font-size: 15px;">
            <thead class="thead-dark">
                <tr class="table-active">
                    <th scope="col">Ürün ID</th>
                    <th scope="col">Ürün Adı</th>
                    <th scope="col">Alınan Miktar</th>
                    <th scope="col">Satılan Miktar</th>
                    <th scope="col">Kalan Stok Miktarı</th>
                    <th scope="col">Alış Fiyatı</th>
                    <th scope="col">Ürün Satış Fiyatı</th>
                    <th scope="col">Toplam Kazanç</th>
                </tr>
            </thead>
            
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td class="table-danger">' . $row["product_id"] . '</td>';
                    echo '<td>' . $row["product_name"] . '(' . $row["barcod"] . ')</td>';
                    echo '<td>' . $row["firststok"] . '</td>';
                    echo '<td>' . $row["total_sales_quantity"] . '</td>';
                    echo '<td>' . $row["total_quantity"] . '</td>';
                    echo '<td>' . $row["aprice"] . '₺</td>';
                    echo '<td>' . $row["sprice"] . '₺</td>';
                    echo '<td  class="table-success" style="font-size: 20px;">';
                    $total_earnings = ($row["total_earnings"] != 0) ? $row["total_earnings"] : 0;
                    if ($total_earnings < 0) {
                        echo '-' . abs($total_earnings) . '₺';
                    } else {
                        echo $total_earnings . '₺';
                    }
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
                
            </tbody>
        </table>
                 
    <?php
    }
    ?>
</div>
</div>

</section>

<script src="js/admin_script.js"></script>

</body>
</html> 