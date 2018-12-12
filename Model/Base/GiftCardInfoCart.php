<?php

namespace TheliaGiftCard\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use TheliaGiftCard\Model\GiftCardInfoCartQuery as ChildGiftCardInfoCartQuery;
use TheliaGiftCard\Model\Map\GiftCardInfoCartTableMap;
use Thelia\Model\CartItemQuery;
use Thelia\Model\CartQuery;
use Thelia\Model\Cart as ChildCart;
use Thelia\Model\CartItem as ChildCartItem;

abstract class GiftCardInfoCart implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\TheliaGiftCard\\Model\\Map\\GiftCardInfoCartTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the order_product_id field.
     * @var        int
     */
    protected $order_product_id;

    /**
     * The value for the gift_card_id field.
     * @var        int
     */
    protected $gift_card_id;

    /**
     * The value for the cart_id field.
     * @var        int
     */
    protected $cart_id;

    /**
     * The value for the cart_item_id field.
     * @var        int
     */
    protected $cart_item_id;

    /**
     * The value for the sponsor_name field.
     * @var        string
     */
    protected $sponsor_name;

    /**
     * The value for the beneficiary_name field.
     * @var        string
     */
    protected $beneficiary_name;

    /**
     * The value for the beneficiary_message field.
     * @var        string
     */
    protected $beneficiary_message;

    /**
     * @var        Cart
     */
    protected $aCart;

    /**
     * @var        CartItem
     */
    protected $aCartItem;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of TheliaGiftCard\Model\Base\GiftCardInfoCart object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (Boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (Boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>GiftCardInfoCart</code> instance.  If
     * <code>obj</code> is an instance of <code>GiftCardInfoCart</code>, delegates to
     * <code>equals(GiftCardInfoCart)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        $thisclazz = get_class($this);
        if (!is_object($obj) || !($obj instanceof $thisclazz)) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey()
            || null === $obj->getPrimaryKey())  {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        if (null !== $this->getPrimaryKey()) {
            return crc32(serialize($this->getPrimaryKey()));
        }

        return crc32(serialize(clone $this));
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return GiftCardInfoCart The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     *
     * @return GiftCardInfoCart The current object, for fluid interface
     */
    public function importFrom($parser, $data)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), TableMap::TYPE_PHPNAME);

        return $this;
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return   int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [order_product_id] column value.
     *
     * @return   int
     */
    public function getOrderProductId()
    {

        return $this->order_product_id;
    }

    /**
     * Get the [gift_card_id] column value.
     *
     * @return   int
     */
    public function getGiftCardId()
    {

        return $this->gift_card_id;
    }

    /**
     * Get the [cart_id] column value.
     *
     * @return   int
     */
    public function getCartId()
    {

        return $this->cart_id;
    }

    /**
     * Get the [cart_item_id] column value.
     *
     * @return   int
     */
    public function getCartItemId()
    {

        return $this->cart_item_id;
    }

    /**
     * Get the [sponsor_name] column value.
     *
     * @return   string
     */
    public function getSponsorName()
    {

        return $this->sponsor_name;
    }

    /**
     * Get the [beneficiary_name] column value.
     *
     * @return   string
     */
    public function getBeneficiaryName()
    {

        return $this->beneficiary_name;
    }

    /**
     * Get the [beneficiary_message] column value.
     *
     * @return   string
     */
    public function getBeneficiaryMessage()
    {

        return $this->beneficiary_message;
    }

    /**
     * Set the value of [id] column.
     *
     * @param      int $v new value
     * @return   \TheliaGiftCard\Model\GiftCardInfoCart The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[GiftCardInfoCartTableMap::ID] = true;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [order_product_id] column.
     *
     * @param      int $v new value
     * @return   \TheliaGiftCard\Model\GiftCardInfoCart The current object (for fluent API support)
     */
    public function setOrderProductId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_product_id !== $v) {
            $this->order_product_id = $v;
            $this->modifiedColumns[GiftCardInfoCartTableMap::ORDER_PRODUCT_ID] = true;
        }


        return $this;
    } // setOrderProductId()

    /**
     * Set the value of [gift_card_id] column.
     *
     * @param      int $v new value
     * @return   \TheliaGiftCard\Model\GiftCardInfoCart The current object (for fluent API support)
     */
    public function setGiftCardId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->gift_card_id !== $v) {
            $this->gift_card_id = $v;
            $this->modifiedColumns[GiftCardInfoCartTableMap::GIFT_CARD_ID] = true;
        }


        return $this;
    } // setGiftCardId()

    /**
     * Set the value of [cart_id] column.
     *
     * @param      int $v new value
     * @return   \TheliaGiftCard\Model\GiftCardInfoCart The current object (for fluent API support)
     */
    public function setCartId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->cart_id !== $v) {
            $this->cart_id = $v;
            $this->modifiedColumns[GiftCardInfoCartTableMap::CART_ID] = true;
        }

        if ($this->aCart !== null && $this->aCart->getId() !== $v) {
            $this->aCart = null;
        }


        return $this;
    } // setCartId()

    /**
     * Set the value of [cart_item_id] column.
     *
     * @param      int $v new value
     * @return   \TheliaGiftCard\Model\GiftCardInfoCart The current object (for fluent API support)
     */
    public function setCartItemId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->cart_item_id !== $v) {
            $this->cart_item_id = $v;
            $this->modifiedColumns[GiftCardInfoCartTableMap::CART_ITEM_ID] = true;
        }

        if ($this->aCartItem !== null && $this->aCartItem->getId() !== $v) {
            $this->aCartItem = null;
        }


        return $this;
    } // setCartItemId()

    /**
     * Set the value of [sponsor_name] column.
     *
     * @param      string $v new value
     * @return   \TheliaGiftCard\Model\GiftCardInfoCart The current object (for fluent API support)
     */
    public function setSponsorName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->sponsor_name !== $v) {
            $this->sponsor_name = $v;
            $this->modifiedColumns[GiftCardInfoCartTableMap::SPONSOR_NAME] = true;
        }


        return $this;
    } // setSponsorName()

    /**
     * Set the value of [beneficiary_name] column.
     *
     * @param      string $v new value
     * @return   \TheliaGiftCard\Model\GiftCardInfoCart The current object (for fluent API support)
     */
    public function setBeneficiaryName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->beneficiary_name !== $v) {
            $this->beneficiary_name = $v;
            $this->modifiedColumns[GiftCardInfoCartTableMap::BENEFICIARY_NAME] = true;
        }


        return $this;
    } // setBeneficiaryName()

    /**
     * Set the value of [beneficiary_message] column.
     *
     * @param      string $v new value
     * @return   \TheliaGiftCard\Model\GiftCardInfoCart The current object (for fluent API support)
     */
    public function setBeneficiaryMessage($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->beneficiary_message !== $v) {
            $this->beneficiary_message = $v;
            $this->modifiedColumns[GiftCardInfoCartTableMap::BENEFICIARY_MESSAGE] = true;
        }


        return $this;
    } // setBeneficiaryMessage()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : GiftCardInfoCartTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : GiftCardInfoCartTableMap::translateFieldName('OrderProductId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_product_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : GiftCardInfoCartTableMap::translateFieldName('GiftCardId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->gift_card_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : GiftCardInfoCartTableMap::translateFieldName('CartId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cart_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : GiftCardInfoCartTableMap::translateFieldName('CartItemId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cart_item_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : GiftCardInfoCartTableMap::translateFieldName('SponsorName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sponsor_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : GiftCardInfoCartTableMap::translateFieldName('BeneficiaryName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->beneficiary_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : GiftCardInfoCartTableMap::translateFieldName('BeneficiaryMessage', TableMap::TYPE_PHPNAME, $indexType)];
            $this->beneficiary_message = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 8; // 8 = GiftCardInfoCartTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \TheliaGiftCard\Model\GiftCardInfoCart object", 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aCart !== null && $this->cart_id !== $this->aCart->getId()) {
            $this->aCart = null;
        }
        if ($this->aCartItem !== null && $this->cart_item_id !== $this->aCartItem->getId()) {
            $this->aCartItem = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(GiftCardInfoCartTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildGiftCardInfoCartQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCart = null;
            $this->aCartItem = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see GiftCardInfoCart::setDeleted()
     * @see GiftCardInfoCart::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(GiftCardInfoCartTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildGiftCardInfoCartQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(GiftCardInfoCartTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                GiftCardInfoCartTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCart !== null) {
                if ($this->aCart->isModified() || $this->aCart->isNew()) {
                    $affectedRows += $this->aCart->save($con);
                }
                $this->setCart($this->aCart);
            }

            if ($this->aCartItem !== null) {
                if ($this->aCartItem->isModified() || $this->aCartItem->isNew()) {
                    $affectedRows += $this->aCartItem->save($con);
                }
                $this->setCartItem($this->aCartItem);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[GiftCardInfoCartTableMap::ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . GiftCardInfoCartTableMap::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(GiftCardInfoCartTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(GiftCardInfoCartTableMap::ORDER_PRODUCT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'ORDER_PRODUCT_ID';
        }
        if ($this->isColumnModified(GiftCardInfoCartTableMap::GIFT_CARD_ID)) {
            $modifiedColumns[':p' . $index++]  = 'GIFT_CARD_ID';
        }
        if ($this->isColumnModified(GiftCardInfoCartTableMap::CART_ID)) {
            $modifiedColumns[':p' . $index++]  = 'CART_ID';
        }
        if ($this->isColumnModified(GiftCardInfoCartTableMap::CART_ITEM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'CART_ITEM_ID';
        }
        if ($this->isColumnModified(GiftCardInfoCartTableMap::SPONSOR_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'SPONSOR_NAME';
        }
        if ($this->isColumnModified(GiftCardInfoCartTableMap::BENEFICIARY_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'BENEFICIARY_NAME';
        }
        if ($this->isColumnModified(GiftCardInfoCartTableMap::BENEFICIARY_MESSAGE)) {
            $modifiedColumns[':p' . $index++]  = 'BENEFICIARY_MESSAGE';
        }

        $sql = sprintf(
            'INSERT INTO gift_card_info_cart (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'ID':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'ORDER_PRODUCT_ID':
                        $stmt->bindValue($identifier, $this->order_product_id, PDO::PARAM_INT);
                        break;
                    case 'GIFT_CARD_ID':
                        $stmt->bindValue($identifier, $this->gift_card_id, PDO::PARAM_INT);
                        break;
                    case 'CART_ID':
                        $stmt->bindValue($identifier, $this->cart_id, PDO::PARAM_INT);
                        break;
                    case 'CART_ITEM_ID':
                        $stmt->bindValue($identifier, $this->cart_item_id, PDO::PARAM_INT);
                        break;
                    case 'SPONSOR_NAME':
                        $stmt->bindValue($identifier, $this->sponsor_name, PDO::PARAM_STR);
                        break;
                    case 'BENEFICIARY_NAME':
                        $stmt->bindValue($identifier, $this->beneficiary_name, PDO::PARAM_STR);
                        break;
                    case 'BENEFICIARY_MESSAGE':
                        $stmt->bindValue($identifier, $this->beneficiary_message, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = GiftCardInfoCartTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getOrderProductId();
                break;
            case 2:
                return $this->getGiftCardId();
                break;
            case 3:
                return $this->getCartId();
                break;
            case 4:
                return $this->getCartItemId();
                break;
            case 5:
                return $this->getSponsorName();
                break;
            case 6:
                return $this->getBeneficiaryName();
                break;
            case 7:
                return $this->getBeneficiaryMessage();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['GiftCardInfoCart'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['GiftCardInfoCart'][$this->getPrimaryKey()] = true;
        $keys = GiftCardInfoCartTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getOrderProductId(),
            $keys[2] => $this->getGiftCardId(),
            $keys[3] => $this->getCartId(),
            $keys[4] => $this->getCartItemId(),
            $keys[5] => $this->getSponsorName(),
            $keys[6] => $this->getBeneficiaryName(),
            $keys[7] => $this->getBeneficiaryMessage(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCart) {
                $result['Cart'] = $this->aCart->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCartItem) {
                $result['CartItem'] = $this->aCartItem->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param      string $name
     * @param      mixed  $value field value
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return void
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = GiftCardInfoCartTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @param      mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setOrderProductId($value);
                break;
            case 2:
                $this->setGiftCardId($value);
                break;
            case 3:
                $this->setCartId($value);
                break;
            case 4:
                $this->setCartItemId($value);
                break;
            case 5:
                $this->setSponsorName($value);
                break;
            case 6:
                $this->setBeneficiaryName($value);
                break;
            case 7:
                $this->setBeneficiaryMessage($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = GiftCardInfoCartTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setOrderProductId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setGiftCardId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setCartId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setCartItemId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setSponsorName($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setBeneficiaryName($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setBeneficiaryMessage($arr[$keys[7]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(GiftCardInfoCartTableMap::DATABASE_NAME);

        if ($this->isColumnModified(GiftCardInfoCartTableMap::ID)) $criteria->add(GiftCardInfoCartTableMap::ID, $this->id);
        if ($this->isColumnModified(GiftCardInfoCartTableMap::ORDER_PRODUCT_ID)) $criteria->add(GiftCardInfoCartTableMap::ORDER_PRODUCT_ID, $this->order_product_id);
        if ($this->isColumnModified(GiftCardInfoCartTableMap::GIFT_CARD_ID)) $criteria->add(GiftCardInfoCartTableMap::GIFT_CARD_ID, $this->gift_card_id);
        if ($this->isColumnModified(GiftCardInfoCartTableMap::CART_ID)) $criteria->add(GiftCardInfoCartTableMap::CART_ID, $this->cart_id);
        if ($this->isColumnModified(GiftCardInfoCartTableMap::CART_ITEM_ID)) $criteria->add(GiftCardInfoCartTableMap::CART_ITEM_ID, $this->cart_item_id);
        if ($this->isColumnModified(GiftCardInfoCartTableMap::SPONSOR_NAME)) $criteria->add(GiftCardInfoCartTableMap::SPONSOR_NAME, $this->sponsor_name);
        if ($this->isColumnModified(GiftCardInfoCartTableMap::BENEFICIARY_NAME)) $criteria->add(GiftCardInfoCartTableMap::BENEFICIARY_NAME, $this->beneficiary_name);
        if ($this->isColumnModified(GiftCardInfoCartTableMap::BENEFICIARY_MESSAGE)) $criteria->add(GiftCardInfoCartTableMap::BENEFICIARY_MESSAGE, $this->beneficiary_message);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(GiftCardInfoCartTableMap::DATABASE_NAME);
        $criteria->add(GiftCardInfoCartTableMap::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return   int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \TheliaGiftCard\Model\GiftCardInfoCart (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setOrderProductId($this->getOrderProductId());
        $copyObj->setGiftCardId($this->getGiftCardId());
        $copyObj->setCartId($this->getCartId());
        $copyObj->setCartItemId($this->getCartItemId());
        $copyObj->setSponsorName($this->getSponsorName());
        $copyObj->setBeneficiaryName($this->getBeneficiaryName());
        $copyObj->setBeneficiaryMessage($this->getBeneficiaryMessage());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return                 \TheliaGiftCard\Model\GiftCardInfoCart Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildCart object.
     *
     * @param                  ChildCart $v
     * @return                 \TheliaGiftCard\Model\GiftCardInfoCart The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCart(ChildCart $v = null)
    {
        if ($v === null) {
            $this->setCartId(NULL);
        } else {
            $this->setCartId($v->getId());
        }

        $this->aCart = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCart object, it will not be re-added.
        if ($v !== null) {
            $v->addGiftCardInfoCart($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCart object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildCart The associated ChildCart object.
     * @throws PropelException
     */
    public function getCart(ConnectionInterface $con = null)
    {
        if ($this->aCart === null && ($this->cart_id !== null)) {
            $this->aCart = CartQuery::create()->findPk($this->cart_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCart->addGiftCardInfoCarts($this);
             */
        }

        return $this->aCart;
    }

    /**
     * Declares an association between this object and a ChildCartItem object.
     *
     * @param                  ChildCartItem $v
     * @return                 \TheliaGiftCard\Model\GiftCardInfoCart The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCartItem(ChildCartItem $v = null)
    {
        if ($v === null) {
            $this->setCartItemId(NULL);
        } else {
            $this->setCartItemId($v->getId());
        }

        $this->aCartItem = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCartItem object, it will not be re-added.
        if ($v !== null) {
            $v->addGiftCardInfoCart($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCartItem object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildCartItem The associated ChildCartItem object.
     * @throws PropelException
     */
    public function getCartItem(ConnectionInterface $con = null)
    {
        if ($this->aCartItem === null && ($this->cart_item_id !== null)) {
            $this->aCartItem = CartItemQuery::create()->findPk($this->cart_item_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCartItem->addGiftCardInfoCarts($this);
             */
        }

        return $this->aCartItem;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->order_product_id = null;
        $this->gift_card_id = null;
        $this->cart_id = null;
        $this->cart_item_id = null;
        $this->sponsor_name = null;
        $this->beneficiary_name = null;
        $this->beneficiary_message = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
        } // if ($deep)

        $this->aCart = null;
        $this->aCartItem = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(GiftCardInfoCartTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
