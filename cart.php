<?php

//If there is no session, make one
if (session_id() == '') {
    session_start();
}

if(isset($_SESSION['user_id'])){
	$user_id = $_SESSION['user_id'];
} else {
	print '<h3>You must log in to add products to your cart.';
	exit();
}

include('db_connect.php');

if (!empty($_POST["new_quantity"]) && !empty($_POST["update_id"])) {
	$new_quantity = $_POST["new_quantity"];
	$update_id = $_POST["update_id"];
	$changeQuantity_sql = "UPDATE Carts SET quantity=$new_quantity WHERE cart_id=$update_id";

	$changeQuantity = mysqli_query($conn,$changeQuantity_sql);
	if (!$changeQuantity) {
		printf("Error: %s\n", mysqli_error($conn));
		exit();
	}
}

if (!empty($_POST['add_to_cart'])){
	$add_product_id = $_POST['add_to_cart'];
	$add_to_cart_sql = "INSERT INTO Carts (user_id, product_id, quantity, purchased) VALUES ('$user_id', $add_product_id, 1, 'false')";

	$add_to_cart = mysqli_query($conn,$add_to_cart_sql);
	if (!$add_to_cart) {
		printf("Error: %s\n", mysqli_error($conn));
		exit();
	}
}

if (!empty($_POST['remove_from_cart'])){
	$remove_cart_id = $_POST['remove_from_cart'];
	$remove_from_cart_sql = "DELETE FROM Carts WHERE cart_id = $remove_cart_id";

	$remove_from_cart = mysqli_query($conn,$remove_from_cart_sql);
	if (!$remove_cart_id) {
		printf("Error: %s\n", mysqli_error($conn));
		exit();
	}
}

$itemsInCart_sql = "
SELECT Carts.product_id, product_name, product_price*quantity AS price, quantity, cart_id
FROM Carts
JOIN Products
	ON Carts.product_id = Products.product_id
WHERE user_id LIKE '$user_id'
	AND purchased = 'false'";

$cartTotal_sql = "
SELECT SUM(quantity) AS total_quantity,
	SUM(product_price * quantity) AS total_price
FROM Carts
JOIN Products
	ON Carts.product_id = Products.product_id
WHERE user_id LIKE '$user_id'
	AND purchased = 'false'";

$itemsInCart = mysqli_query($conn,$itemsInCart_sql);
if (!$itemsInCart) {
    printf("Error: %s\n", mysqli_error($conn));
    exit();
}

$cartTotal = mysqli_query($conn,$cartTotal_sql);
if (!$cartTotal) {
    printf("Error: %s\n", mysqli_error($conn));
    exit();
}

$conn->close();

?>

<div class = "cart_outer">

	<h1>Your onSales Shopping Cart <?php if (mysqli_num_rows($itemsInCart)==0) {echo "is empty.";} ?></h1>
	<div class = "cart">

		<ul class = "cart_list">
		<?php

			while($row = mysqli_fetch_array($itemsInCart)){
				$product_id = $row['product_id'];
				$name = $row['product_name'];
				$price = $row['price'];
				$quantity = $row['quantity'];
				$cart_id = $row['cart_id'];
				echo "<li>
					<div class = 'product_img' onclick = 'load_product_page($product_id)'><img src = \"img/$product_id.jpg\"></div>
					<h4 onclick = 'load_product_page($product_id)'>$name</h4>
					<p>Quantity: <input type='number' value='$quantity' onchange='show_quantity($cart_id)' id='quantity$cart_id' min='1'>
					<span id = 'update_quantity$cart_id' onclick='update_quantity($cart_id)'></span></p>
					<span class = 'remove' onclick='remove_from_cart($cart_id)'>Remove</span>
					<p class = 'price'>$$price</p>
					</li>";
			}

		?>
		</ul>


		<div class = "cart_total">
		<h2>Cart Summary</h2><hr>
			<?php

			if (mysqli_num_rows($itemsInCart)!=0) {

				while($row = mysqli_fetch_array($cartTotal)){
					$total_quantity = $row['total_quantity'];
					$total_price = $row['total_price'];
					echo "
						<p>$total_quantity Item(s)</p>
						<p>Total: $$total_price</p>";
				}
				echo '<button type="button" onclick="load_checkout()">Proceed to checkout</button>';
			}

		?>
		</div>
	</div>
</div>
