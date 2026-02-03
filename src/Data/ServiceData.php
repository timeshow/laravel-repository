<?php
namespace TimeShow\Repository\Data;

class ServiceData
{
    public ?Input $input;

    public function __construct(?Input $input = null)
    {
        $this->input = $input ?? new Input(request()->all());
    }
}
