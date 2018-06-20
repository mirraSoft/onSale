<?php
//If there is no session, make one
if (session_id() == '') {
    session_start(); 
}

if(isset($_SESSION['user_id'])){
	$user_id = $_SESSION['user_id'];
}

include('db_connect.php');

if (!empty($_GET["product"])) {
	$product_id = $_GET["product"];
	$product_sql = "SELECT * FROM Products WHERE product_id = $product_id";
	
	$product = mysqli_query($conn,$product_sql);
	if (!$product) {
		printf("Error: %s\n", mysqli_error($conn));
		exit();
	}
}
$conn->close();

$row = mysqli_fetch_array($product);

$product_name = $row['product_name'];
$product_desc = $row['product_desc'];
$product_price = $row['product_price'];

?>

<div class = 'product_page'>

	
	<div class = 'product_page_img'>
		<img src = "img/<?php echo $product_id; ?>.jpg">
	</div>
	
	<h2><?php echo $product_name; ?></h2><hr>
	
	<p class = 'desc'><?php echo $product_desc ?></p>
	
	<p class = 'price'>Price:<span style="color:red;font-size:20px;"> <?php echo "$$product_price"; ?></span></p>
	
	<button type='button' onclick='add_to_cart(<?php echo $product_id; ?>)'>Add to Cart</button>

</div>




