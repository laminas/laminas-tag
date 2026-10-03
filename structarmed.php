<?php

declare(strict_types=1);

use Boundwize\StructArmed\Architecture;
use Boundwize\StructArmed\Preset\Preset;

return Architecture::define()
    ->withPresets(Preset::PER(), Preset::CODEQUALITY())
    ->layer('Exception', 'src/Exception')
    ->layer('Item', [
        'src/Item.php',
        'src/ItemList.php',
        'src/TaggableInterface.php',
    ])
    ->layer('DecoratorException', 'src/Cloud/Decorator/Exception')
    ->layer('Decorator', 'src/Cloud/Decorator', 'src/Cloud/Decorator/Exception')
    ->layer('Cloud', ['src/Cloud.php', 'src/Cloud'], 'src/Cloud/Decorator')
    ->ruleset([
        'Exception'          => [],
        'Item'               => ['Exception'],
        'DecoratorException' => ['Exception'],
        'Decorator'          => ['+DecoratorException', '+Item'],
        'Cloud'              => ['+Decorator'],
    ]);
