<?php
/**
 * InvestiClub
 *
 * LICENSE
 *
 * This file may not be duplicated, disclosed or reproduced in whole or in part
 * for any purpose without the express written authorization of InvestiClub.
 *
 * @category	Ivc
 * @package		Ivc_Model
 * @copyright	Copyright (c) 2011-2013 All Rights Reserved
 * @license		http://investiclub.net/license
 */


/**
 * Brokers model
 * 
 * @author		Alexandre Esser
 * @category	Ivc
 * @package		Ivc_Model
 * @subpackage	Clubs
 */
class Ivc_Model_Clubs_Settings
{
    protected $_gateway;
    protected $_data = array(
        'club_id'                 => null,
    	'min_members'             => null,
        'max_members'             => null,
        'transaction_fee_flat'    => null,
        'transaction_fee_percent' => null,
        'min_contribution'        => null,
        'max_contribution'        => null,
        'last_update'             => null);

    public function __construct($data, $gateway)
    {
        $this->setGateway($gateway);
        $this->populate($data);

        if (!isset($data['club_id'])) {
            throw new Exception('Initial data must contain a club id');
        }
    }
    
    public function save()
    {
        $gateway = $this->getGateway();
        $dbTable = $gateway->getDbTable('Ivc_Model_Clubs_DbTable_ClubSettings');
        $row = $dbTable->find($this->club_id)->current();
        if ($row) {
            foreach ($this->_data as $key => $value) {
                $row->$key = $value;
            }
            $row->save();
        } else {
            $dbTable->insert($this->_data);
        }
    }
    
    public function setGateway(Ivc_Model_Clubs_Gateway $gateway)
    {
        $this->_gateway = $gateway;
        return $this;
    }

    public function getGateway()
    {
        return $this->_gateway;
    }
    
    public function populate($data)
    {
        if ($data instanceof Zend_Db_Table_Row_Abstract) {
            $data = $data->toArray();
        } elseif (is_object($data)) {
            $data = (array) $data;
        }
        if (! is_array($data)) {
            throw new Exception('Initial data must be an array or object');
        }
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }
    
    public function toArray() {
        return $this->_data;
    }
    
    public function __set($name, $value)
    {
        if (! array_key_exists($name, $this->_data)) {
            throw new Exception('Invalid property \"' . $name . '\"');
        }
        $this->_data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }
        return null;
    }
}