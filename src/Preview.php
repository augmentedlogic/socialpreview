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

class Preview
{

    private $data = array();
    private $settings = array();
    private $is_https = true;

    function __construct($data, $settings) {
        $this->data = $data;
        $this->settings = $settings;
    }

    public function getTitle()
    {
        $title = "";
        if(isset($this->data['og:title'])) {
            $title = $this->data['og:title'];
        } elseif(isset($data['twitter:title']) && $this->settngs['allow_fallback_data'] == true) {
            $title = $data['Twitter:title'];
        } elseif(isset($data['title']) && $this->settngs['allow_fallback_data'] == true) {
            $title = $data['title'];
        }
        return $title;
    }

    public function getSiteName()
    {
        $sitename = "";
        if(isset($this->data['og:site_name'])) {
            return $sitename = $this->data['og:site_name'];
        }
        return $sitename;
    }

    public function getDescription()
    {
        $description = "";
        if(isset($this->data['og:description'])) {
            $description = $this->data['og:description'];
        } elseif(isset($data['twitter:description']) && $this->settngs['allow_fallback_data'] == true) {
            $description = $data['twitter:description'];
        } elseif(isset($data['description']) && $this->settngs['allow_fallback_data'] == true) {
            $description = $data['description'];
        }
        return $description;
    }

    public function getImage()
    {
        $image = "";
        if(isset($this->data['og:image'])) {
            $image = $this->data['og:image'];
        } elseif(isset($data['twitter:image']) && $this->settngs['allow_fallback_data'] == true) {
            $image = $data['twitter:image'];
        } elseif(isset($data['image:fallback']) && $this->settngs['allow_fallback_image'] == true) {
            $image = $data['image:fallback'];
        }
        return $image;
    }

    public function getDomain()
    {
        $domain = "";
        if(isset($this->data['domain'])) {
            $domain = $this->data['domain'];
        }
        return $domain;
    }

    public function getStatusCode()
    {
        $status_code = 0;
        if(isset($this->data['status_code'])) {
            $status_code = $this->data['status_code'];
        }
        return $status_code;
    }


    public function getAsArray()
    {
        return $this->data;
    }



}
