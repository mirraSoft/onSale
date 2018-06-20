<?php
include('db_connect.php');

$sql = 'create table Users (
    user_id varchar(20) primary key,
    password varchar(20) not null,
    is_admin enum("true","false") not null default "false"
    )';
	
$productsTable = 'CREATE TABLE Products (
  `product_id` INT NOT NULL AUTO_INCREMENT,
  `product_name` VARCHAR(50) NOT NULL,
  `product_desc` VARCHAR(500),
  `product_price` DECIMAL(6,2) NOT NULL,
  `product_cat` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`product_id`))';
  
$cartsTable = 'CREATE TABLE `csc4370`.`Carts` (
  `user_id` VARCHAR(20) NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 0,
  INDEX `carts_users_idx` (`user_id` ASC),
  INDEX `cats_products_idx` (`product_id` ASC),
  PRIMARY KEY (`user_id`, `product_id`),
  CONSTRAINT `carts_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `csc4370`.`Users` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `cats_products`
    FOREIGN KEY (`product_id`)
    REFERENCES `csc4370`.`Products` (`product_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)';

$insertProducts1 = "INSERT INTO `Products` (`product_name`,`product_desc`,`product_price`,`product_cat`) VALUES ('SAMSUNG 850 EVO 2.5\" 500GB Solid State Drive','Samsung\'s 850 EVO series SSD is the industry\'s #1 best-selling* SSD and is perfect for everyday computing. Powered by Samsung\'s V-NAND technology, the 850 EVO transforms the everyday computing experience with optimized performance and endurance. Designed to fit desktop PCs, laptops, and ultrabooks, the 850 EVO comes in a wide range of capacities and form factors. ',129.99,'Hard Drive')";
$insertProducts2 = "INSERT INTO `Products` (`product_name`,`product_desc`,`product_price`,`product_cat`) VALUES ('ASUS ROG MAXIMUS VIII HERO Gaming Motherboard','LGA1151 socket for 6th-gen Intel Core desktop processors. Dual DDR4 3733 (OC) support. 5-Way Optimization with Auto-Tuning, 2nd-generation T-Topology and OC design Reinvented SupremeFX 2015 with intuitive Sonic Studio II. Best -in-class Intel Gigabit Ethernet, LANGuard and GameFirst technology. ROG gives you more - more gaming-oriented utilities, all free!',219.99,'Motherboard')";
$insertProducts3 = "INSERT INTO `Products` (`product_name`,`product_desc`,`product_price`,`product_cat`) VALUES ('blah','blah',234.00,'Hard Drive')";
  
if ($conn->query($sql) === True) {
    echo 'Create Users success';
} else {
    echo 'Create Users failed: ' . $conn->error;
}
echo '<br>';

if ($conn->query($productsTable) === True || $conn->query($insertProducts1) === True && $conn->query($insertProducts2) === True && $conn->query($insertProducts3) === True ) {
    echo 'Create Products success';
} else {
    echo 'Create Products failed: ' . $conn->error;
}

echo '<br>';

if ($conn->query($cartsTable) === True) {
    echo 'Create Carts success';
} else {
    echo 'Create Carts failed: ' . $conn->error;
}
echo '<br>';

$conn->close();
?>