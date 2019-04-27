<?php

namespace InPlaceEditing\View\Helper;

use Cake\View\Helper;

/*
 * ----------------------------------------------------------------------------
 * Package:     CakePHP InPlaceEditing Plugin
 * Version:     0.0.1
 * Date:        2012-12-31
 * Description: CakePHP plugin for in-place-editing functionality of any
 *				form element.
 * Author:      Karey H. Powell
 * Author URL:  http://kareypowell.com/
 * Repository:  http://github.com/kareypowell/CakePHP-InPlace-Editing
 * ----------------------------------------------------------------------------
 * Copyright (c) 2012 Karey H. Powell
 * Dual licensed under the MIT and GPL licenses.
 * ----------------------------------------------------------------------------
 */

class InPlaceEditingHelper extends Helper
{

    public $helpers = ['Html'];

    /*
     * Returns a script which contains a html element (type defined in a parameter) with the field contents.
     * And includes a script required for the inplace update ajax request logic.
     */
    public function input($modelName, $fieldName, $id, $settings = null)
    {
        $value         = $this::extractSetting($settings, 'value', '');
        $actionName    = $this::extractSetting($settings, 'actionName', 'inPlaceEditing');
        $type          = $this::extractSetting($settings, 'type', 'textarea');
        $cancelText    = $this::extractSetting($settings, 'cancelText', 'Cancel');
        $submitText    = $this::extractSetting($settings, 'submitText', 'Save');
        $toolTip       = $this::extractSetting($settings, 'toolTip', 'Click to edit.');
        $containerType = $this::extractSetting($settings, 'containerType', 'div');

        $elementID = 'inplace_'.$modelName.'_'.$fieldName.'_'.$id;
        $input     = '<'.$containerType.' id="'.$elementID.'" class="in_place_editing">'.$value.'</'.$containerType.'>';
        $script    = "$(function(){
                        $('#$elementID').editable(
                            '..$actionName/$id',
                            {
                                name      : '$fieldName',
                                type      : '$type',
                                cancel    : '$cancelText',
                                submit    : '$submitText',
                                tooltip   : '$toolTip',
                                rows      : 5
                            }
                        );
                    });";

        $this->Html->scriptBlock($script, ['block' => true]);

        return $input;
    }

    /*
     * Extracts a setting under the provided key if possible, otherwise, returns a provided default value.
     */
    protected static function extractSetting($settings, $key, $defaultValue = '')
    {
        if ( ! $settings && empty($settings)) {
            return $defaultValue;
        }

        if (isset($settings[$key])) {
            return $settings[$key];
        } else {
            return $defaultValue;
        }
    }

}