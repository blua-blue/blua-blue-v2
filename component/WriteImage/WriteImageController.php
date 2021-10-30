<?php

namespace Neoan3\Component\WriteImage;

use Neoan3\Frame\BluaBlue;

/**
 * Class WriteImageController
 * @package Neoan3\Component\WriteImage
 *
 * Generated by neoan3-cli for neoan3 v3.*
 */

class WriteImageController extends BluaBlue {
    /**
     * Route: WriteImage
     */
    function init(): void
    {
        $this->renderer->includeElement('WriteImage');
        $this->hook('main', 'writeImage', []);
        $this->output();
    }

}
