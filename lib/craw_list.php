<?php
require_once '';

class CrawList {

	// table contain categories
	private $_categoryTable;

	// table contain craw item
	private $_itemTable;

	// columns of item
	public $nameCol = 'item_name';
	public $urlCol = 'item_url';
	public $imageCol = 'item_image';
	public $getDate = 'get_date';
	public $shortDesCol = 'item_short_description';

	// get date column of category table
	public $getDateCategory = 'get_date';

	public function init($in) {
		if (isset($in['name'])) $this->nameCol = $in['name'];
		if (isset($in['url'])) $this->urlCol = $in['url'];
		if (isset($in['image'])) $this->imageCol = $in['image'];
		if (isset($in['short_des'])) $this->shortDesCol = $in['short_des'];
		if (isset($in['get_date'])) $this->getDate = $in['get_date'];
		if (isset($in['get_date_category'])) $this->getDateCategory = $in['get_date_category'];
	}

	public function getLastCategory() {

	}

}
