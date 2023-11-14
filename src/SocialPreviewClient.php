<?php
/**
  Copyright (c) 2023 Wolfgang Hauptfleisch/augmentedlogic <dev@augmentedlogic.com>

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files (the "Software"), to deal
  in the Software without restriction, including without limitation the rights
  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  copies of the Software, and to permit persons to whom the Software is
  furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
  SOFTWARE.
 **/
namespace com\augmentedlogic\socialpreview;

use \DOMDocument;

class SocialPreviewClient
{

    private $user_agent = "curl/7.64";
    private $timeout = 30;
    private $connect_timeout = 30;
    private $follow_location = true;
    private $allow_fallback_data = true;
    private $allow_fallback_image = false;
    private $headers = array();

    private function connect($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_ENCODING, "UTF-8" );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        // Timeouts
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $payload = array("body" => curl_exec($ch), "status_code" => curl_getinfo($ch, CURLINFO_HTTP_CODE));
        return $payload;
    }

    public function setTimeout(int $timeout)
    {
       $this->timeout = $timeout;
    }

    public function setConnectTimeout(int $connect_timeout)
    {
       $this->connect_timeout = $connect_timeout;
    }

    public function addHeader(String $header)
    {
       $this->headers[] = $header;
    }

    public function setUserAgent(String $user_agent)
    {
       $this->user_agent = $user_agent;
    }

    public function allowFallbackData(bool $allow_fallback_data )
    {
       $this->allow_fallback_data = $allow_fallback_data;
    }

    public function allowFallbackImage(bool $allow_fallback_image)
    {
       $this->allow_fallback_image = $allow_fallback_image;
    }

    public function getPreview($url): Preview
    {
        $meta_data = array();
        $settings = array("allow_fallback_data" => $this->allow_fallback_data,
                "allow_fallback_image" => $this->allow_fallback_image);


        if(filter_var($url, FILTER_VALIDATE_URL)) {

            libxml_use_internal_errors(true);
            $payload = $this->connect($url);
            $html = $payload['body'];
            $status_code = $payload['status_code'];

            $doc = new DOMDocument();
            $doc->loadHTML('<?xml encoding="utf-8" ?>' .$html);

            $meta_data['url'] = $url;

            foreach( $doc->getElementsByTagName('meta') as $meta ) {
                if($meta->getAttribute('property')) {
                    $meta_data[$meta->getAttribute('property')] = trim($meta->getAttribute('content'));
                }
                if($meta->getAttribute('name')) {
                    $meta_data[$meta->getAttribute('name')] = trim($meta->getAttribute('content'));
                }
            }

            $titles = $doc->getElementsByTagName('title');
            foreach ($titles as $title) {
                $meta_data['title'] = $title->nodeValue;
            }

            $parse = parse_url($url);
            $meta_data['domain'] =  $parse['host'];
            $meta_data['status_code'] =  $status_code;

            $imagepaths=array();
            $imageTags = $doc->getElementsByTagName('img');
            foreach($imageTags as $tag) {
                $imagepaths[]=$tag->getAttribute('src');
            }
            if(isset($imagepaths[0])) {
                if(substr( $imagepaths[0], 0, 4 ) != "data") {
                    $meta_data["image:fallback"] = $imagepaths[0];
                }
            }


        } else {
            trigger_error("SocialPreviewClient: Invalid URL", E_USER_WARNING);
        }

        return new Preview($meta_data, $settings);
    }

}

