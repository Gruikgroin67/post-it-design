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
        $targetPlanHeaderId = intval($this->cfg('target_planHeader_id', 0));

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
            . 'transform-origin:center center !important;'
            . 'pointer-events:auto !important;';

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
            . 'overflow:hidden !important;'
            . 'pointer-events:auto !important;'
            . 'cursor:pointer !important;';

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

        $footerStyle = ''
            . 'display:none !important;'
            . 'gap:6px !important;'
            . 'justify-content:flex-end !important;'
            . 'align-items:center !important;'
            . 'margin-top:12px !important;'
            . 'padding-top:7px !important;'
            . 'border-top:1px solid rgba(0,0,0,.12) !important;'
            . 'background:transparent !important;'
            . 'pointer-events:auto !important;';

        $btnStyle = ''
            . 'display:inline-block !important;'
            . 'font-size:11px !important;'
            . 'font-weight:700 !important;'
            . 'line-height:1 !important;'
            . 'padding:6px 7px !important;'
            . 'border-radius:4px !important;'
            . 'border:0 !important;'
            . 'text-decoration:none !important;'
            . 'cursor:pointer !important;'
            . 'background:#2f80ed !important;'
            . 'color:#ffffff !important;'
            . 'font-family:Arial, sans-serif !important;'
            . 'pointer-events:auto !important;';

        $placerBtnStyle = $btnStyle . 'background:#555 !important;';
        $deleteBtnStyle = $btnStyle . 'background:#d9534f !important;';

        $placerUrl = '/plugins/postitdesign/postitdesign_placer.php?id=' . $this->getId();

        $toggleOptionsJs = "event.stopPropagation();"
            . "var f=this.querySelector('.postitdesign-footer-force');"
            . "var st=this.querySelector('.postitdesign-status-force');"
            . "if(!f){return false;}"
            . "var isOpen=f.getAttribute('data-open')==='1';"
            . "if(isOpen){"
            . "f.setAttribute('data-open','0');"
            . "f.style.setProperty('display','none','important');"
            . "if(st){st.style.setProperty('display','none','important');}"
            . "}else{"
            . "f.setAttribute('data-open','1');"
            . "f.style.setProperty('display','flex','important');"
            . "if(st){st.style.setProperty('display','block','important');st.textContent='Options du post-it';}"
            . "}"
            . "return false;";

        $toggleOptionsJsAttr = htmlspecialchars($toggleOptionsJs, ENT_QUOTES, 'UTF-8');

        $directMoveJs = "event.preventDefault();event.stopPropagation();"
            . "(function(btn){"
            . "var widget=btn.closest('.postitdesign-widget');"
            . "if(!widget){return false;}"
            . "var note=widget.querySelector('.postitdesign-note-force');"
            . "var status=widget.querySelector('.postitdesign-status-force');"
            . "var eqId=widget.getAttribute('data-eqLogic_id');"
            . "var p=new URLSearchParams(window.location.search);"
            . "var planHeaderId=p.get('plan_id')||widget.getAttribute('data-target-planheader')||'';"
            . "var moveEl=widget;"
            . "var parent=widget.parentElement;"
            . "for(var i=0;i<6 && parent && parent!==document.body;i++){"
            . "var cs=window.getComputedStyle(parent);"
            . "if(cs.position==='absolute'||parent.style.left||parent.style.top||parent.getAttribute('data-plan_id')){moveEl=parent;break;}"
            . "parent=parent.parentElement;"
            . "}"
            . "widget.style.setProperty('pointer-events','auto','important');"
            . "note.style.setProperty('pointer-events','auto','important');"
            . "note.style.outline='2px dashed #2f80ed';"
            . "note.style.cursor='move';"
            . "btn.textContent='Déplacement actif';"
            . "if(status){status.textContent='Déplace le post-it, puis relâche la souris.';}"
            . "var active=false,sx=0,sy=0,sl=0,st=0;"
            . "function clamp(n,min,max){n=parseInt(n,10);if(isNaN(n)){n=min;}return Math.max(min,Math.min(max,n));}"
            . "function down(e){active=true;sx=e.clientX;sy=e.clientY;sl=parseInt(moveEl.style.left||moveEl.offsetLeft||0,10)||0;st=parseInt(moveEl.style.top||moveEl.offsetTop||0,10)||0;try{note.setPointerCapture(e.pointerId);}catch(err){}e.preventDefault();e.stopPropagation();}"
            . "function move(e){if(!active){return;}var dx=e.clientX-sx;var dy=e.clientY-sy;var box=moveEl.offsetParent||moveEl.parentElement;var maxX=box?Math.max(0,box.clientWidth-moveEl.offsetWidth):5000;var maxY=box?Math.max(0,box.clientHeight-moveEl.offsetHeight):5000;var nx=clamp(Math.round(sl+dx),0,maxX);var ny=clamp(Math.round(st+dy),0,maxY);moveEl.style.left=nx+'px';moveEl.style.top=ny+'px';e.preventDefault();e.stopPropagation();}"
            . "function up(e){if(!active){return;}active=false;var x=parseInt(moveEl.style.left||moveEl.offsetLeft||0,10)||0;var y=parseInt(moveEl.style.top||moveEl.offsetTop||0,10)||0;try{note.releasePointerCapture(e.pointerId);}catch(err){}note.removeEventListener('pointerdown',down);note.removeEventListener('pointermove',move);note.removeEventListener('pointerup',up);note.removeEventListener('pointercancel',up);widget.style.setProperty('pointer-events','none','important');note.style.setProperty('pointer-events','none','important');note.style.outline='';note.style.cursor='default';btn.textContent='↔ Déplacer direct';if(status){status.textContent='Sauvegarde position...';}"
            . "var body=new URLSearchParams();body.append('action','savePositionFromDesign');body.append('eqLogic_id',eqId);body.append('planHeader_id',planHeaderId);body.append('x',x);body.append('y',y);"
            . "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()})"
            . ".then(function(r){return r.json();}).then(function(d){if(d.state==='ok'){if(status){status.textContent='OK position enregistrée X='+x+', Y='+y;}}else{alert(d.result||'Erreur sauvegarde position');if(status){status.textContent='Erreur sauvegarde';}}})"
            . ".catch(function(err){alert(err.message||err);if(status){status.textContent='Erreur sauvegarde';}});"
            . "e.preventDefault();e.stopPropagation();}"
            . "note.addEventListener('pointerdown',down);note.addEventListener('pointermove',move);note.addEventListener('pointerup',up);note.addEventListener('pointercancel',up);"
            . "})(this);return false;";

        $decollerJs = "event.preventDefault();"
            . "event.stopPropagation();"
            . "if(!confirm('Décoller ce post-it du Design ?')){return false;}"
            . "var p=new URLSearchParams(window.location.search);"
            . "var pid=p.get('plan_id')||'';"
            . "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{"
            . "method:'POST',"
            . "credentials:'same-origin',"
            . "headers:{'Content-Type':'application/x-www-form-urlencoded'},"
            . "body:'action=removeFromDesign&eqLogic_id=" . $this->getId() . "&planHeader_id='+encodeURIComponent(pid)"
            . "})"
            . ".then(function(r){return r.json();})"
            . ".then(function(d){if(d.state==='ok'){window.location.reload();}else{alert(d.result||'Erreur');}})"
            . ".catch(function(e){alert(e.message||e);});"
            . "return false;";

        $completeJs = "event.preventDefault();event.stopPropagation();"
            . "var widget=this.closest('.postitdesign-widget');"
            . "if(!widget){return false;}"
            . "var eqId=widget.getAttribute('data-eqLogic_id');"
            . "var msgEl=widget.querySelector('.postitdesign-message-force');"
            . "var st=widget.querySelector('.postitdesign-status-force');"
            . "var txt=prompt('Texte à ajouter au post-it :');"
            . "if(txt===null){return false;}"
            . "txt=(txt||'').trim();"
            . "if(!txt){return false;}"
            . "if(st){st.style.setProperty('display','block','important');st.textContent='Ajout du texte...';}"
            . "var body=new URLSearchParams();"
            . "body.append('action','completeFromDesign');"
            . "body.append('eqLogic_id',eqId);"
            . "body.append('text',txt);"
            . "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{"
            . "method:'POST',"
            . "credentials:'same-origin',"
            . "headers:{'Content-Type':'application/x-www-form-urlencoded'},"
            . "body:body.toString()"
            . "})"
            . ".then(function(r){return r.json();})"
            . ".then(function(d){"
            . "if(d.state==='ok'){"
            . "if(msgEl){msgEl.innerHTML=d.result.message_html;}"
            . "if(st){st.textContent='OK texte ajouté';}"
            . "}else{alert(d.result||'Erreur ajout texte');if(st){st.textContent='Erreur ajout texte';}}"
            . "})"
            . ".catch(function(err){alert(err.message||err);if(st){st.textContent='Erreur ajout texte';}});"
            . "return false;";

        $completeJsAttr = htmlspecialchars($completeJs, ENT_QUOTES, 'UTF-8');

        $directMoveJsAttr = htmlspecialchars($directMoveJs, ENT_QUOTES, 'UTF-8');
        $decollerJsAttr = htmlspecialchars($decollerJs, ENT_QUOTES, 'UTF-8');

        $html = '';
        $html .= '<div class="eqLogic-widget eqLogic allowResize allowReorderCmd postitdesign-widget" ';
        $html .= 'data-eqLogic_id="' . $this->getId() . '" ';
        $html .= 'data-target-planheader="' . $targetPlanHeaderId . '" ';
        $html .= 'data-eqLogic_uid="#uid#" ';
        $html .= 'data-version="' . $_version . '" ';
        $html .= 'style="' . $outerStyle . '">';

        $html .= '<div class="postitdesign-note-force" onclick="' . $toggleOptionsJsAttr . '" style="' . $noteStyle . '">';
        $html .= '<div class="postitdesign-title-force" style="' . $titleStyle . '">' . $title . '</div>';
        $html .= '<div class="postitdesign-message-force" style="' . $messageStyle . '">' . $messageHtml . '</div>';

        $html .= '<div class="postitdesign-footer-force" data-open="0" onclick="event.stopPropagation();" style="' . $footerStyle . '">';
        $html .= '<button type="button" onclick="' . $directMoveJsAttr . '" style="' . $btnStyle . '">↔ Déplacer direct</button>';
        $html .= '<button type="button" onclick="' . $completeJsAttr . '" style="' . $btnStyle . '">✎ Compléter</button>';
        $html .= '<a href="' . $placerUrl . '" target="_blank" onclick="event.stopPropagation();" style="' . $placerBtnStyle . '">🧭 Page</a>';
        $html .= '<button type="button" onclick="' . $decollerJsAttr . '" style="' . $deleteBtnStyle . '">✕ Décoller</button>';
        $html .= '</div>';

        $html .= '<div class="postitdesign-status-force" style="display:none !important;font-size:10px !important;margin-top:5px !important;color:#555 !important;background:transparent !important;"></div>';

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
