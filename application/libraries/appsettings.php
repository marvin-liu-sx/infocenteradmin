<?php

/**
 * 应用程序配置类
 * 
 * 	1. 如果构造参数$value是一个数组, 则配置信息直接就在这个数组中
 *  2. 如果构造参数$value是一个字符串, 则表示它是实际存储配置的地址,一般为http地址
 * 		典型的地址格式为:'http://config.aplusapi.pptv.com/web/[app name]/[md5(url)]'
 */
require_once APPPATH . 'libraries/root.php';
require_once APPPATH . 'libraries/common.php';
require_once APPPATH . 'libraries/sysutils.php';
class AppSettings {

    private $key;
    private $value;
    private $appname;
    private $appsettings;
    private static $basedir = 'http://config.aplusapi.pptv.com/web/';

    public function __construct($appname, $key, $value) {
        $this->key = $key;
        $this->value = $value;
        $this->appname = $appname;
    }

    public function get($name, $default = false) {
        if (!isset($this->appsettings)) {
            $appsettings = $this->loadSettings();
            $this->appsettings = $appsettings;
        } else {
            $appsettings = $this->appsettings;
        }
        if (isset($appsettings[$name])) {
            return $appsettings[$name];
        }
        return $default;
    }

    public function toArray() {
        if (!isset($this->appsettings)) {
            $appsettings = $this->loadSettings();
            $this->appsettings = $appsettings;
        }
        return $this->appsettings;
    }

    public function loadSettings() {
        $appsettings = false;
        if (is_array($this->value)) {
            $appsettings = $this->value;
        } else if (is_string($this->value)) {
            $appmapping = Root::getAppMapping($this->appname);

            // 首先, 判断一下是否是一个合法的App, 如果不是的话, 直接抛Exception
            if ($appmapping === false) {
                Common::reportError('Found unknown appname :' . $this->appname, __FILE__, __LINE__);
                return false;
            }

            // 然后, 判断缓存目录下是否配置文件已经存在, 如果存在则以缓存文件为准
            $cache_filename = $appmapping['cachedir'] . '/settings/' . $this->key . '.php';
            
            if (file_exists($cache_filename)) {
                $appsettings = require($cache_filename);
            }

            // 之后, 判断应用的版本号是否存在(或者版本号是否为0.0.0.0）, 如果是, 则表示这些是系统固有应用. 这种情况下
            //	还要看看固有应用中是否存在对应的配置文件, 如果存在, 则以那个文件为准
            else if (!isset($appmapping['version']) || $appmapping['version'] == '0.0.0.0') {
                $idcname = SysUtils::getIDCName();
                $init_filename = $appmapping['confdir'] . '/settings/' . $this->key . '-' . $idcname . '.php';
                if (!file_exists($init_filename)) {
                    $init_filename = $appmapping['confdir'] . '/settings/' . $this->key . '.php';
                }
               

                if (file_exists($init_filename)) {
                    //如果存在系统固有应用对应的配置文件, 则将这个文件拷贝到缓存目录
                    $dir = $appmapping['cachedir'] . '/settings';
                    if (!is_dir($dir)) {
                        if (!mkdir($dir, 0777, true)) {
                            Common::reportError('Failed to create directory : ' . $dir, __FILE__, __LINE__);
                            return false;
                        }
                    }

                    if (!copy($init_filename, $cache_filename)) {
                        Common::reportError('Failed to copy setting file to : ' . $cache_filename);
                        return false;
                    }

                    $appsettings = require($cache_filename);
                }
            }
            if ($appsettings === false) {
                if (!$this->updateConfig()) {
                    return false;
                }
                $appsettings = require($filename);
            }
        } else {
            $appsettings = array();
        }
        return $appsettings;
    }

    public function updateConfig() {
        if (is_string($this->value)) {
            $appmapping = Root::getAppMapping($this->appname);

            // 首先, 判断一下是否是一个合法的App, 如果不是的话, 直接抛Exception
            if ($appmapping === false) {
                Common::reportError('Found unknown appname :' . $this->appname, __FILE__, __LINE__);
                return false;
            }

            $filename = $appmapping['cachedir'] . '/settings/' . $this->key . '.php';
            if (!SysUtils::saveUrlToFile($this->value, $filename, '@<configurations>(.*?)</configurations>$@is', '<?php return array(%s);')) {
                return false;
            }

            $this->appsettings = require($filename);
        }
        return true;
    }

}
