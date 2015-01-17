<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Form Validation Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	External
 * @author		Adam Jackett | Dark House Media
 * @link		http://www.darkhousemedia.com
 */
 
class CI_External {
	
	var $CI;
	var $ext = array();
	var $css_types = array('file', 'print', 'ie', 'ie6', 'custom', 'ie_custom', 'ie6_custom');
	var $js_types = array('file', 'ie', 'ie6', 'custom', 'ie_custom', 'ie6_custom');

	function CI_External($groups = array()){	
		$this->CI =& get_instance();
		
		$this->ext['css'] = array();
		foreach($this->css_types as $type){
			$this->ext['css'][$type] = array();
		}
		$this->ext['js'] = array();
		foreach($this->js_types as $type){
			$this->ext['js'][$type] = array();
		}
		
		if(is_array($groups) && count($groups) > 0){
			if(isset($groups['all'])){
				$this->set($groups['all']);
			}
			$uri = $this->CI->uri->segment_array();
			$total_segments = count($uri);
			$success = false;
			for($i = 0; $i < $total_segments; $i++){
				$uri_key = implode('/', $uri);
				if(isset($groups[$uri_key])){
					$this->set($groups[$uri_key]);
					$success = true;
					break;
				}
				array_pop($uri);
			}
			if(!$success){
				if(isset($groups['default'])){
					$this->set($groups['default']);
				}
			}
		}
	}
	
	function set($data){
		if(is_array($data)){
			if(isset($data['css'])) $this->set_css($data['css']);
			if(isset($data['js'])) $this->set_js($data['js']);
			return true;
		}
		return false;
	}
	
	function set_css($css, $type='file'){
		if(is_array($css)){
			foreach($css as $val){
				if(is_array($val)){
					$css_type = (isset($val[1]) ? $val[1] : $type);
					$this->_set_css($val[0], $css_type);
				} else {
					$this->_set_css($val, $type);
				}
			}
		} else {
			$this->_set_css($css, $type);
		}
	}
	
	function _set_css($css, $type){
		$this->ext['css'][$type][] = $css;
	}
	
	function set_js($js, $type='file'){
		if(is_array($js)){
			foreach($js as $val){
				if(is_array($val)){
					$js_type = (isset($val[1]) ? $val[1] : $type);
					$this->_set_js($val[0], $js_type);
				} else {
					$this->_set_js($val, $type);
				}
			}
		} else {
			$this->_set_js($js, $type);
		}
	}
	
	function _set_js($js, $type){
		$this->ext['js'][$type][] = $js;
	}

	function run(){
		if(count($this->ext['css']['file']) > 0):
			?>
			<style type="text/css">
			<?php
			foreach($this->ext['css']['file'] as $file):
				echo '@import url("'.$file.'");'."\r\n";
			endforeach;
			?>
			</style>
			<?php			
		endif;
		
		if(count($this->ext['css']['ie6']) > 0):
			echo '<!--[if IE 6]>';
			foreach($this->ext['css']['ie6'] as $file):
			?>
			<link rel="stylesheet" href="<?php echo $file; ?>" type="text/css" media="screen" charset="utf-8" />
			<?php
			endforeach;
			echo '<![endif]-->'."\r\n";
		endif;
		
		if(count($this->ext['css']['ie']) > 0): 
			echo '<!--[if IE]>';
			foreach($this->ext['css']['ie'] as $file):
			?>
			<link rel="stylesheet" href="<?php echo $file; ?>" type="text/css" media="screen" charset="utf-8" />
			<?php
			endforeach;
			echo '<![endif]-->'."\r\n";
		endif;
		
		if(count($this->ext['css']['custom']) > 0):
			?>
			<style type="text/css" media="screen">
			<?php
			foreach($this->ext['css']['custom'] as $style):
				echo $style;
			endforeach;
			?>
			</style>
			<?php
		endif;
		
		if(count($this->ext['css']['ie6_custom']) > 0):
			echo '<!--[if IE 6]>';
			?>
			<style type="text/css" media="screen">
			<?php
			foreach($this->ext['css']['ie6_custom'] as $style):
				echo $style;
			endforeach;
			?>
			</style>
			<?php
			echo '<![endif]-->'."\r\n";
		endif;
		
		if(count($this->ext['css']['ie_custom']) > 0): 
			echo '<!--[if IE]>';
			?>
			<style type="text/css" media="screen">
			<?php
			foreach($this->ext['css']['ie_custom'] as $style):
				echo $style;
			endforeach;
			?>
			</style>
			<?php
			echo '<![endif]-->'."\r\n";
		endif;

		if(count($this->ext['js']['file']) > 0):
			foreach($this->ext['js']['file'] as $file):
			?>
			<script src="<?php echo $file; ?>" type="text/javascript" charset="utf-8"></script>
			<?php
			endforeach;
		endif;
		
		if(count($this->ext['js']['ie6']) > 0):
			echo '<!--[if IE 6]>';
			foreach($this->ext['js']['ie6'] as $file):
			?>
			<script src="<?php echo $file; ?>" type="text/javascript" charset="utf-8"></script>
			<?php
			endforeach;
			echo '<![endif]-->'."\r\n";
		endif;
		
		if(count($this->ext['js']['ie']) > 0): 
			echo '<!--[if IE]>';
			foreach($this->ext['js']['ie'] as $file):
			?>
			<script src="<?php echo $file; ?>" type="text/javascript" charset="utf-8"></script>
			<?php
			endforeach;
			echo '<![endif]-->'."\r\n";
		endif;
		
		if(count($this->ext['js']['custom']) > 0):
			?>
			<script type="text/javascript" charset="utf-8">
				// <![CDATA[
				<?php			
				foreach($this->ext['js']['custom'] as $script):
					echo $script."\r\n";
				endforeach;			
				?>
				// ]]>
			</script>
			<?php
		endif;
		
		if(count($this->ext['js']['ie6_custom']) > 0):
			echo '<!--[if IE 6]>';
			?>
			<script type="text/javascript" charset="utf-8">
				// <![CDATA[
				<?php			
				foreach($this->ext['js']['ie6_custom'] as $script):
					echo $script."\r\n";
				endforeach;			
				?>
				// ]]>
			</script>
			<?php
			echo '<![endif]-->'."\r\n";
		endif;
		
		if(count($this->ext['js']['ie_custom']) > 0):
			echo '<!--[if IE]>';
			?>
			<script type="text/javascript" charset="utf-8">
				// <![CDATA[
				<?php			
				foreach($this->ext['js']['ie_custom'] as $script):
					echo $script."\r\n";
				endforeach;			
				?>
				// ]]>
			</script>
			<?php
			echo '<![endif]-->'."\r\n";
		endif;
	}

}