<?php
defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'third_party/Smalot/PdfParser/Autoloader.php';
require_once APPPATH . 'third_party/Smalot/PdfParser/Parser.php';

use Smalot\PdfParser\Parser;

function read_doc($fpath)
{
  $fileHandle = fopen($fpath, "r");
  $line = @fread($fileHandle, filesize($fpath));
  $lines = explode(chr(0x0D), $line);
  $outtext = "";
  foreach ($lines as $thisline) {
    $pos = strpos($thisline, chr(0x00));
    if (($pos !== FALSE) || (strlen($thisline) == 0)) {
    } else {
      $outtext .= $thisline . " ";
    }
  }
  $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/", "", $outtext);
  return $outtext;
}
function read_docx($fpath)
{

  $striped_content = '';
  $content = '';

  $zip = zip_open($fpath);

  if (!$zip || is_numeric($zip)) return false;

  while ($zip_entry = zip_read($zip)) {

    if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

    if (zip_entry_name($zip_entry) != "word/document.xml") continue;

    $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

    zip_entry_close($zip_entry);
  } // end while

  zip_close($zip);

  $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
  $content = str_replace('</w:r></w:p>', "\r\n", $content);
  $striped_content = strip_tags($content);

  return $striped_content;
}

function readPdf($fpath, $cetak = false)
{
  Autoloader::register();
  $parser  = new Parser();
  $pdf = $parser->parseFile($fpath);
  $metadata = $pdf->getDetails();

  $text = $pdf->getText();
  if ($cetak) {
    foreach ($metadata as $meta => $value) {
      if (is_array($value)) {
        $value . implode(", ", $value);
      }
      echo $meta . "=>" . $value . "\n";
    }
    die;
    echo '<br>';
    echo $text;
  } else {
    return ['meta' => $metadata, 'text' => $text];
  }
}
function wordToXml($docx)
{

  $zip = new ZipArchive; // creating object of ZipArchive class.
  $tmpDir = get_path(DOCS_PATH . 'tmp/xml/');
  $tmpDir2 = get_path(DOCS_PATH . 'tmp/docs/');
  $sUploadedFile = $docx;
  $zip->open($tmpDir2 . $docx);
  $aFileName = explode('.',$sUploadedFile);
  $sDirectoryName =  current($aFileName);

  
  if (!is_dir($tmpDir2 . $sDirectoryName)){
      mkdir($tmpDir2 . $sDirectoryName);
      $zip->extractTo($tmpDir2 . $sDirectoryName); 
      copy($tmpDir2 . $sDirectoryName .  "/word/document.xml", $tmpDir. "$sDirectoryName.xml");
  
      $xml = simplexml_load_file( $tmpDir. "$sDirectoryName.xml");
      $xml->registerXPathNamespace('w',"http://schemas.openxmlformats.org/wordprocessingml/2006/main");
      $text = $xml->xpath('//w:t');
  
      echo '<pre>'; print_r($text); echo '</pre>';
  
      rrmdir($tmpDir2 . $sDirectoryName);
  }
}

function rrmdir($dir) {
  if (is_dir($dir)) {
    $objects = scandir($dir);
    foreach ($objects as $object) {
      if ($object != "." && $object != "..") {
        if (filetype($dir."/".$object) == "dir") 
           rrmdir($dir."/".$object); 
        else unlink   ($dir."/".$object);
      }
    }
    reset($objects);
    rmdir($dir);
  }
 }
