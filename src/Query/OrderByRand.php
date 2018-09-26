<?php

namespace Happyr\DoctrineSpecification\Query;

use Doctrine\ORM\QueryBuilder;

class OrderByRand implements QueryModifier
{
    /**
     * @var string field
     */
    protected $field;

    /**
     * @var string order
     */
    protected $order;

    /**
     * @var string dqlAlias
     */
    protected $dqlAlias;

    /**
     * @param string      $field
     * @param string      $order
     * @param string|null $dqlAlias
     */
    public function __construct($dqlAlias = null)
    {
        $this->dqlAlias = $dqlAlias;
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $dqlAlias
     */
    public function modify(QueryBuilder $qb, $dqlAlias)
    {
        if ($this->dqlAlias !== null) {
            $dqlAlias = $this->dqlAlias;
        }
        $qb->addSelect('RAND() as HIDDEN rand');
        $qb->addOrderBy('rand');
    }
}
