<?php
    //If there is no session, make one
    if (session_id() == '') {
        session_start(); 
    } 
?>

<div class="center">

<?php
    //if there is a logout command
    if (isset($_POST['action']) and $_POST['action'] === 'logout') {
        session_unset();
        session_destroy();
        session_start();
        print "<p>Logout successful</p>\n";
        print "<script>
                    setTimeout(function() {
                        window.location.href = \"index.php\";
                    },3000);
                </script>\n";
        return;
    }
    
    //if there is a login command
    if (isset($_POST['action']) and $_POST['action'] === 'login' and isset($_POST['user_id']) and isset($_POST['pwd'])) {
        $user_id = $_POST['user_id'];
        $pwd = $_POST['pwd'];
		
        //Authenticate
        include('db_connect.php');
        
        $sql_success = True;
        $user_id = $conn->real_escape_string($user_id);
        $sql = "SELECT password FROM Users WHERE user_id=\"$user_id\"";
        $result = $conn->query($sql);
        if ($result) {
            $result = $result->fetch_assoc();
            $sql_password = $result['password'];
        } else {
            $sql_success = False;
        }
        $sql = "SELECT is_admin FROM Users WHERE user_id=\"$user_id\"";
        $result = $conn->query($sql);
        if ($result) {   
            $result = $result->fetch_assoc();
            $is_admin = $result['is_admin'];
        } else {
            $sql_success = False;
        }
        $conn->close();
        
        if ($sql_success === True and $pwd === $sql_password) {
            $_SESSION['user_id'] = $user_id;
            if ($is_admin === 'true') {
                $_SESSION['is_admin'] = True;
            }
            print "<p>Login successful</p>\n";
            print "<script>
                    setTimeout(function() {
                        window.location.href = \"index.php\";
                    },3000);
                    </script>";
            return;
        } else {
            print "<p>Login unsuccessful</p>";
        }
    }
    
    //if there is a create command
    if (isset($_POST['action']) and $_POST['action'] === 'create' and isset($_POST['user_id']) and isset($_POST['pwd'])) {
        $user_id = $_POST['user_id'];
        $pwd = $_POST['pwd'];
        
        //Authenticate
        include('db_connect.php');
        $user_id = $conn->real_escape_string($user_id);
        $pwd = $conn->real_escape_string($pwd);
        
        //Check to see the user_id does not already exist
        $sql = "SELECT user_id FROM Users WHERE user_id=\"$user_id\"";
        $result = $conn->query($sql);
        if (mysqli_num_rows($result) === 0) {
            //Insert the new user into Users
            $sql = "INSERT INTO Users VALUES(\"$user_id\",\"$pwd\",DEFAULT)";
            $result = $conn->query($sql);
            if ($result) {
                print "<p>User $user_id created.";
            } else {
                print "<p>Error creating user. Contact administrator for assistance.</p>";
            }
        } else {
            print "<p>User already exists</p>";
        }
        $conn->close();
    }

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        print "<p>Logged in($user_id)</p>\n";
        print "<p><a href=\"#\" onclick=\"logout()\">Log out</a><p>\n";
    } else {
        print "<form id=\"login_form\" onsubmit=\"login()\"; return false;\">\n";
        print "<p>User ID:</p><p><input type=\"text\" name=\"user_id\" /></p>\n";
        print "<p>Password:</p><p><input type=\"password\" name=\"pwd\" onkeydown = \"if (event.keyCode == 13) login()\" /></p>\n";
        print "<p><button type=\"button\" onclick=\"login()\">Submit</button></p>\n";
        print "</form>\n";
        print "<p><a href=\"#\" onclick=\"create_user()\">Create new user</a></p>\n";
    }
?>

</div>