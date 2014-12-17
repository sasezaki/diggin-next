<?php

namespace Diggin\Scraper;

class Process
{
    private $expression;
    private $name;
    private $arrayFlag;
    private $type;
    private $filters;

    /**
     * toString
     * UnTokenize process For using Exception errstr.
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->getType() instanceof Aggregate) {
            $ret = "";
            foreach ($this->getType()->getProcesses() as $process) {
                $ret .= $process->__toString($process);
            }

            return '\''.$this->getExpression().'\', '.
               "'".$this->getName().' => " '.$ret.'"';
        }

        if ($this->getFilters()) {
            if (count($this->getFilters()) !== 1) {
               $filters= implode(', ', $this->getFilters());
            } else {
               $filters = current($this->getFilters());
            }
            $filters = ($filters instanceof \Closure) ? 'closure' : $filters ;

            return '\''.$this->getExpression().'\', '.
              "'".$this->getName().' => ["'. $this->getType(). '", "'.$filters.'"]\'';
        }

        return '\''.$this->getExpression().'\', '.
              "'".$this->getName().' => "'. $this->getType(). '"\'';
   }

    /**
     * get expression
     *
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * set expression
     *
     * @param string
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;
    }

    /**
     * get Name(Key)
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * set name
     *
     * @param string
     */
    public function setName($name)
    {
        $this->name = trim($name);
    }

    /**
     * get arrayFlag
     *
     * @return boolean
     */
    public function getArrayFlag()
    {
        return $this->arrayFlag;
    }

    /**
     * set arrayFlag
     *
     * @param boolean
     */
    public function setArrayFlag($arrayFlag)
    {
        $this->arrayFlag = $arrayFlag;
    }

    /**
     * get type(of value)
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * set type
     *
     * @param mixed string|Diggin_Scraper_Process_Aggregate
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * get filters
     *
     * @return mixed
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * set filters
     *
     * @param mixed
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }
}

