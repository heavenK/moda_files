<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_ModaFacePage extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_facepage';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'Id';



	function _getAll(){
		return $this->findAll();
	}
	function _getface($data){
		return $this->find(" name = ".$data."");
	}

	function _getfaceEidt($data){
		$pieces = explode(" ",$data);
		
		foreach($pieces  as $pi)
		{
					//dump($pi);
					$pieces_2 = explode(":",$pi);
					foreach($pieces_2  as $pi_2)
					{
						//dump($pi_2);
						$ds = $pi_2;
						dump($ds);
						if($ds=="")
						{
								dump("bbbbbb");
						}
						else
						{
								
								$face = $this->_getface($ds);
								
								
								if($face)
								{
									dump("ccccccc");
									//$Urldomain=eregi_replace($pi_2,$face['face_img'],$_POST['content']);//替换
									//dump($Urldomain);
								}
								else
								{
									dump("aaaaaaa");
								}
						}
						
					}
				}
		
	}

}
