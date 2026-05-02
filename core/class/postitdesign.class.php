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
            $this->setConfiguration('postit_width', 220);
        }
        if ($this->getConfiguration('postit_height', '') === '') {
            $this->setConfiguration('postit_height', 160);
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
        $width = (int) $this->cfg('postit_width', 220);
        $height = (int) $this->cfg('postit_height', 160);
        $rotate = (int) $this->cfg('postit_rotate', -1);
        $visualStyle = (string) $this->cfg('visual_style', 'classic');

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            $color = '#fff475';
        }

        $width = max(120, min(600, $width));
        $height = max(80, min(500, $height));
        $rotate = max(-10, min(10, $rotate));

        if (!in_array($visualStyle, array('classic', 'paper', 'tape'), true)) {
            $visualStyle = 'classic';
        }

        $style = implode('', array(
            'width:' . $width . 'px;',
            'min-height:' . $height . 'px;',
            '--postitdesign-bg:' . $color . ';',
            '--postitdesign-rotate:' . $rotate . 'deg;'
        ));

        $html = '';
        $html .= '<div class="eqLogic-widget eqLogic postitdesign-widget postitdesign-style-' . $visualStyle . '" ';
        $html .= 'data-eqLogic_id="' . $this->getId() . '" ';
        $html .= 'data-eqLogic_uid="#uid#" ';
        $html .= 'data-eqType="postitdesign" ';
        $html .= 'data-version="' . $_version . '" ';
        $html .= 'style="' . $style . '">';
        $html .= '<div class="postitdesign-note">';
        if ($visualStyle === 'tape') {
            $html .= '<div class="postitdesign-tape" aria-hidden="true"></div>';
        }
        $html .= '<div class="postitdesign-title">' . $title . '</div>';
        $html .= '<div class="postitdesign-message">' . nl2br($message, false) . '</div>';
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
