<?php
namespace ShoppingCart\ViewHelpers;

class RadioCheckViewHelper {
    private $output = "";
    private $name = "";
    private $submitValue;
    private $ajaxSubmitValue;
    private $checkedIdentifier;
    private $type;

    /**
     * @return mixed
     */
    private function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    private function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    private function getSubmitValue()
    {
        return $this->submitValue;
    }

    /**
     * @param string $submitValue
     */
    private function setSubmitValue($submitValue)
    {
        $this->submitValue = $submitValue;
    }
    private $value_identifier = [];

    /**
     * @return array
     */
    private function getValueIdentifier()
    {
        return $this->value_identifier;
    }

    /**
     * @param array $value_identifier
     */
    private function setValueIdentifier($value_identifier)
    {
        $this->value_identifier = $value_identifier;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public static function create(){
        return new self();
    }

    /**
     * @return mixed
     */
    private function getOutput()
    {
        return $this->output;
    }

    public function setValueAndIdentifier($arr){
        $this->value_identifier = $arr;
        return $this;
    }

    public function setSubmit($value){
        $this->submitValue = $value;
        return $this;
    }

    public function setAjaxSubmit($id){
        $this->ajaxSubmitValue = $id;
        return $this;
    }

    public function setCheckedIdentifier($identifier){
        $this->checkedIdentifier = $identifier;
        return $this;
    }

    public function typeCheckbox(){
        $this->type = "checkbox";
        return $this;
    }

    public function typeRadiobutton(){
        $this->type = "radio";
        return $this;
    }

    public function render(){
        $this->output .= "<form>\n";
        $name = $this->name;
        $type = self::getType();
        foreach (self::getValueIdentifier() as $k => $v){
            if($this->checkedIdentifier == $v){
                $this->output .= "\t<input type=\"" . $type . "\" name=\"" . $name . "\" value=\"" . $k . "\"" ." checked> " . $v . "\n";
            }
            else{
                $this->output .= "\t<input type=\"" . $type . "\" name=\"" . $name . "\" value=\"" . $k . "\"" ."> " . $v . "\n";
            }

        }
        if(isset($this->submitValue)){
            $this->output .= "\t<input type=\"submit\" value=\"" . $this->getSubmitValue() . "\">\n";
        }
        if(isset($this->ajaxSubmitValue)){
            $this->output .= "\t<input type=\"button\" id=\"" . $this->ajaxSubmitValue . "\" value=\"" . "Submit" . "\">\n";
        }
        $this->output .= "</form>";

        echo self::getOutput();
    }
}