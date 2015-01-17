<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('bulan_list'))
{
  function bulan_list($kosong = 0)
  {
    $CI =& get_instance();
    $CI->lang->load('calendar');

    if($kosong) $result[0] = 'Semua bulan';
    $result['01'] = $CI->lang->line('cal_january');
    $result['02'] = $CI->lang->line('cal_february');
    $result['03'] = $CI->lang->line('cal_march');
    $result['04'] = $CI->lang->line('cal_april');
    $result['05'] = $CI->lang->line('cal_may');
    $result['06'] = $CI->lang->line('cal_june');
    $result['07'] = $CI->lang->line('cal_july');
    $result['08'] = $CI->lang->line('cal_august');
    $result['09'] = $CI->lang->line('cal_september');
    $result['10'] = $CI->lang->line('cal_october');
    $result['11'] = $CI->lang->line('cal_november');
    $result['12'] = $CI->lang->line('cal_december');

    return $result;
  }
}

if ( ! function_exists('bln_list'))
{
  function bln_list($kosong = 0)
  {
    $CI =& get_instance();
    $CI->lang->load('calendar');

    if($kosong) $result[0] = 'Semua bulan';
    $result['01'] = $CI->lang->line('cal_jan');
    $result['02'] = $CI->lang->line('cal_feb');
    $result['03'] = $CI->lang->line('cal_mar');
    $result['04'] = $CI->lang->line('cal_apr');
    $result['05'] = $CI->lang->line('cal_may');
    $result['06'] = $CI->lang->line('cal_jun');
    $result['07'] = $CI->lang->line('cal_jul');
    $result['08'] = $CI->lang->line('cal_aug');
    $result['09'] = $CI->lang->line('cal_sep');
    $result['10'] = $CI->lang->line('cal_oct');
    $result['11'] = $CI->lang->line('cal_nov');
    $result['12'] = $CI->lang->line('cal_dec');

    return $result;
  }
}

if ( ! function_exists('nama_bulan'))
{
  function nama_bulan($bulan)
  {
    $array_bulan = bulan_list();
    if(strlen($bulan) == 1) $bulan = '0'.$bulan;
    return $array_bulan[$bulan];
  }
}

if ( ! function_exists('nama_bln'))
{
  function nama_bln($bulan)
  {
    $array_bulan = bln_list();
    if(strlen($bulan) == 1) $bulan = '0'.$bulan;
    return $array_bulan[$bulan];
  }
}

if ( ! function_exists('to_rupiah'))
{
  function to_rupiah($value)
  {
    if($value < 0)
    {
      return '( Rp '.number_format(abs($value), 0, ',', '.').' )';
    }
    else
    {
      return 'Rp '.number_format($value, 0, ',', '.').'  ';
    }
  }
}

if ( ! function_exists('format_rupiah'))
{
  function format_rupiah($value)
  {
    if($value < 0)
    {
      return '( '.number_format(abs($value), 2, ',', '.').' )';
    }
    else
    {
      return '  '.number_format($value, 2, ',', '.').'  ';
    }
  }
}

if ( ! function_exists('prepare_numeric'))
{
  function prepare_numeric($value, $default = null)
  {
    if(isset($value) && $value != '')
      return floatval(str_replace('.','',$value)) * 1;
    return $default;
  }
}

if ( ! function_exists('format_integer'))
{
  function format_integer($value, $default = null)
  {
    if(isset($value) && $value != '')
      return floatval(str_replace('.00','',$value)) * 1;
    return $default;
  }
}

if ( ! function_exists('format_date'))
{
  function format_date($date, $style='d/m/Y')
  {
    if (isset($date))
      return date($style, strtotime( $date ) );
    return '';
  }
}

if ( ! function_exists('prepare_date'))
{
  function prepare_date($date)
  {
    if (isset($date) && $date != '')
      return implode( "-", array_reverse( explode("/", $date ) ) );
    return null;
  }
}

if ( ! function_exists('get_where_str'))
{
  function get_where_str($param, $fieldmap)
  {
    $wh = array();
    foreach($param as $key => $value){
      if (array_key_exists($key, $fieldmap))
      {
        $fld = "UPPER(".$fieldmap[ $key ].")";
        $str = strtoupper($value['search_str']);
        $op = $value['search_op'];

        switch($op)
        {
          case "bw" : $wh = array($fld." LIKE" => $str."%"); break;
          case "cn" : $wh = array($fld." LIKE" => "%".$str."%"); break;
          case "eq" : $wh = array($fld => $str); break;
          default : ;
        }
      }
    }
    return $wh;
  }
}

if ( ! function_exists('get_order_by_str'))
{
  function get_order_by_str($param, $fieldmap)
  {
    $ob = '';
    if (array_key_exists($param, $fieldmap))
    {
      $ob = $fieldmap[ $param ];
    }
    return $ob;
  }
}
