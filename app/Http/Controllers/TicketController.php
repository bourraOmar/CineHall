<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    public function downloadTicket($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

            $pdf = Pdf::loadView('tickets.pdf', compact('ticket'));

            return response()->streamDownload(
                fn () => print($pdf->output()),
                "ticket_{$ticketId}.pdf"
            );
    }
}
