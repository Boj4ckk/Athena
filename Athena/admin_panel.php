

<?php
require 'bdd.php';
session_start();

$users_query = $bdd->prepare("SELECT id_client , pseudo_client from client where id_client != ? and role != 1;");
$users_query->execute([$_SESSION["id_client"]]);
$users_query_list = $users_query->fetchAll();



$products_query = $bdd->query("SELECT id_produit ,image ,nom ,quantité_disponible from produit");
$product_list = $products_query->fetchAll();


if(isset($_GET['sup'])){
    $id_ban = $_GET['sup'];
    $ban_query = $bdd->prepare("DELETE from client where id_client = ? ");
    $ban_query->execute([$id_ban]);
    echo "<script>alert('Utilisateur Bannis ! ');</script>";
    header("Location:admin_panel.php");
}

else if(isset($_GET['upgrade'])){
    $id_up = $_GET['upgrade'];

    $up_query = $bdd->prepare("UPDATE client SET role = 1 WHERE id_client = ?;");
    $up_query->execute([$id_up]);
    echo "<script>alert('Utilisateur est maintenant un admin! ');</script>";
    header("Location:admin_panel.php");

}
else if(isset($_POST['quantite'])){
    $quantite = $_POST['quantite'];
    $id_produit = $_POST['id_produit'];
    $add_query = $bdd->prepare("UPDATE produit set quantité_disponible = quantité_disponible + ? where id_produit = ? ");
    $add_query->execute([$quantite , $id_produit]);
    echo "<script>alert('La quantité a était ajoutée avec succèes! ');</script>";
    header("Location:admin_panel.php");
}

if(isset($_POST['nom_event']) && isset($_POST['ville_event']) && isset($_POST['pays_event']) && isset($_POST['date_event']) && isset($_FILES['image_event'])){
    
    $target_dir = 'assets/images/Photo_events/';
    $target_file = $target_dir . basename($_FILES["image_event"]["name"]);$nom = $_POST['nom_event'];

    $ville = $_POST['ville_event'];
    $pays = $_POST['pays_event'];
    $date = $_POST['date_event'];
    $image =  $target_file;


    

    if (move_uploaded_file($_FILES["image_event"]["tmp_name"], $target_file)) {
        $add_event_query = $bdd->prepare("
        insert into evenements (nom_event,ville_event,pays_event,date_event,photo_event)values
        (?,?,?,?,?);
        
        ");
        $add_event_query->execute([$nom,$ville,$pays,$date,$image]);
        echo "<script>alert('L'evenement a était ajoutée avec succèes ! ');</script>";
        header("Location:admin_panel.php");

    } else {
        echo "Erreur lors du téléchargement du fichier.";
    }
}








?>
<?php require 'head.php'?>
  <body>
    <div class="header">
      <div class="upper_container">
        <div class="logo_container">
          <img class="logo" src="assets/images/DA/athenalogo2.png" />
          <h1 class="text_logo">ATHENA</h1>
        </div>
        
      </div>
    
      <div class='lower_container_product'>
        <h3 class='product_page_text'>Admin Panel</h3>
      </div>
    
</div>
      <div class='admin_panel_container'>
        <div class='admin_panel_content'>

            
            <div class='admin_users_card'>
                <div class='modify_title_container'>
                        <h5 class='modify_title_text'>Gestion Utilisateurs</h5>
                </div>
                <div class='admin_user_container'>
                    <?php
                        foreach($users_query_list as $user){
                            echo "
                                <div class='admin_user_content'>
                                    <h4 class='admin_username'>".$user[1]."</h4>
                                    <div class='admin_user_icon'>
                                        <a href='admin_panel?sup=".$user[0]."'>
                                        <i class='fa-solid fa-circle-xmark'></i>
                                        </a>

                                        <a href='admin_panel?upgrade=".$user[0]."'>
                                        <i class='fa-solid fa-circle-up'></i>
                                        </a>
                                    </div>
                                </div>

                                 ";
                        }
                    
                    ?>
                </div>
            </div>

            <div class='admin_products_card'>
                <div class='modify_title_container'>
                        <h5 class='modify_title_text'>Gestion Produits</h5>
                    </div>
            
                    <div class='admin_product_container'>
                        <?php
                        foreach($product_list as $product){
                            echo "
                                <div class='admin_product_content'>
                                    <div class='admin_product_upper'>
                                        <img src='".$product[1]."' class='admin_product_img'>
                                        <h4 class='admin_product_name'>".$product[2]."</h4>
                                        <h4 class='admin_product_qt'>"."qt : ".$product[3]."</h4>
                                    </div>
                                    <form method='POST'>
                                    <div class='admin_product_lower'>

                                        <input type='hidden' name='id_produit' value='".$product[0]."'>
                                        <input name='quantite' class='admin_prouct_add'>
                                        <button type ='submit' class='admin_add_product_qt'>Ajouter</button>
                                       
                                    </div>
                                    </form>
                                </div>
                            
                            
                                ";
                        }
                        
                        ?>
                    </div>
                
            </div>

            <div class='admin_events_card'>

                <div class='modify_title_container'>
                            <h5 class='modify_title_text'>Gestion Evenements</h5>
                        </div>
                <div class='admin_event_content'>
                    <form method='POST' enctype="multipart/form-data">

                        <div class='admin_event_field'>
                            <label for='nom' class='admin_event_name'>Nom :</label>
                            <input type='text' name='nom_event' class='admin_event_input' >
                        </div>

                        <div class='admin_event_field'>
                            <label for='ville' class='admin_event_name'>Ville :</label>
                            <input type='text' name='ville_event' class='admin_event_input' >
                        </div>

                        <div class='admin_event_field'>
                            <label for='pays' class='admin_event_name'>Pays :</label>
                            <input type='text' name='pays_event' class='admin_event_input' >
                        </div>

                        <div class='admin_event_field'>
                            <label for='date' class='admin_event_name'>Date :</label>
                            <input type='date' name='date_event' class='admin_event_input' >
                        </div>

                        <div class='admin_event_field'>
                            <label for='image' class='admin_event_name'>Image :</label>
                            <input type='file' name='image_event' class='admin_event_input' >
                        </div>
                        <button type='submit' class='admin_event_button'>Valider</button> 

                       
                        

                        
                        
                    </form

                </div>
            </div>

        </div>

      </div>
    