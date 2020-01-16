<?php
/**
 * @package     mod_statistics__module
 * @author      Thomas Winterling
 * @email       info@winterling-labs.com
 * @copyright   Copyright (C) 2005 - 2013, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';   // Helper

JHTML::_('script', 'mod_wl_statistics_module/scripts.js', array('version' => 'auto', 'relative' => true));

$labels = ModWL_Statistics_Module_Helper::CreateNewLabels($params);
$datasets = ModWL_Statistics_Module_Helper::CreateNewDataSets ($params);
$data = ModWL_Statistics_Module_Helper::GetLivedataParams ($params);
$chartJs =  ModWL_Statistics_Module_Helper::chartJs($data,$datasets,$labels);

// Check for a custom CSS file
JHtml::_('stylesheet', 'mod_wl_statistics_module/user.css', array('version' => 'auto', 'relative' => true));


require JModuleHelper::getLayoutPath('mod_wl_statistics_module', $params->get('layout', 'default'));