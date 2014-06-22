<?php

Class ScalaParser extends AutoSuggestParser {

    public $display_name = "Scala";

    public $short_name = "scala";

    public $icon = "icon.png";

    protected function addResults($cmds) {
        foreach ($cmds as $cmd) {
            $title = $cmd['title'];
            $url = $cmd['url'];
            $description = $cmd['description'];
            $this->addResult($url, $title, $description);
        }
    }

    public function update() {
        $base_url = 'http://www.scala-lang.org/api/current/';

        // Create a new DOM Document to hold our webpage structure
        $xml = new DOMDocument();

        // Load the url's contents into the DOM (the @ supresses any errors from invalid XML)
        @$xml->loadHTMLFile($base_url);

        $cmds = array();
        $string = '';
        $links = $xml->getElementsByTagName('a');

        //Loop through each <a> tag in the dom and add it to the link array
        foreach ($links as $link) {

            if ($link->getAttribute('class') == 'tplshow') {
                if ($link->getAttribute('href')) {
                    $title = null;
                    $spans = $link->getElementsByTagName('span');
                    foreach ($spans as $span) {
                        if ($span->getAttribute('class') == 'tplLink') {
                            $title = $span->nodeValue;
                        }
                    }
                    if (!$title) {
                        continue;
                    }
                    $cmds[] = array( 'url' => $base_url . $link->getAttribute( 'href' ), 'title' => $title, 'description' => null );
                }
            }
        }

        $this->addResults($cmds);

        $this->save();
    }

}
