<?php

namespace AppBundle\Utils;

class OrderUtils
{
    
    public function calculateNoticeOrederDeadline($project_commencement_date, $project_city)
    {
        $x = $project_commencement_date->format('d-m-Y');
        if ($project_city =='Texas' || $project_city == 'texas')
        {       
            $timestamp = $project_commencement_date->format('d-m-Y');
            $deadline = date('d-m-Y',strtotime($timestamp. '+ 15 days'));
        }

        else
        {
            $timestamp    = strtotime($project_commencement_date->format('F Y'));
            $last_day  = date('t-m-Y', $timestamp);
            $deadline = date('d-m-Y', strtotime($last_day. ' + 60 days'));
        }
        $actual_deadline = $this->checkWeekend($deadline);

        return $actual_deadline;
    }

    public function calculateLienOrederDeadline($project_start_date)
    {
        $timestamp = strtotime($project_start_date->format('F Y'));
        $last_day  = date('t-m-Y', $timestamp);
        $deadline = date('d-m-Y', strtotime($last_day. ' + 90 days'));
        $deadline = $this->checkWeekend($deadline);

        return $deadline;
    }

    public function checkWeekend($date)
    {   
        $year = date('Y',strtotime($date));
        $holidays = array('31-12-'.$year , '02-06-'.$year, '24-11-'.$year ,
         date('d-m-Y', strtotime("second friday of december $year")));
        

        if (in_array($date, $holidays))
        {
            if(date('w',strtotime($date . "- 1 days")) == 0)
            {
                $final_date = date('d-m-Y', strtotime($date. '- 3 days'));
            }
            elseif (date('w',strtotime($date."- 1 days")) == 6) 
            {
                $final_date = date('d-m-Y', strtotime($date. '- 2 days'));
            }
            else
            {
                $final_date = date('d-m-Y', strtotime($date. '- 1 days'));
            }

        }
        else
        {
            if(date('w',strtotime($date)) == 0)
            {
                $final_date = date('d-m-Y', strtotime($date. '- 2 days'));
            }
            elseif (date('w',strtotime($date)) == 6) 
            {
                $final_date = date('d-m-Y', strtotime($date. '- 1 days'));
            }
            else
            {
                $final_date = $date;
            }
        }
        return $final_date;

    }

}


