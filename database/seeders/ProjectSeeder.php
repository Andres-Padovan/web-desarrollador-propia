<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

/**
 * Solo carga proyectos reales. El resto se importa con `php artisan github:sync`
 * o se agrega desde el panel en /admin/projects.
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::updateOrCreate(['slug' => 'web-desarrollador-propia'], [
            'title' => ['es' => 'Portfolio personal', 'en' => 'Personal portfolio'],
            'summary' => [
                'es' => 'Este sitio: terminal interactiva, español e inglés, panel de administración y proyectos sincronizados desde GitHub.',
                'en' => 'This site: interactive terminal, Spanish and English, admin panel and projects synced from GitHub.',
            ],
            'category' => ['es' => 'Sitio web', 'en' => 'Website'],
            'stack' => ['Laravel', 'Filament', 'Livewire', 'Tailwind'],
            'status' => 'live',
            'repo_url' => 'https://github.com/Andres-Padovan/web-desarrollador-propia',
            'accent' => '#ff3d7f',
            'language' => 'PHP',
            'is_private' => true,
            'is_featured' => true,
            'is_visible' => true,
            'sort' => 6,
        ]);

        Project::updateOrCreate(['slug' => 'estudio-brillo'], [
            'title' => ['es' => 'Estudio Brillo', 'en' => 'Estudio Brillo'],
            'summary' => [
                'es' => 'Sitio para un estudio de maquillaje y peinado de San Martín: portfolio de trabajos, paquetes para novias y quinceañeras, y reservas por WhatsApp.',
                'en' => 'Website for a makeup and hairstyling studio in San Martín: work portfolio, bridal and quinceañera packages, and WhatsApp bookings.',
            ],
            'category' => ['es' => 'Sitio web', 'en' => 'Website'],
            'stack' => ['Tailwind', 'Alpine.js', 'WhatsApp'],
            'status' => 'live',
            'live_url' => 'https://estudiobrillo.com',
            'image' => 'images/proyectos/estudio-brillo.webp',
            'video' => 'videos/proyectos/estudio-brillo.mp4',
            'accent' => '#e8a7b8',
            'is_private' => true,
            'is_featured' => true,
            'is_visible' => true,
            'sort' => 1,
        ]);

        Project::updateOrCreate(['slug' => 'padel-mza'], [
            'title' => ['es' => 'PádelMza', 'en' => 'PádelMza'],
            'summary' => [
                'es' => 'App para armar partidos de pádel en Mendoza: partidos por categoría con lista de espera, turnos libres con seña por Mercado Pago, clases, chat, notificaciones y ranking.',
                'en' => 'App to set up padel matches in Mendoza: matches by skill level with a waitlist, open court slots with a Mercado Pago deposit, lessons, chat, push notifications and rankings.',
            ],
            'category' => ['es' => 'App móvil', 'en' => 'Mobile app'],
            'stack' => ['Flutter', 'Supabase', 'Firebase', 'Mercado Pago', 'Laravel'],
            'status' => 'wip',
            'image' => 'images/proyectos/padel-mza.webp',
            'video' => 'videos/proyectos/padel-mza.mp4',
            'accent' => '#c6f432',
            'language' => 'Dart',
            'is_private' => true,
            'is_featured' => true,
            'is_visible' => true,
            'sort' => 2,
        ]);

        Project::updateOrCreate(['slug' => 'toldos-y-velas-pro'], [
            'title' => ['es' => 'Toldos y Velas Pro', 'en' => 'Toldos y Velas Pro'],
            'summary' => [
                'es' => 'Tienda online para un fabricante de velas sombreadoras, cierres de terraza y malla raschel: catálogo, carrito, cotizador y envíos a todo Chile.',
                'en' => 'Online store for a maker of shade sails, terrace enclosures and shade netting: catalog, cart, quote builder and shipping across Chile.',
            ],
            'category' => ['es' => 'E-commerce', 'en' => 'E-commerce'],
            'stack' => ['Laravel', 'Livewire', 'Tailwind'],
            'status' => 'wip',
            'image' => 'images/proyectos/toldos-y-velas-pro.webp',
            'video' => 'videos/proyectos/toldos-y-velas-pro.mp4',
            'accent' => '#2f5fd0',
            'language' => 'PHP',
            'is_private' => true,
            'is_featured' => true,
            'is_visible' => true,
            'sort' => 3,
        ]);

        Project::updateOrCreate(['slug' => 'vanderperr-autos'], [
            'title' => ['es' => 'Vanderperr Autos', 'en' => 'Vanderperr Autos'],
            'summary' => [
                'es' => 'Web para una agencia de compra y venta de autos: buscador por condición, tipo y marca, stock de usados y 0 km, financiación y cotización del usado.',
                'en' => 'Website for a car dealership: search by condition, type and brand, used and new stock, financing and trade-in quotes.',
            ],
            'category' => ['es' => 'Sitio web', 'en' => 'Website'],
            'stack' => ['Laravel', 'Livewire', 'Tailwind'],
            'status' => 'wip',
            'image' => 'images/proyectos/vanderperr-autos.webp',
            'video' => 'videos/proyectos/vanderperr-autos.mp4',
            'accent' => '#1e9be0',
            'language' => 'PHP',
            'is_private' => true,
            'is_featured' => true,
            'is_visible' => true,
            'sort' => 4,
        ]);

        Project::updateOrCreate(['slug' => 'laconsola-servicio-tecnico'], [
            'title' => ['es' => 'La Consola · Servicio técnico', 'en' => 'La Consola · Repair tracking'],
            'summary' => [
                'es' => 'App instalable para el servicio técnico de La Consola: el cliente entra con su orden y DNI o con un link por WhatsApp, sigue el estado de su equipo y acepta o rechaza el presupuesto. Con panel interno para el local.',
                'en' => 'Installable app for La Consola\'s repair shop: customers sign in with their order and ID number or a WhatsApp link, follow their device\'s status and accept or decline the quote. Includes an internal panel for the shop.',
            ],
            'category' => ['es' => 'Sistema web', 'en' => 'Web system'],
            'stack' => ['Laravel', 'PWA', 'MySQL', 'WhatsApp'],
            'status' => 'wip',
            'image' => 'images/proyectos/laconsola-servicio-tecnico.webp',
            'video' => 'videos/proyectos/laconsola-servicio-tecnico.mp4',
            'accent' => '#ff7a1a',
            'language' => 'PHP',
            'is_private' => true,
            'is_featured' => true,
            'is_visible' => true,
            'sort' => 5,
        ]);
    }
}
