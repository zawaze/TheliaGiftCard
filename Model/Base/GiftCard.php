<?php

namespace TheliaGiftCard\Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use TheliaGiftCard\Model\GiftCard as ChildGiftCard;
use TheliaGiftCard\Model\GiftCardCart as ChildGiftCardCart;
use TheliaGiftCard\Model\GiftCardCartQuery as ChildGiftCardCartQuery;
use TheliaGiftCard\Model\GiftCardCustomer as ChildGiftCardCustomer;
use TheliaGiftCard\Model\GiftCardCustomerQuery as ChildGiftCardCustomerQuery;
use TheliaGiftCard\Model\GiftCardOrder as ChildGiftCardOrder;
use TheliaGiftCard\Model\GiftCardOrderQuery as ChildGiftCardOrderQuery;
use TheliaGiftCard\Model\GiftCardQuery as ChildGiftCardQuery;
use TheliaGiftCard\Model\Map\GiftCardTableMap;
use Thelia\Model\Customer as ChildCustomer;
use Thelia\Model\Order as ChildOrder;
use Thelia\Model\Product as ChildProduct;
use Thelia\Model\CustomerQuery;
use Thelia\Model\OrderQuery;
use Thelia\Model\ProductQuery;

abstract class GiftCard implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\TheliaGiftCard\\Model\\Map\\GiftCardTableMap';


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
     * The value for the sponsor_customer_id field.
     * @var        int
     */
    protected $sponsor_customer_id;

    /**
     * The value for the order_id field.
     * @var        int
     */
    protected $order_id;

    /**
     * The value for the product_id field.
     * @var        int
     */
    protected $product_id;

    /**
     * The value for the code field.
     * @var        string
     */
    protected $code;

    /**
     * The value for the to_name field.
     * @var        string
     */
    protected $to_name;

    /**
     * The value for the to_message field.
     * @var        string
     */
    protected $to_message;

    /**
     * The value for the amount field.
     * @var        string
     */
    protected $amount;

    /**
     * The value for the status field.
     * @var        int
     */
    protected $status;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        Customer
     */
    protected $aCustomer;

    /**
     * @var        Order
     */
    protected $aOrder;

    /**
     * @var        Product
     */
    protected $aProduct;

    /**
     * @var        ObjectCollection|ChildGiftCardCustomer[] Collection to store aggregation of ChildGiftCardCustomer objects.
     */
    protected $collGiftCardCustomers;
    protected $collGiftCardCustomersPartial;

    /**
     * @var        ObjectCollection|ChildGiftCardCart[] Collection to store aggregation of ChildGiftCardCart objects.
     */
    protected $collGiftCardCarts;
    protected $collGiftCardCartsPartial;

    /**
     * @var        ObjectCollection|ChildGiftCardOrder[] Collection to store aggregation of ChildGiftCardOrder objects.
     */
    protected $collGiftCardOrders;
    protected $collGiftCardOrdersPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $giftCardCustomersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $giftCardCartsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $giftCardOrdersScheduledForDeletion = null;

    /**
     * Initializes internal state of TheliaGiftCard\Model\Base\GiftCard object.
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
     * Compares this with another <code>GiftCard</code> instance.  If
     * <code>obj</code> is an instance of <code>GiftCard</code>, delegates to
     * <code>equals(GiftCard)</code>.  Otherwise, returns <code>false</code>.
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
     * @return GiftCard The current object, for fluid interface
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
     * @return GiftCard The current object, for fluid interface
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
     * Get the [sponsor_customer_id] column value.
     *
     * @return   int
     */
    public function getSponsorCustomerId()
    {

        return $this->sponsor_customer_id;
    }

    /**
     * Get the [order_id] column value.
     *
     * @return   int
     */
    public function getOrderId()
    {

        return $this->order_id;
    }

    /**
     * Get the [product_id] column value.
     *
     * @return   int
     */
    public function getProductId()
    {

        return $this->product_id;
    }

    /**
     * Get the [code] column value.
     *
     * @return   string
     */
    public function getCode()
    {

        return $this->code;
    }

    /**
     * Get the [to_name] column value.
     *
     * @return   string
     */
    public function getToName()
    {

        return $this->to_name;
    }

    /**
     * Get the [to_message] column value.
     *
     * @return   string
     */
    public function getToMessage()
    {

        return $this->to_message;
    }

    /**
     * Get the [amount] column value.
     *
     * @return   string
     */
    public function getAmount()
    {

        return $this->amount;
    }

    /**
     * Get the [status] column value.
     *
     * @return   int
     */
    public function getStatus()
    {

        return $this->status;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTime ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTime ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param      int $v new value
     * @return   \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[GiftCardTableMap::ID] = true;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [sponsor_customer_id] column.
     *
     * @param      int $v new value
     * @return   \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     */
    public function setSponsorCustomerId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->sponsor_customer_id !== $v) {
            $this->sponsor_customer_id = $v;
            $this->modifiedColumns[GiftCardTableMap::SPONSOR_CUSTOMER_ID] = true;
        }

        if ($this->aCustomer !== null && $this->aCustomer->getId() !== $v) {
            $this->aCustomer = null;
        }


        return $this;
    } // setSponsorCustomerId()

    /**
     * Set the value of [order_id] column.
     *
     * @param      int $v new value
     * @return   \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     */
    public function setOrderId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_id !== $v) {
            $this->order_id = $v;
            $this->modifiedColumns[GiftCardTableMap::ORDER_ID] = true;
        }

        if ($this->aOrder !== null && $this->aOrder->getId() !== $v) {
            $this->aOrder = null;
        }


        return $this;
    } // setOrderId()

    /**
     * Set the value of [product_id] column.
     *
     * @param      int $v new value
     * @return   \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     */
    public function setProductId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->product_id !== $v) {
            $this->product_id = $v;
            $this->modifiedColumns[GiftCardTableMap::PRODUCT_ID] = true;
        }

        if ($this->aProduct !== null && $this->aProduct->getId() !== $v) {
            $this->aProduct = null;
        }


        return $this;
    } // setProductId()

    /**
     * Set the value of [code] column.
     *
     * @param      string $v new value
     * @return   \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[GiftCardTableMap::CODE] = true;
        }


        return $this;
    } // setCode()

    /**
     * Set the value of [to_name] column.
     *
     * @param      string $v new value
     * @return   \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     */
    public function setToName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->to_name !== $v) {
            $this->to_name = $v;
            $this->modifiedColumns[GiftCardTableMap::TO_NAME] = true;
        }


        return $this;
    } // setToName()

    /**
     * Set the value of [to_message] column.
     *
     * @param      string $v new value
     * @return   \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     */
    public function setToMessage($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->to_message !== $v) {
            $this->to_message = $v;
            $this->modifiedColumns[GiftCardTableMap::TO_MESSAGE] = true;
        }


        return $this;
    } // setToMessage()

    /**
     * Set the value of [amount] column.
     *
     * @param      string $v new value
     * @return   \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     */
    public function setAmount($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->amount !== $v) {
            $this->amount = $v;
            $this->modifiedColumns[GiftCardTableMap::AMOUNT] = true;
        }


        return $this;
    } // setAmount()

    /**
     * Set the value of [status] column.
     *
     * @param      int $v new value
     * @return   \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[GiftCardTableMap::STATUS] = true;
        }


        return $this;
    } // setStatus()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($dt !== $this->created_at) {
                $this->created_at = $dt;
                $this->modifiedColumns[GiftCardTableMap::CREATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($dt !== $this->updated_at) {
                $this->updated_at = $dt;
                $this->modifiedColumns[GiftCardTableMap::UPDATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

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


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : GiftCardTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : GiftCardTableMap::translateFieldName('SponsorCustomerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sponsor_customer_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : GiftCardTableMap::translateFieldName('OrderId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : GiftCardTableMap::translateFieldName('ProductId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->product_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : GiftCardTableMap::translateFieldName('Code', TableMap::TYPE_PHPNAME, $indexType)];
            $this->code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : GiftCardTableMap::translateFieldName('ToName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->to_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : GiftCardTableMap::translateFieldName('ToMessage', TableMap::TYPE_PHPNAME, $indexType)];
            $this->to_message = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : GiftCardTableMap::translateFieldName('Amount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->amount = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : GiftCardTableMap::translateFieldName('Status', TableMap::TYPE_PHPNAME, $indexType)];
            $this->status = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : GiftCardTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : GiftCardTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 11; // 11 = GiftCardTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \TheliaGiftCard\Model\GiftCard object", 0, $e);
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
        if ($this->aCustomer !== null && $this->sponsor_customer_id !== $this->aCustomer->getId()) {
            $this->aCustomer = null;
        }
        if ($this->aOrder !== null && $this->order_id !== $this->aOrder->getId()) {
            $this->aOrder = null;
        }
        if ($this->aProduct !== null && $this->product_id !== $this->aProduct->getId()) {
            $this->aProduct = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(GiftCardTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildGiftCardQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCustomer = null;
            $this->aOrder = null;
            $this->aProduct = null;
            $this->collGiftCardCustomers = null;

            $this->collGiftCardCarts = null;

            $this->collGiftCardOrders = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see GiftCard::setDeleted()
     * @see GiftCard::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(GiftCardTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildGiftCardQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(GiftCardTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(GiftCardTableMap::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(GiftCardTableMap::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(GiftCardTableMap::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                GiftCardTableMap::addInstanceToPool($this);
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

            if ($this->aCustomer !== null) {
                if ($this->aCustomer->isModified() || $this->aCustomer->isNew()) {
                    $affectedRows += $this->aCustomer->save($con);
                }
                $this->setCustomer($this->aCustomer);
            }

            if ($this->aOrder !== null) {
                if ($this->aOrder->isModified() || $this->aOrder->isNew()) {
                    $affectedRows += $this->aOrder->save($con);
                }
                $this->setOrder($this->aOrder);
            }

            if ($this->aProduct !== null) {
                if ($this->aProduct->isModified() || $this->aProduct->isNew()) {
                    $affectedRows += $this->aProduct->save($con);
                }
                $this->setProduct($this->aProduct);
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

            if ($this->giftCardCustomersScheduledForDeletion !== null) {
                if (!$this->giftCardCustomersScheduledForDeletion->isEmpty()) {
                    \TheliaGiftCard\Model\GiftCardCustomerQuery::create()
                        ->filterByPrimaryKeys($this->giftCardCustomersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->giftCardCustomersScheduledForDeletion = null;
                }
            }

                if ($this->collGiftCardCustomers !== null) {
            foreach ($this->collGiftCardCustomers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->giftCardCartsScheduledForDeletion !== null) {
                if (!$this->giftCardCartsScheduledForDeletion->isEmpty()) {
                    \TheliaGiftCard\Model\GiftCardCartQuery::create()
                        ->filterByPrimaryKeys($this->giftCardCartsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->giftCardCartsScheduledForDeletion = null;
                }
            }

                if ($this->collGiftCardCarts !== null) {
            foreach ($this->collGiftCardCarts as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->giftCardOrdersScheduledForDeletion !== null) {
                if (!$this->giftCardOrdersScheduledForDeletion->isEmpty()) {
                    \TheliaGiftCard\Model\GiftCardOrderQuery::create()
                        ->filterByPrimaryKeys($this->giftCardOrdersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->giftCardOrdersScheduledForDeletion = null;
                }
            }

                if ($this->collGiftCardOrders !== null) {
            foreach ($this->collGiftCardOrders as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

        $this->modifiedColumns[GiftCardTableMap::ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . GiftCardTableMap::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(GiftCardTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(GiftCardTableMap::SPONSOR_CUSTOMER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'SPONSOR_CUSTOMER_ID';
        }
        if ($this->isColumnModified(GiftCardTableMap::ORDER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'ORDER_ID';
        }
        if ($this->isColumnModified(GiftCardTableMap::PRODUCT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'PRODUCT_ID';
        }
        if ($this->isColumnModified(GiftCardTableMap::CODE)) {
            $modifiedColumns[':p' . $index++]  = 'CODE';
        }
        if ($this->isColumnModified(GiftCardTableMap::TO_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'TO_NAME';
        }
        if ($this->isColumnModified(GiftCardTableMap::TO_MESSAGE)) {
            $modifiedColumns[':p' . $index++]  = 'TO_MESSAGE';
        }
        if ($this->isColumnModified(GiftCardTableMap::AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'AMOUNT';
        }
        if ($this->isColumnModified(GiftCardTableMap::STATUS)) {
            $modifiedColumns[':p' . $index++]  = 'STATUS';
        }
        if ($this->isColumnModified(GiftCardTableMap::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'CREATED_AT';
        }
        if ($this->isColumnModified(GiftCardTableMap::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'UPDATED_AT';
        }

        $sql = sprintf(
            'INSERT INTO gift_card (%s) VALUES (%s)',
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
                    case 'SPONSOR_CUSTOMER_ID':
                        $stmt->bindValue($identifier, $this->sponsor_customer_id, PDO::PARAM_INT);
                        break;
                    case 'ORDER_ID':
                        $stmt->bindValue($identifier, $this->order_id, PDO::PARAM_INT);
                        break;
                    case 'PRODUCT_ID':
                        $stmt->bindValue($identifier, $this->product_id, PDO::PARAM_INT);
                        break;
                    case 'CODE':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case 'TO_NAME':
                        $stmt->bindValue($identifier, $this->to_name, PDO::PARAM_STR);
                        break;
                    case 'TO_MESSAGE':
                        $stmt->bindValue($identifier, $this->to_message, PDO::PARAM_STR);
                        break;
                    case 'AMOUNT':
                        $stmt->bindValue($identifier, $this->amount, PDO::PARAM_STR);
                        break;
                    case 'STATUS':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case 'CREATED_AT':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'UPDATED_AT':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
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
        $pos = GiftCardTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getSponsorCustomerId();
                break;
            case 2:
                return $this->getOrderId();
                break;
            case 3:
                return $this->getProductId();
                break;
            case 4:
                return $this->getCode();
                break;
            case 5:
                return $this->getToName();
                break;
            case 6:
                return $this->getToMessage();
                break;
            case 7:
                return $this->getAmount();
                break;
            case 8:
                return $this->getStatus();
                break;
            case 9:
                return $this->getCreatedAt();
                break;
            case 10:
                return $this->getUpdatedAt();
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
        if (isset($alreadyDumpedObjects['GiftCard'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['GiftCard'][$this->getPrimaryKey()] = true;
        $keys = GiftCardTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSponsorCustomerId(),
            $keys[2] => $this->getOrderId(),
            $keys[3] => $this->getProductId(),
            $keys[4] => $this->getCode(),
            $keys[5] => $this->getToName(),
            $keys[6] => $this->getToMessage(),
            $keys[7] => $this->getAmount(),
            $keys[8] => $this->getStatus(),
            $keys[9] => $this->getCreatedAt(),
            $keys[10] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCustomer) {
                $result['Customer'] = $this->aCustomer->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aOrder) {
                $result['Order'] = $this->aOrder->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aProduct) {
                $result['Product'] = $this->aProduct->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collGiftCardCustomers) {
                $result['GiftCardCustomers'] = $this->collGiftCardCustomers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collGiftCardCarts) {
                $result['GiftCardCarts'] = $this->collGiftCardCarts->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collGiftCardOrders) {
                $result['GiftCardOrders'] = $this->collGiftCardOrders->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = GiftCardTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setSponsorCustomerId($value);
                break;
            case 2:
                $this->setOrderId($value);
                break;
            case 3:
                $this->setProductId($value);
                break;
            case 4:
                $this->setCode($value);
                break;
            case 5:
                $this->setToName($value);
                break;
            case 6:
                $this->setToMessage($value);
                break;
            case 7:
                $this->setAmount($value);
                break;
            case 8:
                $this->setStatus($value);
                break;
            case 9:
                $this->setCreatedAt($value);
                break;
            case 10:
                $this->setUpdatedAt($value);
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
        $keys = GiftCardTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setSponsorCustomerId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setOrderId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setProductId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setCode($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setToName($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setToMessage($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setAmount($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setStatus($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setCreatedAt($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setUpdatedAt($arr[$keys[10]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(GiftCardTableMap::DATABASE_NAME);

        if ($this->isColumnModified(GiftCardTableMap::ID)) $criteria->add(GiftCardTableMap::ID, $this->id);
        if ($this->isColumnModified(GiftCardTableMap::SPONSOR_CUSTOMER_ID)) $criteria->add(GiftCardTableMap::SPONSOR_CUSTOMER_ID, $this->sponsor_customer_id);
        if ($this->isColumnModified(GiftCardTableMap::ORDER_ID)) $criteria->add(GiftCardTableMap::ORDER_ID, $this->order_id);
        if ($this->isColumnModified(GiftCardTableMap::PRODUCT_ID)) $criteria->add(GiftCardTableMap::PRODUCT_ID, $this->product_id);
        if ($this->isColumnModified(GiftCardTableMap::CODE)) $criteria->add(GiftCardTableMap::CODE, $this->code);
        if ($this->isColumnModified(GiftCardTableMap::TO_NAME)) $criteria->add(GiftCardTableMap::TO_NAME, $this->to_name);
        if ($this->isColumnModified(GiftCardTableMap::TO_MESSAGE)) $criteria->add(GiftCardTableMap::TO_MESSAGE, $this->to_message);
        if ($this->isColumnModified(GiftCardTableMap::AMOUNT)) $criteria->add(GiftCardTableMap::AMOUNT, $this->amount);
        if ($this->isColumnModified(GiftCardTableMap::STATUS)) $criteria->add(GiftCardTableMap::STATUS, $this->status);
        if ($this->isColumnModified(GiftCardTableMap::CREATED_AT)) $criteria->add(GiftCardTableMap::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(GiftCardTableMap::UPDATED_AT)) $criteria->add(GiftCardTableMap::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(GiftCardTableMap::DATABASE_NAME);
        $criteria->add(GiftCardTableMap::ID, $this->id);

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
     * @param      object $copyObj An object of \TheliaGiftCard\Model\GiftCard (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setSponsorCustomerId($this->getSponsorCustomerId());
        $copyObj->setOrderId($this->getOrderId());
        $copyObj->setProductId($this->getProductId());
        $copyObj->setCode($this->getCode());
        $copyObj->setToName($this->getToName());
        $copyObj->setToMessage($this->getToMessage());
        $copyObj->setAmount($this->getAmount());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getGiftCardCustomers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGiftCardCustomer($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getGiftCardCarts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGiftCardCart($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getGiftCardOrders() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGiftCardOrder($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

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
     * @return                 \TheliaGiftCard\Model\GiftCard Clone of current object.
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
     * Declares an association between this object and a ChildCustomer object.
     *
     * @param                  ChildCustomer $v
     * @return                 \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCustomer(ChildCustomer $v = null)
    {
        if ($v === null) {
            $this->setSponsorCustomerId(NULL);
        } else {
            $this->setSponsorCustomerId($v->getId());
        }

        $this->aCustomer = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCustomer object, it will not be re-added.
        if ($v !== null) {
            $v->addGiftCard($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCustomer object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildCustomer The associated ChildCustomer object.
     * @throws PropelException
     */
    public function getCustomer(ConnectionInterface $con = null)
    {
        if ($this->aCustomer === null && ($this->sponsor_customer_id !== null)) {
            $this->aCustomer = CustomerQuery::create()->findPk($this->sponsor_customer_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCustomer->addGiftCards($this);
             */
        }

        return $this->aCustomer;
    }

    /**
     * Declares an association between this object and a ChildOrder object.
     *
     * @param                  ChildOrder $v
     * @return                 \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     * @throws PropelException
     */
    public function setOrder(ChildOrder $v = null)
    {
        if ($v === null) {
            $this->setOrderId(NULL);
        } else {
            $this->setOrderId($v->getId());
        }

        $this->aOrder = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildOrder object, it will not be re-added.
        if ($v !== null) {
            $v->addGiftCard($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildOrder object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildOrder The associated ChildOrder object.
     * @throws PropelException
     */
    public function getOrder(ConnectionInterface $con = null)
    {
        if ($this->aOrder === null && ($this->order_id !== null)) {
            $this->aOrder = OrderQuery::create()->findPk($this->order_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aOrder->addGiftCards($this);
             */
        }

        return $this->aOrder;
    }

    /**
     * Declares an association between this object and a ChildProduct object.
     *
     * @param                  ChildProduct $v
     * @return                 \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     * @throws PropelException
     */
    public function setProduct(ChildProduct $v = null)
    {
        if ($v === null) {
            $this->setProductId(NULL);
        } else {
            $this->setProductId($v->getId());
        }

        $this->aProduct = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildProduct object, it will not be re-added.
        if ($v !== null) {
            $v->addGiftCard($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildProduct object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildProduct The associated ChildProduct object.
     * @throws PropelException
     */
    public function getProduct(ConnectionInterface $con = null)
    {
        if ($this->aProduct === null && ($this->product_id !== null)) {
            $this->aProduct = ProductQuery::create()->findPk($this->product_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aProduct->addGiftCards($this);
             */
        }

        return $this->aProduct;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('GiftCardCustomer' == $relationName) {
            return $this->initGiftCardCustomers();
        }
        if ('GiftCardCart' == $relationName) {
            return $this->initGiftCardCarts();
        }
        if ('GiftCardOrder' == $relationName) {
            return $this->initGiftCardOrders();
        }
    }

    /**
     * Clears out the collGiftCardCustomers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addGiftCardCustomers()
     */
    public function clearGiftCardCustomers()
    {
        $this->collGiftCardCustomers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collGiftCardCustomers collection loaded partially.
     */
    public function resetPartialGiftCardCustomers($v = true)
    {
        $this->collGiftCardCustomersPartial = $v;
    }

    /**
     * Initializes the collGiftCardCustomers collection.
     *
     * By default this just sets the collGiftCardCustomers collection to an empty array (like clearcollGiftCardCustomers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGiftCardCustomers($overrideExisting = true)
    {
        if (null !== $this->collGiftCardCustomers && !$overrideExisting) {
            return;
        }
        $this->collGiftCardCustomers = new ObjectCollection();
        $this->collGiftCardCustomers->setModel('\TheliaGiftCard\Model\GiftCardCustomer');
    }

    /**
     * Gets an array of ChildGiftCardCustomer objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGiftCard is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildGiftCardCustomer[] List of ChildGiftCardCustomer objects
     * @throws PropelException
     */
    public function getGiftCardCustomers($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collGiftCardCustomersPartial && !$this->isNew();
        if (null === $this->collGiftCardCustomers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGiftCardCustomers) {
                // return empty collection
                $this->initGiftCardCustomers();
            } else {
                $collGiftCardCustomers = ChildGiftCardCustomerQuery::create(null, $criteria)
                    ->filterByGiftCard($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collGiftCardCustomersPartial && count($collGiftCardCustomers)) {
                        $this->initGiftCardCustomers(false);

                        foreach ($collGiftCardCustomers as $obj) {
                            if (false == $this->collGiftCardCustomers->contains($obj)) {
                                $this->collGiftCardCustomers->append($obj);
                            }
                        }

                        $this->collGiftCardCustomersPartial = true;
                    }

                    reset($collGiftCardCustomers);

                    return $collGiftCardCustomers;
                }

                if ($partial && $this->collGiftCardCustomers) {
                    foreach ($this->collGiftCardCustomers as $obj) {
                        if ($obj->isNew()) {
                            $collGiftCardCustomers[] = $obj;
                        }
                    }
                }

                $this->collGiftCardCustomers = $collGiftCardCustomers;
                $this->collGiftCardCustomersPartial = false;
            }
        }

        return $this->collGiftCardCustomers;
    }

    /**
     * Sets a collection of GiftCardCustomer objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $giftCardCustomers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildGiftCard The current object (for fluent API support)
     */
    public function setGiftCardCustomers(Collection $giftCardCustomers, ConnectionInterface $con = null)
    {
        $giftCardCustomersToDelete = $this->getGiftCardCustomers(new Criteria(), $con)->diff($giftCardCustomers);


        $this->giftCardCustomersScheduledForDeletion = $giftCardCustomersToDelete;

        foreach ($giftCardCustomersToDelete as $giftCardCustomerRemoved) {
            $giftCardCustomerRemoved->setGiftCard(null);
        }

        $this->collGiftCardCustomers = null;
        foreach ($giftCardCustomers as $giftCardCustomer) {
            $this->addGiftCardCustomer($giftCardCustomer);
        }

        $this->collGiftCardCustomers = $giftCardCustomers;
        $this->collGiftCardCustomersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related GiftCardCustomer objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related GiftCardCustomer objects.
     * @throws PropelException
     */
    public function countGiftCardCustomers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collGiftCardCustomersPartial && !$this->isNew();
        if (null === $this->collGiftCardCustomers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGiftCardCustomers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getGiftCardCustomers());
            }

            $query = ChildGiftCardCustomerQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGiftCard($this)
                ->count($con);
        }

        return count($this->collGiftCardCustomers);
    }

    /**
     * Method called to associate a ChildGiftCardCustomer object to this object
     * through the ChildGiftCardCustomer foreign key attribute.
     *
     * @param    ChildGiftCardCustomer $l ChildGiftCardCustomer
     * @return   \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     */
    public function addGiftCardCustomer(ChildGiftCardCustomer $l)
    {
        if ($this->collGiftCardCustomers === null) {
            $this->initGiftCardCustomers();
            $this->collGiftCardCustomersPartial = true;
        }

        if (!in_array($l, $this->collGiftCardCustomers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddGiftCardCustomer($l);
        }

        return $this;
    }

    /**
     * @param GiftCardCustomer $giftCardCustomer The giftCardCustomer object to add.
     */
    protected function doAddGiftCardCustomer($giftCardCustomer)
    {
        $this->collGiftCardCustomers[]= $giftCardCustomer;
        $giftCardCustomer->setGiftCard($this);
    }

    /**
     * @param  GiftCardCustomer $giftCardCustomer The giftCardCustomer object to remove.
     * @return ChildGiftCard The current object (for fluent API support)
     */
    public function removeGiftCardCustomer($giftCardCustomer)
    {
        if ($this->getGiftCardCustomers()->contains($giftCardCustomer)) {
            $this->collGiftCardCustomers->remove($this->collGiftCardCustomers->search($giftCardCustomer));
            if (null === $this->giftCardCustomersScheduledForDeletion) {
                $this->giftCardCustomersScheduledForDeletion = clone $this->collGiftCardCustomers;
                $this->giftCardCustomersScheduledForDeletion->clear();
            }
            $this->giftCardCustomersScheduledForDeletion[]= clone $giftCardCustomer;
            $giftCardCustomer->setGiftCard(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this GiftCard is new, it will return
     * an empty collection; or if this GiftCard has previously
     * been saved, it will retrieve related GiftCardCustomers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in GiftCard.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildGiftCardCustomer[] List of ChildGiftCardCustomer objects
     */
    public function getGiftCardCustomersJoinCustomer($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildGiftCardCustomerQuery::create(null, $criteria);
        $query->joinWith('Customer', $joinBehavior);

        return $this->getGiftCardCustomers($query, $con);
    }

    /**
     * Clears out the collGiftCardCarts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addGiftCardCarts()
     */
    public function clearGiftCardCarts()
    {
        $this->collGiftCardCarts = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collGiftCardCarts collection loaded partially.
     */
    public function resetPartialGiftCardCarts($v = true)
    {
        $this->collGiftCardCartsPartial = $v;
    }

    /**
     * Initializes the collGiftCardCarts collection.
     *
     * By default this just sets the collGiftCardCarts collection to an empty array (like clearcollGiftCardCarts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGiftCardCarts($overrideExisting = true)
    {
        if (null !== $this->collGiftCardCarts && !$overrideExisting) {
            return;
        }
        $this->collGiftCardCarts = new ObjectCollection();
        $this->collGiftCardCarts->setModel('\TheliaGiftCard\Model\GiftCardCart');
    }

    /**
     * Gets an array of ChildGiftCardCart objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGiftCard is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildGiftCardCart[] List of ChildGiftCardCart objects
     * @throws PropelException
     */
    public function getGiftCardCarts($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collGiftCardCartsPartial && !$this->isNew();
        if (null === $this->collGiftCardCarts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGiftCardCarts) {
                // return empty collection
                $this->initGiftCardCarts();
            } else {
                $collGiftCardCarts = ChildGiftCardCartQuery::create(null, $criteria)
                    ->filterByGiftCard($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collGiftCardCartsPartial && count($collGiftCardCarts)) {
                        $this->initGiftCardCarts(false);

                        foreach ($collGiftCardCarts as $obj) {
                            if (false == $this->collGiftCardCarts->contains($obj)) {
                                $this->collGiftCardCarts->append($obj);
                            }
                        }

                        $this->collGiftCardCartsPartial = true;
                    }

                    reset($collGiftCardCarts);

                    return $collGiftCardCarts;
                }

                if ($partial && $this->collGiftCardCarts) {
                    foreach ($this->collGiftCardCarts as $obj) {
                        if ($obj->isNew()) {
                            $collGiftCardCarts[] = $obj;
                        }
                    }
                }

                $this->collGiftCardCarts = $collGiftCardCarts;
                $this->collGiftCardCartsPartial = false;
            }
        }

        return $this->collGiftCardCarts;
    }

    /**
     * Sets a collection of GiftCardCart objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $giftCardCarts A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildGiftCard The current object (for fluent API support)
     */
    public function setGiftCardCarts(Collection $giftCardCarts, ConnectionInterface $con = null)
    {
        $giftCardCartsToDelete = $this->getGiftCardCarts(new Criteria(), $con)->diff($giftCardCarts);


        $this->giftCardCartsScheduledForDeletion = $giftCardCartsToDelete;

        foreach ($giftCardCartsToDelete as $giftCardCartRemoved) {
            $giftCardCartRemoved->setGiftCard(null);
        }

        $this->collGiftCardCarts = null;
        foreach ($giftCardCarts as $giftCardCart) {
            $this->addGiftCardCart($giftCardCart);
        }

        $this->collGiftCardCarts = $giftCardCarts;
        $this->collGiftCardCartsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related GiftCardCart objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related GiftCardCart objects.
     * @throws PropelException
     */
    public function countGiftCardCarts(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collGiftCardCartsPartial && !$this->isNew();
        if (null === $this->collGiftCardCarts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGiftCardCarts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getGiftCardCarts());
            }

            $query = ChildGiftCardCartQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGiftCard($this)
                ->count($con);
        }

        return count($this->collGiftCardCarts);
    }

    /**
     * Method called to associate a ChildGiftCardCart object to this object
     * through the ChildGiftCardCart foreign key attribute.
     *
     * @param    ChildGiftCardCart $l ChildGiftCardCart
     * @return   \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     */
    public function addGiftCardCart(ChildGiftCardCart $l)
    {
        if ($this->collGiftCardCarts === null) {
            $this->initGiftCardCarts();
            $this->collGiftCardCartsPartial = true;
        }

        if (!in_array($l, $this->collGiftCardCarts->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddGiftCardCart($l);
        }

        return $this;
    }

    /**
     * @param GiftCardCart $giftCardCart The giftCardCart object to add.
     */
    protected function doAddGiftCardCart($giftCardCart)
    {
        $this->collGiftCardCarts[]= $giftCardCart;
        $giftCardCart->setGiftCard($this);
    }

    /**
     * @param  GiftCardCart $giftCardCart The giftCardCart object to remove.
     * @return ChildGiftCard The current object (for fluent API support)
     */
    public function removeGiftCardCart($giftCardCart)
    {
        if ($this->getGiftCardCarts()->contains($giftCardCart)) {
            $this->collGiftCardCarts->remove($this->collGiftCardCarts->search($giftCardCart));
            if (null === $this->giftCardCartsScheduledForDeletion) {
                $this->giftCardCartsScheduledForDeletion = clone $this->collGiftCardCarts;
                $this->giftCardCartsScheduledForDeletion->clear();
            }
            $this->giftCardCartsScheduledForDeletion[]= $giftCardCart;
            $giftCardCart->setGiftCard(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this GiftCard is new, it will return
     * an empty collection; or if this GiftCard has previously
     * been saved, it will retrieve related GiftCardCarts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in GiftCard.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildGiftCardCart[] List of ChildGiftCardCart objects
     */
    public function getGiftCardCartsJoinCart($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildGiftCardCartQuery::create(null, $criteria);
        $query->joinWith('Cart', $joinBehavior);

        return $this->getGiftCardCarts($query, $con);
    }

    /**
     * Clears out the collGiftCardOrders collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addGiftCardOrders()
     */
    public function clearGiftCardOrders()
    {
        $this->collGiftCardOrders = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collGiftCardOrders collection loaded partially.
     */
    public function resetPartialGiftCardOrders($v = true)
    {
        $this->collGiftCardOrdersPartial = $v;
    }

    /**
     * Initializes the collGiftCardOrders collection.
     *
     * By default this just sets the collGiftCardOrders collection to an empty array (like clearcollGiftCardOrders());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGiftCardOrders($overrideExisting = true)
    {
        if (null !== $this->collGiftCardOrders && !$overrideExisting) {
            return;
        }
        $this->collGiftCardOrders = new ObjectCollection();
        $this->collGiftCardOrders->setModel('\TheliaGiftCard\Model\GiftCardOrder');
    }

    /**
     * Gets an array of ChildGiftCardOrder objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGiftCard is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildGiftCardOrder[] List of ChildGiftCardOrder objects
     * @throws PropelException
     */
    public function getGiftCardOrders($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collGiftCardOrdersPartial && !$this->isNew();
        if (null === $this->collGiftCardOrders || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGiftCardOrders) {
                // return empty collection
                $this->initGiftCardOrders();
            } else {
                $collGiftCardOrders = ChildGiftCardOrderQuery::create(null, $criteria)
                    ->filterByGiftCard($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collGiftCardOrdersPartial && count($collGiftCardOrders)) {
                        $this->initGiftCardOrders(false);

                        foreach ($collGiftCardOrders as $obj) {
                            if (false == $this->collGiftCardOrders->contains($obj)) {
                                $this->collGiftCardOrders->append($obj);
                            }
                        }

                        $this->collGiftCardOrdersPartial = true;
                    }

                    reset($collGiftCardOrders);

                    return $collGiftCardOrders;
                }

                if ($partial && $this->collGiftCardOrders) {
                    foreach ($this->collGiftCardOrders as $obj) {
                        if ($obj->isNew()) {
                            $collGiftCardOrders[] = $obj;
                        }
                    }
                }

                $this->collGiftCardOrders = $collGiftCardOrders;
                $this->collGiftCardOrdersPartial = false;
            }
        }

        return $this->collGiftCardOrders;
    }

    /**
     * Sets a collection of GiftCardOrder objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $giftCardOrders A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildGiftCard The current object (for fluent API support)
     */
    public function setGiftCardOrders(Collection $giftCardOrders, ConnectionInterface $con = null)
    {
        $giftCardOrdersToDelete = $this->getGiftCardOrders(new Criteria(), $con)->diff($giftCardOrders);


        $this->giftCardOrdersScheduledForDeletion = $giftCardOrdersToDelete;

        foreach ($giftCardOrdersToDelete as $giftCardOrderRemoved) {
            $giftCardOrderRemoved->setGiftCard(null);
        }

        $this->collGiftCardOrders = null;
        foreach ($giftCardOrders as $giftCardOrder) {
            $this->addGiftCardOrder($giftCardOrder);
        }

        $this->collGiftCardOrders = $giftCardOrders;
        $this->collGiftCardOrdersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related GiftCardOrder objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related GiftCardOrder objects.
     * @throws PropelException
     */
    public function countGiftCardOrders(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collGiftCardOrdersPartial && !$this->isNew();
        if (null === $this->collGiftCardOrders || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGiftCardOrders) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getGiftCardOrders());
            }

            $query = ChildGiftCardOrderQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGiftCard($this)
                ->count($con);
        }

        return count($this->collGiftCardOrders);
    }

    /**
     * Method called to associate a ChildGiftCardOrder object to this object
     * through the ChildGiftCardOrder foreign key attribute.
     *
     * @param    ChildGiftCardOrder $l ChildGiftCardOrder
     * @return   \TheliaGiftCard\Model\GiftCard The current object (for fluent API support)
     */
    public function addGiftCardOrder(ChildGiftCardOrder $l)
    {
        if ($this->collGiftCardOrders === null) {
            $this->initGiftCardOrders();
            $this->collGiftCardOrdersPartial = true;
        }

        if (!in_array($l, $this->collGiftCardOrders->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddGiftCardOrder($l);
        }

        return $this;
    }

    /**
     * @param GiftCardOrder $giftCardOrder The giftCardOrder object to add.
     */
    protected function doAddGiftCardOrder($giftCardOrder)
    {
        $this->collGiftCardOrders[]= $giftCardOrder;
        $giftCardOrder->setGiftCard($this);
    }

    /**
     * @param  GiftCardOrder $giftCardOrder The giftCardOrder object to remove.
     * @return ChildGiftCard The current object (for fluent API support)
     */
    public function removeGiftCardOrder($giftCardOrder)
    {
        if ($this->getGiftCardOrders()->contains($giftCardOrder)) {
            $this->collGiftCardOrders->remove($this->collGiftCardOrders->search($giftCardOrder));
            if (null === $this->giftCardOrdersScheduledForDeletion) {
                $this->giftCardOrdersScheduledForDeletion = clone $this->collGiftCardOrders;
                $this->giftCardOrdersScheduledForDeletion->clear();
            }
            $this->giftCardOrdersScheduledForDeletion[]= $giftCardOrder;
            $giftCardOrder->setGiftCard(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this GiftCard is new, it will return
     * an empty collection; or if this GiftCard has previously
     * been saved, it will retrieve related GiftCardOrders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in GiftCard.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildGiftCardOrder[] List of ChildGiftCardOrder objects
     */
    public function getGiftCardOrdersJoinOrder($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildGiftCardOrderQuery::create(null, $criteria);
        $query->joinWith('Order', $joinBehavior);

        return $this->getGiftCardOrders($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->sponsor_customer_id = null;
        $this->order_id = null;
        $this->product_id = null;
        $this->code = null;
        $this->to_name = null;
        $this->to_message = null;
        $this->amount = null;
        $this->status = null;
        $this->created_at = null;
        $this->updated_at = null;
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
            if ($this->collGiftCardCustomers) {
                foreach ($this->collGiftCardCustomers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGiftCardCarts) {
                foreach ($this->collGiftCardCarts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGiftCardOrders) {
                foreach ($this->collGiftCardOrders as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collGiftCardCustomers = null;
        $this->collGiftCardCarts = null;
        $this->collGiftCardOrders = null;
        $this->aCustomer = null;
        $this->aOrder = null;
        $this->aProduct = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(GiftCardTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     ChildGiftCard The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[GiftCardTableMap::UPDATED_AT] = true;

        return $this;
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
