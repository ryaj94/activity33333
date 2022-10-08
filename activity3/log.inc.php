<?php
    session_start();
 
    if(isset($_POST['submit'])){
        //connection
        require 'config.php';



        $username = $_POST["email"];
        $pwd = $_POST["pwd"];
        //set login attempt if not set
        if(!isset($_SESSION['attempt'])){
            $_SESSION['attempt'] = 0;
        }
 
        //check if there are 3 attempts already
        if($_SESSION['attempt'] == 3){
            $_SESSION['error'] = 'Attempt limit reach';
        }
        else{
            //get the user with the email
            $sql = "SELECT * FROM account WHERE username = '".$_POST['email']."'";
            $query = $conn->query($sql);
          
            if($query->num_rows > 0){
                $row = $query->fetch_assoc();
                $ppwd=$row['password'];
                //verify password
                if(($pwd ===  $ppwd)){
                    //action after a successful login
                    //for now just message a successful login
                    $_SESSION['success'] = 'Login successful';
                    //unset our attempt
                    unset($_SESSION['attempt']);
                   
                }
                else{
                   
                    //this is where we put our 3 attempt limit
                    $_SESSION['attempt'] += 1;
                    $att=3-$_SESSION['attempt'];
                    $_SESSION['error'] = "Incorrect password, {$att}  attempts left.";
                    //set the time to allow login if third attempt is reach
                    if($_SESSION['attempt'] == 3){
                        $_SESSION['attempt_again'] = time() + (2*60);
                        //note 5*60 = 5mins, 60*60 = 1hr, to set to 2hrs change it to 2*60*60
                    }
                   
                }
            }
            else{
                $_SESSION['error'] = 'No account with that username';
                
            }
 
        }
 
    }
    else{
        $_SESSION['error'] = 'Fill up login form first';
    }
 
    header('location: index.php');
 
?>