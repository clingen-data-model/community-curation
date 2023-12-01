<?php

namespace App\Traits;

use League\HTMLToMarkdown\HtmlConverter;
use Parsedown;

trait TranscodesHtmlToMarkdown
{
    /**
     * Parsdown instance for invite_message accessor.
     */
    private $parsdown;

    /**
     * Html-to-markdown converter.
     */
    private $converter;

    public function htmlToMarkdown($value)
    {
        return $this->getConverter()->convert($value);
    }

    public function markdownToHtml($value)
    {
        return $this->getParsedown()->text($value);
    }

    private function getConverter()
    {
        if (! $this->converter) {
            $this->converter = new HtmlConverter(['strip tags' => true, 'remove_nodes' => 'script']);
        }

        return $this->converter;
    }

    protected function getParsedown()
    {
        if (! $this->parsedown) {
            $this->parsedown = new Parsedown();
            $this->parsedown->setSafeMode(true);
        }

        return $this->parsedown;
    }
}
