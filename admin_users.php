<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_users.php');
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

<section class="users">
    
   <h1 class="title">kullanıcı hesapları</h1>
      <div class="box-container">
          <?php 
            $where_clause = '';
            if(isset($_POST['filter_submit'])){
               $filter_user = $_POST['filter_user'];
               if(!empty($filter_user)){
                  $where_clause = "WHERE users.user_type = '$filter_user'";
               }
            }
         ?>
         <form action="" method="post">
            <label for="filter_user" style="font-size: 18px; font-family:Arial, Helvetica, sans-serif">Kullanıcılar:</label>
            <select id="filter_user" name="filter_user" class="box1">
               <option value="">Tümü</option>
               <option value="admin" <?php if(isset($_POST['filter_user']) && $_POST['filter_user'] === 'admin') echo 'selected'; ?>>Admin</option>
               <option value="user" <?php if(isset($_POST['filter_user']) && $_POST['filter_user'] === 'user') echo 'selected'; ?>>Kullanıcı</option>
            </select>
            
            &nbsp; <button type="submit" name="filter_submit" class="btnn">Göster</button>
            <br><br>
         </form>
      </div>

      <div class="box-container">
               <?php
                  $select_users = mysqli_query($conn, "SELECT * FROM `users` $where_clause") or die('query failed');
                  if(mysqli_num_rows($select_users) > 0){
                     while($fetch_users = mysqli_fetch_assoc($select_users)){
               ?>
         <div class="box">
               <p>user id : <span><?php echo $fetch_users['id']; ?></span></p>
               <p>username : <span><?php echo $fetch_users['name']; ?></span></p>
               <p>email : <span><?php echo $fetch_users['email']; ?></span></p>
               <p>user type : <span style="color:<?php if($fetch_users['user_type'] == 'admin'){ echo 'var(--green)'; }; ?>"><?php echo $fetch_users['user_type']; ?></span></p>
               <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>" onclick="return confirm('kullanıcı silinsin mi?');" class="delete-btn">sil</a>
         </div>
            <?php
               }
            }
            ?>
      </div>
</section>
<script src="js/admin_script.js"></script>

</body>
</html>