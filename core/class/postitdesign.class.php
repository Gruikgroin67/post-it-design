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
        $rotate = intval($this->cfg('postit_rotate', 0));
        $targetPlanHeaderId = intval($this->cfg('target_planHeader_id', 0));
        $targetX = intval($this->cfg('target_x', 100));
        $targetY = intval($this->cfg('target_y', 100));
        $visualStyle = $this->cfg('visual_style', 'classic');

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            $color = '#fff475';
        }

        if ($width < 120) { $width = 220; }
        if ($width > 900) { $width = 900; }

        if ($height < 80) { $height = 160; }
        if ($height > 700) { $height = 700; }

        if ($rotate < -15) { $rotate = -15; }
        if ($rotate > 15) { $rotate = 15; }

        if ($targetX < 0) { $targetX = 0; }
        if ($targetY < 0) { $targetY = 0; }
        if ($targetX > 5000) { $targetX = 5000; }
        if ($targetY > 5000) { $targetY = 5000; }

        if (!in_array($visualStyle, array('classic', 'paper', 'tape'), true)) {
            $visualStyle = 'classic';
        }

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
            . 'pointer-events:auto !important;'
            . 'position:fixed !important;'
            . 'left:' . $targetX . 'px !important;'
            . 'top:' . $targetY . 'px !important;'
            . 'z-index:9999 !important;';

        $visualBackground = 'background:' . $color . ' !important;background-color:' . $color . ' !important;';
        $visualShadow = 'box-shadow:0 10px 24px rgba(0,0,0,.24) !important;';
        $visualBorder = 'border:1px solid rgba(120,95,15,.18) !important;';
        $visualTexture = 'background-image:radial-gradient(rgba(255,255,255,.22) .6px, transparent .8px) !important;background-size:7px 7px !important;';
        $visualLines = '';
        $visualFont = 'font-family:"Trebuchet MS",Arial,sans-serif !important;';
        $visualFold = '<div style="position:absolute;top:0;right:0;width:0;height:0;border-top:22px solid rgba(255,255,255,.86);border-left:22px solid transparent;filter:drop-shadow(-1px 1px 1px rgba(0,0,0,.13));pointer-events:none;"></div>';
        $visualTape = '';

        if ($visualStyle == 'paper') {
            $visualLines = 'background-image:repeating-linear-gradient(to bottom, rgba(0,0,0,0) 0px, rgba(0,0,0,0) 23px, rgba(80,70,40,.10) 24px) !important;';
            $visualTexture = '';
            $visualShadow = 'box-shadow:0 9px 20px rgba(0,0,0,.22) !important;';
            $visualFont = 'font-family:Verdana,Arial,sans-serif !important;';
        }

        if ($visualStyle == 'tape') {
            $visualShadow = 'box-shadow:0 13px 28px rgba(0,0,0,.28) !important;';
            $visualTape = '<div style="position:absolute;top:-11px;left:50%;transform:translateX(-50%) rotate(-2deg);width:46px;height:18px;background:rgba(250,250,235,.72);border-left:1px solid rgba(255,255,255,.45);border-right:1px solid rgba(200,200,180,.45);box-shadow:0 1px 2px rgba(0,0,0,.16);pointer-events:none;"></div>';
        }

        $noteStyle = ''
            . 'display:block !important;'
            . 'position:relative !important;'
            . 'box-sizing:border-box !important;'
            . 'width:' . $width . 'px !important;'
            . 'min-width:' . $width . 'px !important;'
            . 'max-width:' . $width . 'px !important;'
            . 'min-height:' . $height . 'px !important;'
            . $visualBackground
            . 'padding:14px 16px !important;'
            . 'border-radius:5px !important;'
            . $visualBorder
            . $visualShadow
            . 'color:#2b2b2b !important;'
            . $visualFont
            . 'overflow:visible !important;'
            . 'pointer-events:auto !important;'
            . 'cursor:move !important;'
            . 'touch-action:none !important;'
            . 'user-select:none !important;'
            . 'transform:rotate(' . $rotate . 'deg) !important;'
            . 'transform-origin:center center !important;'
            . $visualTexture
            . $visualLines;

        $titleStyle = ''
            . 'display:block !important;'
            . 'background:transparent !important;'
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
            . 'gap:4px !important;'
            . 'justify-content:flex-start !important;'
            . 'align-items:center !important;'
            . 'flex-wrap:wrap !important;'
            . 'margin-top:10px !important;'
            . 'padding-top:7px !important;'
            . 'border-top:1px solid rgba(0,0,0,.12) !important;'
            . 'background:transparent !important;'
            . 'pointer-events:auto !important;';

        $btnStyle = ''
            . 'display:inline-block !important;'
            . 'font-size:12px !important;'
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
            . 'pointer-events:auto !important;'
            . 'touch-action:manipulation !important;'
            . 'user-select:none !important;'
            . 'white-space:nowrap !important;';

        $newBtnStyle = $btnStyle . 'background:#3cae45 !important;';
        $rotateBtnStyle = $btnStyle . 'background:#f0ad4e !important;';
        $deleteBtnStyle = $btnStyle . 'background:#d9534f !important;';

        $toggleOptionsJs = "event.stopPropagation();"
            . "if(this.__postitMovedUntil && Date.now()<this.__postitMovedUntil){return false;}"
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

        $dragJs = "var note=this;"
            . "var ev=event||window.event;"
            . "if(ev.target && ev.target.closest && ev.target.closest('button,a,input,textarea,select')){return true;}"
            . "var widget=note.closest('.postitdesign-widget');"
            . "if(!widget){return true;}"
            . "var eqId=widget.getAttribute('data-eqLogic_id');"
            . "var planHeaderId=(new URLSearchParams(window.location.search)).get('plan_id')||widget.getAttribute('data-target-planheader')||'';"
            . "var moveEl=widget;"
            . "var status=widget.querySelector('.postitdesign-status-force');"
            . "var touchOpt={capture:true,passive:false};"
            . "function point(e){"
            . "if(e.touches && e.touches.length){return {x:e.touches[0].clientX,y:e.touches[0].clientY};}"
            . "if(e.changedTouches && e.changedTouches.length){return {x:e.changedTouches[0].clientX,y:e.changedTouches[0].clientY};}"
            . "return {x:e.clientX||0,y:e.clientY||0};"
            . "}"
            . "var p0=point(ev);"
            . "var startX=p0.x;"
            . "var startY=p0.y;"
            . "var startLeft=parseInt(moveEl.style.left||moveEl.offsetLeft||0,10)||0;"
            . "var startTop=parseInt(moveEl.style.top||moveEl.offsetTop||0,10)||0;"
            . "var lastX=startLeft;"
            . "var lastY=startTop;"
            . "var moved=false;"
            . "function clamp(n,min,max){n=parseInt(n,10);if(isNaN(n)){n=min;}return Math.max(min,Math.min(max,n));}"
            . "function savePos(x,y){"
            . "var body=new URLSearchParams();"
            . "body.append('action','savePositionFromDesign');"
            . "body.append('eqLogic_id',eqId);"
            . "body.append('planHeader_id',planHeaderId);"
            . "body.append('x',x);"
            . "body.append('y',y);"
            . "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()})"
            . ".then(function(r){return r.json();})"
            . ".then(function(d){if(d.state==='ok'){if(status){status.style.setProperty('display','block','important');status.textContent='OK position X='+x+', Y='+y;}}else{alert(d.result||'Erreur position');}})"
            . ".catch(function(e){alert(e.message||e);});"
            . "}"
            . "function move(e){"
            . "var p=point(e);"
            . "var dx=p.x-startX;"
            . "var dy=p.y-startY;"
            . "if(Math.abs(dx)<1 && Math.abs(dy)<1){return;}"
            . "moved=true;"
            . "var maxX=Math.max(0,(window.innerWidth||document.documentElement.clientWidth||5000)-moveEl.offsetWidth);"
            . "var maxY=Math.max(0,(window.innerHeight||document.documentElement.clientHeight||5000)-moveEl.offsetHeight);"
            . "lastX=clamp(startLeft+dx,0,maxX);"
            . "lastY=clamp(startTop+dy,0,maxY);"
            . "moveEl.style.left=lastX+'px';"
            . "moveEl.style.top=lastY+'px';"
            . "if(status){status.style.setProperty('display','block','important');status.textContent='Déplacement...';}"
            . "if(e.cancelable){e.preventDefault();}"
            . "e.stopPropagation();"
            . "}"
            . "function up(e){"
            . "document.removeEventListener('pointermove',move,true);"
            . "document.removeEventListener('pointerup',up,true);"
            . "document.removeEventListener('pointercancel',up,true);"
            . "document.removeEventListener('mousemove',move,true);"
            . "document.removeEventListener('mouseup',up,true);"
            . "document.removeEventListener('touchmove',move,touchOpt);"
            . "document.removeEventListener('touchend',up,true);"
            . "document.removeEventListener('touchcancel',up,true);"
            . "try{if(note.releasePointerCapture && ev.pointerId!==undefined){note.releasePointerCapture(ev.pointerId);}}catch(err){}"
            . "if(moved){"
            . "note.__postitMovedUntil=Date.now()+500;"
            . "savePos(lastX,lastY);"
            . "}"
            . "if(e && e.cancelable){e.preventDefault();}"
            . "if(e){e.stopPropagation();}"
            . "}"
            . "document.addEventListener('pointermove',move,true);"
            . "document.addEventListener('pointerup',up,true);"
            . "document.addEventListener('pointercancel',up,true);"
            . "document.addEventListener('mousemove',move,true);"
            . "document.addEventListener('mouseup',up,true);"
            . "document.addEventListener('touchmove',move,touchOpt);"
            . "document.addEventListener('touchend',up,true);"
            . "document.addEventListener('touchcancel',up,true);"
            . "try{if(note.setPointerCapture && ev.pointerId!==undefined){note.setPointerCapture(ev.pointerId);}}catch(err){}"
            . "if(status){status.style.setProperty('display','block','important');status.textContent='Déplacement actif';}"
            . "if(ev.cancelable){ev.preventDefault();}"
            . "ev.stopPropagation();"
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
            . "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()})"
            . ".then(function(r){return r.json();})"
            . ".then(function(d){if(d.state==='ok'){if(msgEl){msgEl.innerHTML=d.result.message_html;}if(st){st.textContent='OK texte ajouté';}}else{alert(d.result||'Erreur ajout texte');if(st){st.textContent='Erreur ajout texte';}}})"
            . ".catch(function(err){alert(err.message||err);if(st){st.textContent='Erreur ajout texte';}});"
            . "return false;";

        $rotateJs = "event.preventDefault();event.stopPropagation();"
            . "var widget=this.closest('.postitdesign-widget');"
            . "if(!widget){return false;}"
            . "var eqId=widget.getAttribute('data-eqLogic_id');"
            . "var note=widget.querySelector('.postitdesign-note-force');"
            . "var st=widget.querySelector('.postitdesign-status-force');"
            . "var current=parseInt(widget.getAttribute('data-rotate')||'0',10);"
            . "if(isNaN(current)){current=0;}"
            . "var angle=current+5;"
            . "if(angle>15){angle=-15;}"
            . "var body=new URLSearchParams();"
            . "body.append('action','saveRotationFromDesign');"
            . "body.append('eqLogic_id',eqId);"
            . "body.append('rotate',angle);"
            . "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()})"
            . ".then(function(r){return r.json();})"
            . ".then(function(d){if(d.state==='ok'){widget.setAttribute('data-rotate', angle);if(note){note.style.setProperty('transform','rotate('+angle+'deg)','important');}if(st){st.style.setProperty('display','block','important');st.textContent='OK rotation '+angle+'°';}}else{alert(d.result||'Erreur rotation');}})"
            . ".catch(function(err){alert(err.message||err);});"
            . "return false;";

        $newJs = "event.preventDefault();event.stopPropagation();"
            . "var widget=this.closest('.postitdesign-widget');"
            . "if(!widget){return false;}"
            . "var st=widget.querySelector('.postitdesign-status-force');"
            . "var p=new URLSearchParams(window.location.search);"
            . "var planHeaderId=p.get('plan_id')||widget.getAttribute('data-target-planheader')||'';"
            . "if(!planHeaderId){alert('Design introuvable. Recharge le Design.');return false;}"
            . "var title=prompt('Titre du nouveau post-it :','Nouveau post-it');"
            . "if(title===null){return false;}"
            . "title=(title||'').trim();if(!title){title='Nouveau post-it';}"
            . "var message=prompt('Message du nouveau post-it :','');"
            . "if(message===null){return false;}"
            . "var color=prompt('Couleur : jaune, vert, rose, bleu ou code #RRGGBB','jaune');"
            . "if(color===null){return false;}"
            . "var moveEl=widget;"
            . "var moveEl=widget;"
            . "var x=(parseInt(moveEl.style.left||moveEl.offsetLeft||0,10)||0)+35;"
            . "var y=(parseInt(moveEl.style.top||moveEl.offsetTop||0,10)||0)+35;"
            . "if(st){st.style.setProperty('display','block','important');st.textContent='Création du post-it...';}"
            . "var body=new URLSearchParams();"
            . "body.append('action','createFromDesign');"
            . "body.append('planHeader_id',planHeaderId);"
            . "body.append('title',title);"
            . "body.append('message',message);"
            . "body.append('color',color);"
            . "body.append('rotate','0');"
            . "body.append('x',x);"
            . "body.append('y',y);"
            . "body.append('width','220');"
            . "body.append('height','160');"
            . "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()})"
            . ".then(function(r){return r.json();})"
            . ".then(function(d){if(d.state==='ok'){if(st){st.textContent='OK nouveau post-it créé';}window.location.reload();}else{alert(d.result||'Erreur création');if(st){st.textContent='Erreur création';}}})"
            . ".catch(function(err){alert(err.message||err);if(st){st.textContent='Erreur création';}});"
            . "return false;";

        $decollerJs = "event.preventDefault();event.stopPropagation();"
            . "var widget=this.closest('.postitdesign-widget');"
            . "if(!widget){return false;}"
            . "if(!confirm('Décoller ce post-it du Design ?')){return false;}"
            . "var p=new URLSearchParams(window.location.search);"
            . "var pid=p.get('plan_id')||widget.getAttribute('data-target-planheader')||'';"
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

        $toggleOptionsJsAttr = htmlspecialchars($toggleOptionsJs, ENT_QUOTES, 'UTF-8');
        $dragJsAttr = htmlspecialchars($dragJs, ENT_QUOTES, 'UTF-8');
        $completeJsAttr = htmlspecialchars($completeJs, ENT_QUOTES, 'UTF-8');
        $rotateJsAttr = htmlspecialchars($rotateJs, ENT_QUOTES, 'UTF-8');
        $newJsAttr = htmlspecialchars($newJs, ENT_QUOTES, 'UTF-8');
        $decollerJsAttr = htmlspecialchars($decollerJs, ENT_QUOTES, 'UTF-8');

        $touchButtonGuardJs = "if(event&&event.type==='touchend'){this.__postitTouchHandled=Date.now();}"
            . "if(event&&event.type==='click'&&this.__postitTouchHandled&&Date.now()-this.__postitTouchHandled<900){event.preventDefault();event.stopPropagation();return false;}";
        $touchButtonDownJs = "event.stopPropagation();";
        $touchButtonGuardJsAttr = htmlspecialchars($touchButtonGuardJs, ENT_QUOTES, 'UTF-8');
        $touchButtonDownJsAttr = htmlspecialchars($touchButtonDownJs, ENT_QUOTES, 'UTF-8');

        $html = '';
        $html .= '<div class="eqLogic-widget eqLogic postitdesign-widget" ';
        $html .= 'data-eqLogic_id="' . $this->getId() . '" ';
        $html .= 'data-target-planheader="' . $targetPlanHeaderId . '" ';
        $html .= 'data-rotate="' . $rotate . '" ';
        $html .= 'data-eqLogic_uid="#uid#" ';
        $html .= 'data-version="' . $_version . '" ';
        $html .= 'style="' . $outerStyle . '">';

        $html .= '<div class="postitdesign-note-force" onpointerdown="' . $dragJsAttr . '" onclick="' . $toggleOptionsJsAttr . '" style="' . $noteStyle . '">';
        $html .= $visualTape;
        $html .= $visualFold;
        $html .= '<div class="postitdesign-title-force" style="' . $titleStyle . '">' . $title . '</div>';
        $html .= '<div class="postitdesign-message-force" style="' . $messageStyle . '">' . $messageHtml . '</div>';

        $html .= '<div class="postitdesign-footer-force" data-open="0" onclick="event.stopPropagation();" style="' . $footerStyle . '">';
        $html .= '<button type="button" onpointerdown="' . $touchButtonDownJsAttr . '" ontouchstart="' . $touchButtonDownJsAttr . '" ontouchend="' . $touchButtonGuardJsAttr . $newJsAttr . '" onclick="' . $touchButtonGuardJsAttr . $newJsAttr . '" style="' . $newBtnStyle . '" title="Créer un nouveau post-it">+</button>';
        $html .= '<button type="button" onpointerdown="' . $touchButtonDownJsAttr . '" ontouchstart="' . $touchButtonDownJsAttr . '" ontouchend="' . $touchButtonGuardJsAttr . $completeJsAttr . '" onclick="' . $touchButtonGuardJsAttr . $completeJsAttr . '" style="' . $btnStyle . '" title="Compléter le post-it">✎</button>';
        $html .= '<button type="button" onpointerdown="' . $touchButtonDownJsAttr . '" ontouchstart="' . $touchButtonDownJsAttr . '" ontouchend="' . $touchButtonGuardJsAttr . $rotateJsAttr . '" onclick="' . $touchButtonGuardJsAttr . $rotateJsAttr . '" style="' . $rotateBtnStyle . '" title="Rotation : appui simple">⟳</button>';
        $html .= '<button type="button" onpointerdown="' . $touchButtonDownJsAttr . '" ontouchstart="' . $touchButtonDownJsAttr . '" ontouchend="' . $touchButtonGuardJsAttr . $decollerJsAttr . '" onclick="' . $touchButtonGuardJsAttr . $decollerJsAttr . '" style="' . $deleteBtnStyle . '" title="Décoller du design">✕</button>';
        $html .= '</div>';

        $html .= '<div class="postitdesign-status-force" style="display:none !important;font-size:10px !important;margin-top:5px !important;color:#555 !important;background:transparent !important;line-height:1.2 !important;word-break:break-word !important;"></div>';

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
