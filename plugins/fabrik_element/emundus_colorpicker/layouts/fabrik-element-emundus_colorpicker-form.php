<?php
defined('JPATH_BASE') or die;

use Joomla\CMS\Language\Text;

$d = $displayData;

?>

<div id="<?php echo $displayData->attributes['name']; ?>___map_container" class="fabrikSubElementContainer fabrikEmundusColorpicker">
</div>
<div class="color-picker">
    <div class="preset-colors" style="visibility: <?php echo ($d->hiddenColor == 1) ? 'hidden' : 'visible'; ?>;">
        <div class="color" style="background-color: <?php echo $d->firstColorPalette; ?>;" onclick="setColor('<?php echo $d->firstColorPalette; ?>')"></div>
        <div class="color" style="background-color: <?php echo $d->secondColorPalette; ?>;" onclick="setColor('<?php echo $d->secondColorPalette; ?>')"></div>
        <div class="color" style="background-color: <?php echo $d->thirdColorPalette; ?>;" onclick="setColor('<?php echo $d->thirdColorPalette; ?>')"></div>
        <div class="color" style="background-color: <?php echo $d->fourthColorPalette; ?>;" onclick="setColor('<?php echo $d->fourthColorPalette; ?>')"></div>
        <div class="color" style="background-color: <?php echo $d->fifthColorPalette; ?>;" onclick="setColor('<?php echo $d->fifthColorPalette; ?>')"></div>
        <div class="color" style="background-color: <?php echo $d->sixthColorPalette; ?>;" onclick="setColor('<?php echo $d->sixthColorPalette; ?>')"></div>
        <div class="color" style="background-color: <?php echo $d->seventhColorPalette; ?>;" onclick="setColor('<?php echo $d->seventhColorPalette; ?>')"></div>
    </div>
    <div class="manual-color">
        <input type="text" oninput="updateColorFromInput()" placeholder="#000000" maxlength="7" class="text-color"
            <?php foreach ($displayData->attributes as $key => $value) :
                echo $key . '="' . $value . '" ';
            endforeach;
            ?> />

        <input type="color" onchange="updateHexFromColor()" id="color-selector" class="form-control fabrikinput inputbox color-selector"
               value="<?php echo $displayData->attributes['value']; ?>" />
    </div>
</div>

<!-- Implémentation JS, l'attribut 'name' est nécessaire pour reproduire ce plug-in à tout autre formulaire -->
<script>
    function setColor(color) {
        document.getElementById('color-selector').value = color;
        document.getElementById('<?php echo $displayData->attributes['name']; ?>').value = color;
    }

    function updateHexFromColor() {
        const color = document.getElementById('color-selector').value;
        // On effectue une vérification avec regex
        if(/^#[0-9A-Fa-f]{6}$/.test(color)) {
            document.getElementById('<?php echo $displayData->attributes['name']; ?>').value = color;
        }
    }
    function updateColorFromInput() {
        const color = document.getElementById('<?php echo $displayData->attributes['name']; ?>').value;
        document.getElementById('color-selector').value = color;
    }
</script>