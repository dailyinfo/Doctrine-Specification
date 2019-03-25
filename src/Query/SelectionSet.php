<?php

namespace Happyr\DoctrineSpecification\Query;

use Doctrine\ORM\QueryBuilder;

/**
 * Class SelectionSet.
 */
class SelectionSet implements QueryModifier
{
    /**
     * Field names.
     *
     * @var string[]
     */
    private $fields;

    /**
     * DQL Alias.
     *
     * @var string
     */
    private $dqlAlias;

    /**
     * Flag if previous selection should be cleared.
     *
     * @var bool
     */
    private $replace;

    /**
     * SelectionSet constructor.
     *
     * @param string[]|string $fields   List of fields or map entityField -> resultField
     * @param bool            $replace  Flag if previous selection should be replaced with new one
     * @param string          $dqlAlias DQL alias
     */
    public function __construct($fields, $replace = false, $dqlAlias = null)
    {
        $this->fields = (array) $fields;
        $this->dqlAlias = $dqlAlias;
        $this->replace = $replace;
    }

    /**
     * {@inheritdoc}
     */
    public function modify(QueryBuilder $qb, $dqlAlias)
    {
        if ($this->dqlAlias !== null) {
            $dqlAlias = $this->dqlAlias;
        }

        $fields = $this->getSelection($dqlAlias);

        if ($this->replace) {
            $qb->select($fields);
        } else {
            $qb->addSelect($fields);
        }
    }

    /**
     * Return fields selection.
     *
     * @param string $dqlAlias DQL alias
     *
     * @return array
     */
    private function getSelection($dqlAlias)
    {
        $result = [];
        foreach ($this->fields as $k => $v) {
            $isAliased = is_string($k) && is_string($v);
            $fieldName = $isAliased ? $k : $v;

            list($fieldAlias, $fieldName) = explode('.', $fieldName, 2) + [null, null];
            if ($fieldName === null) {
                $fieldName = $fieldAlias;
                $fieldAlias = $dqlAlias;
            }

            $result[] = $isAliased ? sprintf('%s.%s AS %s', $fieldAlias, $fieldName, $v) :
                sprintf('%s.%s', $fieldAlias, $fieldName);
        }

        return $result;
    }
}
