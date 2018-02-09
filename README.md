# rms
招聘管理系统 [传送门](http://rms.rbacshiro.com)

基于TP5(ThinkPHP5)框架，实现了招聘的整个流程。
上传简历->筛选简历->安排面试->面试反馈->面试通过->offer(未实现)
由于offer流程和招聘流程关系不大，所以未进行实现

## 简历上传
目前系统支持2种上传方式，
- 1、批量上传
-   需要人为标准化信息，进行数据准确的批量上传
- 2、快速上传
-    将所有简历进行批量上传，上传后再做数据确认

## 简历分析

目前支持的格式有：docx、doc、pdf

从简历中提取内容使用到了:[phpword](https://github.com/PHPOffice/PHPWord)、[pdfparse](https://github.com/smalot/pdfparser)

如果使用简历上传的第2个途径，会进行简历的分析。
从简历内容、简历名称中，提取应聘人姓名、手机号，性别、邮箱、应聘职位信息。
经过上传人进行二次确认后，达到有效标准，才能进入简历库中。

标准为：姓名 + 手机号 + 应聘职位 + 简历

目前对职位只分析了：Java、Android、IOS、大数据

需要[自定义](https://github.com/wzypandaking/rms/blob/master/application/utils/word/Analysis.php)实现方式

## 安装 Install

### 安装composer [传送门](http://docs.phpcomposer.com/00-intro.html#Installation-*nix)
### 加载依赖
- composer update
- 编辑vendor/phpoffice/phpword/src/PhpWord/Shared/AbstractEnum.php
```php
    第67行
    public static function validate($value)
    {
        // todo modify only check value is not empty
        if ($value) {
            if (!self::isValid($value)) {
                $calledClass = get_called_class();
                $values = array_values(self::getConstants());
                throw new \InvalidArgumentException("$value is not a valid value for $calledClass, possible values are " . implode(', ', $values));
            }
        }
    }
```
- 编辑vendor/phpoffice/phpword/src/PhpWord/Element/Image.php
```php
    第384行
    if (!is_array($imageData)) {
        //  todo modify give a default image type
        $imageData = array(0, 0, 3);
//            throw new InvalidImageException(sprintf('Invalid image: %s', $this->source));
    }
```
- 编辑vendor/phpoffice/phpword/src/PhpWord/Reader/RTF/Document.php
```php
    第267行
    // todo check if key is exist
    if (isset($this->flags['paragraph'])) {
        if (true === $this->flags['paragraph']) {
            $this->flags['paragraph'] = false;
            $this->flags['text'] = $this->text;
        }
    }
```


## 未实现

目前系统没有用户概念，所有的人都可以进行上传，下载简历。就目前来说已经能满足我的需求了。如果有什么需要的可以联系我。
我的QQ：1291946094