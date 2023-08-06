-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 06 Ağu 2023, 22:38:33
-- Sunucu sürümü: 10.4.25-MariaDB
-- PHP Sürümü: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `flower`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `cart_name` varchar(100) NOT NULL,
  `cart_no` int(14) NOT NULL,
  `cvc` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `cart_name`, `cart_no`, `cvc`, `date`) VALUES
(230, 14, 'user A', 2147483647, 101, '2025-02-05');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `message`
--

CREATE TABLE `message` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `message`
--

INSERT INTO `message` (`id`, `user_id`, `message`) VALUES
(22, 14, 'kolaylıkla alışveriş yapabilirsiniz'),
(23, 21, 'çiçekleriniz çok güzeldi');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `product_id` int(100) NOT NULL,
  `method` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT 'kapıda ödeme',
  `quantity` int(100) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` decimal(20,0) NOT NULL,
  `placed_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payment_status` varchar(20) NOT NULL DEFAULT 'beklemede',
  `address` varchar(100) NOT NULL,
  `currentadres` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `method`, `quantity`, `total_products`, `total_price`, `placed_on`, `payment_status`, `address`, `currentadres`) VALUES
(159, 24, 58, 'kapıda ödeme', 2, 'Gül (2) ', '450', '2023-05-16 13:37:17', 'tamamlanmış', '', 'troya caddesi, çiçek sokak ,Çanakkale,17'),
(161, 25, 58, 'kapıda ödeme', 13, 'Gül (13) ', '2925', '2023-05-16 13:39:06', 'tamamlanmış', 'pendik, antika sokak no:10', ''),
(162, 14, 55, 'credit_card', 1, 'mavi sümbül (1) ', '170', '2023-05-17 09:06:31', 'tamamlanmış', '', 'Flat 3, 23a, Vernon Street, London, Greater London'),
(163, 14, 61, 'kapıda ödeme', 1, 'Pembe Kraliçe Gül (1) ', '200', '2023-05-17 09:06:25', 'tamamlanmış', 'beykoz, çiçek sokak no:10', ''),
(164, 18, 59, 'kapıda ödeme', 1, 'orkide (1) ', '210', '2023-05-17 09:10:02', 'tamamlanmış', '', '41,İstasyon, Gebze,Kocaeli');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `details` varchar(300) NOT NULL,
  `image` varchar(100) NOT NULL,
  `barcod` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `products`
--

INSERT INTO `products` (`id`, `name`, `details`, `image`, `barcod`) VALUES
(55, 'Mavi Sümbül', 'Mavi sümbül çiçek buketi', 'sumbul.jpg', 'MVSMBL1'),
(56, 'Mimoza', 'Mor,sarı mimoza buketi', 'mimoza.jpg', 'MZO12'),
(57, 'Sarı Lale', '12 li vazoda sarı lale', 'sarilale.jpg', 'LALE12'),
(58, 'Gül', '100 adet kırmızı gül', 'kirmizigul.jpg', 'GUL12'),
(59, 'Orkide', 'Vazoda orkide', 'orkide.jpg', 'orkıda12'),
(60, 'Şakayık', 'Şakayık ', 'sakayik.jpg', 'sakayik6'),
(61, 'Pembe Kraliçe Gül', 'Pembe Kraliçe Gül', 'pink queen rose.jpg', 'Kraliçe1'),
(62, 'Kırmızı lale', 'Kırmızı Lale ', 'red tulipa.jpg', 'kirmizilale1'),
(64, 'Beyaz Çiçek Buketi', 'Beyaz Buket', 'buket2.jpg', 'Buket01');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `id` int(11) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `shopping_cart`
--

INSERT INTO `shopping_cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES
(105, 14, 56, 'Mimoza', 185, 2, 'mimoza.jpg'),
(106, 14, 59, 'Orkide', 210, 1, 'orkide.jpg');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `stock`
--

CREATE TABLE `stock` (
  `id` int(100) NOT NULL,
  `tedarik_id` int(100) NOT NULL,
  `product_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `aprice` decimal(50,0) NOT NULL,
  `sprice` decimal(50,0) NOT NULL,
  `stok` int(50) NOT NULL,
  `firststok` int(100) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `stock`
--

INSERT INTO `stock` (`id`, `tedarik_id`, `product_id`, `name`, `aprice`, `sprice`, `stok`, `firststok`, `date_added`) VALUES
(95, 8, 61, '', '120', '200', 19, 20, '2023-05-11'),
(96, 8, 59, '', '130', '210', 19, 20, '2023-05-09'),
(97, 7, 58, '', '130', '225', 0, 15, '2023-05-09'),
(98, 10, 56, '', '110', '185', 20, 20, '2023-05-08'),
(99, 10, 55, '', '100', '170', 9, 10, '2023-05-17'),
(100, 8, 57, '', '100', '170', 20, 20, '2023-05-16'),
(101, 7, 60, '', '120', '190', 15, 15, '2023-05-17');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tedarik`
--

CREATE TABLE `tedarik` (
  `id` int(100) NOT NULL,
  `tedarik_name` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `adres` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `tedarik`
--

INSERT INTO `tedarik` (`id`, `tedarik_name`, `phone`, `mail`, `adres`) VALUES
(7, 'Manolya Çiçekcilik', '(553) 904 - 4558', 'manolya@gmail.com', 'istanbul-Pendik'),
(8, 'Saksıda Çiçek', '(553) 870-2030', 'ciceksaksi@gmail.com', 'istanbul-kartal'),
(10, 'Bahar Çiçek', '(530) 900- 0000', 'bahar@gmail.com', 'İstanbul,Yunus');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `adress` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user',
  `number` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `adress`, `user_type`, `number`) VALUES
(10, 'admin A', 'admin@gmail.com', '698d51a19d8a121ce581499d7b701668', '', 'admin', '324234'),
(14, 'user A', 'user@gmail.com', '698d51a19d8a121ce581499d7b701668', 'Flat 3, 23a, Vernon Street, London, Greater London', 'user', '33243'),
(18, 'Aslıhan İkiel', 'asliikiel@gmail.com', '202cb962ac59075b964b07152d234b70', '41,İstasyon, Gebze,Kocaeli', 'user', '234234'),
(21, 'Ali Ertug', 'ali@gmail.com', '698d51a19d8a121ce581499d7b701668', '8, Vernon Street, London, Greater London', 'user', '55467000'),
(24, 'Ayşe Korkmaz', 'ayse@gmail.com', '698d51a19d8a121ce581499d7b701668', 'troya caddesi, çiçek sokak ,Çanakkale,17', 'user', '123-456-78-7'),
(25, 'Gökhan Bişar', 'gokhan@gmail.com', '698d51a19d8a121ce581499d7b701668', 'ataköy,istanbul,34', 'user', '123-456-78-8'),
(27, 'Kamil Akgün', 'kamilakgun@gmail.com', '698d51a19d8a121ce581499d7b701668', 'Troya caddesi, Çanakkale,17', 'user', '111-222-33-4');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`user_id`);

--
-- Tablo için indeksler `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Tablo için indeksler `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pid` (`pid`);

--
-- Tablo için indeksler `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tedarik_id` (`tedarik_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Tablo için indeksler `tedarik`
--
ALTER TABLE `tedarik`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=233;

--
-- Tablo için AUTO_INCREMENT değeri `message`
--
ALTER TABLE `message`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Tablo için AUTO_INCREMENT değeri `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- Tablo için AUTO_INCREMENT değeri `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- Tablo için AUTO_INCREMENT değeri `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- Tablo için AUTO_INCREMENT değeri `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- Tablo için AUTO_INCREMENT değeri `tedarik`
--
ALTER TABLE `tedarik`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Tablo için AUTO_INCREMENT değeri `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD CONSTRAINT `shopping_cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tedarik_id` FOREIGN KEY (`tedarik_id`) REFERENCES `tedarik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
