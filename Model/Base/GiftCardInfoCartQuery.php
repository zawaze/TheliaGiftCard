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
use TheliaGiftCard\Model\GiftCardInfoCart as ChildGiftCardInfoCart;
use TheliaGiftCard\Model\GiftCardInfoCartQuery as ChildGiftCardInfoCartQuery;
use TheliaGiftCard\Model\Map\GiftCardInfoCartTableMap;
use Thelia\Model\Cart;
use Thelia\Model\CartItem;

/**
 * Base class that represents a query for the 'gift_card_info_cart' table.
 *
 *
 *
 * @method     ChildGiftCardInfoCartQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildGiftCardInfoCartQuery orderByOrderProductId($order = Criteria::ASC) Order by the order_product_id column
 * @method     ChildGiftCardInfoCartQuery orderByGiftCardId($order = Criteria::ASC) Order by the gift_card_id column
 * @method     ChildGiftCardInfoCartQuery orderByCartId($order = Criteria::ASC) Order by the cart_id column
 * @method     ChildGiftCardInfoCartQuery orderByCartItemId($order = Criteria::ASC) Order by the cart_item_id column
 * @method     ChildGiftCardInfoCartQuery orderBySponsorName($order = Criteria::ASC) Order by the sponsor_name column
 * @method     ChildGiftCardInfoCartQuery orderByBeneficiaryName($order = Criteria::ASC) Order by the beneficiary_name column
 * @method     ChildGiftCardInfoCartQuery orderByBeneficiaryMessage($order = Criteria::ASC) Order by the beneficiary_message column
 *
 * @method     ChildGiftCardInfoCartQuery groupById() Group by the id column
 * @method     ChildGiftCardInfoCartQuery groupByOrderProductId() Group by the order_product_id column
 * @method     ChildGiftCardInfoCartQuery groupByGiftCardId() Group by the gift_card_id column
 * @method     ChildGiftCardInfoCartQuery groupByCartId() Group by the cart_id column
 * @method     ChildGiftCardInfoCartQuery groupByCartItemId() Group by the cart_item_id column
 * @method     ChildGiftCardInfoCartQuery groupBySponsorName() Group by the sponsor_name column
 * @method     ChildGiftCardInfoCartQuery groupByBeneficiaryName() Group by the beneficiary_name column
 * @method     ChildGiftCardInfoCartQuery groupByBeneficiaryMessage() Group by the beneficiary_message column
 *
 * @method     ChildGiftCardInfoCartQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildGiftCardInfoCartQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildGiftCardInfoCartQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildGiftCardInfoCartQuery leftJoinCart($relationAlias = null) Adds a LEFT JOIN clause to the query using the Cart relation
 * @method     ChildGiftCardInfoCartQuery rightJoinCart($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Cart relation
 * @method     ChildGiftCardInfoCartQuery innerJoinCart($relationAlias = null) Adds a INNER JOIN clause to the query using the Cart relation
 *
 * @method     ChildGiftCardInfoCartQuery leftJoinCartItem($relationAlias = null) Adds a LEFT JOIN clause to the query using the CartItem relation
 * @method     ChildGiftCardInfoCartQuery rightJoinCartItem($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CartItem relation
 * @method     ChildGiftCardInfoCartQuery innerJoinCartItem($relationAlias = null) Adds a INNER JOIN clause to the query using the CartItem relation
 *
 * @method     ChildGiftCardInfoCart findOne(ConnectionInterface $con = null) Return the first ChildGiftCardInfoCart matching the query
 * @method     ChildGiftCardInfoCart findOneOrCreate(ConnectionInterface $con = null) Return the first ChildGiftCardInfoCart matching the query, or a new ChildGiftCardInfoCart object populated from the query conditions when no match is found
 *
 * @method     ChildGiftCardInfoCart findOneById(int $id) Return the first ChildGiftCardInfoCart filtered by the id column
 * @method     ChildGiftCardInfoCart findOneByOrderProductId(int $order_product_id) Return the first ChildGiftCardInfoCart filtered by the order_product_id column
 * @method     ChildGiftCardInfoCart findOneByGiftCardId(int $gift_card_id) Return the first ChildGiftCardInfoCart filtered by the gift_card_id column
 * @method     ChildGiftCardInfoCart findOneByCartId(int $cart_id) Return the first ChildGiftCardInfoCart filtered by the cart_id column
 * @method     ChildGiftCardInfoCart findOneByCartItemId(int $cart_item_id) Return the first ChildGiftCardInfoCart filtered by the cart_item_id column
 * @method     ChildGiftCardInfoCart findOneBySponsorName(string $sponsor_name) Return the first ChildGiftCardInfoCart filtered by the sponsor_name column
 * @method     ChildGiftCardInfoCart findOneByBeneficiaryName(string $beneficiary_name) Return the first ChildGiftCardInfoCart filtered by the beneficiary_name column
 * @method     ChildGiftCardInfoCart findOneByBeneficiaryMessage(string $beneficiary_message) Return the first ChildGiftCardInfoCart filtered by the beneficiary_message column
 *
 * @method     array findById(int $id) Return ChildGiftCardInfoCart objects filtered by the id column
 * @method     array findByOrderProductId(int $order_product_id) Return ChildGiftCardInfoCart objects filtered by the order_product_id column
 * @method     array findByGiftCardId(int $gift_card_id) Return ChildGiftCardInfoCart objects filtered by the gift_card_id column
 * @method     array findByCartId(int $cart_id) Return ChildGiftCardInfoCart objects filtered by the cart_id column
 * @method     array findByCartItemId(int $cart_item_id) Return ChildGiftCardInfoCart objects filtered by the cart_item_id column
 * @method     array findBySponsorName(string $sponsor_name) Return ChildGiftCardInfoCart objects filtered by the sponsor_name column
 * @method     array findByBeneficiaryName(string $beneficiary_name) Return ChildGiftCardInfoCart objects filtered by the beneficiary_name column
 * @method     array findByBeneficiaryMessage(string $beneficiary_message) Return ChildGiftCardInfoCart objects filtered by the beneficiary_message column
 *
 */
abstract class GiftCardInfoCartQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \TheliaGiftCard\Model\Base\GiftCardInfoCartQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\TheliaGiftCard\\Model\\GiftCardInfoCart', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildGiftCardInfoCartQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildGiftCardInfoCartQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \TheliaGiftCard\Model\GiftCardInfoCartQuery) {
            return $criteria;
        }
        $query = new \TheliaGiftCard\Model\GiftCardInfoCartQuery();
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
     * @return ChildGiftCardInfoCart|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = GiftCardInfoCartTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(GiftCardInfoCartTableMap::DATABASE_NAME);
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
     * @return   ChildGiftCardInfoCart A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, ORDER_PRODUCT_ID, GIFT_CARD_ID, CART_ID, CART_ITEM_ID, SPONSOR_NAME, BENEFICIARY_NAME, BENEFICIARY_MESSAGE FROM gift_card_info_cart WHERE ID = :p0';
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
            $obj = new ChildGiftCardInfoCart();
            $obj->hydrate($row);
            GiftCardInfoCartTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildGiftCardInfoCart|array|mixed the result, formatted by the current formatter
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
     * @return ChildGiftCardInfoCartQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(GiftCardInfoCartTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildGiftCardInfoCartQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(GiftCardInfoCartTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildGiftCardInfoCartQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(GiftCardInfoCartTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(GiftCardInfoCartTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GiftCardInfoCartTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the order_product_id column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderProductId(1234); // WHERE order_product_id = 1234
     * $query->filterByOrderProductId(array(12, 34)); // WHERE order_product_id IN (12, 34)
     * $query->filterByOrderProductId(array('min' => 12)); // WHERE order_product_id > 12
     * </code>
     *
     * @param     mixed $orderProductId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGiftCardInfoCartQuery The current query, for fluid interface
     */
    public function filterByOrderProductId($orderProductId = null, $comparison = null)
    {
        if (is_array($orderProductId)) {
            $useMinMax = false;
            if (isset($orderProductId['min'])) {
                $this->addUsingAlias(GiftCardInfoCartTableMap::ORDER_PRODUCT_ID, $orderProductId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderProductId['max'])) {
                $this->addUsingAlias(GiftCardInfoCartTableMap::ORDER_PRODUCT_ID, $orderProductId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GiftCardInfoCartTableMap::ORDER_PRODUCT_ID, $orderProductId, $comparison);
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
     * @param     mixed $giftCardId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGiftCardInfoCartQuery The current query, for fluid interface
     */
    public function filterByGiftCardId($giftCardId = null, $comparison = null)
    {
        if (is_array($giftCardId)) {
            $useMinMax = false;
            if (isset($giftCardId['min'])) {
                $this->addUsingAlias(GiftCardInfoCartTableMap::GIFT_CARD_ID, $giftCardId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($giftCardId['max'])) {
                $this->addUsingAlias(GiftCardInfoCartTableMap::GIFT_CARD_ID, $giftCardId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GiftCardInfoCartTableMap::GIFT_CARD_ID, $giftCardId, $comparison);
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
     * @return ChildGiftCardInfoCartQuery The current query, for fluid interface
     */
    public function filterByCartId($cartId = null, $comparison = null)
    {
        if (is_array($cartId)) {
            $useMinMax = false;
            if (isset($cartId['min'])) {
                $this->addUsingAlias(GiftCardInfoCartTableMap::CART_ID, $cartId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cartId['max'])) {
                $this->addUsingAlias(GiftCardInfoCartTableMap::CART_ID, $cartId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GiftCardInfoCartTableMap::CART_ID, $cartId, $comparison);
    }

    /**
     * Filter the query on the cart_item_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCartItemId(1234); // WHERE cart_item_id = 1234
     * $query->filterByCartItemId(array(12, 34)); // WHERE cart_item_id IN (12, 34)
     * $query->filterByCartItemId(array('min' => 12)); // WHERE cart_item_id > 12
     * </code>
     *
     * @see       filterByCartItem()
     *
     * @param     mixed $cartItemId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGiftCardInfoCartQuery The current query, for fluid interface
     */
    public function filterByCartItemId($cartItemId = null, $comparison = null)
    {
        if (is_array($cartItemId)) {
            $useMinMax = false;
            if (isset($cartItemId['min'])) {
                $this->addUsingAlias(GiftCardInfoCartTableMap::CART_ITEM_ID, $cartItemId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cartItemId['max'])) {
                $this->addUsingAlias(GiftCardInfoCartTableMap::CART_ITEM_ID, $cartItemId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GiftCardInfoCartTableMap::CART_ITEM_ID, $cartItemId, $comparison);
    }

    /**
     * Filter the query on the sponsor_name column
     *
     * Example usage:
     * <code>
     * $query->filterBySponsorName('fooValue');   // WHERE sponsor_name = 'fooValue'
     * $query->filterBySponsorName('%fooValue%'); // WHERE sponsor_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $sponsorName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGiftCardInfoCartQuery The current query, for fluid interface
     */
    public function filterBySponsorName($sponsorName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($sponsorName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $sponsorName)) {
                $sponsorName = str_replace('*', '%', $sponsorName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(GiftCardInfoCartTableMap::SPONSOR_NAME, $sponsorName, $comparison);
    }

    /**
     * Filter the query on the beneficiary_name column
     *
     * Example usage:
     * <code>
     * $query->filterByBeneficiaryName('fooValue');   // WHERE beneficiary_name = 'fooValue'
     * $query->filterByBeneficiaryName('%fooValue%'); // WHERE beneficiary_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $beneficiaryName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGiftCardInfoCartQuery The current query, for fluid interface
     */
    public function filterByBeneficiaryName($beneficiaryName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($beneficiaryName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $beneficiaryName)) {
                $beneficiaryName = str_replace('*', '%', $beneficiaryName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(GiftCardInfoCartTableMap::BENEFICIARY_NAME, $beneficiaryName, $comparison);
    }

    /**
     * Filter the query on the beneficiary_message column
     *
     * Example usage:
     * <code>
     * $query->filterByBeneficiaryMessage('fooValue');   // WHERE beneficiary_message = 'fooValue'
     * $query->filterByBeneficiaryMessage('%fooValue%'); // WHERE beneficiary_message LIKE '%fooValue%'
     * </code>
     *
     * @param     string $beneficiaryMessage The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGiftCardInfoCartQuery The current query, for fluid interface
     */
    public function filterByBeneficiaryMessage($beneficiaryMessage = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($beneficiaryMessage)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $beneficiaryMessage)) {
                $beneficiaryMessage = str_replace('*', '%', $beneficiaryMessage);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(GiftCardInfoCartTableMap::BENEFICIARY_MESSAGE, $beneficiaryMessage, $comparison);
    }

    /**
     * Filter the query by a related \Thelia\Model\Cart object
     *
     * @param \Thelia\Model\Cart|ObjectCollection $cart The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGiftCardInfoCartQuery The current query, for fluid interface
     */
    public function filterByCart($cart, $comparison = null)
    {
        if ($cart instanceof \Thelia\Model\Cart) {
            return $this
                ->addUsingAlias(GiftCardInfoCartTableMap::CART_ID, $cart->getId(), $comparison);
        } elseif ($cart instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GiftCardInfoCartTableMap::CART_ID, $cart->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ChildGiftCardInfoCartQuery The current query, for fluid interface
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
     * Filter the query by a related \Thelia\Model\CartItem object
     *
     * @param \Thelia\Model\CartItem|ObjectCollection $cartItem The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGiftCardInfoCartQuery The current query, for fluid interface
     */
    public function filterByCartItem($cartItem, $comparison = null)
    {
        if ($cartItem instanceof \Thelia\Model\CartItem) {
            return $this
                ->addUsingAlias(GiftCardInfoCartTableMap::CART_ITEM_ID, $cartItem->getId(), $comparison);
        } elseif ($cartItem instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GiftCardInfoCartTableMap::CART_ITEM_ID, $cartItem->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCartItem() only accepts arguments of type \Thelia\Model\CartItem or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CartItem relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildGiftCardInfoCartQuery The current query, for fluid interface
     */
    public function joinCartItem($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CartItem');

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
            $this->addJoinObject($join, 'CartItem');
        }

        return $this;
    }

    /**
     * Use the CartItem relation CartItem object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\CartItemQuery A secondary query class using the current class as primary query
     */
    public function useCartItemQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCartItem($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CartItem', '\Thelia\Model\CartItemQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildGiftCardInfoCart $giftCardInfoCart Object to remove from the list of results
     *
     * @return ChildGiftCardInfoCartQuery The current query, for fluid interface
     */
    public function prune($giftCardInfoCart = null)
    {
        if ($giftCardInfoCart) {
            $this->addUsingAlias(GiftCardInfoCartTableMap::ID, $giftCardInfoCart->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the gift_card_info_cart table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GiftCardInfoCartTableMap::DATABASE_NAME);
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
            GiftCardInfoCartTableMap::clearInstancePool();
            GiftCardInfoCartTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildGiftCardInfoCart or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildGiftCardInfoCart object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(GiftCardInfoCartTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(GiftCardInfoCartTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        GiftCardInfoCartTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            GiftCardInfoCartTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // GiftCardInfoCartQuery
