<?php

/**
 * Adds a "View as Client" and "View as Admin" button to WHMCS for easy access to a clients server on Pterodactyl.
 * Must have the External ID set on all servers for this to work.
 *
 * @package     WHMCS
 * @copyright   ItsLeon15
 * @link        https://www.platinumhost.xyz
 * @author      Leon <leon@platinumhost.xyz>
 */

add_hook('AdminClientServicesTabFields', 1, function ($vars) {
    $apiKey = ''; // Enter your Pterodactyl API Key
    $panelUrl = ''; // Enter your Pterodactyl URL (https://example.com). Won't work if you have a / at the end. 
    $productId = $vars['id'];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $panelUrl . '/api/application/servers/external/' . $productId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . $apiKey,
            "Cache-Control: no-cache",
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $response = json_decode($response, true);

    $uuid = $response['attributes']['identifier'];
    $serverId = $response['attributes']['id'];

    if ($uuid && $serverId) {
        return [
            'Quick Server Access' => '<a href="' . $panelUrl . '/server/' . $uuid . '" target="_blank" class="btn btn-default">View as Client</a> <a href="' . $panelUrl . '/admin/servers/view/' . $serverId . '" target="_blank" class="btn btn-default">View as Admin</a>',
        ];
    }
});
