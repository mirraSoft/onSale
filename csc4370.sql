-- MySQL dump 10.13  Distrib 5.5.50, for debian-linux-gnu (armv7l)
--
-- Host: localhost    Database: csc4370
-- ------------------------------------------------------
-- Server version	5.5.50-0+deb8u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Carts`
--

DROP TABLE IF EXISTS `Carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Carts` (
  `user_id` varchar(20) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `purchased` enum('true','false') NOT NULL DEFAULT 'false',
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`cart_id`),
  KEY `carts_users_idx` (`user_id`),
  KEY `cats_products_idx` (`product_id`),
  CONSTRAINT `carts_users` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `cats_products` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Carts`
--

LOCK TABLES `Carts` WRITE;
/*!40000 ALTER TABLE `Carts` DISABLE KEYS */;
INSERT INTO `Carts` VALUES ('user',1,4,'false',1),('user',2,2,'false',2),('user',51,2,'true',4),('user',64,1,'true',5),('user',49,1,'false',14),('user',69,1,'false',15),('user',44,1,'false',20),('user',53,2,'false',21);
/*!40000 ALTER TABLE `Carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Products`
--

DROP TABLE IF EXISTS `Products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(50) NOT NULL,
  `product_desc` varchar(500) NOT NULL DEFAULT '',
  `product_price` decimal(6,2) NOT NULL,
  `product_cat` varchar(20) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Products`
--

LOCK TABLES `Products` WRITE;
/*!40000 ALTER TABLE `Products` DISABLE KEYS */;
INSERT INTO `Products` VALUES (1,'SAMSUNG 850 EVO 2.5\" 250GB Solid State Drive','Samsung\'s 850 EVO series SSD is the industry\'s #1 best-selling* SSD and is perfect for everyday computing. Powered by Samsung\'s V-NAND technology, the 850 EVO transforms the everyday computing experience with optimized performance and endurance. Designed to fit desktop PCs, laptops, and ultrabooks, the 850 EVO comes in a wide range of capacities and form factors. ',129.99,'Hard Drive'),(2,'ASUS ROG MAXIMUS VIII HERO Gaming Motherboard','LGA1151 socket for 6th-gen Intel Core desktop processors. Dual DDR4 3733 (OC) support. 5-Way Optimization with Auto-Tuning, 2nd-generation T-Topology and OC design Reinvented SupremeFX 2015 with intuitive Sonic Studio II. Best -in-class Intel Gigabit Ethernet, LANGuard and GameFirst technology. ROG gives you more - more gaming-oriented utilities, all free!',219.99,'Motherboard'),(10,'WD Black 1TB Performance Mobile Hard Disk Drive','Next-generation desktop performance hard drive designed to intensify your PC experience\nImproved Architectural Designs: Dual Core Processor, High Resolution Controller (HRC), StableTrac Technology\nImproved Data Protection: Vibration Control Technology (VCT), Corruption Protection Technology (CPT), NoTouchâ„¢ Ramp Load Technology\nIndustry-leading 5-year manufacturer limited warranty\nPackage includes a hard drive only - no screws, cables, manuals included.',71.99,'Hard Drive'),(44,'Acer Laptop Aspire E5-774G','Intel Core i5 7200U (2.50 GHz)\r\n8 GB DDR4 Memory 256 GB SSD\r\nNVIDIA GeForce 940MX\r\n1920 x 1080\r\nWindows 10 Home 64-Bit',559.99,'Laptop'),(49,'Apple MMGF2LL/A MacBook Air','1.6 GHz dual-core Intel Core i5 (Turbo Boost up to 2.7 GHz) with 3 MB shared L3 cache\r\n8 GB of 1600 MHz LPDDR3 RAM; 128 GB PCIe-based flash storage\r\n13.3-Inch (diagonal) LED-backlit Glossy Widescreen\r\n',879.97,'Laptop'),(50,'DELL Laptop XPS 13 XPS9350-1340SLV','ntel Core i5 6200U (2.30 GHz)\r\n8 GB Memory 128 GB SSD\r\nIntel HD Graphics 520\r\n1920 x 1080\r\nWindows 10 Home 64-Bit',979.99,'Laptop'),(51,'Intel Core i7-6700 8M Skylake Quad-Core CPU','LGA 1151\r\nDDR4 & DDR3L Support\r\nDisplay Resolution up to 4096 x 2304\r\nIntel Turbo Boost Technology\r\nCompatible with Intel 100 Series Chipset Motherboards',304.99,'CPU'),(52,'DEEPCOOL TESSERACT SW Mid Tower Computer Case','2 x 120mm Blue LED fans\r\nWith side window\r\n1 x USB 3.0 + 1 x USB 2.0 Port\r\nFully black painted interior\r\nMetal mesh front panel\r\nAvailable room for long VGA card installation\r\nSupport cable management',39.99,'CPU'),(53,'AMD A10-7860K with AMD quiet cooler Quad-Core CPU','65W 4MB L2 Cache\r\nAMD Radeon R7\r\nAMD quiet cooler',109.99,'CPU'),(54,'MSI Radeon RX 470 DirectX 12 RX 470 GAMING X 8G 8G','8GB 256-Bit GDDR5\r\nBoost Clock 1254 MHz (OC Mode)\r\n1242 MHz (Gaming Mode)\r\n1206 MHz (Silent Mode)\r\n1 x DL-DVI-D 2 x HDMI 2.0b 2 x DisplayPort 1.4\r\n2048 Stream Processors\r\nPCI Express 3.0 x16',229.99,'Graphics Card'),(55,'PNY GeForce GTX 750 Ti DirectX 11.2 VCGGTX750T2XPB','2GB 128-Bit GDDR5\r\nCore Clock 1020 MHz\r\nBoost Clock 1085 MHz\r\n2 x DVI 1 x mini HDMI\r\n640 CUDA Cores\r\nPCI Express 3.0 x16',109.99,'Graphics Card'),(56,'ASUS GeForce GTX 1060 DUAL-GTX1060-O6G 6GB GPU','6GB 192-Bit GDDR5\r\nCore Clock OC Mode: 1594 MHz\r\nGaming Mode: 1569 MHz\r\nBoost Clock OC Mode: 1809 MHz\r\nGaming Mode: 1784 MHz\r\n1 x Native Dual-link DVI-D 2 x Native HDMI 2.0 2 x Native DisplayPort 1.4\r\n1280 CUDA Cores\r\nPCI Express 3.0',279.99,'Graphics Card'),(57,'Seagate Desktop HDD ST4000DM000 4TB 64MB Cache','4TB, SATA 6Gb/s, 64MB\r\nHigher performance with Seagate AcuTrac servo technology\r\nBest for Desktop or all-in-one PCs, home servers and Entry-level (DAS)',109.99,'Hard Drive'),(58,'GIGABYTE G1 Gaming GA-Z170X-Gaming 7 (rev. 1.0) ','ntel Z170\r\nCore i7 / i5 / i3 / Pentium / Celeron (LGA1151)\r\nDDR4 3866*(*O.C)/ 3733*/ 3666*/ 3600*/ 3466*/ 3400*/ 3333*/ 3300*/ 3200*/ 3000*/ 2800*/ 2666*/ 2400*/ 2133\r\n',179.99,'Motherboard'),(59,'ASUS M5A99FX PRO R2.0 AM3+ AMD 990FX + SB950 SATA ','Dual Intelligent Processors 3\r\nRemote GO!\r\nSupports (dual) NVIDIA SLI / AMD CrossFireX graphics configurations\r\nNetwork iControl\r\nUSB 3.0 Boost',129.99,'Motherboard'),(60,'Laptop Replacement New US Layout Black Keyboard','1.Backlit Function: as item title description\r\n2.Big â€œEnter â€œkey or small â€œenterâ€key: as picture shown\r\n3.With Frame or without frame: as picture shown\r\n4.Frame color: as picture shown\r\n5.Button color: as picture shown\r\n6.Icon color: as picture shown\r\n7.Waterproof function : as item title description\r\n8.Package diamensions: 407MMX158MMX38MM\r\n9.Package gross weight: 230grams',53.99,'Keyboard'),(61,'New Laptop Backlit keyboard without mouse pointer ','Backlit Function: as item title description\r\n2.Big â€œEnter â€œkey or small â€œenterâ€key: as picture shown\r\n3.With Frame or without frame: as picture shown\r\n4.Frame color: as picture shown\r\n5.Button color: as picture shown\r\n6.Icon color: as picture shown\r\n7.Waterproof function : as item title description\r\n8.Package diamensions: 407MMX158MMX38MM\r\n9.Package gross weight: 230grams',29.99,'Keyboard'),(62,'Corsair Vengeance K65 Compact Mechanical Keyboard','Vengeance K65 is the compact high-performance mechanical gaming keyboard for travelling gamers or anybody who has limited space. The full-size key spacing wonâ€™t throw you off your game, and the Cherry MX Red key switches give you smooth, linear key response for ultra fast double and triple taps without the audible click.\r\n\r\nThereâ€™s 100% anti-ghosting with full key rollover on USB for accurate gameplay, no matter how fast you are. And, the travel-friendly detachable braided cloth USB cable ha',59.99,'Keyboard'),(64,'RAZER DeathAdder Chroma USB Gaming Mouse','Perfectly designed to fit snugly under your palm, the ergonomic shape of the Razer DeathAdder Chroma gives gamers the most comfortable gaming experience ever, especially during the most testing of battles. Together with its rubber side grips, the Razer DeathAdder Chroma keeps you in control for extended gaming sessions.',54.99,'Mouse'),(65,'Victake Precision 8000 DPI Gaming Mouse - 6 Speed ','Victake Precision 8000 DPI Gaming Mouse - 6 Speed DPI Adjustment, 13 Light Modes, 8 Buttons, Optical Engine Power for Pro Gamer & Office - Black\r\nThis high precision gaming mouse is built for game lovers with comfortable fit and accurate control to help you win in the game.\r\nSet your mouse with 8000 DPI to gain the advantages over your opponents and command the speed and sensitivity accurately. You also can adjust the DPI with one button and flashing light indicators. 1000 DPI red light flash 3 ',16.39,'Mouse'),(66,'Logitech G900 Chaos Spectrum Wireless Mousei','In high-pressure moments, latency can be the difference between winning it all or losing. With millions on the line, top eSports professionals can depend on G900 Chaos Spectrum. Featuring a 1-millisecond report rate and our highly optimized 2.4GHz wireless connection, Chaos Spectrum delivers incredible responsiveness for competition-level twitch targeting.Pixel-precise performance\r\nChaos Spectrum comes equipped with the PMW3366 optical sensor â€” widely regarded as the best gaming mouse sensor o',129.99,'Mouse'),(67,'BenQ BL3200PT Black 32\" 4ms (GTG) HD Monitor','Accurate color gamut that brings your imagination to life, a large 32\" screen that extends your design space, high resolution to view the smallest details and ergonomic features that provide excellent viewing comfort... The result - a monitor that meets all of your graphic designing needs.Features such as 2560x1440 WQHD resolution and 32\" of monitor real estate decreases scrolling to zoom in and out of views, boosting design productivity.',399.99,'Monitor'),(68,'Acer G6 Series G236HLBbd Black','Function meets style when it comes to Acer G series display.\r\n\r\nThis Acer G series display delivers the beauty you appreciate and the performance you need, at a great price point you want. The sleek bezel with vibrant visuals delivers comfortable viewing for your work and entertainment. Cinematic widescreen and superior contrast present brilliant graphics and sharp text whether you are gaming, watching movies and surfing the web. What\'s more, this display features power-saving technologies to co',99.99,'Monitor'),(69,'Philips SHP9500S Over-Ear Headphone','The high-precision 50mm neodymium drivers deliver the full spectrum of sound, letting you immerse yourself in every single note and nuance â€“ anytime, anywhere. Better yet, the drivers are acoustically angled to perfectly align with your ear for precise and accurate sound. Audio signals are directly channeled into the ears, creating a dynamic and authentic listening experience.',57.99,'Headphone'),(70,'Monster MH ELMT ON RGLD BT WW Wireless Headphones','Monster Bluetooth for exceptional sound quality and freedom from cables.\r\nMemory Foam Cushions for comfort during extended wear.\r\nPowerful pure Monster sound.\r\nShowroom beautiful finish.\r\nHard case included.',249.95,'Headphone');
/*!40000 ALTER TABLE `Products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `user_id` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `is_admin` enum('true','false') NOT NULL DEFAULT 'false',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES ('admin','123foobar!','true'),('another_user','foobar','false'),('bar','foo','false'),('ebull1','ebull1','true'),('ebull_user','foobar','false'),('foo','bar','false'),('user','123foobar!','false');
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-12-12  8:22:18
