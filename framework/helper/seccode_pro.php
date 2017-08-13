<?php
/**
 * 高级的验证码
 *
 * 支持从一组字符中选取指定颜色的文本
 */
class seccode_pro
{
	public $stringLength = 16;
	public $currentLength = 4;
	public $colors = array(
		array(255, 0, 0, '<span style="color:rgb(255, 0, 0);">红色</span>'),
		array(0, 255, 0, '<span style="color:rgb(0, 255, 0);">绿色</span>'),
		array(0, 0, 255, '<span style="color:rgb(0, 0, 255);">蓝色</span>'),
		array(192, 192, 0, '<span style="color:rgb(192, 192, 0);">黄色</span>'),
		array(192, 0, 192, '<span style="color:rgb(92, 0, 192);">紫色</span>')
	);

	private $string = '';
	private $current = '';
	private $index = array();
	private $currentColor = '';
	private $options = array();
	public $image = null;

	function __construct($sessionid = false)
	{
		$session = factory::session();

        if ($sessionid !== false) {
            session_id($sessionid);
        }

		$session->start();
		$this->fonts = array(
			ROOT_PATH.'resources/fonts/couri.ttf',
			ROOT_PATH.'resources/fonts/consolas.ttf',
		);
 	}

 	function image($options = array(), $binary = false)
 	{
 		$this->options = $options;
 		$this->_data();
 		$this->_render($binary);
 	}

 	function getCurrentColor()
 	{
 		return $this->currentColor;
 	}

 	function valid($input, $destroy = true)
	{
		$result = isset($_SESSION['seccode_pro']) && strcasecmp($input, $_SESSION['seccode_pro']) == 0;
		if ($destroy) unset($_SESSION['seccode_pro']);
		return $result;
	}

 	private function _data()
	{
		$string = '';
		$current = '';
		$index = array();
		$consts = 'bcdfhjkmnpqrstwxy';
		$vowels = 'aei3456789';
		$constsLength = strlen($consts) - 1;
		$vowelsLength = strlen($vowels) - 1;
		for ($i = $this->stringLength; $i--;) {
			$string .= $i % 2
				? $consts[mt_rand(0, $constsLength)]
				: $vowels[mt_rand(0, $vowelsLength)];
		}
		$index = array_rand(range(0, $this->stringLength - 1), $this->currentLength);
		sort($index);
		foreach ($index as $k) {
			$current .= $string[$k];
		}
		$this->string = $string;
		$this->current = $current;
		$this->index = $index;
		$this->currentColor = array_rand($this->colors, 1);
		$cookieName = empty($this->options['color_cookie']) ? 'seccode_color' : $this->options['color_cookie'];
		factory::cookie()->set($cookieName, $this->colors[$this->currentColor][3], time() + 3600);
		$_SESSION['seccode_pro'] = $this->current;
	}

	private function _render($binary = false)
	{
        if ($binary === false) {
            ob_clean();
        } else {
            ob_clean();
            ob_start();
        }

		$width = $this->options['width'] ? $this->options['width'] : 16;
		$height = $this->options['height'] ? $this->options['height'] : 28;
		$imageWidth = $this->stringLength * $width + 4;
		$imageHeight = $height;
		$font_size_min = 15;
		$font_size_max = 17;

		$im = imagecreatetruecolor($imageWidth, $imageHeight);

		// 绘制背景
		imagefill($im, 0, 0, imagecolorallocate($im, 255, 255, 255));
		// 绘制文字
		for ($i = $this->stringLength; $i--;) {
			$this->_renderLetter($im, $i, $font_size_min, $font_size_max);
		}
		// 特效
		$dstim = imagecreatetruecolor($imageWidth, $imageHeight);
		imagefill($dstim, 0, 0, imagecolorallocate($dstim, 255, 255, 255));
		$contort = mt_rand(1, 2);
		$func = value(array('sin', 'cos'), mt_rand(0, 1));
		for ($j=0; $j<$imageHeight; $j++) {
			$amend = round($func($j / $imageHeight * 2 * M_PI - M_PI * 0.5) * $contort);
			for ($i=0; $i<$imageWidth; $i++) {
				$rgb = imagecolorat($im, $i , $j);
				imagesetpixel($dstim, $i+$amend, $j, $rgb);
			}
		}
		if (empty($this->options['no_border'])) {
			$border = imagecolorallocate($dstim , 133, 153, 193);
			imagerectangle($dstim , 0, 0, $imageWidth - 1, $imageHeight - 1, $border);
		}

        $binary === false && header("content-type:image/png\r\n");

        imagepng($dstim);
        imagedestroy($im);
        imagedestroy($dstim);

        if ($binary === true) {
            $this->image = ob_get_contents();
            ob_end_clean();
        }
	}

	private function _renderLetter($im, $index, $font_size_min, $font_size_max)
	{
		$angle = mt_rand(-10, 10);
		$fontsize = mt_rand($font_size_min, $font_size_max);
		$fontfile = value($this->fonts, array_rand($this->fonts));
		if (in_array($index, $this->index)) {
			$color = $this->colors[$this->currentColor];
		} else {
			$colors = $this->colors;
			unset($colors[$this->currentColor]);
			$color = value($colors, array_rand($colors));
		}
		$left = 3 + $index * 16 + mt_rand(-3, 3);
		$bottom = 28;
		$colorAllocate = imagecolorallocate($im, $color[0], $color[1], $color[2]);

		$viewport = imagecreatetruecolor(16, $bottom);
		imagefill($viewport, 0, 0, imagecolorallocate($viewport, 255, 255, 255));
		imagefttext($viewport, $fontsize, $angle, 0, $bottom - 4, $colorAllocate, $fontfile, $this->string[$index]);

		$direct = mt_rand(0, 1);

		for ($y = $bottom; $y--;) {
			if ($direct) {
				$alpha = (128 + (255 - 128) * ($bottom - $y) / $bottom) / 255;
			} else {
				$alpha = (128 + (255 - 128) * $y / $bottom) / 255;
			}
			for ($x = 16; $x--;) {
				$rgb = imagecolorat($viewport, $x, $y + 2);
				if ($rgb == 0xffffff || $rgb == 0x000000) {
					continue;
				}
				$rgb = (($rgb >> 16 & 0xff) * $alpha << 16) + (($rgb >> 8 & 0xff) * $alpha << 8) + ($rgb & 0xff) * $alpha << 0;
				imagesetpixel($im, $left + $x, $y - 3, $rgb);
			}
		}
		imagedestroy($viewport);
	}
} 