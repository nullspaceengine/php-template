<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Nullspaceengine\PhpTemplate\PhpTemplate;

class PhpTemplateTest extends TestCase {
  private ?PhpTemplate $pt;

  protected function setUp() : void {
    $this->pt = new PhpTemplate();
    $this->pt->useTheme('test', getcwd() . "/tests/themes/test");
  }

  protected function tearDown() : void {
    $this->pt = NULL;
  }

  /**
   * Test the page rendering to output.
   */
  #[TestDox('HTML is output')]
  public function testPage() : void {
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

    $this->pt->render($page);

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

  /**
   * Test the build method's html output.
   */
  #[TestDox('Basic Templates are built with provided variables.')]
  public function testBuildContent() : void {
    $content_array = [
      'content 2' => [
      '#type' => 'content',
      '#vars' => [
        'content' => 'Some Dashboard Stuff Goes Here.'
        ],
      ],
    ];

    $content = $this->pt->build($content_array);
    $expected_content = "<div>Some Dashboard Stuff Goes Here.</div>\n";
    $this->assertEquals($content, $expected_content);
  }

  /**
   * Test build with a more complicated set of structured data.
   */
  #[TestDox('More complicated Templates are built with provided variables.')]
  public function testBuildHeading() : void {
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

      $content = $this->pt->build($content_array);
      $expected_content = "<h$level>Some Heading Stuff Goes Here.</h$level>\n";
      $this->assertEquals($content, $expected_content);
    }

  }

  /**
   * Test the CSS aggregation.
   */
  #[TestDox('Styles are aggregated.')]
  public function testStyles() : void {
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

    $html = $this->pt->build($page);

    $styles = $this->pt->getPageStyles();
    $expected_styles = '<style>
body {
  background-color: black;
  color: white;
}
    </style>
';

    $this->assertEquals($styles, $expected_styles);
  }

  /**
   * Test the JS aggregation.
   */
  #[TestDox('Javascript is aggregated.')]
  public function testScripts() : void {
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

    $html = $this->pt->build($page);

    $scripts = $this->pt->getPageScripts();
    $expected_scripts = '<script>
alert("hello world!");
    </script>
';

    $this->assertEquals($expected_scripts, $scripts);
  }
}
