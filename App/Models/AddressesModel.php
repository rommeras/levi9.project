<?php


namespace Models;
use \Core\AppModel;

class AddressesModel extends AppModel {

    protected $fillable = ['LABEL', 'STREET', 'HOUSENUMBER', 'POSTALCODE', 'CITY', 'COUNTRY'];

    public function findOne($id) {

        $this->db->query("SELECT * FROM ADDRESS WHERE ADDRESSID = :id");
        $this->db->setParameter(":id", $id);
        return $this->db->getSingleResult();
    }

    public function findAll() {
        $this->db->query("SELECT * FROM ADDRESS");
        return $this->db->getResult();
    }

    // Only one resource is accepted
    public function create(array $data) {

        $fieldNames = array_map('strtoupper', array_keys($data));
        $fillable = array_map('strtoupper', $this->fillable);
        if ( array_diff($fieldNames, $fillable) || array_diff($fillable, $fieldNames) )
            return false;

        $values = [];
        foreach ($data as $name => $value)
            $values[":".$name] = $value;

        $query = 'INSERT INTO ADDRESS ('.implode(", ", $fieldNames) .') VALUES (' .implode(", ", array_keys($values)) .')' ;

        $this->db->query($query);
        $this->db->setParameters($values);
        $this->db->execute();
        return true;
    }

    public function update($id, array $data) {

        $criteria = [];
        $values = [];

        $filteredData = $this->filterData($data);
        if (!$filteredData || $filteredData != $data)
            return false;

        foreach ($filteredData as $name => $value) {
            $criteria[] = $name.' = :'.$name;
            $values[':'.$name] = $value;
        }

        $query = 'UPDATE ADDRESS SET ' .implode(', ', $criteria) .' WHERE ADDRESSID = :id';
        $values[':id'] = $id;

        $this->db->query($query);
        $this->db->setParameters($values);
        $this->db->execute();
        return true;
    }

    /* Nothing mentioned in the task ???

    public function delete($id) {

    }

    */
}