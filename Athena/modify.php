<?php

require 'bdd.php';
require 'function.php';
session_start();


$user_id = $_SESSION['id_client'];
$user_data_query = $bdd->prepare("SELECT pseudo_client , mdp_client,email_client , wallet from client where id_client = ?");
$user_data_query->execute([$user_id]);

$user_data_list = $user_data_query->fetchAll();

$user_data = $user_data_list[0];

$liste_label_data;
$user_data['mdp_client'] = "*******";

$liste_label_data["Pseudo"] = $user_data["pseudo_client"];
$liste_label_data["Password"] = $user_data["mdp_client"];
$liste_label_data["Email"] = $user_data["email_client"];
$liste_label_data["Wallet"] = $user_data['wallet'];









$purchase_date_quantity_query = $bdd->prepare("
SELECT  achete.id_produit ,achete.quantite_acheter, achete.date_transaction, produit.nom , produit.prix
FROM achete
JOIN produit ON produit.id_produit = achete.id_produit
WHERE achete.id_client = ?
");



$purchase_date_quantity_query->execute([$user_id]);
$purchase_date_quantity_list = $purchase_date_quantity_query->fetchALL();
$purchase_by_date = array(); 
foreach($purchase_date_quantity_list as $purchase) {
    $date = $purchase['date_transaction'];

    if(!isset($purchase_by_date[$date])) {
        $purchase_by_date[$date] = array(); 
    }

    
    $purchase_data = array();
    array_push($purchase_data, $purchase['nom'], $purchase['prix'], $purchase['quantite_acheter']);

   
    $purchase_by_date[$date][] = $purchase_data; 
}






$liked_product_img_query = $bdd->prepare("
SELECT produit.image 
FROM produit 
JOIN aime_produit ON produit.id_produit = aime_produit.id_produit
WHERE aime_produit.id_client = ?
");


$liked_product_img_query->execute([$user_id]);
$liked_product_img_list = $liked_product_img_query->fetchAll();



if(isset($_POST['Password']) && isset($_POST['Email']) && isset($_POST['Pseudo'])){

    $password = $_POST['Password'];
    $new_email = $_POST['Email'];
    $new_username = $_POST['Pseudo'];



    $current_username = $liste_label_data['Pseudo'];
    $current_email = $liste_label_data['Email'];
    $password_hash = password_hash($password,PASSWORD_DEFAULT);

    if (($new_username != $current_username || $new_email != $current_email) && !isUsernameEmailAvailable($new_username, $new_email, $user_id)) {
        echo "<script>alert('Username ou email est déjà utilisé par un autre utilisateur.');</script>";
    }
    else{
        if(!passwordValide($password)){
            echo "<script>alert('Votre Mot de Passe doit etre long de 7 caractère minimum , contenir une majuscule , un chiffre');</script>";
            
        }
        else{
            $modify_query = $bdd->prepare("
            UPDATE client SET email_client= ? , pseudo_client = ? , mdp_client = ?  WHERE id_client = ?
            ");
            $modify_query->execute([$new_email , $new_username , $password_hash, $user_id ]);

            echo "<script>alert('Les informations de votre compte on etait modifier avec succès. Vous pouvez maintenant vous connecter en utilisant vos nouveau identifiants.');</script>"; 
        }

    }
  

         
}






?>

<?php require 'head.php'?>
  <body>
    <div class="header">
      <div class="upper_container">
        <div class="logo_container">
          <img class="logo" src="assets/images/DA/athena logo 2.png" />
          <h1 class="text_logo">ATHENA</h1>
        </div>
        <?php 
        if(isset($_SESSION)){
        
          echo "<div class='profile_cart_container'>

                  <div class='profile_logo_container'>
                    <a href='profile.php'>
                    <i class='fa-solid fa-user'></i>
                    </a>
                  </div>

                  

                </div>";
                
        }
        else{
          echo '
            <div class="button_container">
              <button id="goToLogin" type="button" class="login_button">Log IN</button>
            </div>
          
              ';
        }
        
        ?>
       
      </div>
      <div class="lower_container">
        <!--Ajouter les lien cliquable pour arriver sur les differente parti du site-->
      </div>
    </div>
    <div class='profile_page_container'>
        <div class='profile_page_content'>
            <div class='modify_and_disconnect_container'>
                <div class='modify_and_disconnect_content'>
                    
                    <div class='disconnect_card_modify'>
                        <a href='disconnect.php'>
                            <button class='disconnect_button'>Se deconnecter</button>
                        </a>
                    </div>

                </div>

            </div>

            <div class='personnal_data_container'>
                <div class='personnal_data_content'>
                <div class='modify_title_container'>
                     <h5 class='modify_title_text'>informations personnelle</h5>
                </div>
                    <div class='personnal_data_card'>
                        <form method='post' class='post_modify_form'>
                        <?php 
                        
                            foreach($liste_label_data as $label => $data){
                                

                                
                                    echo "<div class='personnal_data_card_container'>"; 

                                    if($label != "Wallet"){

                                
                                        echo "
                                            
                                            <div class='personnal_data_card_label_container'>
                                                <h4 class='personnal_data_card_label_text'>".$label.' :'."</h4>
                                            </div>

                                            <div class='personnal_data_card_data_modify'>
                                                <input name='".$label."' value='".$data." 'class='personnal_data_card_data_text'>
                                            </div>
                                            
                                           

                                            ";
                                    }
                                    
                                    echo "</div>";
                               
                            }
                           
                        
                        ?>
                        <button type='submit' class='submit_modify'>Valider</button>
                        </form>
                    
                        

                    </div>

                </div>

            </div>

            <div class='history_liked_container'>
                <div class='modify_title_container'>
                     <h5 class='modify_title_text'>Historique des achats</h5>
                </div>
                <div class='history_card'>
                    <div class='history_card_data_container'>
                        
                    <?php foreach($purchase_by_date as $date => $purchase_list){
                        echo "<div class='history_card_date_container'>
                                <h4 class='history_card_date_text'>".$date."</h4>
                            </div>";

                        foreach($purchase_list as $purchase){
                            echo "<div class='history_card_data_content'>
                                    <h5 class='history_card_data_text_name'>".$purchase[0]." x ".$purchase[2]."</h5>
                                    <h5 class='history_card_data_text_price'>".$purchase[1]." BTC</h5>
                                </div>";
                        }
                    }
                    ?>
                        


                    </div>

                </div>

                <div class='liked_product_container'>
                 <div class='liked_product_card'>
                        <div class='liked_product_card_container'>
                        <?php
                        foreach($liked_product_img_list as $product){
                            echo "
                            <div class='liked_profile_product_container'>
                                <img src='".$product['image']."' class='liked_profile_product_img'/>
                            </div>
                            
                            ";
                        }
                        
                        
                        ?>
                        
                        </div>
                    </div>
                    <div class='liked_title_container'>
                     <h5 class='modify_title_text'>Produits liker</h5>
                </div>

                </div>
                
            </div>


            </div>

            
        </div>

      </div>