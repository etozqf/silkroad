<?php
/**
 * 文章管理
 *
 * @aca 文章
 */
class controller_admin_upexcel extends article_controller_abstract
{
	private $excel;

	function __construct($app)
	{
		parent::__construct($app);
		$this->article = loader::model('admin/article');
		$this->modelid = $this->article->modelid;
	}

	function upexcel(){ 
		$objExcel = loader::lib('PHPExcel','article');
		$data = $this->json->decode($_POST['json']);
		$filename = UPLOAD_PATH.$data['file'];
		$path = pathinfo($filename);
		$htmlpath = $path['dirname'].'/'.$path['filename'].'.html';
		//实例化一个读取对象
		if($path['extension'] == 'xls'){
			$objReader = new PHPExcel_Reader_Excel5();
		}elseif($path['extension'] == 'xlsx'){
			$objReader = new PHPExcel_Reader_Excel2007();
		}
		$objexceload = $objReader->load($filename);
		$_properties = $objexceload -> getProperties();
		$_properties -> setTitle(pathinfo($data['name'])['filename']);
		//读取excel文件，并将它实例化为PHPExcel_Writer_HTML对象
		$objWriteHTML = new PHPExcel_Writer_HTML($objexceload);  
		//在页面上打印（这里会直接打印，没有返回值。需要返回值的童鞋请根据save()方法自行改写）
		$objWriteHTML->save($htmlpath);

		$html = file_get_contents($htmlpath);
		// 清楚生成的html文件和上传的xls文件
		unlink($htmlpath);
		unlink($filename);

		if(!empty($html)){
			$json= array('state'=>true,'html'=>$html,'message'=>'Excel导入成功');
		}else{
			$json= array('state'=>false,'error'=>'Excel导入失败');
		}
		echo $this->json->encode($json);
	}
}