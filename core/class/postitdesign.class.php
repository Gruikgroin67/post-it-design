<?php

require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class postitdesign extends eqLogic {

    private static function cleanText($_value) {
        return htmlspecialchars((string) $_value, ENT_QUOTES, 'UTF-8');
    }

    private function cfg($_key, $_default = '') {
        $value = $this->getConfiguration($_key);
        if ($value === '' || $value === null) {
            return $_default;
        }
        return $value;
    }

    public function preSave() {
        if ($this->getConfiguration('postit_color', '') == '') {
            $this->setConfiguration('postit_color', '#fff475');
        }
        if ($this->getConfiguration('postit_width', '') == '') {
            $this->setConfiguration('postit_width', 220);
        }
        if ($this->getConfiguration('postit_height', '') == '') {
            $this->setConfiguration('postit_height', 160);
        }
        if ($this->getConfiguration('postit_rotate', '') == '') {
            $this->setConfiguration('postit_rotate', -1);
        }
    }

    public function toHtml($_version = 'dashboard') {
        $_version = jeedom::versionAlias($_version);

        $replace = $this->preToHtml($_version);
        if (!is_array($replace)) {
            return $replace;
        }

        $title = self::cleanText($this->cfg('postit_title', $this->getName()));
        $message = self::cleanText($this->cfg('postit_message', 'Nouveau post-it'));

        $color = $this->cfg('postit_color', '#fff475');
        $width = intval($this->cfg('postit_width', 220));
        $height = intval($this->cfg('postit_height', 160));
        $rotate = intval($this->cfg('postit_rotate', -1));

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            $color = '#fff475';
        }

        if ($width < 120) { $width = 220; }
        if ($width > 900) { $width = 900; }

        if ($height < 80) { $height = 160; }
        if ($height > 700) { $height = 700; }

        if ($rotate < -15) { $rotate = -15; }
        if ($rotate > 15) { $rotate = 15; }

        $messageHtml = nl2br($message);

        $outerStyle = ''
            . 'width:' . $width . 'px !important;'
            . 'min-width:' . $width . 'px !important;'
            . 'max-width:' . $width . 'px !important;'
            . 'min-height:' . $height . 'px !important;'
            . 'background:transparent !important;'
            . 'background-color:transparent !important;'
            . 'border:0 !important;'
            . 'box-shadow:none !important;'
            . 'padding:0 !important;'
            . 'margin:0 !important;'
            . 'overflow:visible !important;'
            . 'transform:rotate(' . $rotate . 'deg) !important;'
            . 'transform-origin:center center !important;';

        $noteStyle = ''
            . 'display:block !important;'
            . 'box-sizing:border-box !important;'
            . 'width:' . $width . 'px !important;'
            . 'min-width:' . $width . 'px !important;'
            . 'max-width:' . $width . 'px !important;'
            . 'min-height:' . $height . 'px !important;'
            . 'background:' . $color . ' !important;'
            . 'background-color:' . $color . ' !important;'
            . 'padding:14px 16px !important;'
            . 'border-radius:4px !important;'
            . 'box-shadow:0 8px 18px rgba(0,0,0,.28) !important;'
            . 'color:#2b2b2b !important;'
            . 'font-family:Arial, sans-serif !important;'
            . 'overflow:hidden !important;';

        $titleStyle = ''
            . 'display:block !important;'
            . 'background:transparent !important;'
            . 'background-color:transparent !important;'
            . 'color:#2b2b2b !important;'
            . 'font-weight:700 !important;'
            . 'font-size:16px !important;'
            . 'line-height:1.2 !important;'
            . 'margin:0 0 10px 0 !important;'
            . 'border:0 !important;'
            . 'border-bottom:1px solid rgba(0,0,0,.18) !important;'
            . 'padding:0 0 6px 0 !important;';

        $messageStyle = ''
            . 'display:block !important;'
            . 'background:transparent !important;'
            . 'background-color:transparent !important;'
            . 'color:#2b2b2b !important;'
            . 'font-size:15px !important;'
            . 'line-height:1.35 !important;'
            . 'white-space:normal !important;'
            . 'word-wrap:break-word !important;'
            . 'margin:0 !important;'
            . 'padding:0 !important;'
            . 'border:0 !important;';

        $html = '';
        $html .= '<div class="eqLogic-widget eqLogic allowResize allowReorderCmd postitdesign-widget" ';
        $html .= 'data-eqLogic_id="' . $this->getId() . '" ';
        $html .= 'data-eqLogic_uid="#uid#" ';
        $html .= 'data-version="' . $_version . '" ';
        $html .= 'style="' . $outerStyle . '">';

        $html .= '<div class="postitdesign-note-force" style="' . $noteStyle . '">';
        $html .= '<div class="postitdesign-title-force" style="' . $titleStyle . '">' . $title . '</div>';
        $html .= '<div class="postitdesign-message-force" style="' . $messageStyle . '">' . $messageHtml . '</div>';
        $html .= '</div>';

        $html .= '</div>';

        return $this->postToHtml($_version, $html);
    }
}

class postitdesignCmd extends cmd {
    public function execute($_options = array()) {
        return null;
    }
}
