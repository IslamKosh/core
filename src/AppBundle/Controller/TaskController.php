<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Google\Drive;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Resource\Event\ResourceEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskController extends ResourceController
{
    public function indexAction(Request $request)
    {
        $datatable = $this->get('app.datatable.task');
        $datatable->setResultRoute('task_results');
        $datatable->buildDatatable();

        return $this->render('AppBundle:Task:index.html.twig', [
            'datatable' => $datatable,
            'pageTitle' => 'Tasks'
        ]);
    }

    public function resultsAction()
    {
        $datatable = $this->get('app.datatable.task');
        $datatable->setResultRoute('task_results');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        return $query->getResponse();
    }

    public function groupTasksAction(Request $request)
    {
        $datatable = $this->get('app.datatable.task');
        $datatable->setResultRoute('group_task_results');
        $datatable->showLockOnly();
        $datatable->buildDatatable();

        return $this->render('AppBundle:Task:index.html.twig', [
            'datatable' => $datatable,
            'pageTitle' => 'Group Tasks'
        ]);
    }

    public function groupTasksResultsAction()
    {
        $datatable = $this->get('app.datatable.task');
        $datatable->setResultRoute('group_task_results');
        $datatable->showLockOnly();
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        $function = function(QueryBuilder $qb) {
            $qb->andWhere($qb->getRootAlias() . ".state = :state");
            $qb->andWhere($qb->getRootAlias() . ".lockedBy IS NULL");
            $qb->setParameter('state', $this->getUser()->getGroupState());
        };

        $query->addWhereAll($function);

        return $query->getResponse();
    }

    public function myTasksAction(Request $request)
    {
        $datatable = $this->get('app.datatable.task');
        $datatable->setResultRoute('my_task_results');
        $datatable->setRole('contributor');
        $datatable->buildDatatable();

        return $this->render('AppBundle:Task:index.html.twig', [
            'datatable' => $datatable,
            'pageTitle' => 'My Tasks'
        ]);
    }

    public function myTasksResultsAction()
    {
        $datatable = $this->get('app.datatable.task');
        $datatable->setResultRoute('my_task_results');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        $function = function(QueryBuilder $qb) {
            $qb->andWhere($qb->getRootAlias() . ".lockedBy = :user");
            $qb->setParameter('user', $this->getUser()->getId());
        };

        $query->addWhereAll($function);

        return $query->getResponse();
    }

    public function lockAction(Request $request)
    {
        /** @var Task $task */
        $task = $this->findOr404($request);

        $this->getRepository()->lockTask($task, $this->getUser());

        return $this->redirectToRoute('task_entry', ['id' => $task->getId()]);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function entryAction(Request $request)
    {
        $this->isGrantedOr403('update');

        $resource = $this->findOr404($request);
        $form     = $this->getForm($resource);

        if (in_array($request->getMethod(), array('POST', 'PUT', 'PATCH')) && $form->submit($request, !$request->isMethod('PATCH'))->isValid()) {

            $this->domainManager->update($resource);

            if ($resource instanceof ResourceEvent) {
                return $this->redirectHandler->redirectToIndex();
            }

            return $this->redirectHandler->redirectTo($resource);

        }

        $view = $this
            ->view()
            ->setTemplate('AppBundle:Task:entry.html.twig')
            ->setData(array(
                $this->config->getResourceName() => $resource,
                'form'                           => $form->createView(),
            ))
        ;

        return $this->handleView($view);
    }
}
