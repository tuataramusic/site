<?php
/* 
 * Altesack captcha library
 * http://blogocms.ru/2009/09/alcaptcha-vsyo-okazalos-gorazdo-seryoznee/
 * Anyone can use and modify this script anyway
 */
class alcaptcha {
    var $session_key = 'alcode_iuhOYujkLKJubjHB';
                       // Put here some random string
                       // Подставьте какую-нибудь случайную строку.
    function generate_code($charlen){
	// This function is obsolete now.
        // It saved only for compatipility with ealier version of Alcaptcha

        // Эта функция устарела
        // Она оставлена для совместимости с предыдущей версией Alcaptcha.


    }
    function old_generate_code($charlen){
        // Here is an algorythm of random code generation
        // Здесь задаётся алгоритм выдачи случайного кода.

	$possible = '23456789bcdfghjkmnpqrstvwxyz';
	$code = '';
	$i = 0;
	while ($i < $charlen) {
		$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
		$i++;
	}

	return $code;
    }
    function check($code){
        $CI =& get_instance();
        if ($CI->session->userdata($this->session_key)== $code) {
            return true;
        } else {
            return false;
        }

    }
    function image($charlen=6) {
	// Drawing function
        // Алгоритм рисования капчи.

        $width=100; $height=60;
	$im1 = imagecreatetruecolor ($width, $height) or die ("Cannot initialize new GD image stream!");
        $bg = imagecolorallocate ($im1, 255, 255, 255);
        imagefilledrectangle($im1, 0, 0, $width, $height, $bg);
	
        $char=$this->old_generate_code($charlen);

        $CI =& get_instance();
	$CI->session->set_userdata($this->session_key,$char);

        //Выводим символы кода
        //Задаём цвет надписи. ПРи желании можно поменять 
        $color = imagecolorallocate ($im1, 250, 100, 0);

	for ($i = 0; $i < strlen($char); $i++) {
		$x = 10 + $i * 9;
		$y = 10;
		imagechar ($im1, 5, $x, $y, $char[$i], $color);
	}

    //===============================
        $im2= $this->mv($im1,$width,$height);
    //===============================
    //создание рисунка в зависимости от доступного формата
        imagerectangle  ( $im2  , 0  , 0  , $width-1  , $height-1  , imagecolorallocate ($im2, 200, 200, 200));

        if (function_exists("imagepng")) {
		   header("Content-type: image/png");
		   imagepng($im2);
                }elseif (function_exists("imagejpeg")) {
		   header("Content-type: image/jpeg");
		   imagejpeg($im2);
		} elseif (function_exists("imagegif")) {
		   header("Content-type: image/gif");
		   imagegif($im2);
		} else {
		   die("No image support in this PHP server!");
		}
		imagedestroy ($im1);
		imagedestroy ($im2);


    }

    function mv($im,$width,$height){
        // The main idea off algoryth is token from http://captcha.ru/captchas/multiwave/
        // Главная идея алгоритма заимствован с http://captcha.ru/captchas/multiwave/

        // Function of image transforming
        // Функция искажения изображения
      $im2 = imagecreatetruecolor ($width, $height)
        or die ("Cannot initialize new GD image stream!"); // создаём новую подложку
        $bg = imagecolorallocate ($im2, 255, 255, 255);
        imagefilledrectangle($im2, 0, 0, $width, $height, $bg);
        // Distortion parameters:
        // Параметры искажения:

        // frequencies
        // частоты
        $rand1 = mt_rand(700000, 1000000) / 15000000;
        $rand2 = mt_rand(700000, 1000000) / 15000000;
        $rand3 = mt_rand(700000, 1000000) / 15000000;
        $rand4 = mt_rand(700000, 1000000) / 15000000;
        // фазы
        // phases
        $rand5 = mt_rand(0, 3141592) / 1000000;
        $rand6 = mt_rand(0, 3141592) / 1000000;
        $rand7 = mt_rand(0, 3141592) / 1000000;
        $rand8 = mt_rand(0, 3141592) / 1000000;
        // амплитуды
        // amplitudes
        $rand9 = mt_rand(400, 600) / 100;
        $rand10 = mt_rand(400, 600) / 100;

        for($x = 0; $x < $width; $x++){
          for($y = 0; $y < $height; $y++){
            $sx = ($x + ( sin($x * $rand1 + $rand5) + sin($y * $rand3 + $rand6) ) * $rand9)/1.5;
            $sy = ($y + ( sin($x * $rand2 + $rand7) + sin($y * $rand4 + $rand8) ) * $rand10)/1.5;

            if($sx < 0 || $sy < 0 || $sx >= $width - 1 || $sy >= $height - 1){
              continue;
            }else{ 
              $color = imagecolorsforindex($im,imagecolorat($im, $sx, $sy));
              $color_x = imagecolorsforindex($im,imagecolorat($im, $sx + 1, $sy));
              $color_y = imagecolorsforindex($im,imagecolorat($im, $sx, $sy + 1));
              $color_xy = imagecolorsforindex($im,imagecolorat($im, $sx + 1, $sy + 1));
            }

            $frsx = $sx - floor($sx);
            $frsy = $sy - floor($sy);
            $frsx1 = 1 - $frsx;
            $frsy1 = 1 - $frsy;          

            $red   = floor($color['red']      * $frsx1 * $frsy1 +
                           $color_x['red']    * $frsx  * $frsy1 +
                           $color_y['red']    * $frsx1 * $frsy  +
                           $color_xy['red']   * $frsx  * $frsy );

            $green = floor($color['green']    * $frsx1 * $frsy1 +
                           $color_x['green']  * $frsx  * $frsy1 +
                           $color_y['green']  * $frsx1 * $frsy  +
                           $color_xy['green'] * $frsx  * $frsy );

            $blue  = floor($color['blue']     * $frsx1 * $frsy1 +
                           $color_x['blue']   * $frsx  * $frsy1 +
                           $color_y['blue']   * $frsx1 * $frsy  +
                           $color_xy['blue']  * $frsx  * $frsy );

            $newcolor=imagecolorallocate($im2, $red,$green,$blue);
            imagesetpixel($im2, $x, $y, $newcolor);

          }
        }
        return $im2;
    }
}



?>
