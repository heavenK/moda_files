<?php

FLEA::loadClass('Lib_Pager');
class Lib_NewPager extends Lib_Pager
{
    /**
     * ÏÔÊ¾ËùÓÐ
     */	 

	 
	 function display(){
		
		

			$html	=	'<a href='.$this->_rebuildUrl($this->firstPage).' >&nbsp;&lt;&lt;&lt;&nbsp;&nbsp;</a>';
			if($this->currentPage == $this->firstPage)
				$html	.=	'<span>&nbsp;Prev&nbsp;</span>';
			else
				$html	.=	'<a href="'.$this->_rebuildUrl($this->currentPage-1).'" >&nbsp;Prev&nbsp;</a>';
		
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
				$html	.=	'<a href="'.$this->_rebuildUrl(1).'" >1</a>';
			if($this->currentPage==4 and $this->pageCount==4)
				$html	.=	'<a href="'.$this->_rebuildUrl(1).'" >1</a>';
			if($this->currentPage>=4)
				if($this->pageCount>=2 and $this->pageCount>4)
				{
					$html	.=	'<a href="'.$this->_rebuildUrl(1).'" >1</a>';
					$html	.=	'<a href="'.$this->_rebuildUrl(2).'" >2</a>';
					$html	.=	'<em >...</em>';
					}
				
				
			/**/	
			for($i,$t=1;$t<=$lim;$i++,$t++){
				if($i==$this->currentPage){
					$html	.=	'<span >'.$i.'</span>';
				}else{
					$html	.=	'<a href="'.$this->_rebuildUrl($i).'" >'.$i.'</a>';
					
				}
			}
			
			/**/
			$xsd = $this->pageCount - $this->currentPage;
			if($xsd==2 and $this->pageCount!=3)
				$html	.=	'<a href="'.$this->_rebuildUrl($this->pageCount).'">'.$this->pageCount.'</a>';
			if($xsd==3 and $this->pageCount==4)
				$html	.=	'<a href="'.$this->_rebuildUrl($this->pageCount).'" >'.$this->pageCount.'</a>';	
			if($xsd>=3 and $this->pageCount>4)
				if($this->pageCount>=2)
				{
					$html	.=	'<em >...</em>';
					$html	.=	'<a href="'.$this->_rebuildUrl($this->pageCount-1).'" >'.($this->pageCount-1).'</a>';
					$html	.=	'<a href="'.$this->_rebuildUrl($this->pageCount).'" >'.$this->pageCount.'</a>';
					
				}
			
			if($this->currentPage == $this->lastPage)
				$html	.=	'<span>&nbsp;Next&nbsp;</span>';
			else
				$html	.=	'<a href="'.$this->_rebuildUrl($this->currentPage+1).'" >&nbsp;Next&nbsp;</a>';
			$html	.=	'<a href="'.$this->_rebuildUrl($this->lastPage).'" >&nbsp;&nbsp;&gt;&gt;&gt;&nbsp;</a>';
			
		$html	.=	'';
		$this->pageHtml	=	 $html;

	
	}

	
}
?>
