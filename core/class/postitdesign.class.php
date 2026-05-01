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
            . 'overflow:visible !important;'
            . 'pointer-events:auto !important;'
            . 'cursor:pointer !important;'
            . 'touch-action:none !important;';

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
            . 'gap:4px !important;'
            . 'justify-content:flex-start !important;'
            . 'align-items:center !important;'
            . 'flex-wrap:wrap !important;'
            . 'margin-top:10px !important;'
            . 'padding-top:7px !important;'
            . 'border-top:1px solid rgba(0,0,0,.12) !important;'
            . 'background:transparent !important;'
            . 'pointer-events:auto !important;'
            . 'max-width:100% !important;'
            . 'overflow:visible !important;';

        $btnStyle = ''
            . 'display:inline-block !important;'
            . 'font-size:10px !important;'
            . 'font-weight:700 !important;'
            . 'line-height:1 !important;'
            . 'padding:5px 6px !important;'
            . 'border-radius:4px !important;'
            . 'border:0 !important;'
            . 'text-decoration:none !important;'
            . 'cursor:pointer !important;'
            . 'background:#2f80ed !important;'
            . 'color:#ffffff !important;'
            . 'font-family:Arial, sans-serif !important;'
            . 'pointer-events:auto !important;'
            . 'white-space:nowrap !important;'
            . 'max-width:100% !important;';

        $newBtnStyle = $btnStyle . 'background:#3cae45 !important;';
        $rotateBtnStyle = $btnStyle . 'background:#f0ad4e !important;';
        $deleteBtnStyle = $btnStyle . 'background:#d9534f !important;';

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

        $autoDragJs = "event.stopPropagation();"
            . "var note=this;"
            . "var widget=note.closest('.postitdesign-widget');"
            . "if(!widget){return false;}"
            . "if(event.target && event.target.closest && event.target.closest('button,a,input,textarea,select')){return true;}"
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
            . "if(!widget.__postitGesture){"
            . "widget.__postitGesture={pointers:{},mode:null,moved:false,rotated:false,startX:0,startY:0,startLeft:0,startTop:0,startAngle:0,startRotate:parseInt(widget.getAttribute('data-rotate')||'0',10)||0,currentRotate:parseInt(widget.getAttribute('data-rotate')||'0',10)||0,moveBound:null,upBound:null};"
            . "}"
            . "var g=widget.__postitGesture;"
            . "g.pointers[event.pointerId]={x:event.clientX,y:event.clientY};"
            . "function pointerList(){var a=[];for(var k in g.pointers){a.push(g.pointers[k]);}return a;}"
            . "function clamp(n,min,max){n=parseInt(n,10);if(isNaN(n)){n=min;}return Math.max(min,Math.min(max,n));}"
            . "function angle(a,b){return Math.atan2(b.y-a.y,b.x-a.x)*180/Math.PI;}"
            . "function savePosition(x,y){"
            . "if(status){status.style.setProperty('display','block','important');status.textContent='Sauvegarde position...';}"
            . "var body=new URLSearchParams();"
            . "body.append('action','savePositionFromDesign');"
            . "body.append('eqLogic_id',eqId);"
            . "body.append('planHeader_id',planHeaderId);"
            . "body.append('x',x);"
            . "body.append('y',y);"
            . "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()})"
            . ".then(function(r){return r.json();})"
            . ".then(function(d){if(d.state==='ok'){if(status){status.textContent='OK position X='+x+', Y='+y;}}else{alert(d.result||'Erreur sauvegarde position');if(status){status.textContent='Erreur sauvegarde';}}})"
            . ".catch(function(err){alert(err.message||err);if(status){status.textContent='Erreur sauvegarde';}});"
            . "}"
            . "function saveRotation(rot){"
            . "if(status){status.style.setProperty('display','block','important');status.textContent='Sauvegarde rotation...';}"
            . "var body=new URLSearchParams();"
            . "body.append('action','saveRotationFromDesign');"
            . "body.append('eqLogic_id',eqId);"
            . "body.append('rotate',rot);"
            . "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()})"
            . ".then(function(r){return r.json();})"
            . ".then(function(d){if(d.state==='ok'){widget.setAttribute('data-rotate',rot);if(status){status.textContent='OK rotation '+rot+'°';}}else{alert(d.result||'Erreur rotation');if(status){status.textContent='Erreur rotation';}}})"
            . ".catch(function(err){alert(err.message||err);if(status){status.textContent='Erreur rotation';}});"
            . "}"
            . "function toggleOptions(){"
            . "var f=note.querySelector('.postitdesign-footer-force');"
            . "var stt=note.querySelector('.postitdesign-status-force');"
            . "if(!f){return;}"
            . "var isOpen=f.getAttribute('data-open')==='1';"
            . "if(isOpen){f.setAttribute('data-open','0');f.style.setProperty('display','none','important');if(stt){stt.style.setProperty('display','none','important');}}"
            . "else{f.setAttribute('data-open','1');f.style.setProperty('display','flex','important');if(stt){stt.style.setProperty('display','block','important');stt.textContent='Options du post-it';}}"
            . "}"
            . "function beginDrag(e){"
            . "g.mode='drag';"
            . "g.moved=false;"
            . "g.startX=e.clientX;"
            . "g.startY=e.clientY;"
            . "g.startLeft=parseInt(moveEl.style.left||moveEl.offsetLeft||0,10)||0;"
            . "g.startTop=parseInt(moveEl.style.top||moveEl.offsetTop||0,10)||0;"
            . "}"
            . "function beginRotate(){"
            . "var pts=pointerList();"
            . "if(pts.length<2){return;}"
            . "g.mode='rotate';"
            . "g.rotated=false;"
            . "g.startAngle=angle(pts[0],pts[1]);"
            . "g.startRotate=parseInt(widget.getAttribute('data-rotate')||'0',10)||0;"
            . "g.currentRotate=g.startRotate;"
            . "note.style.outline='2px dashed #f0ad4e';"
            . "if(status){status.style.setProperty('display','block','important');status.textContent='Rotation à deux doigts...';}"
            . "}"
            . "function move(e){"
            . "if(!g.pointers[e.pointerId]){return;}"
            . "g.pointers[e.pointerId]={x:e.clientX,y:e.clientY};"
            . "var pts=pointerList();"
            . "if(pts.length>=2){"
            . "if(g.mode!=='rotate'){beginRotate();}"
            . "var delta=angle(pts[0],pts[1])-g.startAngle;"
            . "var rot=clamp(Math.round(g.startRotate+delta),-15,15);"
            . "g.currentRotate=rot;"
            . "g.rotated=true;"
            . "widget.style.setProperty('transform','rotate('+rot+'deg)','important');"
            . "if(status){status.textContent='Rotation '+rot+'°';}"
            . "e.preventDefault();e.stopPropagation();return;"
            . "}"
            . "if(g.mode!=='drag'){return;}"
            . "var dx=e.clientX-g.startX;var dy=e.clientY-g.startY;"
            . "if(Math.abs(dx)<4 && Math.abs(dy)<4){return;}"
            . "g.moved=true;"
            . "var box=moveEl.offsetParent||moveEl.parentElement;"
            . "var maxX=box?Math.max(0,box.clientWidth-moveEl.offsetWidth):5000;"
            . "var maxY=box?Math.max(0,box.clientHeight-moveEl.offsetHeight):5000;"
            . "var nx=clamp(Math.round(g.startLeft+dx),0,maxX);"
            . "var ny=clamp(Math.round(g.startTop+dy),0,maxY);"
            . "moveEl.style.left=nx+'px';"
            . "moveEl.style.top=ny+'px';"
            . "note.style.outline='2px dashed #2f80ed';"
            . "note.style.cursor='move';"
            . "if(status){status.style.setProperty('display','block','important');status.textContent='Déplacement...';}"
            . "e.preventDefault();e.stopPropagation();"
            . "}"
            . "function up(e){"
            . "delete g.pointers[e.pointerId];"
            . "var pts=pointerList();"
            . "if(g.mode==='rotate' && pts.length<2){"
            . "note.style.outline='';"
            . "note.style.cursor='pointer';"
            . "if(g.rotated){saveRotation(g.currentRotate);}"
            . "g.mode=null;g.rotated=false;"
            . "}"
            . "if(g.mode==='drag' && pts.length===0){"
            . "note.style.outline='';"
            . "note.style.cursor='pointer';"
            . "if(g.moved){"
            . "var x=parseInt(moveEl.style.left||moveEl.offsetLeft||0,10)||0;"
            . "var y=parseInt(moveEl.style.top||moveEl.offsetTop||0,10)||0;"
            . "savePosition(x,y);"
            . "}else{toggleOptions();}"
            . "g.mode=null;g.moved=false;"
            . "}"
            . "if(pts.length===0){"
            . "document.removeEventListener('pointermove',g.moveBound,true);"
            . "document.removeEventListener('pointerup',g.upBound,true);"
            . "document.removeEventListener('pointercancel',g.upBound,true);"
            . "widget.__postitGesture=null;"
            . "}"
            . "try{note.releasePointerCapture(e.pointerId);}catch(err){}"
            . "e.preventDefault();e.stopPropagation();"
            . "}"
            . "if(!g.moveBound){g.moveBound=move;g.upBound=up;document.addEventListener('pointermove',g.moveBound,true);document.addEventListener('pointerup',g.upBound,true);document.addEventListener('pointercancel',g.upBound,true);}"
            . "var ptsNow=pointerList();"
            . "if(ptsNow.length>=2){beginRotate();}"
            . "else if(!g.mode){beginDrag(event);}"
            . "try{note.setPointerCapture(event.pointerId);}catch(err){}"
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
            . "var st=widget.querySelector('.postitdesign-status-force');"
            . "var current=parseInt(widget.getAttribute('data-rotate')||'0',10);"
            . "var angle=prompt('Rotation du post-it (-15 à 15 degrés) :', current);"
            . "if(angle===null){return false;}"
            . "angle=parseInt(angle,10);"
            . "if(isNaN(angle)){angle=0;}"
            . "if(angle<-15){angle=-15;}if(angle>15){angle=15;}"
            . "if(st){st.style.setProperty('display','block','important');st.textContent='Sauvegarde rotation...';}"
            . "var body=new URLSearchParams();"
            . "body.append('action','saveRotationFromDesign');"
            . "body.append('eqLogic_id',eqId);"
            . "body.append('rotate',angle);"
            . "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()})"
            . ".then(function(r){return r.json();})"
            . ".then(function(d){if(d.state==='ok'){widget.setAttribute('data-rotate', angle);widget.style.setProperty('transform','rotate('+angle+'deg)','important');if(st){st.textContent='OK rotation '+angle+'°';}}else{alert(d.result||'Erreur rotation');if(st){st.textContent='Erreur rotation';}}})"
            . ".catch(function(err){alert(err.message||err);if(st){st.textContent='Erreur rotation';}});"
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
            . "var rotate=prompt('Rotation (-15 à 15 degrés) :','-2');"
            . "if(rotate===null){return false;}"
            . "var moveEl=widget;var parent=widget.parentElement;"
            . "for(var i=0;i<6 && parent && parent!==document.body;i++){var cs=window.getComputedStyle(parent);if(cs.position==='absolute'||parent.style.left||parent.style.top||parent.getAttribute('data-plan_id')){moveEl=parent;break;}parent=parent.parentElement;}"
            . "var x=(parseInt(moveEl.style.left||moveEl.offsetLeft||0,10)||0)+35;"
            . "var y=(parseInt(moveEl.style.top||moveEl.offsetTop||0,10)||0)+35;"
            . "if(st){st.style.setProperty('display','block','important');st.textContent='Création du post-it...';}"
            . "var body=new URLSearchParams();"
            . "body.append('action','createFromDesign');"
            . "body.append('planHeader_id',planHeaderId);"
            . "body.append('title',title);"
            . "body.append('message',message);"
            . "body.append('color',color);"
            . "body.append('rotate',rotate);"
            . "body.append('x',x);"
            . "body.append('y',y);"
            . "body.append('width','220');"
            . "body.append('height','160');"
            . "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()})"
            . ".then(function(r){return r.json();})"
            . ".then(function(d){if(d.state==='ok'){if(st){st.textContent='OK nouveau post-it créé';}window.location.reload();}else{alert(d.result||'Erreur création');if(st){st.textContent='Erreur création';}}})"
            . ".catch(function(err){alert(err.message||err);if(st){st.textContent='Erreur création';}});"
            . "return false;";

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

        $toggleOptionsJsAttr = htmlspecialchars($toggleOptionsJs, ENT_QUOTES, 'UTF-8');
        $autoDragJsAttr = htmlspecialchars($autoDragJs, ENT_QUOTES, 'UTF-8');
        $completeJsAttr = htmlspecialchars($completeJs, ENT_QUOTES, 'UTF-8');
        $rotateJsAttr = htmlspecialchars($rotateJs, ENT_QUOTES, 'UTF-8');
        $newJsAttr = htmlspecialchars($newJs, ENT_QUOTES, 'UTF-8');
        $decollerJsAttr = htmlspecialchars($decollerJs, ENT_QUOTES, 'UTF-8');

        $html = '';
        $html .= '<div class="eqLogic-widget eqLogic allowResize allowReorderCmd postitdesign-widget" ';
        $html .= 'data-eqLogic_id="' . $this->getId() . '" ';
        $html .= 'data-target-planheader="' . $targetPlanHeaderId . '" ';
        $html .= 'data-rotate="' . $rotate . '" ';
        $html .= 'data-eqLogic_uid="#uid#" ';
        $html .= 'data-version="' . $_version . '" ';
        $html .= 'style="' . $outerStyle . '">';

        $html .= '<div class="postitdesign-note-force" onpointerdown="' . $autoDragJsAttr . '" style="' . $noteStyle . '">';
        $html .= '<div class="postitdesign-title-force" style="' . $titleStyle . '">' . $title . '</div>';
        $html .= '<div class="postitdesign-message-force" style="' . $messageStyle . '">' . $messageHtml . '</div>';

        $html .= '<div class="postitdesign-footer-force" data-open="0" onclick="event.stopPropagation();" style="' . $footerStyle . '">';
        $html .= '<button type="button" onclick="' . $newJsAttr . '" style="' . $newBtnStyle . '" title="Créer un nouveau post-it sur ce Design">+</button>';
        $html .= '<button type="button" onclick="' . $completeJsAttr . '" style="' . $btnStyle . '" title="Compléter le post-it">✎</button>';
        $html .= '<button type="button" onclick="' . $rotateJsAttr . '" style="' . $rotateBtnStyle . '" title="Changer la rotation">⟳</button>';
        $html .= '<button type="button" onclick="' . $decollerJsAttr . '" style="' . $deleteBtnStyle . '" title="Décoller du design">✕</button>';
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
