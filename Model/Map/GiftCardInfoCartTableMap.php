<?php

namespace TheliaGiftCard\Model\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use TheliaGiftCard\Model\GiftCardInfoCart;
use TheliaGiftCard\Model\GiftCardInfoCartQuery;


/**
 * This class defines the structure of the 'gift_card_info_cart' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class GiftCardInfoCartTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'TheliaGiftCard.Model.Map.GiftCardInfoCartTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'gift_card_info_cart';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\TheliaGiftCard\\Model\\GiftCardInfoCart';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'TheliaGiftCard.Model.GiftCardInfoCart';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the ID field
     */
    const ID = 'gift_card_info_cart.ID';

    /**
     * the column name for the ORDER_PRODUCT_ID field
     */
    const ORDER_PRODUCT_ID = 'gift_card_info_cart.ORDER_PRODUCT_ID';

    /**
     * the column name for the GIFT_CARD_ID field
     */
    const GIFT_CARD_ID = 'gift_card_info_cart.GIFT_CARD_ID';

    /**
     * the column name for the CART_ID field
     */
    const CART_ID = 'gift_card_info_cart.CART_ID';

    /**
     * the column name for the CART_ITEM_ID field
     */
    const CART_ITEM_ID = 'gift_card_info_cart.CART_ITEM_ID';

    /**
     * the column name for the SPONSOR_NAME field
     */
    const SPONSOR_NAME = 'gift_card_info_cart.SPONSOR_NAME';

    /**
     * the column name for the BENEFICIARY_NAME field
     */
    const BENEFICIARY_NAME = 'gift_card_info_cart.BENEFICIARY_NAME';

    /**
     * the column name for the BENEFICIARY_MESSAGE field
     */
    const BENEFICIARY_MESSAGE = 'gift_card_info_cart.BENEFICIARY_MESSAGE';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'OrderProductId', 'GiftCardId', 'CartId', 'CartItemId', 'SponsorName', 'BeneficiaryName', 'BeneficiaryMessage', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'orderProductId', 'giftCardId', 'cartId', 'cartItemId', 'sponsorName', 'beneficiaryName', 'beneficiaryMessage', ),
        self::TYPE_COLNAME       => array(GiftCardInfoCartTableMap::ID, GiftCardInfoCartTableMap::ORDER_PRODUCT_ID, GiftCardInfoCartTableMap::GIFT_CARD_ID, GiftCardInfoCartTableMap::CART_ID, GiftCardInfoCartTableMap::CART_ITEM_ID, GiftCardInfoCartTableMap::SPONSOR_NAME, GiftCardInfoCartTableMap::BENEFICIARY_NAME, GiftCardInfoCartTableMap::BENEFICIARY_MESSAGE, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'ORDER_PRODUCT_ID', 'GIFT_CARD_ID', 'CART_ID', 'CART_ITEM_ID', 'SPONSOR_NAME', 'BENEFICIARY_NAME', 'BENEFICIARY_MESSAGE', ),
        self::TYPE_FIELDNAME     => array('id', 'order_product_id', 'gift_card_id', 'cart_id', 'cart_item_id', 'sponsor_name', 'beneficiary_name', 'beneficiary_message', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'OrderProductId' => 1, 'GiftCardId' => 2, 'CartId' => 3, 'CartItemId' => 4, 'SponsorName' => 5, 'BeneficiaryName' => 6, 'BeneficiaryMessage' => 7, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'orderProductId' => 1, 'giftCardId' => 2, 'cartId' => 3, 'cartItemId' => 4, 'sponsorName' => 5, 'beneficiaryName' => 6, 'beneficiaryMessage' => 7, ),
        self::TYPE_COLNAME       => array(GiftCardInfoCartTableMap::ID => 0, GiftCardInfoCartTableMap::ORDER_PRODUCT_ID => 1, GiftCardInfoCartTableMap::GIFT_CARD_ID => 2, GiftCardInfoCartTableMap::CART_ID => 3, GiftCardInfoCartTableMap::CART_ITEM_ID => 4, GiftCardInfoCartTableMap::SPONSOR_NAME => 5, GiftCardInfoCartTableMap::BENEFICIARY_NAME => 6, GiftCardInfoCartTableMap::BENEFICIARY_MESSAGE => 7, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'ORDER_PRODUCT_ID' => 1, 'GIFT_CARD_ID' => 2, 'CART_ID' => 3, 'CART_ITEM_ID' => 4, 'SPONSOR_NAME' => 5, 'BENEFICIARY_NAME' => 6, 'BENEFICIARY_MESSAGE' => 7, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'order_product_id' => 1, 'gift_card_id' => 2, 'cart_id' => 3, 'cart_item_id' => 4, 'sponsor_name' => 5, 'beneficiary_name' => 6, 'beneficiary_message' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('gift_card_info_cart');
        $this->setPhpName('GiftCardInfoCart');
        $this->setClassName('\\TheliaGiftCard\\Model\\GiftCardInfoCart');
        $this->setPackage('TheliaGiftCard.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('ORDER_PRODUCT_ID', 'OrderProductId', 'INTEGER', false, null, null);
        $this->addColumn('GIFT_CARD_ID', 'GiftCardId', 'INTEGER', false, null, null);
        $this->addForeignKey('CART_ID', 'CartId', 'INTEGER', 'cart', 'ID', true, null, null);
        $this->addForeignKey('CART_ITEM_ID', 'CartItemId', 'INTEGER', 'cart_item', 'ID', true, null, null);
        $this->addColumn('SPONSOR_NAME', 'SponsorName', 'VARCHAR', false, 16, null);
        $this->addColumn('BENEFICIARY_NAME', 'BeneficiaryName', 'VARCHAR', false, 16, null);
        $this->addColumn('BENEFICIARY_MESSAGE', 'BeneficiaryMessage', 'VARCHAR', false, 16, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Cart', '\\Thelia\\Model\\Cart', RelationMap::MANY_TO_ONE, array('cart_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('CartItem', '\\Thelia\\Model\\CartItem', RelationMap::MANY_TO_ONE, array('cart_item_id' => 'id', ), 'CASCADE', 'CASCADE');
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {

            return (int) $row[
                            $indexType == TableMap::TYPE_NUM
                            ? 0 + $offset
                            : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
                        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? GiftCardInfoCartTableMap::CLASS_DEFAULT : GiftCardInfoCartTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     * @return array (GiftCardInfoCart object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = GiftCardInfoCartTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = GiftCardInfoCartTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + GiftCardInfoCartTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = GiftCardInfoCartTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            GiftCardInfoCartTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = GiftCardInfoCartTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = GiftCardInfoCartTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                GiftCardInfoCartTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(GiftCardInfoCartTableMap::ID);
            $criteria->addSelectColumn(GiftCardInfoCartTableMap::ORDER_PRODUCT_ID);
            $criteria->addSelectColumn(GiftCardInfoCartTableMap::GIFT_CARD_ID);
            $criteria->addSelectColumn(GiftCardInfoCartTableMap::CART_ID);
            $criteria->addSelectColumn(GiftCardInfoCartTableMap::CART_ITEM_ID);
            $criteria->addSelectColumn(GiftCardInfoCartTableMap::SPONSOR_NAME);
            $criteria->addSelectColumn(GiftCardInfoCartTableMap::BENEFICIARY_NAME);
            $criteria->addSelectColumn(GiftCardInfoCartTableMap::BENEFICIARY_MESSAGE);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.ORDER_PRODUCT_ID');
            $criteria->addSelectColumn($alias . '.GIFT_CARD_ID');
            $criteria->addSelectColumn($alias . '.CART_ID');
            $criteria->addSelectColumn($alias . '.CART_ITEM_ID');
            $criteria->addSelectColumn($alias . '.SPONSOR_NAME');
            $criteria->addSelectColumn($alias . '.BENEFICIARY_NAME');
            $criteria->addSelectColumn($alias . '.BENEFICIARY_MESSAGE');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(GiftCardInfoCartTableMap::DATABASE_NAME)->getTable(GiftCardInfoCartTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(GiftCardInfoCartTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(GiftCardInfoCartTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new GiftCardInfoCartTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a GiftCardInfoCart or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or GiftCardInfoCart object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GiftCardInfoCartTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \TheliaGiftCard\Model\GiftCardInfoCart) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(GiftCardInfoCartTableMap::DATABASE_NAME);
            $criteria->add(GiftCardInfoCartTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = GiftCardInfoCartQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { GiftCardInfoCartTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { GiftCardInfoCartTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the gift_card_info_cart table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return GiftCardInfoCartQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a GiftCardInfoCart or Criteria object.
     *
     * @param mixed               $criteria Criteria or GiftCardInfoCart object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GiftCardInfoCartTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from GiftCardInfoCart object
        }

        if ($criteria->containsKey(GiftCardInfoCartTableMap::ID) && $criteria->keyContainsValue(GiftCardInfoCartTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.GiftCardInfoCartTableMap::ID.')');
        }


        // Set the correct dbName
        $query = GiftCardInfoCartQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // GiftCardInfoCartTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
GiftCardInfoCartTableMap::buildTableMap();
