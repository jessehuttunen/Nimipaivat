<div class="cent_left mini_top_margin">
  <label for="fname" class="">Hae nimellä ja maalla:</label>
  <form action="<?php bloginfo('url'); ?>/nimihaku/" method="GET" class="flexing">
    <input type="text" id="fname" name="fname" placeholder="Kirjoita haettava nimi tähän"
      class="nameday_search_bar thin_border white_background">
    <select name="country" id="country" class="white_background mid_button_margin" value=language_code>
      <option value="fi" <?php if ($language_code == "fi") echo "selected" ?>>Suomi</option>
      <option value="se" <?php if ($language_code == "se") echo "selected" ?>>Ruotsi</option>
      <option value="dk" <?php if ($language_code == "dk") echo "selected" ?>>Tanska</option>
      <option value="ru" <?php if ($language_code == "ru") echo "selected" ?>>Venäjä</option>
      <option value="ee" <?php if ($language_code == "ee") echo "selected" ?>>Viro</option>
      <option value="lv" <?php if ($language_code == "lv") echo "selected" ?>>Latvia</option>
      <option value="lt" <?php if ($language_code == "lt") echo "selected" ?>>Liettua</option>
      <option value="cz" <?php if ($language_code == "cz") echo "selected" ?>>Tsekki</option>
      <option value="sk" <?php if ($language_code == "sk") echo "selected" ?>>Slovakia</option>
      <option value="pl" <?php if ($language_code == "pl") echo "selected" ?>>Puola</option>
      <option value="fr" <?php if ($language_code == "fr") echo "selected" ?>>Ranska</option>
      <option value="hu" <?php if ($language_code == "hu") echo "selected" ?>>Unkari</option>
      <option value="us" <?php if ($language_code == "us") echo "selected" ?>>USA</option>
      <option value="at" <?php if ($language_code == "at") echo "selected" ?>>Itävalta</option>
      <option value="it" <?php if ($language_code == "it") echo "selected" ?>>Italia</option>
      <option value="es" <?php if ($language_code == "es") echo "selected" ?>>Espanja</option>
      <option value="de" <?php if ($language_code == "de") echo "selected" ?>>Saksa</option>
      <option value="bg" <?php if ($language_code == "bg") echo "selected" ?>>Bulgaria</option>
      <option value="gr" <?php if ($language_code == "gr") echo "selected" ?>>Kreikka</option>
      <option value="cat" <?php if ($language_code == "cat") echo "selected" ?>>Kissat</option>
      <option value="dog" <?php if ($language_code == "dog") echo "selected" ?>>Koirat</option>
      <option value="rodent" <?php if ($language_code == "rodent") echo "selected" ?>>Jyrsijät</option>
    </select>
    <input type="submit" class="read-more flex1" value="Hae">
  </form>
</div>