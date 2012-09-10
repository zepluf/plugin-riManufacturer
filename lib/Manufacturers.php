<?php
namespace plugins\riManufacturer;

use plugins\riPlugin\Object;

class Manufacturers extends Object{
	public function findById($manufacturers_id){
		global $db;
		$sql = "SELECT * FROM ".TABLE_MANUFACTURERS." WHERE manufacturers_id = :manufacturers_id";
		$sql = $db->bindVars($sql, ":manufacturers_id", $manufacturers_id, 'integer');
		$result = $db->Execute($sql);
		
		if($result->RecordCount() > 0){
			$manufacturer = $this->container->get('riManufacturer.Manufacturer');			
			$manufacturer->setArray($result->fields);
			return $manufacturer;
		}
		
		return false;
	}
	
	public function findByName($manufacturers_name, $limit = 20){
		global $db;
		$sql = "SELECT p.* 
		        FROM " . TABLE_MANUFACTURERS . " p," . TABLE_MANUFACTURERS_INFO . " pd
		 	    WHERE pd.manufacturers_id = p.manufacturers_id
				AND pd.languages_id = :languages_id
				AND manufacturers_name like ':manufacturers_name%'";
		
		if($limit > 0) $sql .= " limit $limit";
		
		$sql = $db->bindVars($sql, ":languages_id", $_SESSION['languages_id'], 'integer');
		$sql = $db->bindVars($sql, ":manufacturers_name", $manufacturers_name, 'noquotestring');
		$result = $db->Execute($sql);
		
		if($result->RecordCount() > 0){
			$collection = array();
			while(!$result->EOF){
				$manufacturer = $this->container->get('riManufacturer.Manufacturer');			
				$manufacturer->setArray($result->fields);	
				$collection[] = $manufacturer;
				$result->MoveNext();
			}		
			return $collection;
		}
		
		return false;
	}
}