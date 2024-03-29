<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Nullspaceengine\PhpTemplate\PhpTemplate;

class PhpTemplateTest extends TestCase {

  public function testPage() : void {
    $pt = new PhpTemplate();
    $pt->useTheme('basic');

    $page = [
      'page' => [
        '#type' => 'page',
        '#vars' => [
          'title' => 'Dashboard',
          'content' => [
            'content' => [
              '#type' => 'content',
              '#vars' => [
                'content' => 'Some Dashboard Stuff Goes Here.'
              ],
            ],
            'content 2' => [
              '#type' => 'content',
              '#vars' => [
                'content' => 'Some Dashboard Stuff Goes Here.'
              ],
            ],
          ],
        ],
      ],
    ];

    $pt->render($page);

    $this->expectOutputString('<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <style>
body {
  background-color: black;
  color: white;
}
    </style>
</head>

<body>
    <div>Some Dashboard Stuff Goes Here.</div>
<div>Some Dashboard Stuff Goes Here.</div>
    <script>
alert("hello world!");
    </script>
</body>

</html>
');
  }

  public function testBuildContent() : void {
    $pt = new PhpTemplate();
    $pt->useTheme('basic');

    $content_array = [
      'content 2' => [
      '#type' => 'content',
      '#vars' => [
        'content' => 'Some Dashboard Stuff Goes Here.'
        ],
      ],
    ];

    $content = $pt->build($content_array);
    $expected_content = "<div>Some Dashboard Stuff Goes Here.</div>\n";
    $this->assertEquals($content, $expected_content);
  }

  public function testBuildHeading() : void {
    $pt = new PhpTemplate();
    $pt->useTheme('basic');

    foreach (range(1, 6) as $level) {
      $content_array = [
        'content 2' => [
          '#type' => 'heading',
          '#vars' => [
            'level' => $level,
            'text' => 'Some Heading Stuff Goes Here.',
          ],
        ],
      ];

      $content = $pt->build($content_array);
      $expected_content = "<h$level>Some Heading Stuff Goes Here.</h$level>\n";
      $this->assertEquals($content, $expected_content);
    }

  }


  public function testStyles() : void {
    $pt = new PhpTemplate();
    $pt->useTheme('basic');

    $page = [
      'page' => [
        '#type' => 'page',
        '#vars' => [
          'title' => 'Dashboard',
          'content' => [
            'content' => [
              '#type' => 'content',
              '#vars' => [
                'content' => 'Some Dashboard Stuff Goes Here.'
              ],
            ],
            'content 2' => [
              '#type' => 'content',
              '#vars' => [
                'content' => 'Some Dashboard Stuff Goes Here.'
              ],
            ],
          ],
        ],
      ],
    ];

    $html = $pt->build($page);

    $styles = $pt->getPageStyles();
    $expected_styles = '<style>
body {
  background-color: black;
  color: white;
}
    </style>
';

    $this->assertEquals($styles, $expected_styles);
  }

  public function testScripts() : void {
    $pt = new PhpTemplate();
    $pt->useTheme('basic');

    $page = [
      'page' => [
        '#type' => 'page',
        '#vars' => [
          'title' => 'Dashboard',
          'content' => [
            'content' => [
              '#type' => 'content',
              '#vars' => [
                'content' => 'Some Dashboard Stuff Goes Here.'
              ],
            ],
            'content 2' => [
              '#type' => 'content',
              '#vars' => [
                'content' => 'Some Dashboard Stuff Goes Here.'
              ],
            ],
          ],
        ],
      ],
    ];

    $html = $pt->build($page);

    $scripts = $pt->getPageScripts();
    $expected_scripts = '<script>
alert("hello world!");
    </script>
';

    $this->assertEquals($expected_scripts, $scripts);
  }
}
