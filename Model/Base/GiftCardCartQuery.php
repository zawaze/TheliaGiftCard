<?php

namespace TheliaGiftCard\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use TheliaGiftCard\Model\GiftCardCart as ChildGiftCardCart;
use TheliaGiftCard\Model\GiftCardCartQuery as ChildGiftCardCartQuery;
use TheliaGiftCard\Model\Map\GiftCardCartTableMap;
use Thelia\Model\Cart;

/**
 * Base class that represents a query for the 'gift_card_cart' table.
 *
 *
 *
 * @method     ChildGiftCardCartQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildGiftCardCartQuery orderByGiftCardId($order = Criteria::ASC) Order by the gift_card_id column
 * @method     ChildGiftCardCartQuery orderByCartId($order = Criteria::ASC) Order by the cart_id column
 * @method     ChildGiftCardCartQuery orderBySpendAmount($order = Criteria::ASC) Order by the spend_amount column
 * @method     ChildGiftCardCartQuery orderBySpendDelivery($order = Criteria::ASC) Order by the spend_delivery column
 * @method     ChildGiftCardCartQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildGiftCardCartQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildGiftCardCartQuery groupById() Group by the id column
 * @method     ChildGiftCardCartQuery groupByGiftCardId() Group by the gift_card_id column
 * @method     ChildGiftCardCartQuery groupByCartId() Group by the cart_id column
 * @method     ChildGiftCardCartQuery groupBySpendAmount() Group by the spend_amount column
 * @method     ChildGiftCardCartQuery groupBySpendDelivery() Group by the spend_delivery column
 * @method     ChildGiftCardCartQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildGiftCardCartQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildGiftCardCartQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildGiftCardCartQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildGiftCardCartQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildGiftCardCartQuery leftJoinCart($relationAlias = null) Adds a LEFT JOIN clause to the query using the Cart relation
 * @method     ChildGiftCardCartQuery rightJoinCart($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Cart relation
 * @method     ChildGiftCardCartQuery innerJoinCart($relationAlias = null) Adds a INNER JOIN clause to the query using the Cart relation
 *
 * @method     ChildGiftCardCartQuery leftJoinGiftCard($relationAlias = null) Adds a LEFT JOIN clause to the query using the GiftCard relation
 * @method     ChildGiftCardCartQuery rightJoinGiftCard($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GiftCard relation
 * @method     ChildGiftCardCartQuery innerJoinGiftCard($relationAlias = null) Adds a INNER JOIN clause to the query using the GiftCard relation
 *
 * @method     ChildGiftCardCart findOne(ConnectionInterface $con = null) Return the first ChildGiftCardCart matching the query
 * @method     ChildGiftCardCart findOneOrCreate(ConnectionInterface $con = null) Return the first ChildGiftCardCart matching the query, or a new ChildGiftCardCart object populated from the query conditions when no match is found
 *
 * @method     ChildGiftCardCart findOneById(int $id) Return the first ChildGiftCardCart filtered by the id column
 * @method     ChildGiftCardCart findOneByGiftCardId(int $gift_card_id) Return the first ChildGiftCardCart filtered by the gift_card_id column
 * @method     ChildGiftCardCart findOneByCartId(int $cart_id) Return the first ChildGiftCardCart filtered by the cart_id column
 * @method     ChildGiftCardCart findOneBySpendAmount(string $spend_amount) Return the first ChildGiftCardCart filtered by the spend_amount column
 * @method     ChildGiftCardCart findOneBySpendDelivery(string $spend_delivery) Return the first ChildGiftCardCart filtered by the spend_delivery column
 * @method     ChildGiftCardCart findOneByCreatedAt(string $created_at) Return the first ChildGiftCardCart filtered by the created_at column
 * @method     ChildGiftCardCart findOneByUpdatedAt(string $updated_at) Return the first ChildGiftCardCart filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildGiftCardCart objects filtered by the id column
 * @method     array findByGiftCardId(int $gift_card_id) Return ChildGiftCardCart objects filtered by the gift_card_id column
 * @method     array findByCartId(int $cart_id) Return ChildGiftCardCart objects filtered by the cart_id column
 * @method     array findBySpendAmount(string $spend_amount) Return ChildGiftCardCart objects filtered by the spend_amount column
 * @method     array findBySpendDelivery(string $spend_delivery) Return ChildGiftCardCart objects filtered by the spend_delivery column
 * @method     array findByCreatedAt(string $created_at) Return ChildGiftCardCart objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildGiftCardCart objects filtered by the updated_at column
 *
 */
abstract class GiftCardCartQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \TheliaGiftCard\Model\Base\GiftCardCartQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\TheliaGiftCard\\Model\\GiftCardCart', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildGiftCardCartQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildGiftCardCartQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \TheliaGiftCard\Model\GiftCardCartQuery) {
            return $criteria;
        }
        $query = new \TheliaGiftCard\Model\GiftCardCartQuery();
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
     * @return ChildGiftCardCart|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = GiftCardCartTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(GiftCardCartTableMap::DATABASE_NAME);
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
     * @return   ChildGiftCardCart A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, GIFT_CARD_ID, CART_ID, SPEND_AMOUNT, SPEND_DELIVERY, CREATED_AT, UPDATED_AT FROM gift_card_cart WHERE ID = :p0';
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
            $obj = new ChildGiftCardCart();
            $obj->hydrate($row);
            GiftCardCartTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildGiftCardCart|array|mixed the result, formatted by the current formatter
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
     * @return ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(GiftCardCartTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(GiftCardCartTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(GiftCardCartTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(GiftCardCartTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GiftCardCartTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the gift_card_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGiftCardId(1234); // WHERE gift_card_id = 1234
     * $query->filterByGiftCardId(array(12, 34)); // WHERE gift_card_id IN (12, 34)
     * $query->filterByGiftCardId(array('min' => 12)); // WHERE gift_card_id > 12
     * </code>
     *
     * @see       filterByGiftCard()
     *
     * @param     mixed $giftCardId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function filterByGiftCardId($giftCardId = null, $comparison = null)
    {
        if (is_array($giftCardId)) {
            $useMinMax = false;
            if (isset($giftCardId['min'])) {
                $this->addUsingAlias(GiftCardCartTableMap::GIFT_CARD_ID, $giftCardId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($giftCardId['max'])) {
                $this->addUsingAlias(GiftCardCartTableMap::GIFT_CARD_ID, $giftCardId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GiftCardCartTableMap::GIFT_CARD_ID, $giftCardId, $comparison);
    }

    /**
     * Filter the query on the cart_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCartId(1234); // WHERE cart_id = 1234
     * $query->filterByCartId(array(12, 34)); // WHERE cart_id IN (12, 34)
     * $query->filterByCartId(array('min' => 12)); // WHERE cart_id > 12
     * </code>
     *
     * @see       filterByCart()
     *
     * @param     mixed $cartId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function filterByCartId($cartId = null, $comparison = null)
    {
        if (is_array($cartId)) {
            $useMinMax = false;
            if (isset($cartId['min'])) {
                $this->addUsingAlias(GiftCardCartTableMap::CART_ID, $cartId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cartId['max'])) {
                $this->addUsingAlias(GiftCardCartTableMap::CART_ID, $cartId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GiftCardCartTableMap::CART_ID, $cartId, $comparison);
    }

    /**
     * Filter the query on the spend_amount column
     *
     * Example usage:
     * <code>
     * $query->filterBySpendAmount(1234); // WHERE spend_amount = 1234
     * $query->filterBySpendAmount(array(12, 34)); // WHERE spend_amount IN (12, 34)
     * $query->filterBySpendAmount(array('min' => 12)); // WHERE spend_amount > 12
     * </code>
     *
     * @param     mixed $spendAmount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function filterBySpendAmount($spendAmount = null, $comparison = null)
    {
        if (is_array($spendAmount)) {
            $useMinMax = false;
            if (isset($spendAmount['min'])) {
                $this->addUsingAlias(GiftCardCartTableMap::SPEND_AMOUNT, $spendAmount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($spendAmount['max'])) {
                $this->addUsingAlias(GiftCardCartTableMap::SPEND_AMOUNT, $spendAmount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GiftCardCartTableMap::SPEND_AMOUNT, $spendAmount, $comparison);
    }

    /**
     * Filter the query on the spend_delivery column
     *
     * Example usage:
     * <code>
     * $query->filterBySpendDelivery(1234); // WHERE spend_delivery = 1234
     * $query->filterBySpendDelivery(array(12, 34)); // WHERE spend_delivery IN (12, 34)
     * $query->filterBySpendDelivery(array('min' => 12)); // WHERE spend_delivery > 12
     * </code>
     *
     * @param     mixed $spendDelivery The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function filterBySpendDelivery($spendDelivery = null, $comparison = null)
    {
        if (is_array($spendDelivery)) {
            $useMinMax = false;
            if (isset($spendDelivery['min'])) {
                $this->addUsingAlias(GiftCardCartTableMap::SPEND_DELIVERY, $spendDelivery['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($spendDelivery['max'])) {
                $this->addUsingAlias(GiftCardCartTableMap::SPEND_DELIVERY, $spendDelivery['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GiftCardCartTableMap::SPEND_DELIVERY, $spendDelivery, $comparison);
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
     * @return ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(GiftCardCartTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(GiftCardCartTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GiftCardCartTableMap::CREATED_AT, $createdAt, $comparison);
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
     * @return ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(GiftCardCartTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(GiftCardCartTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GiftCardCartTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Thelia\Model\Cart object
     *
     * @param \Thelia\Model\Cart|ObjectCollection $cart The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function filterByCart($cart, $comparison = null)
    {
        if ($cart instanceof \Thelia\Model\Cart) {
            return $this
                ->addUsingAlias(GiftCardCartTableMap::CART_ID, $cart->getId(), $comparison);
        } elseif ($cart instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GiftCardCartTableMap::CART_ID, $cart->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCart() only accepts arguments of type \Thelia\Model\Cart or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Cart relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function joinCart($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Cart');

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
            $this->addJoinObject($join, 'Cart');
        }

        return $this;
    }

    /**
     * Use the Cart relation Cart object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\CartQuery A secondary query class using the current class as primary query
     */
    public function useCartQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCart($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Cart', '\Thelia\Model\CartQuery');
    }

    /**
     * Filter the query by a related \TheliaGiftCard\Model\GiftCard object
     *
     * @param \TheliaGiftCard\Model\GiftCard|ObjectCollection $giftCard The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function filterByGiftCard($giftCard, $comparison = null)
    {
        if ($giftCard instanceof \TheliaGiftCard\Model\GiftCard) {
            return $this
                ->addUsingAlias(GiftCardCartTableMap::GIFT_CARD_ID, $giftCard->getId(), $comparison);
        } elseif ($giftCard instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GiftCardCartTableMap::GIFT_CARD_ID, $giftCard->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByGiftCard() only accepts arguments of type \TheliaGiftCard\Model\GiftCard or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GiftCard relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function joinGiftCard($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GiftCard');

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
            $this->addJoinObject($join, 'GiftCard');
        }

        return $this;
    }

    /**
     * Use the GiftCard relation GiftCard object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \TheliaGiftCard\Model\GiftCardQuery A secondary query class using the current class as primary query
     */
    public function useGiftCardQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinGiftCard($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GiftCard', '\TheliaGiftCard\Model\GiftCardQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildGiftCardCart $giftCardCart Object to remove from the list of results
     *
     * @return ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function prune($giftCardCart = null)
    {
        if ($giftCardCart) {
            $this->addUsingAlias(GiftCardCartTableMap::ID, $giftCardCart->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the gift_card_cart table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GiftCardCartTableMap::DATABASE_NAME);
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
            GiftCardCartTableMap::clearInstancePool();
            GiftCardCartTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildGiftCardCart or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildGiftCardCart object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(GiftCardCartTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(GiftCardCartTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        GiftCardCartTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            GiftCardCartTableMap::clearRelatedInstancePool();
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
     * @return     ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(GiftCardCartTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(GiftCardCartTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(GiftCardCartTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(GiftCardCartTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(GiftCardCartTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildGiftCardCartQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(GiftCardCartTableMap::CREATED_AT);
    }

} // GiftCardCartQuery
