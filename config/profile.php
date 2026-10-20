<?php

/*
| Personal data shown across the public site. Texts that change by language live in lang/{es,en}/site.php;
| projects, services and testimonials are managed from the Filament panel at /admin.
*/

return [
    'name' => env('PROFILE_NAME', 'Andrés Padovan'),
    'short_name' => 'Andrés',
    'handle' => 'andres',
    'location' => 'San Martín, Mendoza, AR',
    'timezone' => 'America/Argentina/Mendoza',
    'years_experience' => 7,
    'gpa' => '8,28',
    'email' => env('PROFILE_EMAIL', 'andrespadovan38@gmail.com'),
    'whatsapp' => env('PROFILE_WHATSAPP', '5492634625076'),
    'whatsapp_display' => '+54 9 263 462-5076',
    'linkedin' => 'https://www.linkedin.com/in/andres-padovan-309a48223',
    'github' => 'https://github.com/Andres-Padovan',
    'github_user' => env('GITHUB_USER', 'Andres-Padovan'),
    'photo' => 'images/andres-padovan.webp',
    'available' => (bool) env('PROFILE_AVAILABLE', true),
    'stack' => ['Laravel', 'PHP', 'Filament', 'Livewire', 'Tailwind', 'JavaScript', 'Vue', 'MySQL', 'PostgreSQL', 'Git', 'Linux', 'APIs REST', 'Docker', 'IA'],
];
