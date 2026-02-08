<?php
namespace TimeShow\Repository\Data;

class ServiceData
{
    public ?Input $input = null;

    public function __construct(?Input $input = null)
    {
        if (is_array($input)) {
            $this->input = new Input($input);
        } elseif ($input instanceof Input) {
            $this->input = $input;
        } else {
            $this->input = new Input($input ? (array)$input : []);
        }
    }
}
