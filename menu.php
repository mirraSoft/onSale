<?php
//If there is no session, make one
if (session_id() == '') {
    session_start();
}

include('db_connect.php');

$getCategories = 'SELECT distinct product_cat FROM Products ORDER BY product_cat';

if (!mysqli_query($conn, $getCategories)){
	echo("Could not retrieve categories: " . mysqli_error($conn));
}

if(isset($_SESSION['user_id'])){
	$user_id = $_SESSION['user_id'];
	$getItemsInCart = "SELECT COALESCE(SUM(quantity),0) AS sum FROM Carts WHERE user_id like '$user_id' AND purchased = 'false'";
	if (!mysqli_query($conn, $getItemsInCart)){
		echo("Could not retrieve number of items in Cart: " . mysqli_error($conn));
	}
	$quantityInCart = mysqli_query($conn, $getItemsInCart)->fetch_object()->sum;
}

$categories = mysqli_query($conn, $getCategories);

$conn->close();
?>
<div class="menu_bar">
    <ul class="menu">
	<?php
		//admin
		if (isset($_SESSION['user_id']) and isset($_SESSION['is_admin']) and $_SESSION['is_admin'] === True) {
            echo '<li class="accounts" onclick="load_cart()">Shopping Cart ('.$quantityInCart.' items)</li>';
			echo '<li class="accounts" onclick="load_admin_page()">Administration</li>';
			echo '<li class="accounts" onclick="load_login_page()">Welcome, '.$user_id.'!</li>';
		} else if (isset($_SESSION['user_id'])){
		//user
			echo '<li class="accounts" onclick="load_cart()">Shopping Cart ('.$quantityInCart.' items)</li>';
			echo '<li class="accounts" onclick="load_admin_page()">Manage Your Account</li>';
			echo '<li class="accounts" onclick="load_login_page()">Welcome, '.$user_id.'!</li>';
        } else {
		//guest
			echo '<li class="accounts" onclick="load_login_page()">Log In or Register</li>';
		}
	?>
    </ul>
</div>

<div class="title_bar">
    <!--<img src='img/smart.png'>-->
	<h1>#SWAG ELECTRONICS</h1>
    <h3>Best On Line Prices</h3>
</div>

<div class="menu_bar">
    <ul class="menu">
        <li class="menu" onclick="load_front_page()">Home</li>
        <li class="menu dropdown">Categories
			<ul class="dropdown-content">
			<li onclick='load_items_by_cat("%")'>All</li>
				<?php

				while($row = $categories->fetch_assoc()){
					$cat = $row['product_cat'];
					echo "<li onclick='load_items_by_cat(\"" . $cat . "\")'>" . $cat . "</li>";
				}

				?>
            </ul>
		</li>
        <li class="menu search"><input class = "search" onkeydown = "if (event.keyCode == 13)
			load_items_by_search(this.value)" type="text" name="search" placeholder="Search...">
		</li>
	</ul>
</div>
