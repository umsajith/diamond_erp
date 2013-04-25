<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Create PDF using Dompdf rendering engine
 * @param  string  $html     
 * @param  string  $filename 
 * @param  boolean $stream   to stream or output the page
 */
function mkpdf($html, $filename, $stream = TRUE) 
{
    require_once(APPPATH . 'third_party/dompdf/dompdf_config.inc.php');
    
    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->set_paper("a4", "portrait" );
    $dompdf->render();

    if($stream)
    {
    	$dompdf->stream($filename . ".pdf");
    }
    else
    {
    	return $dompdf->output();
    }
}