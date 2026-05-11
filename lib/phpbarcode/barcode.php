<?php

define('INDEX_AUTH', '1');

if (!defined('SB')) {
  require __DIR__ . '/../../sysconfig.inc.php';
}

// Load composer autoloader for picqer/php-barcode-generator
$autoload_path = __DIR__ . '/../../vendor/autoload.php';
if (file_exists($autoload_path)) {
    require_once $autoload_path;
}

function scinfo()
{
  $host = $_SERVER['HTTP_HOST'];
  $path = $_SERVER['SCRIPT_NAME'];
  $dir = explode('/', $path);
  $file = $dir[count($dir)-1];
  unset($dir[count($dir)-1]);
  $dir = implode('/', $dir);
  return array($host, $dir, $file);
}

function checkref($mode = 'module') {
  $ref = false;
  if (isset($_SERVER['HTTP_REFERER'])) {
  	$ref_url = $_SERVER['HTTP_REFERER'];
  	$ref_part = (object) parse_url($ref_url);
  	
	$ref_host = isset($ref_part->host) ? $ref_part->host : '';
  	$ref_host .= isset($ref_part->port) ? ':' . $ref_part->port : '';
  	$ref_ip = isset($ref_part->host) ? gethostbyname($ref_host) : '';
  	$ref_path = isset($ref_part->path) ? $ref_part->path : '/';
  	$ref_dir = explode('/', $ref_path);
  	unset($ref_dir[count($ref_dir)-1]);
  	$ref_dir = implode('/', $ref_dir);
  	$ref_admin = $ref_host . $ref_dir;
  	$ref_q = isset($ref_part->query) ? $ref_part->query : '';
  	$ref_req = $ref_admin . '?' . $ref_q;
  
  	list($dest_host, $dest_dir, $dest_file) = scinfo();
  	$dest_path = $_SERVER['SCRIPT_NAME'];
  	$dest_ip = gethostbyname($dest_host);
  	$dest_dir = explode('/', SWB);
  	unset($dest_dir[count($dest_dir)-3]);
  	unset($dest_dir[count($dest_dir)-2]);
  	unset($dest_dir[count($dest_dir)-1]);
  	$dest_dir = implode('/', $dest_dir);
  	$dest_admin = $dest_host . $dest_dir . 'admin';
  	$dest_plugin = $dest_admin . '/modules/plugins';
  	$dest_q = 'mod=plugins';
  	$dest_req = $dest_admin . '?' . $dest_q;
  	switch ($mode)
  	{
  		case "host":
  			if ($ref_host == $dest_host)
  				$ref = true;
  			break;
  		case "ip":
  			if ($ref_ip == $dest_ip)
  				$ref = true;
  			break;
  		case "admin":
  			$is_admin = explode($dest_admin, $ref_admin);
  			if (empty($is_admin[0]))
  				$ref = true;
  			break;
  		case "module":
  		default:
  			if ($ref_req == $dest_req)
  				$ref = true;
  	}
  	if ($ref_path == $dest_path)
  		$ref = true;
  }
  if ($ref !== true)
    die(sprintf('<div>%s %s!</div>', $ref_admin, $dest_admin));
  else
    return;
}

$get = (object)$_GET;
$allowed_scale = array(1, 2, 3, 4, 5, 6);
if ( ! isset($get->scale) OR (isset($get->scale) AND ! in_array($get->scale, $allowed_scale)))
	$get->scale = 2;

$code = isset($get->code) ? trim($get->code) : '1234567890';
$code = stripslashes($code);
$code_raw = $code;
$code = urlencode($code);

$encoding = isset($get->encoding) ? trim($get->encoding) : 'code128';
$scale = isset($get->scale) ? trim($get->scale) : '2';
$mode = isset($get->mode) ? trim($get->mode) : 'png';

// Encoding mapping from SLiMS encoding names to picqer/php-barcode-generator types
$encoding_map = [
    'code128' => \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_128,
    'code39' => \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_39,
    'code25' => \Picqer\Barcode\BarcodeGenerator::TYPE_STANDARD_2_5,
    'code25interleaved' => \Picqer\Barcode\BarcodeGenerator::TYPE_INTERLEAVED_2_5,
    'codabar' => \Picqer\Barcode\BarcodeGenerator::TYPE_CODABAR,
    'code93' => \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_93,
    'ean2' => \Picqer\Barcode\BarcodeGenerator::TYPE_EAN_2,
    'ean5' => \Picqer\Barcode\BarcodeGenerator::TYPE_EAN_5,
    'ean8' => \Picqer\Barcode\BarcodeGenerator::TYPE_EAN_8,
    'ean13' => \Picqer\Barcode\BarcodeGenerator::TYPE_EAN_13,
    'upca' => \Picqer\Barcode\BarcodeGenerator::TYPE_UPC_A,
    'upce' => \Picqer\Barcode\BarcodeGenerator::TYPE_UPC_E,
    'itf14' => \Picqer\Barcode\BarcodeGenerator::TYPE_ITF_14,
    'postnet' => \Picqer\Barcode\BarcodeGenerator::TYPE_POSTNET,
    'planet' => \Picqer\Barcode\BarcodeGenerator::TYPE_PLANET,
];

// Try Zend_Barcode engine if enabled
if ($sysconf['zend_barcode_engine'] === true) {
  $zend_file = LIB . 'Zend/Barcode.php';
  if (file_exists($zend_file)) {
    ini_set('include_path', LIB);
    require_once $zend_file;
    
    $act = isset($get->act) ? trim($get->act) : 'save';
    $output = isset($get->output) ? trim($get->output) : 'image';
    $ext = $output == 'image' ? $mode : 'pdf';
    
    $file_name = __DIR__ . '/../../images/barcodes/' . $code . '.' . $ext;
    
    $options = array('text' => $code_raw);
    $options['factor'] = $scale;
    $options['font'] = realpath(__DIR__ . '/DejaVuSans.ttf');
    $options['fontSize'] = 8;
    
    $renderer = Zend_Barcode:: factory(
        $encoding, $output, $options, array()
    );
    if ($act == 'save') {
      call_user_func('image'.$mode, $renderer->draw(), $file_name);
    } else {
      $renderer->render();
    }
    exit;
  }
}

// Fallback: use picqer/php-barcode-generator
$picqer_type = isset($encoding_map[$encoding])
    ? $encoding_map[$encoding]
    : \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_128;

$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
$height = (int)$scale * 25;
$widthFactor = (int)$scale;
if ($widthFactor < 1) $widthFactor = 2;

$barcode_data = $generator->getBarcode($code_raw, $picqer_type, $widthFactor, $height);

$file_name = __DIR__ . '/../../images/barcodes/' . $code . '.png';
file_put_contents($file_name, $barcode_data);

header('Content-Type: image/png');
echo $barcode_data;
exit;
