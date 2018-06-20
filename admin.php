<?php
//If there is no session, make one
if (session_id() == '') {
    session_start(); 
}
//If there is a user, check if the user is an admin
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $admin = False;
    if (isset($_SESSION['is_admin']) and $_SESSION['is_admin'] === True) {
        $admin = True;
    }
}
//If there is no authenticated user, print warning and exit before displaying anything sensitive
else {
    print '<div class="center">
        <p>You must log in to manage an account.</p>
        <p><a href="#" onclick="load_login_page()">Log in</a></p>
    </div>';
    exit();
}
?>

<script type="text/javascript" src="admin.js"></script>
<div class="admin_bar">
    <ul>
        <li onclick="display('profile')">Manage Your Account</li>
        <?php
            if ($admin === True) {
                print "\n<li onclick=\"display('all_profiles')\">Manage Other Accounts</li>\n";
                print "<li onclick=\"display('manage_products')\">Manage Products</li>\n";
            }
        ?>
        <li onclick="display('order_history')">View Order History</li>
    </ul>
</div>

<div id="admin_content" class="admin_content">
    <h3>Welcome, <?php print "$user_id"; ?></h3>
    <hr />
    <!-- All divs of class admin_opt are hidden until chosen as a display option -->

    <div id="profile" class="admin_opt">        
        <h3 id="change_pwd_header">Change your password</h3>
        <div class="center">
            <form id="change_pwd">
                <p>Old password:<input type="password" id="old_pwd" value="old_pwd" /></p>
                <p>New password:<input type="password" id="new_pwd1" value="new_pwd1" class="new_pwd" /></p>
                <p>Re-enter new password:<input type="password" id="new_pwd2" value="new_pwd2" class="new_pwd" /></p>
                <input type="button" value="Submit" onclick="change_password()" />
            </form>
        </div>
    </div>

    <div id="all_profiles" class="admin_opt">
        <h3 id="all_profiles_header">All Users</h3>

        <div class="admin_content">
            <table id="all_profiles_table">
                <thead>
                    <td>Username</td>
                    <td>Permissions</td>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="new_user_id" /></td>
                        <td><select name="new_user_priv"><option value='user'>User</option><option value='admin'>Admin</option></select></td>
                        <td>New Password: <input type="password" name="new_pwd1" />Confirm: <input type="password" name="new_pwd2" /></td>
                        <td><input type="button" value="Create new user" onclick="admin_create_user()"/></td>
                    </tr>
                    <?php
                        include('db_connect.php');
                        $user_id_safe = $conn->real_escape_string($user_id);
                        $sql = "SELECT user_id, is_admin FROM Users WHERE user_id != '$user_id_safe' ORDER BY user_id ASC";
                        $result = $conn->query($sql);
                        if ($result) {
                            for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                                $sql_row = $result->fetch_assoc();
                                $current_user = $sql_row['user_id'];
                                $priv = 'User';
                                if ($sql_row['is_admin'] === 'true') {
                                    $priv = 'Admin';
                                }
                                print "\n<tr id=\"$current_user\">\n";
                                print "<td>$current_user</td>";
                                print "<td>$priv</td>";
                                print "<td><form id=\"new_pwd_$current_user\">New Password: <input type=\"password\" name=\"new_pwd1\" />Confirm: <input type=\"password\" name=\"new_pwd2\" /></form></td>";
                                print "<td><input type=\"button\" value=\"Change password\" onclick=\"change_password('$current_user')\"/></td>";
                                print "<td><input type=\"button\" value=\"Delete user\" onclick=\"delete_user('$current_user')\"/></td>";
                                print "\n</tr>\n";
                            }
                        }
                        $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="manage_products" class="admin_opt">
        <h3 id="manage_products_header">All products</h3>

        <div id="admin_product_top" class="admin_content">
            <p>Show Products: <select id="admin_select_product_cat" onchange="admin_display_product_list()">
                <?php
                    include('db_connect.php');
                    $sql = 'SELECT DISTINCT product_cat from Products';
                    $result = $conn->query($sql);
                    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                        $sql_row = $result->fetch_assoc();
                        $category = $sql_row['product_cat'];
                        print "<option value=\"$category\">$category</option>";
                    }
                    $conn->close();
                ?>
            </select> <input id="product_view_button" type="button" value="Create Product" onclick="admin_product_view()"/></p>
            <div id="admin_products">
                <script>admin_display_product_list()</script>
            </div>
        </div>

        <div id="add_product" style="display: none">
            <form id="add_product" method="post" action="admin_actions.php" enctype="multipart/form-data" target="my_iframe">
                <table>
                <tr>
                    <td>Product Name:</td><td><input type="text" name="product_name" /></td>
                </tr><tr>
                    <td>Product Category:</td>
                        <td><select id="product_category" name="product_category">
                            <?php
                                include('db_connect.php');
                                $sql = 'SELECT DISTINCT product_cat FROM Products ORDER BY product_cat ASC';
                                $result = $conn->query($sql);
                                if ($result) {
                                    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                                        $sql_row = $result->fetch_assoc();
                                        $category = $sql_row['product_cat'];
                                        print "<option value=\"$category\">$category</option>";
                                    }
                                }
                            ?>
                        </select> <input id="custom_category" type="text" name="custom_category" /><input type="button" value="Create a new category" onclick="create_category()"/> </td>
                </tr><tr>
                    <td>Product Description: </td><td><textarea name="product_desc" cols="50" rows="15"></textarea></td>
                </tr><tr>
                    <td>Product Price:</td><td><input type="text" name="product_price" /></td>
                </tr>
                </tr><tr>
                    <td>Product Image:</td><td><input type="file" name="img_upload" /></td>
                </tr>
                <tr>
                    <td><input type="button" value="Create product" onclick="create_product()"/></td>
                    <td><iframe id="my_iframe" name='my_iframe' class='submit-iframe' scrolling="no"></iframe></td>
                </tr>
                </table>
            </form>
        </div>
    </div>

    <div id="order_history" class="admin_opt">
        <h3 id="order_history_header"> Order History </h3>

        <table class="order_history">
            <thead>
                <td>Product</td>
                <td>Quantity</td>
                <td>Price</td>
            </thead>
            <tbody>
                <?php
                    include('db_connect.php');
                    $user_id_safe = $conn->real_escape_string($user_id);
                    $sql = "SELECT product_name, quantity, product_price FROM Carts INNER JOIN Products ON Carts.product_id=Products.product_id WHERE user_id=\"$user_id_safe\" and purchased=\"true\"";
                    $result = $conn->query($sql);
                    if ($result) {
                        for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                            $sql_row = $result->fetch_assoc();
                            $product_name = $sql_row['product_name'];
                            $qty = $sql_row['quantity'];
                            $price = $sql_row['product_price'];
                            print "<tr>";
                            print "<td>$product_name</td><td>$qty</td><td>$price</td>";
                            print "</tr>";
                        }
                    }
                ?>
            </tbody>
        </table>

    </div>

</div>