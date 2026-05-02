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
        $targetPlanHeaderId = (int) $this->cfg('target_planHeader_id', 0);

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            $color = '#fff475';
        }

        $width = max(120, min(700, $width));
        $height = max(80, min(600, $height));
        $rotate = max(-15, min(15, $rotate));

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
            'text-align:left;',
            'transform:rotate(' . $rotate . 'deg);',
            'transform-origin:center center;',
            'cursor:default;'
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

        $footerStyle = implode('', array(
            'display:flex;',
            'gap:5px;',
            'align-items:center;',
            'justify-content:flex-start;',
            'margin-top:12px;',
            'padding-top:8px;',
            'border-top:1px solid rgba(0,0,0,.14);',
            'background:transparent!important;'
        ));

        $btnBase = implode('', array(
            'display:inline-block;',
            'border:0;',
            'border-radius:4px;',
            'padding:5px 7px;',
            'font-size:12px;',
            'font-weight:700;',
            'line-height:1;',
            'cursor:pointer;',
            'color:#fff;',
            'text-decoration:none;',
            'font-family:Arial,sans-serif;'
        ));

        $moveBtnStyle = $btnBase . 'background:#2f80ed;';
        $editBtnStyle = $btnBase . 'background:#3cae45;';
        $rotateBtnStyle = $btnBase . 'background:#f0ad4e;';
        $deleteBtnStyle = $btnBase . 'background:#d9534f;';

        $statusStyle = 'display:none;margin-top:7px;font-size:11px;font-weight:700;color:#222;background:rgba(255,255,255,.55);padding:4px 6px;border-radius:4px;';

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

        $eqId = (int) $this->getId();

        $moveJs = "(function(btn){" .
            "var widget=btn.closest('.postitdesign-widget');if(!widget){return false;}" .
            "var p=new URLSearchParams(window.location.search);var planId=p.get('plan_id')||widget.getAttribute('data-target-planheader')||'';" .
            "if(!planId){alert('Design introuvable. Recharge le Design.');return false;}" .
            "var target=widget;var node=widget.parentElement;" .
            "for(var i=0;i<7&&node;i++){var cs=window.getComputedStyle(node);if(cs.position==='absolute' || node.getAttribute('data-plan_id') || node.className.toString().indexOf('plan')>=0){target=node;break;}node=node.parentElement;}" .
            "var st=widget.querySelector('.postitdesign-status-force');" .
            "if(st){st.style.display='block';st.textContent='Déplacement actif : bouge la souris puis relâche';}" .
            "var startX=event.clientX,startY=event.clientY;" .
            "var baseLeft=parseInt(target.style.left||target.offsetLeft||0,10)||0;" .
            "var baseTop=parseInt(target.style.top||target.offsetTop||0,10)||0;" .
            "function move(ev){var x=baseLeft+(ev.clientX-startX);var y=baseTop+(ev.clientY-startY);if(x<0){x=0;}if(y<0){y=0;}target.style.left=x+'px';target.style.top=y+'px';}" .
            "function up(ev){document.removeEventListener('mousemove',move,true);document.removeEventListener('mouseup',up,true);var x=parseInt(target.style.left||target.offsetLeft||0,10)||0;var y=parseInt(target.style.top||target.offsetTop||0,10)||0;var body=new URLSearchParams();body.append('action','savePositionFromDesign');body.append('eqLogic_id','" . $eqId . "');body.append('planHeader_id',planId);body.append('x',x);body.append('y',y);fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()}).then(function(r){return r.json();}).then(function(d){if(d.state==='ok'){if(st){st.textContent='Position sauvegardée X='+x+' Y='+y;}}else{alert(d.result||'Erreur sauvegarde position');if(st){st.textContent='Erreur sauvegarde';}}}).catch(function(e){alert(e.message||e);if(st){st.textContent='Erreur sauvegarde';}});}" .
            "document.addEventListener('mousemove',move,true);document.addEventListener('mouseup',up,true);" .
            "return false;" .
            "})(this);return false;";

        $editJs = "(function(btn){" .
            "var widget=btn.closest('.postitdesign-widget');if(!widget){return false;}" .
            "var text=prompt('Texte à ajouter au post-it :','');if(text===null){return false;}text=(text||'').trim();if(!text){return false;}" .
            "var st=widget.querySelector('.postitdesign-status-force');" .
            "var body=new URLSearchParams();body.append('action','completeFromDesign');body.append('eqLogic_id','" . $eqId . "');body.append('text',text);" .
            "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()}).then(function(r){return r.json();}).then(function(d){if(d.state==='ok'){var msg=widget.querySelector('.postitdesign-message');if(msg){msg.innerHTML=d.result.message_html;}if(st){st.style.display='block';st.textContent='Message sauvegardé';}}else{alert(d.result||'Erreur sauvegarde message');}}).catch(function(e){alert(e.message||e);});" .
            "return false;" .
            "})(this);return false;";

        $rotateJs = "(function(btn){" .
            "var widget=btn.closest('.postitdesign-widget');if(!widget){return false;}" .
            "var note=widget.querySelector('.postitdesign-note');var current=parseInt(widget.getAttribute('data-rotate')||'0',10)||0;var next=current+3;if(next>15){next=-15;}" .
            "var st=widget.querySelector('.postitdesign-status-force');" .
            "var body=new URLSearchParams();body.append('action','saveRotationFromDesign');body.append('eqLogic_id','" . $eqId . "');body.append('rotate',next);" .
            "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()}).then(function(r){return r.json();}).then(function(d){if(d.state==='ok'){widget.setAttribute('data-rotate',next);if(note){note.style.transform='rotate('+next+'deg)';}if(st){st.style.display='block';st.textContent='Rotation '+next+'° sauvegardée';}}else{alert(d.result||'Erreur rotation');}}).catch(function(e){alert(e.message||e);});" .
            "return false;" .
            "})(this);return false;";

        $deleteJs = "(function(btn){" .
            "var widget=btn.closest('.postitdesign-widget');if(!widget){return false;}" .
            "if(!confirm('Décoller ce post-it du Design ?')){return false;}" .
            "var p=new URLSearchParams(window.location.search);var planId=p.get('plan_id')||widget.getAttribute('data-target-planheader')||'';" .
            "if(!planId){alert('Design introuvable. Recharge le Design.');return false;}" .
            "var body=new URLSearchParams();body.append('action','removeFromDesign');body.append('eqLogic_id','" . $eqId . "');body.append('planHeader_id',planId);" .
            "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()}).then(function(r){return r.json();}).then(function(d){if(d.state==='ok'){window.location.reload();}else{alert(d.result||'Erreur décollage');}}).catch(function(e){alert(e.message||e);});" .
            "return false;" .
            "})(this);return false;";

        $moveJsAttr = htmlspecialchars($moveJs, ENT_QUOTES, 'UTF-8');
        $editJsAttr = htmlspecialchars($editJs, ENT_QUOTES, 'UTF-8');
        $rotateJsAttr = htmlspecialchars($rotateJs, ENT_QUOTES, 'UTF-8');
        $deleteJsAttr = htmlspecialchars($deleteJs, ENT_QUOTES, 'UTF-8');

        $html = '';
        $html .= '<div class="eqLogic-widget eqLogic postitdesign-widget postitdesign-style-' . $visualStyle . '" ';
        $html .= 'data-eqLogic_id="' . $eqId . '" ';
        $html .= 'data-eqLogic_uid="#uid#" ';
        $html .= 'data-eqType="postitdesign" ';
        $html .= 'data-version="' . $_version . '" ';
        $html .= 'data-target-planheader="' . $targetPlanHeaderId . '" ';
        $html .= 'data-rotate="' . $rotate . '" ';
        $html .= 'style="' . $outerStyle . '">';
        $html .= '<div class="postitdesign-note" style="' . $noteStyle . '">';
        if ($visualStyle === 'tape') {
            $html .= '<div class="postitdesign-tape" aria-hidden="true" style="' . $tapeStyle . '"></div>';
        }
        $html .= '<div class="postitdesign-title" style="' . $titleStyle . '">' . $title . '</div>';
        $html .= '<div class="postitdesign-message" style="' . $messageStyle . '">' . nl2br($message, false) . '</div>';
        $html .= '<div class="postitdesign-footer" style="' . $footerStyle . '">';
        $html .= '<button type="button" class="postitdesign-move-btn" style="' . $moveBtnStyle . '" onclick="' . $moveJsAttr . '">↕</button>';
        $html .= '<button type="button" class="postitdesign-edit-btn" style="' . $editBtnStyle . '" onclick="' . $editJsAttr . '">✎</button>';
        $html .= '<button type="button" class="postitdesign-rotate-btn" style="' . $rotateBtnStyle . '" onclick="' . $rotateJsAttr . '">⟳</button>';
        $html .= '<button type="button" class="postitdesign-delete-btn" style="' . $deleteBtnStyle . '" onclick="' . $deleteJsAttr . '">✕</button>';
        $html .= '</div>';
        $html .= '<div class="postitdesign-status-force" style="' . $statusStyle . '"></div>';
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
