<?php namespace common\parser;

abstract class Parser
{
    public $html;

    public function __construct($html)
    {
        $this->html = $html;
    }

    public function find($explain)
    {
        $children = $this->html->find($explain);
        if (!empty($children)) {
            return $children;
        }
        return null;
    }
}