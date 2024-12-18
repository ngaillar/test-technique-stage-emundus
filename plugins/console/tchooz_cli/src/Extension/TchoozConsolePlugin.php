<?php
namespace Emundus\Plugin\Console\Tchooz\Extension;

\defined('_JEXEC') or die;

use Emundus\Plugin\Console\Tchooz\CliCommand\TchoozResetFabrikConnectionCommand;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use Joomla\Application\ApplicationEvents;
use Joomla\CMS\Factory;

class TchoozConsolePlugin extends CMSPlugin implements SubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            \Joomla\Application\ApplicationEvents::BEFORE_EXECUTE => 'registerCommands',
        ];
    }

    public function registerCommands(): void
    {
        $app = Factory::getApplication();
		$db = Factory::getContainer()->get('DatabaseDriver');

		$app->addCommand(new TchoozResetFabrikConnectionCommand($db));
    }
}