<?php
/**
 * 
 *
 * FLEA_Helper_Pager ʹ�úܼ򵥣�ֻ��Ҫ����ʱ���� FLEA_Db_TableDataGateway ʵ���Լ���ѯ�������ɡ�
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class Lib_Pager
{
    /**
     * ��� $this->source ��һ�� FLEA_Db_TableDataGateway ���������
     * $this->source->findAll() ����ȡ��¼����
     *
     * ����ͨ�� $this->dbo->selectLimit() ����ȡ��¼����
     *
     * @var FLEA_Db_TableDataGateway|string
     */
    var $source;

    /**
     * ���ݿ���ʶ��󣬵� $this->source ����Ϊ SQL ���ʱ���������
     * $this->setDBO() ���ò�ѯʱҪʹ�õ����ݿ���ʶ���
     *
     * @var SDBO
     */
    var $dbo;

    /**
     * ��ѯ����
     *
     * @var mixed
     */
    var $_conditions;

    /**
     * ����
     *
     * @var string
     */
    var $_sortby;

    /**
     * ����ʵ��ҳ��ʱ�Ļ���
     *
     * @var int
     */
    var $_basePageIndex = 0;

    /**
     * ÿҳ��¼��
     *
     * @var int
     */
    var $pageSize = -1;

    /**
     * ���ݱ��з��ϲ�ѯ�����ļ�¼����
     *
     * @var int
     */
    var $totalCount = -1;

 
    /**
     * ���������ļ�¼ҳ��
     *
     * @var int
     */
    var $pageCount = -1;

    /**
     * ��һҳ���������� 0 ��ʼ
     *
     * @var int
     */
    var $firstPage = 1;

    /**
     * ��һҳ��ҳ��
     *
     * @var int
     */
    var $firstPageNumber = -1;

    /**
     * ���һҳ���������� 0 ��ʼ
     *
     * @var int
     */
    var $lastPage = -1;

    /**
     * ���һҳ��ҳ��
     *
     * @var int
     */
    var $lastPageNumber = -1;

    /**
     * ��һҳ������
     *
     * @var int
     */
    var $prevPage = 1;

    /**
     * ��һҳ��ҳ��
     *
     * @var int
     */
    var $prevPageNumber = 1;

    /**
     * ��һҳ������
     *
     * @var int
     */
    var $nextPage = 1;

    /**
     * ��һҳ��ҳ��
     *
     * @var int
     */
    var $nextPageNumber = 1;

    /**
     * ��ǰҳ������
     *
     * @var int
     */
    var $currentPage = 1;

    /**
     * ���캯�����ṩ�ĵ�ǰҳ���������� setBasePageIndex() �����¼���ҳ��
     *
     * @var int
     */
    var $_currentPage = -1;

    /**
     * ��ǰҳ��ҳ��
     *
     * @var int
     */
    var $currentPageNumber = -1;
	
	var $rowset	=	array();
	var $pageHtml	=	"";

    /**
     * ���캯��
     *
     * ��� $source ������һ�� TableDataGateway ������ FLEA_Helper_Pager �����
     * �� TDG ����� findCount() �� findAll() ��ȷ����¼���������ؼ�¼����
     *
     * ��� $source ������һ���ַ�������ٶ�Ϊ SQL ��䡣��ʱ��FLEA_Helper_Pager
     * �����Զ����ü�������ҳ����������ͨ�� setCount() ������������Ϊ��ҳ����
     * �����ļ�¼������
     *
     * ͬʱ����� $source ����Ϊһ���ַ���������Ҫ $conditions �� $sortby ������
     * ���ҿ���ͨ�� setDBO() ��������Ҫʹ�õ����ݿ���ʶ��󡣷��� FLEA_Helper_Pager
     * �����Ի�ȡһ��Ĭ�ϵ����ݿ���ʶ���
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
     * ���ص�ǰҳ��Ӧ�ļ�¼��
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
     * ����һ��ҳ��ѡ����ת�ؼ�
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
     * ��������ҳ����
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
     * ���ط�ҳ��Ϣ��������ģ����ʹ��
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
	
	/*һ��Ч��  ����û�з��� �Ժ��ٸİ�*/
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
