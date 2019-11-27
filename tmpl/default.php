<?php
/**
 * @package    WL_LIVEDATA_MODULE
 *
 * @author     Thomas Winterling <info@winterling-labs.com>
 * @copyright  Copyright (C) 2011 - 2019
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

JHtml::_('stylesheet', 'mod_wl_livedata_module/style.css', array('version' => 'auto', 'relative' => true));
JHtml::_('script', 'mod_wl_livedata_module/chart.min.js', array('version' => 'auto', 'relative' => true));
JHtml::_('script', 'mod_wl_livedata_module/scripts.js', array('version' => 'auto', 'relative' => true));
JHtml::_('jQuery.Framework');
?>

<?php
/*
echo "<pre>";
print_r($params);
echo "</pre>";
*/
?>
<div style="width:<?php echo $data->chartsize . '%'; ?>">
    <canvas id="myChart" width="<?php echo $data->chartwidth . 'px'; ?>" height="<?php echo $data->chartheight . 'px'; ?>"></canvas>
    <?php echo $data->userdisplay;  ?>
</div>