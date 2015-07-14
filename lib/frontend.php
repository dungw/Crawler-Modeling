<?php
class Frontend {
	
	private $_pathCss = '../css/';
	private $_css = array(
		'frontend'
	);

	public function addCss() {
		$html = '';
		if (!empty($this->_css)) {
			foreach ($this->_css as $css) {
				$html .= '<link rel="stylesheet" href="'. $this->_pathCss . $css . '.css' .'">';
			}
		}
		return $html;
	}

	// function create layout
	public function createLayout($page, $value) {
		if ($page == 'result') {
			$html = $this->addCss();
			$html .= '<div align="center">';
			$html .= '<div class="wrap-result-page">';
			$html .= '<div class="result-list" align="left">';
			if (!empty($value)) {
				foreach ($value as $item) {
					$html .= '<ul>';
					$html .= '<li class="result-item">';
					$html .= '<span class="result-label">' . $item['label'] . '</span>';
					$html .= ': ';
					$html .= '<span class="result-value">' . $item['value'] . '</span>';
					$html .= '</li>';
					$html .= '</ul>';
				}
			}
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}
		return $html;
	}

	// show result box
	public function showMessage($page, $value) {
		$html = $this->createLayout($page, $value);
		print $html;
	}

}