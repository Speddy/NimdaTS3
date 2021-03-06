<?php

namespace Plugin;

use App\Plugin;
use Carbon\Carbon;
use TeamSpeak3\Ts3Exception;

class WelcomeMsg extends Plugin implements PluginContract
{
    public function isTriggered()
    {
        try {
            $client = $this->teamSpeak3Bot->node->clientGetById($this->info['clid']);
            $clientInfo = $this->teamSpeak3Bot->node->clientInfoDb($this->teamSpeak3Bot->node->clientFindDb($client['client_nickname']));
        } catch(Ts3Exception $e) {
            return;
        }

        $format = [
            "%CL_DATABASE_ID%"      => $clientInfo["client_database_id"],
            "%CL_UNIQUE_ID%"        => $clientInfo["client_unique_identifier"],
            "%CL_COUNTRY%"          => $client['client_country'],
            "%CL_NAME%"             => $client['client_nickname'],
            "%CL_VERSION%"          => $client['client_version'],
            "%CL_PLATFORM%"         => $client['client_platform'],
            "%CL_IP%"               => $client['connection_client_ip'],
            "%CL_CREATED%"          => Carbon::createFromTimestamp($clientInfo["client_created"])->toDayDateTimeString(),
            "%CL_TOTALCONNECTIONS%"  => $clientInfo["client_totalconnections"],
        ];

        $msg = strtr($this->CONFIG['msg'], $format);
        $this->sendOutput($msg);
    }
}
