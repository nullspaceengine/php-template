<?php

namespace Nullspaceengine\PhpTemplate;

class PhpTemplate
{
    protected string $theme;

    public function build(array $build)
    {
        $getType = function (array $build) {
            return $build['#type'];
        };

        $renderTemplate = function (string $template, array $vars) {
            foreach ($vars as $name => $value) {
                ${$name} = $value;
            }

            return require $template;
        };

        $vars = $build['#vars'];
        $template = __DIR__ . "/../themes/{$this->theme}/templates/{$getType($build)}.tpl.php";

        return $renderTemplate($template, $vars);
    }

    public function render($build): void
    {
        require_once(__DIR__ . "/../themes/{$this->theme}/{$this->theme}.php");
        $this->build($build);
    }

    public function useTheme(string $theme): void
    {
        $this->theme = $theme;
    }
}
