<?php
if (session_id() == '') {
	session_start();
}

include('db_connect.php');

$deals_sql = "SELECT * FROM Products ORDER BY product_price ASC LIMIT 4";
$deals = mysqli_query($conn,$deals_sql);
if (!$deals) {
    printf("Error: %s\n", mysqli_error($conn));
    exit();
}

?>

<div class = "front">

	<h1>onSale Deals of the Week!</h1>

	<ul class = "products">

	<?php
		while($row = mysqli_fetch_array($deals)){
			$product_id = $row['product_id'];
			$product_name = $row['product_name'];
			$product_price = $row['product_price'];
			echo "<li>
				<img src = \"img/$product_id.jpg\" onclick = 'load_product_page($product_id)'>
				<p onclick = 'load_product_page($product_id)'>$product_name</p>
				<h3>$$product_price</h3>
				</li>";
		}
	?>

	</ul>

</div>
