<?php

use Pheanstalk\Pheanstalk,
    Sunra\PhpSimple\HtmlDomParser;

class GrablinksTask extends \Phalcon\CLI\Task {

    public function mainAction() {
        $appid = "570";
        $maxPages = 5000;

        $page = Grabber::grab("http://steamcommunity.com/market/search?appid=" . $appid);
        $page_count = $this->getPageCount($page);
        echo "Total pages: $page_count. Parsing first page\n";
        $page_count = ($page_count < $maxPages) ? $page_count : $maxPages;
        for ($i = 1; $i<=$page_count; $i++) {
            echo "Parsing page $i of $page_count\n";
            $page = Grabber::grab("http://steamcommunity.com/market/search/render/?query=&start=" . $i*100 . "&count=100&search_descriptions=0&sort_column=quantity&sort_dir=desc&appid=" . $appid);
            $page = json_decode($page);
            $this->parseLinks($page->results_html);
        }
    }

    private function parseLinks($page) {
        $pheanstalk = new Pheanstalk('localhost');
        $html = HtmlDomParser::str_get_html($page);
        $links = $html->find(".market_listing_row_link");
        foreach ($links as $link) {
            echo $link->href . "\n";
            $pheanstalk->put(json_encode(['link' => $link->href]));
        }
    }

    private function getPageCount($page) {
        $html = HtmlDomParser::str_get_html($page);
        $total = $html->getElementById("searchResults_total");
        $total = (int) str_replace(",", "", $total->innertext);
        $pages = ceil($total/100);
        return $pages;
    }

}