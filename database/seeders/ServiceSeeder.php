<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /** Services from the first version of the site, replaced by the packages below. */
    private const RETIRED = ['Desarrollo web a medida', 'Análisis de sistemas', 'Sistemas de gestión', 'Integraciones y automatización'];

    public function run(): void
    {
        Service::query()->whereIn('title->es', self::RETIRED)->delete();

        foreach ($this->packages() as $sort => $package) {
            Service::updateOrCreate(['title->es' => $package['title']['es']], [...$package, 'sort' => $sort]);
        }
    }

    /** @return array<int, array<string, mixed>> */
    private function packages(): array
    {
        return [
            [
                'group' => 'sites',
                'icon' => 'cursor-arrow-rays',
                'title' => ['es' => 'Landing page', 'en' => 'Landing page'],
                'description' => [
                    'es' => 'Una sola página, un solo objetivo: que quien la visite te escriba o te compre.',
                    'en' => 'One page, one goal: getting visitors to message you or buy.',
                ],
                'details' => [
                    'es' => 'Ideal para lanzar un servicio, una campaña de anuncios o validar una idea rápido. Escribo el texto con vos, pensando en las dudas reales de tus clientes, y la dejo medida para saber cuántas consultas trae.',
                    'en' => 'Ideal for launching a service, running an ad campaign or testing an idea quickly. We write the copy together around your customers\' real questions, and I set up tracking so you know how many leads it brings.',
                ],
                'includes' => [
                    'es' => ['Diseño a medida, pensado primero para el celular', 'Textos orientados a que te contacten', 'Botón de WhatsApp con mensaje armado y formulario', 'SEO básico, Google Analytics y velocidad optimizada', 'Publicación con tu dominio y certificado SSL'],
                    'en' => ['Custom design, mobile first', 'Copy written to get people in touch', 'WhatsApp button with a prefilled message, plus a form', 'Basic SEO, Google Analytics and fast loading', 'Launch on your domain with SSL'],
                ],
                'delivery' => ['es' => '1 a 2 semanas', 'en' => '1 to 2 weeks'],
                'stack' => ['Laravel', 'Tailwind', 'Analytics'],
            ],
            [
                'group' => 'sites',
                'icon' => 'globe-alt',
                'is_highlighted' => true,
                'title' => ['es' => 'Sitio web profesional', 'en' => 'Business website'],
                'description' => [
                    'es' => 'Tu negocio completo en internet, con un panel para que cambies textos, fotos y precios sin depender de nadie.',
                    'en' => 'Your whole business online, with an admin panel to change text, photos and prices on your own.',
                ],
                'details' => [
                    'es' => 'Para empresas, profesionales y comercios que necesitan mostrar servicios, trabajos y datos de contacto con una imagen seria. Queda listo para crecer: blog, segundo idioma o una sección de clientes se agregan sin rehacer nada.',
                    'en' => 'For companies, professionals and shops that need to present their services, work and contact details with a serious image. It is ready to grow: a blog, a second language or a client area can be added without starting over.',
                ],
                'includes' => [
                    'es' => ['Hasta 6 secciones diseñadas a medida', 'Panel de administración propio', 'Blog o novedades para posicionar en Google', 'SEO técnico, mapa del sitio y datos estructurados', 'Versión en inglés opcional', 'Capacitación para que lo manejes vos'],
                    'en' => ['Up to 6 custom-designed sections', 'Your own admin panel', 'Blog or news section to rank on Google', 'Technical SEO, sitemap and structured data', 'Optional second language', 'Training so you can run it yourself'],
                ],
                'delivery' => ['es' => '3 a 4 semanas', 'en' => '3 to 4 weeks'],
                'stack' => ['Laravel', 'Filament', 'Tailwind'],
            ],
            [
                'group' => 'sites',
                'icon' => 'shopping-bag',
                'title' => ['es' => 'Tienda online', 'en' => 'Online store'],
                'description' => [
                    'es' => 'Vendé las 24 horas con cobro por Mercado Pago, control de stock y pedidos en un solo lugar.',
                    'en' => 'Sell around the clock with online payments, stock control and orders in one place.',
                ],
                'details' => [
                    'es' => 'Una tienda hecha a tu medida, sin comisiones por venta de una plataforma. Se puede conectar con tu sistema de stock, tu facturación o tu WhatsApp para que la operación del día a día no se duplique.',
                    'en' => 'A store built around your business, with no per-sale platform fees. It can connect to your stock system, invoicing or WhatsApp so day-to-day work is not done twice.',
                ],
                'includes' => [
                    'es' => ['Catálogo con categorías, filtros y buscador', 'Carrito y pago con Mercado Pago', 'Envíos, retiro en local y cupones', 'Panel de pedidos, clientes y stock', 'Avisos automáticos por email o WhatsApp'],
                    'en' => ['Catalog with categories, filters and search', 'Cart and online payments', 'Shipping, store pickup and coupons', 'Orders, customers and stock dashboard', 'Automatic email or WhatsApp notifications'],
                ],
                'delivery' => ['es' => '5 a 8 semanas', 'en' => '5 to 8 weeks'],
                'stack' => ['Laravel', 'Livewire', 'Mercado Pago'],
            ],
            [
                'group' => 'systems',
                'icon' => 'squares-2x2',
                'is_highlighted' => true,
                'title' => ['es' => 'Sistema de gestión a medida', 'en' => 'Custom management system'],
                'description' => [
                    'es' => 'Stock, ventas, turnos, clientes u órdenes de trabajo: el sistema se adapta a cómo trabajás, no al revés.',
                    'en' => 'Stock, sales, bookings, customers or work orders: the system fits the way you work, not the other way around.',
                ],
                'details' => [
                    'es' => 'Para negocios que hoy se organizan con planillas, cuadernos o varios programas que no se hablan entre sí. Empiezo por relevar cómo trabajás y armo primero lo que más tiempo te ahorra; el resto se suma por etapas.',
                    'en' => 'For businesses running on spreadsheets, notebooks or several programs that don\'t talk to each other. I start by mapping how you work and build first what saves you the most time; the rest comes in stages.',
                ],
                'includes' => [
                    'es' => ['Relevamiento de procesos antes de programar', 'Usuarios con roles y permisos', 'Reportes y exportación a Excel o PDF', 'Funciona en la compu, la tablet y el celular', 'Copias de seguridad automáticas', 'Entregas por etapas, con demos cada semana'],
                    'en' => ['Process mapping before writing code', 'Users with roles and permissions', 'Reports and Excel or PDF export', 'Works on desktop, tablet and phone', 'Automatic backups', 'Delivered in stages, with weekly demos'],
                ],
                'delivery' => ['es' => '6 a 12 semanas', 'en' => '6 to 12 weeks'],
                'stack' => ['Laravel', 'Filament', 'MySQL'],
            ],
            [
                'group' => 'systems',
                'icon' => 'device-phone-mobile',
                'title' => ['es' => 'App web instalable (PWA)', 'en' => 'Installable web app (PWA)'],
                'description' => [
                    'es' => 'Una app que tus clientes o tu equipo instalan en el celular, sin pasar por las tiendas de aplicaciones.',
                    'en' => 'An app your customers or team install on their phone, without going through the app stores.',
                ],
                'details' => [
                    'es' => 'Sirve para reservas, seguimiento de pedidos, técnicos en la calle o clientes que consultan el estado de su trámite. Es más rápida y económica que una app nativa y se actualiza sola.',
                    'en' => 'Great for bookings, order tracking, field technicians or customers checking the status of their request. Faster and cheaper than a native app, and it updates itself.',
                ],
                'includes' => [
                    'es' => ['Ícono en la pantalla de inicio del celular', 'Ingreso con usuario o código', 'Notificaciones y avisos por WhatsApp', 'Funciona aunque la señal sea mala', 'Panel para administrar todo'],
                    'en' => ['Home-screen icon on the phone', 'Login with user or access code', 'Push and WhatsApp notifications', 'Works even with a weak signal', 'Admin panel to manage everything'],
                ],
                'delivery' => ['es' => '6 a 10 semanas', 'en' => '6 to 10 weeks'],
                'stack' => ['Laravel', 'PWA', 'Livewire'],
            ],
            [
                'group' => 'systems',
                'icon' => 'magnifying-glass-circle',
                'title' => ['es' => 'Análisis y relevamiento', 'en' => 'Analysis and discovery'],
                'description' => [
                    'es' => 'Antes de invertir en software, entendemos el problema y definimos qué conviene construir.',
                    'en' => 'Before investing in software, we understand the problem and decide what is worth building.',
                ],
                'details' => [
                    'es' => 'Es el paso que más plata ahorra. Te llevás un documento claro con el alcance, las etapas y los costos, que te sirve aunque después decidas desarrollarlo con otra persona.',
                    'en' => 'The step that saves the most money. You get a clear document with scope, stages and costs, useful even if you later build it with someone else.',
                ],
                'includes' => [
                    'es' => ['Entrevistas con quienes usan el proceso hoy', 'Mapa del proceso actual y del propuesto', 'Requerimientos y modelo de datos', 'Propuesta por etapas con plazos y costos'],
                    'en' => ['Interviews with the people who run the process', 'Map of the current and proposed process', 'Requirements and data model', 'Staged proposal with timelines and costs'],
                ],
                'delivery' => ['es' => '1 a 2 semanas', 'en' => '1 to 2 weeks'],
                'stack' => ['UML', 'BPMN', 'SQL'],
            ],
            [
                'group' => 'ai',
                'icon' => 'chat-bubble-left-right',
                'is_highlighted' => true,
                'title' => ['es' => 'Asistente con IA para WhatsApp y web', 'en' => 'AI assistant for WhatsApp and web'],
                'description' => [
                    'es' => 'Responde consultas a toda hora con la información de tu negocio y te pasa la charla cuando hace falta una persona.',
                    'en' => 'Answers questions at any hour using your business information, and hands the chat to you when a person is needed.',
                ],
                'details' => [
                    'es' => 'No es el bot de "presioná 1". Entiende lo que la gente escribe, consulta precios, stock o turnos en tu sistema y deja registrada cada conversación. Vos definís qué puede responder y qué no.',
                    'en' => 'Not a "press 1" bot. It understands what people write, checks prices, stock or bookings in your system and logs every conversation. You decide what it can and cannot answer.',
                ],
                'includes' => [
                    'es' => ['Entrenado con tus preguntas frecuentes, precios y políticas', 'Toma pedidos, turnos o datos de contacto', 'Botón "hablar con una persona" siempre disponible', 'Panel con todas las conversaciones', 'Pruebas con consultas reales antes de publicarlo'],
                    'en' => ['Trained on your FAQs, prices and policies', 'Takes orders, bookings or contact details', '"Talk to a person" option always available', 'Dashboard with every conversation', 'Tested with real questions before launch'],
                ],
                'delivery' => ['es' => '2 a 4 semanas', 'en' => '2 to 4 weeks'],
                'stack' => ['IA', 'WhatsApp API', 'Laravel'],
            ],
            [
                'group' => 'ai',
                'icon' => 'bolt',
                'title' => ['es' => 'Automatización de tareas', 'en' => 'Task automation'],
                'description' => [
                    'es' => 'Las tareas repetitivas que te comen horas por semana pasan a hacerse solas.',
                    'en' => 'The repetitive tasks that eat hours every week start running on their own.',
                ],
                'details' => [
                    'es' => 'Cargar datos de una planilla a otra, mandar recordatorios, armar el reporte del viernes, clasificar mails o comprobantes. Identificamos juntos qué conviene automatizar primero y medimos el tiempo que te ahorra.',
                    'en' => 'Copying data between spreadsheets, sending reminders, building the Friday report, sorting emails or receipts. We pick together what to automate first and measure the time it saves.',
                ],
                'includes' => [
                    'es' => ['Detección de las tareas que más tiempo consumen', 'Recordatorios y avisos automáticos', 'Reportes que llegan solos a tu correo', 'Lectura y clasificación de documentos con IA', 'Registro de todo lo que se ejecutó'],
                    'en' => ['Finding the tasks that take the most time', 'Automatic reminders and alerts', 'Reports delivered to your inbox', 'AI document reading and sorting', 'Log of everything that ran'],
                ],
                'delivery' => ['es' => '1 a 3 semanas', 'en' => '1 to 3 weeks'],
                'stack' => ['IA', 'Cron', 'Webhooks'],
            ],
            [
                'group' => 'ai',
                'icon' => 'arrows-right-left',
                'title' => ['es' => 'Integraciones entre sistemas', 'en' => 'System integrations'],
                'description' => [
                    'es' => 'Conecto tu web, tu sistema y los servicios que ya usás para que la información viaje sola.',
                    'en' => 'I connect your website, your system and the services you already use so information flows on its own.',
                ],
                'details' => [
                    'es' => 'Cuando una venta entra por la web y alguien la vuelve a cargar a mano en otro lado, hay una integración pendiente. Trabajo con APIs de pagos, mensajería, facturación electrónica, planillas y sistemas propios.',
                    'en' => 'If a sale comes in through the website and someone retypes it somewhere else, there is an integration waiting to happen. I work with payment, messaging, e-invoicing, spreadsheet and in-house system APIs.',
                ],
                'includes' => [
                    'es' => ['Mercado Pago y otros medios de cobro', 'WhatsApp Business API', 'Facturación electrónica (ARCA)', 'Google Sheets, correo y calendarios', 'APIs a medida y webhooks'],
                    'en' => ['Mercado Pago and other payment gateways', 'WhatsApp Business API', 'Electronic invoicing', 'Google Sheets, email and calendars', 'Custom APIs and webhooks'],
                ],
                'delivery' => ['es' => '1 a 4 semanas', 'en' => '1 to 4 weeks'],
                'stack' => ['APIs REST', 'Webhooks', 'OAuth'],
            ],
            [
                'group' => 'care',
                'icon' => 'shield-check',
                'is_highlighted' => true,
                'title' => ['es' => 'Mantenimiento mensual', 'en' => 'Monthly maintenance'],
                'description' => [
                    'es' => 'Tu web o sistema siempre actualizado, seguro y con alguien que responde cuando lo necesitás.',
                    'en' => 'Your website or system always up to date, secure, and with someone who answers when you need it.',
                ],
                'details' => [
                    'es' => 'Incluye horas para cambios chicos cada mes, así no tenés que pedir un presupuesto por cada ajuste. Sirve para proyectos que hice yo y también para los que hizo otra persona.',
                    'en' => 'Includes hours for small changes every month, so you don\'t need a quote for every tweak. Works for projects I built and for ones built by someone else.',
                ],
                'includes' => [
                    'es' => ['Actualizaciones de seguridad', 'Copias de seguridad y monitoreo', 'Horas mensuales para cambios y mejoras', 'Atención directa por WhatsApp', 'Informe mensual de lo que se hizo'],
                    'en' => ['Security updates', 'Backups and uptime monitoring', 'Monthly hours for changes and improvements', 'Direct support on WhatsApp', 'Monthly report of the work done'],
                ],
                'delivery' => ['es' => 'Plan mensual', 'en' => 'Monthly plan'],
                'stack' => ['Backups', 'Monitoreo', 'Soporte'],
            ],
            [
                'group' => 'care',
                'icon' => 'wrench-screwdriver',
                'title' => ['es' => 'Rescate de proyectos', 'en' => 'Project rescue'],
                'description' => [
                    'es' => '¿Quedó a medias, anda lento o quien lo hizo ya no responde? Lo reviso y lo pongo en marcha.',
                    'en' => 'Left half done, running slow, or the original developer stopped answering? I review it and get it moving.',
                ],
                'details' => [
                    'es' => 'Empiezo con una auditoría para saber qué se puede aprovechar y qué conviene rehacer. Te explico el estado real en palabras simples antes de tocar una sola línea.',
                    'en' => 'I start with an audit to see what can be kept and what is better rebuilt. You get the real state of things in plain words before I change a single line.',
                ],
                'includes' => [
                    'es' => ['Auditoría de código, servidor y seguridad', 'Recuperación de accesos, dominio y hosting', 'Corrección de errores urgentes', 'Plan de mejoras por prioridad'],
                    'en' => ['Code, server and security audit', 'Recovering access, domain and hosting', 'Fixing urgent bugs', 'Prioritized improvement plan'],
                ],
                'delivery' => ['es' => '1 a 3 semanas', 'en' => '1 to 3 weeks'],
                'stack' => ['Laravel', 'PHP', 'Linux'],
            ],
        ];
    }
}
