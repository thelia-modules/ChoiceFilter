<?php

namespace ChoiceFilter\Model\Base;

use \Exception;
use \PDO;
use ChoiceFilter\Model\ChoiceFilterOther as ChildChoiceFilterOther;
use ChoiceFilter\Model\ChoiceFilterOtherI18nQuery as ChildChoiceFilterOtherI18nQuery;
use ChoiceFilter\Model\ChoiceFilterOtherQuery as ChildChoiceFilterOtherQuery;
use ChoiceFilter\Model\Map\ChoiceFilterOtherTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'choice_filter_other' table.
 *
 *
 *
 * @method     ChildChoiceFilterOtherQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildChoiceFilterOtherQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     ChildChoiceFilterOtherQuery orderByVisible($order = Criteria::ASC) Order by the visible column
 *
 * @method     ChildChoiceFilterOtherQuery groupById() Group by the id column
 * @method     ChildChoiceFilterOtherQuery groupByType() Group by the type column
 * @method     ChildChoiceFilterOtherQuery groupByVisible() Group by the visible column
 *
 * @method     ChildChoiceFilterOtherQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildChoiceFilterOtherQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildChoiceFilterOtherQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildChoiceFilterOtherQuery leftJoinChoiceFilter($relationAlias = null) Adds a LEFT JOIN clause to the query using the ChoiceFilter relation
 * @method     ChildChoiceFilterOtherQuery rightJoinChoiceFilter($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ChoiceFilter relation
 * @method     ChildChoiceFilterOtherQuery innerJoinChoiceFilter($relationAlias = null) Adds a INNER JOIN clause to the query using the ChoiceFilter relation
 *
 * @method     ChildChoiceFilterOtherQuery leftJoinChoiceFilterOtherI18n($relationAlias = null) Adds a LEFT JOIN clause to the query using the ChoiceFilterOtherI18n relation
 * @method     ChildChoiceFilterOtherQuery rightJoinChoiceFilterOtherI18n($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ChoiceFilterOtherI18n relation
 * @method     ChildChoiceFilterOtherQuery innerJoinChoiceFilterOtherI18n($relationAlias = null) Adds a INNER JOIN clause to the query using the ChoiceFilterOtherI18n relation
 *
 * @method     ChildChoiceFilterOther findOne(ConnectionInterface $con = null) Return the first ChildChoiceFilterOther matching the query
 * @method     ChildChoiceFilterOther findOneOrCreate(ConnectionInterface $con = null) Return the first ChildChoiceFilterOther matching the query, or a new ChildChoiceFilterOther object populated from the query conditions when no match is found
 *
 * @method     ChildChoiceFilterOther findOneById(int $id) Return the first ChildChoiceFilterOther filtered by the id column
 * @method     ChildChoiceFilterOther findOneByType(string $type) Return the first ChildChoiceFilterOther filtered by the type column
 * @method     ChildChoiceFilterOther findOneByVisible(boolean $visible) Return the first ChildChoiceFilterOther filtered by the visible column
 *
 * @method     array findById(int $id) Return ChildChoiceFilterOther objects filtered by the id column
 * @method     array findByType(string $type) Return ChildChoiceFilterOther objects filtered by the type column
 * @method     array findByVisible(boolean $visible) Return ChildChoiceFilterOther objects filtered by the visible column
 *
 */
abstract class ChoiceFilterOtherQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \ChoiceFilter\Model\Base\ChoiceFilterOtherQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\ChoiceFilter\\Model\\ChoiceFilterOther', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildChoiceFilterOtherQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildChoiceFilterOtherQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \ChoiceFilter\Model\ChoiceFilterOtherQuery) {
            return $criteria;
        }
        $query = new \ChoiceFilter\Model\ChoiceFilterOtherQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildChoiceFilterOther|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ChoiceFilterOtherTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ChoiceFilterOtherTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildChoiceFilterOther A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, TYPE, VISIBLE FROM choice_filter_other WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildChoiceFilterOther();
            $obj->hydrate($row);
            ChoiceFilterOtherTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildChoiceFilterOther|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildChoiceFilterOtherQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ChoiceFilterOtherTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildChoiceFilterOtherQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ChoiceFilterOtherTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterOtherQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ChoiceFilterOtherTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ChoiceFilterOtherTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChoiceFilterOtherTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE type = 'fooValue'
     * $query->filterByType('%fooValue%'); // WHERE type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterOtherQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $type)) {
                $type = str_replace('*', '%', $type);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ChoiceFilterOtherTableMap::TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the visible column
     *
     * Example usage:
     * <code>
     * $query->filterByVisible(true); // WHERE visible = true
     * $query->filterByVisible('yes'); // WHERE visible = true
     * </code>
     *
     * @param     boolean|string $visible The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterOtherQuery The current query, for fluid interface
     */
    public function filterByVisible($visible = null, $comparison = null)
    {
        if (is_string($visible)) {
            $visible = in_array(strtolower($visible), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ChoiceFilterOtherTableMap::VISIBLE, $visible, $comparison);
    }

    /**
     * Filter the query by a related \ChoiceFilter\Model\ChoiceFilter object
     *
     * @param \ChoiceFilter\Model\ChoiceFilter|ObjectCollection $choiceFilter  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterOtherQuery The current query, for fluid interface
     */
    public function filterByChoiceFilter($choiceFilter, $comparison = null)
    {
        if ($choiceFilter instanceof \ChoiceFilter\Model\ChoiceFilter) {
            return $this
                ->addUsingAlias(ChoiceFilterOtherTableMap::ID, $choiceFilter->getOtherId(), $comparison);
        } elseif ($choiceFilter instanceof ObjectCollection) {
            return $this
                ->useChoiceFilterQuery()
                ->filterByPrimaryKeys($choiceFilter->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByChoiceFilter() only accepts arguments of type \ChoiceFilter\Model\ChoiceFilter or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ChoiceFilter relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildChoiceFilterOtherQuery The current query, for fluid interface
     */
    public function joinChoiceFilter($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ChoiceFilter');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ChoiceFilter');
        }

        return $this;
    }

    /**
     * Use the ChoiceFilter relation ChoiceFilter object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \ChoiceFilter\Model\ChoiceFilterQuery A secondary query class using the current class as primary query
     */
    public function useChoiceFilterQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinChoiceFilter($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ChoiceFilter', '\ChoiceFilter\Model\ChoiceFilterQuery');
    }

    /**
     * Filter the query by a related \ChoiceFilter\Model\ChoiceFilterOtherI18n object
     *
     * @param \ChoiceFilter\Model\ChoiceFilterOtherI18n|ObjectCollection $choiceFilterOtherI18n  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterOtherQuery The current query, for fluid interface
     */
    public function filterByChoiceFilterOtherI18n($choiceFilterOtherI18n, $comparison = null)
    {
        if ($choiceFilterOtherI18n instanceof \ChoiceFilter\Model\ChoiceFilterOtherI18n) {
            return $this
                ->addUsingAlias(ChoiceFilterOtherTableMap::ID, $choiceFilterOtherI18n->getId(), $comparison);
        } elseif ($choiceFilterOtherI18n instanceof ObjectCollection) {
            return $this
                ->useChoiceFilterOtherI18nQuery()
                ->filterByPrimaryKeys($choiceFilterOtherI18n->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByChoiceFilterOtherI18n() only accepts arguments of type \ChoiceFilter\Model\ChoiceFilterOtherI18n or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ChoiceFilterOtherI18n relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildChoiceFilterOtherQuery The current query, for fluid interface
     */
    public function joinChoiceFilterOtherI18n($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ChoiceFilterOtherI18n');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ChoiceFilterOtherI18n');
        }

        return $this;
    }

    /**
     * Use the ChoiceFilterOtherI18n relation ChoiceFilterOtherI18n object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \ChoiceFilter\Model\ChoiceFilterOtherI18nQuery A secondary query class using the current class as primary query
     */
    public function useChoiceFilterOtherI18nQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinChoiceFilterOtherI18n($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ChoiceFilterOtherI18n', '\ChoiceFilter\Model\ChoiceFilterOtherI18nQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildChoiceFilterOther $choiceFilterOther Object to remove from the list of results
     *
     * @return ChildChoiceFilterOtherQuery The current query, for fluid interface
     */
    public function prune($choiceFilterOther = null)
    {
        if ($choiceFilterOther) {
            $this->addUsingAlias(ChoiceFilterOtherTableMap::ID, $choiceFilterOther->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the choice_filter_other table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChoiceFilterOtherTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ChoiceFilterOtherTableMap::clearInstancePool();
            ChoiceFilterOtherTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildChoiceFilterOther or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildChoiceFilterOther object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChoiceFilterOtherTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ChoiceFilterOtherTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        ChoiceFilterOtherTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ChoiceFilterOtherTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // i18n behavior

    /**
     * Adds a JOIN clause to the query using the i18n relation
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildChoiceFilterOtherQuery The current query, for fluid interface
     */
    public function joinI18n($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $relationName = $relationAlias ? $relationAlias : 'ChoiceFilterOtherI18n';

        return $this
            ->joinChoiceFilterOtherI18n($relationAlias, $joinType)
            ->addJoinCondition($relationName, $relationName . '.Locale = ?', $locale);
    }

    /**
     * Adds a JOIN clause to the query and hydrates the related I18n object.
     * Shortcut for $c->joinI18n($locale)->with()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildChoiceFilterOtherQuery The current query, for fluid interface
     */
    public function joinWithI18n($locale = 'en_US', $joinType = Criteria::LEFT_JOIN)
    {
        $this
            ->joinI18n($locale, null, $joinType)
            ->with('ChoiceFilterOtherI18n');
        $this->with['ChoiceFilterOtherI18n']->setIsWithOneToMany(false);

        return $this;
    }

    /**
     * Use the I18n relation query object
     *
     * @see       useQuery()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildChoiceFilterOtherI18nQuery A secondary query class using the current class as primary query
     */
    public function useI18nQuery($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinI18n($locale, $relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ChoiceFilterOtherI18n', '\ChoiceFilter\Model\ChoiceFilterOtherI18nQuery');
    }

} // ChoiceFilterOtherQuery
