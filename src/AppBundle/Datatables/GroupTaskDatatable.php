<?php

namespace AppBundle\Datatables;

class GroupTaskDatatable extends TaskDatatable
{
    public function buildDatatable($locale = null)
    {
        $this->actions[] = [
            'route'            => 'task_lock',
            'route_parameters' => [
                'id' => 'id'
            ],
            'label'            => 'Lock',
            'icon'             => 'fa fa-lock',
            'attributes'       => [
                'rel'   => 'tooltip',
                'title' => 'Lock this task',
                'class' => 'btn btn-xs btn-lock-task',
                'role'  => 'button'
            ],
        ];

        parent::buildDatatable($locale);
    }
}
