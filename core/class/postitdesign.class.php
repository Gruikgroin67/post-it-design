<?php

class postitdesign extends eqLogic
{
    private static function cleanText($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    private function cfg($key, $default = '')
    {
        $value = $this->getConfiguration($key, $default);
        if ($value === '' || $value === null) {
            return $default;
        }
        return $value;
    }

    public function preSave()
    {
        if ($this->getConfiguration('postit_title', '') === '') {
            $this->setConfiguration('postit_title', $this->getName());
        }
        if ($this->getConfiguration('postit_message', '') === '') {
            $this->setConfiguration('postit_message', 'Nouveau post-it');
        }
        if ($this->getConfiguration('postit_color', '') === '') {
            $this->setConfiguration('postit_color', '#fff475');
        }
        if ($this->getConfiguration('postit_width', '') === '') {
            $this->setConfiguration('postit_width', 240);
        }
        if ($this->getConfiguration('postit_height', '') === '') {
            $this->setConfiguration('postit_height', 170);
        }
        if ($this->getConfiguration('postit_rotate', '') === '') {
            $this->setConfiguration('postit_rotate', -1);
        }
        if ($this->getConfiguration('visual_style', '') === '') {
            $this->setConfiguration('visual_style', 'classic');
        }
    }

    public function toHtml($_version = 'dashboard')
    {
        $_version = jeedom::versionAlias($_version);
        $replace = $this->preToHtml($_version);
        if (!is_array($replace)) {
            return $replace;
        }

        $title = self::cleanText($this->cfg('postit_title', $this->getName()));
        $message = self::cleanText($this->cfg('postit_message', 'Nouveau post-it'));
        $color = (string) $this->cfg('postit_color', '#fff475');
        $width = (int) $this->cfg('postit_width', 240);
        $height = (int) $this->cfg('postit_height', 170);
        $rotate = (int) $this->cfg('postit_rotate', -1);
        $visualStyle = (string) $this->cfg('visual_style', 'classic');

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            $color = '#fff475';
        }

        $width = max(120, min(700, $width));
        $height = max(80, min(600, $height));
        $rotate = max(-10, min(10, $rotate));

        if (!in_array($visualStyle, array('classic', 'paper', 'tape'), true)) {
            $visualStyle = 'classic';
        }

        $outerStyle = implode('', array(
            'box-sizing:border-box;',
            'display:inline-block;',
            'position:relative;',
            'width:' . $width . 'px;',
            'min-height:' . $height . 'px;',
            'max-width:100%;',
            'margin:0;',
            'padding:0;',
            'background:transparent!important;',
            'border:0!important;',
            'box-shadow:none!important;',
            'overflow:visible!important;',
            'transform:rotate(' . $rotate . 'deg);',
            'transform-origin:center center;',
            'font-family:Trebuchet MS,Arial,sans-serif;',
            'pointer-events:auto;'
        ));

        $noteStyle = implode('', array(
            'box-sizing:border-box;',
            'display:block;',
            'position:relative;',
            'width:100%;',
            'min-height:' . $height . 'px;',
            'padding:16px 18px;',
            'background:' . $color . '!important;',
            'border:1px solid rgba(120,95,15,.25);',
            'border-radius:6px;',
            'box-shadow:0 10px 22px rgba(0,0,0,.28);',
            'overflow:hidden;',
            'color:#262626;',
            'text-align:left;'
        ));

        if ($visualStyle === 'paper') {
            $noteStyle .= 'background-image:repeating-linear-gradient(to bottom,rgba(0,0,0,0) 0,rgba(0,0,0,0) 23px,rgba(80,70,40,.12) 24px)!important;';
        }

        if ($visualStyle === 'tape') {
            $noteStyle .= 'padding-top:28px;';
        }

        $titleStyle = implode('', array(
            'display:block;',
            'margin:0 0 10px 0;',
            'padding:0 0 7px 0;',
            'border-bottom:1px solid rgba(0,0,0,.20);',
            'font-size:17px;',
            'font-weight:700;',
            'line-height:1.2;',
            'color:#262626;',
            'background:transparent!important;',
            'white-space:normal;',
            'word-wrap:break-word;'
        ));

        $messageStyle = implode('', array(
            'display:block;',
            'margin:0;',
            'padding:0;',
            'font-size:15px;',
            'font-weight:400;',
            'line-height:1.35;',
            'color:#262626;',
            'background:transparent!important;',
            'white-space:normal;',
            'word-wrap:break-word;'
        ));

        $tapeStyle = implode('', array(
            'position:absolute;',
            'top:7px;',
            'left:50%;',
            'width:78px;',
            'height:18px;',
            'margin-left:-39px;',
            'background:rgba(255,255,255,.55);',
            'border-radius:2px;',
            'box-shadow:0 1px 4px rgba(0,0,0,.16);'
        ));

        $html = '';
        $html .= '<div class="eqLogic-widget eqLogic postitdesign-widget postitdesign-style-' . $visualStyle . '" ';
        $html .= 'data-eqLogic_id="' . $this->getId() . '" ';
        $html .= 'data-eqLogic_uid="#uid#" ';
        $html .= 'data-eqType="postitdesign" ';
        $html .= 'data-version="' . $_version . '" ';
        $html .= 'style="' . $outerStyle . '">';
        $html .= '<div class="postitdesign-note" style="' . $noteStyle . '">';
        if ($visualStyle === 'tape') {
            $html .= '<div class="postitdesign-tape" aria-hidden="true" style="' . $tapeStyle . '"></div>';
        }
        $html .= '<div class="postitdesign-title" style="' . $titleStyle . '">' . $title . '</div>';
        $html .= '<div class="postitdesign-message" style="' . $messageStyle . '">' . nl2br($message, false) . '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $this->postToHtml($_version, $html);
    }
}

class postitdesignCmd extends cmd
{
    public function execute($_options = array())
    {
        return null;
    }
}
