<?php

namespace App\Mail;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\MessageConverter;

class ResendTransport extends AbstractTransport
{
    protected $key;

    public function __construct(string $key)
    {
        parent::__construct();
        $this->key = $key;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        
        $payload = [
            'from'    => $email->getFrom()[0]->toString(),
            'to'      => array_map(fn($recipient) => $recipient->getAddress(), $email->getTo()),
            'subject' => $email->getSubject(),
            'html'    => $email->getHtmlBody(),
            'text'    => $email->getTextBody(),
        ];

        // Manejar archivos adjuntos si existen
        foreach ($email->getAttachments() as $attachment) {
            $payload['attachments'][] = [
                'filename' => $attachment->getFilename(),
                'content'  => base64_encode($attachment->getBody()),
            ];
        }

        $response = Http::withToken($this->key)
            ->post('https://api.resend.com/emails', $payload);

        if ($response->failed()) {
            Log::error('Resend API Error: ' . $response->body());
            throw new \Exception('Resend API Error: ' . $response->body());
        }
    }

    public function __toString(): string
    {
        return 'resend';
    }
}
