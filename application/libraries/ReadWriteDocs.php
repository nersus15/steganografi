<?php
use Smalot\PdfParser\Parser;

class ReadWriteDocs{
    /** @var CI_Controller */
    private $ci;

    /** @var Word */
    var $word;

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
    function CreateWordDocument($string, $fpath) {
        $this->ci->load->library('Word');
        $PHPWord = $this->word; // New Word Document
        $section = $PHPWord->createSection(); // New portrait section
        // Add text elements
        $section->addText($string);
        $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        $objWriter->save($fpath);
    }
}