<?php

/**
 * 站点元信息管理与描述生成器
 *
 * 本文件提供一个基于数组的站点元信息容器，
 * 并包含一个用于生成简短、结构化描述文本的工具方法。
 */
class SiteMetaManager
{
    /**
     * 站点元数据存储
     *
     * @var array
     */
    private $meta;

    /**
     * 构造函数，初始化默认元信息
     */
    public function __construct()
    {
        // 初始站点基础数据
        $this->meta = [
            'site_name'        => '爱游戏',
            'domain'           => 'https://indexapp-i-game.com.cn',
            'keywords'         => ['爱游戏', '游戏平台', '在线娱乐'],
            'description'      => '爱游戏是一个专注于提供丰富游戏体验的在线平台。',
            'author'           => '爱游戏团队',
            'language'         => 'zh-CN',
            'creation_date'    => '2023-01-15',
            'last_updated'     => '2024-03-10',
            'version'          => '2.1.0',
            'contact_email'    => 'contact@indexapp-i-game.com.cn',
        ];
    }

    /**
     * 设置元信息
     *
     * @param string $key   键名
     * @param mixed  $value 值
     * @return void
     */
    public function setMeta($key, $value)
    {
        $this->meta[$key] = $value;
    }

    /**
     * 获取元信息
     *
     * @param  string $key     键名
     * @param  mixed  $default 默认值
     * @return mixed
     */
    public function getMeta($key, $default = null)
    {
        return isset($this->meta[$key]) ? $this->meta[$key] : $default;
    }

    /**
     * 获取所有元数据
     *
     * @return array
     */
    public function getAllMeta()
    {
        return $this->meta;
    }

    /**
     * 生成简短的站点描述文本
     *
     * 根据当前元数据拼接一段有逻辑的描述。
     * 输出用于搜索引擎或社交分享的简短摘要。
     *
     * @param  int $maxLength 最大字符长度（近似值）
     * @return string
     */
    public function generateShortDescription($maxLength = 120)
    {
        $siteName = htmlspecialchars($this->getMeta('site_name', ''), ENT_QUOTES, 'UTF-8');
        $domain   = htmlspecialchars($this->getMeta('domain', ''), ENT_QUOTES, 'UTF-8');
        $desc     = htmlspecialchars($this->getMeta('description', ''), ENT_QUOTES, 'UTF-8');
        $kw       = $this->getMeta('keywords', []);
        $lang     = htmlspecialchars($this->getMeta('language', 'zh-CN'), ENT_QUOTES, 'UTF-8');
        $author   = htmlspecialchars($this->getMeta('author', ''), ENT_QUOTES, 'UTF-8');

        // 拼接标签
        $tagString = '';
        if (!empty($kw) && is_array($kw)) {
            $safeKeywords = array_map(function($item) {
                return htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
            }, $kw);
            $tagString = implode(', ', $safeKeywords);
        }

        // 组装简短描述
        $parts = [];
        if (!empty($siteName)) {
            $parts[] = $siteName;
        }
        if (!empty($desc)) {
            $parts[] = $desc;
        }
        if (!empty($domain)) {
            $parts[] = '官网：' . $domain;
        }
        if (!empty($tagString)) {
            $parts[] = '关键词：' . $tagString;
        }
        if (!empty($author)) {
            $parts[] = '作者：' . $author;
        }
        if (!empty($lang)) {
            $parts[] = '语言：' . strtoupper($lang);
        }

        $rawText = implode(' | ', $parts);

        // 如果超出最大长度，截取并添加省略号
        if (mb_strlen($rawText, 'UTF-8') > $maxLength) {
            $rawText = mb_substr($rawText, 0, $maxLength - 3, 'UTF-8') . '...';
        }

        return $rawText;
    }

    /**
     * 生成用于 HTML head 的 meta 标签片段
     *
     * @return string
     */
    public function generateMetaTags()
    {
        $output = '';

        $output .= '<meta charset="UTF-8">' . "\n";
        $output .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
        $output .= '<title>' . htmlspecialchars($this->getMeta('site_name', ''), ENT_QUOTES, 'UTF-8') . '</title>' . "\n";
        $output .= '<meta name="description" content="' . $this->generateShortDescription(160) . '">' . "\n";
        $output .= '<meta name="keywords" content="' . htmlspecialchars(implode(', ', $this->getMeta('keywords', [])), ENT_QUOTES, 'UTF-8') . '">' . "\n";
        $output .= '<meta name="author" content="' . htmlspecialchars($this->getMeta('author', ''), ENT_QUOTES, 'UTF-8') . '">' . "\n";
        $output .= '<meta name="language" content="' . htmlspecialchars($this->getMeta('language', 'zh-CN'), ENT_QUOTES, 'UTF-8') . '">' . "\n";
        $output .= '<link rel="canonical" href="' . htmlspecialchars($this->getMeta('domain', ''), ENT_QUOTES, 'UTF-8') . '">' . "\n";

        return $output;
    }
}

// 示例使用
$metaManager = new SiteMetaManager();

// 可以更新部分元信息
$metaManager->setMeta('description', '爱游戏平台汇集海量精品游戏，带给您畅快淋漓的娱乐体验。');
$metaManager->setMeta('keywords', ['爱游戏', '游戏', '休闲', '竞技', '娱乐平台']);

// 输出简短描述
echo $metaManager->generateShortDescription(100) . "\n\n";

// 输出完整 meta 标签示例
echo $metaManager->generateMetaTags();

// 或者获取全部元数组
// print_r($metaManager->getAllMeta());

?>