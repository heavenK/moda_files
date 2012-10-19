<?php

include 'php-ofc-library/open-flash-chart.php';

$title = new title( date("D M d Y") );
$title->set_style( '{color: #567300; font-size: 14px}' );

$data=array();
for( $i=0; $i<12; $i++)
{
    if(rand(1,10)<7)
        $data[] = $i;    // <-- append a number
    else
    {
        // append a bar_value object to the data array
        $bar = new bar_value(3);
        $bar->set_tooltip( 'Hello<br>#val#' );
        $data[] = $bar;
    }
}

$bar = new bar_sketch( '#81AC00', '#567300', 5 );
$bar->set_values( $data );

$chart = new open_flash_chart();
$chart->set_title( $title );
$chart->add_element( $bar );

echo $chart->toPrettyString();
