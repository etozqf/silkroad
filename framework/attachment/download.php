<?php 
define('SITE_URL', IMG_URL.'|'.UPLOAD_URL);
import('attachment.abstract');

class download extends attachment_abstract 
{
	public $allow_exts = 'jpg|jpeg|gif|png|bmp',
	       $site_url = SITE_URL;
	
	function __construct($dir = null, $allow_exts = null, $site_url = null)
    {
    	parent::__construct($dir);
    	$this->set($dir, $allow_exts, $site_url);
    }
    
    public function set($dir, $allow_exts = null, $site_url = null)
    {
    	if (!is_null($site_url)) $this->site_url = $site_url;
        parent::set($dir, $allow_exts);
    }
    
    public function by_content($string)
    {
        return preg_replace('/(http:\/\/[^>\'"]*\.('.$this->allow_exts.'))/ie', "\$this->by_file_callback('\\1')", $string);
    }
    
    private function by_file_callback($file)
    {
    	if (!preg_match("#^(".$this->site_url.")#", $file))
    	{
    		$file = UPLOAD_URL.$this->by_file($file);
    	}
    	return $file;
    }
    
    public function by_file($file)
    {
    	if (is_array($file))
    	{
    		return array_filter(array_map(array($this, 'by_file'), $file));
    	}
    	else 
    	{
            if ($this->allow_exts && !preg_match('/\.(' . $this->allow_exts . ')$/i', $file))
            {
                $this->error = 'file extension not allowed, expected ' . $this->allow_exts;
                return false;
            };
	    	$path = $this->copy($file);
	    	$info = $this->info($path);
            $info['alias'] = remove_xss(basename($file));
	    	$this->files[] = $info;
	    	return $info['filepath'].$info['filename'];
    	}
    }
    
    public function by_dir($dir)
    {
    	$data = folder::find($dir, '/.+/', 'name', true);
    	if (!$data) return false;

        $file = array();
        foreach ($data as $v)
        {
            if (is_file($v)) $file[] = $v;
        }
        return array_filter(array_map(array($this, 'by_file'), $file));
    }
}