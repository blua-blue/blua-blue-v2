<?php

namespace Neoan3\Component\WriteMeta;

use Neoan3\Frame\BluaBlue;

/**
 * Class WriteMetaController
 * @package Neoan3\Component\WriteMeta
 *
 * Generated by neoan3-cli for neoan3 v3.*
 */

class WriteMetaController extends BluaBlue {
    /**
     * Route: WriteMeta
     */
    function init(): void
    {
        $this->renderer->includeElement('WriteMeta');
        $this->hook('main', 'writeMeta', []);
        $this->output();
    }

}
