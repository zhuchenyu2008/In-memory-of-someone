<?php
// 引入配置文件
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo PAGE_TITLE; ?></title>
    <!-- 引入 Tailwind CSS 以便快速构建响应式布局 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- 引入字体 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* 自定义样式和动画 */
        body {
            font-family: 'Noto Serif SC', serif;
            background-color: #000;
            color: #e0e0e0;
            overflow: hidden; /* 防止页面滚动，因为是单页全屏设计 */
            cursor: default;
        }

        /* 内容容器 */
        .content-container {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 2rem;
            /* 新增下面两行来将内容上移 */
            padding-bottom: 25vh; /* 这个值可以调整上移的幅度 */
            box-sizing: border-box; /* 确保 padding 不会影响容器总高度 */
            text-align: center;
        }
        
        /* 初始加载时的淡入动画 */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            opacity: 0; /* 初始状态为透明 */
            animation: fadeIn 1.5s ease-out forwards;
        }

        /* 时间计数器样式 */
        #time-counter {
            font-size: 1.1rem;
            line-height: 1.8;
            letter-spacing: 1px;
            font-weight: 700;
            color: #fafafa;
            min-height: 100px; /* 预留空间防止抖动 */
        }
        
        @media (min-width: 768px) {
             #time-counter {
                font-size: 1.5rem;
             }
        }

        /* 点击时的波纹效果 */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-effect 0.6s ease-out;
            pointer-events: none; /* 确保波纹不会影响其他点击事件 */
        }

        @keyframes ripple-effect {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</head>
<body>

    <!-- 主要内容 -->
    <main class="content-container">
        <h1 class="text-4xl md:text-5xl font-bold tracking-wider mb-6 fade-in" style="animation-delay: <?php echo ANIMATION_DELAY_1; ?>;"><?php echo MAIN_TITLE; ?></h1>
        
        <p class="text-lg md:text-xl leading-relaxed mb-8 fade-in" style="animation-delay: <?php echo ANIMATION_DELAY_2; ?>;">
            <?php echo MAIN_PARAGRAPH; ?>
        </p>

        <div class="fade-in" style="animation-delay: <?php echo ANIMATION_DELAY_3; ?>;">
            <p class="text-base md:text-lg mb-2"><?php echo TIME_COUNTER_PREFIX; ?></p>
            <div id="time-counter"></div>
        </div>
    </main>

    <!-- 页脚现在位于 main 元素之外，以确保其定位的稳定性 -->
    <footer class="absolute bottom-10 w-full text-center text-gray-400 text-sm fade-in z-10" style="animation-delay: <?php echo ANIMATION_DELAY_4; ?>;">
        <?php echo FOOTER_TEXT; ?>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- 1. 时间计数器 ---
            const timeCounterElement = document.getElementById('time-counter');
            const separationDate = new Date('<?php echo SEPARATION_DATE; ?>').getTime();

            function updateTimeCounter() {
                const now = new Date().getTime();
                const difference = now - separationDate;

                if (difference < 0) {
                    timeCounterElement.innerHTML = "未来的日子，尚未到来。";
                    return;
                }

                let days = Math.floor(difference / (1000 * 60 * 60 * 24));
                let hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((difference % (1000 * 60)) / 1000);

                // 格式化输出
                timeCounterElement.innerHTML = `
                    <span class="block">${days} 天</span>
                    <span class="block">${hours} 小时 ${minutes} 分钟 ${seconds} 秒</span>
                `;
            }

            // 立即执行一次，然后每秒更新
            updateTimeCounter();
            setInterval(updateTimeCounter, 1000);

            // --- 2. 点击反馈效果 ---
            document.body.addEventListener('click', (e) => {
                // 在body上创建波纹元素
                const ripple = document.createElement('div');
                ripple.className = 'ripple';
                document.body.appendChild(ripple);

                // 设置波纹大小和位置
                const size = 50;
                ripple.style.width = `${size}px`;
                ripple.style.height = `${size}px`;
                ripple.style.left = `${e.clientX - size / 2}px`;
                ripple.style.top = `${e.clientY - size / 2}px`;
                
                // 动画结束后移除元素
                ripple.addEventListener('animationend', () => {
                    ripple.remove();
                });
            });

        });
    </script>
</body>
</html>
