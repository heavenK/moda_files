<?php

class Services_Db
{
var $conn;
function Services_Db($host="localhost",$user="root",$pass="123",$db="blog")
{
  if(!$this->conn=mysql_connect($host,$user,$pass))
  die("can't connect to mysql sever");
  mysql_select_db($db,$this->conn);
  mysql_query("SET NAMES 'gbk'");
}
function execute($sql)
{
   return mysql_query($sql,$this->conn);
}
function findCount($sql)
{
    $result=$this->execute($sql);
    return mysql_num_rows($result);
}
function findBySql($sql)
{
    $array=array();
    $result=mysql_query($sql);
    $i=0;
    while($row=mysql_fetch_assoc($result))
       {
      $array[$i]=$row; 
   $i++;
       }
    return $array;
}

/*
*/
function toExtJsonByGp($sqltocount,$sqltoquery)
{
   $totalNum=$this->findCount($sqltocount);
   $result=$this->findBySql($sqltoquery);
   $resultNum = count($result);
  $str="";
  $str.= "{";
  $str.= "'totalCount':'$totalNum',";
  $str.="'rows':";
  $str.="[";
  for($i=0;$i<$resultNum;$i++){
   $str.="{"; 
   $count=count($result[$i]);
   $j=1;
   foreach($result[$i] as $key=>$val)
   {
	   $val = $this->_charCover($val);

	   if($j<$count)
	   {
	   $str.="'".$key."':'".$val."',";
	   }
	   elseif($j==$count)
	   {
	   $str.="'".$key."':'".$val."'";
	   }
	   $j++;
				}
	   
	   $str.="}";
	   if ($i != $resultNum-1) {
				 $str.= ",";
			 }
  }
  $str.="]";
  $str.="}";
  return $str;  
}
 /*
 */
function _charCover($val)
{
	  $val = str_replace("'"," ",$val);
	  $val = str_replace('"',' ',$val);
	  $val = str_replace("\r\n"," ",$val);
	  $val = str_replace("\n"," ",$val); 
	  //$val = str_replace(":","：",$val);
	  $val = str_replace(",","，",$val);
	  $val = str_replace("{","『",$val);
	  $val = str_replace("}","』",$val);
	  $val = str_replace("[","【",$val);
	  $val = str_replace("]","】",$val);
	  //$val = str_replace(".","。",$val);
	  $val = htmlspecialchars($val);
	  
		
		return $val;
}




function toExtJson($table,$start="0",$limit="10",$cons="")
{
   $sql=$this->generateSql($table,$cons);
   $totalNum=$this->findCount($sql);
   $result=$this->findBySql($sql." LIMIT ".$start." ,".$limit);
   $resultNum = count($result);//ǰ
  $str="";
  $str.= "{";
  $str.= "'totalCount':'$totalNum',";
  $str.="'rows':";
  $str.="[";
  for($i=0;$i<$resultNum;$i++){
   $str.="{"; 
   $count=count($result[$i]);
   $j=1;
   foreach($result[$i] as $key=>$val)
   {
	   $val = $this->_charCover($val);
	   if($j<$count)
	   {
	   $str.="'".$key."':'".$val."',";
	   }
	   elseif($j==$count)
	   {
	   $str.="'".$key."':'".$val."'";
	   }
	   $j++;
				}
	   
	   $str.="}";
	   if ($i != $resultNum-1) {
				 $str.= ",";
			 }
  }
  $str.="]";
  $str.="}";
  return $str;  
}
function generateSql($table,$cons)
{
    $sql="";//sql
   $sql="select * from ".$table;
   if($cons!="")
   {
   if(is_array($cons))
   {
     $k=0;
     foreach($cons as $key=>$val)
  {
  if($k==0)
  {
  $sql.="where '";
  $sql.=$key;
  $sql.="'='";
  $sql.=$val."'";
  }else
  {
  $sql.="and '";
  $sql.=$key;
  $sql.="'='";
  $sql.=$val."'";
  }
  $k++;
  }
   }else
   {
   $sql.=" where ".$cons;
   }
   }
   return $sql;
}
function toExtXml($table,$start="0",$limit="10",$cons="")
{
   $sql=$this->generateSql($table,$cons);
   $totalNum=$this->findCount($sql);
   $result=$this->findBySql($sql." LIMIT ".$start." ,".$limit);
   $resultNum = count($result);//ǰ
      header("Content-Type: text/xml");
   $xml="<?xml version=\"1.0\"  encoding=\"utf-8\" ?>\n";
   $xml.="<xml>\n";
   $xml.="\t<totalCount>".$totalNum."</totalCount>\n";
   $xml.="\t<items>\n";
   for($i=0;$i<$resultNum;$i++){
   $xml.="\t\t<item>\n";
   foreach($result[$i] as $key=>$val)
   $xml.="\t\t\t<".$key.">".$val."</".$key.">\n";
   $xml.="\t\t</item>\n";
   }
    $xml.="\t</items>\n";
    $xml.="</xml>\n";
    return $xml;
}
//word
function toWord($table,$mapping,$fileName)
{
   header('Content-type: application/doc'); 
      header('Content-Disposition: attachment; filename="'.$fileName.'.doc"'); 
      echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" 
       xmlns:w="urn:schemas-microsoft-com:office:word" 
       xmlns="http://www.w3.org/TR/REC-html40">
    <head>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>'.$fileName.'</title>
    </head>
    <body>'; 
    echo'<table border=1><tr>';
    if(is_array($mapping))
    {
      foreach($mapping as $key=>$val)
   echo'<td>'.$val.'</td>';
    }
    echo'</tr>';
    $results=$this->findBySql('select * from '.$table);
    foreach($results as $result)
    {
      echo'<tr>';
      foreach($result as $key=>$val)
   echo'<td>'.$val.'</td>';
   echo'</tr>';
    }
    echo'</table>';
    echo'</body>';
    echo'</html>';
}
function toExcel($table,$mapping,$fileName)
{
  header("Content-type:application/vnd.ms-excel");
     header("Content-Disposition:filename=".$fileName.".xls");
  echo'<html xmlns:o="urn:schemas-microsoft-com:office:office"
       xmlns:x="urn:schemas-microsoft-com:office:excel"
       xmlns="http://www.w3.org/TR/REC-html40">
       <head>
       <meta http-equiv="expires" content="Mon, 06 Jan 1999 00:00:01 GMT">
       <meta http-equiv=Content-Type content="text/html; charset=iso-8859-1">
       <!--[if gte mso 9]><xml>
       <x:ExcelWorkbook>
       <x:ExcelWorksheets>
               <x:ExcelWorksheet>
               <x:Name></x:Name>
               <x:WorksheetOptions>
                       <x:DisplayGridlines/>
               </x:WorksheetOptions>
               </x:ExcelWorksheet>
       </x:ExcelWorksheets>
       </x:ExcelWorkbook>
       </xml><![endif]-->
       </head>
    <body link=blue vlink=purple leftmargin=0 topmargin=0>'; 
    echo'<table width="100%" border="0" cellspacing="0" cellpadding="0">';
       echo'<tr>';
    if(is_array($mapping))
    {
      foreach($mapping as $key=>$val)
   echo'<td>'.$val.'</td>';
    }
    echo'</tr>';
    $results=$this->findBySql('select * from '.$table);
    foreach($results as $result)
    {
      echo'<tr>';
      foreach($result as $key=>$val)
   echo'<td>'.$val.'</td>';
   echo'</tr>';
    }
    echo'</table>';
    echo'</body>';
    echo'</html>';
}
function Backup($table)
{
  if(is_array ($table))
  {
   $str="";
   foreach($table as $tab)
   $str.=$this->get_table_content($tab);
   return $str;
  }else{
   return $this->get_table_content($table);
  }
}
function Backuptofile($table,$file)
{
  header("Content-disposition: filename=$file.sql");//ļ
  header("Content-type: application/octetstream");
  header("Pragma: no-cache");
  header("Expires: 0");
  if(is_array ($table))
  {
   $str="";
   foreach($table as $tab)
   $str.=$this->get_table_content($tab);
   echo $str;
  }else{
   echo $this->get_table_content($table);
  }
}
function Restore($table,$file="",$content="")
{
  //ųfilecontentΪջ߶Ϊյ
  if(($file==""&&$content=="")||($file!=""&&$content!=""))
  echo"";
  $this->truncate($table);
  if($file!="")
  {
   if($this->RestoreFromFile($file))
   return true;
   else
   return false;
  }
  if($content!="")
  {
   if($this->RestoreFromContent($content))
   return true;
   else
   return false;
  }
}
//ձ���Աָ
function truncate($table)
{
  if(is_array ($table))
  {
   $str="";
   foreach($table as $tab)
   $this->execute("TRUNCATE TABLE $tab");
  }else{
   $this->execute("TRUNCATE TABLE $table");
  }
}
function get_table_content($table)
{
  $results=$this->findBySql("select * from $table");
  $temp = "";
  $crlf="\r\n";
  foreach($results as $result)
  {
   
   /*(";
  foreach($result as $key=>$val)
  {
   $schema_insert .= " `".$key."`,";
  }
  $schema_insert = ereg_replace(",$", "", $schema_insert);
  $schema_insert .= ") 
  */
  $schema_insert = "INSERT INTO  $table VALUES (";
  foreach($result as $key=>$val)
  {
   if($val != "")
   $schema_insert .= " '".addslashes($val)."',";
   else
   $schema_insert .= "NULL,";
  }
  $schema_insert = ereg_replace(",$", "", $schema_insert);
  $schema_insert .= ");$crlf";
  $temp = $temp.$schema_insert ;
  }
  return $temp;
}
function RestoreFromFile($file){
  if (false !== ($fp = fopen($file, 'r'))) {
   $sql_queries = trim(fread($fp, filesize($file)));
   $this->splitMySqlFile($pieces, $sql_queries);
   foreach ($pieces as $query) {
    if(!$this->execute(trim($query)))
    return false;
   }
   return true;
  }
  return false;
}
function RestoreFromContent($content)
{
  $content = trim($content);
  $this->splitMySqlFile($pieces, $content);
  foreach ($pieces as $query) {
   if(!$this->execute(trim($query)))
   return false;
  }
  return true;
}
function splitMySqlFile(&$ret, $sql)
{
  $sql= trim($sql);
  $sql=split(';',$sql);
  $arr=array();
  foreach($sql as $sq)
  {
    if($sq!="");
    $arr[]=$sq;
  }
  $ret=$arr;
  return true;
}
}
//$db=new Services_Db();
//$map=array('No','Name','Email','Age');
////$db->toExcel('test',$map,'');
//echo $db->toExtXml('test');
?>