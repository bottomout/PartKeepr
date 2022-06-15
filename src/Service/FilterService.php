<?php

namespace App\Service;

use App\Filter\Filter;
use Doctrine\ORM\EntityManagerInterface;

class FilterService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Returns a DQL expression for the given filter and alias.
     *
     * @param Filter $filter    The filter to build the expression for
     * @param string $alias     The field alias to search in
     * @param string $paramName The parameter name you use to bind the value to
     *
     * @throws \Exception
     *
     * @return \Doctrine\ORM\Query\Expr\Comparison
     */
    public function getExpressionForFilter(Filter $filter, $alias, $paramName)
    {
        switch (strtolower($filter->getOperator())) {
            case Filter::OPERATOR_EQUALS:
                return $this->em->getExpressionBuilder()->eq($alias, $paramName);
                break;
            case Filter::OPERATOR_GREATER_THAN:
                return $this->em->getExpressionBuilder()->gt($alias, $paramName);
                break;
            case Filter::OPERATOR_GREATER_THAN_EQUALS:
                return $this->em->getExpressionBuilder()->gte($alias, $paramName);
                break;
            case Filter::OPERATOR_LESS_THAN:
                return $this->em->getExpressionBuilder()->lt($alias, $paramName);
                break;
            case Filter::OPERATOR_LESS_THAN_EQUALS:
                return $this->em->getExpressionBuilder()->lte($alias, $paramName);
                break;
            case Filter::OPERATOR_NOT_EQUALS:
                return $this->em->getExpressionBuilder()->neq($alias, $paramName);
                break;
            case Filter::OPERATOR_LIKE:
                return $this->em->getExpressionBuilder()->like($alias, $paramName);
                break;
            default:
                throw new \Exception('Unknown operator '.$filter->getOperator());
        }
    }
}
