<?php namespace common\parser;

require_once 'Parser.php';

use common\parser\Parser;

class Table_Parser extends Parser
{
    protected $_multiple = true;

    /**
     * @var array
     * Ex multiple:
     *  [
     *      ''
     *  ]
     */
    protected $_rules = [];

    /*public function DataMultipleRuleEx()
    {
        return [
            'name'  => [
                'td_number'     => 1,
                'type'          => 'str',
                'dom'           => 'a[class=link]',
                'dom_number'    => 0,
                'property'      => 'href',
            ],
            'href'  => [
                'td_number'     => 2,
                'type'          => 'int',
                'dom'           => 'span[class=label]',
                'dom_number'    => 1,
                'property'      => 'plaintext',
            ],
        ];
    }

    public function DataRuleEx()
    {
        return [
            'name'  => [
                'tr_number'     => 1,
                'td_number'     => 2,
                'type'          => 'str',
                'dom'           => 'div[id=data_name]',
                'dom_number'    => 2,
                'property'      => 'plaintext',
            ],
            'area'  => [
                'tr_number'     => 2,
                'td_number'     => 4,
                'type'          => 'str',
                'dom'           => 'div[id=area_name]',
                'dom_number'    => 3,
                'property'      => 'plaintext',
            ],
        ];
    }*/

    public function setMultiple($multiple)
    {
        if (is_bool($multiple)) {
            $this->_multiple = $multiple;
        }
    }

    public function findData()
    {
        if (isset($this->_rules['row']) && isset($this->_rules['column'])) {
            $rows = $this->findRow();
            if ($rows) {

                $countRow = 0;
                foreach ($rows as $row) {
                    $countRow++;
                    $columns = $this->findColumn();
                    if ($columns) {

                        $countColumn = 0;
                        foreach ($columns as $col) {
                            $countColumn++;

                            //loop rules
                            $keys = array_keys($this->_rules['data']);
                            foreach ($keys as $key) {
                                $rule = $this->_rules['data'][$key];

                            }

                        }

                    }
                }

            }
        }
    }

    public function findRow()
    {
        return $this->find($this->_rules['row']);
    }

    public function findColumn()
    {
        return $this->find($this->_rules['column']);
    }
}