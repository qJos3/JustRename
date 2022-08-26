<?php
namespace Jos3\Commands;


use Jos3\JustRename;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Bow;
use pocketmine\item\Tool;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class RenameCommand extends Command {
    public function __construct()
    {
        parent::__construct("rename","Rename the item what you are holding.");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            $file = JustRename::getConfiguration("messages.yml");
            $item = $sender->getInventory()->getItemInHand();
            if ($sender->hasPermission("rename.command.use")){
                if (!$item instanceof Bow and !$item instanceof Tool){
                    $msg = TextFormat::colorize($file->get("rename-error"));
                    $sender->sendMessage($msg);
                    return;
                }
                if (empty($args[0])){
                    $msg = TextFormat::colorize($file->get("rename-usage"));
                    $sender->sendMessage($msg);
                    return;
                }
                if (strlen(implode(" ",$args)) > 40){
                    $msg = TextFormat::colorize($file->get("rename-length"));
                    $sender->sendMessage($msg);
                    return;
                }
                $item->clearCustomName();
                $sender->getInventory()->setItemInHand($item->setCustomName(TextFormat::colorize(implode(" ",$args))));
                $msg = TextFormat::colorize($file->get("rename-success"));
                $sender->sendMessage($msg."\n> Your new rename is: ".$item->getCustomName());
            } else {
                $msg = TextFormat::colorize($file->get("non-permission"));
                $sender->sendMessage($msg);
            }

        }
    }
}