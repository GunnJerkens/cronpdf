<?php
require_once('./config.php');
require_once('./dompdf/dompdf_config.inc.php');

class createPDF {

  private $config, $html, $header, $footer;

  function __construct() {
    global $config;
    $this->config = (object) $config;
    $this->checkReqs();
    $this->assemble();
    $this->output();
  }

  private function checkReqs() {
    $header = file_get_contents('./header.html');
    $footer = file_get_contents('./footer.html');
    if($header == false || $footer == false) {
      die('Missing components!');
    } else {
      $this->header = $header;
      $this->footer = $footer;
    }
  }

  private function assemble() {
    $body = $this->buildBody();
    $this->html = $this->header.$body.$this->footer;
  }

  private function output() {
    $dompdf = new DOMPDF();
    $dompdf->load_html($this->html);
    $dompdf->set_paper('a4', $this->config->orientation);
    $dompdf->render();
    $output = $dompdf->output();
    file_put_contents($this->config->filename, $output);
  }

  private static function retrieveData() {
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

  private function buildBody() { // Never skip leg day kids.
    $sites = self::retrieveData();
    $sites = $sites->rows;
    $body  = "";
    $count = 0;

    foreach($sites as $site) {
      if($site->value->status !== 'disabled' && $site->value->status !== 'none') {
        $x = ($site->value->x * $this->config->scale_x) - $this->config->shift_x;
        $y = ($site->value->y * $this->config->scale_y) - $this->config->shift_y;
        $body .= '<img style="position:absolute;top:'.$y.'px;left:'.$x.'px;z-index: '.$this->config->z_index.';" src="./img/'.$site->value->status.'.png">';
        $body .= "\n";
      }
    }
    return $body;
  }

}
new createPDF();
