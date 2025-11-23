<?php
// 引入配置文件
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#050505">
    <title><?php echo PAGE_TITLE; ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['"Noto Serif SC"', 'serif'],
                        mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'monospace'],
                    },
                    letterSpacing: {
                        'widest-xl': '.25em',
                    },
                    animation: {
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@200;400;700&display=swap" rel="stylesheet">

    <style>
        /* --- 核心基调 --- */
        :root {
            --bg-color: #050505;
            --text-color: #e5e5e5;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Noto Serif SC', serif;
            overflow: hidden;
            margin: 0;
            height: 100dvh;
            perspective: 1000px; /* 开启3D透视 */
            cursor: none; /* 隐藏默认光标，使用自定义光标 */
        }

        /* --- 1. 胶片噪点纹理 (Film Grain) --- */
        .grain-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.07'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 50;
            opacity: 0.4;
            mix-blend-mode: overlay;
        }

        /* --- 2. 电影级模糊进场 --- */
        @keyframes cinematicReveal {
            0% { opacity: 0; filter: blur(15px); transform: scale(0.95) translateY(20px); }
            100% { opacity: 1; filter: blur(0); transform: scale(1) translateY(0); }
        }

        .cinematic-in {
            opacity: 0; /* 初始隐藏 */
            animation: cinematicReveal 2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            will-change: transform, opacity, filter;
        }

        /* --- 3. 3D 容器与交互 --- */
        #tilt-container {
            transform-style: preserve-3d;
            transition: transform 0.1s ease-out; /* 极短的过渡，保证丝滑 */
            will-change: transform;
        }

        /* --- 4. 自定义光标 --- */
        .cursor-dot, .cursor-outline {
            position: fixed;
            top: 0; left: 0;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            z-index: 9999;
            pointer-events: none;
        }
        .cursor-dot {
            width: 8px; height: 8px;
            background-color: white;
        }
        .cursor-outline {
            width: 40px; height: 40px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: width 0.2s, height 0.2s, background-color 0.2s;
        }

        /* --- Canvas 背景层 --- */
        #canvas-bg {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0;
        }

        /* --- 移动端适配 --- */
        @media (hover: none) {
            .cursor-dot, .cursor-outline { display: none; } /* 触摸屏隐藏自定义光标 */
            body { cursor: auto; }
        }
    </style>
</head>
<body>

    <canvas id="canvas-bg"></canvas>
    
    <div class="grain-overlay"></div>

    <div class="cursor-dot" id="cursor-dot"></div>
    <div class="cursor-outline" id="cursor-outline"></div>

    <main class="relative z-10 flex flex-col justify-center items-center h-full w-full px-8 pb-[20vh]" id="main-wrapper">
        <div id="tilt-container" class="flex flex-col items-center text-center max-w-4xl">
            
            <h1 class="text-4xl md:text-6xl font-bold tracking-[0.2em] mb-10 cinematic-in text-transparent bg-clip-text bg-gradient-to-b from-white to-gray-400 drop-shadow-2xl" 
                style="animation-delay: <?php echo ANIMATION_DELAY_1; ?>;">
                <?php echo MAIN_TITLE; ?>
            </h1>
            
            <div class="relative">
                <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 w-px h-12 bg-gradient-to-b from-transparent via-gray-500 to-transparent opacity-50 cinematic-in" style="animation-delay: 1.8s"></div>
                
                <p class="text-lg md:text-2xl leading-loose mb-16 cinematic-in text-gray-300 font-extralight tracking-wide mix-blend-difference" 
                   style="animation-delay: <?php echo ANIMATION_DELAY_2; ?>;">
                    <?php echo MAIN_PARAGRAPH; ?>
                </p>
            </div>

            <div class="cinematic-in backdrop-blur-sm bg-white/5 px-8 py-6 rounded-2xl border border-white/10 shadow-[0_0_50px_-12px_rgba(255,255,255,0.1)]" 
                 style="animation-delay: <?php echo ANIMATION_DELAY_3; ?>;">
                <p class="text-xs md:text-sm text-gray-400 mb-4 tracking-[0.3em] uppercase opacity-60">
                    <?php echo TIME_COUNTER_PREFIX; ?>
                </p>
                <div id="time-counter" class="font-variant-numeric-tabular font-light"></div>
            </div>

        </div>
    </main>

    <footer class="fixed bottom-8 w-full text-center z-20 cinematic-in" style="animation-delay: <?php echo ANIMATION_DELAY_4; ?>;">
        <span class="text-[10px] md:text-xs text-gray-600 tracking-[0.2em] uppercase border-t border-gray-800 pt-4 px-4 inline-block hover:text-gray-400 transition-colors duration-500">
            <?php echo FOOTER_TEXT; ?>
        </span>
    </footer>

    <script>
        /**
         * 1. 高级时间计数器逻辑
         * 使用 requestAnimationFrame 保证渲染与屏幕刷新率同步
         */
        class TimeCounter {
            constructor(targetId, dateString) {
                this.el = document.getElementById(targetId);
                // 兼容性处理 Safari 的日期格式
                this.targetDate = new Date(dateString.replace(/-/g, "/")).getTime();
                this.start();
            }

            pad(num) {
                return num.toString().padStart(2, '0');
            }

            update() {
                const now = new Date().getTime();
                const diff = now - this.targetDate;

                if (diff < 0) {
                    this.el.innerHTML = "<span class='text-gray-500 italic'>Waiting for destiny...</span>";
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                const ms = Math.floor((diff % 1000) / 10); // 毫秒显示，增加紧张感

                // 渲染 HTML，区分样式层级
                this.el.innerHTML = `
                    <div class="flex flex-col md:flex-row items-baseline justify-center gap-2 md:gap-6">
                        <span class="text-3xl md:text-4xl font-serif text-white drop-shadow-md">
                            ${days}<span class="text-sm text-gray-500 ml-2 font-sans font-light tracking-normal">DAYS</span>
                        </span>
                        <span class="font-mono text-xl md:text-2xl text-gray-300 tracking-widest">
                            ${this.pad(hours)}<span class="animate-pulse text-gray-600 mx-1">:</span>${this.pad(minutes)}<span class="animate-pulse text-gray-600 mx-1">:</span>${this.pad(seconds)}
                        </span>
                    </div>
                `;

                requestAnimationFrame(() => this.update());
            }

            start() {
                this.update();
            }
        }

        /**
         * 2. Canvas 粒子星尘系统 (Dust Particles)
         * 模拟漂浮在黑暗空间中的微尘
         */
        class ParticleSystem {
            constructor() {
                this.canvas = document.getElementById('canvas-bg');
                this.ctx = this.canvas.getContext('2d');
                this.particles = [];
                this.mouseX = 0;
                this.mouseY = 0;
                this.resize();
                this.initParticles();
                this.animate();
                
                window.addEventListener('resize', () => this.resize());
                window.addEventListener('mousemove', (e) => {
                    this.mouseX = e.clientX;
                    this.mouseY = e.clientY;
                });
            }

            resize() {
                this.canvas.width = window.innerWidth;
                this.canvas.height = window.innerHeight;
                this.initParticles(); // 重置粒子以适应新屏幕
            }

            initParticles() {
                this.particles = [];
                // 粒子数量根据屏幕面积动态计算
                const count = (this.canvas.width * this.canvas.height) / 15000;
                for (let i = 0; i < count; i++) {
                    this.particles.push({
                        x: Math.random() * this.canvas.width,
                        y: Math.random() * this.canvas.height,
                        size: Math.random() * 2, // 粒子大小
                        speedX: (Math.random() - 0.5) * 0.2, // 极慢的水平漂浮
                        speedY: (Math.random() - 0.5) * 0.2, // 极慢的垂直漂浮
                        opacity: Math.random() * 0.5,
                        baseOpacity: Math.random() * 0.5
                    });
                }
            }

            animate() {
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                
                // 创建径向渐变跟随鼠标 (手电筒效果)
                const gradient = this.ctx.createRadialGradient(
                    this.mouseX, this.mouseY, 0, 
                    this.mouseX, this.mouseY, 400
                );
                gradient.addColorStop(0, 'rgba(30, 30, 30, 1)'); // 中心稍微照亮
                gradient.addColorStop(1, 'rgba(5, 5, 5, 0)');
                
                // 填充背景基调
                this.ctx.fillStyle = '#050505';
                this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
                
                // 绘制光照层
                this.ctx.globalCompositeOperation = 'lighter';
                this.ctx.fillStyle = gradient;
                this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);

                // 绘制粒子
                this.particles.forEach(p => {
                    p.x += p.speedX;
                    p.y += p.speedY;

                    // 边界循环
                    if (p.x < 0) p.x = this.canvas.width;
                    if (p.x > this.canvas.width) p.x = 0;
                    if (p.y < 0) p.y = this.canvas.height;
                    if (p.y > this.canvas.height) p.y = 0;

                    // 计算与鼠标的距离，靠近时变亮
                    const dx = this.mouseX - p.x;
                    const dy = this.mouseY - p.y;
                    const dist = Math.sqrt(dx*dx + dy*dy);
                    const hoverEffect = Math.max(0, (300 - dist) / 300);

                    this.ctx.beginPath();
                    this.ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                    this.ctx.fillStyle = `rgba(255, 255, 255, ${p.baseOpacity + hoverEffect * 0.3})`;
                    this.ctx.fill();
                });

                this.ctx.globalCompositeOperation = 'source-over';
                requestAnimationFrame(() => this.animate());
            }
        }

        /**
         * 3. 3D 视差与光标系统
         */
        class InteractionManager {
            constructor() {
                this.tiltContainer = document.getElementById('tilt-container');
                this.cursorDot = document.getElementById('cursor-dot');
                this.cursorOutline = document.getElementById('cursor-outline');
                
                this.setupTilt();
                this.setupCursor();
            }

            setupTilt() {
                document.addEventListener('mousemove', (e) => {
                    const x = (window.innerWidth / 2 - e.clientX) / 30; // 分母越大移动幅度越小
                    const y = (window.innerHeight / 2 - e.clientY) / 30;
                    
                    // 应用旋转，制造3D景深感
                    this.tiltContainer.style.transform = `rotateY(${x}deg) rotateX(${-y}deg)`;
                });
            }

            setupCursor() {
                // 鼠标移动跟随
                document.addEventListener('mousemove', (e) => {
                    const posX = e.clientX;
                    const posY = e.clientY;

                    // 点是实时的
                    this.cursorDot.style.left = `${posX}px`;
                    this.cursorDot.style.top = `${posY}px`;

                    // 圆圈带一点延迟（使用 animate 看起来更平滑，这里简单用 transition）
                    this.cursorOutline.style.left = `${posX}px`;
                    this.cursorOutline.style.top = `${posY}px`;
                });

                // 点击动效
                document.addEventListener('mousedown', () => {
                    this.cursorOutline.style.transform = 'translate(-50%, -50%) scale(0.5)';
                });
                document.addEventListener('mouseup', () => {
                    this.cursorOutline.style.transform = 'translate(-50%, -50%) scale(1)';
                });
            }
        }

        // --- 初始化执行 ---
        document.addEventListener('DOMContentLoaded', () => {
            new TimeCounter('time-counter', '<?php echo SEPARATION_DATE; ?>');
            new ParticleSystem();
            
            // 仅在非触屏设备启用高级交互
            if (window.matchMedia("(hover: hover)").matches) {
                new InteractionManager();
            }
        });
    </script>
</body>
</html>
