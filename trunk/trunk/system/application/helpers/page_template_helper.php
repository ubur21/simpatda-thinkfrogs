<?php

if ( ! function_exists('element')) {
    function show($view, $data=array(), $template='template') {
        $ci = &get_instance();
		$cview = (isset($data['layout_dir'])) ? $data['layout_dir'] : $view;
		$data['view'] = $view;
        $data = $ci->load->view($cview.'/'.$template, $data);
    }
}
 
/* End of file page_template_helper.php */
/* Location: ./system/helpers/page_template_helper.php */
?>