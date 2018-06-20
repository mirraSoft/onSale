<?php
//If there is no session, make one
if (session_id() == '') {
    session_start();
}

if(isset($_SESSION['user_id'])){
	$user_id = $_SESSION['user_id'];
}

?>

<div class="checkout">

	<h1>onSale Checkout</h1>

	<h3><u>Card Info</u></h3>
	<label for="cardno">Card Number:</label><br>
	<input type="number" id="cardno" onblur="credit_card_type()"><span id="card_type"></span><br>
	<label for="exp_date">Expiration Date:</label><br>
	<input type="number" id="exp_date"><br>
	<label for="security">Security Code:</label><br>
	<input type="number" id="security" maxlength="4"><br>

	<label for="fname">First Name:</label><br>
	<input type="text" id="fname"><br>
	<label for="lname">Last Name:</label><br>
	<input type="text" id="lname"><br><br>

	<h3><u>Billing Info</u></h3>
	<label for="address">Billing Address:</label><br>
	<input type="text" id="address"><br>
	<label for="city">City:</label><br>
	<input type="text" id="city"><br>
	<label for="state">State</label><br>
	<input type="text" id="state"><br>
	<label for="zip">Zip:</label><br>
	<input type="text" id="zip"><br>
	<label for="phone">Phone Number:</label><br>
	<input type="text" id="phone"><br><br>

	<button onclick="checkout()">Confirm and Pay</button>

</div>
