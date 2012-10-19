<?php

FLEA::loadClass('Lib_Pager');
class Lib_ModaPager2 extends Lib_Pager
{
    /**
     * ÏÔÊ¾ËùÓÐ
     */	 

	 function Moda_Pager()
	 {
	 }
	 
	 function display(){
		$html	=	'
		<li><a href="'.$this->_rebuildUrl($this->firstPage).'">&lt;&lt;&lt;</a></li>';
		
			/**/
			$lef = $this->currentPage -1;
			if($lef == 0)
				$i = 1;
			else
				$i = $lef;	
			$rig =	$this->pageCount - $this->currentPage;
			if($rig == 0 and $this->currentPage>2)	
			 	$i = $this->currentPage - 2;
			
			if($this->pageCount<4)	
				$lim = $this->pageCount;
			else
				$lim = 3;
							
				
			/**/		
			if($this->currentPage==3 and $this->pageCount!=3)
				$html	.=	'<li><a href="'.$this->_rebuildUrl(1).'">1</a></li>';
			if($this->currentPage==4 and $this->pageCount==4)
				$html	.=	'<li><a href="'.$this->_rebuildUrl(1).'">1</a></li>';
			if($this->currentPage>=4)
				if($this->pageCount>=2 and $this->pageCount>4)
				{
					$html	.=	'<li><a href="'.$this->_rebuildUrl(1).'">1</a></li>';
					$html	.=	'<li><a href="'.$this->_rebuildUrl(2).'">2</a><li>';
					$html	.=	'<li>...</li>';
					}
				
				
			/**/	
			for($i,$t=1;$t<=$lim;$i++,$t++){
				if($i==$this->currentPage){
					$html	.=	'<li class="thisclass"><a>'.$i.'</a></li>';
				}else{
					$html	.=	'<li><a href="'.$this->_rebuildUrl($i).'">'.$i.'</a></li>';
					
				}
			}
			
			/**/
			$xsd = $this->pageCount - $this->currentPage;
			if($xsd==2 and $this->pageCount!=3)
				$html	.=	'<li><a href="'.$this->_rebuildUrl($this->pageCount).'">'.$this->pageCount.'</a></li>';
			if($xsd==3 and $this->pageCount==4)
				$html	.=	'<li><a href="'.$this->_rebuildUrl($this->pageCount).'">'.$this->pageCount.'</a></li>';	
			if($xsd>=3 and $this->pageCount>4)
				if($this->pageCount>=2)
				{
					$html	.=	'<li>...</li>';
					$html	.=	'<li><a href="'.$this->_rebuildUrl($this->pageCount-1).'">'.($this->pageCount-1).'</a></li>';
					$html	.=	'<li><a href="'.$this->_rebuildUrl($this->pageCount).'">'.$this->pageCount.'</a></li>';
					
				}
			
			
			$html	.=	'<li><a href="'.$this->_rebuildUrl($this->lastPage).'">&gt;&gt;&gt;</a></li>';
			
		$this->pageHtml	= $html;

	
	}

	
}
?>
