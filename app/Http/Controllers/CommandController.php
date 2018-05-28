<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\src\QueueHandler;
use xgerhard\nbheaders\Nightbot;

class CommandController extends BaseController
{
    public function QueryParser(Request $request)
    {
        if($request->has('q'))
        {
            $aQuery = explode(" ", trim($request->input('q')));
            if(!empty($aQuery) && $strAction = $this->getAction($aQuery[0]))
            {
                array_shift($aQuery);
                $strMessage = empty($aQuery) ? "" : implode(" ", $aQuery);
                
                $oNightbot = new Nightbot($request);
                if(!$oNightbot->isNightbotRequest())
                {
                    return 'This command only works through Nightbot.';
                }

                try{
                    $oQH = new QueueHandler($oNightbot->getChannel());
                    if($oNightbot->getUser()) $oQH->setUser($oNightbot->getUser());

                    switch($strAction)
                    {
                        case 'join':
                            return $oQH->joinQueue($strMessage);
                        break;

                        case 'leave':
                            return $oQH->leaveQueue();
                        break;

                        case 'position':
                            return $oQH->getPosition();
                        break;

                        case 'list':
                            return $oQH->getList();
                        break;

                        case 'info':
                            return $oQH->info();
                        break;

                        case 'open':
                            return $oQH->openQueue();
                        break;

                        case 'close':
                            return $oQH->closeQueue();
                        break;

                        case 'next':
                            return $oQH->getNext((int) $strMessage);
                        break;

                        case 'clear':
                            return $oQH->clearQueue();
                        break;

                        case 'add':
                            return $oQH->addQueue($strMessage, 1, true);
                        break;

                        case 'del':
                            return $oQH->deleteQueue($strMessage);
                        break;

                        case 'set':
                            return $oQH->setQueue($strMessage);
                        break;

                        case 'remove':
                            return $oQH->removeQueueUser($strMessage);
                        break;

                        case 'help':
                            return 'Please see www.url.com for more info on usage';
                        break;

                        default:
                            return 'Unknown command, please see www.url.com for more info on usage';
                        break;
                    }
                }
                //catch(\Exception $e)
                catch(Exception $e)
                {
                    dd($e);
                }
            }
            else return 'Invalid action, available actions: add, remove, join, leave, position';
        }
        else return 'Invalid request';
    }

    public function getAction($strAction)
    {
        $strAction = strtolower(trim($strAction));
        $aActions = array(
            'add',
            'set',
            'del',
            'list',
            'join' ,
            'leave',
            'position',
            'open',
            'close',
            'next',
            'clear',
            'info',
            'remove'
        );
        if(in_array($strAction, $aActions)) return $strAction;
        return false;
    }
}
?>