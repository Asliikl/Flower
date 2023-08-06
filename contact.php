<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['send'])){

    $msg = mysqli_real_escape_string($conn, $_POST['message']);

    $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE message = '$msg'") or die('query failed');

    if(mysqli_num_rows($select_message) > 0){
        $message[] = 'mesaj zaten gönderildi!';
    }else{
        mysqli_query($conn, "INSERT INTO `message`(user_id,message) VALUES('$user_id','$msg')") or die('query failed');
        $message[] = 'mesaj başarıyla gönderildi!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>bizimle iletişime geçin</h3>
    <p> <a href="home.php">anasayfa</a> / iletişim </p>
</section>

<section class="contact">
    <form action="" method="POST">
        <h3>Bize mesaj bırakın!</h3>
        <textarea name="message" class="box" placeholder="mesaj yazın" required cols="30" rows="10"></textarea>
        <input type="submit" value="mesaj gönder" name="send" class="btn">
    </form>
</section>
<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>