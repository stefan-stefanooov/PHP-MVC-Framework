<?php
namespace ShoppingCart\ViewHelpers;

class DropDownViewHelper {
    private $output = "";
    private $name ="";
    private $action ="";
    private $valueIdentifierArr = [];
    private $submitValue;
    private $selectedIdentifier;

    public static function create(){
        return new self();
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setSubmit($submitValue){
        $this->submitValue = $submitValue;
        return $this;
    }

    public function setValueAndIdentifier($arr){
        $this->valueIdentifierArr = $arr;
        return $this;
    }

    public function setSelectedIdentifier($identifier){
        $this->selectedIdentifier = $identifier;
        return $this;
    }

    public function render(){
        $this->output .= "<form action=\"" . $this->action . "\">\n";
        $this->output .= "\t<select name=\"" . $this->name . "\">\n";
        foreach ($this->valueIdentifierArr as $k => $v){
            if($this->selectedIdentifier == $v){
                $this->output .= "\t\t<option value=\"" . $k . "\"" ." selected> " . $v . "\n";
            }
            else{
                $this->output .= "\t\t<option value=\"" . $k . "\"" ."> " . $v . "\n";
            }

        }
        $this->output .= "\t</select>\n";
        if(isset($this->submitValue)){
            $this->output .= "\t<input type=\"submit\" value=\"" . $this->submitValue . "\">\n";
        }
        $this->output .= "</form>";

        echo  $this->output;
    }
}