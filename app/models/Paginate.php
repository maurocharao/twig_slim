<?php

namespace app\models;

class Paginate
{
	private $page;
	private $perPage;
	private $offset;
	private $pages;
	private $records;
	protected $maxLinks = 4;

	public function records($records)
	{
		$this->records = $records;
	}

	public function sqlPaginate()
	{
		return ' LIMIT '.$this->perPage.' OFFSET '.$this->offset;
	}

	public function paginate($perPage)
	{
		$this->page = $_GET['page'] ?? 1;
		$this->perPage = $perPage ?? 30;
		$this->offset = ($this->page - 1) * $this->perPage;
		$this->pages = ceil($this->records / $this->perPage);
	}

	public function links()
	{
		if($this->pages <= 0)
		{
			return '';
		}
    if(empty($search = search())) {
			$lnk = '?page=';
		}
		else {
			$lnk = '?search='.$search.'&page=';
		}
		$links = "<nav aria-label=\"Page navigation\">\n"
		 ."<ul class=\"pagination pagination-sm justify-content-center\">\n";
		if($this->page > 1)
		{
			$links .= '<li class="page-item"><a class="page-link" href="'.$lnk.'1"> [1] </a></li>'
			 .'<li class="page-item"><a class="page-link" href="'.$lnk.($this->page - 1).'" aria-label="Previous"> <span aria-hidden="true">&laquo;</span></a></li>';
		}
		$first = max(1, $this->page - $this->maxLinks);
		$last = min($this->pages, $this->page + $this->maxLinks);

		for($i = $first; $i <= $last; $i++)
		{
			$links .= '<li class="page-item'.($i == $this->page ? ' active' : '')."\">\n"
			 .'<a href="'.$lnk.$i.'" class="page-link">'.$i."</a></li>\n";
		}
		if($this->page < $this->pages)
		{
			$links .= '<li class="page-item"><a class="page-link" href="'.$lnk.($this->page + 1)."\" aria-label=\"Next\">\n"
			 ."<span aria-hidden=\"true\">&raquo;</span></a></li>\n"
       .'<li class="page-item"><a class="page-link" href="'.$lnk.$this->pages.'"> ['.$this->pages."] </a></li>\n";
		}
		$links .= "</ul>\n"
		 ."</nav>\n";

		return $links;
	}
}
