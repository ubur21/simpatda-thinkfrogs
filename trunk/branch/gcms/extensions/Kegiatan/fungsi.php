<?php
/* === MANAJEMEN FUNGSI ================================================================================================== */

/* membuat Fungsi perhitungan untuk nilai : 
-> rata- rata            : nilai total belanja semua kegiatan dibagi dengan jumlah kegiatan
-> standar deviasi  :  lihat di fungsi
-> batas atas          : (nilai rata-rata + standar deviasi)/Total nilai rata-rata
-> batas bawah      : (nilai rata-rata - standar deviasi)/Total nilai rata-rata
*/

/* Membuat Fungsi Rata - Rata */
function mean ($a){
    //variable and initializations
    $the_result = 0.0;
    $the_array_sum = array_sum($a); //sum the elements
    $number_of_elements = count($a); //count the number of elements
    //calculate the mean
    if ($number_of_elements > 0) {
        $the_result = $the_array_sum / $number_of_elements;
    }
    //return the value
    return $the_result;
}

function standard_deviation_population ($b){
    //variable and initializations
    $the_standard_deviation = 0.0;
    $the_variance = 0.0;
    $the_mean = 0.0;
    $number_elements = count($b); //count the number of elements

    //calculate the mean
    $the_mean = mean($b);

    //calculate the variance
    for ($i = 0; $i < $number_elements; $i++){
        //sum the array
        $the_variance = $the_variance + ($b[$i] - $the_mean) * ($b[$i] - $the_mean);
    }

    if ($number_elements > 0) {
        $the_variance = $the_variance / $number_elements;
    } else {
        $the_variance = 0;
    }

    //calculate the standard deviation
    $the_standard_deviation = pow( $the_variance, 0.5);

    //return the variance
    return $the_standard_deviation;
}
?>
