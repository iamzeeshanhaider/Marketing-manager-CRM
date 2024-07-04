<?php

namespace App\Mail;

use Dompdf\Dompdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public function __construct(public Dompdf $pdfData, public $lead)
    {
    }


    public function build()
    {
        return $this->view('emails.invoice')
            ->attachData($this->pdfData->output(), 'invoice.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
