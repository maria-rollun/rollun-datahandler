<?php

namespace rollun\datanadler\Processor;

/**
 * Class Concat
 * @package rollun\datanadler\Processor
 */
class Concat extends AbstractProcessor
{
    /**
     * Array of data store column which will be concat to create hash
     * Keys of array is a priority on which columns will concat
     *
     * Example:
     * [
     *      1 => 'make',
     *      2 => 'model',
     *      3 => 'year',
     * ]
     *
     * @var array
     */
    protected $columns;

    /**
     * Column to write result of concating
     *
     * @var string
     */
    protected $columnToWrite;

    /**
     * @var string
     */
    protected $delimiter = '_';

    /**
     * Minimum count of columns - 2
     *
     * @param array $columns
     */
    public function setColumns(array $columns)
    {
        if (!isset($columns) || count($columns) < 2) {
            throw new \InvalidArgumentException(self::class . ' processor: minimum count of columns - 2');
        }

        $this->columns = $columns;
    }

    /**
     * @param $columnToWrite
     */
    public function setColumnToWrite($columnToWrite)
    {
        $this->columnToWrite = $columnToWrite;
    }

    /**
     * @return string
     */
    public function getColumnToWrite()
    {
        if (!isset($this->columnToWrite)) {
            throw new \InvalidArgumentException(self::class . ' processor: columnToWrite is not set');
        }

        return $this->columnToWrite;
    }

    /**
     * @return array
     */
    public static function getAllowedDelimiters()
    {
        return [
            '-',
            ' ',
            '_'
        ];
    }

    /**
     * @param string $delimiter
     * @throws \Exception
     */
    public function setDelimiter(string $delimiter)
    {
        $allowedDelimiters = self::getAllowedDelimiters();

        if (in_array($delimiter, $allowedDelimiters)) {
            $this->delimiter = $delimiter;
        } else {
            throw new \InvalidArgumentException(
                self::class . ' Delimiter ' . $delimiter . ' must be one of [' .
                implode(',', $allowedDelimiters) . ']'
            );
        }
    }

    public function getDelimiter()
    {
        if (!isset($this->delimiter)) {
            throw new \InvalidArgumentException(self::class . ' processor: delimiter is not set');
        }

        return $this->delimiter;
    }

    /**
     * Implode $this->columns with $this->delimiter
     *
     * @param array $value
     * @return array
     */
    public function doProcess(array $value)
    {
        foreach ($this->columns as $column) {
            if (!array_key_exists($column, $value)) {
                throw new \InvalidArgumentException(self::class . ' processor: column ' . $column . ' is not valid');
            }
        }

        $columns = $this->getValueColumns($value);
        ksort($columns);

        $columnToWrite = $this->getColumnToWrite();
        $delimiter = $this->getDelimiter();
        $value[$columnToWrite] = implode($delimiter, $columns);

        return $value;
    }

    /**
     * @param array $value
     * @return array
     */
    protected function getValueColumns(array $value)
    {
        $valueColumns = [];

        foreach ($this->columns as $priority => $column) {
            $valueColumns[$priority] = $value[$column];
        }

        return $valueColumns;
    }
}