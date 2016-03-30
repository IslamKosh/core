<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\Style;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;

/**
 * Class TaskDatatable
 *
 * @package AppBundle\Datatables
 */
class TaskDatatable extends AbstractDatatableView
{
    /** @var string */
    protected $resultRoute;

    /** @var boolean */
    protected $lockOnly;

    /** @var string */
    protected $role;

    /** @var array */
    protected $actions = [];

    /**
     * @param $path
     */
    public function setResultRoute($path)
    {
        $this->resultRoute = $path;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function showLockOnly()
    {
        $this->lockOnly = true;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatable($locale = null)
    {
        $this->features->set([
            'auto_width'    => true,
            'defer_render'  => false,
            'info'          => true,
            'jquery_ui'     => false,
            'length_change' => true,
            'ordering'      => true,
            'paging'        => true,
            'processing'    => true,
            'scroll_x'      => false,
            'scroll_y'      => '',
            'searching'     => true,
            'server_side'   => true,
            'state_save'    => false,
            'delay'         => 0,
            'extensions'    => []
        ]);

        $this->ajax->set([
            'url'  => $this->router->generate($this->resultRoute),
            'type' => 'GET'
        ]);

        $this->options->set([
            'display_start'                 => 0,
            'defer_loading'                 => -1,
            'dom'                           => 'lfrtip',
            'length_menu'                   => [10, 25, 50, 100],
            'order_classes'                 => true,
            'order'                         => [[0, 'desc']],
            'order_multi'                   => true,
            'page_length'                   => 10,
            'paging_type'                   => Style::FULL_NUMBERS_PAGINATION,
            'renderer'                      => '',
            'scroll_collapse'               => false,
            'search_delay'                  => 0,
            'state_duration'                => 7200,
            'stripe_classes'                => [],
            'class'                         => Style::BOOTSTRAP_3_STYLE,
            'individual_filtering'          => false,
            'individual_filtering_position' => 'foot',
            'use_integration_options'       => true,
            'force_dom'                     => false
        ]);

        $this->columnBuilder
            ->add('id', 'column', [
                'title' => 'ID'
            ])
            ->add('title', 'column', [
                'title' => 'Title',
                'width' => '40%'
            ])
            ->add('state', 'column', [
                'title'      => 'State',
                'searchable' => false
            ])
            ->add('lockedBy.firstName', 'column', [
                'title'      => 'Locked By',
                'searchable' => false
            ])
            ->add('published', 'boolean', [
                'title'      => 'Published',
                'searchable' => false
            ])
            ->add('updatedAt', 'datetime', [
                'title'      => 'Last Updated',
                'searchable' => false
            ]);

        $actions[] = [
            'route'            => 'task_show',
            'route_parameters' => [
                'id' => 'id'
            ],
            'label'            => 'View',
            'icon'             => 'fa fa-search',
            'attributes'       => [
                'rel'   => 'tooltip',
                'title' => 'View task',
                'class' => 'btn btn-xs',
                'role'  => 'button'
            ]
        ];

        if ($this->lockOnly) {

            $actions[] = [
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

        } else {

            if ($this->role == 'contributor') {
                $editRoute = 'task_entry';
                $editLabel = 'Entry';
            } else {
                $editRoute = 'task_update';
                $editLabel = 'Edit';
            }

            $actions[] = [
                'route'            => $editRoute,
                'route_parameters' => [
                    'id' => 'id'
                ],
                'label'            => $editLabel,
                'icon'             => 'fa fa-edit',
                'attributes'       => [
                    'rel'   => 'tooltip',
                    'class' => 'btn btn-xs',
                    'role'  => 'button'
                ],
            ];

        }

        $this->columnBuilder->add(null, 'action', [
            'title'   => $this->translator->trans('datatables.actions.title'),
            'actions' => $actions
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\Task';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'task_datatable';
    }
}
