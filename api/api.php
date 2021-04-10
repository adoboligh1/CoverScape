<?php

header("Access-Control-Allow-Origin: https://coverscape.ml");
//header("Access-Control-Allow-Origin: *");
header("Vary: Origin");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Accept, Referer, User-Agent, Content-Type, Origin, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8;");

require_once "vendor/autoload.php";

use \GuzzleHttp\Exception\RequestException;

if (isset($_GET["mode"])) {
    if ($_GET["mode"] == "redditPost") {
        if (isset($_GET["id"])) {
            $body = false;
            $guzzle = new \GuzzleHttp\Client([
                'base_uri'    => 'https://www.reddit.com/',
                'http_errors' => true,
                'timeout'     => 20
            ]);
            try {
                $body = $guzzle->get($_GET["id"] . "/.json")->getBody();
            } catch (RequestException $e) {
                $errorCode = $e->getCode();
                $errorMessage = $e->getMessage();
                if ($e->hasResponse()) {
                    $httpMessage = \GuzzleHttp\Psr7\str($e->getResponse());
                }
            }

            if ($body) {
                print($body);
            }
        } else {
            $body = (object) [
                'kind' => 'Error',
                'data' => (object)[],
                'error_message' => "Empty post ID. Insert ID and try again."
            ];
            print(json_encode($body));
        }
    } /*else if (isset($_GET['img'])) {
        if ($_GET["ext"] == "png") {
            header('Content-Type: image/png');
            $im = imagecreatefrompng('https://i.redd.it/' . $_GET["img"] . "." . $_GET["ext"]);
            imagepng($im);
            imagedestroy($im);
        } else {
            header('Content-Type: image/jpeg');
            $im = imagecreatefromjpeg('https://i.redd.it/' . $_GET["img"] . "." . $_GET["ext"]);
            imagejpeg($im);
            imagedestroy($im);
        }
    } */ else if ($_GET["mode"] == "redditSearch") {
        if (isset($_GET["q"])) {
            $body = false;
            $after = "";
            isset($_GET["after"]) ? $after = $_GET["after"] : null;
            $guzzle = new \GuzzleHttp\Client([
                'base_uri'    => 'https://www.reddit.com/r/AlbumArtPorn/',
                'http_errors' => true,
                'timeout'     => 20
            ]);
            try {
                $body = $guzzle->get('search.json', [
                    'query' => [
                        'restrict_sr' => 'true',
                        'q' => $_GET["q"],
                        'after' => $after
                    ]
                ])->getBody();
            } catch (RequestException $e) {
                $errorCode = $e->getCode();
                $errorMessage = $e->getMessage();
                if ($e->hasResponse()) {
                    $httpMessage = \GuzzleHttp\Psr7\str($e->getResponse());
                }
            }

            if ($body) {
                print($body);
            }
        } else {
            $body = (object) [
                'kind' => 'Error',
                'data' => (object)[],
                'error_message' => "Empty query. Insert query and try again."
            ];
            print(json_encode($body));
        }
    } else if ($_GET["mode"] == "lastfmSearch") {
        if (isset($_GET["q"])) {
            $body = false;
            $page = "";
            isset($_GET["page"]) ? $page = $_GET["page"] : null;
            $guzzle = new \GuzzleHttp\Client([
                'base_uri'    => 'https://ws.audioscrobbler.com/2.0/',
                'http_errors' => true,
                'timeout'     => 20
            ]);
            try {
                $body = $guzzle->get("", [
                    'query' => [
                        'method' => 'album.search',
                        'album' => $_GET["q"],
                        'api_key' => "008dd2405a84da9505c1b2dd4dffd5e4",
                        'format' => "json",
                        'page' => $page
                    ]
                ])->getBody();
            } catch (RequestException $e) {
                $errorCode = $e->getCode();
                $errorMessage = $e->getMessage();
                if ($e->hasResponse()) {
                    $httpMessage = \GuzzleHttp\Psr7\str($e->getResponse());
                }
            }

            if ($body) {
                print($body);
            }
        } else {
            $body = (object) [
                'kind' => 'Error',
                'data' => (object)[],
                'error_message' => "Empty query. Insert query and try again."
            ];
            print(json_encode($body));
        }
    } else if ($_GET["mode"] == "lastfmAlbum") {
        $body = false;
        if (isset($_GET["title"]) && isset($_GET["artist"]) && isset($_GET["imgUrl"])) {
            $body = array("title" => $_GET["title"], "artist" => $_GET["artist"], "imgUrl" => $_GET["imgUrl"]);
        } else {
            $body = (object) [
                'kind' => 'Error',
                'data' => (object)[],
                'error_message' => "One or more parameters were empty. Please check the request and try again."
            ];
        }

        if ($body) {
            print(json_encode($body));
        }
    }
    else{
        $body = (object) [
            'kind' => 'Error',
            'data' => (object)[],
            'error_message' => "Invalid mode. Please check the request and try again."
        ];
        print(json_encode($body));
    }
} else {
    $body = (object) [
        'kind' => 'Error',
        'data' => (object)[],
        'error_message' => "The necessary parameters were not found, or were empty. Please check the request and try again."
    ];
    print(json_encode($body));
}
