<?php
/**
 * Colour Picker Element
 *
 * @package     Joomla.Plugin
 * @subpackage  Fabrik.element.colourpicker
 * @copyright   Copyright (C) 2005-2020  Media A-Team, Inc. - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access
use Joomla\CMS\Profiler\Profiler;

defined('_JEXEC') or die('Restricted access');

/**
 * Plugin element to render colour picker
 *
 * @package     Joomla.Plugin
 * @subpackage  Fabrik.element.colorpicker
 * @since       3.0
 */
class PlgFabrik_ElementEmundus_colorpicker extends PlgFabrik_Element
{

    public function render($data, $repeatCounter = 0)
    {
        JHTML::stylesheet('plugins/fabrik_element/emundus_colorpicker/css/emundus_colorpicker.css');

        $properties = $this->inputProperties($repeatCounter);

        if (is_array($this->getFormModel()->data))
        {
            $data = $this->getFormModel()->data;
        }
        $value = $this->getValue($data, $repeatCounter);

        // On récupère les paramètres du plugin
        $params = $this->getParams();
        // On récupère les valeurs pour chaque personnalisation :
        $firstColorPalette = $params->get('color1', '#db4141');
        $secondColorPalette = $params->get('color2', '#b266e8');
        $thirdColorPalette = $params->get('color3', '#344aba');
        $fourthColorPalette = $params->get('color4', '#44c1cf');
        $fifthColorPalette = $params->get('color5', '#2fbd66');
        $sixthColorPalette = $params->get('color6', '#e0b534');
        $seventhColorPalette = $params->get('color7', '#f26e09');
        $hiddenColor = $params->get('hidden_color', '0');

        $layout = $this->getLayout('form');
        $layoutData = new stdClass;
        $layoutData->attributes = $properties;
        $layoutData->attributes['value'] = $value;

        // On envoie les données à la vue
        $layoutData->firstColorPalette = $firstColorPalette;
        $layoutData->secondColorPalette = $secondColorPalette;
        $layoutData->thirdColorPalette = $thirdColorPalette;
        $layoutData->fourthColorPalette = $fourthColorPalette;
        $layoutData->fifthColorPalette = $fifthColorPalette;
        $layoutData->sixthColorPalette = $sixthColorPalette;
        $layoutData->seventhColorPalette = $seventhColorPalette;
        $layoutData->hiddenColor = $hiddenColor;

        return $layout->render($layoutData);
    }

    public function renderListData($data, stdClass &$thisRow, $opts = array())
    {
        $profiler = Profiler::getInstance('Application');
        JDEBUG ? $profiler->mark("renderListData: {$this->element->plugin}: start: {$this->element->name}") : null;

        $data              = FabrikWorker::JSONtoData($data, true);
        $layout            = $this->getLayout('list');
        $displayData       = new stdClass;
        $displayData->data = $data;

        return $layout->render($displayData);
    }

    public function elementJavascript($repeatCounter)
    {
        $params = $this->getParams();
        $id = $this->getHTMLId($repeatCounter);
        $opts = $this->getElementJSOptions($repeatCounter);

        // Pass variables to JS
        $opts->parameter_1 = $params->get('parameter_1', 0);

        return array('FbEmundusColorpicker', $id, $opts);
    }

}
