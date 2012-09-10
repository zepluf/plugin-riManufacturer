<?php 

namespace plugins\riManufacturer;

use plugins\riCore\Model;

class Manufacturer extends Model {
    protected $id = 'manufacturers_id', $table = TABLE_MANUFACTURERS;
    
	private $info, $manufacturers = array();
	
	// TODO: need to return false
	public function save(){
		global $db;
					
		$data = $this->getArray(array('info'));
		unset($data['id']);
		
		if(isset($this->manufacturersId) && $this->manufacturersId > 0){
			// TODO: info
			zen_db_perform(TABLE_MANUFACTURERS, $data, 'update', 'id = '.$this->id);
			return true;
		}
		else {
		    $data['date_added'] = 'now()';
			zen_db_perform(TABLE_MANUFACTURERS, $data);
			$this->manufacturersId = $db->Insert_ID();
			
			// insert info
			$this->info->manufacturersId = $this->manufacturersId;
			$this->info->save(true);											
			
			return true;
		}

		return false;
	}
	
	public function getInfo($languages_id = 1){
	    
	    if(isset($this->info) && !empty($this->info)) return $this->info;
		global $db;
		$sql = "SELECT * FROM ".TABLE_MANUFACTURERS_INFO." WHERE manufacturers_id = :manufacturers_id AND languages_id = :languages_id";
		$sql = $db->bindVars($sql, ":manufacturers_id", $this->manufacturersId, 'integer');
		$sql = $db->bindVars($sql, ":languages_id", $languages_id, 'integer');
		
		$result = $db->Execute($sql);
		if($result->RecordCount() > 0){
		    $this->info = $this->container->get('riManufacturer.ManufacturersInfo')->setArray($result->fields);		    
		}
		
		return $this->info;  
	} 
	
	public function setInfo($info){
	    $this->info = $info;
	}
}