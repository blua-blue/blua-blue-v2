<?php

namespace Neoan3\Component\{{name.pascal}};

use Neoan3\Frame\BluaBlue;

/**
 * Class {{name.pascal}}Controller
 * @package Neoan3\Component\{{name.pascal}}
 *
 * Generated by neoan3-cli for neoan3 v3.*
 */

class {{name.pascal}}Controller extends BluaBlue {
    /**
     * Route: {{name.pascal}}
     */
    function init(): void
    {
        $this->renderer->includeElement('{{name.pascal}}');
        $this->hook('main', '{{name.camel}}', []);
        $this->output();
    }

}
