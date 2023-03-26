<?php
use Smalot\PdfParser\Parser;

class ReadWriteDocs{
    /** @var CI_Controller */

    
    private $ci;

    public function __construct() {
        $this->ci =& get_instance();
    }
    function pdfToPlainText(){
        require_once APPPATH . 'third_party/Smalot/PdfParser/Parser.php';
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
    function CreateWordDocument($xmlString) {
        $templateFolder = $this->config->fileTemplateFolder;
        if(!endsWith($templateFolder, '/'))
            $templateFolder = $templateFolder.'/';
    
    
        $temp_file = tempnam(sys_get_temp_dir(), 'coi_').'.docx';
    
        copy($templateFolder. 'coi.docx', $temp_file);
    
        $zip = new ZipArchive();
        if($zip->open($temp_file)===TRUE) {
    
            $zip->deleteName('word/document.xml');
            $zip->addFromString("word/document.xml", $xmlString);
            $zip->close();
    
            return $temp_file;
        }
        else {
            return null;
        }
    }
}