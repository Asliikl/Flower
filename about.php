<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>hakkımızda</h3>
    <p> <a href="home.php">anasayfa</a> / hakkımızda </p>
</section>

<section class="about">

    <div class="flex">

        <div class="image">
            <img src="images/about-img-1.png" alt="">
        </div>

        <div class="content">
            <h3>Neden bizi seçmelisiniz?</h3>
            <p>Yaptığımız işe hâkimiz, titiz çalışıyor ve sektörlerdeki müşterilerimiz için kaliteli hizmetler üretiyoruz.</p>
            <a href="shop.php" class="btn">şimdi satın al</a>
        </div>

    </div>

    <div class="flex">
        <div class="content">
            <h3>Ne sağlıyoruz?</h3>
            <p>En taze ürünleri en yeni tasarımlarla sizlere sunma anlayışıyla çalışıyoruz. Seçmiş olduğunuz ürünleri özenle ve görsele uygun olarak hazırlıyoruz.
            </p>
            <a href="contact.php" class="btn">bizle iletişim kurun</a>
        </div>

        <div class="image">
            <img src="images/flower.jpeg" alt="">
        </div>
    </div>

    <div class="flex">
        <div class="image">
            <img src="images/abt-3.jpeg" alt="">
        </div>
        <div class="content">
            <h3>Biz Kimiz?</h3>
            <p>Dünyada hızla gelişen Butik Çiçekçilik sektörünün yenilikçi enerjisine bizde kendi enerjimizi kattık. Canlı-kesme, kuru ve yapay çiçeklerle yapılan tasarımlarımızı görmek için bizi takip etmeyi unutmayın..</p>
            <a href="#reviews" class="btn">müşteri yorumları</a>
        </div>
    </div>
</section>

<section class="reviews" id="reviews">

    <h1 class="title">müşteri yorumları</h1>

    <div class="box-container">

        <div class="box">
            <img src="images/pic-1.png" alt="">
            <p>Harika taze ve canlı çiçekler!!!</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Buğra</h3>
        </div>

        <div class="box">
            <img src="images/pic-2.png" alt="">
            <p>Teşekkürler, buketi çok beğendim.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Sevil</h3>
        </div>

        <div class="box">
            <img src="images/pic-3.png" alt="">
            <p>Harikaaaaaa!</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <h3>Enes</h3>
        </div>

        <div class="box">
            <img src="images/pic-4.png" alt="">
            <p>Çok memnunum, buraya bayılıyorum.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <h3>Burçin</h3>
        </div>

        <div class="box">
            <img src="images/pic-5.png" alt="">
            <p>Hizmet, kalite her şey çok iyi!</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Fırat</h3>
        </div>

        <div class="box">
            <img src="images/pic-6.png" alt="">
            <p>Güvenebilirsiniz, bu işte harikalar.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <h3>Zeynep</h3>
        </div>

    </div>

</section>











<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>