<?php

namespace AppBundle\Controller\Admin;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

/**
 * This is an example of how to use a custom controller for a backend entity.
 */
class PurchaseController extends BaseAdminController
{
    /**
     * This method overrides the default query builder used to search for this
     * entity. This allows to make a more complex search joining related entities.
     * @param string $entityClass
     * @param string $searchQuery
     * @param array $searchableFields
     * @param mixed $sortField
     * @param mixed $sortDirection
     * @param mixed $dqlFilter
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function createSearchQueryBuilder($entityClass, $searchQuery, array $searchableFields, $sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $em->createQueryBuilder()
            ->select('entity')
            ->from($this->entity['class'], 'entity')
            ->join('entity.buyer', 'buyer')
            ->orWhere('LOWER(buyer.username) LIKE :query')
            ->orWhere('LOWER(buyer.email) LIKE :query')
            ->setParameter('query', '%'.strtolower($searchQuery).'%')
        ;

        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy('entity.'.$sortField, $sortDirection ?: 'DESC');
        }

        return $queryBuilder;
    }
}
