<?php

namespace ChoiceFilter\Model\Base;

use \Exception;
use \PDO;
use ChoiceFilter\Model\ChoiceFilter as ChildChoiceFilter;
use ChoiceFilter\Model\ChoiceFilterOther as ChildChoiceFilterOther;
use ChoiceFilter\Model\ChoiceFilterOtherI18n as ChildChoiceFilterOtherI18n;
use ChoiceFilter\Model\ChoiceFilterOtherI18nQuery as ChildChoiceFilterOtherI18nQuery;
use ChoiceFilter\Model\ChoiceFilterOtherQuery as ChildChoiceFilterOtherQuery;
use ChoiceFilter\Model\ChoiceFilterQuery as ChildChoiceFilterQuery;
use ChoiceFilter\Model\Map\ChoiceFilterOtherTableMap;
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

abstract class ChoiceFilterOther implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\ChoiceFilter\\Model\\Map\\ChoiceFilterOtherTableMap';


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
     * The value for the type field.
     * @var        string
     */
    protected $type;

    /**
     * The value for the visible field.
     * @var        boolean
     */
    protected $visible;

    /**
     * @var        ObjectCollection|ChildChoiceFilter[] Collection to store aggregation of ChildChoiceFilter objects.
     */
    protected $collChoiceFilters;
    protected $collChoiceFiltersPartial;

    /**
     * @var        ObjectCollection|ChildChoiceFilterOtherI18n[] Collection to store aggregation of ChildChoiceFilterOtherI18n objects.
     */
    protected $collChoiceFilterOtherI18ns;
    protected $collChoiceFilterOtherI18nsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // i18n behavior

    /**
     * Current locale
     * @var        string
     */
    protected $currentLocale = 'en_US';

    /**
     * Current translation objects
     * @var        array[ChildChoiceFilterOtherI18n]
     */
    protected $currentTranslations;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $choiceFiltersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $choiceFilterOtherI18nsScheduledForDeletion = null;

    /**
     * Initializes internal state of ChoiceFilter\Model\Base\ChoiceFilterOther object.
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
     * Compares this with another <code>ChoiceFilterOther</code> instance.  If
     * <code>obj</code> is an instance of <code>ChoiceFilterOther</code>, delegates to
     * <code>equals(ChoiceFilterOther)</code>.  Otherwise, returns <code>false</code>.
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
     * @return ChoiceFilterOther The current object, for fluid interface
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
     * @return ChoiceFilterOther The current object, for fluid interface
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
     * Get the [type] column value.
     *
     * @return   string
     */
    public function getType()
    {

        return $this->type;
    }

    /**
     * Get the [visible] column value.
     *
     * @return   boolean
     */
    public function getVisible()
    {

        return $this->visible;
    }

    /**
     * Set the value of [id] column.
     *
     * @param      int $v new value
     * @return   \ChoiceFilter\Model\ChoiceFilterOther The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[ChoiceFilterOtherTableMap::ID] = true;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [type] column.
     *
     * @param      string $v new value
     * @return   \ChoiceFilter\Model\ChoiceFilterOther The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[ChoiceFilterOtherTableMap::TYPE] = true;
        }


        return $this;
    } // setType()

    /**
     * Sets the value of the [visible] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param      boolean|integer|string $v The new value
     * @return   \ChoiceFilter\Model\ChoiceFilterOther The current object (for fluent API support)
     */
    public function setVisible($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->visible !== $v) {
            $this->visible = $v;
            $this->modifiedColumns[ChoiceFilterOtherTableMap::VISIBLE] = true;
        }


        return $this;
    } // setVisible()

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


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ChoiceFilterOtherTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ChoiceFilterOtherTableMap::translateFieldName('Type', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ChoiceFilterOtherTableMap::translateFieldName('Visible', TableMap::TYPE_PHPNAME, $indexType)];
            $this->visible = (null !== $col) ? (boolean) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = ChoiceFilterOtherTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \ChoiceFilter\Model\ChoiceFilterOther object", 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(ChoiceFilterOtherTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildChoiceFilterOtherQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collChoiceFilters = null;

            $this->collChoiceFilterOtherI18ns = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see ChoiceFilterOther::setDeleted()
     * @see ChoiceFilterOther::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChoiceFilterOtherTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildChoiceFilterOtherQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(ChoiceFilterOtherTableMap::DATABASE_NAME);
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
                ChoiceFilterOtherTableMap::addInstanceToPool($this);
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

            if ($this->choiceFiltersScheduledForDeletion !== null) {
                if (!$this->choiceFiltersScheduledForDeletion->isEmpty()) {
                    \ChoiceFilter\Model\ChoiceFilterQuery::create()
                        ->filterByPrimaryKeys($this->choiceFiltersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->choiceFiltersScheduledForDeletion = null;
                }
            }

                if ($this->collChoiceFilters !== null) {
            foreach ($this->collChoiceFilters as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->choiceFilterOtherI18nsScheduledForDeletion !== null) {
                if (!$this->choiceFilterOtherI18nsScheduledForDeletion->isEmpty()) {
                    \ChoiceFilter\Model\ChoiceFilterOtherI18nQuery::create()
                        ->filterByPrimaryKeys($this->choiceFilterOtherI18nsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->choiceFilterOtherI18nsScheduledForDeletion = null;
                }
            }

                if ($this->collChoiceFilterOtherI18ns !== null) {
            foreach ($this->collChoiceFilterOtherI18ns as $referrerFK) {
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

        $this->modifiedColumns[ChoiceFilterOtherTableMap::ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ChoiceFilterOtherTableMap::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ChoiceFilterOtherTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(ChoiceFilterOtherTableMap::TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'TYPE';
        }
        if ($this->isColumnModified(ChoiceFilterOtherTableMap::VISIBLE)) {
            $modifiedColumns[':p' . $index++]  = 'VISIBLE';
        }

        $sql = sprintf(
            'INSERT INTO choice_filter_other (%s) VALUES (%s)',
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
                    case 'TYPE':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_STR);
                        break;
                    case 'VISIBLE':
                        $stmt->bindValue($identifier, (int) $this->visible, PDO::PARAM_INT);
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
        $pos = ChoiceFilterOtherTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getType();
                break;
            case 2:
                return $this->getVisible();
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
        if (isset($alreadyDumpedObjects['ChoiceFilterOther'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['ChoiceFilterOther'][$this->getPrimaryKey()] = true;
        $keys = ChoiceFilterOtherTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getType(),
            $keys[2] => $this->getVisible(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collChoiceFilters) {
                $result['ChoiceFilters'] = $this->collChoiceFilters->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collChoiceFilterOtherI18ns) {
                $result['ChoiceFilterOtherI18ns'] = $this->collChoiceFilterOtherI18ns->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = ChoiceFilterOtherTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setType($value);
                break;
            case 2:
                $this->setVisible($value);
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
        $keys = ChoiceFilterOtherTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setType($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setVisible($arr[$keys[2]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ChoiceFilterOtherTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ChoiceFilterOtherTableMap::ID)) $criteria->add(ChoiceFilterOtherTableMap::ID, $this->id);
        if ($this->isColumnModified(ChoiceFilterOtherTableMap::TYPE)) $criteria->add(ChoiceFilterOtherTableMap::TYPE, $this->type);
        if ($this->isColumnModified(ChoiceFilterOtherTableMap::VISIBLE)) $criteria->add(ChoiceFilterOtherTableMap::VISIBLE, $this->visible);

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
        $criteria = new Criteria(ChoiceFilterOtherTableMap::DATABASE_NAME);
        $criteria->add(ChoiceFilterOtherTableMap::ID, $this->id);

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
     * @param      object $copyObj An object of \ChoiceFilter\Model\ChoiceFilterOther (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setType($this->getType());
        $copyObj->setVisible($this->getVisible());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getChoiceFilters() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addChoiceFilter($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getChoiceFilterOtherI18ns() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addChoiceFilterOtherI18n($relObj->copy($deepCopy));
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
     * @return                 \ChoiceFilter\Model\ChoiceFilterOther Clone of current object.
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('ChoiceFilter' == $relationName) {
            return $this->initChoiceFilters();
        }
        if ('ChoiceFilterOtherI18n' == $relationName) {
            return $this->initChoiceFilterOtherI18ns();
        }
    }

    /**
     * Clears out the collChoiceFilters collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addChoiceFilters()
     */
    public function clearChoiceFilters()
    {
        $this->collChoiceFilters = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collChoiceFilters collection loaded partially.
     */
    public function resetPartialChoiceFilters($v = true)
    {
        $this->collChoiceFiltersPartial = $v;
    }

    /**
     * Initializes the collChoiceFilters collection.
     *
     * By default this just sets the collChoiceFilters collection to an empty array (like clearcollChoiceFilters());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initChoiceFilters($overrideExisting = true)
    {
        if (null !== $this->collChoiceFilters && !$overrideExisting) {
            return;
        }
        $this->collChoiceFilters = new ObjectCollection();
        $this->collChoiceFilters->setModel('\ChoiceFilter\Model\ChoiceFilter');
    }

    /**
     * Gets an array of ChildChoiceFilter objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildChoiceFilterOther is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildChoiceFilter[] List of ChildChoiceFilter objects
     * @throws PropelException
     */
    public function getChoiceFilters($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collChoiceFiltersPartial && !$this->isNew();
        if (null === $this->collChoiceFilters || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collChoiceFilters) {
                // return empty collection
                $this->initChoiceFilters();
            } else {
                $collChoiceFilters = ChildChoiceFilterQuery::create(null, $criteria)
                    ->filterByChoiceFilterOther($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collChoiceFiltersPartial && count($collChoiceFilters)) {
                        $this->initChoiceFilters(false);

                        foreach ($collChoiceFilters as $obj) {
                            if (false == $this->collChoiceFilters->contains($obj)) {
                                $this->collChoiceFilters->append($obj);
                            }
                        }

                        $this->collChoiceFiltersPartial = true;
                    }

                    reset($collChoiceFilters);

                    return $collChoiceFilters;
                }

                if ($partial && $this->collChoiceFilters) {
                    foreach ($this->collChoiceFilters as $obj) {
                        if ($obj->isNew()) {
                            $collChoiceFilters[] = $obj;
                        }
                    }
                }

                $this->collChoiceFilters = $collChoiceFilters;
                $this->collChoiceFiltersPartial = false;
            }
        }

        return $this->collChoiceFilters;
    }

    /**
     * Sets a collection of ChoiceFilter objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $choiceFilters A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildChoiceFilterOther The current object (for fluent API support)
     */
    public function setChoiceFilters(Collection $choiceFilters, ConnectionInterface $con = null)
    {
        $choiceFiltersToDelete = $this->getChoiceFilters(new Criteria(), $con)->diff($choiceFilters);


        $this->choiceFiltersScheduledForDeletion = $choiceFiltersToDelete;

        foreach ($choiceFiltersToDelete as $choiceFilterRemoved) {
            $choiceFilterRemoved->setChoiceFilterOther(null);
        }

        $this->collChoiceFilters = null;
        foreach ($choiceFilters as $choiceFilter) {
            $this->addChoiceFilter($choiceFilter);
        }

        $this->collChoiceFilters = $choiceFilters;
        $this->collChoiceFiltersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ChoiceFilter objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ChoiceFilter objects.
     * @throws PropelException
     */
    public function countChoiceFilters(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collChoiceFiltersPartial && !$this->isNew();
        if (null === $this->collChoiceFilters || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collChoiceFilters) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getChoiceFilters());
            }

            $query = ChildChoiceFilterQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByChoiceFilterOther($this)
                ->count($con);
        }

        return count($this->collChoiceFilters);
    }

    /**
     * Method called to associate a ChildChoiceFilter object to this object
     * through the ChildChoiceFilter foreign key attribute.
     *
     * @param    ChildChoiceFilter $l ChildChoiceFilter
     * @return   \ChoiceFilter\Model\ChoiceFilterOther The current object (for fluent API support)
     */
    public function addChoiceFilter(ChildChoiceFilter $l)
    {
        if ($this->collChoiceFilters === null) {
            $this->initChoiceFilters();
            $this->collChoiceFiltersPartial = true;
        }

        if (!in_array($l, $this->collChoiceFilters->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddChoiceFilter($l);
        }

        return $this;
    }

    /**
     * @param ChoiceFilter $choiceFilter The choiceFilter object to add.
     */
    protected function doAddChoiceFilter($choiceFilter)
    {
        $this->collChoiceFilters[]= $choiceFilter;
        $choiceFilter->setChoiceFilterOther($this);
    }

    /**
     * @param  ChoiceFilter $choiceFilter The choiceFilter object to remove.
     * @return ChildChoiceFilterOther The current object (for fluent API support)
     */
    public function removeChoiceFilter($choiceFilter)
    {
        if ($this->getChoiceFilters()->contains($choiceFilter)) {
            $this->collChoiceFilters->remove($this->collChoiceFilters->search($choiceFilter));
            if (null === $this->choiceFiltersScheduledForDeletion) {
                $this->choiceFiltersScheduledForDeletion = clone $this->collChoiceFilters;
                $this->choiceFiltersScheduledForDeletion->clear();
            }
            $this->choiceFiltersScheduledForDeletion[]= $choiceFilter;
            $choiceFilter->setChoiceFilterOther(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ChoiceFilterOther is new, it will return
     * an empty collection; or if this ChoiceFilterOther has previously
     * been saved, it will retrieve related ChoiceFilters from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ChoiceFilterOther.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildChoiceFilter[] List of ChildChoiceFilter objects
     */
    public function getChoiceFiltersJoinAttribute($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildChoiceFilterQuery::create(null, $criteria);
        $query->joinWith('Attribute', $joinBehavior);

        return $this->getChoiceFilters($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ChoiceFilterOther is new, it will return
     * an empty collection; or if this ChoiceFilterOther has previously
     * been saved, it will retrieve related ChoiceFilters from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ChoiceFilterOther.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildChoiceFilter[] List of ChildChoiceFilter objects
     */
    public function getChoiceFiltersJoinFeature($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildChoiceFilterQuery::create(null, $criteria);
        $query->joinWith('Feature', $joinBehavior);

        return $this->getChoiceFilters($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ChoiceFilterOther is new, it will return
     * an empty collection; or if this ChoiceFilterOther has previously
     * been saved, it will retrieve related ChoiceFilters from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ChoiceFilterOther.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildChoiceFilter[] List of ChildChoiceFilter objects
     */
    public function getChoiceFiltersJoinCategory($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildChoiceFilterQuery::create(null, $criteria);
        $query->joinWith('Category', $joinBehavior);

        return $this->getChoiceFilters($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ChoiceFilterOther is new, it will return
     * an empty collection; or if this ChoiceFilterOther has previously
     * been saved, it will retrieve related ChoiceFilters from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ChoiceFilterOther.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildChoiceFilter[] List of ChildChoiceFilter objects
     */
    public function getChoiceFiltersJoinTemplate($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildChoiceFilterQuery::create(null, $criteria);
        $query->joinWith('Template', $joinBehavior);

        return $this->getChoiceFilters($query, $con);
    }

    /**
     * Clears out the collChoiceFilterOtherI18ns collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addChoiceFilterOtherI18ns()
     */
    public function clearChoiceFilterOtherI18ns()
    {
        $this->collChoiceFilterOtherI18ns = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collChoiceFilterOtherI18ns collection loaded partially.
     */
    public function resetPartialChoiceFilterOtherI18ns($v = true)
    {
        $this->collChoiceFilterOtherI18nsPartial = $v;
    }

    /**
     * Initializes the collChoiceFilterOtherI18ns collection.
     *
     * By default this just sets the collChoiceFilterOtherI18ns collection to an empty array (like clearcollChoiceFilterOtherI18ns());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initChoiceFilterOtherI18ns($overrideExisting = true)
    {
        if (null !== $this->collChoiceFilterOtherI18ns && !$overrideExisting) {
            return;
        }
        $this->collChoiceFilterOtherI18ns = new ObjectCollection();
        $this->collChoiceFilterOtherI18ns->setModel('\ChoiceFilter\Model\ChoiceFilterOtherI18n');
    }

    /**
     * Gets an array of ChildChoiceFilterOtherI18n objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildChoiceFilterOther is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildChoiceFilterOtherI18n[] List of ChildChoiceFilterOtherI18n objects
     * @throws PropelException
     */
    public function getChoiceFilterOtherI18ns($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collChoiceFilterOtherI18nsPartial && !$this->isNew();
        if (null === $this->collChoiceFilterOtherI18ns || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collChoiceFilterOtherI18ns) {
                // return empty collection
                $this->initChoiceFilterOtherI18ns();
            } else {
                $collChoiceFilterOtherI18ns = ChildChoiceFilterOtherI18nQuery::create(null, $criteria)
                    ->filterByChoiceFilterOther($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collChoiceFilterOtherI18nsPartial && count($collChoiceFilterOtherI18ns)) {
                        $this->initChoiceFilterOtherI18ns(false);

                        foreach ($collChoiceFilterOtherI18ns as $obj) {
                            if (false == $this->collChoiceFilterOtherI18ns->contains($obj)) {
                                $this->collChoiceFilterOtherI18ns->append($obj);
                            }
                        }

                        $this->collChoiceFilterOtherI18nsPartial = true;
                    }

                    reset($collChoiceFilterOtherI18ns);

                    return $collChoiceFilterOtherI18ns;
                }

                if ($partial && $this->collChoiceFilterOtherI18ns) {
                    foreach ($this->collChoiceFilterOtherI18ns as $obj) {
                        if ($obj->isNew()) {
                            $collChoiceFilterOtherI18ns[] = $obj;
                        }
                    }
                }

                $this->collChoiceFilterOtherI18ns = $collChoiceFilterOtherI18ns;
                $this->collChoiceFilterOtherI18nsPartial = false;
            }
        }

        return $this->collChoiceFilterOtherI18ns;
    }

    /**
     * Sets a collection of ChoiceFilterOtherI18n objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $choiceFilterOtherI18ns A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildChoiceFilterOther The current object (for fluent API support)
     */
    public function setChoiceFilterOtherI18ns(Collection $choiceFilterOtherI18ns, ConnectionInterface $con = null)
    {
        $choiceFilterOtherI18nsToDelete = $this->getChoiceFilterOtherI18ns(new Criteria(), $con)->diff($choiceFilterOtherI18ns);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->choiceFilterOtherI18nsScheduledForDeletion = clone $choiceFilterOtherI18nsToDelete;

        foreach ($choiceFilterOtherI18nsToDelete as $choiceFilterOtherI18nRemoved) {
            $choiceFilterOtherI18nRemoved->setChoiceFilterOther(null);
        }

        $this->collChoiceFilterOtherI18ns = null;
        foreach ($choiceFilterOtherI18ns as $choiceFilterOtherI18n) {
            $this->addChoiceFilterOtherI18n($choiceFilterOtherI18n);
        }

        $this->collChoiceFilterOtherI18ns = $choiceFilterOtherI18ns;
        $this->collChoiceFilterOtherI18nsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ChoiceFilterOtherI18n objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ChoiceFilterOtherI18n objects.
     * @throws PropelException
     */
    public function countChoiceFilterOtherI18ns(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collChoiceFilterOtherI18nsPartial && !$this->isNew();
        if (null === $this->collChoiceFilterOtherI18ns || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collChoiceFilterOtherI18ns) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getChoiceFilterOtherI18ns());
            }

            $query = ChildChoiceFilterOtherI18nQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByChoiceFilterOther($this)
                ->count($con);
        }

        return count($this->collChoiceFilterOtherI18ns);
    }

    /**
     * Method called to associate a ChildChoiceFilterOtherI18n object to this object
     * through the ChildChoiceFilterOtherI18n foreign key attribute.
     *
     * @param    ChildChoiceFilterOtherI18n $l ChildChoiceFilterOtherI18n
     * @return   \ChoiceFilter\Model\ChoiceFilterOther The current object (for fluent API support)
     */
    public function addChoiceFilterOtherI18n(ChildChoiceFilterOtherI18n $l)
    {
        if ($l && $locale = $l->getLocale()) {
            $this->setLocale($locale);
            $this->currentTranslations[$locale] = $l;
        }
        if ($this->collChoiceFilterOtherI18ns === null) {
            $this->initChoiceFilterOtherI18ns();
            $this->collChoiceFilterOtherI18nsPartial = true;
        }

        if (!in_array($l, $this->collChoiceFilterOtherI18ns->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddChoiceFilterOtherI18n($l);
        }

        return $this;
    }

    /**
     * @param ChoiceFilterOtherI18n $choiceFilterOtherI18n The choiceFilterOtherI18n object to add.
     */
    protected function doAddChoiceFilterOtherI18n($choiceFilterOtherI18n)
    {
        $this->collChoiceFilterOtherI18ns[]= $choiceFilterOtherI18n;
        $choiceFilterOtherI18n->setChoiceFilterOther($this);
    }

    /**
     * @param  ChoiceFilterOtherI18n $choiceFilterOtherI18n The choiceFilterOtherI18n object to remove.
     * @return ChildChoiceFilterOther The current object (for fluent API support)
     */
    public function removeChoiceFilterOtherI18n($choiceFilterOtherI18n)
    {
        if ($this->getChoiceFilterOtherI18ns()->contains($choiceFilterOtherI18n)) {
            $this->collChoiceFilterOtherI18ns->remove($this->collChoiceFilterOtherI18ns->search($choiceFilterOtherI18n));
            if (null === $this->choiceFilterOtherI18nsScheduledForDeletion) {
                $this->choiceFilterOtherI18nsScheduledForDeletion = clone $this->collChoiceFilterOtherI18ns;
                $this->choiceFilterOtherI18nsScheduledForDeletion->clear();
            }
            $this->choiceFilterOtherI18nsScheduledForDeletion[]= clone $choiceFilterOtherI18n;
            $choiceFilterOtherI18n->setChoiceFilterOther(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->type = null;
        $this->visible = null;
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
            if ($this->collChoiceFilters) {
                foreach ($this->collChoiceFilters as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collChoiceFilterOtherI18ns) {
                foreach ($this->collChoiceFilterOtherI18ns as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        // i18n behavior
        $this->currentLocale = 'en_US';
        $this->currentTranslations = null;

        $this->collChoiceFilters = null;
        $this->collChoiceFilterOtherI18ns = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ChoiceFilterOtherTableMap::DEFAULT_STRING_FORMAT);
    }

    // i18n behavior

    /**
     * Sets the locale for translations
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     *
     * @return    ChildChoiceFilterOther The current object (for fluent API support)
     */
    public function setLocale($locale = 'en_US')
    {
        $this->currentLocale = $locale;

        return $this;
    }

    /**
     * Gets the locale for translations
     *
     * @return    string $locale Locale to use for the translation, e.g. 'fr_FR'
     */
    public function getLocale()
    {
        return $this->currentLocale;
    }

    /**
     * Returns the current translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ChildChoiceFilterOtherI18n */
    public function getTranslation($locale = 'en_US', ConnectionInterface $con = null)
    {
        if (!isset($this->currentTranslations[$locale])) {
            if (null !== $this->collChoiceFilterOtherI18ns) {
                foreach ($this->collChoiceFilterOtherI18ns as $translation) {
                    if ($translation->getLocale() == $locale) {
                        $this->currentTranslations[$locale] = $translation;

                        return $translation;
                    }
                }
            }
            if ($this->isNew()) {
                $translation = new ChildChoiceFilterOtherI18n();
                $translation->setLocale($locale);
            } else {
                $translation = ChildChoiceFilterOtherI18nQuery::create()
                    ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                    ->findOneOrCreate($con);
                $this->currentTranslations[$locale] = $translation;
            }
            $this->addChoiceFilterOtherI18n($translation);
        }

        return $this->currentTranslations[$locale];
    }

    /**
     * Remove the translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return    ChildChoiceFilterOther The current object (for fluent API support)
     */
    public function removeTranslation($locale = 'en_US', ConnectionInterface $con = null)
    {
        if (!$this->isNew()) {
            ChildChoiceFilterOtherI18nQuery::create()
                ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                ->delete($con);
        }
        if (isset($this->currentTranslations[$locale])) {
            unset($this->currentTranslations[$locale]);
        }
        foreach ($this->collChoiceFilterOtherI18ns as $key => $translation) {
            if ($translation->getLocale() == $locale) {
                unset($this->collChoiceFilterOtherI18ns[$key]);
                break;
            }
        }

        return $this;
    }

    /**
     * Returns the current translation
     *
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ChildChoiceFilterOtherI18n */
    public function getCurrentTranslation(ConnectionInterface $con = null)
    {
        return $this->getTranslation($this->getLocale(), $con);
    }


        /**
         * Get the [title] column value.
         *
         * @return   string
         */
        public function getTitle()
        {
        return $this->getCurrentTranslation()->getTitle();
    }


        /**
         * Set the value of [title] column.
         *
         * @param      string $v new value
         * @return   \ChoiceFilter\Model\ChoiceFilterOtherI18n The current object (for fluent API support)
         */
        public function setTitle($v)
        {    $this->getCurrentTranslation()->setTitle($v);

        return $this;
    }


        /**
         * Get the [description] column value.
         *
         * @return   string
         */
        public function getDescription()
        {
        return $this->getCurrentTranslation()->getDescription();
    }


        /**
         * Set the value of [description] column.
         *
         * @param      string $v new value
         * @return   \ChoiceFilter\Model\ChoiceFilterOtherI18n The current object (for fluent API support)
         */
        public function setDescription($v)
        {    $this->getCurrentTranslation()->setDescription($v);

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
