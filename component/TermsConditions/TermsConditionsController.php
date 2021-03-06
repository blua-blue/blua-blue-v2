<?php

namespace Neoan3\Component\TermsConditions;

use Neoan3\Frame\BluaBlue;

/**
 * Class TermsConditionsController
 * @package Neoan3\Component\TermsConditions
 *
 * Generated by neoan3-cli for neoan3 v3.*
 */

class TermsConditionsController extends BluaBlue {
    /**
     * Route: TermsConditions
     */
    function init(): void
    {
        $this->renderer->includeElement('TermsConditions');
        $this->hook('main', 'termsConditions', []);
        $this->output();
    }

}
