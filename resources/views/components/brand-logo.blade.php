{{-- Logo "Mendoza": la A es una montaña con la cima malbec, el sol y un cursor. El resto toma currentColor. --}}
<svg {{ $attributes->merge(['viewBox' => '0 0 220 120', 'role' => 'img']) }} xmlns="http://www.w3.org/2000/svg" aria-label="Andrés Padovan">
    <circle cx="92" cy="22" r="11" fill="#ffb43e"/>
    <path fill="currentColor" d="M4 108 L58 12 L112 108 H86 L58 58 L30 108 Z"/>
    <path fill="#ff3d7f" d="M58 12 L74 40 L66 36 L58 46 L50 36 L42 40 Z"/>
    <path fill="currentColor" fill-rule="evenodd" d="M124 12 H170 A34 34 0 0 1 170 80 H150 V108 H124 Z M150 36 V56 H168 A10 10 0 0 0 168 36 Z"/>
    <rect x="190" y="94" width="26" height="14" fill="#ff3d7f" class="animate-blink"/>
</svg>
