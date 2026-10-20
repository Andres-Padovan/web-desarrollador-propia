<?php

namespace App\Livewire;

use App\Models\ContactMessage;
use App\Notifications\NewContactMessage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactForm extends Component
{
    #[Locked]
    public string $locale = 'es';

    #[Validate('required|string|max:120')]
    public string $name = '';

    #[Validate('required|email|max:160')]
    public string $email = '';

    #[Validate('nullable|string|max:40')]
    public string $phone = '';

    #[Validate('nullable|string|max:80')]
    public string $budget = '';

    #[Validate('required|string|min:10|max:5000')]
    public string $message = '';

    /** Honeypot: real visitors never see or fill this field. */
    public string $website = '';

    public bool $sent = false;

    public bool $throttled = false;

    public function mount(): void
    {
        $this->locale = App::getLocale();
    }

    public function hydrate(): void
    {
        App::setLocale($this->locale);
    }

    public function send(): void
    {
        $this->validate();

        if (filled($this->website)) {
            $this->sent = true;

            return;
        }

        $key = 'contact:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $this->throttled = true;

            return;
        }

        RateLimiter::hit($key, 3600);

        $contactMessage = ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'budget' => $this->budget ?: null,
            'message' => $this->message,
            'locale' => $this->locale,
            'ip' => request()->ip(),
        ]);

        if ($recipient = config('mail.contact_notify')) {
            rescue(fn () => Notification::route('mail', $recipient)->notify(new NewContactMessage($contactMessage)), report: true);
        }

        $this->reset('name', 'email', 'phone', 'budget', 'message');
        $this->sent = true;
    }

    public function render(): View
    {
        return view('livewire.contact-form');
    }
}
