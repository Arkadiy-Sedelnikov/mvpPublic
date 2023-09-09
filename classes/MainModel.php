<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Classes;

use Config;

class MainModel {
    protected $config, $db, $table;

    public function __construct() {
        $this->config = new Config();
        $this->db = SafeMySQL::getInstance( $this->config->getDbConfig() );
    }

    /**
     * Очистка таблицы
     */
    public function truncate(){
        $this->db->query('TRUNCATE TABLE '.$this->table);
    }

    /** Выводит из таблицы пару ключ-значение
     * @param string $where
     * @param string $idKey
     * @param string $valueKey
     * @return array
     */
    public function get($where=array(), $idKey='id', $valueKey='name'){
        $query = 'SELECT * FROM '.$this->table;
        $whereString = $this->buildWhere($where);
        if($whereString){
            $query .= ' WHERE '.$whereString;
        }

        $query .= ' ORDER BY '.$valueKey. ' ASC';

        $rows = $this->db->getAll($query);
        $ret = array();
        foreach ($rows as $row) {
            $ret[$row[$idKey]] = $row[$valueKey];
        }
        return $ret;
    }

    /** Выводит из таблицы пару ключ-значение
     * @param $field
     * @param array $where
     * @return bool|FALSE|string
     */
    public function getOne($field, $where=array()){
        $query = 'SELECT '.$field.' FROM '.$this->table;
        $whereString = $this->buildWhere($where);
        if($whereString){
            $query .= ' WHERE '.$whereString;
        }
        return $this->db->getOne($query);
    }

    /** Получает выбранные строки
     * @param array $where
     * @param string $fields
     * @param string $index
     * @param int $page
     * @param int $count
     * @param string $order
     * @return array
     */
    public function getRows(array $where=array(), $fields='*', $index='', $page=0, $count=0, $order=''){
        $query = 'SELECT '.$fields.' FROM '.$this->table;
        $whereString = $this->buildWhere($where);
        if($whereString){
            $query .= ' WHERE '.$whereString;
        }
        if($order){
            $query .= ' ORDER BY '.$order;
        }
        if ($page > 0 && $count > 0) {
            $query .= sprintf(' LIMIT %s, %s', ($page - 1) * $count, $count);
        }

        if($index){
            $rows = $this->db->getInd($index, $query);
        }
        else{
            $rows = $this->db->getAll($query);
        }
        return $rows;
    }

    public function getCountRows(array $where=array()){
        $query = 'SELECT COUNT(*) FROM '.$this->table;
        $whereString = $this->buildWhere($where);
        if($whereString){
            $query .= ' WHERE '.$whereString;
        }

        $count = $this->db->getOne($query);
        return $count;
    }

    /** Получает строки с ключами установленными в $index
     * @param string $index
     * @param array $where
     * @param string $fields
     * @return array
     */
    public function getInd($index='id', $where=array(), $fields='*'){
        $query = 'SELECT '.$fields.' FROM '.$this->table;
        $whereString = $this->buildWhere($where);
        if($whereString){
            $query .= ' WHERE '.$whereString;
        }
        $rows = $this->db->getInd($index, $query);
        return $rows;
    }

    /**
     * @param $field
     * @param array $where
     * @param string $fields
     * @return array
     */
    public function getCol(array $where, $field){
        $query = 'SELECT '.$field.' FROM '.$this->table;
        $whereString = $this->buildWhere($where);
        if($whereString){
            $query .= ' WHERE '.$whereString;
        }
        $rows = $this->db->getCol($query);
        if(!is_array($rows)){
            $rows = array();
        }
        return $rows;
    }

    /** Получает одну строку
     * @param array $where
     * @param string $fields
     * @param string $order
     * @return array|mixed
     */
    public function getRow(array $where, $fields='*', $order=''){
        $rows = $this->getRows($where, $fields, '', 1, 1, $order);
        if(!count($rows)){
            return array();
        }
        return $rows[0];
    }

    /** Добавляет данные
     * @param $data
     * @return int
     */
    public function add($data){
        $sql     = "INSERT INTO ?n SET ?u";
        $this->db->query($sql, $this->table, $data);
        $id = $this->db->insertId();
        return $id;
    }

    /** Обновляет данные
     * @param $data
     * @param $whereField
     * @param $whereValue
     * @return bool|FALSE|\mysqli|resource
     */
    public function update($data, $where=array()){
        $sql     = "UPDATE ?n SET ?u";
        $whereString = $this->buildWhere($where);
        if(empty($whereString)){
            return false;
        }
        $sql .= ' WHERE '.$whereString;
        return $this->db->query($sql, $this->table, $data);
    }

    /** Проверяет есть-ли запись и обновляет или добавляет ее
     * @param $data
     * @param $where
     * @return bool|FALSE|int|\mysqli|resource
     */
    public function store($data, $where=array()){
        $id = $this->getOne('id', $where);
        if(!$id){
            return $this->add($data);
        }
        else{
            return  $this->update($data, array('id' => $id));
        }
    }

    public function delete($where=array()){
        $query = 'DELETE FROM ?n';
        $whereString = $this->buildWhere($where);
        if(empty($whereString)){
            return false;
        }
        $query .= ' WHERE '.$whereString;
        return $this->db->query($query, $this->table);
    }

    protected function buildWhere(array $where=array()){
        $aWhere = array();
        if(!count($where)){
            return '';
        }
        foreach ($where as $k => $v) {
            if(is_array($v)) {
                if(count($v) < 2){
                    continue;
                }
                switch ($v[0]){
                    case 'IN';
                    case 'in';
                        $value = is_array($v[1]) ? $v[1] : array($v[1]);
                        $aWhere[] = $this->db->parse($k.' IN (?a)', $value);
                        break;
                    case 'NOT IN';
                    case 'not in';
                        $value = is_array($v[1]) ? $v[1] : array($v[1]);
                        $aWhere[] = $this->db->parse($k.' NOT IN (?a)', $value);
                        break;
                    case 'BETWEEN':
                    case 'between':
                        $aWhere[] = $this->db->parse($k.' BETWEEN ?s AND ?s', $v[1], $v[2]);
                        break;
                    case 'less':
                    case 'LESS':
                    case '<':
                        $aWhere[] = $this->db->parse($k.' < ?s', $v[1]);
                        break;
                    case 'MORE':
                    case 'more':
                    case '>':
                        $aWhere[] = $this->db->parse($k.' > ?s', $v[1]);
                        break;
                    case '<=':
                        $aWhere[] = $this->db->parse($k.' <= ?s', $v[1]);
                        break;
                    case '>=':
                        $aWhere[] = $this->db->parse($k.' >= ?s', $v[1]);
                        break;
                    case 'IS NULL':
                    case 'is null':
                    case 'ISNULL':
                        $aWhere[] = $k.' IS NULL';
                        break;
                    case 'IS NOT NULL':
                    case 'is not null':
                    case 'ISNOTNULL':
                        $aWhere[] = $k.' IS NOT NULL';
                        break;
                    case 'LIKE':
                    case 'like':
                        $aWhere[] = $k . ' LIKE "%' . addslashes($v[1]) . '%"';
                        break;
                    case '!=':
                    case '<>':
                        $aWhere[] = $this->db->parse($k.' != ?s', $v[1]);
                        break;
                    case 'raw_condition':
                        $aWhere[] = $v[1];
                        break;
                    case 'FIND_IN_SET':
                    case 'find_in_set':
                        $aWhere[] = 'FIND_IN_SET("' . addslashes($v[1]) . '", ' . $k . ')';
                        break;
                    case 'OR':
                    case 'or':
                        if(is_array($v[1]) && count($v[1])){
                            $aWhere[] = '(' . implode(' OR ', $v[1]).')';
                        }
                        break;
                    case 'AND':
                    case 'and':
                        if(is_array($v[1]) && count($v[1])){
                            foreach ($v[1] as $array) {
                                if (count($array) == 1) {
                                    if(in_array($array[0], array('IS NULL', 'is null', 'IS NOT NULL', 'is not null'))){
                                        $aWhere[] = $k.' '.$array[0];
                                    }
                                    else if(in_array($array[0], array('IN', 'in', 'NOT IN', 'not in'))){
                                        $value = is_array($array[1]) ? $array[1] : array($array[1]);
                                        $aWhere[] = $this->db->parse($k.' IN (?a)', $value);
                                    }
                                    else{
                                        $aWhere[] = $this->db->parse($k.' = ?s', $array[0]);
                                    }

                                } else if(count($array) == 2) {
                                    $aWhere[] = $this->db->parse($k.' '.$array[0].' ?s', $array[1]);
                                }
                            }
                        }
                        break;
                    default:
                        $aWhere[] = $this->db->parse($k.' '.$v[0].' ?s', $v[1]);
                }
            }
            else{
                $aWhere[] = $this->db->parse($k.' = ?s', $v);
            }
        }

        $aWhere = implode(' AND ', $aWhere);
        return $aWhere;
    }
}