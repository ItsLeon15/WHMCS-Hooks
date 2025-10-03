<?php

/**
 * Automatically send a email to a client when a new reply is made from a admin.
 *
 * @package     WHMCS
 * @copyright   ItsLeon15
 * @link        https://www.platinumhost.io
 * @author      Leon <leon@platinumhost.io>
 */

use WHMCS\Database\Capsule;

add_hook('TicketAdminReply', 1, function($vars) {

    $ticketDetails = Capsule::table('tbltickets')
        ->where('id', $vars['ticketid'])
        ->first(['userid', 'tid', 'title', 'id', 'c']
    );

    $EmailData = array(
        'id' => $ticketDetails->userid,
        'customtype' => 'general',
        'customsubject' => '[Ticket ID: ' . $ticketDetails->tid . '] ' . $ticketDetails->title,
        'custommessage' => $vars['message'] . PHP_EOL . 
        '----------------------------------------------' . PHP_EOL . 
        'Ticket ID: ' . $ticketDetails->tid . PHP_EOL . 
        'Subject: ' . $ticketDetails->title . PHP_EOL . 
        'Status: ' . $vars['status'] . PHP_EOL .
        'View Ticket: ' . '<a href="' . $vars['systemurl'] . 'viewticket.php?tid=' . $ticketDetails->tid . '&c=' . $ticketDetails->c . '">Click Here</a>' . PHP_EOL .
        '----------------------------------------------' . PHP_EOL . 
        PHP_EOL . 
        'This is an automated message. Please do not reply to this email.',
    );
    
    localAPI('SendEmail', $EmailData);
});
