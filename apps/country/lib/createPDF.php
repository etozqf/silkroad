<?php 
loader::_load('tcpdf', 'lib');

class createPDF extends TCPDF
{
	private $data, $SelfFont, $SelfName;
	public function create($data, $pro, $msg)
	{	
		// 设置文档信息 
		$this->SetCreator('Helloweba'); 
		$this->SetAuthor('silkroad'); 
		$this->SetTitle($pro); 
		$this->SetSubject('TCPDF Tutorial'); 
		$this->SetKeywords('TCPDF, PDF, PHP'); 
		$this->SelfFont  = $msg['SelfFont'];
		$this->SelfName = $msg['SelfName'];
		
		// 设置页眉和页脚信息 
		$this->SetHeaderData('', 0, $pro, $msg['ps'],array(0,64,255), array(0,64,128)); 
		// $this->setFooterData(array(0,64,255), array(0,64,128));
		// 设置页眉和页脚字体 
		$this->setHeaderFont(Array($this->SelfFont, '', '10')); 
		$this->setFooterFont(Array('helvetica', '', '8')); 
		 
		// 设置默认等宽字体 
		$this->SetDefaultMonospacedFont('courier'); 
		 
		// 设置间距 
		$this->SetMargins(25, 18, 25, 2); 
		$this->SetHeaderMargin(5); 
		$this->SetFooterMargin(10); 
		 
		// 设置分页 
		$this->SetAutoPageBreak(TRUE, 15); 
		  
		//set image scale factor
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// set default font subsetting mode 
		$this->setFontSubsetting(true); 
		$this->AddPage();
		$this->SetFont($this->SelfFont, 'B', 40);
		$this->writeHTMLCell('', 0, '', 100, "<div>$pro</div>", false, false, false, true, 'C', true);
		$this->Ln(20);
		$this->writeHTMLCell('', 0, '', '', $msg['msg'], false, false, false, true, 'C', true);
		$this->Ln();
		$this->SetFont($this->SelfFont, 'B', 28);
		$this->writeHTMLCell('', 100, '', '', $msg['str'], false, false, false, true, 'C', true);
	
        $this->AddPage();
        $j = 0;
        foreach($data as $k => $v) {
        	$j++;
            $this->createBody($v[$this->SelfName], $v['content'], $j); 
        }

        $this->addTOCPage();
        $this->SetFont($this->SelfFont, 'B', 18);
        $this->Ln(3);
		$this->MultiCell(0, 3, '', 0, 'C', 0, 1, '', '', true, 0);
		$this->MultiCell(0, 5, $msg['table'], 0, 'C', 0, 1, '', '', true, 0);
        $this->SetFont($this->SelfFont, '', 14);
		$this->Ln(1);
		$this->addTOC(2, 'courier', '.', 'INDEX', 'B', array(128,0,0));
		// end of TOC page
		$this->endTOCPage();
	}

	public function createBody($name, $content, $j)
	{
		$this->Ln(4);
		//设置字体 
		$this->SetFont($this->SelfFont, 'B', 20);
		$this->Bookmark($j. '. ' . $name, 0, 0, '', 'B', array(0,64,128));
		$this->Write(0, $j. '. ' . $name, $add, 0, 'L', true, 0, false, false, 0);
		// $this->Ln();

		$patterns = array('#<p style="(.*)text-align: center;(.*)">(.*)<img #U', '#' . UPLOAD_URL . '#U');
		$replacements = array('<p style="$1 $2">$3<img ', UPLOAD_PATH);
		$content = '<p></p>' . preg_replace($patterns, $replacements, $content);
		//设置字体 
		$this->SetFont($this->SelfFont, '', 12);
		$this->writeHTML($content, true, false, true, false, '');
	}

}
