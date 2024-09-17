<?php

namespace App\Mail;

use App\Models\SuratMasuk;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuratMasukNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public String $namaPengirim;
    public SuratMasuk $suratMasuk;

    public function __construct(String $namaPengirim, SuratMasuk $suratMasuk)
    {
        $this->namaPengirim = $namaPengirim;
        $this->suratMasuk = $suratMasuk;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('sparti.office@gmail.com', $this->namaPengirim),
            subject: 'Notifikasi Surat Masuk',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.surat-masuk-notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
