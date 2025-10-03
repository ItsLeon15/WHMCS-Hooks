<?php

/**
 * Adds the option to view Credit Balance in WHMCS - Client View
 *
 * @package     WHMCS
 * @copyright   ItsLeon15
 * @link        https://www.platinumhost.io
 * @author      Leon <leon@platinumhost.io>
 */

use WHMCS\View\Menu\Item as MenuItem;

add_hook('ClientAreaPrimarySidebar', 1, function (MenuItem $primarySidebar) {

    $filename = basename($_SERVER['REQUEST_URI'], ".php");
    $parseFile = explode('.', $filename);
    $client = Menu::context("client");
    $clientid = intval($client->id);
    if ($parseFile['0'] !== 'clientarea' || $clientid === 0) {
        return;
    }
    $primarySidebar->addChild('Client-Balance', array('label' => Lang::trans('availcreditbal'), 'uri' => '#', 'order' => '1', 'icon' => 'fa-money'));
    $balancePanel = $primarySidebar->getChild('Client-Balance')->moveToBack()->setOrder(0);
    $balancePanel->addChild('balance-amount', array('uri' => 'clientarea.php?action=addfunds"style="background-color: transparent !important', 'label' => '<h4 style="text-align:center; margin:0">' . formatcurrency($client->credit, $client->currencyId) . '</h4>', 'order' => 1));
    $balancePanel->setFooterHtml('<a href="clientarea.php?action=addfunds" class="btn btn-success btn-sm btn-block"><i class="fa fa-plus"></i> ' . Lang::trans('addfunds') . '</a>');
});
