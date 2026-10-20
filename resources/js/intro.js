/*
 * Intro: el nombre de Andrés armado con miles de caracteres de código.
 * Las partículas llegan desde todo el viewport y arman el logo; después se reacomodan en
 * "ANDRÉS PADOVAN" y en la propuesta ("Sistemas que trabajan por vos.") y van rotando hasta
 * que el visitante entra. Se apartan del mouse y explotan al entrar (scroll, clic, tecla, botón o deslizamiento rápido).
 */

const CODE = '01<>/{}[];=+*#$&|~';
const DENSE = '{}[]<>/#$%&@=+*';
const ease = (t) => 1 - Math.pow(1 - t, 3);
const SHAPE_HOLD = 3600;

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
    const shapesText = JSON.parse(root.dataset.shapes);
    const images = [];
    let start = performance.now();
    let particles = [];
    let shapes = [];
    let currentShape = 0;
    let nextSwitch = 0;
    let width = 0;
    let height = 0;
    let fontSize = 10;
    let leavingAt = 0;
    let frame = 0;
    let bounds = { top: 0, height: 1, cx: 0, cy: 0 };
    let shapeChangedAt = 0;

    const brand = (name, fallback) => getComputedStyle(document.documentElement).getPropertyValue(`--color-${name}`).trim() || fallback;
    const colors = { fg: brand('fg', '#f3f1f7'), malbec: brand('malbec', '#d43cff'), sun: brand('sun', '#ffb43e') };

    html.style.overflow = 'hidden';

    /* Escribe las líneas en un canvas aparte y devuelve las celdas de la grilla que caen dentro de las letras. */
    const sampleText = (sourceLines, stepX, stepY) => {
        const isWide = width >= 640;
        // En celulares cada palabra va en su propia línea para que las letras salgan grandes.
        const lines = isWide ? sourceLines : sourceLines.flatMap((line) => line.text.split(' ').map((text) => ({ ...line, text })));
        const maxWidth = width * (isWide ? 0.8 : 0.9);
        const maxHeight = height * (isWide ? 0.56 : 0.5);
        const off = document.createElement('canvas');
        off.width = width;
        off.height = height;
        const octx = off.getContext('2d', { willReadFrequently: true });
        const family = '"Unbounded Variable", "Arial Black", sans-serif';

        let size = maxHeight / (lines.length * 0.98);
        octx.font = `900 ${size}px ${family}`;
        const widest = Math.max(...lines.map((line) => octx.measureText(line.text).width));
        if (widest > maxWidth) size *= maxWidth / widest;
        octx.font = `900 ${size}px ${family}`;
        octx.textAlign = 'center';
        octx.textBaseline = 'middle';

        const lineHeight = size * 0.98;
        const blockTop = height * (isWide ? 0.47 : 0.44) - (lineHeight * lines.length) / 2;
        lines.forEach((line, index) => {
            octx.fillStyle = line.accent ? colors.malbec : colors.fg;
            octx.fillText(line.text, width / 2, blockTop + lineHeight * (index + 0.5));
        });

        return { ...gridTargets(off, stepX, stepY), top: blockTop, height: lineHeight * lines.length, canvas: off };
    };

    /* Dibuja el logo (SVG) centrado y lo muestrea igual que el texto. */
    const sampleImage = (image, stepX, stepY) => {
        const isWide = width >= 640;
        const ratio = image.naturalWidth / image.naturalHeight || 1.8;
        let boxHeight = height * (isWide ? 0.42 : 0.3);
        let boxWidth = boxHeight * ratio;
        if (boxWidth > width * 0.86) {
            boxWidth = width * 0.86;
            boxHeight = boxWidth / ratio;
        }
        const top = height * (isWide ? 0.47 : 0.44) - boxHeight / 2;
        const off = document.createElement('canvas');
        off.width = width;
        off.height = height;
        off.getContext('2d', { willReadFrequently: true }).drawImage(image, (width - boxWidth) / 2, top, boxWidth, boxHeight);

        return { ...gridTargets(off, stepX, stepY), top, height: boxHeight, canvas: off };
    };

    /* Celdas de la grilla que caen dentro de lo dibujado, con el color de cada una (texto, malbec o sol). */
    const gridTargets = (off, stepX, stepY) => {
        const data = off.getContext('2d', { willReadFrequently: true }).getImageData(0, 0, width, height).data;
        const targets = [];
        for (let y = 0; y + stepY < height; y += stepY) {
            for (let x = 0; x + stepX < width; x += stepX) {
                const i = (Math.floor(y + stepY / 2) * width + Math.floor(x + stepX / 2)) * 4;
                if (data[i + 3] < 128) continue;
                const [r, g, b] = [data[i], data[i + 1], data[i + 2]];
                const color = r > 200 && b < 150 && g > 140 ? 'sun' : r > 200 && g < 120 ? 'malbec' : 'fg';
                targets.push({ x, y, color });
            }
        }
        targets.sort((a, b) => a.x + a.y * 0.15 - (b.x + b.y * 0.15));

        return { targets };
    };

    const build = () => {
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width * dpr;
        canvas.height = height * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

        fontSize = width >= 640 ? 9 : 7;
        const stepX = fontSize * 0.6;
        const stepY = fontSize * 0.92;
        shapes = shapesText.map((shape, index) => (shape.image ? sampleImage(images[index], stepX, stepY) : sampleText(shape, stepX, stepY)));

        const count = Math.max(...shapes.map((shape) => shape.targets.length));
        const previous = particles;
        particles = Array.from({ length: count }, (_, index) => {
            const old = previous[index];
            return {
                x: old ? old.x : Math.random() * width,
                y: old ? old.y : Math.random() * height,
                vx: old ? old.vx : (Math.random() - 0.5) * 0.6,
                vy: old ? old.vy : (Math.random() - 0.5) * 0.6,
                glyph: DENSE[Math.floor(Math.random() * DENSE.length)],
                base: null,
                shade: 0.7 + Math.random() * 0.3,
                spark: Math.random() < 0.03,
                delay: old ? 0 : 200 + Math.random() * 1100,
                alpha: 0,
            };
        });
        particles.forEach((p) => (p.base = p.glyph));
        assign(currentShape, false);
    };

    /* Le da a cada partícula su lugar en la forma; las que sobran se apilan sobre lugares ya ocupados. */
    const assign = (shapeIndex, morph) => {
        const { targets, top, height: shapeHeight } = shapes[shapeIndex];
        bounds = { top, height: shapeHeight, cx: width / 2, cy: top + shapeHeight / 2 };
        shapeChangedAt = performance.now();
        particles.forEach((p, index) => {
            const target = index < targets.length ? targets[index] : targets[Math.floor(Math.random() * targets.length)];
            p.tx = target.x;
            p.ty = target.y;
            p.color = target.color;
            p.spare = index >= targets.length;
            if (morph) {
                p.vx += (Math.random() - 0.5) * 10;
                p.vy += (Math.random() - 0.5) * 10;
                p.glyph = CODE[Math.floor(Math.random() * CODE.length)];
            }
        });
    };

    const draw = (now) => {
        const elapsed = now - start;
        const leaving = leavingAt ? Math.min((now - leavingAt) / 1100, 1) : 0;

        if (!leavingAt && shapes.length > 1 && now >= nextSwitch) {
            currentShape = (currentShape + 1) % shapes.length;
            assign(currentShape, true);
            root.dataset.shape = currentShape;
            nextSwitch = now + SHAPE_HOLD;
        }

        ctx.clearRect(0, 0, width, height);

        // Cuerpo tenue de las letras detrás del código, para que se lean de lejos.
        if (shapes[currentShape] && elapsed > 1200) {
            const body = Math.min((now - Math.max(shapeChangedAt, start + 1200)) / 900, 1);
            ctx.globalAlpha = 0.1 * Math.max(body, 0) * (1 - leaving);
            ctx.drawImage(shapes[currentShape].canvas, 0, 0, width, height);
            ctx.globalAlpha = 1;
        }

        ctx.font = `${fontSize}px "JetBrains Mono Variable", ui-monospace, monospace`;
        ctx.textBaseline = 'top';

        // Línea de escaneo que recorre el texto de arriba abajo.
        const scanY = elapsed > 2200 ? bounds.top - 30 + (((elapsed - 2200) / 2600) % 1.3) * (bounds.height + 60) : -9999;

        const buckets = new Map();
        for (const p of particles) {
            if (leavingAt) {
                p.vx *= 0.985;
                p.vy = p.vy * 0.985 + 0.05;
            } else if (elapsed > p.delay) {
                p.vx = (p.vx + (p.tx - p.x) * 0.045) * 0.84;
                p.vy = (p.vy + (p.ty - p.y) * 0.045) * 0.84;
                const dx = p.x - pointer.x;
                const dy = p.y - pointer.y;
                const distance = Math.hypot(dx, dy);
                const reach = lastPointerType === 'touch' ? 150 : 120;
                if (distance < reach && distance > 0.1) {
                    const force = (1 - distance / reach) * 7;
                    p.vx += (dx / distance) * force;
                    p.vy += (dy / distance) * force;
                }
            }
            p.x += p.vx;
            p.y += p.vy;

            const arriving = elapsed > p.delay ? ease(Math.min((elapsed - p.delay) / 900, 1)) : 0;
            const settled = Math.min(Math.hypot(p.tx - p.x, p.ty - p.y) / 40, 1);
            p.alpha = (0.1 + (p.spare ? 0 : p.shade * 0.9) * arriving) * (1 - leaving);

            if (Math.random() < 0.004 + settled * 0.05) p.glyph = CODE[Math.floor(Math.random() * CODE.length)];
            else if (p.glyph !== p.base && Math.random() < 0.06) p.glyph = p.base;

            let color = arriving > 0.5 ? (p.spark && p.color === 'fg' ? 'sun' : p.color) : 'fg';
            const scanDistance = Math.abs(p.y - scanY);
            if (!leavingAt && !p.spare && scanDistance < 12) {
                color = p.color === 'fg' ? 'malbec' : 'fg';
                p.alpha = Math.max(p.alpha, 1 - scanDistance / 12);
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
            const dx = p.x - bounds.cx;
            const dy = p.y - bounds.cy;
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

    // Con el dedo se juega igual que con el mouse; para entrar está el botón o un deslizamiento rápido hacia arriba.
    let lastPointerType = 'mouse';
    const onPointer = (event) => {
        lastPointerType = event.pointerType;
        pointer.x = event.clientX;
        pointer.y = event.clientY;
    };
    const onPointerLeave = () => {
        pointer.x = pointer.y = -9999;
    };
    const onPointerUp = (event) => {
        if (event.pointerType === 'touch') onPointerLeave();
    };
    const onClick = (event) => {
        if (event.target.closest('[data-intro-enter], [data-intro-skip]') || lastPointerType !== 'touch') leave();
    };
    const onKey = (event) => {
        if (['Enter', ' ', 'Escape', 'ArrowDown', 'PageDown'].includes(event.key)) leave();
    };
    let touchStart = null;
    const onTouchStart = (event) => (touchStart = { y: event.touches[0].clientY, at: performance.now() });
    const onTouchEnd = (event) => {
        if (!touchStart) return;
        const flick = touchStart.y - event.changedTouches[0].clientY;
        if (flick > 80 && performance.now() - touchStart.at < 300) leave();
        touchStart = null;
    };
    let resizeTimer;
    const onResize = () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => !leavingAt && build(), 150);
    };

    const listeners = [
        [window, 'wheel', leave, { passive: true }],
        [window, 'keydown', onKey],
        [window, 'resize', onResize],
        [root, 'pointerdown', onPointer],
        [root, 'pointermove', onPointer],
        [root, 'pointerleave', onPointerLeave],
        [root, 'pointerup', onPointerUp],
        [root, 'pointercancel', onPointerUp],
        [root, 'click', onClick],
        [root, 'touchstart', onTouchStart, { passive: true }],
        [root, 'touchend', onTouchEnd, { passive: true }],
    ];
    listeners.forEach(([target, type, handler, options]) => target.addEventListener(type, handler, options));
    const removeListeners = () => listeners.forEach(([target, type, handler]) => target.removeEventListener(type, handler));

    const begin = () => {
        build();
        start = performance.now();
        nextSwitch = start + 2400 + SHAPE_HOLD;
        root.classList.add('is-running');
        setTimeout(() => root.classList.add('is-formed'), 1900);
        typeLog();
        frame = requestAnimationFrame(draw);
    };

    // Las letras se dibujan con Unbounded y el logo es un SVG: esperamos ambos (con tope) antes de muestrear.
    const loadImage = (url, index) =>
        new Promise((resolve) => {
            const image = new Image();
            image.onload = image.onerror = () => resolve((images[index] = image));
            image.src = url;
        });
    const ready = Promise.all([document.fonts.load('900 100px "Unbounded Variable"'), ...shapesText.map((shape, index) => shape.image && loadImage(shape.image, index))]);
    Promise.race([ready, new Promise((resolve) => setTimeout(resolve, 1800))]).then(begin, begin);
}
