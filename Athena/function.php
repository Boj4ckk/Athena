<?php 
require 'bdd.php';
function passwordValide($motDePasse) {
    if (strlen($motDePasse) < 7) {
        return false; 
    }
  
    if (!preg_match('/[A-Z]/', $motDePasse)) {
        return false; 
    }
  
    if (!preg_match('/\d/', $motDePasse)) {
        return false; 
    }
  
    return true; 
  }

  function isUsernameEmailNotUsed($username , $email){
    global $bdd;
    $user_email_query = $bdd->prepare("SELECT pseudo_client , email_client from client where pseudo_client = ? OR email_client = ? ");
    $user_email_query->execute([$username,$email]);
    $user_email_list = $user_email_query->fetchAll();

    if(count($user_email_list) > 0){
        return false ;
    }
    return true;
  }

  function isUsernameAndPasswordValid($username , $password){
    global $bdd;
    $username_password_query = $bdd->prepare("SELECT id_client ,pseudo_client , mdp_client , role , wallet from client where pseudo_client = ?");
    $username_password_query->execute([$username]);
    $username_password_list = $username_password_query->fetchAll();

    $hashedpass = $username_password_list[0][2];

    if(count($username_password_list )>0){
        if(password_verify($password,$hashedpass)){
            $username_password_list = $username_password_list[0];
            session_start();
            $_SESSION['id_client'] = $username_password_list[0];
            $_SESSION['username'] = $username_password_list[1];
            $_SESSION['role'] = $username_password_list[3];
            $_SESSION['wallet'] = $username_password_list[4];
            if($username_password_list[3] == 1){
                header('Location:admin_panel.php');

            }
            else{
                header('Location:index.php');
            }
           
         }
         else{
            echo "<script>alert('Mot de passe ou Username Invalide ! ')</script>";
        }
       
        
    }

    
    else{
        echo "<script>alert('Mot de passe ou Username Invalide ! ')</script>";

    }

    
  }

  function isUsernameUnchanged($username , $new_username){
    if($username == $new_username){
        return true;
    }
    return false;
  }


  function isEmailUnchanged($email , $new_email){
    if($email == $new_email){
        return true;
    }
    return false;
  }

  function isUsernameEmailAvailable($new_username, $new_email, $current_user_id) {
    global $bdd;
    $query = $bdd->prepare("SELECT id_client FROM client WHERE (pseudo_client = ? OR email_client = ?) AND id_client != ?");
    $query->execute([$new_username, $new_email, $current_user_id]);
    return $query->rowCount() === 0;
}

?>