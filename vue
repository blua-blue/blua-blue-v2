<?php

use Neoan3\Apps\Ops;
use Neoan3\Apps\Template;

require_once 'vendor/autoload.php';

if(isset($argv[1])){
    $name = $argv[1];
    exec('neoan3 new component ' . $name . ' -t:route -v:yes');
    echo "generating...";
    usleep(500);
    $path = path . '/component/' . Ops::toPascalCase($name) . '/';
    $params = [
        'name' => $name,
        'name.lower' => mb_strtolower($name),
        'name.pascal' => Ops::toPascalCase($name),
        'name.camel' => Ops::toCamelCase($name),
        'name.kebab' => Ops::toKebabCase($name)
    ];
    unlink($path . Ops::toCamelCase($name) .'.view.html');

    file_put_contents($path . Ops::toPascalCase($name) . '.vue', Template::embraceFromFile('_template/vue.js', $params));

}