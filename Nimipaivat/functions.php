<?php

add_action( 'wp_enqueue_scripts', 'add_my_script' );

function add_my_script() {

    wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'));

}



//Nameday Google charts
function chart_run($title, $names, $div_id, $title2, $names2, $div_id2) {
    wp_enqueue_script('my-script', get_stylesheet_directory_uri() . '/js/nimipaivat-googlechart_script.js', array('jquery') );
    wp_localize_script('my-script', 'my_variables', array(
        'title' => ($title),
        'names' => ($names),
        'div_id' => ($div_id),
        'title2' => ($title2),
        'names2' => ($names2),
        'div_id2' => ($div_id2)
    )
);
   }
add_action('run_chart','chart_run',10, 6);

//Nameday countdown timers
function countdown_run( $year2, $month2, $day2, $namedays) {
    wp_enqueue_script('scripti', get_stylesheet_directory_uri() . '/js/nimipaivat-countdown_timer.js', array('jquery') );
    wp_localize_script('scripti', 'my_variable', array(
        //Swedish finnish nameday
        'year2' => ($year2),
        'month2' => ($month2),
        'day2' => ($day2),
        //Other namedays
        'namedays' => ($namedays),        
    )
);
}
add_action('countdown','countdown_run',10, 4);




//Dequeue JavaScripts

function project_dequeue_unnecessary_scripts() {

    wp_dequeue_script( 'modernizr' );

        wp_deregister_script( 'modernizr' );

}

add_action( 'wp_print_scripts', 'project_dequeue_unnecessary_scripts' );







// ACF OPTIONS PAGE

if( function_exists('acf_add_options_page') ) {	

	acf_add_options_page();	

}