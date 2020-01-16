<?php

/**
 * @package    WL STATISTICS MODUL
 *
 * @author     Thomas Winterling <info@winterling-labs.com>
 * @copyright  Copyright (C) 2005 - 2019. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

/**
 * Helper for mod_statistics_module
 *
 * @since  Version 1.0.0.0
 */
class ModWL_Statistics_Module_Helper
{

    /**
     * CreateNewLabels
     *
     * @param $params  array  The params attributes
     * @return string  dataset of params
     */

    public function CreateNewLabels($params)
    {

        $fields  = (array) $params->get('labelsfields');

        $textlabels = [];

        foreach ($fields as $field){
            $textlabels [] = $field->labels;
        }

        $labeldata = json_encode($textlabels);
        $labeldata = str_replace('"','\'',$labeldata);

        $newdata = 'labels:' . $labeldata;


        return $newdata;
    }


    /**
     * CreateNewDataSets
     *
     * @param $params  array  The params attributes
     * @return string  dataset of params
     */

    public function CreateNewDataSets($params)
    {

        /* Create data arrays */

        $fieldParams = $params->get('fields');

        $labels = [];
        $backgroundColors = [];
        $borderColors = [];

        foreach ($fieldParams as $singleParam)
        {
            $labels[] = $singleParam->labeltext;
            $backgroundColors [] = $singleParam->labeltextcolor;
            $borderColors [] = $singleParam->bordercolor;
        }


        /* Get parameters content */


        $fields  = (array) $params->get('fields');

        $newArray = [];

        foreach ($fields as $field)
        {
            $numbervalues = [];

            foreach ((array) $field->fieldname as $numbervalue)
            {
                $numbervalues[] = $numbervalue->mynumbervalue;
            }

            array_push($newArray,$numbervalues);

        }


        /* Create Datasets */


        $counter = count($numbervalues) - 1;

        print_r($counter);

        $data = [];

        for($i = 0; $i <= $counter; $i++)
        {
            array_push($data, '{label:'. '\'' . json_encode($labels[$i]) . '\'' . ','
                . 'backgroundColor:'. '\'' . json_encode($backgroundColors [$i]) . '\'' . ','
                . 'borderColor:'. '\'' . json_encode($borderColors [$i]) . '\'' . ','
                . 'data:' . json_encode($newArray[$i]) . '}');
        }


        $formdata = str_replace('"', '', implode(',', $data));



        return $formdata;
    }


    /**
     * GetLiveDataParams
     *
     * @param $params  array  The params attributes
     * @return string  database of params
     */


    public function GetLivedataParams ($params)
    {
        /// Add Module Parameter
        jimport( 'joomla.application.module.helper' );
        $module = JModuleHelper::getModule('wl_statistics_module');
        $module_id = $module->id;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('params')
            ->from($db->quoteName('#__modules'))
            ->where('id = ' . $db->quote($module_id));
        $db->setQuery($query);
        $moduleparams = (json_decode($db->loadResult()));
        return $moduleparams;
    }


    /**
     * chartJs
     *
     * @param $params  array  The params attributes
     * @return string  Oject return
     */

    // Create Chart.js Object

    public function chartJs($data,$datasets,$labels)
    {

        // Add JS Parameter
        JFactory::getDocument()->addScriptDeclaration("jQuery(document).ready(function () {  
                
              
              var myChartObject = document.getElementById('myChart');

              var chartData = {

                type: `$data->type`,
       data: {
        $labels,

        datasets: [$datasets]
    },
    options: {
        title: {
            display: true,
            text: 'Online Nutzer der letzten 20 Sekunden',
            position: 'top',
            fontSize: 20
        },
        animation: {
            duration: 0
        },
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    userCallback: function (label, index, labels) {
                        if (Math.floor(label) === label) {
                            return label;
                        }
                    },
                }
            }]
        }
    }
    };

    var chart = new Chart(myChartObject, chartData);
    
     });");

    }

}