<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/common/constant/System_Constant.php';

use common\System_Constant;

/**
 * @param $environment
 * @return string
 */
function loop($environment)
{
    if ($environment == System_Constant::ENV_PRODUCTION) {
        $loop = 1;
    } elseif ($environment == System_Constant::ENV_DEVELOP) {
        $loop = 100;
    }
    return '<meta http-equiv="refresh" content="'. $loop .'" />';
}

function get_numerics($str)
{
    preg_match_all('/\d+/', $str, $matches);
    return $matches[0];
}

function redirect($url)
{
    $url = htmlspecialbo($url);
    echo '<script type="text/javascript">window.location.href = "' . $url . '";</script>';
    exit();
}

// Function generate picture name from product name
function genPictureName($proName, $ext)
{
    $piName = '';
    $piName = removeAccent(trim($proName));
    $piName = strtolower($piName);
    $piName = ucfirst($piName);
    $piName = str_replace(' ', '_', $piName);
    $piName = str_replace('_-_', '-', $piName);
    $piName = str_replace('__', '_', $piName);
    $pattern = '/[^a-zA-Z0-9-_]/';
    $piName = preg_replace($pattern, '', $piName) . '.' . $ext;

    return $piName;
}

// Remove Script & Style tags
function removeHtmlTag($string, $arrTag = array())
{
    $arrPattern = array();
    if (!empty($arrTag)) {
        $i = 0;
        foreach ($arrTag as $tag) {
            $i++;
            $arrPattern[$i] = '';
            $arrReplacement[$i] = '';
            switch ($tag) {
                case 'a': {
                    $arrPattern[$i] = '/<a.*?\>(.*?)<\/a>/si';
                    $arrReplacement[$i] = '${1}';
                    break;
                }
                case 'img': {
                    $arrPattern[$i] = '/<img[^>]+\>/si';
                    break;
                }
                case 'script': {
                    $arrPattern[$i] = '/<script.*?\>.*?<\/script>/si';
                    break;
                }
                case 'style': {
                    $arrPattern[$i] = '/<style.*?\>.*?<\/style>/si';
                    break;
                }
            }
        }

        if (!empty($arrPattern)) {
            foreach ($arrPattern as $k => $pattern) {
                $string = preg_replace($pattern, $arrReplacement[$k], $string);
            }
        }
    }

    return $string;
}

// Function get image from content of description
function imageForContent($dom)
{
    global $arrExtImage;
    $description = '';
    if (is_object($dom)) {
        $domImg = $dom->find('img');
        if (!empty($domImg)) {
            $description = $dom->innertext;
            foreach ($domImg as $img) {
                $src = $img->src;
                if (!strpos($src, 'ttp://')) {
                    if (substr($src, 0, 1) == '/') {
                        $src = PROTOCOL . DOMAIN . $src;
                    } else {
                        $src = PROTOCOL . DOMAIN . '/' . $src;
                    }
                } else {

                    /*
                     if (substr($src, 0, 1) == '/') {
                        $src = DOMAIN . $src;
                        } else {
                        $src = DOMAIN . '/' . $src;
                        }
                        */
                }

                $ext = getExtention($src);
                if (!in_array($ext, $arrExtImage)) {
                    break;
                }
                $name = PRE_ . '_' . time() . rand(1, 9) . '.' . $ext;
                $new = DEFAULT_PATH_UPLOAD . getImgCurlSiemens($src, $name, '../picture/' . PICTURE_DIR . '/', 0);
                $description = str_replace($img->outertext, '<img src="' . $new . '">', $description);
            }
        }
    }

    return $description;
}

function getExtention($filename)
{
    $ext = end(explode('.', $filename));
    $ext = substr(strrchr($filename, '.'), 1);
    $ext = substr($filename, strrpos($filename, '.') + 1);
    $ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $filename);
    $exts = split("[/\\.]", $filename);
    $n = count($exts) - 1;
    $ext = $exts[$n];
    return $ext;
}

function execKeyword($keyword)
{
    $arrEnter = array();
    $temp = str_replace(' ', '', $keyword);
    $temp = str_replace('	', '', $temp);

    $tmp = explode(chr(13), $temp);
    if (!empty($tmp)) {
        foreach ($tmp as $k => $v) {
            $v = trim($v);
            $tmp1 = explode(',', $v);
            if (count($tmp1) > 1) {
                $arrResult[$tmp1[0]] = $tmp1[1];
            } else if (count($tmp1) == 1) {
                $arrResult[$tmp1[0]] = 1;
            }
        }
    }

    return $arrResult;
}

function getSearchResult($url)
{
    $html = getHTML($url);
    $dom = $html->find('li[class=list-item]');
    unset($html);
    if (!empty($dom)) {
        return $dom;
    }
    return false;
}

/** Clean text */
function mytrim($text)
{
    $retext = trim($text);
    $retext = htmlspecialbo($retext);

    return $retext;
}

/** Function get name of all files and subfolders of directory */
function dirView($directory, $recursive = false, $classify = false)
{
    $array_items = array();
    $array_dir = array();

    if ($handle = opendir($directory)) {
        if ($classify == true) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (is_dir($directory . "/" . $file)) {
                        $array_dir[] = $file;
                        if ($recursive) {
                            $array_items = array_merge($array_items, dirView($directory . "/" . $file, $recursive));
                        }
                        $file = $directory . "/" . $file;
                    } else {
                        $file = $directory . "/" . $file;
                        $array_items[] = preg_replace("/\/\//si", "/", $file);
                    }
                }
            }
        } else {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (is_dir($directory . "/" . $file)) {
                        if ($recursive) {
                            $array_items = array_merge($array_items, dirView($directory . "/" . $file, $recursive));
                        }
                        $file = $directory . "/" . $file;
                    } else {
                        $file = $directory . "/" . $file;
                        $array_items[] = preg_replace("/\/\//si", "/", $file);
                    }
                }
            }
        }
        closedir($handle);
    }
    if ($classify == false) return $array_items;
    else return array($array_items, $array_dir);
}

// Generate character a-z 0-9 | $n = number character need
function genChar($n)
{
    $reValue = '';
    for ($i = 1; $i <= $n; $i++) {
        $reValue .= chr(rand(97, 122));
    }
    return $reValue;
}

// Create folder store pictures
function createFolderImage($file, $img_path)
{
    $path1 = $file[0];
    $path2 = $file[1];
    $path3 = $file[2];
    $path = $path1 . '/' . $path2 . '/' . $path3;
    if (!is_dir($img_path . '/' . $path1)) {
        mkdir($img_path . '/' . $path1);
    }
    if (!is_dir($img_path . '/' . $path1 . '/' . $path2)) {
        mkdir($img_path . '/' . $path1 . '/' . $path2);
    }
    if (!is_dir($img_path . '/' . $path1 . '/' . $path2 . '/' . $path3)) {
        mkdir($img_path . '/' . $path1 . '/' . $path2 . '/' . $path3);
    }
    if ($img_path[strlen($img_path) - 1] == '/') {
        $filePath = $img_path . $path . '/' . $file;
    } else {
        $filePath = $img_path . '/' . $path . '/' . $file;
    }

    return $filePath;
}

// Create folder store pictures
function createFolderImage1($file, $img_path)
{

    $path1 = date('Y');
    $path2 = date('m');
    $path3 = date('d');

    $path = $path1 . '/' . $path2 . '/' . $path3;
    if (!is_dir($img_path . '/' . $path1)) {
        mkdir($img_path . '/' . $path1);
    }
    if (!is_dir($img_path . '/' . $path1 . '/' . $path2)) {
        mkdir($img_path . '/' . $path1 . '/' . $path2);
    }
    if (!is_dir($img_path . '/' . $path1 . '/' . $path2 . '/' . $path3)) {
        mkdir($img_path . '/' . $path1 . '/' . $path2 . '/' . $path3);
    }
    if (!is_dir($img_path . '/' . $path1 . '/' . $path2 . '/' . $path3 . '/01')) {
        mkdir($img_path . '/' . $path1 . '/' . $path2 . '/' . $path3 . '/01');
    }

    $tempPath = $img_path . $path;
    $tmp = dirView($tempPath, false, true);
    $tmp1 = getBiggestNum($tmp[1]);
    $curNumDir = sprintf('%02d', $tmp1);
    $curNumber = countFiles($tempPath . '/' . $curNumDir . '/', array('swf', 'jpg', 'jpeg', 'gif', 'png', 'rar'));

    if ($curNumber >= 200) {
        $curNumDir = sprintf('%02d', $tmp1 + 1);
        mkdir($tempPath . '/' . $curNumDir);
    }
    $curDir = $tempPath . '/' . $curNumDir . '/';
    /*
	if (!is_dir($curDir . '/thumb')) {
		mkdir($curDir . '/thumb');
	}

	 if (!is_dir($curDir . '/thumb40_50')) {
	 mkdir($curDir . '/thumb40_50');
	 }
	 */
    $filePath = $curDir . $file;
    $a = '/' . $path1 . '/' . $path2 . '/' . $path3 . '/' . $curNumDir . '/' . $file;
    //$dirThumb1 = $curDir . 'thumb100_100/';

    return array($filePath, $a, $curDir);
}


function getImgCurlSiemens($url, $name, $path = '../picture/booking/', $resize = 1)
{
    $namePic = '';
    $tmp = createFolderImage1($name, $path);
    $namePic = $tmp[0];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    $raw = curl_exec($ch);

    $fp = fopen($namePic, 'x');
    fwrite($fp, $raw);
    fclose($fp);
    curl_close($ch);

    // Resize 100 * 100
    if ($resize == 1) {
        //resize_image($tmp[2], $tmp[2] . '/thumb/', $name, 100, 100, 90, '');
        //resize_image($tmp[2], $tmp[2] . '/thumb40_50/', $name, 40, 60, 90, 'sma_');
    }

    return $tmp[1];
}

// Function Get image by Curl
/*
 function getImgCurlSiemens($url, $name, $path = '../picture/booking/',$resize = 1) {
 $namePic = '';
 $tmp = createFolderImage1($name, $path);
 $namePic = $tmp[0];

 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 $fp = fopen($namePic, "w");

 curl_setopt($ch, CURLOPT_FILE, $fp);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
 $a = curl_exec($ch);

 $check = curl_getinfo($ch, CURLINFO_HTTP_CODE);
 curl_close($ch);
 fclose($fp);

 // Resize 100 * 100
 if ($resize == 1) {
 resize_image($tmp[2], $tmp[2] . '/thumb/', $name, 100, 100, 90, '');
 //resize_image($tmp[2], $tmp[2] . '/thumb40_50/', $name, 40, 60, 90, 'sma_');
 }

 return $tmp[1];
 }
 */

// Function save image newer
function saveImage($img, $name, $path)
{

    // Create folder
    $tmp = createFolderImage1($name, $path);
    $fullpath = $tmp[0];
    $ch = curl_init($img);

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);

    $rawdata = curl_exec($ch);
    curl_close($ch);
    if (file_exists($fullpath)) {
        unlink($fullpath);
    }

    $fp = fopen($fullpath, 'w+');
    $flag = fwrite($fp, $rawdata);
    fclose($fp);

    // Resize
    if ($flag) {
        resize_image($tmp[2], $tmp[2] . '/thumb/', $name, 200, 160, 90, '');
        return $tmp[1];
    } else {
        return '';
    }
}

// Function save image newer
function saveImageNoithatnhanh($img, $name, $path)
{

    // Create folder
    $tmp = createFolderImage1($name, $path);
    $fullpath = $tmp[0];
    $ch = curl_init($img);

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);

    $rawdata = curl_exec($ch);
    curl_close($ch);
    if (file_exists($fullpath)) {
        unlink($fullpath);
    }

    $fp = fopen($fullpath, 'w+');
    $flag = fwrite($fp, $rawdata);
    fclose($fp);

    // Resize
    resize_image($tmp[2], $tmp[2], $name, 100, 100, 90, 'normal_');
    resize_image($tmp[2], $tmp[2], $name, 160, 160, 90, 'medium_');

    return $tmp[1];
}

function getImgCurlBooking($url, $name, $path = '../picture/booking/')
{
    $namePic = '';
    $namePic = $path . $name;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $fp = fopen($namePic, "w");
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_exec($ch);
    $check = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    fclose($fp);
    return $name;
}

// Function count files in directory
function countFiles($directory, $fileType = array('jpg'))
{
    $total = 0;
    if (!empty($fileType)) {
        foreach ($fileType as $fi) {
            $tmp = glob($directory . "*.{$fi}");
            if ($tmp != false) {
                $filecount = count($tmp);
                $total += $filecount;
            } else {
                $filecount = 0;
            }
        }
    }

    return $total;
}

/**
 * Function resize_image()
 */
function resize_image($path, $newpath, $filename, $maxwidth, $maxheight, $quality = 90, $prefix = "s_")
{

    //File dung de resize
    $file = $path . $filename;

    //Lay gia tri width & height cua file dung de resize gan vao 2 bien
    list($img_width, $img_height) = @getimagesize($file);

    if (!$img_width || !$img_height) {
        return false;
    }

    //Lay ti le de resize
    $scale = min($maxwidth / $img_width, $maxheight / $img_height);
    if ($scale > 1) $scale = 1;

    //Kich thuoc cua file moi
    $new_width = $img_width * $scale;
    $new_height = $img_height * $scale;

    //Create a new true color image
    $new_img = @imagecreatetruecolor($new_width, $new_height);

    //Kiem tra dinh dang file de lua chon tao file moi
    $ext = end(explode('.', $filename));

    switch ($ext) {
        case "gif":
            $image = imagecreatefromgif($file);
            break;
        case "jpg":
        case "jpe":
        case "jpeg":
            $image = imagecreatefromjpeg($file);
            break;
        case "png":
            $image = imagecreatefrompng($file);
            break;
    }

    //Copy file tu file upload
    imagecopyresampled($new_img, $image, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height);

    //Anh tao thanh
    switch ($ext) {
        case "gif":
            imagegif($new_img, $newpath . $prefix . $filename);
            break;
        case "jpg":
        case "jpe":
        case "jpeg":
            imagejpeg($new_img, $newpath . $prefix . $filename, $quality);
            break;
        case "png":
            imagepng($new_img, $newpath . $prefix . $filename);
            break;
    }
    //Free up memory (imagedestroy does not delete files):
    @imagedestroy($new_img);
    @imagedestroy($image);
}

function html_entity_decode_utf8($string)
{
    static $trans_tbl;

    // replace numeric entities
    $string = preg_replace('~&#x([0-9a-f]+);~ei', 'code2utf(hexdec("\\1"))', $string);
    $string = preg_replace('~&#([0-9]+);~e', 'code2utf(\\1)', $string);

    // replace literal entities
    if (!isset($trans_tbl)) {
        $trans_tbl = array();

        foreach (get_html_translation_table(HTML_ENTITIES) as $val => $key)
            $trans_tbl[$key] = utf8_encode($val);
    }

    return strtr($string, $trans_tbl);
}

function code2utf($num)
{
    if ($num < 128) return chr($num);
    if ($num < 2048) return chr(($num >> 6) + 192) . chr(($num & 63) + 128);
    if ($num < 65536) return chr(($num >> 12) + 224) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
    if ($num < 2097152) return chr(($num >> 18) + 240) . chr((($num >> 12) & 63) + 128) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
    return '';
}

function ncr_decode($string, $target_encoding = 'BIG5')
{
    return iconv('UTF-8', 'BIG5', html_entity_decode_utf8($string));
}

function objectsIntoArray($arrObjData, $arrSkipIndices = array())
{
    $arrData = array();

    // if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }

    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}


/** Function get current URL */
function getAddress()
{
    /*     * * check for https ** */
    $protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
    /*     * * return the full address ** */
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}


/*** Show alert box */
function alertB($message = 'Chả có thông báo gì cả :-S')
{
    print '<body style="background: black;font-family: Arial;font-size: 11px;color: #ACAC9A;">';
    print '<div align="center">';
    print '<table><tr><td align="center">';
    print '<div style="border: 1px dotted gray;padding: 10px 30px;margin-top:20px;color: #ACAC9A;font-family: Arial;font-size: 11px;font-weight: bold;">';
    print $message;
    print '</div>';
    print '</td></tr></table>';
    print '</div>';
    print '</body>';
}

function getValue($value_name, $data_type = "int", $method = "GET", $default_value = 0, $advance = 0)
{
    $value = $default_value;
    switch ($method) {
        case "GET":
            if (isset($_GET[$value_name])) $value = $_GET[$value_name];
            break;
        case "POST":
            if (isset($_POST[$value_name])) $value = $_POST[$value_name];
            break;
        case "COOKIE":
            if (isset($_COOKIE[$value_name])) $value = $_COOKIE[$value_name];
            break;
        case "SESSION":
            if (isset($_SESSION[$value_name])) $value = $_SESSION[$value_name];
            break;
        default:
            if (isset($_GET[$value_name])) $value = $_GET[$value_name];
            break;
    }
    $valueArray = array("int" => intval($value), "str" => trim(strval($value)), "flo" => floatval($value), "dbl" => doubleval($value), "arr" => $value);
    foreach ($valueArray as $key => $returnValue) {
        if ($data_type == $key) {
            if ($advance != 0) {
                switch ($advance) {
                    case 1:
                        $returnValue = replaceMQ($returnValue);
                        break;
                    case 2:
                        $returnValue = htmlspecialbo($returnValue);
                        break;
                }
            }
            //Do số quá lớn nên phải kiểm tra trước khi trả về giá trị
            if ((strval($returnValue) == "INF") && ($data_type != "str")) return 0;
            return $returnValue;
            break;
        }
    }
    return (intval($value));
}

function display_link($href, $text = 'URL')
{
    if ($text != '') print '<a href="' . $href . '">' . $text . '</a><br>';
    else print '<a href="' . $href . '">Link</a><br>';
}

function removeAccent($mystring)
{
    $marTViet = array(
        // Chữ thường
        "à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă", "ằ", "ắ", "ặ", "ẳ", "ẵ",
        "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ",
        "ì", "í", "ị", "ỉ", "ĩ",
        "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ", "ờ", "ớ", "ợ", "ở", "ỡ",
        "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ",
        "ỳ", "ý", "ỵ", "ỷ", "ỹ",
        "đ", "Đ", "'",
        // Chữ hoa
        "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă", "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ",
        "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ",
        "Ì", "Í", "Ị", "Ỉ", "Ĩ",
        "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ", "Ờ", "Ớ", "Ợ", "Ở", "Ỡ",
        "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ",
        "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ",
        "Đ", "Đ", "'"
    );
    $marKoDau = array(
        /// Chữ thường
        "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a",
        "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e",
        "i", "i", "i", "i", "i",
        "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o",
        "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u",
        "y", "y", "y", "y", "y",
        "d", "D", "",
        //Chữ hoa
        "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A",
        "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E",
        "I", "I", "I", "I", "I",
        "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O",
        "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U",
        "Y", "Y", "Y", "Y", "Y",
        "D", "D", ""
    );
    return str_replace($marTViet, $marKoDau, $mystring);
}

function convert_ascii_hexadecimal_to_string($text)
{
    /*
     $str = trim($str);
     $str = str_replace('%', '', $str);
     */
    $arrAsciiHexadecimal = array('20', '21', '22', '23', '24', '25', '26', '27', '28', '29',
        '2A', '2B', '2C', '2D', '2E', '2F', '30', '31', '32', '33',
        '34', '35', '36', '37', '38', '39', '3A', '3B', '3C', '3D',
        '3E', '3F', '40', '41', '42', '43', '44', '45', '46', '47',
        '48', '49', '4A', '4B', '4C', '4D', '4E', '4F', '50', '51',
        '52', '53', '54', '55', '56', '57', '58', '59', '5A', '5B',
        '5C', '5D', '5E', '5F', '60', '61', '62', '63', '64', '65',
        '66', '67', '68', '69', '6A', '6B', '6C', '6D', '6E', '6F',
        '70', '71', '72', '73', '74', '75', '76', '77', '78', '79',
        '7A', '7B', '7C', '7D', '7E');

    $arrChar = array(' ', '!', '"', '#', '$', '%', '&', "'", '(', ')', '*', '+', ',',
        '-', '.', '/', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        ':', ';', '<', '=', '>', '?', '@', 'A', 'B', 'C', 'D', 'E', 'F',
        'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S',
        'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '[', '\\', ']', '^', '_', '`',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
        'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        '{', '|', '}', '~');

    /*
     if (!in_array($str, $arrAsciiHexadecimal)) {
        print 'Khong co trong tu dien';
        return false;
        } else {
        $key = array_search($str, $arrAsciiHexadecimal);
        return $arrChar[$key];
        }
        */
    foreach ($arrAsciiHexadecimal as $key => $ascii) {
        $text = str_replace('%' . $ascii, $arrChar[$key], $text);
    }
    return $text;
}

function getNameFromUrl($url)
{
    $tmp = explode('/', $url);
    $name = $tmp[count($tmp) - 1];
    $name = explode('.', $name);
    $name = $name[0];
    return $name;
}

function excute_string($str)
{
    $str = str_replace('"', '\"', $str);
    $str = str_replace("'", "\'", $str);
    return $str;
}

function getImgCurl($url, $name, $type)
{
    $namePic = '';
    switch ($type) {
        case 'pro_picture':
            $namePic = 'picture/product/' . $name;
            break;
        case 'pro_picture_rs':
            $namePic = 'picture/product_rs/' . $name;
            break;
    }

    $ch = curl_init($url);
    $fp = fopen($namePic, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
    return $name;
}

function getImgCurlRS($url, $name)
{
    $namePic = '';
    $namePic = '../picture/product_rs/' . $name;
    $ch = curl_init($url);
    $fp = fopen($namePic, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
    return $name;
}

function getImageUrl($url, $name, $type)
{
    $namePic = '';
    switch ($type) {
        case 'room_type':
            $namePic = 'picture/room/' . $name;
            break;
        case 'picture_hotel':
            $namePic = 'picture/hotel/' . $name;
            break;
    }
    $img = file_put_contents($namePic, file_get_contents($url));
    return $name;
}

function intoSeek($tblName, $data = array())
{
    if (is_array($data)) {
        $sql = 'INSERT INTO ' . $tblName . ' (' . implode(', ', array_keys($data)) . ') VALUES (' . implode(',', $data) . ')';

        $db = new db_execute_return();
        $lastId = $db->db_execute($sql);
        if ($lastId <= 0) {
            print $sql;
            die;
        }

        unset($db);
        return $lastId;
    }
}

/**
 * Function insert multi record to database
 * @param   $tblName (str) name of table in database
 *          $arrData (arr2) array of array containt data of all column and type of each
 * @return  $numRecord (int) number of record inserted in database
 * @author  vuong dung
 */
function intoSeeks($tblName, $arrData = array())
{
    $a = array();
    if (!empty($arrData)) {
        $sql = 'INSERT INTO ' . $tblName . ' (' . implode(', ', array_keys(current($arrData))) . ') VALUES ';

        foreach ($arrData as $data) {
            $a[] = ' ( ' . implode(',', $data) . ' ) ';
        }
        $sql .= implode(',', $a);
        $db = new db_query($sql);

        unset($db);
    }
}

function updateDB($tblName, $colID, $ID, $up)
{
    $strSet = '';
    foreach ($up as $key => $value) {
        $arrSet[] = $key . '=' . $value;
    }
    $sql = 'UPDATE ' . $tblName . ' SET ' . implode(', ', $arrSet) . ' WHERE ' . $colID . '=' . $ID;
    $db = new db_query($sql);

    unset($db);
    if (mysql_affected_rows() > 0) return true;
    return false;
}

function dump($item)
{
    $item = array($item);
    echo '<pre>';
    print_r($item);
    echo '</pre>';
}

function convertDateTimeVG($str)
{
    $strDate = "";
    $strTime = "";
    $str = trim($str);
    if (strpos($str, "ôm nay") > 0 || strpos($str, "ôm qua") > 0) {
        $arrTemp = explode(",", $str);
        $tmp = trim($arrTemp[1]);
        $arrTemp = explode(" ", $tmp);
        $strTime = $arrTemp[1];
        if (strpos($str, "ôm nay") > 0) {
            $strDate = date("d/m/Y");
        } else {
            $strDate = date("d/m/Y", time() - 86400);
        }
    } else {
        $arrTemp = explode("-", $str);
        $strTime = trim($arrTemp[1]);
        $strDate = trim($arrTemp[0]);
    }

    return convertDateTime($strDate, $strTime);
}

// Function get HTML block by phpWebHack
function getHTML($url, $return_html = 0, $port = 80)
{
    $browser = new phpWebHacks($port);
    if ($port != 80) {
        $browser->changePort($port);
    }

    $response = $browser->get($url);
    if ($return_html == 0) {
        $response = str_get_html($response);
    }

    return $response;
}

// Function get attribute from HTML tag
function getAttrTag($tag, $attr)
{
    $re = '/' . $attr . '=["\']?([^"\' ]*)["\' ]/is';
    preg_match($re, $tag, $match);
    if ($match) {
        return urldecode($match[1]);
    } else {
        return false;
    }
}

// Function get image from URL
function getImage($url, $path, $fName)
{
    $content = file_get_contents($url);
    $fPath = $_SERVER['DOCUMENT_ROOT'] . $path . $fName;
    $handle = fopen($fPath, "w");
    fwrite($handle, $content);
    fclose($handle);

    return $fName;
}

// Remove Script & Style tags
function removeHTML($string, $img_tag = 0)
{
    $string = preg_replace('/<script.*?\>.*?<\/script>/si', ' ', $string);
    $string = preg_replace('/<style.*?\>.*?<\/style>/si', ' ', $string);
    if ($img_tag == 0) {
        $string = preg_replace('/<img[^>]+\>/si', ' ', $string);
    }
    //$string = preg_replace ('/<.*?\>/si', ' ', $string);
    //$string = str_replace ('&nbsp;', ' ', $string);
    return $string;
}

// Remove character
function getNumberFromString($string)
{
    preg_match('/[0-9]*/si', $string, $match);
    dump($match);
    return $temp[0];
}

// Remove Img tags
function removeImg($string)
{
    $string = preg_replace('/<img.*?\>/si', '', $string);
    return $string;
}

// Remove white space
function removeExcessSpace($data)
{
    $data = preg_replace('/(\n|\r)+/', '', $data); // Xóa ký tự xuống dòng
    $data = trim(preg_replace("/[ \s]+/", ' ', $data)); // Xóa ký tự trắng thừa

    return $data;
}

// Remove ky tu dac biet
function removeQuote($string)
{
    $string = trim($string);
    $string = str_replace("\'", "'", $string);
    //$string = str_replace("'", "''", $string);
    return $string;
}

// Cut a part of string
function cut_string($str, $length, $char = " ...")
{
    //Nếu chuỗi cần cắt nhỏ hơn $length thì return luôn
    $strlen = mb_strlen($str, "UTF-8");
    if ($strlen <= $length) return $str;

    //Cắt chiều dài chuỗi $str tới đoạn cần lấy
    $substr = mb_substr($str, 0, $length, "UTF-8");
    if (mb_substr($str, $length, 1, "UTF-8") == " ") return $substr . $char;

    //Xác định dấu " " cuối cùng trong chuỗi $substr vừa cắt
    $strPoint = mb_strrpos($substr, " ", "UTF-8");

    //Return string
    if ($strPoint < $length - 20) return $substr . $char;
    else return mb_substr($substr, 0, $strPoint, "UTF-8") . $char;
}

function htmlspecialbo($str)
{
    $arrDenied = array('<', '>', '\"', '"');
    $arrReplace = array('&lt;', '&gt;', '&quot;', '&quot;');
    $str = str_replace($arrDenied, $arrReplace, $str);
    return $str;
}

// Get the biggest
function getBiggestNum($arr = array())
{
    $biggest = $arr[0];
    for ($i = 0; $i < count($arr); $i++) {
        $arr[$i] = trim($arr[$i]);
        $arr[$i] = intval($arr[$i]);
        if ($arr[$i] > $biggest) {
            $biggest = $arr[$i];
        }
    }

    return $biggest;
}

// Write log
function attentionLog($content)
{
    $path = $_SERVER['DOCUMENT_ROOT'] . "/get_content/log/";
    $filename = date("Y_m_d_H") . "h.txt";

    $handle = @fopen($path . $filename, "a");
    //Nếu handle chưa có mở thêm ../
    if (!$handle) $handle = @fopen($path . $filename, "a");
    //Nếu ko mở đc lần 2 thì exit luôn
    if (!$handle) exit();

    //fwrite($handle, date("d/m/Y h:i:S A") . "\n");
    @fwrite($handle, $content);
    @fclose($handle);
}

// Function convert date(string) to time(int)
function convertDateTime($strDate = "", $strTime = "")
{
    //Break string and create array date time
    $array_replace = array("/", ":");
    $strDate = str_replace($array_replace, "-", $strDate);
    $strTime = str_replace($array_replace, "-", $strTime);
    $strDateArray = explode("-", $strDate);
    $strTimeArray = explode("-", $strTime);
    $countDateArr = count($strDateArray);
    $countTimeArr = count($strTimeArray);

    //Get Current date time
    $today = getdate();
    $day = $today["mday"];
    $mon = $today["mon"];
    $year = $today["year"];
    $hour = $today["hours"];
    $min = $today["minutes"];
    $sec = $today["seconds"];
    //Get date array
    switch ($countDateArr) {
        case 2:
            $day = intval($strDateArray[0]);
            $mon = intval($strDateArray[1]);
            break;
        case $countDateArr >= 3:
            $day = intval($strDateArray[2]);
            $mon = intval($strDateArray[1]);
            $year = intval($strDateArray[0]);
            break;
    }
    //Get time array
    switch ($countTimeArr) {
        case 2:
            $hour = intval($strTimeArray[0]);
            $min = intval($strTimeArray[1]);
            break;
        case $countTimeArr >= 3:
            $hour = intval($strTimeArray[0]);
            $min = intval($strTimeArray[1]);
            $sec = intval($strTimeArray[2]);
            break;
    }
    //Return date time integer
    if (@mktime($hour, $min, $sec, $mon, $day, $year) == -1) return $today[0];
    else return mktime($hour, $min, $sec, $mon, $day, $year);
}

?>
