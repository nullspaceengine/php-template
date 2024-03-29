<?php

function template_page() : array{
    return [
        'title' => '',
        'content' => '',
    ];
}

function template_content() : array {
  return [
    'content' => '',
  ];
}

function template_heading() : array {
  return [
    'level' => '',
    'text' => '',
  ];
}

function basic_page_preprocess(&$vars) : void {
}

function basic_content_preprocess(&$vars) : void {
}
