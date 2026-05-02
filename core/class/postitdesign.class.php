<?php

class postitdesign extends eqLogic {
    private function cfg($key, $default = '') {
        $value = $this->getConfiguration($key, $default);
        return ($value === null || $value === '') ? $default : $value;
    }

    private function safeColor($color) {
        $color = trim((string)$color);
        return preg_match('/^#[0-9a-fA-F]{6}$/', $color) ? $color : '#fff475';
    }

    private function safeInt($value, $default, $min, $max) {
        $value = intval($value);
        if ($value === 0 && strval($value) !== strval($this->cfg($value, ''))) {
            $value = $default;
        }
        return max($min, min($max, $value));
    }

    public function toHtml($_version = 'dashboard') {
        $title = htmlspecialchars((string)$this->cfg('postit_title', $this->getName()), ENT_QUOTES, 'UTF-8');
        $message = nl2br(htmlspecialchars((string)$this->cfg('postit_message', ''), ENT_QUOTES, 'UTF-8'));
        $color = $this->safeColor($this->cfg('postit_color', '#fff475'));
        $width = max(120, min(800, intval($this->cfg('postit_width', 220))));
        $height = max(80, min(600, intval($this->cfg('postit_height', 160))));
        $rotate = max(-8, min(8, intval($this->cfg('postit_rotate', 0))));

        $html = '';
        $html .= '<div class="eqLogic-widget eqLogic postitdesign-widget" ';
        $html .= 'data-eqLogic_id="' . intval($this->getId()) . '" ';
        $html .= 'data-eqLogic_uid="#uid#" ';
        $html .= 'data-version="' . htmlspecialchars($_version, ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'style="width:' . $width . 'px;height:' . $height . 'px;">';
        $html .= '<div class="postitdesign-note" style="background:' . $color . ';transform:rotate(' . $rotate . 'deg);">';
        $html .= '<div class="postitdesign-title">' . $title . '</div>';
        $html .= '<div class="postitdesign-message">' . $message . '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}

class postitdesignCmd extends cmd {
}
