<?php

namespace AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

class TaskEditType extends TaskType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('contents', 'textarea', [
            'label'    => 'Contents',
            'required' => false,
            'attr'     => [
                'rows' => 5,
                'placeholder' => 'The combined text from all the individual image contents',
                'style' => 'min-height: 300px; height: 600px; font-size: 18px; padding: 10px;'
            ]
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_task_edit';
    }
}
