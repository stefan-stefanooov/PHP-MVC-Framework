<?php
namespace ShoppingCart\ViewHelpers;

class TextFieldViewHelper {
    private $output;
    private $outputText;
    private $type;
    private $name;
    private $id;

    public static function create(){
        return new self();
    }

    public function setTypeText(){
        $this->type = 'text';
        return $this;
    }

    public function setTypePassword(){
        $this->type = 'password';
        return $this;
    }

    public function setName($name){
        $this->name = $name;
        return $this;
    }

    public function setId($id){
        $this->id = $id;
        return $this;
    }

    public function render(){
        echo $this->output = "<input type=\"" . $this->type . "\"";
        if(isset($this->name)){
            $this->output .= "name=\"" . $this->name . "\">\n";
        }
        if(isset($this->id)){
            $this->output .= "id=\"" . $this->id . "\">\n";
        }
    }

    public function renderString(){
        $this->outputText = "<input type=\"" . $this->type . "\"";
        if(isset($this->name)){
            $this->outputText .= " name=\"" . $this->name . "\">\n";
        }
        if(isset($this->id)){
            $this->outputText .= " id=\"" . $this->id . "\">\n";
        }
        return $this->outputText;
    }
}