<?php 

//If there is no session, make one
if (session_id() == '') {
    session_start(); 
}

if (!empty($_GET["cat"])) {
	$category = $_GET["cat"];
	$sql = "SELECT * FROM Products WHERE product_cat like '" . $category . "'";
	if($category == "%"){
		$heading = "All products";
	}
	else{
		$heading = $category . "s";
	}
}else if (!empty($_GET["search"])){
	$key = $_GET["search"];
    $sql = "SELECT * FROM Products WHERE product_name like '%" . $key . "%'";
	$heading = "Search results for: \"$key\"";
}else {
	exit();
}

//Adding an additional ORDER BY to the query based on some new GET parameters
//These will be sent whenever the 'Sort by' <select> field is changed.
//-Ed
if (isset($_GET['sort'])) {
	$sort_type = $_GET['sort'];
	if ($sort_type === 'price_asc') {
		$sql = $sql . " ORDER BY product_price ASC";
	} else if ($sort_type === 'price_desc') {
		$sql = $sql . " ORDER BY product_price DESC";
	} else if ($sort_type === 'alpha') {
		$sql = $sql . " ORDER BY product_name ASC";
	} else {
		//Default, same as alpha but change it here if you want something else
		$sql = $sql . " ORDER BY product_name ASC";
	}
} else {
	$sql = $sql . " ORDER BY product_name ASC";
}

include('db_connect.php');
$items = mysqli_query($conn,$sql);
if (!$items) {
    printf("Error: %s\n", mysqli_error($conn));
    exit();
}

$conn->close();
?>

<!------TEMPORARY TABLE------>

<style>
table{
	border-collapse: collapse;
}
td, th {
	border: 1px solid black;
}
</style>
<div class = "product_list">

	<h1 id="product_list_header"><?php echo $heading; ?></h1>

	<div class = "sort">
		Sort by:
		<select name = "sort" onchange = "sort_items_by(this.value)">
			<option value="alpha" selected>Alphabetical</option>
			<option value="price_asc">Price: Lowest First</option>
			<option value="price_desc">Price: Highest First</option>
		</select>
		<script>
		<?php
			//This is to keep the correct <select> value even after reloading via a new GET request.
			//Probably an easier way to do this but I don't want to break your code.
			//-Ed
			if (isset($sort_type)) {
				print "set_sort_select('$sort_type')";
			}
		?>
		</script>
	</div>

	<ul class = "products">

	<?php
		while($row = mysqli_fetch_array($items)){
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
