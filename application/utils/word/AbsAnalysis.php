<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/8
 * Time: 上午10:32
 */

namespace app\utils\word;


use app\utils\Time;
use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser;
use think\Config;
use think\Exception;

abstract class AbsAnalysis implements Analysis
{

    private static $_SURNAME_ = array('赵','钱','孙','李','周','吴','郑','王','冯','陈','褚','卫','蒋',
        '沈','韩','杨','朱','秦','尤','许','何','吕','施','张','孔','曹','严','华','金','魏','陶','姜','戚',
        '谢','邹','喻','柏','水','窦','章','云','苏','潘','葛','奚','范','彭','郎','鲁','韦','昌','马','苗',
        '凤','花','方','俞','任','袁','柳','酆','鲍','史','唐','费','廉','岑','薛','雷','贺','倪','汤','滕',
        '殷','罗','毕','郝','邬','安','常','乐','于','时','傅','皮','卞','齐','康','伍','余','元','卜','顾',
        '孟','平','黄','和','穆','萧','尹','姚','邵','湛','汪','祁','毛','禹','狄','米','贝','明','臧','计',
        '伏','成','戴','谈','宋','茅','庞','熊','纪','舒','屈','项','祝','董','梁','杜','阮','蓝','闵','席',
        '季','麻','强','贾','路','娄','危','江','童','颜','郭','梅','盛','林','刁','钟','徐','邱','骆','高',
        '夏','蔡','田','樊','胡','凌','霍','虞','万','支','柯','昝','管','卢','莫','经','房','裘','缪','干',
        '解','应','宗','丁','宣','贲','邓','郁','单','杭','洪','包','诸','左','石','崔','吉','钮','龚','程',
        '嵇','邢','滑','裴','陆','荣','翁','荀','羊','於','惠','甄','曲','家','封','芮','羿','储','靳','汲',
        '邴','糜','松','井','段','富','巫','乌','焦','巴','弓','牧','隗','山','谷','车','侯','宓','蓬','全',
        '郗','班','仰','秋','仲','伊','宫','宁','仇','栾','暴','甘','钭','厉','戎','祖','武','符','刘','景',
        '詹','束','龙','叶','幸','司','韶','郜','黎','蓟','薄','印','宿','白','怀','蒲','台','从','鄂','索',
        '咸','籍','赖','卓','蔺','屠','蒙','池','乔','阴','郁','胥','能','苍','双','闻','莘','党','翟','谭',
        '贡','劳','逄','姬','申','扶','堵','冉','宰','郦','雍','却','璩','桑','桂','濮','牛','寿','通','边',
        '扈','燕','冀','郏','浦','尚','农','温','别','庄','晏','柴','瞿','阎','充','慕','连','茹','习','宦',
        '艾','鱼','容','向','古','易','慎','戈','廖','庚','终','暨','居','衡','步','都','耿','满','弘','匡',
        '国','文','寇','广','禄','阙','东','殴','殳','沃','利','蔚','越','夔','隆','师','巩','厍','聂','晁',
        '勾','敖','融','冷','訾','辛','阚','那','简','饶','空','曾','毋','沙','乜','养','鞠','须','丰','巢',
        '关','蒯','相','查','后','荆','红','游','竺','权','逯','盖','益','桓公','万俟','司马','上官','欧阳',
        '夏侯','诸葛','闻人','东方','赫连','皇甫','尉迟','公羊','澹台','公冶','宗政','濮阳','淳于','单于','太叔',
        '申屠','公孙','仲孙','轩辕','令狐','钟离','宇文','长孙','慕容','鲜于','闾丘','司徒','司空','亓官','司寇',
        '仉','督','子车','颛孙','端木','巫马','公西','漆雕','乐正','壤驷','公良','拓跋','夹谷','宰父','谷粱','晋',
        '楚','闫','法','汝', '鄢','涂','钦','段干','百里','东郭','南门','呼延','归海','羊舌','微生','岳','帅','缑',
        '亢','况','后','有','琴','梁丘','左丘','东门','西门', '候', '兰');

    private static $_ILL_WORDS_ = array("程师");

    private static $_POST_ = array(
        '前端工程师|前端' =>  array(
            'vue',
            'dom',
            'H5',
            'html5',
            'nodejs',
            'javascript',
            'es6',
            'css3',
            'reactjs',
            'ui',
            'ue',
            'axure',
            'browerify',
            'gulp',
            'react',
            'hybird',
            'webpack'
        ),
        'JAVA研发|java'  =>  array(
            'spring',
            'mybatis',
            'jvm',
            'gc',
            'redis',
            'dubbo',
            'zookeeper',
            'mq',
            '分布式',
            '后台系统',
            '接口',
            '数据库',
            'mysql',
            'oracle',
            'elasticsearch',
            'nginx',
            'springmvc',
            'java',
            'jdk',
        ),
        'JAVA大数据|大数据'  =>  array(
            '大数据',
            'hadoop',
            'hive',
            'spark',
            'gc',
            'spring',
            'mybatis',
            'jvm',
            'redis',
            'dubbo',
            'zookeeper',
            'mq',
            '分布式',
            '后台系统',
            '接口',
            '数据库',
            'mysql',
            'oracle',
            'elasticsearch',
            'nginx',
            'springmvc',
            'java',
            'jdk',
        ),
        '测试工程师|测试' => array(
            'bug',
            '测试',
            '测试工具',
            '接口测试',
            'linux',
            '产品缺陷',
            '测试环境',
            '测试工具',
            '项目测试',
            '改进意见',
        ),
        'Android开发高级工程师|android'=> array(
            'android',
            '系统碎片化',
            '开源库'
        ),
        'Android开发工程师|android'  =>  array(
            'android'
        ),
        '产品经理|产品'  =>  array(
            '需求分析',
            'visio',
            'word',
            'ppt',
            '规划',
            '产品',
        ),
        'IOS高级开发工程师|ios' => array(
            'objective',
            'swift',
            'xcode',
            'garthang',
            'tcp',
            'udp',
            'https',
            'http',
            'sqlite',
            'core data',
            'object',
            'runtime',
            'autolayout',
            'android',
            'github',
            'oschina',
            'reactnative'
        ),
        'IOS研发工程师|ios'   =>  array(
            'objective',
            'swift',
            'xcode',
            'garthang',
            'ios',
        )
    );

    /**
     * @param $file 全局路径
     * @return mixed
     */
    public function analysis($file)
    {
        $text = $this->toString($file);
        $name = $this->analysisName($text, basename($file));
        $email = $this->analysisEmail($text);
        $mobile = $this->analysisMobile($text);
        $post = $this->analysisPost($text, basename($file));
        $sex = $this->analysisSex($text);
        return $this->buildAnalysisResult($name, $post, $mobile, $email, $sex);
    }

    /**
     * 将文档转换成文本
     * @param $file
     * @return string
     */
    private function toString($file)
    {
        $tempfile = Config::get("temp_path") . '/' . Time::microtime() . '.html';
        ! is_dir(dirname($tempfile)) && mkdir(dirname($tempfile), 0777, true);

//        echo $tempfile;
        if (strpos($file, '.docx') !== false) {
            $phpword = IOFactory::load($file, 'Word2007');
            $phpword->save($tempfile, 'HTML');
            $content = file_get_contents($tempfile);
            unlink($tempfile);
            return $this->getBodyFromHtml($content);
        } else if(strpos($file, '.pdf')) {
            $parser = new Parser();
            $pdf = $parser->parseFile($file);
            return $pdf->getText();
        } else if (strpos($file, '.doc')) {
            $phpword = IOFactory::load($file, 'RTF');
            $phpword->save($tempfile, 'HTML');
            $content = file_get_contents($tempfile);
            $content = str_replace(array("&lt;", "&gt;"), array("<", ">"), $content);
            unlink($tempfile);
            return $this->getBodyFromHtml($content);
        }
        return "";
    }

    /**
     * 在html文件中找出正文
     * @param $html
     * @return string
     */
    private function getBodyFromHtml($html)
    {
        $match = array();
        preg_match('/<body>([\s\S]*?)<\/body>/', $html, $match);
        return strip_tags($match[0]);
    }

    /**
     *
     * 根据内容、简历名称分析姓名
     * @param $text
     * @param $fileName
     * @return mixed|string
     */
    private function analysisName($text, $fileName)
    {
        $analysisResult = array();
        foreach (AbsAnalysis::$_SURNAME_ as $surname) {
            preg_match("/".$surname."[ ]?+[\x{4e00}-\x{9fa5}]{1,2}/u", $text, $match);
            if (empty($match)) {
                continue;
            }
            if (in_array($match[0], AbsAnalysis::$_ILL_WORDS_)) {
                continue;
            }
            //  匹配结果再分析
            $matchName = $match[0];
            $position = strpos($text, $matchName);
            if ($position === false) {
                continue;
            }
            $analysisResult[$position] = str_replace(" ", "", $matchName);
        }
//        echo $text;
        //按照位置进行排序
        ksort($analysisResult);
//        dump($analysisResult);
        //  如果分析的结果，也在文件名称上出现，那么就认为他是名字
        foreach ($analysisResult as $name) {
            if (strpos($fileName, $name) !== false) {
                return $name;
            }
        }

        //  检查文档上是否 有以姓氏开头的字符串
        foreach (AbsAnalysis::$_SURNAME_ as $surname) {
            preg_match_all("/".$surname."[ ]?+[\x{4e00}-\x{9fa5}]{1,2}/u", $fileName, $match);
            if (empty($match)) {
                continue;
            }
            foreach ($match[0] as $m) {
                if (in_array($m, AbsAnalysis::$_ILL_WORDS_)) {
                    continue;
                }
                return $m;
            }
        }
        //  没有匹配到名称
        return "";
    }

    /**
     * 分析email
     * @param $text
     * @return mixed
     */
    private function analysisEmail($text)
    {
        preg_match("/([a-z|A-Z|\d]+@[a-z|A-Z|\d]+\.[a-z]{2,3})/", $text, $match);
        return array_shift($match);
    }

    /**
     * 分析联系方式
     * @param $text
     * @return mixed
     */
    private function analysisMobile($text)
    {
        $text = str_replace(array("-", " ", "\t"), "", $text);
        preg_match("/1\d{10}/", $text, $match);
        return array_shift($match);
    }

    /**
     * 分析职位
     * @param $text
     * @param $fileName
     * @return string
     */
    private function analysisPost($text, $fileName)
    {
        //  分析应聘职位
        $post = $this->analysisPostFromFileName($fileName);
        if (!empty($post)) {
            return $post;
        }

        $analysisResult = $this->analysisPostFromText($text);
//        $fileNameArray = $this->splitFileName($fileName);
//        foreach ($analysisResult as $result) {
//            foreach($fileNameArray as $post) {
//                if (strpos(strtolower($post), $result['keyword']) !== false) {
//                    return $post;
//                }
//            }
//        }

        $matchNum = 0;
        $post = "";
        foreach ($analysisResult as $result) {
            $num = count($result['matches']);
            if ($num > $matchNum) {
                $matchNum = $num;
                $post = $result['recommend'];
            }
        }
        return $post;
    }

    /**
     * 匹配到的职位列表
     * @param $text
     * @return array
     */
    private function analysisPostFromText($text)
    {
        $text=strtolower($text);
        $results = array();
        foreach (AbsAnalysis::$_POST_ as $post=> $tags) {
            preg_match_all("/".implode("|", $tags)."/u", $text, $matches);
            if (empty($matches[0])) {
                continue;
            }
            $posts = explode("|", $post);
            array_push($results, array(
                'recommend' =>  array_shift($posts),
                'keyword'   =>  array_shift($posts),
                'matches'   =>  $matches[0]
            ));
        }
        return $results;
    }

    /**
     * 从简历名称上分析应聘职位
     * @param $text
     * @return null|string
     */
    private function analysisPostFromFileName($text)
    {
        $fileNameArray = $this->splitFileName($text);
        foreach($fileNameArray as $postName) {
            foreach (AbsAnalysis::$_POST_ as $post=> $tag) {
                list($normalPostName, $keyword) = explode("|", $post);
                if (strpos(strtolower($postName), $keyword) !== false) {
                    return $postName;
                }
            }
        }
        return null;
    }

    /**
     * 分析性别
     * @param $text
     * @return string
     */
    private function analysisSex($text)
    {
        if (strpos($text, "男") !== false) {
            return "男";
        } else if (strpos($text, "女") !== false) {
            return "女";
        } else {
            return '';
        }
    }

    /**
     * 构建标准化数据
     * @param $name
     * @param $post
     * @param $mobile
     * @param $email
     * @param $sex
     * @return array
     */
    private function buildAnalysisResult($name, $post, $mobile, $email, $sex)
    {
        return array(
            'name'  =>  $name,
            'post'  =>  $post,
            'mobile'    =>  $mobile,
            'email'     =>  $email,
            'sex'   =>  $sex
        );
    }

    /**
     * 将文件名进行分割
     * @param $filename
     * @return array
     */
    abstract function splitFileName($filename);

}