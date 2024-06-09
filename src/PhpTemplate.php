<?php

namespace Nullspaceengine\PhpTemplate;

class PhpTemplate
{
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
     * @var array
     *
     * A structured array of assets.
     */
    protected array $assets = [
        "js" => [],
        "css" => [],
    ];

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
    public function build(array $build): string
    {
        require_once "{$this->themePath}/{$this->theme}.php";

        $getType = function (array $build) {
            return $build["#type"];
        };

        $renderTemplate = function (
            string $type,
            string $template,
            array $vars
        ): string {
            // The vars that are allowed byt the template callback.
            $available_vars = [];
            if (function_exists("template_$type")) {
                if ($template_variables = call_user_func("template_$type")) {
                    $available_vars = array_keys($template_variables);

                    // Process the assets.
                    if (array_key_exists("#assets", $template_variables)) {
                        foreach (
                            $template_variables["#assets"]
                            as $asset_type => $asset_array
                        ) {
                            switch ($asset_type) {
                                case "css":
                                    $asset_array = array_map(function (
                                        $stylesheet_path
                                    ) use ($type) {
                                        return "{$this->themePath}/templates/$type/$stylesheet_path";
                                    }, $asset_array);

                                    $this->assets["css"] = array_merge(
                                        $this->assets["css"],
                                        $asset_array
                                    );
                                    break;
                                case "js":
                                    $asset_array = array_map(function (
                                        $script_path
                                    ) use ($type) {
                                        return "{$this->themePath}/templates/$type/$script_path";
                                    }, $asset_array);

                                    $this->assets["js"] = array_merge(
                                        $this->assets["js"],
                                        $asset_array
                                    );
                                    break;
                            }
                        }
                    }
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
                call_user_func_array("{$this->theme}_{$type}_preprocess", [
                    &$scoped_variables,
                ]);
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

        $rendered_html = "";

        foreach ($build as $render_array) {
            $vars = $render_array["#vars"];
            $template = "{$this->themePath}/templates/{$getType(
                $render_array
            )}/{$getType($render_array)}.tpl.php";

            $rendered_html .= $renderTemplate(
                $getType($render_array),
                $template,
                $vars
            );
        }

        return $rendered_html;
    }

    /**
     * Get the inline aggregated styles.
     */
    public function getPageStyles(): string
    {
        $styles = [];
        foreach ($this->assets["css"] as $stylesheet) {
            $styles[] = file_get_contents($stylesheet);
        }
        return "<style>\n" . implode("\n", $styles) . "    </style>\n";
    }

    /**
     * Get the inline aggregated scripts.
     */
    public function getPageScripts(): string
    {
        $scripts = [];
        foreach ($this->assets["js"] as $script) {
            $scripts[] = file_get_contents($script);
        }
        return "<script>\n" . implode("\n", $scripts) . "    </script>\n";
    }

    /**
     * Prints the render array after it gets parsed into html by build.
     *
     * @param $build
     *  The render array.
     *
     * @return void
     */
    public function render($build): void
    {
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
    public function useTheme(string $theme, string $theme_path = null): void
    {
        $this->theme = $theme;
        $this->themePath = is_null($theme_path)
            ? __DIR__ . "/../themes/$theme"
            : $theme_path;
    }
}
