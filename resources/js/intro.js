/*
 * Intro: el retrato de Andrés armado con miles de caracteres de código.
 * Las partículas llegan desde todo el viewport, se acomodan según el brillo de la foto,
 * se apartan del mouse y explotan al entrar (scroll, clic, tecla o swipe).
 */

const GLYPHS = '.:-=+<>/(){}[]*#$%&@';
const CODE = '01<>/{}[];=+*#$&|~';
const ease = (t) => 1 - Math.pow(1 - t, 3);

export function startIntro() {
    const root = document.querySelector('[data-intro]');
    const html = document.documentElement;
    if (!root || !html.classList.contains('has-intro')) {
        root?.remove();
        return;
    }
    window.__introStarted = true;

    const canvas = root.querySelector('[data-intro-canvas]');
    const ctx = canvas.getContext('2d');
    const dpr = Math.min(window.devicePixelRatio || 1, 2);
    const pointer = { x: -9999, y: -9999 };
    const start = performance.now();
    let particles = [];
    let width = 0;
    let height = 0;
    let fontSize = 10;
    let leavingAt = 0;
    let frame = 0;
    let center = { x: 0, y: 0 };
    let box = { top: 0, height: 1 };

    html.style.overflow = 'hidden';

    const portrait = new Image();
    portrait.src = root.dataset.portrait;

    const sample = () => {
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width * dpr;
        canvas.height = height * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

        const isWide = width >= 640;
        const ratio = portrait.naturalWidth / portrait.naturalHeight;
        let boxHeight = isWide ? height * 0.9 : Math.min(height * 0.6, (width * 0.94) / ratio);
        let boxWidth = boxHeight * ratio;
        if (isWide && boxWidth > width * 0.5) {
            boxWidth = width * 0.5;
            boxHeight = boxWidth / ratio;
        }
        const left = isWide ? Math.max(width * 0.3 - boxWidth / 2, 24) : (width - boxWidth) / 2;
        const top = isWide ? height - boxHeight : height * 0.07;
        center = { x: left + boxWidth / 2, y: top + boxHeight * 0.4 };
        box = { top, height: boxHeight };

        fontSize = isWide ? Math.max(9, Math.round(boxHeight / 82)) : 8;
        const stepX = fontSize * 0.62;
        const stepY = fontSize * 0.98;
        const cols = Math.floor(boxWidth / stepX);
        const rows = Math.floor(boxHeight / stepY);

        const off = document.createElement('canvas');
        off.width = cols;
        off.height = rows;
        const octx = off.getContext('2d', { willReadFrequently: true });
        octx.drawImage(portrait, 0, 0, cols, rows);
        const data = octx.getImageData(0, 0, cols, rows).data;

        const targets = [];
        for (let row = 0; row < rows; row++) {
            for (let col = 0; col < cols; col++) {
                const i = (row * cols + col) * 4;
                const alpha = data[i + 3] / 255;
                if (alpha < 0.45) continue;
                const brightness = data[i] / 255;
                targets.push({ x: left + col * stepX, y: top + row * stepY, b: brightness });
            }
        }

        const previous = particles;
        particles = targets.map((target, index) => {
            const old = previous[index];
            const glyph = GLYPHS[Math.min(GLYPHS.length - 1, Math.floor(target.b * GLYPHS.length))];
            return {
                x: old ? old.x : Math.random() * width,
                y: old ? old.y : Math.random() * height,
                vx: old ? old.vx : (Math.random() - 0.5) * 0.6,
                vy: old ? old.vy : (Math.random() - 0.5) * 0.6,
                tx: target.x,
                ty: target.y,
                b: target.b,
                glyph,
                base: glyph,
                accent: Math.random() < 0.05 ? (Math.random() < 0.75 ? 'malbec' : 'sun') : null,
                delay: old ? 0 : 250 + (target.y / height) * 900 + Math.random() * 600,
                alpha: 0,
            };
        });
    };

    const colors = { fg: '#f3f1f7', malbec: '#ff3d7f', sun: '#ffb43e' };

    const draw = (now) => {
        const elapsed = now - start;
        const leaving = leavingAt ? Math.min((now - leavingAt) / 1100, 1) : 0;
        ctx.clearRect(0, 0, width, height);
        ctx.font = `${fontSize}px "JetBrains Mono Variable", ui-monospace, monospace`;
        ctx.textBaseline = 'top';

        // Línea de escaneo malbec que recorre el retrato una vez formado.
        const scanY = elapsed > 2400 ? box.top + (((elapsed - 2400) / 3200) % 1.35) * box.height : -9999;

        const buckets = new Map();
        for (const p of particles) {
            if (leavingAt) {
                p.vx *= 0.985;
                p.vy = p.vy * 0.985 + 0.05;
            } else if (elapsed > p.delay) {
                p.vx = (p.vx + (p.tx - p.x) * 0.05) * 0.83;
                p.vy = (p.vy + (p.ty - p.y) * 0.05) * 0.83;
                const dx = p.x - pointer.x;
                const dy = p.y - pointer.y;
                const distance = Math.hypot(dx, dy);
                if (distance < 110 && distance > 0.1) {
                    const force = (1 - distance / 110) * 7;
                    p.vx += (dx / distance) * force;
                    p.vy += (dy / distance) * force;
                }
            }
            p.x += p.vx;
            p.y += p.vy;

            const arriving = elapsed > p.delay ? ease(Math.min((elapsed - p.delay) / 900, 1)) : 0;
            p.alpha = (0.1 + (0.12 + 0.88 * Math.pow(p.b, 1.15) - 0.1) * arriving) * (1 - leaving);

            if (Math.random() < 0.004) p.glyph = CODE[Math.floor(Math.random() * CODE.length)];
            else if (p.glyph !== p.base && Math.random() < 0.08) p.glyph = p.base;

            let color = p.accent && arriving > 0.5 ? p.accent : 'fg';
            const scanDistance = Math.abs(p.y - scanY);
            if (!leavingAt && scanDistance < 14) {
                color = 'malbec';
                p.alpha = Math.max(p.alpha, 1 - scanDistance / 14);
            }
            const level = Math.round(p.alpha * 10);
            const key = `${color}:${level}`;
            if (!buckets.has(key)) buckets.set(key, []);
            buckets.get(key).push(p);
        }

        for (const [key, list] of buckets) {
            const [color, level] = key.split(':');
            if (level === '0') continue;
            ctx.fillStyle = colors[color];
            ctx.globalAlpha = Number(level) / 10;
            for (const p of list) ctx.fillText(p.glyph, p.x, p.y);
        }
        ctx.globalAlpha = 1;

        if (leaving >= 1) return finish();
        frame = requestAnimationFrame(draw);
    };

    const typeLog = async () => {
        const log = root.querySelector('[data-intro-log]');
        const lines = JSON.parse(log.dataset.lines);
        for (const line of [...lines, log.dataset.ready]) {
            const row = document.createElement('div');
            log.append(row);
            for (const char of line) {
                if (leavingAt) return;
                row.textContent += char;
                await new Promise((resolve) => setTimeout(resolve, 22));
            }
            await new Promise((resolve) => setTimeout(resolve, 160));
        }
        log.lastChild?.classList.add('text-leaf');
    };

    const leave = () => {
        if (leavingAt) return;
        leavingAt = performance.now();
        root.classList.add('is-leaving');
        for (const p of particles) {
            const dx = p.x - center.x;
            const dy = p.y - center.y;
            const distance = Math.hypot(dx, dy) || 1;
            const speed = 6 + Math.random() * 26;
            p.vx = (dx / distance) * speed + (Math.random() - 0.5) * 6;
            p.vy = (dy / distance) * speed + (Math.random() - 0.5) * 6;
        }
    };

    function finish() {
        cancelAnimationFrame(frame);
        removeListeners();
        try {
            sessionStorage.setItem('intro-seen', '1');
        } catch {}
        html.style.overflow = '';
        html.classList.remove('has-intro');
        window.scrollTo(0, 0);
        root.remove();
        window.dispatchEvent(new CustomEvent('intro:done'));
    }

    const onPointer = (event) => {
        pointer.x = event.clientX;
        pointer.y = event.clientY;
    };
    const onPointerLeave = () => {
        pointer.x = pointer.y = -9999;
    };
    const onKey = (event) => {
        if (['Enter', ' ', 'Escape', 'ArrowDown', 'PageDown'].includes(event.key)) leave();
    };
    let touchStartY = null;
    const onTouchStart = (event) => (touchStartY = event.touches[0].clientY);
    const onTouchMove = (event) => {
        if (touchStartY !== null && Math.abs(event.touches[0].clientY - touchStartY) > 30) leave();
    };
    let resizeTimer;
    const onResize = () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => !leavingAt && sample(), 150);
    };

    const listeners = [
        [window, 'wheel', leave, { passive: true }],
        [window, 'keydown', onKey],
        [window, 'resize', onResize],
        [root, 'pointermove', onPointer],
        [root, 'pointerleave', onPointerLeave],
        [root, 'click', leave],
        [root, 'touchstart', onTouchStart, { passive: true }],
        [root, 'touchmove', onTouchMove, { passive: true }],
    ];
    listeners.forEach(([target, type, handler, options]) => target.addEventListener(type, handler, options));
    const removeListeners = () => listeners.forEach(([target, type, handler]) => target.removeEventListener(type, handler));

    const begin = () => {
        sample();
        root.classList.add('is-running');
        setTimeout(() => root.classList.add('is-formed'), 1900);
        typeLog();
        frame = requestAnimationFrame(draw);
    };

    portrait.complete && portrait.naturalWidth ? begin() : portrait.addEventListener('load', begin, { once: true });
    portrait.addEventListener('error', finish, { once: true });
}
