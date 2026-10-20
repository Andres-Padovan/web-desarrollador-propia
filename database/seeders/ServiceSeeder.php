<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'icon' => 'globe-alt',
                'span' => 2,
                'title' => ['es' => 'Desarrollo web a medida', 'en' => 'Custom web development'],
                'description' => [
                    'es' => 'Sitios, landing pages y aplicaciones web rápidas, seguras y pensadas para convertir visitas en clientes. Panel propio para que actualices el contenido sin depender de nadie.',
                    'en' => 'Fast, secure websites, landing pages and web apps built to turn visitors into clients. With your own admin panel so you can update content without depending on anyone.',
                ],
                'stack' => ['Laravel', 'Livewire', 'Tailwind', 'Vue'],
            ],
            [
                'icon' => 'magnifying-glass-circle',
                'span' => 1,
                'title' => ['es' => 'Análisis de sistemas', 'en' => 'Systems analysis'],
                'description' => [
                    'es' => 'Relevo procesos y datos, detecto cuellos de botella y diseño la solución antes de programar: requerimientos, modelos y documentación clara.',
                    'en' => 'I map processes and data, find bottlenecks and design the solution before coding: requirements, models and clear documentation.',
                ],
                'stack' => ['UML', 'BPMN', 'SQL'],
            ],
            [
                'icon' => 'squares-2x2',
                'span' => 1,
                'title' => ['es' => 'Sistemas de gestión', 'en' => 'Management systems'],
                'description' => [
                    'es' => 'Stock, turnos, ventas, clientes o lo que tu negocio necesite, en un panel administrativo con usuarios, permisos y reportes.',
                    'en' => 'Inventory, bookings, sales, customers or whatever your business needs, in an admin panel with users, permissions and reports.',
                ],
                'stack' => ['Filament', 'MySQL', 'PostgreSQL'],
            ],
            [
                'icon' => 'arrows-right-left',
                'span' => 2,
                'title' => ['es' => 'Integraciones y automatización', 'en' => 'Integrations & automation'],
                'description' => [
                    'es' => 'Conecto tus sistemas entre sí y con servicios externos: pagos, WhatsApp, APIs, facturación e inteligencia artificial. Las tareas repetitivas se hacen solas.',
                    'en' => 'I connect your systems to each other and to external services: payments, WhatsApp, APIs, invoicing and AI. Repetitive tasks run on their own.',
                ],
                'stack' => ['APIs REST', 'Webhooks', 'IA', 'Cron'],
            ],
        ];

        foreach ($services as $sort => $service) {
            Service::updateOrCreate(['title->es' => $service['title']['es']], [...$service, 'sort' => $sort]);
        }
    }
}
