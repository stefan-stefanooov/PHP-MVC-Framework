<?php
namespace ShoppingCart\ViewHelpers;

class AjaxSubmitViewHelper {
    private $outputAjaxScript = "";
    private $outputAjaxForm = "";
    private $buttonId ="";
    private $url ="";
    private $method = "";
    private $textFieldArr = [];

    public static function create(){
        return new self();
    }

    /**
     * [inputType => id]
     * @return string
     */
    public function setTextFields($textFieldsArr)
    {
        $this->textFieldArr = $textFieldsArr;
        return $this;
    }

    public function setButtonId($buttonId)
    {
        $this->buttonId = $buttonId;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function setMethodt($method){
        $this->method = $method;
        return $this;
    }

    public function render(){
        $this->outputAjaxScript .= "<script>\n";
        $this->outputAjaxForm .= "<form>\n";
        $this->outputAjaxScript .= "\t$(\"#" . $this->buttonId . "\").click(function(){\n$.ajax({\n".
            "url: ". "\"" .$this->url . "\"" . ",\n" .
            "type: ". "\"" .  $this->method . "\"" . ",\n" .
            "data: {\n";
        foreach ($this->textFieldArr as $k => $v){

            if($v == "text"){
                $this->outputAjaxScript .= "\t\t" . $k . ":" ." $(\"#" . $k . "\").val(),\n";
                $this->outputAjaxForm .= "<p>" . $k . "<br>\n";
                $this->outputAjaxForm .= TextFieldViewHelper::create()
                    ->setTypeText()
                    ->setId($k)
                    ->renderString();
                $this->outputAjaxForm .= "</p>";
                $this->outputAjaxForm .= "\n";
            }
            else{
                $this->outputAjaxScript .= "\t\t" . $k . ":" ." $(\"#" . $k . "\").val(),\n";
                $this->outputAjaxForm .= "<p>" . $k . "<br>\n";
                $this->outputAjaxForm .= TextFieldViewHelper::create()
                    ->setTypePassword()
                    ->setId($k)
                    ->renderString();
                $this->outputAjaxForm .= "</p>";
                $this->outputAjaxForm .= "\n";
            }
        }
        $this->outputAjaxScript .= "}\n});})\n";
        $this->outputAjaxScript .= "</script>\n";
        $this->outputAjaxForm .= "<input type=\"button\" id=\"" . $this->buttonId . "\" value=\"Submit\">\n";
        $this->outputAjaxForm .= "</form>\n";

        echo  $this->outputAjaxForm . $this->outputAjaxScript;
    }
}