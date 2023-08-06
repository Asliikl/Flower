<?php

@include 'config.php';

if(isset($_POST['submit'])){

   $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $name = mysqli_real_escape_string($conn, $filter_name);
   
   $filter_adress= filter_var($_POST['adress'], FILTER_SANITIZE_STRING);
   $adress = mysqli_real_escape_string($conn, $filter_adress);

   $filter_number= filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $number = mysqli_real_escape_string($conn, $filter_number);

   $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $email = mysqli_real_escape_string($conn, $filter_email);

   $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
   $pass = mysqli_real_escape_string($conn, md5($filter_pass));

   $filter_cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING);
   $cpass = mysqli_real_escape_string($conn, md5($filter_cpass));

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

   // En son sorgu ile gelen kayıt sayısının öğren->mysqli_num_rows
   if(mysqli_num_rows($select_users) > 0){
      $message[] = 'Kullanıcı zaten var!';
   }else{
      if($pass != $cpass){
         $message[] = 'Şifrenin eşleşmedi!';
      }else{
         mysqli_query($conn, "INSERT INTO `users`(name, email, number,password,adress) VALUES('$name', '$email','$number', '$pass','$adress')") or die('query failed');
         $message[] = 'Başarıyla kayıt olundu!';
         header('location:login.php');
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>kayıt ol</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         // öğenin üst öğesi döndürür->parentElement kaldır
      </div>
      ';
   }
}
?>
   
<section class="form-container"> 
   <!-- üye olma formu -->
   <form action="" method="post">
      <h3>şimdi üye ol</h3>
      <input type="text" name="name" class="box" placeholder="Kullanıcı adınızı giriniz" required>
      <input type="email" name="email" class="box" placeholder="E-postanızı giriniz" required>
      <input type="tel" name="number" class="box"   placeholder="Telefon no giriniz" required>
      <input type="text" name="adress" class="box" placeholder="Adresinizi giriniz" required>
      <input type="password" name="pass" class="box" placeholder="Şifrenizi giriniz" required>
      <input type="password" name="cpass" class="box" placeholder="parolanızı doğrulayın" required>
      <input type="submit" class="btn" name="submit" value="şimdi üye ol">
      <p>Zaten hesabınız var mı? <a href="login.php"> şimdi giriş yap</a></p>
   </form>

</section>

</body>
</html>