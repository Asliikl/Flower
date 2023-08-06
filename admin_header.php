<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
   
<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <div class="flex">

      <a href="admin_page.php" class="logo">Admin<span>Panel</span></a>
      <nav class="navbar">
         <a href="admin_page.php">Anasayfa</a>
         <a href="admin_tedarik.php">Tedarikçi</a>
         <a href="admin_products.php">Ürünler</a>
         <a href="admin_stok.php">Stok</a>
         <a href="admin_orders.php">Siparişler</a>
         <a href="admin_users.php">Kullanıcılar</a>
         <a href="admin_contacts.php">Mesajlar</a>
        <a href="srapor.php">Rapor</a>         
      </nav>

      

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>
 
      <div class="account-box">
         <p>kullanıcı adı : <span><?php echo $_SESSION['admin_name']; ?></span></p>
         <p>email : <span><?php echo $_SESSION['admin_email']; ?></span></p>
         <a href="logout.php" class="delete-btn">logout</a>
         <div>new <a href="login.php">gir</a> | <a href="register.php">kayıt ol</a> </div>
      </div>

   </div>

</header>