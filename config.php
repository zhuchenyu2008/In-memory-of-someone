<?php
// ===============================================================
// 配置文件 (config.php)
// 在这里修改所有的可变内容
// ===============================================================

// 时区设置 (确保时间计算准确)
// Timezone setting (ensures accurate time calculation)
date_default_timezone_set('Asia/Shanghai');

// --- 主要配置 ---

/**
 * @var string 分开的日期和时间 (The date and time of separation)
 * 格式 (Format): 'YYYY-MM-DD HH:MM:SS'
 * 这是最重要的设置，时间计数器将基于此时间进行计算。
 * This is the most important setting. The time counter is based on this.
 */
define('SEPARATION_DATE', '2025-5-11 17:00:00');


// --- 页面文本内容 (Page Text Content) ---

/**
 * @var string 网页标题 (Website Title)
 */
define('PAGE_TITLE', '纪念');

/**
 * @var string 主标题 (Main Title)
 */
define('MAIN_TITLE', '致<br>我们共同度过的时光');

/**
 * @var string 主要段落 (Main Paragraph)
 * 使用 <br> 标签来进行换行。
 * Use the <br> tag for line breaks.
 */
define('MAIN_PARAGRAPH', '有些名字，不必开口，便在心上<br>有些过往，无需回首，亦是风景');

/**
 * @var string 时间计数器前的文字 (Text before the time counter)
 */
define('TIME_COUNTER_PREFIX', '自我们分开，已经过去了');

/**
 * @var string 页脚文字 (Footer Text)
 */
define('FOOTER_TEXT', '一份隐秘在角落的纪念');


// --- 动画延迟配置 (Animation Delay Configuration) ---
// 单位为秒 (Unit: seconds). 例如: '0.5s', '1.2s'

/**
 * @var string 主标题的出现延迟时间
 */
define('ANIMATION_DELAY_1', '1s');

/**
 * @var string 主要段落的出现延迟时间
 */
define('ANIMATION_DELAY_2', '3s');

/**
 * @var string 时间计数器整体的出现延迟时间
 */
define('ANIMATION_DELAY_3', '5s');

/**
 * @var string 页脚文字的出现延迟时间
 */
define('ANIMATION_DELAY_4', '7s');

