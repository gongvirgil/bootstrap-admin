<?php
// 读取文件
function read_file($l1) 

{
    return @file_get_contents ( $l1 );
}

// 写入文件
function write_file($l1, $l2 = '') {
    $dir = dirname ( $l1 );
    
    if (! is_dir ( $dir )) {
        
        mkdirss ( $dir );
    }
    
    return @file_put_contents ( $l1, $l2 );
}

// 递归创建文件
function mkdirss($dirs, $mode = 0777) {
    if (! is_dir ( $dirs )) {
        
        mkdirss ( dirname ( $dirs ), $mode );
        
        return @mkdir ( $dirs, $mode );
    }
    
    return true;
}

// 数组保存到文件
function arr2file($filename, $arr = '') {
    if (is_array ( $arr )) {
        
        $con = var_export ( $arr, true );
    } else {
        
        $con = $arr;
    }
    
    $con = "<?php\nreturn $con;\n?>";
    
    write_file ( $filename, $con );
}

// 获取文件夹大小
function getdirsize($dir) 

{
    $dirlist = opendir ( $dir );
    
    while ( false !== ($folderorfile = readdir ( $dirlist )) ) 

    {
        
        if ($folderorfile != "." && $folderorfile != "..") 

        {
            
            if (is_dir ( "$dir/$folderorfile" )) 

            {
                
                $dirsize += getdirsize ( "$dir/$folderorfile" );
            } 

            else 

            {
                
                $dirsize += filesize ( "$dir/$folderorfile" );
            }
        }
    }
    
    closedir ( $dirlist );
    
    return $dirsize;
}
function get_ip() 

{
    if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
        
        $ip = getenv ( "HTTP_CLIENT_IP" );
    
    else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
        
        $ip = getenv ( "HTTP_X_FORWARDED_FOR" );
    
    else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
        
        $ip = getenv ( "REMOTE_ADDR" );
    
    else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
        
        $ip = $_SERVER ['REMOTE_ADDR'];
    
    else
        
        $ip = "unknown";
    
    return ($ip);
}

// 替换采集等通过url参数传值
function wk_url_repalce($xmlurl, $order = 'asc') 

{
    if ($order == 'asc') 

    {
        
        return str_replace ( array (
                '|',
                '@',
                '#' 
        ), array (
                '/',
                '=',
                '&' 
        ), $xmlurl );
    } 

    else 

    {
        
        return str_replace ( array (
                '/',
                '=',
                '&' 
        ), array (
                '|',
                '@',
                '#' 
        ), $xmlurl );
    }
}

// 获取操作系统
function get_client_os() {
    $agent = $_SERVER ['HTTP_USER_AGENT'];
    
    $os = false;
    
    if (eregi ( 'win', $agent ) && strpos ( $agent, '95' )) {
        
        $os = 'Windows 95';
    } 

    else if (eregi ( 'win 9x', $agent ) && strpos ( $agent, '4.90' )) {
        
        $os = 'Windows ME';
    } 

    else if (eregi ( 'win', $agent ) && ereg ( '98', $agent )) {
        
        $os = 'Windows 98';
    } 

    else if (eregi ( 'win', $agent ) && eregi ( 'nt 5.1', $agent )) {
        
        $os = 'Windows XP';
    } 

    else if (eregi ( 'win', $agent ) && eregi ( 'nt 5', $agent )) {
        
        $os = 'Windows 2000';
    } 

    else if (eregi ( 'win', $agent ) && eregi ( 'nt', $agent )) {
        
        $os = 'Windows NT';
    } 

    else if (eregi ( 'win', $agent ) && ereg ( '32', $agent )) {
        
        $os = 'Windows 32';
    } 

    else if (eregi ( 'linux', $agent )) {
        
        $os = 'Linux';
    } 

    else if (eregi ( 'unix', $agent )) {
        
        $os = 'Unix';
    } 

    else if (eregi ( 'sun', $agent ) && eregi ( 'os', $agent )) {
        
        $os = 'SunOS';
    } 

    else if (eregi ( 'ibm', $agent ) && eregi ( 'os', $agent )) {
        
        $os = 'IBM OS/2';
    } 

    else if (eregi ( 'Mac', $agent ) && eregi ( 'PC', $agent )) {
        
        $os = 'Macintosh';
    } 

    else if (eregi ( 'PowerPC', $agent )) {
        
        $os = 'PowerPC';
    } 

    else if (eregi ( 'AIX', $agent )) {
        
        $os = 'AIX';
    } 

    else if (eregi ( 'HPUX', $agent )) {
        
        $os = 'HPUX';
    } 

    else if (eregi ( 'NetBSD', $agent )) {
        
        $os = 'NetBSD';
    } 

    else if (eregi ( 'BSD', $agent )) {
        
        $os = 'BSD';
    } 

    else if (ereg ( 'OSF1', $agent )) {
        
        $os = 'OSF1';
    } 

    else if (ereg ( 'IRIX', $agent )) {
        
        $os = 'IRIX';
    } 

    else if (eregi ( 'FreeBSD', $agent )) {
        
        $os = 'FreeBSD';
    } 

    else if (eregi ( 'teleport', $agent )) {
        
        $os = 'teleport';
    } 

    else if (eregi ( 'flashget', $agent )) {
        
        $os = 'flashget';
    } 

    else if (eregi ( 'webzip', $agent )) {
        
        $os = 'webzip';
    } 

    else if (eregi ( 'offline', $agent )) {
        
        $os = 'offline';
    } 

    else {
        
        $os = 'Unknown';
    }
    
    return $os;
}
function msubstrs($str, $start = 0, $length, $charset = "utf-8", $suffix = true) 

{
    if (function_exists ( "mb_substr" ))
        
        return mb_substr ( $str, $start, $length, $charset );
    
    elseif (function_exists ( 'iconv_substr' )) {
        
        return iconv_substr ( $str, $start, $length, $charset );
    }
    
    $re ['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    
    $re ['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    
    $re ['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    
    $re ['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    
    preg_match_all ( $re [$charset], $str, $match );
    
    $slice = join ( "", array_slice ( $match [0], $start, $length ) );
    
    if ($suffix)
        return $slice . "…";
    
    return $slice . '…';
}

// php获取第一张图片
function getimg($data) {
    $first_img = '';
    
    $output = preg_match_all ( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $data, $matches );
    
    $first_img = $matches [1] [0];
    
    if (empty ( $first_img )) { // 如果文章内没有图片就用默认的图片.
        
        $first_img = "./Public/images/default.jpg";
    }
    
    return $first_img;
}
function deldir($dirname) 

{
    if (file_exists ( $dirname )) 

    {
        
        $dir = opendir ( $dirname );
        
        while ( $filename = readdir ( $dir ) ) 

        {
            
            if ($filename != "." && $filename != "..") 

            {
                
                $file = $dirname . "/" . $filename;
                
                if (is_dir ( $file )) 

                {
                    
                    deldir ( $file ); // 使用递归删除子目录
                } 

                else 

                {
                    
                    unlink ( $file );
                }
            }
        }
        
        closedir ( $dir );
        
        rmdir ( $dirname );
    }
}

?>