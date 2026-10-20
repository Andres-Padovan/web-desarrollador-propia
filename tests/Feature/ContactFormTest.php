<?php

namespace Tests\Feature;

use App\Livewire\ContactForm;
use App\Models\ContactMessage;
use App\Notifications\NewContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_message_is_stored_and_notified(): void
    {
        Notification::fake();
        config(['mail.contact_notify' => 'andres@example.com']);

        Livewire::test(ContactForm::class)
            ->set('name', 'Lucía')
            ->set('email', 'lucia@example.com')
            ->set('budget', 'Sitio web')
            ->set('message', 'Necesito una web para mi bodega.')
            ->call('send')
            ->assertHasNoErrors()
            ->assertSet('sent', true);

        $this->assertDatabaseHas(ContactMessage::class, ['email' => 'lucia@example.com', 'budget' => 'Sitio web', 'read_at' => null]);
        Notification::assertSentOnDemand(NewContactMessage::class);
    }

    public function test_required_fields_are_validated(): void
    {
        Livewire::test(ContactForm::class)
            ->set('email', 'no-es-un-email')
            ->call('send')
            ->assertHasErrors(['name' => 'required', 'email' => 'email', 'message' => 'required']);

        $this->assertDatabaseCount(ContactMessage::class, 0);
    }

    public function test_honeypot_silently_drops_bots(): void
    {
        Livewire::test(ContactForm::class)
            ->set('name', 'Bot')
            ->set('email', 'bot@example.com')
            ->set('message', 'Compre seguidores baratos ya mismo')
            ->set('website', 'https://spam.example')
            ->call('send')
            ->assertSet('sent', true);

        $this->assertDatabaseCount(ContactMessage::class, 0);
    }

    public function test_more_than_three_messages_per_hour_are_throttled(): void
    {
        foreach (range(1, 4) as $attempt) {
            $component = Livewire::test(ContactForm::class)
                ->set('name', 'Lucía')
                ->set('email', 'lucia@example.com')
                ->set('message', "Mensaje número {$attempt} de prueba")
                ->call('send');
        }

        $component->assertSet('throttled', true);
        $this->assertDatabaseCount(ContactMessage::class, 3);
    }
}
