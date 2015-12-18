<?php
namespace ShoppingCart\ViewHelpers;

class TextAreaViewHelper {
    private $output;
    private $rows;
    private $cols;
    private $defaultText;

    public static function create(){
        return new self();
    }

    public function setRows($rows){
        $this->rows = $rows;
        return $this;
    }

    public function setCols($cols){
        $this->cols = $cols;
        return $this;
    }

    public function setDefaultText($text){
        $this->defaultText = $text;
        return $this;
    }

    public function render(){
        echo $this->output = "<textarea rows=\"" . $this->rows . "\" cols=\"" . $this->cols . "\">\n" .
            $this->defaultText . "\n" .
        "</textarea>\n";
    }
}