<?php

class Response{
    public $images = array();
    public $title;
    public $rates;
    public $average;
}

function getData($url){

    if($url) {

        if(explode('Restaurant_Review',$url)[0] !== 'https://www.tripadvisor.com.br/') {

            echo "Você deve informar uma URL válida de restaurante do tripadvisor!";

        } else {

            $response = new Response();

            $html = file_get_contents($url);

            //region Get restaurant images
            preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?data-mediaid=[\'"].*?[\'"].*?>|i', $html, $images);

            for($i=0; $i < count($images[0]); $i++){

                $xpath = new DOMXPath(@DOMDocument::loadHTML($images[0][$i]));
                $src = $xpath->evaluate("string(//img/@src)");
                array_push($response->images, $src);

            }

            foreach (array_keys($response->images, 'https://static.tacdn.com/img2/x.gif') as $key) {
                unset($response->images[$key]);
            }

            $response->images = array_values($response->images);
            //endregion

            //region Get restaurant name
            preg_match('/<li class=\"breadcrumb\">(.*?)<\/li>/s', $html, $title);

            $xpath = new DOMXPath(@DOMDocument::loadHTML($title[0]));
            $node = $xpath->evaluate("string(//li/node())");
            $response->title = utf8_decode($node);
            //endregion

            //region Get restaurant rates
            preg_match('/<span property=\"count\">(.*?)<\/span>/s', $html, $rates);

            $xpath = new DOMXPath(@DOMDocument::loadHTML($rates[0]));
            $node = $xpath->evaluate("string(//span/node())");
            if($node == '0'){
                $response->rates = 'Este restaurante ainda não possuí avaliações';
            } else {
                $response->rates = $node;
            }
            //endregion

            //region Get restaurant rates
            preg_match('/<span class=\"overallRating\">(.*?)<\/span>/s', $html, $average);

            $xpath = new DOMXPath(@DOMDocument::loadHTML($average[0]));
            $node = $xpath->evaluate("string(//span/node())");
            $response->average = $node;
            //endregion

            echo json_encode($response,JSON_PRETTY_PRINT);

        }

    } else {

        echo "Por favor, informe uma URL do tripadvisor";

    }

}

getData($_GET['url']);