<?php
/**
 * Created by PhpStorm.
 * User: JFog
 * Date: 7/12/2015
 * Time: 12:38 AM
 */
namespace common;

class Message
{
    protected $_breaker = '<br>';
    public $content = null;

    public function insert($text)
    {
        $this->content .= $text . $this->_breaker;
    }

    public function toHtml()
    {
        $html = '<body style="background: black;font-family: Arial;font-size: 11px;color: #ACAC9A;">';
        $html .= '<div align="center">';
        $html .= '<table><tr><td align="center">';
        $html .= '<div style="border: 1px dotted gray;padding: 10px 30px;margin-top:20px;color: #ACAC9A;font-family: Arial;font-size: 11px;font-weight: bold;">';
        $html .= $this->content;
        $html .= '</div>';
        $html .= '</td></tr></table>';
        $html .= '</div>';
        $html .= '</body>';

        return $html;
    }

    public function insertUrl($url)
    {
        $url = '[URL] : <a style="text-decoration: underline;" href="'. $url .'" target="_blank">' . $url . '</a><br>';
        $this->insert($url);
    }
}