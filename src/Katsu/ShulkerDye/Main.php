<?php

declare(strict_types=1);

namespace Katsu\ShulkerDye;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\block\VanillaBlocks;
use pocketmine\block\utils\DyeColor;
use pocketmine\crafting\ShapelessRecipe;
use pocketmine\crafting\ShapelessRecipeType;
use pocketmine\crafting\ExactRecipeIngredient;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->registerCraftingRecipes();
    }

    private function registerCraftingRecipes(): void {
        $colors = [
            DyeColor::WHITE,
            DyeColor::ORANGE,
            DyeColor::MAGENTA,
            DyeColor::LIGHT_BLUE,
            DyeColor::YELLOW,
            DyeColor::LIME,
            DyeColor::PINK,
            DyeColor::GRAY,
            DyeColor::LIGHT_GRAY,
            DyeColor::CYAN,
            DyeColor::PURPLE,
            DyeColor::BLUE,
            DyeColor::BROWN,
            DyeColor::GREEN,
            DyeColor::RED,
            DyeColor::BLACK,
        ];

        foreach ($colors as $color) {
            $dye = VanillaItems::DYE()->setColor($color);
            $shulkerBox = VanillaBlocks::SHULKER_BOX()->asItem();
            $resultShulker = VanillaBlocks::DYED_SHULKER_BOX()->setColor($color)->asItem();
            $dyeIngredient = new ExactRecipeIngredient($dye);
            $shulkerBoxIngredient = new ExactRecipeIngredient($shulkerBox);
            $shapelessRecipeType = ShapelessRecipeType::CRAFTING();
            $recipe = new ShapelessRecipe([$shulkerBoxIngredient, $dyeIngredient], [$resultShulker], $shapelessRecipeType);
            $this->getServer()->getCraftingManager()->registerShapelessRecipe($recipe);
        }
    }

    public function onCraftItem(CraftItemEvent $event): void {
        $transaction = $event->getTransaction();
        $inventories = $transaction->getInventories();
        foreach ($inventories as $inventory) {
            foreach ($event->getOutputs() as $output) {
                if ($output->getTypeId() === VanillaBlocks::DYED_SHULKER_BOX()->asItem()->getTypeId()) {
                    foreach ($event->getTransaction()->getInputSlotChanges() as $change) {
                        $input = $change->getSourceItem();
                        if ($input->getTypeId() === VanillaBlocks::SHULKER_BOX()->asItem()->getTypeId()) {
                            $output->setNamedTag($input->getNamedTag());
                            break;
                        }
                    }
                }
            }
        }
    }
}
