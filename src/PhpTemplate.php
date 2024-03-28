<?php

namespace Nullspaceengine\PhpTemplate;

class PhpTemplate {

  /**
   * @var string
   *
   * The Name of the theme.
   */
  protected string $theme;

  /**
   * @var string
   *
   * The path to the theme.
   */
  protected string $themePath;

  /**
   * Do the heavy lifting of parsing the tpl.php files and giving them correct
   * variable contexts.
   *
   * @param array $build
   *   The render array.
   *
   * @return string
   *   The rendered HTML.
   */
  public function build(array $build) : string {
    require_once "{$this->themePath}/{$this->theme}.php";

    $getType = function (array $build) {
      return $build['#type'];
    };

    $renderTemplate = function (string $type, string $template, array $vars): string {
      // The vars that are allowed byt the template callback.
      $available_vars = [];
      if (function_exists("template_$type")) {
        if ($template_variables = call_user_func("template_$type")) {
          $available_vars = array_keys($template_variables);
        }
      }

      $scoped_variables = [];
      foreach ($vars as $name => $value) {
        // Parse any render arrays in the render array.
        if (is_array($value)) {
          $value = $this->build($value);
        }

        // Limit the scoped variables to the available variables.
        if (in_array($name, $available_vars)) {
          $scoped_variables[$name] = $value;
        }
      }

      // Allow some preprocessing to happen.
      if (function_exists("{$this->theme}_{$type}_preprocess")) {
        call_user_func_array("{$this->theme}_{$type}_preprocess", [&$scoped_variables]);
      }

      // Put the variables into the current scope.
      foreach ($scoped_variables as $name => $value) {
        ${$name} = $value;
      }

      ob_start();
      require $template;
      $element_html = ob_get_contents();
      ob_end_clean();
      return $element_html;
    };

    $rendered_html = '';

    foreach ($build as $render_array) {
      $vars = $render_array['#vars'];
      $template = "{$this->themePath}/templates/{$getType($render_array)}.tpl.php";

      $rendered_html .= $renderTemplate($getType($render_array), $template, $vars);
    }


    return $rendered_html;
  }

  public function render($build) : void {
    echo $this->build($build);
  }

  /**
   * Sets the theme that is to be used by the renderer.
   *
   * @param string $theme
   *  The name of the theme to use.
   * @param string|null $theme_path
   *  The path for the theme.
   *
   * @return void
   */
  public function useTheme(string $theme, string $theme_path = NULL) : void {
    $this->theme = $theme;
    $this->themePath = (is_null($theme_path)) ? __DIR__ . "/../themes/$theme" : $theme_path;
  }
}
