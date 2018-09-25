<?php

namespace ChoiceFilter\Model\Base;

use \Exception;
use \PDO;
use ChoiceFilter\Model\ChoiceFilter as ChildChoiceFilter;
use ChoiceFilter\Model\ChoiceFilterQuery as ChildChoiceFilterQuery;
use ChoiceFilter\Model\Map\ChoiceFilterTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Thelia\Model\Attribute;
use Thelia\Model\Category;
use Thelia\Model\Feature;
use Thelia\Model\Template;

/**
 * Base class that represents a query for the 'choice_filter' table.
 *
 *
 *
 * @method     ChildChoiceFilterQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildChoiceFilterQuery orderByFeatureId($order = Criteria::ASC) Order by the feature_id column
 * @method     ChildChoiceFilterQuery orderByAttributeId($order = Criteria::ASC) Order by the attribute_id column
 * @method     ChildChoiceFilterQuery orderByOtherId($order = Criteria::ASC) Order by the other_id column
 * @method     ChildChoiceFilterQuery orderByCategoryId($order = Criteria::ASC) Order by the category_id column
 * @method     ChildChoiceFilterQuery orderByTemplateId($order = Criteria::ASC) Order by the template_id column
 * @method     ChildChoiceFilterQuery orderByPosition($order = Criteria::ASC) Order by the position column
 * @method     ChildChoiceFilterQuery orderByVisible($order = Criteria::ASC) Order by the visible column
 * @method     ChildChoiceFilterQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildChoiceFilterQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildChoiceFilterQuery groupById() Group by the id column
 * @method     ChildChoiceFilterQuery groupByFeatureId() Group by the feature_id column
 * @method     ChildChoiceFilterQuery groupByAttributeId() Group by the attribute_id column
 * @method     ChildChoiceFilterQuery groupByOtherId() Group by the other_id column
 * @method     ChildChoiceFilterQuery groupByCategoryId() Group by the category_id column
 * @method     ChildChoiceFilterQuery groupByTemplateId() Group by the template_id column
 * @method     ChildChoiceFilterQuery groupByPosition() Group by the position column
 * @method     ChildChoiceFilterQuery groupByVisible() Group by the visible column
 * @method     ChildChoiceFilterQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildChoiceFilterQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildChoiceFilterQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildChoiceFilterQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildChoiceFilterQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildChoiceFilterQuery leftJoinAttribute($relationAlias = null) Adds a LEFT JOIN clause to the query using the Attribute relation
 * @method     ChildChoiceFilterQuery rightJoinAttribute($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Attribute relation
 * @method     ChildChoiceFilterQuery innerJoinAttribute($relationAlias = null) Adds a INNER JOIN clause to the query using the Attribute relation
 *
 * @method     ChildChoiceFilterQuery leftJoinFeature($relationAlias = null) Adds a LEFT JOIN clause to the query using the Feature relation
 * @method     ChildChoiceFilterQuery rightJoinFeature($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Feature relation
 * @method     ChildChoiceFilterQuery innerJoinFeature($relationAlias = null) Adds a INNER JOIN clause to the query using the Feature relation
 *
 * @method     ChildChoiceFilterQuery leftJoinChoiceFilterOther($relationAlias = null) Adds a LEFT JOIN clause to the query using the ChoiceFilterOther relation
 * @method     ChildChoiceFilterQuery rightJoinChoiceFilterOther($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ChoiceFilterOther relation
 * @method     ChildChoiceFilterQuery innerJoinChoiceFilterOther($relationAlias = null) Adds a INNER JOIN clause to the query using the ChoiceFilterOther relation
 *
 * @method     ChildChoiceFilterQuery leftJoinCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the Category relation
 * @method     ChildChoiceFilterQuery rightJoinCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Category relation
 * @method     ChildChoiceFilterQuery innerJoinCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the Category relation
 *
 * @method     ChildChoiceFilterQuery leftJoinTemplate($relationAlias = null) Adds a LEFT JOIN clause to the query using the Template relation
 * @method     ChildChoiceFilterQuery rightJoinTemplate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Template relation
 * @method     ChildChoiceFilterQuery innerJoinTemplate($relationAlias = null) Adds a INNER JOIN clause to the query using the Template relation
 *
 * @method     ChildChoiceFilter findOne(ConnectionInterface $con = null) Return the first ChildChoiceFilter matching the query
 * @method     ChildChoiceFilter findOneOrCreate(ConnectionInterface $con = null) Return the first ChildChoiceFilter matching the query, or a new ChildChoiceFilter object populated from the query conditions when no match is found
 *
 * @method     ChildChoiceFilter findOneById(int $id) Return the first ChildChoiceFilter filtered by the id column
 * @method     ChildChoiceFilter findOneByFeatureId(int $feature_id) Return the first ChildChoiceFilter filtered by the feature_id column
 * @method     ChildChoiceFilter findOneByAttributeId(int $attribute_id) Return the first ChildChoiceFilter filtered by the attribute_id column
 * @method     ChildChoiceFilter findOneByOtherId(int $other_id) Return the first ChildChoiceFilter filtered by the other_id column
 * @method     ChildChoiceFilter findOneByCategoryId(int $category_id) Return the first ChildChoiceFilter filtered by the category_id column
 * @method     ChildChoiceFilter findOneByTemplateId(int $template_id) Return the first ChildChoiceFilter filtered by the template_id column
 * @method     ChildChoiceFilter findOneByPosition(int $position) Return the first ChildChoiceFilter filtered by the position column
 * @method     ChildChoiceFilter findOneByVisible(boolean $visible) Return the first ChildChoiceFilter filtered by the visible column
 * @method     ChildChoiceFilter findOneByCreatedAt(string $created_at) Return the first ChildChoiceFilter filtered by the created_at column
 * @method     ChildChoiceFilter findOneByUpdatedAt(string $updated_at) Return the first ChildChoiceFilter filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildChoiceFilter objects filtered by the id column
 * @method     array findByFeatureId(int $feature_id) Return ChildChoiceFilter objects filtered by the feature_id column
 * @method     array findByAttributeId(int $attribute_id) Return ChildChoiceFilter objects filtered by the attribute_id column
 * @method     array findByOtherId(int $other_id) Return ChildChoiceFilter objects filtered by the other_id column
 * @method     array findByCategoryId(int $category_id) Return ChildChoiceFilter objects filtered by the category_id column
 * @method     array findByTemplateId(int $template_id) Return ChildChoiceFilter objects filtered by the template_id column
 * @method     array findByPosition(int $position) Return ChildChoiceFilter objects filtered by the position column
 * @method     array findByVisible(boolean $visible) Return ChildChoiceFilter objects filtered by the visible column
 * @method     array findByCreatedAt(string $created_at) Return ChildChoiceFilter objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildChoiceFilter objects filtered by the updated_at column
 *
 */
abstract class ChoiceFilterQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \ChoiceFilter\Model\Base\ChoiceFilterQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\ChoiceFilter\\Model\\ChoiceFilter', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildChoiceFilterQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildChoiceFilterQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \ChoiceFilter\Model\ChoiceFilterQuery) {
            return $criteria;
        }
        $query = new \ChoiceFilter\Model\ChoiceFilterQuery();
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
     * @return ChildChoiceFilter|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ChoiceFilterTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ChoiceFilterTableMap::DATABASE_NAME);
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
     * @return   ChildChoiceFilter A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, FEATURE_ID, ATTRIBUTE_ID, OTHER_ID, CATEGORY_ID, TEMPLATE_ID, POSITION, VISIBLE, CREATED_AT, UPDATED_AT FROM choice_filter WHERE ID = :p0';
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
            $obj = new ChildChoiceFilter();
            $obj->hydrate($row);
            ChoiceFilterTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildChoiceFilter|array|mixed the result, formatted by the current formatter
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
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ChoiceFilterTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ChoiceFilterTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChoiceFilterTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the feature_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFeatureId(1234); // WHERE feature_id = 1234
     * $query->filterByFeatureId(array(12, 34)); // WHERE feature_id IN (12, 34)
     * $query->filterByFeatureId(array('min' => 12)); // WHERE feature_id > 12
     * </code>
     *
     * @see       filterByFeature()
     *
     * @param     mixed $featureId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterByFeatureId($featureId = null, $comparison = null)
    {
        if (is_array($featureId)) {
            $useMinMax = false;
            if (isset($featureId['min'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::FEATURE_ID, $featureId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($featureId['max'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::FEATURE_ID, $featureId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChoiceFilterTableMap::FEATURE_ID, $featureId, $comparison);
    }

    /**
     * Filter the query on the attribute_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAttributeId(1234); // WHERE attribute_id = 1234
     * $query->filterByAttributeId(array(12, 34)); // WHERE attribute_id IN (12, 34)
     * $query->filterByAttributeId(array('min' => 12)); // WHERE attribute_id > 12
     * </code>
     *
     * @see       filterByAttribute()
     *
     * @param     mixed $attributeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterByAttributeId($attributeId = null, $comparison = null)
    {
        if (is_array($attributeId)) {
            $useMinMax = false;
            if (isset($attributeId['min'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::ATTRIBUTE_ID, $attributeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($attributeId['max'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::ATTRIBUTE_ID, $attributeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChoiceFilterTableMap::ATTRIBUTE_ID, $attributeId, $comparison);
    }

    /**
     * Filter the query on the other_id column
     *
     * Example usage:
     * <code>
     * $query->filterByOtherId(1234); // WHERE other_id = 1234
     * $query->filterByOtherId(array(12, 34)); // WHERE other_id IN (12, 34)
     * $query->filterByOtherId(array('min' => 12)); // WHERE other_id > 12
     * </code>
     *
     * @see       filterByChoiceFilterOther()
     *
     * @param     mixed $otherId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterByOtherId($otherId = null, $comparison = null)
    {
        if (is_array($otherId)) {
            $useMinMax = false;
            if (isset($otherId['min'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::OTHER_ID, $otherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($otherId['max'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::OTHER_ID, $otherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChoiceFilterTableMap::OTHER_ID, $otherId, $comparison);
    }

    /**
     * Filter the query on the category_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCategoryId(1234); // WHERE category_id = 1234
     * $query->filterByCategoryId(array(12, 34)); // WHERE category_id IN (12, 34)
     * $query->filterByCategoryId(array('min' => 12)); // WHERE category_id > 12
     * </code>
     *
     * @see       filterByCategory()
     *
     * @param     mixed $categoryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterByCategoryId($categoryId = null, $comparison = null)
    {
        if (is_array($categoryId)) {
            $useMinMax = false;
            if (isset($categoryId['min'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::CATEGORY_ID, $categoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($categoryId['max'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::CATEGORY_ID, $categoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChoiceFilterTableMap::CATEGORY_ID, $categoryId, $comparison);
    }

    /**
     * Filter the query on the template_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTemplateId(1234); // WHERE template_id = 1234
     * $query->filterByTemplateId(array(12, 34)); // WHERE template_id IN (12, 34)
     * $query->filterByTemplateId(array('min' => 12)); // WHERE template_id > 12
     * </code>
     *
     * @see       filterByTemplate()
     *
     * @param     mixed $templateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterByTemplateId($templateId = null, $comparison = null)
    {
        if (is_array($templateId)) {
            $useMinMax = false;
            if (isset($templateId['min'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::TEMPLATE_ID, $templateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($templateId['max'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::TEMPLATE_ID, $templateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChoiceFilterTableMap::TEMPLATE_ID, $templateId, $comparison);
    }

    /**
     * Filter the query on the position column
     *
     * Example usage:
     * <code>
     * $query->filterByPosition(1234); // WHERE position = 1234
     * $query->filterByPosition(array(12, 34)); // WHERE position IN (12, 34)
     * $query->filterByPosition(array('min' => 12)); // WHERE position > 12
     * </code>
     *
     * @param     mixed $position The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterByPosition($position = null, $comparison = null)
    {
        if (is_array($position)) {
            $useMinMax = false;
            if (isset($position['min'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::POSITION, $position['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($position['max'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::POSITION, $position['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChoiceFilterTableMap::POSITION, $position, $comparison);
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
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterByVisible($visible = null, $comparison = null)
    {
        if (is_string($visible)) {
            $visible = in_array(strtolower($visible), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ChoiceFilterTableMap::VISIBLE, $visible, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChoiceFilterTableMap::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ChoiceFilterTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChoiceFilterTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Thelia\Model\Attribute object
     *
     * @param \Thelia\Model\Attribute|ObjectCollection $attribute The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterByAttribute($attribute, $comparison = null)
    {
        if ($attribute instanceof \Thelia\Model\Attribute) {
            return $this
                ->addUsingAlias(ChoiceFilterTableMap::ATTRIBUTE_ID, $attribute->getId(), $comparison);
        } elseif ($attribute instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ChoiceFilterTableMap::ATTRIBUTE_ID, $attribute->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAttribute() only accepts arguments of type \Thelia\Model\Attribute or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Attribute relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function joinAttribute($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Attribute');

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
            $this->addJoinObject($join, 'Attribute');
        }

        return $this;
    }

    /**
     * Use the Attribute relation Attribute object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\AttributeQuery A secondary query class using the current class as primary query
     */
    public function useAttributeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAttribute($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Attribute', '\Thelia\Model\AttributeQuery');
    }

    /**
     * Filter the query by a related \Thelia\Model\Feature object
     *
     * @param \Thelia\Model\Feature|ObjectCollection $feature The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterByFeature($feature, $comparison = null)
    {
        if ($feature instanceof \Thelia\Model\Feature) {
            return $this
                ->addUsingAlias(ChoiceFilterTableMap::FEATURE_ID, $feature->getId(), $comparison);
        } elseif ($feature instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ChoiceFilterTableMap::FEATURE_ID, $feature->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFeature() only accepts arguments of type \Thelia\Model\Feature or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Feature relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function joinFeature($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Feature');

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
            $this->addJoinObject($join, 'Feature');
        }

        return $this;
    }

    /**
     * Use the Feature relation Feature object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\FeatureQuery A secondary query class using the current class as primary query
     */
    public function useFeatureQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFeature($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Feature', '\Thelia\Model\FeatureQuery');
    }

    /**
     * Filter the query by a related \ChoiceFilter\Model\ChoiceFilterOther object
     *
     * @param \ChoiceFilter\Model\ChoiceFilterOther|ObjectCollection $choiceFilterOther The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterByChoiceFilterOther($choiceFilterOther, $comparison = null)
    {
        if ($choiceFilterOther instanceof \ChoiceFilter\Model\ChoiceFilterOther) {
            return $this
                ->addUsingAlias(ChoiceFilterTableMap::OTHER_ID, $choiceFilterOther->getId(), $comparison);
        } elseif ($choiceFilterOther instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ChoiceFilterTableMap::OTHER_ID, $choiceFilterOther->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByChoiceFilterOther() only accepts arguments of type \ChoiceFilter\Model\ChoiceFilterOther or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ChoiceFilterOther relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function joinChoiceFilterOther($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ChoiceFilterOther');

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
            $this->addJoinObject($join, 'ChoiceFilterOther');
        }

        return $this;
    }

    /**
     * Use the ChoiceFilterOther relation ChoiceFilterOther object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \ChoiceFilter\Model\ChoiceFilterOtherQuery A secondary query class using the current class as primary query
     */
    public function useChoiceFilterOtherQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinChoiceFilterOther($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ChoiceFilterOther', '\ChoiceFilter\Model\ChoiceFilterOtherQuery');
    }

    /**
     * Filter the query by a related \Thelia\Model\Category object
     *
     * @param \Thelia\Model\Category|ObjectCollection $category The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterByCategory($category, $comparison = null)
    {
        if ($category instanceof \Thelia\Model\Category) {
            return $this
                ->addUsingAlias(ChoiceFilterTableMap::CATEGORY_ID, $category->getId(), $comparison);
        } elseif ($category instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ChoiceFilterTableMap::CATEGORY_ID, $category->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCategory() only accepts arguments of type \Thelia\Model\Category or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Category relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function joinCategory($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Category');

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
            $this->addJoinObject($join, 'Category');
        }

        return $this;
    }

    /**
     * Use the Category relation Category object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\CategoryQuery A secondary query class using the current class as primary query
     */
    public function useCategoryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Category', '\Thelia\Model\CategoryQuery');
    }

    /**
     * Filter the query by a related \Thelia\Model\Template object
     *
     * @param \Thelia\Model\Template|ObjectCollection $template The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function filterByTemplate($template, $comparison = null)
    {
        if ($template instanceof \Thelia\Model\Template) {
            return $this
                ->addUsingAlias(ChoiceFilterTableMap::TEMPLATE_ID, $template->getId(), $comparison);
        } elseif ($template instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ChoiceFilterTableMap::TEMPLATE_ID, $template->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTemplate() only accepts arguments of type \Thelia\Model\Template or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Template relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function joinTemplate($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Template');

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
            $this->addJoinObject($join, 'Template');
        }

        return $this;
    }

    /**
     * Use the Template relation Template object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\TemplateQuery A secondary query class using the current class as primary query
     */
    public function useTemplateQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinTemplate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Template', '\Thelia\Model\TemplateQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildChoiceFilter $choiceFilter Object to remove from the list of results
     *
     * @return ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function prune($choiceFilter = null)
    {
        if ($choiceFilter) {
            $this->addUsingAlias(ChoiceFilterTableMap::ID, $choiceFilter->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the choice_filter table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChoiceFilterTableMap::DATABASE_NAME);
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
            ChoiceFilterTableMap::clearInstancePool();
            ChoiceFilterTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildChoiceFilter or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildChoiceFilter object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ChoiceFilterTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ChoiceFilterTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        ChoiceFilterTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ChoiceFilterTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ChoiceFilterTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ChoiceFilterTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ChoiceFilterTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ChoiceFilterTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ChoiceFilterTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildChoiceFilterQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ChoiceFilterTableMap::CREATED_AT);
    }

} // ChoiceFilterQuery
