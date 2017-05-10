<?php


class page
{
	private $_baseurl;
	private $_total;
	private $_pagesize;
	private $_pagetotal;

	public function __construct($baseurl, $total, $pagesize, $curpage = 1)
	{
		$this->_baseurl 	= $baseurl;
		$this->_total 		= $total;
		$this->_pagesize 	= $pagesize;
		$this->_curpage 	= (($curpage <= 1) ? 1 : $curpage);
		$this->_pagetotal 	= ceil($total / $pagesize);
	}

	public function createHtml()
	{
		if(strpos($this->_baseurl, '?') === false){
			$url = $this->_baseurl . '?page=';
		}else{
			$url = $this->_baseurl . '&page=';
		}

		//首页, 上一页
		if($this->_curpage == 1){
			$first = "<a href=\"#\">首页</a>\n";
			$prev = "<a href=\"#\">上一页</a>\n";
		}else{
			$first = "<a href=\"{$url}1\">首页</a>\n";
			$prev = "<a href=\"{$url}" . ($this->_curpage - 1) . "\">上一页</a>\n";
		}

		//下一页，尾页
		if($this->_curpage >= $this->_pagetotal){
			$next = "<a href=\"#\">下一页</a>\n";
			$last = "<a href=\"#\">尾页</a>\n";
		}else{
			$next = "<a href=\"{$url}" . ($this->_curpage + 1) . "\">下一页</a>\n";
			$last = "<a href=\"{$url}{$this->_pagetotal}\">尾页</a>\n";
		}

		$html = $first . $prev . $next . $last;

		return $html;
	}
}