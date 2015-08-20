<?php
/**
 * Scws分词处理
 *
 * PHP Version 5
 * Scws分词处理
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/8/13 下午5:34
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace common\vendor;

/**
 * Scws
 *
 * @category Channel
 * @package  Scws
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class Scws
{
    /**
     * 分词处理
     * @param string $keywords 搜索词
     * @return string
     */
    public static function getWords($keywords)
    {
        $so = scws_new();
        $so->set_charset('utf-8');
        //默认词库
        $so->add_dict(ini_get('scws.default.fpath') . '/dict.utf8.xdb');
        //自定义词库
// $so->add_dict('./dd.txt',SCWS_XDICT_TXT);
        //默认规则
        $so->set_rule(ini_get('scws.default.fpath') . '/rules.utf8.ini');

        //设定分词返回结果时是否去除一些特殊的标点符号
        $so->set_ignore(true);
        //设定分词返回结果时是否复式分割，如“中国人”返回“中国＋人＋中国人”三个词。
        // 按位异或的 1 | 2 | 4 | 8 分别表示: 短词 | 二元 | 主要单字 | 所有单字
        //1,2,4,8 分别对应常量 SCWS_MULTI_SHORT SCWS_MULTI_DUALITY SCWS_MULTI_ZMAIN SCWS_MULTI_ZALL
        $so->set_multi(false);

        //设定是否将闲散文字自动以二字分词法聚合
        $so->set_duality(false);

        //设定搜索词
        $so->send_text($keywords);
        $words_array = $so->get_result();
        $words = "";
        foreach ($words_array as $v) {
            $words = $words.'|('.$v['word'].')';
        }

        //加入全词
        $words = trim($words, '|');
        $so->close();
        return $words;
    }

}
