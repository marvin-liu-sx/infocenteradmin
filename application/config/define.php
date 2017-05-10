<?php
#------------------------------------------------------常用常量配置文件-----------------------------------------
/**
 * @author louisGao
 * @var unknown      //search  所用的常量
 */


define("REQUEST_SUCC", 0);  //请求成功

define("REQUEST_ERROR", 1);  //请求失败

# ----------------------  请求状态 ------------------------------------------


define("EPGCATE_ALL", -2);  //全部
define("EPGCATE_MOVIE", 1);  //电影
define("EPGCATE_OTHER", -1);  //其他
define("EPGCATE_TV", 2);  //电视剧
define("EPGCATE_VARIETY", 4);  //综艺
define("EPGCATE_SPORT", 5);  //体育
define("EPGCATE_HOT", 6);  //热点
define("EPGCATE_GAME", 7);  //游戏
define("EPGCATE_VIP", 75099);  //VIP尊享
define("EPGCATE_CARTOON", 3);  //动漫

#-------------------------------------SO 接口EPG分类-------------------------------

define("SCOPETYPE_POSITIVE", 0);  //正片
define("SCOPETYPE_NOPOSITIVE", 1);  //非正片
define("SCOPETYPE_SEEDING", 2);  //直播

#-------------------------------------SO 接口片源类型--------------------------------

define("SCOPETYPE_SEEDING_BEFORE", 0);  //直播前期
define("SCOPETYPE_SEEDING_LIVEING", 1);  //直播中期
define("SCOPETYPE_SEEDING_AFTER", 2);  //直播后期

#-------------------------------------SO 接口节目状态--------------------------------


