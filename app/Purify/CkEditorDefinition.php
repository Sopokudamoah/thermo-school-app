<?php

namespace App\Purify;

use HTMLPurifier_HTMLDefinition;
use Stevebauman\Purify\Definitions\Definition;

class CkEditorDefinition implements Definition
{
    /**
     * Adds elements and attributes to the HTML purifier
     * definition required by the ckeditor editor.
     *
     * @param HTMLPurifier_HTMLDefinition $def
     */
    public static function apply(HTMLPurifier_HTMLDefinition $def): void
    {
        $def->addElement('figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common');
        $def->addAttribute('figure', 'class', 'Text');
    }
}
