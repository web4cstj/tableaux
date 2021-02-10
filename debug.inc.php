<?php
class Debug {
	const OUTPUT_SCREEN = 0;
	const OUTPUT_FILE = 1;
	const FILE_PREPEND = -1;
	const FILE_REPLACE = 0;
	const FILE_APPEND = 1;
	static public $filename = "debug.txt";
	static public $append = self::FILE_REPLACE;
	static public $output = self::OUTPUT_SCREEN;
	static public $width = 60;
	
	static public function trace($value=null) {
		$val = str_replace("\t", "  ", var_export($value, true));
		//var_export($value);
		$back = debug_backtrace();
		for ($i=0; $i<count($back) && $back[$i]['file']==__FILE__; $i++);
		//print_r($back);
		$line = self::strline("LINE", $back[$i]['line']).self::strline("FILE", basename($back[$i]['file']));
		if (isset($back[$i+1])) {
			$line .= self::strline("IN", self::debug_context($back[$i+1]));
		}
		$result[] = $line;
		if (isset($back[$i+2])) {
			$by = array();
			for ($i=$i+2; $i<count($back); $i++) {
				$by[] = self::debug_context($back[$i], $back[$i-1]['line']);
			}
			$result[] = "BY   : ".implode(' <span style="font-family:arial; font-weight:bold;">&lArr;</span> ', $by);
		}
		if (self::$output == self::OUTPUT_FILE) {
			$result = self::asciibox($result, self::$width+4, 0, 1);
			$result .= $val."\n\n";
			if (self::$append == self::FILE_PREPEND) {
				file_put_contents("debug.txt", $result.file_get_contents("debug.txt"));				
			}
			else if (self::$append == self::FILE_APPEND) {
				file_put_contents("debug.txt", $result, FILE_APPEND);
			}
			else file_put_contents("debug.txt", $result);
		}else{
			$result = implode("\n", $result);
			$styleBox = ' style="'.self::style_box().'"';
			$styleHeader = ' style="'.self::style_header().'"';
			
			$onclick = ' onclick="'.self::jsCollapse().self::jsPosition().'"';
			echo '<pre'.$styleBox.$onclick.'>'
				.'<div'.$styleHeader.'>'.$result.'</div>'
				.htmlspecialchars($val)
				.'</pre>';
		}
	}
	static public function jsCollapse() {
		$result = "if (arguments[0].ctrlKey || arguments[0].metaKey) ";
		$result .= "if (this.style.overflow=='hidden') {";
		$style['overflow'] = 'auto';
		$style['width'] = '';
		$style['height'] = '';
		$result .= self::renderStyleObj($style, 'this');
		$result .= "} else {";
		$style['overflow'] = 'hidden';
		$style['width'] = '7em';
		$style['height'] = '1em';
		$result .= self::renderStyleObj($style, 'this');
		$result .= "}";
		return $result;
	}
	static public function jsPosition() {
		$result = "if (arguments[0].shiftKey) ";
		$result .= "if (this.style.position=='absolute') {";
		$style['position'] = '';
		$result .= self::renderStyleObj($style, 'this');
		$result .= "} else {";
		$style['position'] = 'absolute';
		$result .= self::renderStyleObj($style, 'this');
		$result .= "}";
		return $result;
	}
	static public function style_header() {
		$style['background'] = "rgba(0,0,0,.2)";
		$style['border-radius'] = ".5em .5em 0 0";
		$style['-moz-border-radius'] = $style['border-radius'];
		$style['-webkit-border-radius'] = $style['border-radius'];
		$style['padding'] = "0 .25em";
		$style['margin'] = "-.25em -.25em .25em;";
		return self::renderStyle($style);
	}
	static public function style_box() {
		$style['background'] = "#FFC";
		$style['border'] = "1px solid #000";
		$style['font-size'] = "12px";
		$style['padding'] = ".5em";
		$style['border-radius'] = ".75em .75em 0 0";
		$style['-moz-border-radius'] = $style['border-radius'];
		$style['-webkit-border-radius'] = $style['border-radius'];
		$style['box-shadow'] = "1px 1px 2px rgba(0,0,0,.2)";
		$style['-moz-box-shadow'] = $style['box-shadow'];
		$style['-webkit-box-shadow'] = $style['box-shadow'];
		return self::renderStyle($style);
	}
	static public function renderStyle($style) {
		$result = '';
		foreach($style as $key=>$value) {
			$result .= "$key:$value;";
		}
		return $result;
	}
	static public function renderStyleObj($style, $obj='this') {
		$result = '';
		foreach($style as $key=>$value) {
			$key = str_replace('-', ' ', ($key));
			$key = ucwords($key);
			$key = lcfirst($key);
			$key = str_replace(' ', '', ($key));
			$key = str_replace(array('class', 'float'), array('className','cssFloat'), $key);
			$result .= "$obj.style.$key='".addslashes($value)."';";
		}
		return $result;
	}
	static public function debug_context($back, $line=0) {
		$context = "";
		if (isset($back['class'])) $context .= $back['class']."::";
		$context .= "{$back['function']}";
		if ($line) $context .= " [{$back['line']}]";
		return $context;
	}
	static public function asciibox($str = "", $width, $type=0, $paddingh=1, $paddingv=0) {
		$types = array("╔╗╚╝═║", "┌┐└┘─│");
		$c = $types[$type];
		
		if (is_string($str)) $str = explode("\n", $str);
		$result[] = substr($c,0,3).str_repeat(substr($c,12,3),$width-2).substr($c,3,3);
		$innerWidth = $width-2-$paddingh-$paddingh;
		$emptyline = substr($c,15,3).str_repeat(" ",$width-2).substr($c,15,3);
		for ($i=0;$i<$paddingv; $i++) {
			$result[] = $emptyline;
		}
		foreach($str as $ligne){
			$ligne = substr(str_pad($ligne,$innerWidth),0,$innerWidth);
			$ligne = substr($c,15,3).str_repeat(" ",$paddingh).$ligne.str_repeat(" ",$paddingh).substr($c,15,3);
			$result[] = $ligne;
		}
		for ($i=0;$i<$paddingv; $i++) {
			$result[] = $emptyline;
		}
		$result[] = substr($c,6,3).str_repeat(substr($c,12,3),$width-2).substr($c,9,3);
		return implode("\n", $result)."\n";
	}
	static public function strline($label="", $data="", $pos=4, $width=30) {
		$result = substr(str_pad($label, $pos, " ", STR_PAD_LEFT),0,$pos)." : ";
		$result .= substr(str_pad($data, $width),0,$width);
		return $result;
	}
}
function trace($val) {
	return Debug::trace($val);
}
