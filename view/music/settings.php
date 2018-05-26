<h1 class="rules">Music settings</h1>

<form method="post">
    <label for="volume">Music volume:</label>
    <input id="volume" name="volume" type="range" value="<?=$settings['volume']?>" style="width: 50%;"><br>

    <div class="center"><input name="submit" type="submit" value="Save"></div>
</form>

<style>
    input[type=range] {
        -webkit-appearance: none;
        width: 100%;
        margin: 7.3px 0;
    }
    input[type=range]:focus {
        outline: none;
    }
    input[type=range]::-webkit-slider-runnable-track {
        width: 100%;
        height: 11.4px;
        cursor: pointer;
        box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
        background: #000000;
        border-radius: 1.3px;
        border: 0.2px solid #010101;
    }
    input[type=range]::-webkit-slider-thumb {
        box-shadow: 0.9px 0.9px 1px #000031, 0px 0px 0.9px #00004b;
        border: 1.8px solid #00001e;
        height: 26px;
        width: 26px;
        border-radius: 15px;
        background: #ffffff;
        cursor: pointer;
        -webkit-appearance: none;
        margin-top: -7.5px;
    }
    input[type=range]:focus::-webkit-slider-runnable-track {
        background: #0d0d0d;
    }
    input[type=range]::-moz-range-track {
        width: 100%;
        height: 11.4px;
        cursor: pointer;
        box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
        background: #000000;
        border-radius: 1.3px;
        border: 0.2px solid #010101;
    }
    input[type=range]::-moz-range-thumb {
        box-shadow: 0.9px 0.9px 1px #000031, 0px 0px 0.9px #00004b;
        border: 1.8px solid #00001e;
        height: 26px;
        width: 26px;
        border-radius: 15px;
        background: #ffffff;
        cursor: pointer;
    }
    input[type=range]::-ms-track {
        width: 100%;
        height: 11.4px;
        cursor: pointer;
        background: transparent;
        border-color: transparent;
        color: transparent;
    }
    input[type=range]::-ms-fill-lower {
        background: #000000;
        border: 0.2px solid #010101;
        border-radius: 2.6px;
        box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
    }
    input[type=range]::-ms-fill-upper {
        background: #000000;
        border: 0.2px solid #010101;
        border-radius: 2.6px;
        box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
    }
    input[type=range]::-ms-thumb {
        box-shadow: 0.9px 0.9px 1px #000031, 0px 0px 0.9px #00004b;
        border: 1.8px solid #00001e;
        height: 26px;
        width: 26px;
        border-radius: 15px;
        background: #ffffff;
        cursor: pointer;
        height: 11.4px;
    }
    input[type=range]:focus::-ms-fill-lower {
        background: #000000;
    }
    input[type=range]:focus::-ms-fill-upper {
        background: #0d0d0d;
    }
</style>