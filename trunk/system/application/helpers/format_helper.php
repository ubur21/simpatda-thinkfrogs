<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('to_words'))
{
	function to_words($value)
	{
		
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
			return $value * 1;
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

if ( ! function_exists('set_dropdown'))
{
	function set_dropdown($option, $data, $pilih=1)
	{
		$opt = "";
		if ($pilih)
			$opt = "<option value=\"\"".("" == $data ? " selected=\"selected\"" : "").">Pilih</option>\n";
		for ($i = 0; $i < count($option); $i++)
		{
			$opt .= "<option value=\"".$option[$i]['ID']."\"".($option[$i]['ID'] == $data ? " selected=\"selected\"" : "").">".$option[$i]['URAIAN']."</option>\n";
		}
		return $opt;
	}
}

if ( ! function_exists('get_image_size'))
{
	function get_image_size($fn, $sz)
	{
		list($width, $height, $type, $attr) = getimagesize( $fn );
		if ($width > $height) {
		  $w = $sz;
		  $h = ($sz * $height) / $width;
		}
		else {
		  $w = ($sz * $width) / $height;
		  $h = $sz;
		}
		$size = array( $w, $h );
		return $size;
	}
}