<?php

require 'bdd.php';
session_start();
$best_products_query = $bdd->query(
    "SELECT produit.nom AS product_name , produit.prix AS product_price , produit.image AS pic_liked_products , produit.id_produit
    FROM aime_produit , produit where produit.id_produit = aime_produit.id_produit
    GROUP BY aime_produit.id_produit
    order by count(aime_produit.id_client) DESC LIMIT 3;"
);
$best_products = $best_products_query->fetchAll();





$event_by_date_arr = [];
$event_by_date_query = $bdd->query(
  "SELECT id_event, date_event ,nom_event,photo_event,pays_event,ville_event FROM evenements ORDER BY date_event;" // fetch tout les id et les date
);

$event_by_date = $event_by_date_query->fetchAll();
foreach($event_by_date as $event){
  if(!(isset( $event_by_date_arr[$event[1]]))){ // verifie si l'arrat a l'indice[date de l'evenement] exsiste
    $event_by_date_arr[$event[1]] = array(); // si ce n'est pas le cas il crée un liste avec comme clé la date d'event 

  }
  $event_by_date_data = [];
  $data = [];
  array_push($data , $event[2],$event[3],$event[4],$event[5]);
  $event_by_date_data[$event[0]]  = $data;
  $event_by_date_arr[$event[1]][] = $event_by_date_data[$event[0]];// attitre l'evenement au bonne indice qui est la date de l'event.
}
print_r($event_by_date_arr["2023-05-12"]);



$shop_product = $bdd->query("SELECT * FROM produit");

$shop_product = $shop_product->fetchAll();

$nb_produits = count($shop_product);
$half = ceil($nb_produits / 2);

$produit_first_half  = array_slice($shop_product , 0,$half);
$produit_second_half = array_slice($shop_product,$half);



if(isset($_POST['contact_pseudo']) && isset($_POST['contact_email']) && isset($_POST['contact_message'])){
  echo "<script>alert('Message sent , our team will send you an email shortly')</script>";
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
        <h3 class='product_page_text'>HOME</h3>
      </div>
    </div>
    <div class="scrollable_bar">
      <div class="scrollable_text_logo_container">
        <div class="scrollable_logo_container">
          <i class="fa fa-truck" aria-hidden="true"></i>
        </div>
        <div class="scrollable_text_container">
          <h3 class="scrollable_text">Fast Delivery</h3>
        </div>
      </div>

      <div class="scrollable_text_logo_container">
        <div class="scrollable_logo_container">
          <i class="fa fa-lock" aria-hidden="true"></i>
        </div>
        <div class="scrollable_text_container">
          <!--text-->
          <h3 class="scrollable_text">100% Secure</h3>
        </div>
      </div>

      <div class="scrollable_text_logo_container">
        <div class="scrollable_logo_container">
          <i class="fa fa-trophy" aria-hidden="true"></i>
        </div>
        <div class="scrollable_text_container">
          <h3 class="scrollable_text">Best in EU</h3>
        </div>
      </div>

      <div class="scrollable_text_logo_container">
        <div class="scrollable_logo_container">
          <i class="fa fa-btc" aria-hidden="true"></i>
        </div>
        <div class="scrollable_text_container">
          
          <h3 class="scrollable_text">0.01 Btc Gift !</h3>
        </div>
      </div>
    </div>
    <div class="home_page">
      x<div class="events_container">
        <div class="title_container">
          <h2 class='event_title'>EVENEMENT</h2>
        </div>
       
        <div class='scrollable_event'>
             
            <?php
             foreach($event_by_date_arr as $date => $event){
              
              $ev = 'assets/images/Photo_events/Ahmed_spins.png';                    // [0][0] = nom , [0][1] = lien ect
              echo "
                <div class='date_event_container'>
                    <h1>".$date."</h1>
                </div>
                ";
                foreach($event as $data){
                  
                  echo "
                  <div class='event'>
                    <img src=".$data[1]." class='event_img'/>
                    <div class='text_event_container'>
                      <div class='upper_text_event_container'>
                          <h3 class='event_text_one'>".$data[2]."</h3>
                          <h3 class='event_text_one'>".$data[3]."</h3>
                      </div>
                      <div class='lower_text_event_container'>
                        <h3 class='event_text_two '>".$data[0]."</h3>
                      </div>
                    </div>
                  </div>";
                }
              
             }

            
            ?>
            
        </div>
       



        <!--Evenement-->
      </div>
      <div class="product_container">
        <div class="title_best_product_container">
          <h2 class="title_best_product">Most Liked Products</h2>
        </div>
        <div class="scroll_product scroll_product_fixed_height">
            <div class="product_data_container">
                <?php 
                foreach($best_products as $best_product){
                  
                    echo "<div class='product_card'>
                            
                                <a href='details_produit.php?id=".$best_product[3]."'>
                                <img src=".$best_product[2]." class='product_img'/>
                                </a>
                                
                                
                                
                            <div class='scroll_product_lower_part'>
                                <div class='scroll_product_lower_part_right'>
                                    <h2 class='product_lower_part_name'>".$best_product[0]."</h2>
                                </div>

                                <div class='scroll_product_lower_part_left'>
                                    <h2 class='product_lower_part_name'>".$best_product[1]." BTC"."</h2>
                                </div>
                             </div>
                    
                    
                          </div>
                       " ;
                }
                ?>
              

            </div>
          
        </div>
      </div>
    </div>

    <div class='shop_page'>
          <div class='shop_page_title_container'>
            <div class='ligne_title_left'></div>
            <div class='shop_page_title_text_container'>
              <h3 class='shop_page_title_text'>SHOP</h3>
            </div>
            <div class='ligne_title_right'></div>
          </div>

          <div class='shop_page_products_container'>
              <div class='shop_page_first_row'>
                <?php  
                    foreach($produit_first_half as $product_f){ // 1 name , 2 price , 4 path
                      echo "<div class='shop_card'>
                                 <a href='details_produit.php?id=".$product_f[0]."' class='shop_card_link'>

                                  <img src=".$product_f[4]." class='product_shop_img'/>
                                  
                                  
                                  
                                  <div class='shop_product_lower_part'>

                                      <div class='shop_product_lower_part_container'>
                                          <h6 class='shop_product_lower_part_content'>".$product_f[1]."</h6>
                                      </div>

                                      <div class='shop_product_lower_part_container'>
                                            <h6 class='shop_product_lower_part_content'>".$product_f[2]." BTC"."</h6>
                                      </div>
                                  </div>
                                  
                              </div>"; 
                              // print_r($produit_first_half[1]); affiche le premier produit en forme d'array.

                    }
                    ?>
              </div>

              <div class='shop_page_first_row'>
                  <?php  
                        foreach($produit_second_half as $product_f){ // 1 name , 2 price , 4 path
                          echo "<div class='shop_card'>
                                    <a href='details_produit.php?id=".$product_f[0]."' class='shop_card_link'>
                                      <img src=".$product_f[4]." class='product_shop_img'/>
                                      </a>
                                      
                                      
                                      <div class='shop_product_lower_part'>     

                                          <div class='shop_product_lower_part_container'>
                                              <h6 class='shop_product_lower_part_content'>".$product_f[1]."</h6>
                                          </div>

                                          <div class='shop_product_lower_part_container'>
                                                <h6 class='shop_product_lower_part_content'>".$product_f[2]." BTC"."</h6>
                                          </div>
                                      </div>
                                      
                                  </div>"; 
                                  // print_r($produit_first_half[1]); affiche le premier produit en forme d'array.

                        }
                    ?>
              </div>
              
            
          </div>

          

          
    </div>

    
    <div class='about_page'>
         <div class='shop_page_title_container'>
            <div class='ligne_title_left'></div>
            <div class='shop_page_title_text_container'>
              <h3 class='shop_page_title_text'>ABOUT</h3>
            </div>
            <div class='ligne_title_right'></div>
         </div>

         <div class='about_hook_logo_page'>
          <div class='about_logo_container'>
                <img src="assets\images\DA\athenalogo2.png" class='about_logo'/>
          </div>
          <div class='about_hook_container'>
            <h2 class='about_book_text'>“Athena, Godly energy at your fingertips."</h2>
          </div>
         </div>

         <div class='about_paragraph_container'>
          <h3 class='about_paragraph_text'>
            Welcome to Athena, <br><br>
            where we redefine the art of celebration.Inspired by the vibrant<br>
             energy of festivals and parties,our Athena pills are designed for<br>
             those who seek to enhance their experiences without resorting to drugs.<br>
             These are not just pills,but a gateway to amplified energy, creativity,<br>
             and enjoyment.Perfect for festival-goers and party enthusiasts,Athena <br>
             provides a natural boost,ensuring you can revel in the moment,more<br>
            vividly and joyously. Embrace Athena,and discover a new way to<br>
            elevate your festive spirit safely and spiritedly.<br>
            <br><br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Athena - the embodiment of power, wisdom, and vitality
                      </h3>


         </div>
          
    </div>

    <div class='safety_page'>

         <div class='shop_page_title_container'>
            <div class='ligne_title_left'></div>
            <div class='shop_page_title_text_container'>
              <h3 class='shop_page_title_text'>SAFETY</h3>
            </div>
            <div class='ligne_title_right'></div>
         </div>
          <div class='safety_page_content'>

            <div class='safety_page_title_container'>
              <h3 class='safety_page_title_text'>Athena Product Safety Guideline</h3>
            </div>
            <div class='safety_page_paragraph_container'>
              <div class='safety_paragraph_container'>
                  
                <h3 class='safety_paragraph_text'>
                When taking Athena product, it is recommended not to
                exceed a maximum dose of 3.5 mg per kg
                bodyweight for men and 3.3 mg  per kg
                bodyweight for women. In general, a dose of 120
                mg should not be exceeded. After ingestion, 
                be sure to hydrate sufficiently. 
                <h3>
              </div>
              <div class='safety_logo_container'>
                <img src='assets\images\DA\AthenaMol.png' class='safety_logo'>
              </div>
                
            </div>
          

          </div>
        
         

    </div>
    <div class='contact_page'>
      <div class='contact_page_container'>
        <div class='shop_page_title_container'>
            <div class='contact_ligne_title_left'></div>
            <div class='shop_page_title_text_container'>
              <h3 class='contact_page_title_text'>CONTACT</h3>
            </div>
            <div class='contact_ligne_title_right'></div>
         </div>

         <div class='contact_page_content'>
          <div class='contact_form_card'>
            <form method='POST' class='contact_form'>

            <div class='form_container'>
              <label for='pseudo' class='contact_form_label'>Pseudo : </label>
              <input type='text' name='contact_pseudo' class='contact_form_pseudo'>
            </div>

            <div class='form_container'>
              
              <label for='email' class='contact_form_label'>Email : </label>
              <input type='email' name='contact_email' class='contact_form_pseudo'>

            </div>

              <div class='form_container'>
                <label for='message' class='contact_form_label'>Message : </label>
                <textarea name="contact_message" class='contact_form_message' rows="4" cols="50" placeholder="Écrivez votre message ici...">
                </textarea>
               
                
              
              </div>
              <button type='submit' class='contact_submit'>Submit</button>
            

              
            </form>


          </div>

          <div class='contact_logo_number_container'>
            <div class='contact_number_container'>
              <i class="fa-solid fa-phone"></i>
              <h3>066768890</h3>
            </div>
            <div class='contact_logo_container'>
              <i class="fa-brands fa-square-instagram"></i>
              <i class="fa-brands fa-square-facebook"></i>
            </div>

          </div>

         </div>
   

      </div>
    </div>


  </body>
  <script src="scripts.js"></script>  
  <script>
    function submitForm(formId) {
        document.getElementById(formId).submit();
    }
</script>
</html>
