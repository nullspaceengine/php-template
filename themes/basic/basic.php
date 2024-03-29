<?php

function template_page() : array{
    return [
      'title' => '',
      'content' => '',
      '#assets' => [
        'js' => [
          'scripts.js',
        ],
        'css' => [
          'styles.css',
        ],
      ],
    ];
}

function template_content() : array {
  return [
    'content' => '',
    '#assets' => [],
  ];
}

function template_heading() : array {
  return [
    'level' => '',
    'text' => '',
    '#assets' => [],
  ];
}

function basic_page_preprocess(&$vars) : void {
}

function basic_content_preprocess(&$vars) : void {
}
