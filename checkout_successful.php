<?php
//If there is no session, make one
if (session_id() == '') {
    session_start(); 
}

if(isset($_SESSION['user_id'])){
	$user_id = $_SESSION['user_id'];
}
	
include('db_connect.php');
	
if (!empty($_POST['checkout'])) {
	$checkout_sql = "UPDATE Carts SET purchased='true' WHERE user_id='$user_id'";

	$checkout = mysqli_query($conn,$checkout_sql);
	if (!$checkout) {
		printf("Error: %s\n", mysqli_error($conn));
		exit();
	}
	print "<h2>Order placed!</h2>\n";
	print "<script>
				setTimeout(function() {
					window.location.href = \"index.php\";
				},3000);
			</script>\n";
	return;
	
}
$conn->close();
?>