<?php
/*
Template Name: Nimipaivat nimitilastot
*/
get_header();
?>


<?php $thumb = get_the_post_thumbnail_url(); ?>
<div class="banner-container"
  style="background: url('<?php echo $thumb; ?>') no-repeat;background-position: center top; background-size:cover;">
  <div class="banner-content">
    <h1><?php the_title(); ?></h1>
    <p>Nimitilastosivulla voit selata Väestötietojärjestelmän jakamia nimitilastoja Suomessa käytössä olevista nimistä.
    </p>
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

    <?php 
    //Alternative image if flag icon isnt found 
    $alt_img='onerror="this.onerror=null;this.src=\'../flag_not_found.jpg\';" ';
    $liput_url = wp_upload_dir()['baseurl'] . '/liput/';
?>
    <!-- Back button to front page. -->
    <a class="read-more back_button" href="<?php bloginfo('url'); ?>/nimipaivat/">Takaisin</a>
    <div class="cent_text">
      <!-- Finnish fla icon. -->
      <!-- <img src="https://www.countryflags.io/fi/shiny/64.png" alt="Suomen lippu kuvake" class="auto_margin"
        <?php //echo $alt_img ?>> -->

      <img src="<?php echo $liput_url.'FI'; ?>.png"  alt="Suomen lippu kuvake" class="valtion-lippu auto_margin" <?php echo $alt_img; ?> />
    </div>
    <h4 class='cent_text'>Nimien määrät Suomessa</h4>

    <?php     
      //Gets the values given from the forms, and if some value wasnt sent, set a default value for the variables.
      if (isset($_GET['pagenumber']) && !empty($_GET["pagenumber"]) && $_GET["pagenumber"]>=1) {
        $pagenumber = $_GET['pagenumber'];
      } else{
        $pagenumber = 1;
      }
      
      if (isset($_GET['gender']) && !empty($_GET["gender"]) && ($_GET["gender"]=="man" || $_GET["gender"]=="woman") ) {
      $gender = $_GET['gender'];
      } else{
      $gender = "%";
      }

      if (isset($_GET['which_name']) && !empty($_GET["which_name"]) && $_GET["which_name"]=="secondarynames") {
        $which_name = $_GET['which_name'];
        } else{
        $which_name = "firstnames";
        }

      if (isset($_GET['order']) && !empty($_GET["order"]) && $_GET["order"]=="asc") {
        $order = $_GET['order'];
        } else{
        $order = "desc";
        }

      
    //Establishes the connection with our own database that holds name stats from Väestörekisteri.
    include 'nimipaivat-database_connection.php';

      ?>
    <p class='cent_text'>Rajaa hakutuloksia</p>
    <!-- Form for filtering data from database that holds the Väestönrekisteri namestats. -->
    <form action="" method="GET" class="justify_cent flexing">

      <select name="which_name" onchange="this.form.submit()" class="some_padding">
        <option value="firstnames" <?php if ($which_name == "firstnames") echo "selected" ?>>Etunimi</option>
        <option value="secondarynames" <?php if ($which_name == "secondarynames") echo "selected" ?>>Muu nimi</option>
      </select>

      <select name="gender" onchange="this.form.submit()" class="some_padding">
        <option value="man" <?php if ($gender == "man") echo "selected" ?>>Miehet</option>
        <option value="woman" <?php if ($gender == "woman") echo "selected" ?>>Naiset</option>
        <option value="%" <?php if ($gender == "%") echo "selected" ?>>Kaikki</option>
      </select>

      <select name="order" onchange="this.form.submit()" class="some_padding">
        <option value="desc" <?php if ($order == "desc") echo "selected" ?>>Laskeva</option>
        <option value="asc" <?php if ($order == "asc") echo "selected" ?>>Nouseva</option>
      </select>

      <!--<input type="submit" value="Rajaa">-->
    </form>

    <?php
     include 'nimipaivat-search_with_name_form.php';

      //Limit for how many names are shown per page;
      $results_per_page = 40;
//Counts total amount of name results
$query = "SELECT count(*) FROM $which_name Where sex like '$gender'";
//Counting number of pages for pagination.
$sql = $db->query($query);
$total_results = $sql->fetchColumn();
$total_pagenumbers = ceil($total_results/$results_per_page);
$starting_limit = ($pagenumber-1)*$results_per_page;


//Information texts above name list.

//Counts amount of names to show in the info
$count  = "SELECT count(*) FROM $which_name Where sex like '$gender'";
$sql2 = $db->query($count);
$total = $sql2->fetchColumn();

//Whatnames are shown (firstname, secondary or all)
if($which_name == "secondarynames"){
  $translate_name = "muita nimiä";
  }
else $translate_name = "etunimiä";

//Pagenumber for info section
echo "<br><div class='justify_cent flexing wrap'>";
//echo "<p><b class='lightly_bold'>Sivu ".$pagenumber.". </b></p>";
echo "<p class='cent_tex_mobile'>";

//What sex is selected (men, women or all)
if($gender == "woman"){
echo " Naisten ".$translate_name." löydetty <b class='lightly_bold'>".number_format($total, 0, '.', ' ');
}
else if($gender == "man"){
  echo " Miesten  ".$translate_name." löydetty <b class='lightly_bold'>".number_format($total, 0, '.', ' ');
}
else if($gender == "%"){
  echo ucfirst($translate_name)." löydetty <b class='lightly_bold'>".number_format($total, 0, '.', ' ');
}

echo "</b> kpl:ta.</p>";
/*
//Order for results
echo "<p class='cent_tex_mobile'> Nimien järjestys "; 
if($order == "asc"){
  echo "nouseva.";
  }
else{
  echo "laskeva.";
}
echo "</p>";
*/
echo "</div>";
//Info section ends

//Query to get name data.
$show  = "SELECT  name, 1+(SELECT count(*) FROM $which_name a Where a.amount > b.amount AND sex like '$gender') as RNK, amount, sex FROM $which_name b WHERE sex like '$gender' order by amount $order LIMIT ?,?";
$r = $db->prepare($show);
$r->execute([$starting_limit, $results_per_page]);
echo '<ul class="name_list">';

//Name stat results shown in a list
while($res = $r->fetch(PDO::FETCH_ASSOC)):
?>
    <li>
      <?php echo $res['RNK'].". ";?>
      <a href="/info/nimihaku/?fname=<?php echo $res['name'];?>&country=fi"><?php echo $res['name'];?></a>
      <?php echo number_format($res['amount'], 0, '.', ' '); ?>
    </li>
    <?php endwhile; ?>

    </ul>

    <!-- Page control buttons -->
    <div class="flexing justify_cent">

      <!-- Previous page button -->
      <a class="read-more page_change_button cent_text"
        href="<?php bloginfo('url'); ?>/nimitilastot/?pagenumber=<?php echo $pagenumber-1; ?>&which_name=<?php echo $which_name; ?>&gender=<?php echo $gender; ?>&order=<?php echo $gender; ?>">Edellinen
        sivu</a>

      <!-- Select any page number. For better UX, form is submitted automatically when user changes the value. -->
      <form action="" method="GET" class=" flexing justify_cent mid_button_margin">
        <input type="hidden" name="which_name" value="<?php echo $which_name; ?>">
        <input type="hidden" name="gender" value="<?php echo $gender; ?>">
        <input type="hidden" name="order" value="<?php echo $order; ?>">
        <select name="pagenumber" onchange="this.form.submit()" class="page_selector">
          <?php
          for ($page=1; $page <= $total_pagenumbers ; $page++):
          echo '<option value="'.$page.'" '.(($page==$pagenumber)?'selected="selected"':"").'>Sivu '.$page.'</option>';
          endfor; 
          ?>
        </select>
      </form>

      <!-- Next page button -->
      <a class="page_change_button read-more cent_text"
        href="<?php bloginfo('url'); ?>/nimitilastot/?pagenumber=<?php echo $pagenumber+1; ?>&which_name=<?php echo $which_name; ?>&gender=<?php echo $gender; ?>&order=<?php echo $gender; ?>">Seuraava
        sivu</a>

    </div>
    <br><br>
    <!--
    <p class='cent_text mini_text'>Tiedot perustuvat Digi- ja väestötietoviraston jakamaan
      avoimeen
      <a href="https://www.avoindata.fi/data/fi/dataset/none"> Väestötietojärjestelmän etunimitilastoihin</a>. Tiedot
      päivitetty
      viimeksi marraskuussa 2020.
    </p>
          -->
    <div class="horizontal-ad">
      <?php the_field("alabanneri","option"); ?>
    </div>

  </div>
</div>

<?php
get_footer();