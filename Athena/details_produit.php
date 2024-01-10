
<?php
require 'bdd.php';
session_start();



if(!isset($_GET['id'])){
    header("Location:index.php");
    exit();
}
$id_produit = $_GET['id'];
$product_query = $bdd->prepare("
    SELECT p.id_produit, p.prix ,p.nom, p.image, p.description, COUNT(ap.id_client) AS nombre_likes
    FROM produit p
    LEFT JOIN aime_produit ap ON p.id_produit = ap.id_produit
    WHERE p.id_produit = ?
    GROUP BY p.id_produit, p.nom, p.image, p.description
");
$product_query->execute([$id_produit]);
$products_data = $product_query->fetchAll();


$product_data = $products_data[0];

$product_name = $product_data['nom'];
$product_img = $product_data['image'];
$product_desc = $product_data['description'];
$product_likes = $product_data['nombre_likes'];
$product_price = $product_data['prix'];


$id_client = $_SESSION['id_client'];
$wallet_client = $_SESSION['wallet'];

if(isset($_POST['detail_quantite'])){
  
  date_default_timezone_set('Europe/Paris');
  $date = date("Y-m-d H:i:s");
  
  $quantite = $_POST['detail_quantite'];
  $total_price = $product_price * $quantite;
  if($wallet_client - $total_price >= 0){

    $wallet_client = $wallet_client - $total_price;
    $buy_query = $bdd->prepare("insert into achete(id_client , id_produit , quantite_acheter,date_transaction) values
    (?,?,?,?);
    
    ");

    $wallet_query = $bdd->prepare("update client set wallet = ? where id_client = ?");
    $wallet_query->execute([$wallet_client , $id_client]);

    $buy_query->execute([$id_client , $id_produit , $quantite,$date]);
    echo "<script>alert('Achat fait ! ');</script>";


    


  }else{
    echo "<script>alert('Pas Assez dargent ! ');</script>";

  }



}





$like_state = 0;
if(isset($_GET['like'])){
    
    $like_state = 1;
    $like_query = $bdd->prepare("insert into aime_produit (id_client , id_produit) values(?,?)");
    $like_query->execute([$id_client,$id_produit]);
    

  }






require 'head.php';
?>

  <body>
    <div class="header">
      <div class="upper_container">
        <div class="logo_container">
          <img class="logo" src="assets/images/DA/athenalogo2.png" />
          <h1 class="text_logo">ATHENA</h1>
        </div>
        <?php 
        if(isset($_SESSION['username'])){
          
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
      <div class='lower_container_product'>
        <h3 class='product_page_text'>Product</h3>
      </div>
    <div>
    
    <div class='product_page_content'>
        <div class='product_page_data_container'>

            <div class='product_page_data_left_side'>
                <div class='product_page_image_container'>
                  <img src=<?=$product_img?> class='product_page_image'/>
                </div>
            </div>

            <div class='product_page_data_right_side'>
                
                    <div class='product_page_data_title_container'>
                        <div class='product_page_data_line'></div>
                        <div class='product_page_data_title_wrapper'>
                            
                            <h3 class='product_page_data_title'><?=$product_name?></h3>
                        </div>
                        
                        <div class='product_page_data_line'></div>
                    </div>

                    <div class='product_page_data_card'>

                      <div class='product_page_data_card_upper'>
                            <h3 class='product_page_data_card_upper_data_text'><?=$product_price?></h3>
                            <h4>BTC</h4>
                      </div>
                    </div>

                    <div class='product_page_data_card_lower'>
                      <div class='product_page_data_card_lower_content'>
                            <h5 class='product_page_data_card_upper_data_text'><?=$product_desc?></h5>
                      </div>
                    </div>  

                    <div class='product_page_data_card_button'>
                        <form method='POST'>
                        
                        <input name='detail_quantite' class='product_page_qt'>
                        <button type="submit" class="btn_buy btn-secondary">Acheter</button>
                        </form>
                        <div class='product_page_data_card_likes'>
                          <h4><?=$product_likes?></h4>
                          <div class='likes_container'>
                          <a href='details_produit.php?id=<?= $id_produit ?>&like=1'>
                            <?php
                                if($like_state == 1){
                                  echo '<i class="fa-solid fa-heart"></i>';

                                }
                                else{
                                  echo '<i class="fa-regular fa-heart"></i>';
                                }
                               
                            
                            ?>
                         </a>
                           
                            </a>
                          </div>
                          
                        </div>
                    </div>

            </div>



    </div>
    
</body>
</html>

