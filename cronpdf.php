<?php

require_once(__DIR__.'/vendor/autoload.php');
require_once(__DIR__.'/config.php');

define('DOMPDF_ENABLE_AUTOLOAD', false);
define('DOMPDF_ENABLE_REMOTE', $config['remote_assets']);

require_once(__DIR__.'/vendor/dompdf/dompdf/dompdf_config.inc.php');

class createPDF {

  private $config, $html, $header, $footer;

  /**
   * Constructor to set our private variables and run the app
   */
  function __construct() {
    global $config;
    $this->config = (object) $config;
    $this->checkReqs();
    $this->assemble();
    $this->output();
  }

  /**
   * Check the application has all needed files, else abort
   *
   * @return void
   */
  private function checkReqs() {
    $header = file_get_contents(__DIR__.'/html/header.html');
    $footer = file_get_contents(__DIR__.'/html/footer.html');
    if($header == false || $footer == false) {
      die('Missing components!');
    } else {
      $this->header = $header;
      $this->footer = $footer;
    }
  }

  /**
   * Assemble the html
   *
   * @return void
   */
  private function assemble() {
    $body = $this->buildBody();
    $this->html = $this->header.$body.$this->footer;
  }

  /**
   * Gently place the data into a pdf and save to disk
   *
   * @return void
   */
  private function output() {
    $dompdf = new DOMPDF();
    $dompdf->load_html($this->html);
    $dompdf->set_paper('a4', $this->config->orientation);
    $dompdf->render();
    $output = $dompdf->output();
    file_put_contents($this->config->filename, $output);
  }

  /** 
   * Retrieve the json from the endpoint
   *
   * @return array
   */
  private function retrieveData() {
    $endpoint = $this->config->endpoint;

    $curl = curl_init($endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    $output = curl_exec($curl);
    curl_close($curl);

    return json_decode($output);
  }

  /**
   * Build the body || This is the function to edit a per map basis
   *
   * @return string
   */
  private function buildBody() { // Never skip leg day kids.
    $sites = $this->retrieveData();
    $sites = $sites->rows;
    $body  = "";

    foreach($sites as $site) {
      if($site->value->status !== 'disabled' && $site->value->status !== 'none') {
        $x = ($site->value->x * $this->config->scale_x) - $this->config->shift_x;
        $y = ($site->value->y * $this->config->scale_y) - $this->config->shift_y;
        $body .= '<img style="position:absolute;top:'.$y.'px;left:'.$x.'px;z-index: '.$this->config->z_index.';" src="./html/img/'.$site->value->status.'.png">';
        $body .= "\n";
      }
    }
    return $body;
  }

}
new createPDF();
