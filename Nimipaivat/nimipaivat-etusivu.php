<?php
/*
Template Name: Nimipaivat etusivu
*/
get_header();
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


<?php $thumb = get_the_post_thumbnail_url(); ?>
<div class="banner-container"
  style="background: url('<?php echo $thumb; ?>') no-repeat;background-position: center top; background-size:cover;">
  <div class="banner-content">
    <h1><?php the_title(); ?></h1>
    <p>Nimipäivien vietolla on pitkä historia Suomessa. Nimipäiväsivuilla pystyt tutkimaan nimipäiviä niin
      Suomessa kuin myös monessa muussakin maassa.</p>
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

    <div class="cent_text">
      <?php
//Alternative image if flag icon isnt found 
$alt_img='onerror="this.onerror=null;this.src=\'flag_not_found.jpg\';" ';
$liput_url = wp_upload_dir()['baseurl'] . '/liput/';

//Establishes the connection with our own database that holds name stats from Väestörekisteri and swedish finnish namedays.
include 'nimipaivat-database_connection.php';


      //Gets the values given from the forms, and if some value wasnt sent, set a default value for the variables.
      if(isset($_GET["country"]) && !empty($_GET["country"]))
      { 
        $language_code = $_GET["country"];
      }
      else{$language_code = "fi";}

      if(isset($_GET["dateselect"]) && !empty($_GET["dateselect"]))
      { 
        $selected_date = $_GET["dateselect"];
      }
      //date function sets the value as current day in a format like "2020-11-28"
      else{$selected_date = date('Y-m-d');}
  
      //Divides selected_date into 3 different variables: year, month and day 
     list($year,$month,$day)=explode("-", $selected_date); 

     //Year is not needed in SQL commands for animal and swedish finnish namedays
     $month_day = $month."-".$day;

  $animaldb="finnish_namedays";
//Changes country codes into more readable descriptions
if ($language_code!="fi") {
  if ($language_code=="cz") {$language="tsekkiläisiä";}
  if ($language_code=="sk") {$language="slovakialaisia";}
  if ($language_code=="pl") {$language="puolalaisia";}
  if ($language_code=="fr") {$language="ranskalaisia";}
  if ($language_code=="hu") {$language="unkarilaisia";}
  if ($language_code=="se") {$language="ruotsalaisia";}
  if ($language_code=="us") {$language="yhdysvaltalaisia";}
  if ($language_code=="at") {$language="itävaltalaisia";}
  if ($language_code=="it") {$language="italialaisia";}
  if ($language_code=="es") {$language="espanjalaisia";}
  if ($language_code=="de") {$language="saksalaisia";}
  if ($language_code=="dk") {$language="tanskalaisia";}
  if ($language_code=="bg") {$language="bulgaarialaisia";}
  if ($language_code=="lt") {$language="liettualaisia";}
  if ($language_code=="ee") {$language="virolaisia";}
  if ($language_code=="lv") {$language="latvialaisia";}
  if ($language_code=="gr") {$language="kreikkalaisa";}
  if ($language_code=="ru") {$language="venäläisiä";}
  if ($language_code=="cat") {$language="kissojen"; $animaldb="cat_namedays";}  //Animaldb is used to select which animal nameday table to use for the SQL search.
  if ($language_code=="dog") {$language="koirien"; $animaldb="dog_namedays";}
  if ($language_code=="rodent") {$language="jyrsijöiden"; $animaldb="rodent_namedays";}
}
else {$language="suomenkielisiä";};

//Animal namedays start 
if($language_code=="cat" || $language_code=="dog" || $language_code=="rodent" || $language_code=="fi"){

  //Slecting which animal icon to show
  if($language_code=="cat" ){
        echo '<img src="https://img.icons8.com/color/48/000000/cat-head.png" alt="Eläin kuvake" class="auto_margin"'.$alt_img.'/>';
      }
      if($language_code=="dog" ){
        echo '<img src="https://img.icons8.com/color/48/000000/pug.png" alt="Eläin kuvake" class="auto_margin"'.$alt_img.'/>';
      }
        if($language_code=="rodent" ){        
        echo '<img src="https://img.icons8.com/color/48/000000/cute-hamster.png" alt="Eläin kuvake" class="auto_margin"'.$alt_img.'/>';
      }   

      if($language_code=="fi" ){        
        //echo '<img src="https://www.countryflags.io/'.$language_code.'/shiny/64.png"  alt="Valtion lippu kuvake" class="auto_margin"'.$alt_img.'/>';
        echo '<img src="'.$liput_url.strtoupper($language_code).'.png"  alt="Valtion lippu kuvake" class="valtion-lippu auto_margin"'.$alt_img.'/>';
      }         
  
      //The SQL query
      $animals = $db->query("SELECT * FROM $animaldb where nameday like '%$month_day';");  

        //Used to count if a name was found or not.
        $i=0;
        //Selects which text to show the use, the date or "today"
        if ($selected_date!=date('Y-m-d')) {
          echo "<h4 class='cent_text' >Nimipäivät ".$day.".".$month.".".$year." "."</h4>";
          $is_it_today= "<b class='lightly_bold'>".$day.".".$month.".".$year."</b> ".$language." nimipäiväsankareita ovat ";
        }        
        else{
          echo '<h4 class="cent_text">Nimipäivät tänään '.$day.'.'.$month.'.'.$year.'. </h4>';          
          $is_it_today= "Tänään ".$language." nimipäiväsankareita ovat ";          
        }
        echo "<p class='cent_text'>";  
        echo $is_it_today;

        //Loop through local animal nameday database
          while($row = $animals->fetch(PDO::FETCH_ASSOC)) {  
              $name = $row['name'];   
              $nameday = $row['nameday']; 

              //Dividing full date into year month and day variales
              list($year,$month,$day)=explode("-", $nameday);   

              //Dividing string of that days nameday heroes into individual names
              $names = explode(", ", $name);

              //Loop thourgh the names
              foreach ($names as $one_name){                
                echo '<a href="/info/nimihaku/?fname='.$one_name.'&country='.$language_code.'">'.$one_name."</a>. ";  
                //Gives the right value for year based on if the day is already in the past this year.
                $comparison_date=$month.$day;                 
                $now= date('md');
                if($comparison_date < $now) {
                  $year=date('Y')+1;
                }
                else {
                  $year=date('Y');
                }                
                $i++;
              }  
          }
          //No names found
          if($i<= 0){
            echo ucfirst($language)." nimipäivää ei löytynyt";
          }
          echo "</p>";   
          
           //Swedish finnish namedays from our own database because that data wasnt in Abalin's nameday api
        //Only needed when selected country is Finland.
        if($language_code=="fi"){
          if ($selected_date!=date('Y-m-d')) {
            echo "<p class='cent_text'><b class='lightly_bold'>".$day.".".$month.".".$year."</b> ruotsinkielisiä nimipäiväsankareita ovat ";
          }        
          else{
            echo "<p class='cent_text'>Tänään ruotsinkielistä nimipäiväsankareita ovat ";        
          }       
                 
          //Query for swedish finnish nameheroes from our DB.
          $swedish_finnish = $db->query("SELECT * FROM swedish_finnish_namedays where nameday like '%$month_day';");
          
          //Shows name links
          while($row = $swedish_finnish->fetch(PDO::FETCH_ASSOC)) {  
            $name = $row['name'];   
              $nameday = $row['nameday']; 

              //Dividing full date into year month and day variales
              list($year,$month,$day)=explode("-", $nameday);   

              //Dividing string of that days nameday heroes into individual names
              $names = explode(", ", $name);

              //Loop thourgh the names
              foreach ($names as $one_name){                
                echo '<a href="/info/nimihaku/?fname='.$one_name.'&country='.$language_code.'">'.$one_name."</a>. ";  
                //Gives the right value for year based on if the day is already in the past this year.
                $comparison_date=$month.$day;                 
                $now= date('md');
                if($comparison_date < $now) {
                  $year=date('Y')+1;
                }
                else {
                  $year=date('Y');
                }                
                $i++;
              }  
          }
          //No names found
          if($i<= 0){
            echo "Ruotsinkielistä nimipäivää ei löytynyt";
          }
          echo "</p>";   
        }
      } //Animals end

      //Abalin nameday api starts
else{
      //Curl gets wanted data from Abalins nameday api.
      $curl = curl_init();  
      curl_setopt_array($curl, array(
        //For example finnish language code is fi.
        //CURLOPT_URL => "https://api.abalin.net/namedays?country=".$language_code."&month=".$month."&day=".$day,
        CURLOPT_URL => "https://nameday.abalin.net/namedays?country=".$language_code."&month=".$month."&day=".$day,
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

      //Checks curl response for errors
      if ($err) {
        //Curl had an error
        if ($debug) echo "cURL Error #:" . $err;
        else echo $err;
      } else {
        //Data succesfully received from api without errors.

        //Decoding received json array
        $data = json_decode($response);

        //Shows country flag of the selected country.  Number infront of .png means resolution of the icon.
        //echo '<img src="https://www.countryflags.io/'.$language_code.'/shiny/64.png"  alt="Valtion lippu kuvake" class="auto_margin"'.$alt_img.'/>';
        echo '<img src="'.$liput_url.strtoupper($language_code).'.png"  alt="Valtion lippu kuvake" class="valtion-lippu auto_margin"'.$alt_img.'/>';

        //Selects "today" text or date if it isnt today.
        if ($selected_date!=date('Y-m-d')) {
          echo "<h4 class='cent_text'>Nimipäivät ".$day.".".$month.".".$year." "."</h4>";
          $is_it_today= "<b class='lightly_bold'>".$day.".".$month.".".$year."</b> ".$language." nimipäiväsankareita ovat ";
        }        
        else{
          echo "<h4 class='cent_text'>Nimipäivät tänään ".$day.".".$month.".".$year." "."</h4>";          
          $is_it_today= "Tänään ".$language." nimipäiväsankareita ovat ";          
        }       
        
        echo "<p class='cent_text'>";  

        //Shows date or "today" text infront of the nameday heroes name and selected language.
        echo $is_it_today;   

        //Name day hero data
        $nameheroes = $data->data->namedays->$language_code;        

        //Divides multiple name results into an array
        $singular_names=preg_split('/\s*,\s*/', $nameheroes);

        //Creating links for each name
        foreach($singular_names as $one_name)
        {          
          echo '<a href="/info/nimihaku/?fname='.$one_name."&country=".$language_code.'">'.$one_name."</a>. ";
        }
        echo "</p>";

       

      } 
    }
?>
      <!-- Next and previous day buttons, along with rest button. -->
      <div class='flexing justify_cent'>

        <a class="read-more page_change_button" lang="fi"
          href="<?php bloginfo('url'); ?>/nimipaivat/?country=<?php echo $language_code ?>&dateselect=<?php echo date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $selected_date) ) )); ?>">
          <?php if ($selected_date!=date('Y-m-d')) { echo "Edellinen päivä"; }else{ echo "Eilen"; } ?></a>

        <a class="read-more page_change_button mid_button_margin" lang="fi"
          href="<?php bloginfo('url'); ?>/nimipaivat/?country=<?php echo $language_code ?>&dateselect=<?php echo date('Y-m-d' ); ?>">Tänään</a>

        <a class="read-more page_change_button" lang="fi"
          href="<?php bloginfo('url'); ?>/nimipaivat/?country=<?php echo $language_code ?>&dateselect=<?php echo date('Y-m-d',(strtotime ( '+1 day' , strtotime ( $selected_date) ) )); ?>">
          <?php if ($selected_date!=date('Y-m-d')) { echo "Seuraava päivä"; }else{ echo "Huomenna"; } ?></a>

      </div>

      <div>
        <?php       
      //Form that searches namedays with date and country.
      include 'nimipaivat-search_with_date_form.php';
      //Form that searches namedays with name and country.
      include 'nimipaivat-search_with_name_form.php'; 
            ?>

      </div>
    </div>
    <?php
    //Popular Finnish womens names based on our DB filled with Väestönrekisteri data
  echo "
  <br>
  <div>    
    <div>
      <h4> Suosituimpia suomalaisia naisten etunimiä</h4>
      <ul>";
        //SQL query for most popular womens names.
        $ladies = $db->query("SELECT * FROM firstnames where sex like 'woman' order by amount desc limit 5 ;");
        while($row = $ladies->fetch(PDO::FETCH_ASSOC)) {  
            $name = $row['name'];
            $amount = $row['amount'];      
            echo "<li>".'<a href="info/nimihaku/?fname='.$name.'&country=fi">'.$name."</a> on etunimenä 
            <b class='lightly_bold'>".number_format($amount, 0, '.', ' ')."</b>:lla suomalaisella naisella.</li>";       
        }  
    echo "<a href='/info/nimitilastot/?gender=woman'><p>Näytä kaikki suomalaiset naisten nimet</p></a>";
    echo "
      </ul>
    </div>";

//Popular Finnish womens names based on our DB filled with Väestönrekisteri data
echo "
  <div>
    <h4> Suosituimpia suomalaisia miesten etunimiä</h4>
    <ul>";
    //SQL query for most popular mens names.
    $gentlemen = $db->query("SELECT * FROM firstnames where sex like 'man' order by amount desc limit 5 ;");           
      while($row = $gentlemen->fetch(PDO::FETCH_ASSOC)) {  
          $name = $row['name'];
          $amount = $row['amount'];      
          echo "<li>".'<a href="info/nimihaku/?fname='.$name.'&country=fi">'.$name."</a> on etunimenä <b class='lightly_bold'>".number_format($amount, 0, '.', ' ')."</b>:lla suomalaisella miehellä.</li>";        
      }

      

      echo "<a href='/info/nimitilastot/?gender=man'><p>Näytä kaikki suomalaiset miesten nimet</p></a>";
echo "
    </ul>
  </div>
</div>";



echo "<a href='/info/nimitilastot'><p>Näytä kaikki suomalaiset nimet</p></a>";  

//Google chart holders
echo "<div id='women_chart' class='google_chart'></div>";
echo "<div id='men_chart' class='google_chart'></div>";


//Google chart for men.
$title = "Suosituimpia suomalaisia miesten etunimiä";
$div_id = "men_chart";
$result = $db->query("SELECT * FROM firstnames where sex like 'man' order by amount desc limit 20 ;"); 
$rows = array();
$table = array();
$table['cols'] = array(
  array('label' => 'Nimi', 'type' => 'string'),
  array('label' => 'Nimien määrä', 'type' => 'number')
);
$ord=0;
  /* Extract the information from $result */
  foreach($result as $r) {
    $ord++;
    $temp = array();

    // the following line will be used to slice the Pie chart

    $temp[] = array('v' => (string) $ord.". ".$r['name']); 

    // Values of each slice

    $temp[] = array('v' => (int) $r['amount']); 
    $rows[] = array('c' => $temp);
  }

$table['rows'] = $rows;

// convert data into JSON format
$jsonTable = json_encode($table);



//Google chart for women.
$title2 = "Suosituimpia suomalaisia naisten etunimiä";
$div_id2 = "women_chart";
$result2 = $db->query("SELECT * FROM firstnames where sex like 'woman' order by amount desc limit 20 ;"); 
$rows2 = array();
$table2 = array();
$table2['cols'] = array(
  array('label' => 'Nimi', 'type' => 'string'),
  array('label' => 'Nimien määrä', 'type' => 'number')
);
$ord2=0;
  foreach($result2 as $r2) {
    $ord2++;
    $temp2 = array();
    $temp2[] = array('v' => (string) $ord2.". ".$r2['name']); 
    $temp2[] = array('v' => (int) $r2['amount']); 
    $rows2[] = array('c' => $temp2);
  }
$table2['rows'] = $rows2;
$jsonTable2 = json_encode($table2);

//Sending both men and women data thruough a Wordpress funtion to the google chart javascript.
do_action('run_chart',$title, $jsonTable, $div_id, $title2, $jsonTable2, $div_id2); 

//Google chart ends
?>
    <br><br>
    <div class="horizontal-ad">
      <?php the_field("alabanneri","option"); ?>
    </div>

  </div>
</div>

<?php
get_footer();