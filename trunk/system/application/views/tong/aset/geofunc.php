<?php
/*******************************************************************************
* xgeo                                                                         *
********************************************************************************
* Version: 1.0                                                                 *
* Date:    2008-12-01                                                          *
* Author:  Samodra                                                             *
*******************************************************************************/

if ( basename( __FILE__ )==basename( $_SERVER['PHP_SELF'] ) ) {
  die( "You do not have any grant to access this script directly." );
}

global $_INCL_SAM_DEV_GEOFUNC_;

if( !isset( $_INCL_SAM_DEV_GEOFUNC_ ) ) {

  $_INCL_SAM_DEV_GEOFUNC_ = 1;
  define( 'XGEO_VERSION','1.0' );
  define( 'XGEO_AUTHOR', 'Samodra' );

  function subordinate_to_num( $deg=0, $min=0, $sec=0, $msec=0 ) {
    while( $msec > 1 ) $msec = $msec/10;
    return $deg + ($min/60) + (($sec + $msec)/3600);
  }

  function num_to_subordinate( $subordinate=0 ) {
    $aret = array(0,0,0,0,0,0);
    $ni   = $subordinate;

    // degree
    $aret[0] = floor( $ni );
    $ni = $ni - $aret[0];
    $ni = $ni * 60;
    // minute
    $aret[1] = floor( $ni );
    $ni = $ni - $aret[1];
    $ni = $ni * 60;
    // second
    $aret[2] = floor( $ni );
    $ni = $ni - $aret[2];
    $ns = 1;
    while( ( ($ni*$ns)-floor($ni*$ns) ) > 0 ) {
      $ni *= 10;
    }
    // millisecond
    $aret[3] = floor( $ni*$ns );
    $aret[4] = $ni;
    $aret[5] = $ns;

    return $aret;

  }

  /**
   * Class to accessing geospatial data
   *
   * @package MyGeoAppl
   * @copyright 2008, Samodra
   * @ All rights reserved
   * @version $Revision: 1.00 $
   */

  class xgeo_subordinate {

    var $version;
    var $author;

    var $subint;
    var $subord;
    var $xord;
    var $subordashtml;
    var $subordastext;

    /*
    function __construct() {
      $this->version      = XGEO_VERSION;
      $this->author       = XGEO_AUTHOR;
      $this->subint       = 0;
      $this->subord       = array(0,0,0,0);
      $this->subordashtml = $this->make_subord_to_html( $this->subord );
      $this->subordastext = $this->make_subord_to_text( $this->subord );
    }
    */

    /**
     * make sub ordinate text as html with given parameters
     *
     * @name _make_subord_to_html
     * @param integer[optional] $degree
     * @param integer[optional] $min
     * @param integer[optional] $sec
     * @param integer[optional] $msec
     * @return string
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function _make_subord_to_html( $degree=0, $min=0, $sec=0, $msec=0 ) {
      $xsec = $msec;
      while( $xsec > 1000 ) {
        $xsec = round($xsec/10);
      }
      $sret =  $degree                               . "&deg;"  .
               str_pad( $min, 2, '0', STR_PAD_LEFT ) . "'"      .
               str_pad( $sec, 2, '0', STR_PAD_LEFT ) . "."      .
               round( $xsec, 3) . "\"";
      $this->subordashtml = $sret;
      return $sret;
    }

    /**
     * make sub ordinate text as pure text with given parameters
     * degree sign changed with asteris
     *
     * @name _make_subord_to_text
     * @param integer[optional] $degree
     * @param integer[optional] $min
     * @param integer[optional] $sec
     * @param integer[optional] $msec
     * @return string
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function _make_subord_to_text( $degree=0, $min=0, $sec=0, $msec=0 ) {
      $xsec = $msec;
      while( $xsec > 1000 ) {
        $xsec = round($xsec/10);
      }
      $sret =  " ".
               $degree                               . "*"  .
               str_pad( $min, 2, '0', STR_PAD_LEFT ) . "'"  .
               str_pad( $sec, 2, '0', STR_PAD_LEFT ) . "."   .
               round( $xsec, 3) . "\" ";
      $this->subordastext = $sret;
      return $sret;
    }

    /**
     * make sub ordinate text as html with given parameters
     *
     * @name make_subord_to_html
     * @param array[optional] $array_of_sub_ordinate
     * @return string
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function make_subord_to_html( $subord=array() ) {
      $degree = 0;
      $min    = 0;
      $sec    = 0;
      $msec   = 0;
      $i = count( $subord );
      if( $i > 0 ) $degree  = $subord[0];
      if( $i > 1 ) $min     = $subord[1];
      if( $i > 2 ) $sec     = $subord[2];
      if( $i > 3 ) $msec    = $subord[3];
      return $this->_make_subord_to_html( $degree, $min, $sec, $msec );
    }

    /**
     * make sub ordinate text as pure text with given parameters
     * degree sign changed with asteris
     *
     * @name make_subord_to_text
     * @param array[optional] $array_of_sub_ordinate
     * @return string
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function make_subord_to_text( $subord=array() ) {
      $degree = 0;
      $min    = 0;
      $sec    = 0;
      $msec   = 0;
      $i = count( $subord );
      if( $i > 0 ) $degree  = $subord[0];
      if( $i > 1 ) $min     = $subord[1];
      if( $i > 2 ) $sec     = $subord[2];
      if( $i > 3 ) $msec    = $subord[3];
      return $this->_make_subord_to_text( $degree, $min, $sec, $msec );
    }

    /**
     * Convert some integer value to subordinate
     *
     * @name convert_int_to_subcoord
     * @param unknown_type $point_sub
     * @return array of subordinate
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function convert_int_to_subord( $subpoint = 0 ) {

      $subord = array( 0, 0, 0, 0, 0, 0 );
      $ni = $subpoint;

      $subord[0] = floor( $ni ); $ni = $ni - $subord[0];
      $ni = $ni * 60;

      $subord[1] = floor( $ni ); $ni = $ni - $subord[1];
      $ni = $ni * 60;

      $subord[2] = floor( $ni ); $ni = $ni - $subord[2];
      $ns = 1;

      while( ( ( $ni * $ns ) - floor( $ni * $ns ) ) > 0 )
        $ns *= 10;
      $subord[3] = floor( $ni * $ns );

      $subord[4] = $ni;
      $subord[5] = $ns;

      $this->subord = $subord;
      $this->subint = $subpoint;
      $this->make_subord_to_html( $subord );
      $this->make_subord_to_text( $subord );

      // $ns = min( $ns, 1000000000000 );
      // $subord[3] = str_pad( floor( $ni * $ns ), 3, STR_PAD_LEFT );
      // $subord[3] = floor( $ni * $ns );

      return $subord;

    }

    /**
     * Convert subordinate data to integer
     *
     * @name _convert_subord_to_int
     * @param integer[optional] $degree
     * @param integer[optional] $min
     * @param integer[optional] $sec
     * @param integer[optional] $msec
     * @return integer
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function _convert_subord_to_int( $degree=0, $min=0, $sec=0, $msec=0 ) {
      $this->subord   = array( $degree, $min, $sec, $msec );
      $this->subint   = $degree + ( $min/60 ) + ( ( $sec . '.' . $msec )/3600 );
      $this->_make_subord_to_html( $degree, $min, $sec, $msec );
      $this->_make_subord_to_text( $degree, $min, $sec, $msec );
      return $this->subint;
    }

    /**
     * Convert subordinate of array( degree, minutes, seconds, miliseconds ) to integer
     *
     * @name convert_subord_to_int
     * @param array[optional] $arrpoint_sub
     * @return int
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function convert_subord_to_int( $arrpoint_sub = array() ) {
      $degree = 0;
      $min    = 0;
      $sec    = 0;
      $msec   = 0;
      $i = count( $arrpoint_sub );
      if( $i > 0 ) $degree  = $arrpoint_sub[0];
      if( $i > 1 ) $min     = $arrpoint_sub[1];
      if( $i > 2 ) $sec     = $arrpoint_sub[2];
      if( $i > 3 ) $msec    = $arrpoint_sub[3];
      if( $i > 5 ) $msec    = $arrpoint_sub[4] / $arrpoint_sub[5];
      return $this->_convert_subord_to_int( $degree, $min, $sec, $msec );
    }

    function xgeo_subordinate() {

      $narg = func_num_args();
      $aarg = func_get_args();

      $this->version      = XGEO_VERSION;
      $this->author       = XGEO_AUTHOR;

      if( $narg > 0 ) {
        if( is_array( $aarg[0] ) ) {
          $this->_convert_subord_to_int( $aarg[0] );
        } else if ( is_numeric( $aarg[0] ) ) {
          if( $narg==1 ) {
            $this->convert_int_to_subord( $aarg[0] );
          } else {
            if( is_float( $aarg[0] ) ) {
              $this->convert_int_to_subord( $aarg[0] );
            } else {
              if( $narg>3 ) {
                $this->_convert_subord_to_int( $aarg[0], $aarg[1], $aarg[2], $aarg[3] );
              } else if( $narg > 2 ) {
                $this->_convert_subord_to_int( $aarg[0], $aarg[1], $aarg[2] );
              } else if( $narg > 1 ) {
                $this->_convert_subord_to_int( $aarg[0], $aarg[1] );
              } else {
                $this->_convert_subord_to_int( $aarg[0] );
              }
            }
          }
        }
      } else {
        $this->_convert_subord_to_int( 0 );
      }

    }

  }

  class xgeo_point2d extends xgeo_subordinate {

    var $elm_x;
    var $elm_y;

    var $x;
    var $y;
    var $ordx;
    var $ordy;
    var $htmlx;
    var $htmly;
    var $textx;
    var $texty;
    var $pointdata;
    var $coorddata;
    var $htmldata;

    var $arrayofcoord;
    var $arrayofpoint;
    var $arrayofhtml;
    var $arrayoftext;
    var $originaltext;
    var $originaldata;

    var $strprefix = 'POINT';

    /**
     * Construct this class,
     * give the first initialization by calling __construct()
     *
     * @name xgeo_point2d
     * @return void
     */
    function xgeo_point2d( $stringdata='POINT(0 0)' ) {
      // $this->__construct();
      $this->elm_x = new xgeo_subordinate(0);
      $this->elm_y = new xgeo_subordinate(0);
      //
      $this->x = $this->elm_x->subint;
      $this->y = $this->elm_y->subint;
      $this->pointdata = array( $this->x  , $this->y  );
      //
      $this->ordx = array( 0, 0, 0, 0 );
      $this->ordy = array( 0, 0, 0, 0 );
      $this->coorddata = array( $this->ordx , $this->ordy );
      //
      $this->htmlx = $this->make_subord_to_html( $this->ordx );
      $this->htmly = $this->make_subord_to_html( $this->ordy );
      $this->htmldata = array( $this->htmlx, $this->htmly );
      //
      $this->save_input_string( $stringdata );
      $this->set_string_to_coordinate( $stringdata );
    }

    /**
     * Set array of ordinate_x,
     * automatically convert this array to integer and saved to x
     *
     * @name setordx
     * @param integer[optional] $ord_x
     * @return int
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function setordx() {
      $narg = func_num_args();
      $aarg = func_get_args();
      $ordx = $this->ordx;
      if( $narg > 0 ) $ordx = $aarg[0];
      $this->ordx = $ordx;
      // echo
      $this->x    = $this->convert_subord_to_int( $ordx );
      $this->htmlx= $this->make_subord_to_html( $this->ordx );
      $this->textx= $this->make_subord_to_text( $this->ordx );
      return $this->x;
    }
    /**
     * Set array of ordinate_y,
     * automatically convert this array to integer and saved to y
     *
     * @name setordy
     * @param integer[optional] $ord_y
     * @return int
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function setordy() {
      $narg = func_num_args();
      $aarg = func_get_args();
      $ordy = $this->ordy;
      if( $narg > 0 ) $ordy = $aarg[0];
      $this->ordy = $ordy;
      $this->y    = $this->convert_subord_to_int( $ordy );
      $this->htmly= $this->make_subord_to_html( $this->ordy );
      $this->texty= $this->make_subord_to_text( $this->ordy );
      return $this->y;
    }
    /**
     * Set array of ordinate x and y,
     * automatically convert this array data into integer and saved to x and y
     *
     * @name setcoordxy
     * @param array[optional] $ord_x
     * @param array[optional] $ord_y
     * @return array
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function setcoordxy() {
      $narg = func_num_args();
      $aarg = func_get_args();
      if( $narg > 0 ) {
        $this->setordx( $aarg[0] );
      } else {
        $this->setordx();
      }
      if( $narg > 1 ) {
        $this->setordy( $aarg[1] );
      } else {
        $this->setordy();
      }
      return array( $this->x, $this->y );
    }

    /**
     * Set sub data of integer (x),
     * automatically convert into ordinate and saved into ord x
     *
     * @name setx
     * @param integer[optional] $intx
     * @return array
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function setx() {
      $narg = func_num_args();
      $aarg = func_get_args();
      $intx = $this->x;
      if( $narg > 0 ) $intx = $aarg[0];
      $this->x      = $intx;
      $this->ordx   = $this->convert_int_to_subord( $intx );
      $this->htmlx  = $this->make_subord_to_html( $this->ordx );
      $this->textx  = $this->make_subord_to_text( $this->ordx );
      return $this->ordx;
    }
    /**
     * Set sub data of integer (y),
     * automatically convert into ordinate and saved into ord y
     *
     * @name sety
     * @param integer[optional] $intx
     * @return array
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function sety() {
      $narg = func_num_args();
      $aarg = func_get_args();
      $inty = $this->y;
      if( $narg > 0 ) $inty = $aarg[0];
      $this->y      = $inty;
      $this->ordy   = $this->convert_int_to_subord( $inty );
      $this->htmly  = $this->make_subord_to_html( $this->ordy );
      $this->texty  = $this->make_subord_to_text( $this->ordy );
      return $this->ordy;
    }
    /**
     * Set point (int x, int y),
     * automatically convert each data x and y and saved into ord x and ord y
     *
     * @name setpointxy
     * @param integer[optional] $intx;
     * @param integer[optional] $inty;
     * @return array
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function setpointxy() {
      $narg = func_num_args();
      $aarg = func_get_args();
      if( $narg > 0 ) {
        $this->setx( $aarg[0] );
      } else {
        $this->setx();
      }
      if( $narg > 1 ) {
        $this->setx( $aarg[1] );
      } else {
        $this->setx();
      }
      return array( $this->ordx, $this->ordy );
    }

    /**
     * Get x data as integer
     * @name getx
     * @return int
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function getx() {
      return $this->x;
    }
    /**
     * Get y data as integer
     * @name gety
     * @return int
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function gety() {
      return $this->y;
    }
    /**
     * Get x y data as array of integer
     * @name getpointxy
     * @return array of integer
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function getpointxy() {
      return array( $this->x, $this->y );
    }

    /**
     * get ordinate x as array of integer
     * @name getordx
     * @return array of integer
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function getordx() {
      return $this->ordx;
    }
    /**
     * get ordinate y as array of integer
     * @name getordy
     * @return array of integer
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function getordy() {
      return $this->ordy;
    }
    /**
     * get ordinate x and y as array of ordinate x and y
     * each data ordinate of x and y are an array with elements
     * ordinate data are array of ( degree, minute, second and milisecond )
     * @name getordx
     * @return array of array of integer
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function getcoordxy() {
      return array( $this->ordx, $this->ordy );
    }

    /**
     * create text with parameters are array are ordinates, both of x and y
     * space as separator between sub_point x and sub_point y
     * @name _coord_to_text
     * @param array[optional] $ordx
     * @param array[optional] $ordy
     * @return string
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function _coord2d_to_text() {
      $narg = func_num_args();
      $aarg = func_get_args();
      if( $narg > 0 ) $this->setordx();
      if( $narg > 1 ) $this->setordy();
      return $this->x . ' ' . $this->y;
    }
    /**
     * create text with parameters are integers both of x and y,
     * the result is a text with space as separator between sub_point x and sub_point y
     * @name _coord_to_text
     * @param integer[optional] $x
     * @param integer[optional] $y
     * @return string
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function _point2d_to_text() {
      $narg = func_num_args();
      $aarg = func_get_args();
      if( $narg > 0 ) $this->setx( $aarg[0] );
      if( $narg > 1 ) $this->sety( $aarg[1] );
      return $this->x . ' ' . $this->y;
    }

    function _text_to_coord2d() {
      $narg = func_num_args();
      $aarg = func_get_args();
      if( $narg > 0 ) {
        $axy = explode( ' ', $aarg[0] );
      } else {
        $axy = array( $this->x, $this->y );
      }
      if( count( $axy ) > 0 ) $this->setx( $axy[0] );
      if( count( $axy ) > 1 ) $this->sety( $axy[1] );
      return array( $this->ordx, $this->ordy );
    }

    function _text_to_point2d() {
      $narg = func_num_args();
      $aarg = func_get_args();
      if( $narg > 0 ) {
        $axy = explode( ' ', $aarg[0] );
      } else {
        $axy = array( $this->x, $this->y );
      }
      if( count( $axy ) > 0 ) $this->setx( $axy[0] );
      if( count( $axy ) > 1 ) $this->sety( $axy[1] );
      return array( $this->x, $this->y );
    }

    /**
     * Create text: POINT(x y) from given coordinates parameters
     *
     * @name coord_to_text
     * @param array[optional] $ordx
     * @param array[optional] $ordy
     * @return string
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function coord2d_to_text() {
      $narg = func_num_args();
      $aarg = func_get_args();
      if( $narg > 0 ) $this->setordx( $aarg[0] );
      if( $narg > 1 ) $this->setordy( $aarg[1] );
      return 'POINT(' . $this->x . ' ' . $this->y . ')';
    }
    /**
     * Create text: POINT(x y) from given points paramters
     *
     * @name point_to_text
     * @param integer[optional] $x
     * @param integer[optional] $y
     * @return string
     * @version 1.0
     * @copyright 2008, BRR NAD-Nias
     * @author Samodra
     */
    function point2d_to_text() {
      $narg = func_num_args();
      $aarg = func_get_args();
      if( $narg > 0 ) $this->setx( $aarg[0] );
      if( $narg > 1 ) $this->sety( $aarg[1] );
      return 'POINT(' . $this->x . ' ' . $this->y . ')';
    }

    private function text_to_point2d() {
      if( func_num_args() > 0 ) {
        // $strdata =
      } else {
        return array( $this->x, $this->y );
      }
    }

    private function set_variable_info() {
      // -----------------------------------------------------------------------------
      // warning:
      // do not call this function directly, this function called by other function...
      // -----------------------------------------------------------------------------
      $npointcount = count( $this->arrayofpoint );
      $this->originaltext = "";
      $this->originaldata = "";
      $this->strprefix = "";

      if( $npointcount >0 ) {
        if( $npointcount==1 ) {
          $this->strprefix = "POINT";
        } else if( $npointcount==2 ) {
          $this->strprefix = "LINESTRING";
        } else if( $npointcount > 2 ) {
          $this->strprefix = "MULTILINESTRING";
          if( $this->arrayofpoint[0] == $this->arrayofpoint[$npointcount-1] ) {
            $this->strprefix = "POLYGON";
          }
        }
      }

      for( $i=0; $i<$npointcount; $i++ ) {
        if( trim( $this->originaldata )!="" ) $this->originaldata .= ",";
        $this->originaldata .= $this->arrayofpoint[$i][0] . " " .
                               $this->arrayofpoint[$i][1];
      }

      if ($this->strprefix == "MULTILINESTRING") {
        $this->originaltext = $this->strprefix . "((" . $this->originaldata . "))";
    	} else {
        $this->originaltext = $this->strprefix . "(" . $this->originaldata . ")";
    	}
      // echo $this->originaldata.'<br>'.$this->originaltext.'<br>';
    }

    function save_input_string( $stringdata=null ) {
      //
      // $this->originaldata = preg_replace( "/((.*\()|\(|\))/i" , "", $stringdata );
      $this->originaltext = $stringdata;
      //
      $nlstart = strpos( $stringdata, '(' );
      $nlend   = strpos( $stringdata, ')' );
      if( substr( $stringdata, $nlstart+1, 1 )=='(' ) {
        $this->originaldata = substr( $stringdata, $nlstart+2, $nlend - $nlstart - 2 );
      } else {
        $this->originaldata = substr( $stringdata, $nlstart+1, $nlend - $nlstart - 1 );
      }
      /*
      $this->originaldata = substr( $stringdata, $nlstart+1, $nlend - $nlstart );
      */
      if( $nlstart > 0 ) {
        $this->strprefix = substr( $stringdata, 1, $nlstart );
      } else {
        $this->strprefix = 'defined later';
      }
      //
    }

    function proceed_from_saved_string() {

      $apointdata = explode( ",", $this->originaldata );

      $this->arrayofcoord = null; $this->arrayofcoord = array();
      $this->arrayofpoint = null; $this->arrayofpoint = array();
      $this->arrayofhtml  = null; $this->arrayofhtml  = array();
      $this->arrayoftext  = null; $this->arrayoftext  = array();

      $npointcount = count( $apointdata );
      for( $i=0; $i < $npointcount; $i++ ) {
        $this->arrayofpoint[] = explode( " ", $apointdata[$i] );
        $this->setx( $this->arrayofpoint[$i][0] );
        $this->sety( $this->arrayofpoint[$i][1] );
        $this->arrayofcoord[] = array( $this->ordx  , $this->ordy   );
        $this->arrayofhtml[]  = array( $this->htmlx , $this->htmly  );
        $this->arrayoftext[]  = array( $this->textx , $this->texty  );
      }

      $this->set_variable_info();

    }

    function proceed_from_saved_points() {

      $this->arrayofcoord = null; $this->arrayofcoord = array();
      $this->arrayofhtml  = null; $this->arrayofhtml  = array();
      $this->arrayoftext  = null; $this->arrayoftext  = array();
      for( $i=0; $i<count( $this->arrayofpoint ); $i++ ) {
        $this->setx( $this->arrayofpoint[$i][0] );
        $this->sety( $this->arrayofpoint[$i][1] );
        $this->arrayofcoord[] = array( $this->ordx, $this->ordy );
        $this->arrayofhtml[]  = array( $this->htmlx , $this->htmly  );
        $this->arrayoftext[]  = array( $this->textx , $this->texty  );
      }

      $this->set_variable_info();

    }

    function proceed_from_saved_coords() {
      $this->arrayofpoint = null; $this->arrayofpoint = array();
      $this->arrayofhtml  = null; $this->arrayoftext  = array();
      $this->arrayoftext  = null; $this->arrayoftext  = array();
      for( $i=0; $i<count( $this->arrayofcoord ); $i++ ) {
        // echo $this->arrayofcoord[$i][0].' '.$this->arrayofcoord[$i][1]."<br>";
        $this->setordx( $this->arrayofcoord[$i][0] );
        $this->setordy( $this->arrayofcoord[$i][1] );
        $this->arrayofpoint[] = array( $this->x, $this->y );
        $this->arrayofhtml[]  = array( $this->htmlx , $this->htmly  );
        $this->arrayoftext[]  = array( $this->textx , $this->texty  );
      }
      $this->set_variable_info();
    }

    function set_string_to_coordinate( $stringdata=null ) {

      $this->save_input_string( $stringdata );
      $this->proceed_from_saved_string();

      return $this->arrayofcoord;

    }

    function set_coordinate_to_string( $array_of_coords=null ) {
      //
      $this->arrayofcoord = $array_of_coords;
      $this->proceed_from_saved_coords();
      //
      return $this->originaltext;
      //
    }

    function set_arrayofpoint_to_string( $array_of_points=null ) {
      //
      $this->arrayofpoint = $array_of_points;
      $this->proceed_from_saved_points();
      //
      return $this->originaltext;
    }

  }

  class xgeo2d extends xgeo_point2d {

    function xgeo2d( $stringdata='LINESTRING((0 0,0 0))' ) {
      $this->arrayofcoord = array();
      $this->arrayofpoint = array();
      $this->save_input_string( $stringdata );
      $this->set_string_to_arrayofcoordinate( $stringdata );
    }

    function set_string_to_arrayofcoordinate( $stringdata = null ) {
      //
      $this->save_input_string( $stringdata );
      $this->proceed_from_saved_string();
      //
      return $this->arrayofcoord;
    }

    function set_arrayofpoint_to_string( $array_of_points = null ) {
      //
      $this->arrayofpoint = $array_of_points;
      $this->proceed_from_saved_points();
      //
      return $this->originaltext;
      //
    }

    function set_arrayofcoordinate_to_string( $array_of_coords = null ) {
      //
      $this->arrayofcoord = $array_of_coords;
      $this->proceed_from_saved_coords();
      //
      return $this->originaltext;
      //
    }

  }

  function set_data_from_pgsql_string( $strdata=null ) {
    $xgeodata = new xgeo2d();
    $xgeodata->set_string_to_arrayofcoordinate( $strdata );
    return $xgeodata;
  }

  function set_data_from_arrayofcoordinate( $coords=null ) {
    $xgeodata = new xgeo2d();
    $xgeodata->set_arrayofcoordinate_to_string( $coords );
    return $xgeodata;
  }

  function set_data_from_arrayofpoints( $points=null ) {
    $xgeodata = new xgeo2d();
    $xgeodata->set_arrayofpoint_to_string( $points );
    return $xgeodata;
  }

  function get_arrayofcoordinate_to_pgsql_string( $coord=null ) {
    $xdata = new xgeo2d();
    $xdata->set_arrayofcoordinate_to_string( $coord );
    return $xdata->originaltext;
  }

  function get_pgsql_string_to_arrayofcoordinate( $strdata=null ) {
    $xdata = new xgeo2d();
    $xdata->set_string_to_arrayofcoordinate( $strdata );
    return $xdata->arrayofcoord;
  }

  function get_arrayofpoint_to_arrayofcoordinate( $arrpoints=null ) {
    $xdata = new xgeo2d();
    $xdata->set_arrayofpoint_to_string( $arrpoints );
    return $xdata->arrayofcoord;
  }

  function get_pgsql_string_to_arrayofpoints( $strdata=null ) {
    $xdata = new xgeo2d();
    $xdata->set_string_to_arrayofcoordinate( $strdata );
    return $xdata->arrayofpoint;
  }

}
?>