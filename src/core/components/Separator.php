<?php

namespace app\src\core\components;

class Separator implements ComponentInterface
{

    private string $color;
    private string $width;
    private string $height;
    private string $orientation;

    public function __construct(array $params)
    {
        $this->color = $params['color'] ?? 'zinc-200';
        $this->orientation = $params['orientation'] ?? 'horizontal';

        if ($this->orientation === 'vertical') {
            $this->width = $params['width'] ?? '[1px]';
            $this->height = $params['height'] ?? 'full';
        } else {
            $this->width = $params['width'] ?? 'full';
            $this->height = $params['height'] ?? '[1px]';
        }
        $this->render();
    }

    public function render(): void
    {
        echo <<<EOT
<div class="w-$this->width h-$this->height bg-$this->color"></div>
EOT;
    }
}