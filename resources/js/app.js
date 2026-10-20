import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import { startIntro } from './intro';

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));
/* Se resuelve cuando termina la intro (o enseguida si no hay intro en esta visita). */
const introDone = new Promise((resolve) => {
    if (!document.documentElement.classList.contains('has-intro')) return resolve();
    window.addEventListener('intro:done', resolve, { once: true });
});

document.documentElement.classList.add('js');

/*
 * Terminal del hero: escribe una secuencia de comandos y después queda abierta
 * para que el visitante escriba los suyos (help, proyectos, whatsapp, ...).
 */
Alpine.data('terminal', (config) => ({
    lines: [],
    typing: '',
    input: '',
    ready: false,
    history: [],
    historyIndex: -1,

    async init() {
        if (prefersReducedMotion) {
            config.sequence.forEach((step) => this.print(step.cmd, step.out));
            this.ready = true;
            return;
        }

        await introDone;
        await sleep(500);
        for (const step of config.sequence) {
            for (const char of step.cmd) {
                this.typing += char;
                await sleep(38 + Math.random() * 55);
            }
            await sleep(260);
            this.typing = '';
            this.print(step.cmd, []);
            for (const out of step.out) {
                await sleep(140);
                this.lines.push({ type: 'out', text: out });
            }
            await sleep(420);
        }
        this.lines.push({ type: 'hint', text: config.hint });
        this.ready = true;
    },

    print(cmd, out) {
        this.lines.push({ type: 'cmd', text: cmd });
        out.forEach((text) => this.lines.push({ type: 'out', text }));
    },

    focusInput() {
        this.$refs.input?.focus({ preventScroll: true });
    },

    recall(direction) {
        if (!this.history.length) return;
        this.historyIndex = Math.min(Math.max(this.historyIndex + direction, 0), this.history.length - 1);
        this.input = this.history[this.history.length - 1 - this.historyIndex] ?? '';
    },

    run() {
        const raw = this.input.trim();
        this.input = '';
        this.historyIndex = -1;
        if (!raw) return;

        this.history.push(raw);
        this.lines.push({ type: 'cmd', text: raw });

        const command = raw.toLowerCase().replace(/^sudo\s+/, 'sudo ');
        const say = (text) => this.lines.push({ type: 'out', text });
        const go = (url, label) => {
            say(`${config.messages.opening} ${label}…`);
            setTimeout(() => window.open(url, '_blank', 'noopener'), 450);
        };
        // Si la sección está en esta página baja hasta ella; si no, abre su página.
        const scrollTo = (id, text, page) => {
            say(text);
            setTimeout(() => {
                const section = document.getElementById(id);
                if (section) section.scrollIntoView({ behavior: 'smooth' });
                else if (page) window.location.href = config.links[page];
            }, 350);
        };

        const commands = {
            help: () => say(config.messages.help),
            ayuda: () => say(config.messages.help),
            sobre: () => say(config.messages.about),
            about: () => say(config.messages.about),
            servicios: () => scrollTo('servicios', config.messages.services, 'services'),
            services: () => scrollTo('servicios', config.messages.services, 'services'),
            proyectos: () => scrollTo('proyectos', '→ ./proyectos', 'projects'),
            projects: () => scrollTo('proyectos', '→ ./projects', 'projects'),
            proceso: () => scrollTo('proceso', '→ ./proceso', 'process'),
            process: () => scrollTo('proceso', '→ ./process', 'process'),
            contacto: () => scrollTo('contacto', config.messages.contact),
            contact: () => scrollTo('contacto', config.messages.contact),
            whatsapp: () => go(config.links.whatsapp, 'WhatsApp'),
            github: () => go(config.links.github, 'GitHub'),
            linkedin: () => go(config.links.linkedin, 'LinkedIn'),
            cv: () => go(config.links.linkedin, 'LinkedIn'),
            en: () => (window.location.href = config.links.en),
            es: () => (window.location.href = config.links.es),
            whoami: () => say(config.sequence[0].out[0]),
            ls: () => say('servicios/  proyectos/  proceso/  sobre-mi/  contacto/'),
            date: () => say(new Date().toLocaleString(document.documentElement.lang, { timeZone: config.timezone })),
            clear: () => (this.lines = []),
            'sudo contratar': () => say(config.messages.hire),
            'sudo hire': () => say(config.messages.hire),
        };

        (commands[command] ?? (() => say(`${config.messages.notFound} ${raw}`)))();
        this.$nextTick(() => (this.$refs.screen.scrollTop = this.$refs.screen.scrollHeight));
    },
}));

/* Texto que se "decodifica" desde caracteres aleatorios hasta la frase final. */
Alpine.data('scramble', (text) => ({
    output: text,
    init() {
        if (prefersReducedMotion) return;
        const glyphs = '01<>/{}[]#$%&*+=_';
        let frame = 0;
        const total = 28;
        const tick = () => {
            frame++;
            this.output = text
                .split('')
                .map((char, index) => {
                    if (char === ' ' || frame / total > index / text.length) return char;
                    return glyphs[Math.floor(Math.random() * glyphs.length)];
                })
                .join('');
            if (frame <= total) requestAnimationFrame(() => setTimeout(tick, 28));
            else this.output = text;
        };
        tick();
    },
}));

/* Número que cuenta hasta su valor final cuando entra en pantalla. */
Alpine.data('counter', (target, decimals = 0) => ({
    display: prefersReducedMotion ? target : 0,
    init() {
        if (prefersReducedMotion) return;
        const observer = new IntersectionObserver(
            ([entry]) => {
                if (!entry.isIntersecting) return;
                observer.disconnect();
                const start = performance.now();
                const duration = 1600;
                const step = (now) => {
                    const progress = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 4);
                    this.display = target * eased;
                    if (progress < 1) requestAnimationFrame(step);
                    else this.display = target;
                };
                requestAnimationFrame(step);
            },
            { threshold: 0.6 },
        );
        observer.observe(this.$el);
    },
    get formatted() {
        return Number(this.display).toLocaleString(document.documentElement.lang, {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals,
        });
    },
}));

/* Reloj con la hora de Mendoza para el panel de estado. */
Alpine.data('clock', (timezone) => ({
    time: '',
    init() {
        const update = () => {
            this.time = new Date().toLocaleTimeString('es-AR', { timeZone: timezone, hour: '2-digit', minute: '2-digit', second: '2-digit', hourCycle: 'h23' });
        };
        update();
        setInterval(update, 1000);
    },
}));

/*
 * Video de recorrido en las tarjetas de proyectos: se reproduce al pasar el mouse. En pantallas
 * táctiles (sin hover) arranca solo cuando la tarjeta está bien visible.
 */
Alpine.data('projectVideo', () => ({
    playing: false,
    init() {
        if (prefersReducedMotion || window.matchMedia('(hover: hover)').matches) return;
        new IntersectionObserver(([entry]) => (entry.isIntersecting ? this.play() : this.pause()), { threshold: 0.6 }).observe(this.$el);
    },
    play() {
        if (prefersReducedMotion) return;
        this.$refs.video.play().then(() => (this.playing = true)).catch(() => {});
    },
    pause() {
        this.$refs.video.pause();
        this.playing = false;
    },
}));

Alpine.data('copy', (value) => ({
    copied: false,
    async copy() {
        try {
            await navigator.clipboard.writeText(value);
            this.copied = true;
            setTimeout(() => (this.copied = false), 1800);
        } catch {
            window.getSelection()?.selectAllChildren(this.$refs.value);
        }
    },
}));

/* Apariciones al hacer scroll: [data-reveal] (con data-delay opcional en ms). */
const revealObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            revealObserver.unobserve(entry.target);
        });
    },
    { threshold: 0.15, rootMargin: '0px 0px -8% 0px' },
);

introDone.then(() =>
    document.querySelectorAll('[data-reveal]').forEach((element) => {
        if (element.dataset.delay) element.style.setProperty('--delay', element.dataset.delay);
        revealObserver.observe(element);
    }),
);

/* Reflector que sigue al puntero sobre tarjetas .spotlight */
document.addEventListener('pointermove', (event) => {
    const card = event.target.closest?.('.spotlight');
    if (!card) return;
    const rect = card.getBoundingClientRect();
    card.style.setProperty('--mx', `${event.clientX - rect.left}px`);
    card.style.setProperty('--my', `${event.clientY - rect.top}px`);
});

/* Efectos ligados al scroll: barra de progreso, manifiesto palabra por palabra y línea del proceso. */
const progressBar = document.querySelector('[data-scroll-progress]');
const manifesto = document.querySelector('[data-manifesto]');
const manifestoWords = manifesto ? [...manifesto.querySelectorAll('.word, .highlight-sweep')] : [];
const processTrack = document.querySelector('[data-process-track]');
const processLine = processTrack?.querySelector('[data-process-line]');

const showreel = document.querySelector('[data-showreel]');
const showreelParts = showreel && {
    heading: showreel.querySelector('.showreel-heading'),
    laptop: showreel.querySelector('.showreel-laptop'),
    lines: [...showreel.querySelectorAll('.code-line')].map((line) => ({ line, length: Number(line.dataset.length) })),
    steps: [...showreel.querySelectorAll('[data-step]')],
    sites: [...showreel.querySelectorAll('[data-site]')].map((site) => ({ site, image: site.querySelector('img') })),
};
const clamp = (value) => Math.min(Math.max(value, 0), 1);
const range = (value, from, to) => clamp((value - from) / (to - from));
const smooth = (t) => t * t * (3 - 2 * t);

/*
 * Línea de tiempo de la escena (0 → 1 a lo largo de la sección):
 * la laptop se abre, se escribe el código y la pantalla pasa por los sitios reales.
 */
const renderShowreel = () => {
    const rect = showreel.getBoundingClientRect();
    const progress = prefersReducedMotion ? 0.47 : clamp(-rect.top / (rect.height - window.innerHeight));
    const { heading, laptop, lines, steps, sites } = showreelParts;

    const open = smooth(range(progress, 0, 0.16));
    const zoom = smooth(range(progress, 0.9, 1));
    // En pantallas anchas la laptop se corre hacia el lado contrario del paso activo.
    const shift = window.innerWidth >= 1024 ? smooth(range(progress, 0.12, 0.17)) * (1 - 2 * smooth(range(progress, 0.38, 0.42)) + 2 * smooth(range(progress, 0.66, 0.7))) * 11 : 0;
    laptop.style.transform = `translate3d(${shift}vw, ${(1 - open) * 14}vh, 0) rotateX(${(1 - open) * 68}deg) scale(${0.72 + open * 0.28 + zoom * 0.08})`;
    heading.style.opacity = 1 - range(progress, 0.05, 0.14);
    heading.style.transform = `translateY(${-range(progress, 0.05, 0.14) * 40}px)`;

    const total = lines.reduce((sum, { length }) => sum + length, 0);
    let typed = Math.floor(range(progress, 0.15, 0.38) * total);
    let caretPlaced = false;
    lines.forEach(({ line, length }) => {
        const chars = Math.min(typed, length);
        typed -= chars;
        line.style.setProperty('--chars', chars);
        const typing = !caretPlaced && chars < length;
        line.classList.toggle('is-typing', typing && progress > 0.15);
        if (typing) caretPlaced = true;
    });

    const windows = [
        [0.12, 0.4],
        [0.4, 0.68],
        [0.68, 1.01],
    ];
    steps.forEach((step, index) => {
        const [from, to] = windows[index];
        const visible = range(progress, from, from + 0.04) * (1 - range(progress, to - 0.04, to));
        const side = index % 2 ? 1 : -1;
        step.style.opacity = visible;
        step.style.transform = `translate3d(${(1 - visible) * side * 60}px, ${window.innerWidth >= 1024 ? '-50%' : '0'}, 0)`;
    });

    const siteWindows = [
        [0.4, 0.58],
        [0.58, 0.74],
        [0.74, 0.96],
    ];
    sites.forEach(({ site, image }, index) => {
        const [from, to] = siteWindows[index];
        site.style.opacity = range(progress, from, from + 0.035);
        site.style.clipPath = `inset(${(1 - smooth(range(progress, from, from + 0.04))) * 100}% 0 0 0)`;
        const overflow = image.offsetHeight - site.offsetHeight;
        if (overflow > 0) image.style.transform = `translateY(${-smooth(range(progress, from + 0.03, to)) * overflow}px)`;
    });
};

const sectionProgress = (element, start = 0.85, end = 0.35) => {
    const rect = element.getBoundingClientRect();
    const viewport = window.innerHeight;
    const from = viewport * start;
    const to = viewport * end - rect.height;
    return Math.min(Math.max((from - rect.top) / (from - to), 0), 1);
};

let ticking = false;
const onScroll = () => {
    ticking = false;
    const scrollable = document.documentElement.scrollHeight - window.innerHeight;
    if (progressBar) progressBar.style.transform = `scaleX(${scrollable > 0 ? window.scrollY / scrollable : 0})`;

    if (manifesto) {
        const lit = prefersReducedMotion ? manifestoWords.length : Math.round(sectionProgress(manifesto, 0.9, 0.55) * manifestoWords.length);
        manifestoWords.forEach((word, index) => word.classList.toggle('is-lit', index < lit));
    }

    if (showreel) renderShowreel();

    if (processLine) {
        processLine.style.transform = `scaleY(${prefersReducedMotion ? 1 : sectionProgress(processTrack, 0.75, 0.5)})`;
    }
};

window.addEventListener(
    'scroll',
    () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(onScroll);
    },
    { passive: true },
);
onScroll();

Livewire.start();
startIntro();
