<?php

namespace Plugin;

use App\Plugin;
use TeamSpeak3\TeamSpeak3;
use TeamSpeak3\Ts3Exception;

define('iOS','iOS');
define('MacOS','OS X');
define('Windows','Windows');
define('Android','Android');
define('Linux','Linux');

class DisallowOS extends Plugin implements PluginContract
{
    private $server;

    public function isTriggered()
    {
        $this->disallowed = array(Android);

        $this->server = $this->teamSpeak3Bot->node;
        try {
            $client = $this->server->clientGetById($this->info['clid']);
            $clientInfo = $this->server->clientInfoDb($this->server->clientFindDb($client['client_nickname']));
        } catch(Ts3Exception $e) {
            return;
        }

        $client = $this->server->clientGetById($this->info['clid']);
        if(in_array($client['client_platform'],$this->disallowed))
        {
            $client->kick(TeamSpeak3::KICK_SERVER,"You are using disallowed OS!");
        }
    }
}
