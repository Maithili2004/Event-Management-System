<?php
include 'dbconnection.php';
require_once 'C:\Users\Maithili\Downloads\tcpdf/tcpdf.php';
// Get attendee and event information
if (isset($_GET['attendee_id']) && isset($_GET['event_id'])) {
    $attendee_id = $_GET['attendee_id'];
    $event_id = $_GET['event_id'];

    // Retrieve event details
    $event_query = $pdo->prepare("SELECT event_name, event_date, venue, ticket_price FROM events WHERE event_id = :event_id");
    $event_query->execute(['event_id' => $event_id]);
    $event = $event_query->fetch();

    // Retrieve attendee details
    $attendee_query = $pdo->prepare("SELECT attendee_name, email FROM attendees WHERE attendee_id = :attendee_id");
    $attendee_query->execute(['attendee_id' => $attendee_id]);
    $attendee = $attendee_query->fetch();

    // If event and attendee are found, generate the ticket
    if ($event && $attendee) {
        // Create new PDF document
        $pdf = new TCPDF();
        $pdf->SetCreator('Event Management System');
        $pdf->SetAuthor('Event Management');
        $pdf->SetTitle('Event Ticket');
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();

        // Add ticket content
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->Cell(0, 10, 'Event Ticket', 0, 1, 'C');

        $pdf->SetFont('helvetica', '', 12);
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Attendee: ' . $attendee['attendee_name'], 0, 1);
        $pdf->Cell(0, 10, 'Email: ' . $attendee['email'], 0, 1);
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Event: ' . $event['event_name'], 0, 1);
        $pdf->Cell(0, 10, 'Date: ' . $event['event_date'], 0, 1);
        $pdf->Cell(0, 10, 'Venue: ' . $event['venue'], 0, 1);
        $pdf->Cell(0, 10, 'ticket_price: Rs.' . $event['ticket_price'], 0, 1);
        $pdf->Ln(10);
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Thank you for your booking!', 0, 1, 'C');

        // Output the PDF as a download
        $pdf->Output('ticket.pdf', 'D');
    } else {
        echo "Event or Attendee not found!";
    }
} else {
    echo "Invalid parameters!";
}
?>
