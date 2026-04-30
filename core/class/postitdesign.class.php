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

    public function toHtml($_version = 'dashboard') {
        $_version = jeedom::versionAlias($_version);
        $replace = $this->preToHtml($_version);
        if (!is_array($replace)) {
            return $replace;
        }

        $title = self::cleanText($this->cfg('postit_title', $this->getName()));
        $message = $this->cfg('postit_message', 'Nouveau post-it');
        $color = $this->cfg('postit_color', '#fff475');
        $width = intval($this->cfg('postit_width', 220));
        $height = intval($this->cfg('postit_height', 160));
        $rotate = intval($this->cfg('postit_rotate', -1));

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            $color = '#fff475';
        }

        if ($width < 120) { $width = 120; }
        if ($width > 600) { $width = 600; }
        if ($height < 80) { $height = 80; }
        if ($height > 600) { $height = 600; }
        if ($rotate < -8) { $rotate = -8; }
        if ($rotate > 8) { $rotate = 8; }

        $messageHtml = nl2br(self::cleanText($message));

        $html = '';
        $html .= '<div class="eqLogic-widget eqLogic postitdesign-widget allowResize allowReorderCmd" ';
        $html .= 'data-eqLogic_id="' . $this->getId() . '" ';
        $html .= 'data-eqLogic_uid="#uid#" ';
        $html .= 'data-version="' . $_version . '" ';
        $html .= 'style="width:' . $width . 'px; min-height:' . $height . 'px; background:' . $color . '; padding:14px 16px; border-radius:4px; box-shadow:0 8px 18px rgba(0,0,0,.28); transform:rotate(' . $rotate . 'deg); color:#2b2b2b; font-family:Arial, sans-serif; overflow:hidden;">';

        $html .= '<div class="widget-name" style="font-weight:700; font-size:16px; line-height:1.2; margin-bottom:10px; border-bottom:1px solid rgba(0,0,0,.18); padding-bottom:6px;">';
        $html .= $title;
        $html .= '</div>';

        $html .= '<div class="postitdesign-message" style="font-size:15px; line-height:1.35; white-space:normal; word-wrap:break-word;">';
        $html .= $messageHtml;
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
