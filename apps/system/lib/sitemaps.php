<?php

class sitemaps {
	public $charset;
	public $items = array();
	private $header = "<\x3Fxml version=\"1.0\" encoding=\"UTF-8\"\x3F>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
	private $footer = "</urlset>\n";
	
	public function __construct($charset = "UTF-8")
	{
		$this->charset = $charset;
	}
	
	public function addItem($loc, $lastmod = '', $changefreq = '', $priority = '')
	{
		$this->items[] = array(
			'loc' => $loc,
			'lastmod' => $lastmod,
			'changefreq' => $changefreq,
			'priority' => $priority
		);
	}

	public function build()
	{
		$map = $this->header . "\n";
		foreach ($this->items as $item)
		{
			$item['loc'] = htmlentities($item['loc'], ENT_QUOTES);
			$map .= "\t<url>\n\t\t<loc>".$item['loc']."</loc>\n";
			if (!empty($item['lastmod']))
				$map .= "\t\t<lastmod>".$item['lastmod']."</lastmod>\n";
			if (!empty($item['changefreq']))
				$map .= "\t\t<changefreq>".$item['changefreq']."</changefreq>\n";
			if (!empty($item['priority']))
				$map .= "\t\t<priority>".$item['priority']."</priority>\n";

			$map .= "\t</url>\n";
		}
		$map .= $this->footer."\n";
		return $map;
	}
}