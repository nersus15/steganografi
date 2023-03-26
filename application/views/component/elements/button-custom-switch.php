<?php
if (!isset($class)) $class = '';
if (!isset($id)) $id = '';
?>
<style>
    .switch input {
        display: none;
    }

    .switch {
        width: 60px;
        height: 30px;
        position: relative;
    }

    .slider {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        border-radius: 30px;
        box-shadow: 0 0 0 2px #777, 0 0 4px #777;
        cursor: pointer;
        border: 4px solid transparent;
        overflow: hidden;
        transition: 0.2s;
    }

    .slider:before {
        position: absolute;
        content: "";
        width: 100%;
        height: 100%;
        background-color: #777;
        border-radius: 30px;
        transform: translateX(-30px);
        /*translateX(-(w-h))*/
        transition: 0.2s;
    }

    input:checked+.slider:before {
        transform: translateX(30px);
        /*translateX(w-h)*/
        background-color: #86367e;
    }

    input:checked+.slider {
        box-shadow: 0 0 0 2px #86367e, 0 0 8px #86367e;
    }

    .switch200 .slider:before {
        width: 200%;
        transform: translateX(-82px);
        /*translateX(-(w-h))*/
    }

    .switch200 input:checked+.slider:before {
        background-color: red;
    }

    .switch200 input:checked+.slider {
        box-shadow: 0 0 0 2px red, 0 0 8px red;
    }
</style>
<label class="switch">
    <input <?= !empty($id) ? 'id = "'.$id.'"' : '' ?> class="<?= $class ?> custom-button-back" type="checkbox">
    <span class="slider"></span>
</label>