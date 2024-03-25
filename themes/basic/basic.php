<?php

function template_page()
{
    return [
        'title' => 'some title',
        'content' => 'content',
    ];
}

function basic_page_preprocess($vars)
{
    // Do something about vars.
}
