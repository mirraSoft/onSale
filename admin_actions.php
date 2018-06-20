<?php
    //If there is no session, make one
    if (session_id() == '') {
        session_start(); 
    }

    //Check the current session
    if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        } else {
            print "Invalid session. Must be authenticated to perform administrative functions.";
            exit();
        }

    //Change password
    if (isset($_POST['action']) and $_POST['action'] === 'change_password') {
        if (isset($_POST['new_pwd']) and isset($_POST['old_pwd'])) {
            $authenticated = False;
            $old_pwd = $_POST['old_pwd'];
            $new_pwd = $_POST['new_pwd'];

            include('db_connect.php'); 

            //Check the old password
            $user_id_safe = $conn->real_escape_string($user_id);
            $sql = "SELECT password FROM Users WHERE user_id=\"$user_id_safe\"";
            $result = $conn->query($sql);
            if ($result) {
                $result = $result->fetch_assoc();
                $sql_password = $result['password'];
                if ($old_pwd === $sql_password) {
                    //Make the change
                    $new_pwd = $conn->real_escape_string($new_pwd);
                    $sql = "UPDATE Users SET password=\"$new_pwd\" WHERE user_id=\"$user_id_safe\"";
                    $result = $conn->query($sql);
                    if ($result) {
                        print 'Password changed.';
                        return;
                    }
                }
            }
            $conn->close();
        }
        print 'Error changing password. Contact your administrator.';
        return;
    }

    //Delete User
    if (isset($_POST['action']) and $_POST['action'] === 'delete_user') {
        if (isset($_POST['user_id'])) {
            $delete_user_id = $_POST['user_id'];
            include('db_connect.php');
            $delete_user_id_safe = $conn->real_escape_string($delete_user_id);
            //Make sure we are an admin
            $user_id_safe = $conn->real_escape_string($user_id);
            $sql = "SELECT is_admin FROM Users WHERE user_id = \"$user_id_safe\"";
            $result = $conn->query($sql);
            $user_is_admin = False;
            if ($result and mysqli_num_rows($result) === 1) {
                $result = $result->fetch_assoc();
                if ($result['is_admin'] === 'true') {
                    $user_is_admin = True;
                }
            }
            //Make sure the user to delete is not an admin
            $sql = "SELECT is_admin FROM Users WHERE user_id = \"$delete_user_id_safe\"";
            $result = $conn->query($sql);
            $delete_user_is_admin = False;
            if ($result and mysqli_num_rows($result) === 1) {
                $result = $result->fetch_assoc();
                if ($result['is_admin'] === 'true') {
                    $delete_user_is_admin = True;
                }
            }
            if ($user_is_admin and !$delete_user_is_admin) {
                //First delete Carts since user_id is a foreign key there
                $sql = "DELETE FROM Carts WHERE user_id = \"$delete_user_id_safe\"";
                $result = $conn->query($sql);
                //If the Carts deletions were successful, delete the User
                if ($result) {
                    $sql = "DELETE FROM Users WHERE user_id = \"$delete_user_id_safe\"";
                    $result = $conn->query($sql);
                    if ($result) {
                        print 'User deleted.';
                        return;
                    }
                }
            }
            $conn->close();
        }
        print 'Error deleting user.';
        return;
    }

    //Change other user's password
    if(isset($_POST['action']) and $_POST['action'] === 'change_other_password') {
        if (isset($_POST['user_id']) and isset($_POST['new_pwd'])) {
            $target_user_id = $_POST['user_id'];
            $new_pwd = $_POST['new_pwd'];
            include('db_connect.php');
            $target_user_id_safe = $conn->real_escape_string($target_user_id);
            $new_pwd_safe = $conn->real_escape_string($new_pwd);
            //Make sure we are an admin
            $user_id_safe = $conn->real_escape_string($user_id);
            $sql = "SELECT is_admin FROM Users WHERE user_id = \"$user_id_safe\"";
            $result = $conn->query($sql);
            if ($result and mysqli_num_rows($result) === 1) {
                $result = $result->fetch_assoc();
                if ($result['is_admin'] != 'true') {
                    print "Admin permissions required to change another user's password.";
                    return;
                }
            }
             //Make sure the target user is not an admin
            $sql = "SELECT is_admin FROM Users WHERE user_id = \"$target_user_id_safe\"";
            $result = $conn->query($sql);
            if ($result and mysqli_num_rows($result) === 1) {
                $result = $result->fetch_assoc();
                if ($result['is_admin'] === 'true') {
                    print 'Can not change the password of an Admin user. ';
                    return;
                }
            }
            //Update the user's password
            $sql = "UPDATE Users SET password = \"$new_pwd_safe\" WHERE user_id = \"$target_user_id_safe\"";
            $result = $conn->query($sql);
            if ($result) {
                print 'Password changed';
                $conn->close();
                return;
            }
        }
        print 'Error changing password.';
        $conn->close();
        return;
    }

    //Create a new user
    if (isset($_POST['action']) and $_POST['action'] === 'create_user') {
        if (isset($_POST['user_id']) and isset($_POST['new_pwd']) and isset($_POST['priv'])) {
            $new_user_id = $_POST['user_id'];
            $new_pwd = $_POST['new_pwd'];
            if ($_POST['priv'] === 'admin') {
                $new_is_admin = 'true';
            } else {
                $new_is_admin = 'false';
            }
            include('db_connect.php');
            //Make sure we are an admin
            $user_id_safe = $conn->real_escape_string($user_id);
            $sql = "SELECT is_admin FROM Users WHERE user_id = \"$user_id_safe\"";
            $result = $conn->query($sql);
            if ($result and mysqli_num_rows($result) === 1) {
                $result = $result->fetch_assoc();
                if ($result['is_admin'] != 'true') {
                    print "Admin permissions required.";
                    $conn->close();
                    return;
                }
            }
            //Check if user already exists
            $new_user_id_safe = $conn->real_escape_string($new_user_id);
            $new_pwd_safe = $conn->real_escape_string($new_pwd);
            $sql = "SELECT user_id FROM Users WHERE user_id = \"$new_user_id_safe\"";
            $result = $conn->query($sql);
            if ($result and mysqli_num_rows($result) === 0) {
                //Insert the user into Users
                $sql = "INSERT INTO Users VALUES('$new_user_id_safe', '$new_pwd_safe', '$new_is_admin')";
                $result = $conn->query($sql);
                if ($result) {
                    print "User created.";
                    $conn->close();
                    return;
                }
            }
        }
        print 'Error creating user.';
        $conn->close();
        return;
    }

    //Display Products in a table
    if (isset($_POST['action']) and $_POST['action'] === 'display_product_list')  {
        if (isset($_POST['product_cat'])) {
            $category = $_POST['product_cat'];
            include('db_connect.php');
            $sql = "SELECT product_id, product_name, product_desc, product_price, product_cat FROM Products WHERE product_cat like \"$category\"";
            $result = $conn->query($sql);
            for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                $sql_row = $result->fetch_assoc();
                $product_id = $sql_row['product_id'];
                $product_name = $sql_row['product_name'];
                $product_desc = $sql_row['product_desc'];
                $product_price = $sql_row['product_price'];
                $product_cat = $sql_row['product_cat'];
                print "<table id=\"$product_id\"class=\"admin_product\" style=\"table-layout:fixed\">";
                print '<col width="20%" />';
                print '<col width="40%" />';
                print '<col width="40%" />';
                print '<tbody>';
                print "<tr class=\"product_id\">";
                    print "<td>Product ID:</td>";
                    print "<td class=\"product_display\">$product_id</td>";
                    print "<td class=\"product_edit\"><input type=\"text\" name=\"product_id\" value=\"$product_id\"/></td>";
                    print "<td><input class=\"edit_product_button\" type=\"button\" value=\"Edit Product\" onclick=\"edit_product($product_id)\"/><input class=\"delete_product_button\" type=\"button\" value=\"Delete Product\" onclick=\"delete_product($product_id)\"/></td>";
                print "</tr>";
                print "<tr class=\"product_name\">";
                    print "<td>Product Name:</td>";
                    print "<td class=\"product_display\">$product_name</td>";
                    print "<td class=\"product_edit\"><input type=\"text\" name=\"product_name\" value=\"$product_name\"/></td>";
                print "</tr>";
                print "<tr class=\"product_cat\">";
                    print "<td>Product Category:</td>";
                    print "<td class=\"product_display\">$product_cat</td>";
                    print "<td class=\"product_edit\"><input type=\"text\" name=\"product_cat\" value=\"$product_cat\"/></td>";
                print "</tr>";
                print "<tr class=\"product_desc\">"; 
                    print "<td>Product Description:</td>";
                    print "<td class=\"product_display\">$product_desc</td>";
                    print "<td class=\"product_edit\"><textarea name=\"product_desc\" style=\"width: 95%; height: 10em;\">$product_desc</textarea></td>";                    
                print "</tr>";
                print "<tr class=\"product_price\">";
                    print "<td>Product Price:</td>";
                    print "<td class=\"product_display\">$product_price</td>";
                    print "<td class=\"product_edit\"><input type=\"text\" name=\"product_price\" value=\"$product_price\"/></td>";
                print "</tr>";
                print '</tbody>';
                print '</table>';
                //Don't know why CSS can't touch the display property, but this seems to work fine.
                print "<script>$('table#$product_id td.product_edit').hide()</script>";

                print '<hr />';
            }
            $conn->close();
        }
    }

    //Create a new product
    if (isset($_POST['product_name']) and isset($_POST['product_category']) and isset($_POST['product_desc']) and isset($_POST['product_price']) and isset($_FILES["img_upload"]["name"])) {
        include('db_connect.php');
        //Make sure we are an admin
        $user_id_safe = $conn->real_escape_string($user_id);
        $sql = "SELECT is_admin FROM Users WHERE user_id = \"$user_id_safe\"";
        $result = $conn->query($sql);
        if ($result and mysqli_num_rows($result) === 1) {
            $result = $result->fetch_assoc();
            if ($result['is_admin'] != 'true') {
                print "Admin permissions required.";
                $conn->close();
                return;
            }
        }
        //Add the product to Products
        $product_name_safe = $conn->real_escape_string($_POST['product_name']);
        $product_desc_safe = $conn->real_escape_string($_POST['product_desc']);
        $product_price_safe = $conn->real_escape_string(floatval($_POST['product_price']));
        $product_category_safe = $conn->real_escape_string($_POST['product_category']);
        $sql = "INSERT INTO Products VALUES(DEFAULT, \"$product_name_safe\", \"$product_desc_safe\", $product_price_safe, \"$product_category_safe\")";
        $result = $conn->query($sql);
        if ($result) {
            //Get the id that was just added
            $sql = "SELECT LAST_INSERT_ID()";
            $result = $conn->query($sql);
            if ($result) {
                $sql_row = $result->fetch_assoc();
                $last_id = $sql_row['LAST_INSERT_ID()'];
            }
        }
        $conn->close();
        //Upload the products image file
        if (isset($last_id)) {
            $target_dir = 'img/';
            $upload_ok = 1;
            $image_file_type = pathinfo($_FILES['img_upload']['name'], PATHINFO_EXTENSION);
            $target_file = $target_dir . $last_id . '.' . $image_file_type;
            $check = getimagesize($_FILES['img_upload']['tmp_name']);
            if ($check !== False) {
                $upload_ok = 1;
            } else {
                print 'File is not an image.';
                $upload_ok = 0;
            }
            if (file_exists($target_file)) {
                print 'File already exists';
                $upload_ok = 0;
            }
            if ($_FILES['img_upload']['size'] > 500000) {
                print 'File too large';
                $upload_ok = 0;
            }
            if ($image_file_type != 'jpg' && $image_file_type != 'png' && $image_file_type != 'jpeg' && $image_file_type != 'gif') {
                print 'Wrong file type. Use JPG, JPEG, PNG, GIF.';
                $upload_ok = 0;
            }

            if ($upload_ok == 0) {
                print 'Canceled upload.';
            } else {
                if (move_uploaded_file($_FILES['img_upload']['tmp_name'], $target_file)) {
                    print 'Product added.';
                    return;
                } else {
                    print 'Error uploading file.';
                }
            }
        }
    print 'Error adding product';
    return;
    }

    //Edit product information
    if (isset($_POST['action']) and $_POST['action'] === 'edit_product' and isset($_POST['product_id_pk'])) {
        $product_id_pk = $_POST['product_id_pk'];
        if (isset($_POST['product_id']) or isset($_POST['product_name']) or isset($_POST['product_cat']) or isset($_POST['product_desc']) or isset($_POST['product_price'])) {

            
            include('db_connect.php');

            //Check if user is admin
            $user_id_safe = $conn->real_escape_string($user_id);
            $sql = "SELECT is_admin FROM Users WHERE user_id = \"$user_id_safe\"";
            $result = $conn->query($sql);
            if ($result and mysqli_num_rows($result) === 1) {
                $result = $result->fetch_assoc();
                if ($result['is_admin'] != 'true') {
                    print "Admin permissions required.";
                    $conn->close();
                    return;
                }
            }

            //Create UPDATE sql
            $product_id_pk = $conn->real_escape_string($product_id_pk);
            $mods = array();
            if (isset($_POST['product_id'])) {
                $product_id = $_POST['product_id'];
                $product_id = $conn->real_escape_string($product_id);
                array_push($mods, "product_id=\"$product_id)\"");
            }
            if (isset($_POST['product_name'])) {
                $product_name = $_POST['product_name'];
                $product_name = $conn->real_escape_string($product_name);
                array_push($mods, "product_name=\"$product_name\"");
            }
            if (isset($_POST['product_cat'])) {
                $product_cat = $_POST['product_cat'];
                $product_cat = $conn->real_escape_string($product_cat);
                array_push($mods, "product_cat=\"$product_cat\"");
            }
            if (isset($_POST['product_desc'])) {
                $product_desc = $_POST['product_desc'];
                $product_desc = $conn->real_escape_string($product_desc);
                array_push($mods, "product_desc=\"$product_desc\"");
            }
            if (isset($_POST['product_price'])) {
                $product_price = $_POST['product_price'];
                $product_price = $conn->real_escape_string($product_price);
                array_push($mods, "product_price=\"$product_price\"");
            }
            //Run the sql
            $set = join(', ', $mods);
            $sql = "Update Products SET $set WHERE product_id=\"$product_id_pk\"";
            $result = $conn->query($sql);
            if ($result) {
                print 'Product updated.';
                $conn->close();
                return;
            }

            $conn->close();
        }
    print 'Error.';
    return;
    }


    //Delete a product
    if (isset($_POST['action']) and $_POST['action'] === 'delete_product' and isset($_POST['product_id'])) {
        include('db_connect.php');
        //Check if user is admin
        $user_id_safe = $conn->real_escape_string($user_id);
        $sql = "SELECT is_admin FROM Users WHERE user_id = \"$user_id_safe\"";
        $result = $conn->query($sql);
        if ($result and mysqli_num_rows($result) === 1) {
            $result = $result->fetch_assoc();
            if ($result['is_admin'] != 'true') {
                print "Admin permissions required.";
                $conn->close();
                return;
            }
        }
        //Delete the product
        $product_id = $_POST['product_id'];
        $product_id = $conn->real_escape_string($product_id);
        $sql = "DELETE FROM Products WHERE product_id=\"$product_id\"";
        $result = $conn->query($sql);
        if ($result) {
            print 'Product deleted.';
            $conn->close();
            return;
        }
        print 'Error.';
        $conn->close();
        return;
    }

?>