<?php 

namespace plugins\riManufacturer;

use plugins\riCore\Model;

class ManufacturersInfo extends Model{
    
    protected $table = TABLE_MANUFACTURERS_INFO;
    
    public function save($new = false){
        $data = $this->getArray();
        if(!$new){        
            unset($data['manufacturers_id']);
            unset($data['languages_id']);
            zen_db_perform($this->table, $data, 'update', ' manufacturers_id = ' . $this->get('manufacturers_id') . ' AND languages_id = ' . $this->get('languages_id'));
        }
        else{
            zen_db_perform($this->table, $data);            
        }
        
        return $this;
    }
}