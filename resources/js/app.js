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
        const scrollTo = (id, text) => {
            say(text);
            setTimeout(() => document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' }), 350);
        };

        const commands = {
            help: () => say(config.messages.help),
            ayuda: () => say(config.messages.help),
            sobre: () => say(config.messages.about),
            about: () => say(config.messages.about),
            servicios: () => scrollTo('servicios', config.messages.services),
            services: () => scrollTo('servicios', config.messages.services),
            proyectos: () => scrollTo('proyectos', '→ ./proyectos'),
            projects: () => scrollTo('proyectos', '→ ./projects'),
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
