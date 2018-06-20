/* LOGIN FUNCTIONS */
function load_login_page() {
    url = 'login.php';
    $.get(url, function (data) {
       $('#content').html(data); 
    });
}

function login() {
    url = 'login.php';
    post_data = {'action' : 'login'};
    $("#login_form input").each(function(index) {
      post_data[this.name] = this.value;
    });
    $.post(url, post_data, function( data ) {
      $('#content').html( data );
    });
}

function logout() {
    url = 'login.php';
    post_data = {'action' : 'logout'};
    $.post(url, post_data, function( data ) {
      $('#content').html( data );
    });
}

function create_user() {
    url = 'login.php';
    post_data = {'action' : 'create'};
    $("#login_form input").each(function(index) {
      post_data[this.name] = this.value;
    });
    $.post(url, post_data, function( data ) {
      $('#content').html( data );
    });
}

/* ADMIN FUNCTIONS */
function load_admin_page() {
    url = 'admin.php';
    $.get(url, function (data) {
       $('#content').html(data); 
    });
}

/* FRONT PAGE FUNCTIONS */
function load_front_page() {
    url = 'front.php';
    $.get(url, function (data) {
       $('#content').html(data); 
    });
}

//populates menu with items of the category
function load_items_by_cat(category)  { 
	url = 'product_list.php?cat=' + category;
    $.get(url, function (data) {
       $('#content').html(data); 
    });
}

//populates menu with items containing key word
function load_items_by_search(key)  { 
	url = 'product_list.php?search=' + key;
    $.get(url, function (data) {
       $('#content').html(data); 
    });
}

function load_cart(){
    url = 'cart.php';
    $.get(url, function (data) {
       $('#content').html(data); 
    });
}

//loads page for a single product
function load_product_page(product_id){
	url = 'product_page.php?product=' + product_id;
    $.get(url, function (data) {
       $('#content').html(data); 
    });
}

//shows update button when quantity is changed
function show_quantity(cart_id){
	document.getElementById('update_quantity'+cart_id).innerHTML = "Update";
}

//updates quantity
function update_quantity(cart_id){
	quantity = document.getElementById('quantity'+cart_id).value;
	post_data = {
		'new_quantity' : quantity,
		'update_id' : cart_id
	};
	url = 'cart.php';
    $.post(url, post_data, function (data) {
       $('#content').html(data); 
    });
}

//Changes the selected sort option for display purposes
function set_sort_select(sort_type) {
  select = $("select[name='sort']");
  select.val(sort_type);
}

//Changes the sorting method and reloads the page
function sort_items_by(sort_type) {
  url = 'product_list.php';
  get_data = {'sort' : sort_type};

  //Detect if we are a search or selected category
  //Persist the category if we are
  product_list_header = $('h1#product_list_header').html();
  if (product_list_header.startsWith('Search results for:')) {
    regex = new RegExp('Search results for: "(.+)"');
    if (regex.test(product_list_header)) {
      get_data['search'] = product_list_header.match(regex)[1];
    } else {
      return;
    }
  } else if (product_list_header != 'All products') {
    get_data['cat'] = product_list_header.substring(0, product_list_header.length - 1);
  } else {
    get_data['cat'] = '%';
  }

  $.get(url, get_data, function(data) {
    $('div#content').html(data);
  });
}

function add_to_cart(product_id) {
	url = 'cart.php';
	post_data = {'add_to_cart' : product_id};
	$.post(url, post_data, function (data) {
       $('#content').html(data); 
    });
}

function remove_from_cart(cart_id) {
	url = 'cart.php';
	post_data = {'remove_from_cart' : cart_id};
	$.post(url, post_data, function (data) {
       $('#content').html(data); 
    });
}

function load_checkout(){
	url = 'checkout.php';
    $.get(url, function (data) {
       $('#content').html(data); 
    });
}

function checkout(){
	if(credit_card_type() == false || credit_card_type() == 'unknown' ){
		return;
	}
    url = 'checkout_successful.php';
	post_data = {'checkout' : 'checkout'};
    $.post(url, post_data, function (data) {
		$('#content').html(data); 
    });
}

function credit_card_type(){
	document.getElementById('cardno').style.borderColor = "#ccc";
	document.getElementById('card_type').innerHTML = "";
	cardno = document.getElementById('cardno').value;
	if(cardno.length != 16){
		document.getElementById('cardno').style.borderColor = "red";
		return false;
	}
	
	var result = "unknown";

	if (/^5[1-5]/.test(cardno)){
		result = "mastercard";
		document.getElementById('card_type').innerHTML = "<img src='img/mastercard.png'>";
	}

	else if (/^4/.test(cardno)){
		result = "visa";
		document.getElementById('card_type').innerHTML = "<img src='img/visa.png'>";
	}

	else if (/^3[47]/.test(cardno)){
		result = "amex";
		document.getElementById('card_type').innerHTML = "<img src='img/amex.png'>";
	}

	console.log(result);
	return result;
	
}

/* MAIN FUNCTION BEGINS HERE*/
/* Populates the menu and front page on page load */

menu_url = 'menu.php';
front_url = 'front.php';

$.get(menu_url,function(data) {
    $('#top').html(data);
});

$.get(front_url,function(data) {
    $('#content').html(data);
});