<?php

namespace App\Services;

//use Michelf\MarkdownExtra;
use Michelf\SmartyPants;
use YuanChao\Editor\EndaEditor;

class Markdowner
{
    public function toHTML($text)
    {
//        $text = $this->preTransformText($text);
//        $text = MarkdownExtra::defaultTransform($text);
//        $text = SmartyPants::defaultTransform($text);
//        $text = $this->postTransformText($text);

        $text = $this->preTransformText($text);
        $text = EndaEditor::MarkDecode($text);
        $text = SmartyPants::defaultTransform($text);
        $text = $this->postTransformText($text);

        return $text;
    }

    protected function preTransformText($text)
    {
        return $text;
    }

    protected function postTransformText($text)
    {
        return $text;
    }
}