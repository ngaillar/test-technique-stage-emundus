<?php
/**
 * Slideshow vizualization: view
 *
 * @package     Joomla.Plugin
 * @subpackage  Fabrik.visualization.slideshow
 * @copyright   Copyright (C) 2005-2020  Media A-Team, Inc. - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

jimport('joomla.application.component.view');

/**
 * Fabrik Slideshow Viz HTML View
 *
 * @package     Joomla.Plugin
 * @subpackage  Fabrik.visualization.timeline
 * @since       3.0
 */

class FabrikViewSlideshow extends HtmlView
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 */

	public function display($tpl = 'default')
	{
		$app = Factory::getApplication();
		$input = $app->input;
		$srcs = FabrikHelperHTML::framework();
		$model = $this->getModel();
		$usersConfig = ComponentHelper::getParams('com_fabrik');
		$model->setId($input->getInt('id', $usersConfig->get('visualizationid', $input->getInt('visualizationid', 0))));
		$this->row = $model->getVisualization();

		if (!$model->canView())
		{
			echo Text::_('JERROR_ALERTNOAUTHOR');

			return false;
		}

		$this->js = $this->get('JS');
		$viewName = $this->getName();
		$params = $model->getParams();
		$this->params = $params;
		$pluginManager = BaseDatabaseModel::getInstance('Pluginmanager', 'FabrikFEModel');
		$plugin = $pluginManager->getPlugIn('slideshow', 'visualization');
		$this->showFilters = $model->showFilters();
		$this->filters = $this->get('Filters');
		$this->filterFormURL = $this->get('FilterFormURL');
		$this->params = $model->getParams();
		$this->containerId = $this->get('ContainerId');
		$this->slideData = $model->getImageJSData();
		$srcs['FbListFilter'] = 'media/com_fabrik/js/listfilter.js';

		if ($this->get('RequiredFiltersFound'))
		{
			$srcs['Slick'] = 'components/com_fabrik/libs/slick/slick.js';
			HTMLHelper::stylesheet('components/com_fabrik/libs/slick/slick.css');
			HTMLHelper::stylesheet('components/com_fabrik/libs/slick/slick-theme.css');
			$srcs['SlideShow'] = 'plugins/fabrik_visualization/slideshow/slideshow.js';
		}

		FabrikHelperHTML::slimbox();
		FabrikHelperHTML::iniRequireJs($model->getShim());
		FabrikHelperHTML::script($srcs, $this->js);

		//FabrikHelperHTML::slimbox();

		$tpl = 'bootstrap';
		$tpl = $params->get('slideshow_viz_layout', $tpl);
		$tmplpath = $model->pathBase . 'slideshow/views/slideshow/tmpl/' . $tpl;
		$this->_setPath('template', $tmplpath);
		FabrikHelperHTML::stylesheetFromPath('plugins/fabrik_visualization/slideshow/views/slideshow/tmpl/' . $tpl . '/template.css');
		FabrikHelperHTML::stylesheetFromPath('plugins/fabrik_visualization/slideshow/views/slideshow/tmpl/' . $tpl . '/custom.css');
		echo parent::display();
	}
}
