<?php 
require 'bdd.php';
require 'function.php';
require 'head.php'; 


if(isset($_POST['r_username']) && isset($_POST['r_password']) && isset($_POST['r_password_bis']) && isset($_POST['r_email'])){

  $password = $_POST['r_password'];
  $password_bis = $_POST['r_password_bis'];
  $email = $_POST['r_email'];
  $username = $_POST['r_username'];

  if(!isUsernameEmailNotUsed($username,$email)){
    echo "<script>alert('Le username  ou le email est déjà utilisé, veuillez vous connecter à votre compte existant ');</script>";
  }else{

    if(!passwordValide($password)){
      echo "<script>alert('Votre Mot de Passe doit etre long de 7 caractère minimum , contenir une majuscule , un chiffre');</script>";
    }
    else if($password != $password_bis){
      echo "<script>alert('Les deux mot de passes ne corresponde pas');</script>";
    }
      else{
    $password_hash = password_hash($password,PASSWORD_DEFAULT);
    $add_client = $bdd->prepare("
    insert into client (pseudo_client , email_client , wallet , mdp_client)
    values(? , ? , ?, ?);
    ");
    $add_client->execute([$username,$email,0.01,$password_hash]);
    echo "<script>alert('Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter en utilisant vos identifiants.');</script>";  
  }

  }

}

else if(isset($_POST['username']) && isset($_POST['password'])){
  $username = $_POST['username'];
  $password = $_POST['password'];
  isUsernameAndPasswordValid($username,$password);
 

  

}



 
  
  

    

  





?>
<body>
    <div class="header">
      <div class="upper_container">
        <div class="logo_container">
          <img class="logo" src="assets/images/DA/athena logo 2.png" />
          <h1 class="text_logo">ATHENA</h1>
        </div>
        <div class="button_container">
          <button type="button" class="login_button">Log IN</button>
        </div>
      </div>
      <div class="lower_container">
        <!--Ajouter les lien cliquable pour arriver sur les differente parti du site-->
      </div>
    </div>

    <div class='login_register_container'>
      <div class='login_register_content'>
        



        <div class='login_container'>

          

          <div class='login_form_card'>
            <div class='login_label_container'>
              <h2 class='login_lable_text'>Log in</h2>
            </div>
            <form method='POST' class='login_form_container'>
              <div class="field_container">

                <label for="username" class="login_form_label">Username</label>
                <input type="text" class="login_form_control" id="username" name='username'>

              </div>
              <div class="field_container">
              
                <label for="password" class="login_form_label">Password</label>
                <input type="password" class="login_form_control" id="password" name='password'>
                

              </div>
              <div class='login_button_container'>
                <button type='submit' class='login_form_button' >Log In</button>
              </div>
            </form>
          </div>
          


        </div>

        <div class='register_container'>
          <div class='register_form_card'>
              <div class='register_label_container'>
                <h2 class='register_lable_text'>Register</h2>
              </div>
              <form method='POST' class='register_form_container'>
                <div class="field_container">

                  <label for="r_username" class="register_form_label">Username</label>
                  <input type="text" class="register_form_control" id="r_username" name='r_username'>

                </div>
                <div class="field_container">
                
                  <label for="r_password" class="register_form_label">Password</label>
                  <input type="password" class="register_form_control" id="r_password" name='r_password'>
                  
                </div>
                <div class="field_container">
                
                  <label for="r_password_bis" class="register_form_label">Password Bis</label>
                  <input type="password" class="register_form_control" id="r_password_bis" name='r_password_bis'>
                  
                </div>
                <div class="field_container">
                
                  <label for="r_email" class="register_form_label">Email</label>
                  <input type="email" class="register_form_control" id="r_email" name='r_email'>
                  
                </div>
                <div class='register_button_container'>
                  <button type='submit' class='register_form_button' >Register</button>
                </div>
              </form>
            </div>
        </div>

      </div>
      
    </div>