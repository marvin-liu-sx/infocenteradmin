<?php

/**
 * 全局自动加载类的实现, 注意整个类的加载规则为, 只负责包含namespace的类的加载, 整个类名的第一
 * 部分(以'/'分割)将作为应用程序名[app name], 被系统映射为成具体的类的存放路径[mapdir], 其计算
 * 规则如下:
 * 
 * 	1. 所有应用程序的的映射关系都存在 '/cache/apps/appSetting.php', 如果在这个文件中找到了对
 * 		应的应用程序的映射关系, 则: 按照这个文件中的映射关系来映射对应的类
 * 
 *  2. 如果找不到对应的映射关系, 则尝试从目录'/oap/apps/'中找到对应的应用程序, 并自动设置映射
 *  	关系
 */
class Root {

    /**
     * 获得系统根目录
     */
    public static function getRootDirectory() {
        static $root_directory = null;
        if ($root_directory !== null) {
            return $root_directory;
        }
        $root_directory = dirname(dirname(__FILE__));
        return $root_directory;
    }

    /**
     * 自动加载类文件的回调
     * @param $class_name 类名
     */
    public static function __class_auto_loader__($class_name) {
        //
        // 分解类名
        //		1. 首先, 判断类名的第一部分在class_mappings中是否有映射, 有的话则根据class_mappings中的设置修改路径
        //		2. 其次, 判断判断cache目录下对应的App目录中是否有相关的设置, 具体的
        //				配置存储的文件名为:'/cache/ovp/app/[app name]/appSetting.php'
        //				设置数据项为: 'class_mapping'
        //
		$class_names = explode('\\', strtolower($class_name), 2);
        if (count($class_names) === 2) {
            $appname = $class_names[0];
            static $app_classes_mappings = array();
            if (isset($app_classes_mappings[$appname])) {
                $maptodir = $app_classes_mappings[$appname];
            } else {
                $appmapping = self::getAppMapping($appname);
                if ($appmapping !== false) {
                    $maptodir = $appmapping['appdir'];
                } else {
                    $maptodir = false;
                }
                $app_classes_mappings[$appname] = $maptodir;
            }
            if ($maptodir !== false) {
                require($maptodir . '/classes/' . str_replace('\\', '/', $class_names[1]) . '.php');
            }
        }
    }

    /**
     * 获取应用程序路径
     * @param $appname 应用名称
     */
    public static function getAppMapping($appname) {
        //
        // 1. 其次, 读取oap的缓存目录中的应用程序部署文件/cache/oap/oap/appMappings.php, 这
        //	个文件中将直接记录当前应用程序的目录信息, 这个文件是在自动化部署完成之后, 自动生成的
        //
		static $app_mappings = null;
        if ($app_mappings === null) {
            $filename = self::getRootDirectory() . '/cache/apps/appMappings.php';
            if (file_exists($filename)) {
                $app_mappings = require($filename);
            } else {
                $app_mappings = array();
            }
        }

        if (isset($app_mappings[$appname])) {
            $appmapping = $app_mappings[$appname];
        } else {
			$appdir = self::getRootDirectory().'/config/'.$appname;
			if( is_dir($appdir) )
			{
				$appmapping = array(
					'confdir' => $appdir,
					'cachedir' => self::getRootDirectory().'/cache/apps/'.$appname.'/0.0.0.0',
				);
			}
			else
			{
				$appmapping = false;
			}
           
            $app_mappings[$appname] = $appmapping;
        }
        return $appmapping;
    }

}

//spl_autoload_register(array('Root', '__class_auto_loader__'));
