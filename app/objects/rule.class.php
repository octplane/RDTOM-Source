<?php
class Rule {
	public $content;
    private $url;

    function __construct($url) {
        $this->url = $url;

    	$fileCache = new FileCache();

		// is there a cached version?
		$cache_name = "rule_cache_" . md5(serialize($url));

		$cached_content = $fileCache->get($cache_name);

		if ($cached_content)
		{
			$this->content = $cached_content;;
			return;
		} else {
            $content = file_get_contents($this->url);
            $fileCache->set($cache_name, $content, 86400 * 10); // 10 days
            $this->content = $content;;
            return;
        }
    }

    public function get_section($section) {
            $dom = new DOMDocument;
            $dom->loadHTML($this->content);

            $divs = $dom->getElementsByTagName('div');
            $rule = $dom->getElementById($section)->parentNode;
            return $rule->ownerDocument->saveHTML($rule);
    }
    public function get_content_with_selection($section) {
        $dom = new DOMDocument;
        $dom->loadHTML($this->content);
        $divs = $dom->getElementsByTagName('div');

        $dom->getElementById($section)->parentNode->setAttribute("class", "valid_response");

        foreach ($divs as $div) {
            if($div->getAttribute("class") == "sidebarMainContent") {
                return $div->ownerDocument->saveHTML($div);
            }
        }
    }
}
