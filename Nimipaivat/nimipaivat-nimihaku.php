<?php
/*
Template Name: Nimipaivat nimihaku
*/
get_header();
?>
<?php      
//Alternative image if flag icon isnt found 
$alt_img='onerror="this.onerror=null;this.src=\'../flag_not_found.jpg\';" ';
$liput_url = wp_upload_dir()['baseurl'] . '/liput/';

//Establishes the connection with our own database that holds name stats from Väestörekisteri.
include 'nimipaivat-database_connection.php';

      //Gets the values given from the forms, and if some value wasnt sent, set a default value for the variables.
      if(isset($_GET["fname"]) && !empty($_GET["fname"]))
      { 
        //Ucfirst converts first letter into uppercase
        $fname =  ucfirst($_GET["fname"]);
      }
      else{$fname= "(tyhjä)";}

      if(isset($_GET["country"]) && !empty($_GET["country"]))
      { 
        $language_code = $_GET["country"];
      }
      else{$language_code = "fi";}

      $animaldb="finnish_namedays";

      //Changing country codes into more readable descriptions
      if ($language_code!="fi") {
        if ($language_code=="cz") {$language="Tsekeissä";}
        if ($language_code=="sk") {$language="Slovakiassa";}
        if ($language_code=="pl") {$language="Puolassa";}
        if ($language_code=="fr") {$language="Ranskassa";}
        if ($language_code=="hu") {$language="Unkarissa";}
        if ($language_code=="se") {$language="Ruotsissa";}
        if ($language_code=="us") {$language="Yhdysvalloissa";}
        if ($language_code=="at") {$language="Itävallassa";}
        if ($language_code=="it") {$language="Italiassa";}
        if ($language_code=="es") {$language="Espanjassa";}
        if ($language_code=="de") {$language="Saksassa";}
        if ($language_code=="dk") {$language="Tanskassa";}
        if ($language_code=="bg") {$language="Bulgariassa";}
        if ($language_code=="lt") {$language="Liettuassa";}
        if ($language_code=="ee") {$language="Virossa";}
        if ($language_code=="lv") {$language="Latviassa";}
        if ($language_code=="gr") {$language="Kreikassa";}
        if ($language_code=="ru") {$language="Venäjällä";}
        if ($language_code=="cat") {$language="kissojen"; $animaldb="cat_namedays";}
        if ($language_code=="dog") {$language="koirien"; $animaldb="dog_namedays";}
        if ($language_code=="rodent") {$language="jyrsijöiden"; $animaldb="rodent_namedays";}
      }
      else {$language="suomenkielistä";};

      $namedays=array(null);
      ?>

<?php $thumb = get_the_post_thumbnail_url(); ?>
<div class="banner-container"
  style="background: url('<?php echo $thumb; ?>') no-repeat;background-position: center top; background-size:cover;">
  <div class="banner-content">
    <h1><?php the_title(); ?></h1>
    <p>Nimihakusivulla voit etsiä nimipäiviä nimen perusteella ja lukemaan muita kyseiseen nimeen liittyviä tietoja.</p>
  </div>
</div>

<div class="white-container">
  <div class="site-content nameday_holder">

    <div class="share-container">
      <?php echo do_shortcode('[addtoany]'); ?>
    </div>

    <div class="horizontal-ad top-ad">
      <?php the_field("ylabanneri","option"); ?>
    </div>

    <!-- Back button to front page. -->
    <a class="read-more back_button"
      href="<?php bloginfo('url'); ?>/nimipaivat/?country=<?php echo $language_code; ?>">Takaisin</a>

    <?php  
    //Animal namedays  
    //If selected nameday "country" is an animal (cat, dog or rodent), we will get the data from our own database instead of Abalin's api.
    if($language_code=="cat" || $language_code=="dog" || $language_code=="rodent" || $language_code=="fi"){

    //Shows selected animal icon top of the page where the country flag is shown when an country is selected.
    if($language_code=="cat" ){echo '<img src="https://img.icons8.com/color/48/000000/cat-head.png" alt="Eläin kuvake" class="auto_margin"'.$alt_img.'/>';}
    if($language_code=="dog" ){echo '<img src="https://img.icons8.com/color/48/000000/pug.png" alt="Eläin kuvake" class="auto_margin"'.$alt_img.'/>';}
    if($language_code=="rodent" ){echo '<img src="https://img.icons8.com/color/48/000000/cute-hamster.png" alt="Eläin kuvake" class="auto_margin"'.$alt_img.'/>';}
    if($language_code=="fi" ){
      //echo '<img src="https://www.countryflags.io/'.$language_code.'/shiny/64.png"  alt="Valtion lippu kuvake" class="auto_margin"'.$alt_img.'/>';
      echo '<img src="'.$liput_url.strtoupper($language_code).'.png"  alt="Valtion lippu kuvake" class="valtion-lippu auto_margin"'.$alt_img.'/>';
    }   

      //SQL query for wanted data from database.
      $animals = $db->query("SELECT * FROM $animaldb where name like '%$fname%';");

      echo "<p class='cent_text'>";

      //Counter for if right name wasnt found and to give the div that holds the countdown timer the same number that the namedays array will use.
      $number=0;
      
        while($row = $animals->fetch(PDO::FETCH_ASSOC)) {  
          //The wanted data
            $name = $row['name'];   
            $nameday = $row['nameday'];    

            //Dividing full date from DB into year month and day variales
            list($result_year,$result_month,$result_day)=explode("-", $nameday); 

            //Splits name strings as the data is written in a way where each nameday has its names grouped into one string. For example cat nameday 26.01 has the names "Jonttu, Jope" as one string. Explode splits names into a array with multiple name variables.
            $names = explode(", ", $name);

            //Loop throught the names
            foreach ($names as $one_name){
              //We only want data for the exact name we were searching, not all names for that day.
              if($one_name==$fname){

              //Gives the right value for year based on if the day is in the past this year.
              $comparison_date=$result_month.$result_day;                 
              $now= date('md');
              if($comparison_date < $now) {
                $result_year=date('Y')+1;
              }
              else {
                $result_year=date('Y');
              }        
             
              //Result text for the user, for example "Tupsu viettää kissojen nimipäiväänsä 17.12."
              echo '<a href="?fname='.$one_name.'&country='.$language_code.'">'.$one_name."</a> ";
              echo "viettää ".$language." nimipäiväänsä <a href='/info/nimipaivat/?dateselect=".date('Y')."-".$result_month."-".$result_day."&country=".$language_code."'>".$result_day.".".$result_month.".</a>"; 
              
              //Second result text row, for example "Seuraavan kerran Tupsu viettää kissojen nimipäiväänsä 17.12.2020."
              echo "<p class='cent_text'>";
              echo "Seuraavan kerran "."<a href='?fname=".$fname."&country=".$language_code."'>".$fname."</a> viettää ".$language." nimipäiväänsä <a href='/info/nimipaivat/?dateselect=".date('Y')."-".$result_month."-".$result_day."&country=".$language_code."'>".$result_day.".".$result_month.".".$result_year."</a>.</p>";
              
              //Third result text row, for example "Aikaa seuraavaan kissojen nimipäivään: 1pv 1t 1min 1s".
              echo "<p class='cent_text'>Aikaa seuraavaan ".$language." nimipäivään: ";              
              $number++; //Counter for if there are multiple namedays for the name, probably unnessecary for Finnish animals, but needed for German names. Animal namedays and Abalin namedays are sent to countdown timer with the same array.
              $row_to_add= $result_year."/".$result_month."/".$result_day." 00:00 AM"; //The row of text that will be added to the array.
              array_push($namedays, $row_to_add); //Add new row to the end of the array.
              echo '<span id="countdown'.$number.'"></span>'; //Element that holds the countdown text.

              echo "</p>";              
              }
            }  
        }
        //Wanted name wasnt found
        if($number<= 0){
          echo ucfirst($language)." nimipäivää ei löytynyt nimellä ".$fname;
        }

       echo "</p>";  


       
//Swedish finnish namedays from our database
        //Only needed when searching for finnish namedays
        if($language_code=="fi"){          
          echo '<br><div class="cent_text">';
          //Sql query
          $swedish_finnish = $db->query("SELECT * FROM swedish_finnish_namedays where name like '%$fname%';");
          
          echo "<p class='cent_text'>";

      //Counter for if right name wasnt found and to give the div that holds the countdown timer the same number that the namedays array will use.
      $number=0;
      
        while($row = $swedish_finnish->fetch(PDO::FETCH_ASSOC)) {  
          //The wanted data
            $name = $row['name'];   
            $nameday = $row['nameday'];    

            

            //Splits name strings as the data is written in a way where each nameday has its names grouped into one string. For example cat nameday 26.01 has the names "Jonttu, Jope" as one string. Explode splits names into a array with multiple name variables.
            $names = explode(", ", $name);

            //Loop throught the names
            foreach ($names as $one_name){
              
              //We only want data for the exact name we were searching, not all names for that day.
              if($one_name==$fname){
                
                //Dividing full date from DB into year month and day variales
            list($year2,$month2,$day2)=explode("-", $nameday); 

              //Gives the right value for year based on if the day is in the past this year.
              $comparison_date=$month2.$day2;                 
              $now= date('md');
              if($comparison_date < $now) {
                $year2=date('Y')+1;
              }
              else {
                $year2=date('Y');
              }        
             
              //Result text for the user, for example "Tupsu viettää kissojen nimipäiväänsä 17.12."
              echo '<a href="?fname='.$one_name.'&country='.$language_code.'">'.$one_name."</a> ";
              echo "viettää ruotsinkielistä nimipäiväänsä <a href='/info/nimipaivat/?dateselect=".date('Y')."-".$month2."-".$day2."&country=".$language_code."'>".$day2.".".$month2.".</a>"; 
              
              //Second result text row, for example "Seuraavan kerran Tupsu viettää kissojen nimipäiväänsä 17.12.2020."
              echo "<p class='cent_text'>";
              echo "Seuraavan kerran "."<a href='?fname=".$fname."&country=".$language_code."'>".$fname."</a> viettää ruotsinkielistä nimipäiväänsä <a href='/info/nimipaivat/?dateselect=".date('Y')."-".$month2."-".$day2."&country=".$language_code."'>".$day2.".".$month2.".".$year2."</a>.</p>";
              
              //Third result text row, for example "Aikaa seuraavaan kissojen nimipäivään: 1pv 1t 1min 1s".
              echo "<p class='cent_text'>Aikaa seuraavaan ruotsinkieliseen nimipäivään: ";              
              $number++; //Counter for if there are multiple namedays for the name, probably unnessecary for Finnish animals, but needed for German names. Animal namedays and Abalin namedays are sent to countdown timer with the same array.
              echo '<span id="countdown_swe"></span>'; //Element that holds the countdown text.

              echo "</p>";              
              }
            }  
        }
        //Wanted name wasnt found
        if($number<= 0){
          echo "Ruotsinkielistä nimipäivää ei löytynyt nimellä ".$fname;
        }

       echo "</p>"; 
          echo "</div>";
        }  
        
        
    } //Animals end


    //Abalin nameday api starts
    else{
    //Flag icon for selected country 
      echo '<div class="cent_text">';
      //echo '<img src="https://www.countryflags.io/'.$language_code.'/shiny/64.png" alt="Valtion lippu kuvake" class="auto_margin"'.$alt_img.'/>';
      echo '<img src="'.$liput_url.strtoupper($language_code).'.png" alt="Valtion lippu kuvake" class="valtion-lippu auto_margin"'.$alt_img.'/>';
      echo '</div>';

      echo "<h4 class='cent_text'>Nimipäivähaku nimellä " .$fname."</h4>";

      //Initializes Curl, which is used to retrieve data from Abalin nameday api.
      $curl = curl_init();    

      //Establishes connection with the Abalin api where nameday data is retrieved from. 
      curl_setopt_array($curl, array(
        //CURLOPT_URL => "https://api.abalin.net/getdate?name=".urlencode($fname)."&country=".$language_code,
        CURLOPT_URL => "https://nameday.abalin.net/getdate?name=".urlencode($fname)."&country=".$language_code,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        //CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_CUSTOMREQUEST => "POST",
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);
      

      if ($err) {
        if ($debug) echo "cURL Error #:" . $err;
        else echo $err;
      } else {
        //Data succesfully received from api.

        $data = json_decode($response, true);         

        //Counter for if right name wasnt found and to give the div that holds the countdown timer the same number that the namedays array will use.
        $number=0;

        //Used to know when when the need is to inform user name wasnt found 
        $name_found=false;
        
        echo "<div class='cent_text'>";

        if ($data['status'] == 'Success' && $data['data']['resultCount'] > 0 && $data['data']['namedays']) {

          foreach ( $data['data']['namedays'] as $row ) {
            //Each day has a string that has multiple names in it, this splits the names into a array
            $names=preg_split('/\s*,\s*/', $row['name']);

            //Loop through the names
            foreach ($names as $one_name){
              //We only want results for the name we searched for.
              if($one_name==$fname){  
                
                //Creating date from Aballing api data and simple custom code for year.      
                $result_day=$row['day'];
                $result_month=$row['month'];
                if($result_day <= 9){
                  $result_day = "0".$result_day;
                }
                if($result_month <= 9){
                  $result_month = "0".$result_month;
                }
                $result_date=$result_month.$result_day; 
                $now = date ('md');

                //Aballin's nameday api doesnt give years for namedays, only day and month. This IF determines what year the nameday will be next held.
                if($result_date < $now) {
                  $result_year= date('Y')+1;
                }
                else {
                  $result_year=date('Y');
                }            
                
                //Result text for the user, for example "Raakel viettää suomenkielistä nimipäiväänsä 17.12."           
                echo "<p>";
                echo    "<a href='?fname=".$one_name."&country=".$language_code."'>".$one_name."</a> viettää ".$language." nimipäiväänsä 
                <a href='/info/nimipaivat/?dateselect=".date('Y')."-".$result_month."-".$result_day."&country=".$language_code."'>".$result_day.".".$result_month.".</a>";
                echo "</p>";

                //Second text row, for example "Seuraavan kerran Raakel viettää nimipäiväänsä 17.12.2020"
                echo "<p>";
                echo    "Seuraavan kerran "."<a href='?fname=".$one_name."&country=".$language_code."'>".$one_name."</a> viettää nimipäiväänsä 
                <a href='/info/nimipaivat/?dateselect=".date('Y')."-".$result_month."-".$result_day."&country=".$language_code."'>".$result_day.".".$result_month.".".$result_year.".</a>";
                echo "</p>";

                //Third text row, for example "Aikaa seuraavaan nimipäivään: 1pv 1t 1min 1s".
                echo    "<p>Aikaa seuraavaan nimipäivään: ";                
                $number++; //Counter for if there are multiple namedays in a year for the name, needed for German names. 
                $row_to_add= $result_year."/".$result_month."/".$result_day." 00:00 AM"; //The row of text that will be added to the array.
                array_push($namedays, $row_to_add); //Add new date to namedays array.
                echo    '<span id="countdown'.$number.'"></span>';

                echo "</p>";            
              } 
            }
          }
          
        }
        //Name not found
        if($number<= 0) {
          echo  "<p>".ucfirst($language)." nimipäivää ei löytynyt nimellä ".$fname.".</p>";
        echo '<span id="countdown1" hidden></span>';           
        }


        
        echo "</div>";  
        //$rows = $data["results"];

        //
        ////Counter for if right name wasnt found and to give the div that holds the countdown timer the same number that the namedays array will use.
        //$number=0;

        ////Used to know when when the need is to inform user name wasnt found 
        //$name_found=false;
        //echo "<div class='cent_text'";
        //If seach string is less than 3 letters abalin api sends out error json, this check if it has happened.
        //if($rows){   
        ////If user searches with partial name or even a full one, ablin's api may give multiple results. For example anna gives results also for hanna, susanna etc. Loop goes through all of the name rows.  
        //foreach ($rows as $row) { 
        //  //Each day has a string that has multiple names in it, this splits the names into a array          
        //  $names=preg_split('/\s*,\s*/', $row['name']);

        //  //Loop through the names
        //  foreach ($names as $one_name){
        //    //We only want results for the name we searched for.
        //    if($one_name==$fname){  
        //      
        //      //Creating date from Aballing api data and simple custom code for year.      
        //      $result_day=$row['day'];
        //      $result_month=$row['month'];
        //      if($result_day <= 9){
        //        $result_day = "0".$result_day;
        //      }
        //      if($result_month <= 9){
        //        $result_month = "0".$result_month;
        //      }
        //      $result_date=$result_month.$result_day; 
        //      $now = date ('md');

        //      //Aballin's nameday api doesnt give years for namedays, only day and month. This IF determines what year the nameday will be next held.
        //      if($result_date < $now) {
        //        $result_year= date('Y')+1;
        //      }
        //      else {
        //        $result_year=date('Y');
        //      }            
        //      
        //      //Result text for the user, for example "Raakel viettää suomenkielistä nimipäiväänsä 17.12."           
        //      echo "<p>";
        //      echo    "<a href='?fname=".$one_name."&country=".$language_code."'>".$one_name."</a> viettää ".$language." nimipäiväänsä 
        //      <a href='/info/nimipaivat/?dateselect=".date('Y')."-".$result_month."-".$result_day."&country=".$language_code."'>".$result_day.".".$result_month.".</a>";
        //      echo "</p>";

        //      //Second text row, for example "Seuraavan kerran Raakel viettää nimipäiväänsä 17.12.2020"
        //      echo "<p>";
        //      echo    "Seuraavan kerran "."<a href='?fname=".$one_name."&country=".$language_code."'>".$one_name."</a> viettää nimipäiväänsä 
        //      <a href='/info/nimipaivat/?dateselect=".date('Y')."-".$result_month."-".$result_day."&country=".$language_code."'>".$result_day.".".$result_month.".".$result_year.".</a>";
        //      echo "</p>";

        //      //Third text row, for example "Aikaa seuraavaan nimipäivään: 1pv 1t 1min 1s".
        //      echo    "<p>Aikaa seuraavaan nimipäivään: ";                
        //      $number++; //Counter for if there are multiple namedays in a year for the name, needed for German names. 
        //      $row_to_add= $result_year."/".$result_month."/".$result_day." 00:00 AM"; //The row of text that will be added to the array.
        //      array_push($namedays, $row_to_add); //Add new date to namedays array.
        //      echo    '<span id="countdown'.$number.'"></span>';

        //      echo "</p>";            
        //    } 
        //  }
        //}  
                    
        ////Name not found
        //if($number<= 0) {
        //  echo  "<p>".ucfirst($language)." nimipäivää ei löytynyt nimellä ".$fname.".</p>";
        // echo '<span id="countdown1" hidden></span>';           
        //}
        
        //
        //echo "</div>";  
      } 

     }
?>

    <?php
    //Searchbar for searching namedays with name
    include 'nimipaivat-search_with_name_form.php';

    //Send data for javascript countdown timer
   do_action('countdown', $year2, $month2, $day2, $namedays);


//If finnish. Väestörekisteri only holds name stats from Finland, other countries not yet implemented.
if($language_code=="fi"){

  echo "<br>
  <h4>Väestötietojärjestelmän nimitietoja nimellä ".$fname."</h4>"; 
  
  //Queries for database:
  //Firstname stats for wanted name.
  $sql = $db->query("SELECT * FROM firstnames where name like '$fname';");  
  //Secondaryname stats for wanted name.
  $sql2 = $db->query("SELECT * FROM secondarynames where name like '$fname';"); 
   //For empty tests 
  $sqlempty1 = $db->query("SELECT * FROM firstnames where name like '$fname';");
  $sqlempty2 = $db->query("SELECT * FROM secondarynames where name like '$fname';"); 
  
  if(empty($sqlempty1->fetch(PDO::FETCH_ASSOC)) and empty($sqlempty2->fetch(PDO::FETCH_ASSOC)) ){
    echo "<p>Väestötietojärjestelmästä ei löytynyt tietoja nimellä ".$fname.".</p>";
  }
  else{
  //Shows how many men and women in Finland have the wanted name as their firstname.
  echo "<ul>";
  while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
      $name = $row['name'];
      $amount = $row['amount'];    
      $gender = $row['sex']; 
      if($gender == "Woman"){
        $sex = " naisia ";
        }
        else if($gender == "Man"){
          $sex = " miehiä "; 
        }
        else $sex = " ihmisiä ";
     echo "<li><a href='?fname=".$name."&country=fi'>".$name."</a> etunimisiä ".$sex." on <b class='lightly_bold'>".number_format($amount, 0, '.', ' ')."</b> kappaletta.</li>";   
     $total += $amount;
     $i++;
  }

  if($i > 1){
  echo "<li>Yhteensä <a href='?fname=".$name."&country=fi'>".$name."</a> etunimi on käytössä <b class='lightly_bold'>".number_format($total, 0, '.', ' ')."</b> henkilöllä.</li>"; 
  }

  echo "<br>";
     //Shows the firstname ranking of the wanted name.
  $sql3 = $db->query("SELECT  sex, name, 1+(SELECT count(*) from firstnames a WHERE a.amount > b.amount AND sex like '$gender') as RNK, amount FROM firstnames b WHERE Name = '$fname';");
     while($row = $sql3->fetch(PDO::FETCH_ASSOC)) {    
      $name = $row['name'];
      $amount = $row['amount'];    
      $rank= $row['RNK']; 
      $gender = $row['sex']; 
      if($gender == "Woman"){
        $sex = " naisten ";
        }
        else if($gender == "Man"){
          $sex = " miesten "; 
        }
        else $sex = " ihmisten ";
     echo "<li><a href='?fname=".$name."&country=fi'>".$name."</a> nimen ranking ".$sex." etunimenä on <b class='lightly_bold'>".number_format($rank, 0, '.', ' ')."</b>.</li>";     
  }
  echo "<br>";

  //Shows how many men and women in Finland have the wanted name as their secondaryname.
  while($row = $sql2->fetch(PDO::FETCH_ASSOC)) {
      $name = $row['name'];
      $amount = $row['amount'];    
      $gender = $row['sex']; 
      if($gender == "Woman"){
        $sex = " naisella";
        }
        else if($gender == "Man"){
          $sex = " miehellä"; 
        }
        else $sex = " ihmisellä";
        echo "<li><a href='?fname=".$name."&country=fi'>".$name."</a> on muuna nimenä <b class='lightly_bold'>".number_format($amount, 0, '.', ' ')."</b>:lla ".$sex.".</li>";  
        $total2 += $amount;
     $i2++;      
  }

  if($i2 > 1){
    echo "<li>Yhteensä <a href='?fname=".$name."&country=fi'>".$name."</a> on muuna nimenä käytössä <b class='lightly_bold'>".number_format($total2, 0, '.', ' ')."</b> henkilöllä.</li>"; 
    }
echo "<br>";
  

  //Shows the secondaryname ranking of the wanted name.
   $sql4 = $db->query("SELECT  sex, name, 1+(SELECT count(*) from secondarynames a WHERE a.amount > b.amount AND sex like '$gender') as RNK, amount FROM secondarynames b WHERE Name = '$fname';");
  while($row = $sql4->fetch(PDO::FETCH_ASSOC)) {
    $name = $row['name'];
    $amount = $row['amount'];    
    $rank= $row['RNK']; 
    $gender = $row['sex']; 
    if($gender == "Woman"){
      $sex = " naisten ";
      }
      else if($gender == "Man"){
        $sex = " miesten "; 
      }
      else $sex = " ihmisten ";
   echo "<li><a href='?fname=".$name."&country=fi'>".$name."</a> nimen ranking ".$sex." muuna nimenä on <b class='lightly_bold'>".number_format($rank, 0, '.', ' ')."</b>.</li>";      
}

  
  }
  echo "</ul><a href='/info/nimitilastot'><p>Näytä kaikki suomalaiset nimet</p></a>"; 
}//If finnish ends


//Wikipedia api starts
echo "<br>
<h4>Wikipedia tietoja nimellä ".$fname.":</h4>";
  
//Opens local json file that holds wikipedia articles about names. Wikipedia api only supports getting a list of 500 articles, which wasnt enought for this. Json data was made manually with petscan: https://petscan.wmflabs.org/?max_age=&cb_labels_yes_l=1&edits%5Bflagged%5D=both&cb_labels_any_l=1&ns%5B0%5D=1&language=fi&project=wikipedia&cb_labels_no_l=1&edits%5Bbots%5D=both&interface_language=fi&categories=etunimet&edits%5Banons%5D=both&search_max_results=500&doit=
  ob_start();//Used as an alternative for file_get_contents, which isnt working for some reason on this server.
  include "nimipaivat-etunimet.json";
  $contents = ob_get_clean();
  $json = $contents;          
  $json_decoded = json_decode($json, true);

//Key is the array number with the wanted name. Key is later used to get the right page id.
$key = array_search($fname, array_column($json_decoded['*'][0]['a']['*'], 'title') );

//Trying to find the name article  again. Needed only because some name articles have (nimi) in the article title
if ($key == false) {
  $key  = array_search($fname."_(nimi)" , array_column($json_decoded['*'][0]['a']['*'], 'title') );;   
  }
  // Trying to find the name article again. Needed only because some name articles have (etunimi) in the article title
  if ($key == false) {
    $key  = array_search($fname."_(etunimi)" , array_column($json_decoded['*'][0]['a']['*'], 'title') );;   
    }
    //Article found
  if($key !== false) {
    
          //Gets the Wikipedia page id that will be used to open the correct wikipedia page.
          $pageid = $json_decoded['*'][0]['a']['*'][$key]['id'];

$url = "https://fi.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro&explaintext&redirects=1&pageids=".$pageid;

$ch = curl_init( $url );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
$output = curl_exec( $ch );
curl_close( $ch );

if ($err) {
  if ($debug) echo "cURL Error #:" . $err;
  else echo $err;
} else {
//Wikipedia json received
$result = json_decode( $output, true );

  //Writes the wikipedia name article summary for the user to read.
    echo $result["query"]["pages"][$pageid]["extract"];
    }
    
  }
else{
  echo $fname." nimellä ei löytynyt suomenkielistä Wikipedia sivua.";
}
    // Wikipedia link for when no data found
    if($key == false){
    echo "<a href='https://fi.wikipedia.org/'>
      <p class='top_marg'>Avaa Wikipedia</p>
    </a>";
    }
    //Wikipedia link for when data was found
    else{
    echo "<a href='https://fi.wikipedia.org/?curid=".$pageid."'>
      <p class='top_marg'>Lue lisää Wikipediassa</p>
    </a>";
    }
    //Wikipedia api ends    
    ?>
    <br><br>
    <div class="horizontal-ad">
      <?php the_field("alabanneri","option"); ?>
    </div>
  </div>
</div>


<?php



get_footer();