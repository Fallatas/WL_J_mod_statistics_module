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

    // Detects Users
    public function getUsers()
    {

        // Get a db connection.
        $db = JFactory::getDbo();

        //Create Query
        $query = $db
            ->getQuery(true)
            ->select($db->qn("name"))
            ->from($db->qn('#__users'));

        $db->setQuery($query);
        $column = $db->loadColumn ();

        // using the data
        $countData = count($column);
        return $countData;
    }


    /**
     * Show online count
     *
     * @return  array  The number of Users and Guests online.
     *
     * Adapted from Joomla mod_whosonline
     * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
     * @license     GNU General Public License version 2 or later; see LICENSE.txt
     *
     * @since   1.5
     **/

    public static function getOnlineCount()
    {
        $db = JFactory::getDbo();

        // Calculate number of guests and users
        $result	     = array();
        $user_array  = 0;
        $guest_array = 0;

        $whereCondition = JFactory::getConfig()->get('shared_session', '0') ? 'IS NULL' : '= 0';

        $query = $db->getQuery(true)
            ->select($db->qn(array('guest', 'client_id')))
            ->from('#__session')
            ->where('client_id ' . $whereCondition);
        $db->setQuery($query);

        try
        {
            $sessions = (array) $db->loadObjectList();
        }
        catch (RuntimeException $e)
        {
            $sessions = array();
        }

        if (count($sessions))
        {
            foreach ($sessions as $session)
            {
                // If guest increase guest count by 1
                if ($session->guest == 1)
                {
                    $guest_array ++;
                }

                // If member increase member count by 1
                if ($session->guest == 0)
                {
                    $user_array ++;
                }
            }
        }

        $result['user']  = $user_array;
        $result['guest'] = $guest_array;

        $totalNumber = $result['user']  = $user_array + $result['guest'] = $guest_array;
        return $totalNumber;
    }




    // detects Articles
    public function getArticles()
    {

        // Get a db connection.
        $db = JFactory::getDbo();

        //Create Query
        $query = $db
            ->getQuery(true)
            ->select("title")
            ->from($db->quoteName('#__content'));

        $db->setQuery($query);
        $column = $db->loadColumn ();

        // using the data
        $countArticles = count($column);

        return $countArticles;

    }

    /**
     * CreateNewDataSets
     *
     * @param $params  array  The params attributes
     * @return string  dataset of params
     */
    public function CreateNewDataSets($params){

        $fields = $params->get('fields');

        $dataset ='';

        foreach ($fields as $field)
        {
            $dataset .= "{label:'$field->labeltext',backgroundColor:'$field->labeltextcolor',data:[$field->properties]},";
        }

        return $dataset;

    }


    public function getLivedataParams ($params)
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

    // Create Chart.js Object
    public function chartJs($count,$data,$dataset)
    {

        // Add JS Parameter
        JFactory::getDocument()->addScriptDeclaration("jQuery(document).ready(function () {  
                
              
              var myChartObject = document.getElementById('myChart');

              var chartData = {

                type: `$data->type`,
       data: {
        labels: ['January','February','March','April','May','June','July'],

        datasets: [$dataset]


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
console.log(myChartObject);
var onlineCount = $count;
var dataCount = 20;

var liveData = {
    online: [],
    time: []
};

for (var i = 0; i < dataCount; i++){
    liveData.online.push('');
    liveData.time.push('');
}

function onlineUserEmit(){

    var date = new Date();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();
    
    var totalTime = hours + ':' + minutes + ':' + seconds;

    if (liveData.time.length >= dataCount) {
        liveData.time.shift();
    }

    if (liveData.online.length >= dataCount) {
        liveData.online.shift();
    }

    liveData.online.push(onlineCount);
    liveData.time.push(totalTime);
    

}

var chart = new Chart(myChartObject, chartData);

chart.data.labels = liveData.time;
chart.data.datasets[0].data = liveData.online;


onlineUserEmit();

setInterval(function () {
    onlineUserEmit();
    chart.update();
},1000);

   
        });");

    }

    public function getStyleParams()
    {

    }


}