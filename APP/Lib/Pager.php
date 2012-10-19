<?php
/**
 * 
 *
 * FLEA_Helper_Pager 使用很简单，只需要构造时传入 FLEA_Db_TableDataGateway 实例以及查询条件即可。
 *
 * @package Core
 * @author 起源科技 (www.qeeyuan.com)
 * @version 1.0
 */
class Lib_Pager
{
    /**
     * 如果 $this->source 是一个 FLEA_Db_TableDataGateway 对象，则调用
     * $this->source->findAll() 来获取记录集。
     *
     * 否则通过 $this->dbo->selectLimit() 来获取记录集。
     *
     * @var FLEA_Db_TableDataGateway|string
     */
    var $source;

    /**
     * 数据库访问对象，当 $this->source 参数为 SQL 语句时，必须调用
     * $this->setDBO() 设置查询时要使用的数据库访问对象。
     *
     * @var SDBO
     */
    var $dbo;

    /**
     * 查询条件
     *
     * @var mixed
     */
    var $_conditions;

    /**
     * 排序
     *
     * @var string
     */
    var $_sortby;

    /**
     * 计算实际页码时的基数
     *
     * @var int
     */
    var $_basePageIndex = 0;

    /**
     * 每页记录数
     *
     * @var int
     */
    var $pageSize = -1;

    /**
     * 数据表中符合查询条件的记录总数
     *
     * @var int
     */
    var $totalCount = -1;

 
    /**
     * 符合条件的记录页数
     *
     * @var int
     */
    var $pageCount = -1;

    /**
     * 第一页的索引，从 0 开始
     *
     * @var int
     */
    var $firstPage = 1;

    /**
     * 第一页的页码
     *
     * @var int
     */
    var $firstPageNumber = -1;

    /**
     * 最后一页的索引，从 0 开始
     *
     * @var int
     */
    var $lastPage = -1;

    /**
     * 最后一页的页码
     *
     * @var int
     */
    var $lastPageNumber = -1;

    /**
     * 上一页的索引
     *
     * @var int
     */
    var $prevPage = 1;

    /**
     * 上一页的页码
     *
     * @var int
     */
    var $prevPageNumber = 1;

    /**
     * 下一页的索引
     *
     * @var int
     */
    var $nextPage = 1;

    /**
     * 下一页的页码
     *
     * @var int
     */
    var $nextPageNumber = 1;

    /**
     * 当前页的索引
     *
     * @var int
     */
    var $currentPage = 1;

    /**
     * 构造函数中提供的当前页索引，用于 setBasePageIndex() 后重新计算页码
     *
     * @var int
     */
    var $_currentPage = -1;

    /**
     * 当前页的页码
     *
     * @var int
     */
    var $currentPageNumber = -1;
	
	var $rowset	=	array();
	var $pageHtml	=	"";

    /**
     * 构造函数
     *
     * 如果 $source 参数是一个 TableDataGateway 对象，则 FLEA_Helper_Pager 会调用
     * 该 TDG 对象的 findCount() 和 findAll() 来确定记录总数并返回记录集。
     *
     * 如果 $source 参数是一个字符串，则假定为 SQL 语句。这时，FLEA_Helper_Pager
     * 不会自动调用计算各项分页参数。必须通过 setCount() 方法来设置作为分页计算
     * 基础的记录总数。
     *
     * 同时，如果 $source 参数为一个字符串，则不需要 $conditions 和 $sortby 参数。
     * 而且可以通过 setDBO() 方法设置要使用的数据库访问对象。否则 FLEA_Helper_Pager
     * 将尝试获取一个默认的数据库访问对象。
     *
     * @param TableDataGateway|string $source
     * @param int $currentPage
     * @param int $pageSize
     * @param mixed $conditions
     * @param string $sortby
     * @param int $basePageIndex
     *
     * @return FLEA_Helper_Pager
     */
    function Lib_Pager(& $source, $pageSize = 20, $conditions = null, $sortby = null,$fields = '*', $queryLinks = true)
    {     
		$this->pageSize = $pageSize;
        if (is_object($source)) {		
            $this->source =& $source;
            $this->_conditions = $conditions;
            $this->_sortby = $sortby;
            $this->totalCount = (int)$this->source->findCount($conditions);
            $this->computingPage();
			//$this->_currentPage = $this->currentPage = $this->checkCurrentPage((int)$_GET['page']);     		
			
        } elseif (!empty($source)) {
            $this->source = $source;
            $sql = "SELECT COUNT(*) FROM ( $source ) as _count_table";
            $this->dbo =& FLEA::getDBO();
            $this->totalCount = (int)$this->dbo->getOne($sql);
            $this->computingPage();
			//$this->_currentPage = $this->currentPage = (int)$_GET['page'];
        	//$this->pageSize = $pageSize;
        }
		$this->rowset	=	$this->findAll($fields = '*', $queryLinks = true);
		$this->display();
    }


    /**
     * 返回当前页对应的记录集
     *
     * @param string $fields
     * @param boolean $queryLinks
     *
     * @return array
     */
    function & findAll($fields = '*', $queryLinks = true)
    {
        $offset = ($this->currentPage - 1) * $this->pageSize;
		//echo $offset;
        if (is_object($this->source)) {
            $limit = array($this->pageSize, $offset);
			//$limit	=	$offset.",".$this->pageSize;
            $rowset = $this->source->findAll($this->_conditions, $this->_sortby, $limit, $fields, $queryLinks);
        } else {
            if (is_null($this->dbo)) {
                $this->dbo =& FLEA::getDBO(false);
            }
            $rs = $this->dbo->selectLimit($this->source, $this->pageSize, $offset);
            $rowset = $this->dbo->getAll($rs);
        }
        return $rowset;
    }

	 /**
     * 生成一个页面选择跳转控件
     *
     * @param string $caption
     * @param string $jsfunc
     */
    function renderPageJumper($caption = '%u', $jsfunc = 'fnOnPageChanged')
    {
        $out = "<select name=\"PageJumper\" onchange=\"{$jsfunc}(this.value);\">\n";
        for ($i = $this->firstPage; $i <= $this->lastPage; $i++) {
            $out .= "<option value=\"{$i}\"";
            if ($i == $this->currentPage) {
                $out .= " selected";
            }
            $out .=">";
            $out .= sprintf($caption, $i + 1 - $this->_basePageIndex);
            $out .= "</option>\n";
        }
        $out .= "</select>\n";
        echo $out;
    }

    /**
     * 计算各项分页参数
     */
    function computingPage(){
        $this->pageCount = (int)ceil($this->totalCount / $this->pageSize);
        $this->firstPage = 1;
        $this->lastPage = $this->pageCount ;
		if(!isset($_REQUEST['page'])){$this->_currentPage = $this->currentPage	=	1;}
		
		if((int)$_REQUEST['page']<1){			
			$this->_currentPage = $this->currentPage	=	1;
		}elseif((int)$_REQUEST['page']>$this->lastPage){
			$this->_currentPage = $this->currentPage	=	$this->lastPage;
		}else{
			$this->_currentPage = $this->currentPage	=	(int)$_REQUEST['page'];
		}
		
        if ($this->currentPage < $this->lastPage ) {
            $this->nextPage = $this->currentPage + 1;
			//echo $this->nextPage;
        } else {
            $this->nextPage = $this->lastPage;
        }

        if ($this->currentPage > 1) {
            $this->prevPage = $this->currentPage - 1;
        } else {
            $this->prevPage = 1;
        }
       
    }
	
	 /**
     * 返回分页信息，方便在模版中使用
     *
     * @param boolean $returnPageNumbers
     *
     * @return array
     */
    function getPagerData($returnPageNumbers = true)
    {
        $data = array(
            'pageSize' => $this->pageSize,
            'totalCount' => $this->totalCount,
            'pageCount' => $this->pageCount,
            'firstPage' => $this->firstPage,
            'firstPageNumber' => $this->firstPageNumber,
            'lastPage' => $this->lastPage,
            'lastPageNumber' => $this->lastPageNumber,
            'prevPage' => $this->prevPage,
            'prevPageNumber' => $this->prevPageNumber,
            'nextPage' => $this->nextPage,
            'nextPageNumber' => $this->nextPageNumber,
            'currentPage' => $this->currentPage,
            'currentPageNumber' => $this->currentPageNumber,
        );

        if ($returnPageNumbers) {
            $data['pagesNumber'] = array();
            for ($i = 0; $i < $this->pageCount; $i++) {
                $data['pagesNumber'][$i] = $i + 1;
            }
        }

        return $data;
    }
	
	/*一个效果  界面没有分离 以后再改吧*/
	function display(){
		$html	=	'<div id="pagertop"><div class="PicPage070129"><div class="Pagediv070129"><span><a href='.$this->_rebuildUrl($this->firstPage).'>|&lt;&lt;</a><a href="'.$this->_rebuildUrl($this->prevPage).'">&lt;&lt;</a>';
		for($i=1;$i<=$this->pageCount;$i++){
			if($i==$this->currentPage){
				$html	.=	'<b>'.$i.'</b>';
			}else{
				$html	.=	'<a href="'.$this->_rebuildUrl($i).'">'.$i.'</a>';
				
			}
		}
		$html	.=	'<a href="'.$this->_rebuildUrl($this->nextPage).'">&gt;&gt;</a>
					<a href="'.$this->_rebuildUrl($this->lastPage).'">&gt;&gt;|</a>
					</span>
					<!--<div class="page070129"></div>-->
				</div>
			</div>
		</div>';
		$this->pageHtml	=	 $html;

	
	}
	
	function _rebuildUrl($p){
		$rq	=	array();
		$rq	=	$_REQUEST;
		$rq['page']	=	$p;
		/*if(isset($_GET['page'])){
			$rq['page']	=	$p;
		}else{
			$rq['page']	=	1;
		}*/
		if(count($_POST)>0){
			$str	=	"";
			foreach($_POST as $key=>$value){
				$str	.=	"&".$key."=".urlencode($value);			
			}
			$url_this = "http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF']."?".http_build_query($rq).$str;		
		}else{
			$url_this = "http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF']."?".http_build_query($rq);	
		}		
		//echo $url_this;
		return $url_this;
	
	}
	
}
