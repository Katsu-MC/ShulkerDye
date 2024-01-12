<?php

declare(strict_types=1);

namespace Katsu\ShulkerDye;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
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
            $recipe->getResults([$resultShulker->setNamedTag($shulkerBox->getNamedTag())]);
            $this->getServer()->getCraftingManager()->registerShapelessRecipe($recipe);
        }
    }
}
