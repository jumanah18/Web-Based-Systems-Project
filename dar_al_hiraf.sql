-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: 18 مايو 2026 الساعة 01:00
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dar_al_hiraf`
--

-- --------------------------------------------------------

--
-- بنية الجدول `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `admins`
--

INSERT INTO `admins` (`admin_id`, `email`, `password`, `name`) VALUES
(1, 'admin@daralhiraf.sa', 'password123', 'Dar Al Hiraf Admin');

-- --------------------------------------------------------

--
-- بنية الجدول `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `messages`
--

INSERT INTO `messages` (`message_id`, `name`, `email`, `subject`, `message`, `sent_at`) VALUES
(1, 'jumanah', 'jumanah@gmail.com', 'question', 'i love your website', '2026-05-18 00:46:39');

-- --------------------------------------------------------

--
-- بنية الجدول `products`
--

CREATE TABLE `products` (
  `pid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `material` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `artisan` varchar(255) NOT NULL,
  `image` varchar(500) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `sessions` text DEFAULT NULL,
  `seats` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `slots_json` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `products`
--

INSERT INTO `products` (`pid`, `name`, `category`, `description`, `material`, `price`, `artisan`, `image`, `quantity`, `location`, `duration`, `sessions`, `seats`, `admin_id`, `slots_json`) VALUES
(1, 'Woven Baskets & Embroidered Textiles', 'Textiles', 'A beautiful collection of hand-woven baskets and embroidered textiles crafted in the traditional Asiri style, featuring bold geometric patterns in vivid colours. Each piece reflects the rich cultural heritage of southern Saudi Arabia.', 'Natural wool, silk thread', 320.00, 'Fatima Al-Ghamdi', 'images/p1_baskets.jpg', 10, NULL, NULL, NULL, NULL, 1, NULL),
(2, 'Brass Mortar & Pestle', 'Metalwork', 'A heavy, hand-cast brass mortar and pestle used for centuries to grind spices, coffee beans, and cardamom. Engraved with classic Najdi geometric motifs by a master metalsmith.', 'Solid hand-cast brass', 495.00, 'Hassan Al-Otaibi', 'images/p2_mortar.jpg', 0, NULL, NULL, NULL, NULL, 1, NULL),
(3, 'Palm Leaf Sun Hat', 'Textiles', 'A light, breathable sun hat woven entirely by hand from dried palm leaves. A perfect blend of traditional craft and everyday practicality, ideal for Saudi Arabia\'s sunny climate.', 'Natural palm leaf fibre', 145.00, 'Aisha Al-Mutairi', 'images/p3_hat.jpg', 20, NULL, NULL, NULL, NULL, 1, NULL),
(4, 'Traditional Saudi Khanjar', 'Metalwork', 'A traditional ceremonial khanjar dagger featuring a curved blade, an ornately decorated silver sheath, and a carved wooden grip. A striking piece of Saudi cultural heritage crafted by a master of the old guild.', 'Silver, brass, hardwood grip', 980.00, 'Ibrahim Al-Dosari', 'images/p4_khanjar.jpg', 5, NULL, NULL, NULL, NULL, 1, NULL),
(5, 'Hand-Woven Palm Basket', 'Textiles', 'A sturdy, beautifully patterned basket woven entirely by hand from palm fibre. Perfect as a storage piece or home accent, each basket carries the marks of a long tradition of Saudi women\'s weaving.', 'Natural palm fibre', 155.00, 'Aisha Al-Mutairi', 'images/p5_weaving.jpg', 20, NULL, NULL, NULL, NULL, 1, NULL),
(6, 'Traditional Silver Jewellery', 'Metalwork', 'Hand-wrought silver jewellery featuring the distinctive filigree work and amber accents of the southern mountains. Worn historically for celebrations and passed down across generations.', 'Sterling silver, natural stones', 640.00, 'Maha Al-Shehri', 'images/p6_jewelry.jpg', 6, NULL, NULL, NULL, NULL, 1, NULL),
(7, 'Woven Palm Mat', 'Textiles', 'A large, durable floor mat woven in the traditional majlis style. Ideal for seating areas and prayer, each mat is hand-crafted from sun-dried palm leaves by a skilled artisan.', 'Natural palm leaf fibre', 195.00, 'Aisha Al-Mutairi', 'images/p7_palmweave.jpg', 1, NULL, NULL, NULL, NULL, 1, NULL),
(8, 'Taif Rose Water', 'Fragrance', 'Authentic Taif rose water, distilled in small batches from the prized damask roses grown in the highlands of Taif. Used in cooking, skincare, and as a timeless fragrance with deep cultural significance.', 'Pure damask rose distillate', 220.00, 'Reem Al-Harbi', 'images/p8_roses.jpg', 18, NULL, NULL, NULL, NULL, 1, NULL),
(9, 'Sadu Woven Textile Strip', 'Textiles', 'A hand-woven Sadu textile strip in the classic red, black, and cream palette. Sadu weaving is a UNESCO-recognised Bedouin craft and every strip is created on a ground loom by a master weaver.', 'Hand-spun wool', 360.00, 'Fatima Al-Ghamdi', 'images/p9_textile.jpg', 9, NULL, NULL, NULL, NULL, 1, NULL),
(10, 'Handmade Clay Pottery Jug', 'Pottery', 'A rustic pottery jug hand-shaped on a potter\'s wheel from local red clay and finished with a hand-painted rim. Cool to the touch and perfect for traditional service of water or dates.', 'Local red clay', 280.00, 'Noura Al-Rashidi', 'images/p10_clay.jpg', 11, NULL, NULL, NULL, NULL, 1, NULL),
(11, 'Camel Leather Shoulder Bag', 'Leather', 'A supple shoulder bag hand-stitched from tanned camel leather and finished with traditional tooled patterns. Durable, aged naturally, and a modern companion to a timeless Saudi craft.', 'Tanned camel leather', 850.00, 'Ibrahim Al-Dosari', 'images/p11_leather.jpg', 4, NULL, NULL, NULL, NULL, 1, NULL),
(13, 'Traditional Palm Weaving', 'Workshop', 'Learn the traditional Khousse palm-leaf weaving technique with a master artisan from Al-Ahsa. All materials are provided. Suitable for beginners.', NULL, 280.00, 'Fatima Al-Ghamdi', 'images/p5_weaving.jpg', 4, 'Al-Ahsa Heritage Center, Al-Ahsa, Eastern Province', '3 Hours', 'May 14 2026 10:00AM-1:00PM|May 14 2026 2:00PM-5:00PM|May 21 2026 10:00AM-1:00PM', 4, 1, NULL),
(14, 'Sadu Weaving Workshop', 'Workshop', 'Master the iconic Sadu weaving — a UNESCO-recognized Bedouin craft. Learn to use a traditional ground loom and create your own Sadu-patterned piece with Fatima Al-Ghamdi.', NULL, 340.00, 'Moudi Alameel', 'images/p9_textile.jpg', 8, 'Asir Cultural Center, Abha, Asir Region', '4 Hours', 'May 21 2026 9:00AM-1:00PM|Jun 4 2026 9:00AM-1:00PM|Jun 4 2026 2:00PM-6:00PM', 8, 1, NULL),
(15, 'Brass & Copper Crafting', 'Workshop', 'Shape, engrave and polish traditional brass pieces in this immersive metalwork session with master craftsman Hassan Al-Otaibi. Take home your own handmade piece.', NULL, 420.00, 'Hassan Al-Otaibi', 'images/p2_mortar.jpg', NULL, 'Riyadh Craft District, Al-Murabba, Riyadh', '5 Hours', 'Jun 5 2026 10:00AM-3:00PM|Jun 19 2026 10:00AM-3:00PM|Jul 3 2026 10:00AM-3:00PM', 6, 1, NULL),
(16, 'Traditional Pottery Workshop', 'Workshop', 'Shape and glaze your own clay piece under the guidance of Noura Al-Rashidi from the Eastern Province. Using locally sourced red clay, you\'ll create a unique handmade piece to keep.', NULL, 260.00, 'Noura Al-Rashidi', 'images/p10_clay.jpg', 9, 'Al-Qatif Heritage Village, Al-Qatif, Eastern Province', '3 Hours', 'Jun 18 2026 10:00AM-1:00PM|Jun 18 2026 2:00PM-5:00PM|Jul 2 2026 10:00AM-1:00PM', 9, 1, NULL),
(19, 'vase', 'Pottery', 'vases', 'Khobar', 230.00, 'Jumanah Alhareth', 'images/product_1779050253_vase.webp', 10, NULL, NULL, NULL, NULL, 1, NULL),
(20, 'vase', 'Textiles', 'nn', 'Khobar', 123.00, 'Jumanah Alhareth', 'images/product_1779058205_vase.webp', 12, NULL, NULL, NULL, NULL, 1, NULL),
(21, 'making vases', 'Workshop', 'cc', NULL, 123.00, 'jumanah alhareth', 'images/workshop_1779058671_makingvases.webp', 12, 'khobar', '3 Hours', 'Jun 03 2026 6:51AM', 12, 1, '[{\"date\":\"Jun 03, 2026\",\"time\":\"6:51 AM\"}]');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
