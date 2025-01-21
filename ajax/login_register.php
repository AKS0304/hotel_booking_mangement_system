<?php 
    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');


    date_default_timezone_set("Asia/kolkata");

    // Registration processes
    if (isset($_POST['register'])) {
        $data = filteration($_POST);

        // Check if passwords match
        if ($data['pass'] != $data['cpass']) {
            echo 'pass_mismatch';
            exit;
        }

        // Check if user already exists
        $u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? OR  `phonenum`=? LIMIT 1", [$data['email'], $data['phonenum']], "ss");

        if (mysqli_num_rows($u_exist) != 0) {
            $u_exist_fetch = mysqli_fetch_assoc($u_exist);
            echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'phone_already';
            exit;
        }

        // Upload user image to server
        $img = uploadUserImage($_FILES['profile']);

        if ($img == 'inv_img') {
            echo 'inv_img';
            exit;
        } elseif ($img == 'upd_failed') {
            echo 'upd_failed';
            exit;
        }

        // Hash password
        $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);

        // Insert user data into database
        $query = "INSERT INTO `user_cred`(`name`, `email`, `address`, `phonenum`, `pincode`, `dob`, `profile`, `password`, `token`) VALUES (?,?,?,?,?,?,?,?,?)";
        $values = [$data['name'], $data['email'], $data['address'], $data['phonenum'], $data['pincode'], $data['dob'], $img, $enc_pass, $token];

        if (insert($query, $values, 'sssssssss')) {
            echo 1;  // Registration successful
        } else {
            echo 'ins_failed';
        }
    }

    // Login processes
    if (isset($_POST['login'])) {
        $data = filteration($_POST);

        // Check if user exists
        $u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? OR  `phonenum`=? LIMIT 1",
        [$data['email_mob'], $data['email_mob']], "ss");

        if (mysqli_num_rows($u_exist) == 0) {
            echo 'inv_email_mob';
            exit;
        } 
        else {
            $u_fetch = mysqli_fetch_assoc($u_exist);
            if ($u_fetch['status'] == 0) {
                echo 'inactive';
                exit;
            } 
            else {
                if (!password_verify($data['pass'], $u_fetch['password'])) {
                    echo 'invalid_pass';
                    exit;
                } 
                else {
                    session_start();
                    $_SESSION['login']   = true;
                    $_SESSION['uId']     = $u_fetch['id'];
                    $_SESSION['uName']   = $u_fetch['name'];
                    $_SESSION['uPic']   = $u_fetch['profile'];
                    $_SESSION['uPhone']  = $u_fetch['phonenum'];
                    echo 1;
                }
            }
        }
    }


    // Forgot password Verification process
    if (isset($_POST['forget'])) {
        $data = filteration($_POST);

        // Check if user exists
        $u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? AND  `phonenum`=? AND `dob`=? LIMIT 1",
        [$data['email'], $data['phonenum'], $data['dob']], "sss");

        if (mysqli_num_rows($u_exist) == 0) {
            echo 'inv_cred';
        } 
        else {
            // Assuming user exists, generate a unique session ID and store user's ID in session
            session_start();
            $user_data = mysqli_fetch_assoc($u_exist);
            $_SESSION['uId'] = $user_data['id'];
            echo 'success';
        }
    }  

    // Reset password process
    if(isset($_POST['reset'])) {
        $frm_data = filteration($_POST);
        session_start();

        // Verify if passwords match
        if($frm_data['new_pass'] != $frm_data['confirm_pass']) {
            echo 'mismatch';
            exit;
        } 

        // Retrieve user ID from session
        $userId = $_SESSION['uId'];
        
        $enc_pass = password_hash($frm_data['new_pass'], PASSWORD_BCRYPT);
        $query = "UPDATE `user_cred` SET `password`=? WHERE `id`=? LIMIT 1";
        $values = [$enc_pass, $userId];

        if(update($query, $values, 'ss')) {
            echo 'success';
        }
        else {
            echo 'error';
        }
    }


?>

      