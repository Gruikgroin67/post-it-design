<?php

require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class postitdesign extends eqLogic
{
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
            $this->setConfiguration('postit_color', '#fff4a8');
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
        if ($this->getConfiguration('target_x', '') === '') {
            $this->setConfiguration('target_x', 40);
        }
        if ($this->getConfiguration('target_y', '') === '') {
            $this->setConfiguration('target_y', 40);
        }
    }
    /* POSTITDESIGN_NATIVE_CREATE_CMD_V1 */
    public static function createPostitForPlan($_planHeaderId = 0)
    {
        $_planHeaderId = intval($_planHeaderId);
        if ($_planHeaderId <= 0) {
            throw new Exception('{{Design invalide}}');
        }

        $planHeader = planHeader::byId($_planHeaderId);
        if (!is_object($planHeader)) {
            throw new Exception('{{Design introuvable}}');
        }

        $eq = new postitdesign();
        $eq->setEqType_name('postitdesign');
        $eq->setName('Nouveau post-it ' . date('His'));
        $eq->setIsEnable(1);
        $eq->setIsVisible(1);
        $eq->setConfiguration('postit_title', 'Nouveau post-it');
        $eq->setConfiguration('postit_message', 'Nouveau post-it');
        $eq->setConfiguration('postit_color', '#fff4a8');
        $eq->setConfiguration('postit_width', 220);
        $eq->setConfiguration('postit_height', 160);
        $eq->setConfiguration('postit_rotate', rand(-3, 3));
        $eq->setConfiguration('target_planHeader_id', $_planHeaderId);
        $eq->setConfiguration('target_x', 40);
        $eq->setConfiguration('target_y', 40);
        $eq->setConfiguration('createtime', date('Y-m-d H:i:s'));
        $eq->setConfiguration('updatetime', date('Y-m-d H:i:s'));
        $eq->save();

        $plan = new plan();
        $plan->setPlanHeader_id($_planHeaderId);
        $plan->setLink_type('eqLogic');
        $plan->setLink_id($eq->getId());
        $plan->setPosition('left', 40);
        $plan->setPosition('top', 40);
        $plan->setPosition('width', 220);
        $plan->setPosition('height', 160);
        $plan->setDisplay('name', 0);
        $plan->setDisplay('width', 220);
        $plan->setDisplay('height', 160);
        $plan->setCss('z-index', 1000);
        $plan->save();

        return array(
            'eqLogic_id' => $eq->getId(),
            'plan_id' => $plan->getId(),
            'planHeader_id' => $_planHeaderId
        );
    }

    public static function ensureCreateCommandForPlan($_planHeaderId)
    {
        $_planHeaderId = intval($_planHeaderId);
        if ($_planHeaderId <= 0) {
            throw new Exception('{{Design invalide}}');
        }

        $planHeader = planHeader::byId($_planHeaderId);
        if (!is_object($planHeader)) {
            throw new Exception('{{Design introuvable}}');
        }

        $logicalId = 'postitdesign_create_controller_' . $_planHeaderId;
        $eq = self::byLogicalId($logicalId, 'postitdesign');

        if (!is_object($eq)) {
            $eq = new postitdesign();
            $eq->setEqType_name('postitdesign');
            $eq->setLogicalId($logicalId);
            $eq->setName('+ Post-it - ' . $planHeader->getName());
            $eq->setIsEnable(1);
            $eq->setIsVisible(1);
        }

        $eq->setConfiguration('is_create_controller', 1);
        $eq->setConfiguration('target_planHeader_id', $_planHeaderId);
        $eq->save();

        $cmd = $eq->getCmd(null, 'create_postit');
        if (!is_object($cmd)) {
            $cmd = new postitdesignCmd();
            $cmd->setEqLogic_id($eq->getId());
            $cmd->setEqType('postitdesign');
            $cmd->setLogicalId('create_postit');
            $cmd->setName('+ Post-it');
            $cmd->setType('action');
            $cmd->setSubType('other');
            $cmd->setIsVisible(1);
            $cmd->save();
        }

        $plan = plan::byLinkTypeLinkIdPlanHeaderId('cmd', $cmd->getId(), $_planHeaderId);
        if (!is_object($plan)) {
            $plan = new plan();
            $plan->setPlanHeader_id($_planHeaderId);
            $plan->setLink_type('cmd');
            $plan->setLink_id($cmd->getId());
            $plan->setPosition('left', 20);
            $plan->setPosition('top', 20);
            $plan->setDisplay('name', 1);
            $plan->setCss('z-index', 1100);
            $plan->save();
        }

        return array(
            'eqLogic_id' => $eq->getId(),
            'cmd_id' => $cmd->getId(),
            'plan_id' => $plan->getId(),
            'planHeader_id' => $_planHeaderId
        );
    }



    public function toHtml($_version = 'dashboard')
    {
        
        if (intval($this->getConfiguration('is_create_controller', 0)) === 1) { return parent::toHtml($_version); } /* POSTITDESIGN_CREATE_CONTROLLER_TOHTML_GUARD_V1 */
$title = htmlspecialchars((string)$this->cfg('postit_title', $this->getName()), ENT_QUOTES, 'UTF-8');
        $message = (string)$this->cfg('postit_message', 'Nouveau post-it');
        $color = preg_replace('/[^#a-zA-Z0-9(),.%\s-]/', '', (string)$this->cfg('postit_color', '#fff4a8'));

        $width = intval($this->cfg('postit_width', 220));
        $height = intval($this->cfg('postit_height', 160));
        if ($width < 120) { $width = 220; }
        if ($width > 900) { $width = 900; }
        if ($height < 80) { $height = 160; }
        if ($height > 700) { $height = 700; }

        $rotate = intval($this->cfg('postit_rotate', -1));
        $visualStyle = strtolower(trim(strval($this->cfg('visual_style', 'classic')))); /* POSTITDESIGN_DESIGN_VISUAL_STYLE_V1 */
        if (!in_array($visualStyle, array('classic', 'paper', 'tape'), true)) {
            $visualStyle = 'classic';
        }
        if ($rotate < -9) { $rotate = -9; }
        if ($rotate > 9) { $rotate = 9; }

        $targetPlanHeaderId = intval($this->cfg('target_planHeader_id', 0));
        if (function_exists('init')) {
            $fromUrl = intval(init('plan_id', init('id', init('planHeader_id', 0))));
            if ($fromUrl > 0) {
                $targetPlanHeaderId = $fromUrl;
            }
        }

        $targetX = intval($this->cfg('target_x', 40));
        $targetY = intval($this->cfg('target_y', 40));

        if ($targetPlanHeaderId > 0 && class_exists('plan')) {
            try {
                $plan = plan::byLinkTypeLinkIdPlanHeaderId('eqLogic', $this->getId(), $targetPlanHeaderId);
                if (is_object($plan) && method_exists($plan, 'getPosition')) {
                    $px = $plan->getPosition('left');
                    $py = $plan->getPosition('top');
                    if ($px !== '' && $px !== null) { $targetX = intval($px); }
                    if ($py !== '' && $py !== null) { $targetY = intval($py); }
                }
            } catch (Exception $e) {
            }
        }

        if ($targetX < 0) { $targetX = 0; }
        if ($targetY < 0) { $targetY = 0; }

        $strikeRaw = (string)$this->cfg('postit_strikes', ''); $syncRev = sha1($title . "\n" . $message . "\n" . $strikeRaw . "\n" . $rotate); /* POSTITDESIGN_SYNC_REV_ATTR_V1 */
        $strikeIndexes = array();
        foreach (explode(',', $strikeRaw) as $strikePart) {
            $strikePart = trim($strikePart);
            if ($strikePart !== '' && ctype_digit($strikePart)) {
                $strikeIndexes[] = intval($strikePart);
            }
        }
        $strikeIndexes = array_values(array_unique($strikeIndexes));

        $messageLines = preg_split('/\r\n|\n|\r/', $message);
        if (!is_array($messageLines) || count($messageLines) === 0) {
            $messageLines = array('');
        }

        $messageHtml = '';
        foreach ($messageLines as $lineIndex => $lineText) {
            $isStruck = in_array(intval($lineIndex), $strikeIndexes, true);
            $lineSafe = htmlspecialchars($lineText, ENT_QUOTES, 'UTF-8');
            $lineStyle = ''
                . 'display:block !important;'
                . 'min-height:18px !important;'
                . 'padding:1px 2px !important;'
                . 'margin:0 !important;'
                . 'cursor:pointer !important;'
                . 'border-radius:3px !important;'
                . 'text-decoration:' . ($isStruck ? 'line-through' : 'none') . ' !important;'
                . 'opacity:' . ($isStruck ? '.55' : '1') . ' !important;';
            $messageHtml .= '<div class="postitdesign-line-force" data-line-index="' . intval($lineIndex) . '" data-struck="' . ($isStruck ? '1' : '0') . '" ontouchend="return postitdesignTabletRunClickV4(this,event);" style="' . $lineStyle . '">' . ($lineSafe === '' ? '&nbsp;' : $lineSafe) . '</div>'; /* POSTITDESIGN_LINE_TOUCH_TABLET_V1 */
        }
        /* POSTITDESIGN_HANDLES_LINE_STRIKE_V1 */

        $outerStyle = ''
            . 'width:' . $width . 'px !important;'
            . 'min-width:' . $width . 'px !important;'
            . 'max-width:' . $width . 'px !important;'
            . 'min-height:' . $height . 'px !important;'
            . 'background:transparent !important;'
            . 'border:0 !important;'
            . 'box-shadow:none !important;'
            . 'padding:0 !important;'
            . 'margin:0 !important;'
            . 'overflow:visible !important;'
            . 'pointer-events:auto !important;'
            . 'position:fixed !important;'
            . 'left:' . $targetX . 'px !important;'
            . 'top:' . $targetY . 'px !important;'
            . 'z-index:9999 !important;'
            . 'transform:none !important;';

        $noteStyle = ''
            . 'display:block !important;'
            . 'position:relative !important;'
            . 'box-sizing:border-box !important;'
            . 'width:' . $width . 'px !important;'
            . 'min-width:' . $width . 'px !important;'
            . 'max-width:' . $width . 'px !important;'
            . 'min-height:' . $height . 'px !important;'
            . 'background:' . $color . ' !important;'
            . 'background-color:' . $color . ' !important;'
            . 'padding:14px 16px !important;'
            . 'border-radius:5px !important;'
            . 'border:1px solid rgba(120,95,15,.18) !important;'
            . 'box-shadow:0 10px 24px rgba(0,0,0,.24) !important;'
            . 'color:#2b2b2b !important;'
            . 'font-family:Arial,sans-serif !important;'
            . 'overflow:visible !important;'
            . 'pointer-events:auto !important;'
            . 'user-select:none !important;'
            . 'touch-action:none !important;'
            . 'background-image:radial-gradient(rgba(255,255,255,.22) .6px, transparent .8px) !important;'
            . 'background-size:7px 7px !important;'
            . 'transform:rotate(' . $rotate . 'deg) !important;'
            . 'transform-origin:center center !important;'; /* POSTITDESIGN_RENDER_SAVED_ROTATE_V1 */

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
            . 'cursor:pointer !important;'
            . 'background:#2f80ed !important;'
            . 'color:#ffffff !important;'
            . 'font-family:Arial,sans-serif !important;'
            . 'pointer-events:auto !important;'
            . 'white-space:nowrap !important;';

        $newBtnStyle = $btnStyle . 'background:#3cae45 !important;';
        $rotateBtnStyle = $btnStyle . 'background:#f0ad4e !important;';
        $deleteBtnStyle = $btnStyle . 'background:#d9534f !important;';

        if ($visualStyle === 'paper') { /* POSTITDESIGN_DESIGN_VISUAL_STYLE_APPLY_V1 */
            $noteStyle .= 'background:' . $color . ' !important;';
            $noteStyle .= 'background-image:repeating-linear-gradient(to bottom, rgba(255,255,255,0) 0px, rgba(255,255,255,0) 22px, rgba(80,70,40,.18) 23px, rgba(80,70,40,.18) 24px) !important;';
            $noteStyle .= 'border-radius:2px !important;';
            $noteStyle .= 'box-shadow:0 7px 16px rgba(0,0,0,.24) !important;';
        } elseif ($visualStyle === 'tape') {
            $noteStyle .= 'background:' . $color . ' !important;';
            $noteStyle .= 'background-image:linear-gradient(to bottom, rgba(255,255,255,.58) 0px, rgba(255,255,255,.25) 24px, rgba(255,255,255,0) 25px), linear-gradient(135deg, rgba(255,255,255,.22), rgba(0,0,0,.04)) !important;';
            $noteStyle .= 'border-top:8px solid rgba(245,230,140,.70) !important;';
            $noteStyle .= 'border-radius:3px !important;';
            $noteStyle .= 'box-shadow:0 9px 18px rgba(0,0,0,.28) !important;';
        } else {
            $noteStyle .= 'background:' . $color . ' !important;';
            $noteStyle .= 'background-image:radial-gradient(rgba(255,255,255,.28) .7px, transparent .9px), linear-gradient(160deg, rgba(255,255,255,.30), rgba(0,0,0,.04)) !important;';
            $noteStyle .= 'background-size:7px 7px, 100% 100% !important;';
            $noteStyle .= 'border-radius:4px !important;';
            $noteStyle .= 'box-shadow:0 6px 14px rgba(0,0,0,.22) !important;';
        }


        $dragHandleStyle = ''
            . 'position:absolute !important;'
            . 'right:4px !important;'
            . 'top:4px !important;'
            . 'width:34px !important;'
            . 'height:34px !important;'
            . 'line-height:34px !important;'
            . 'text-align:center !important;'
            . 'border-radius:50% !important;'
            . 'background:rgba(0,0,0,.22) !important;'
            . 'color:rgba(255,255,255,.92) !important;'
            . 'font-size:13px !important;'
            . 'font-weight:700 !important;'
            . 'cursor:move !important;'
            . 'z-index:99999 !important;'
            . 'user-select:none !important;'
            . 'touch-action:none !important;';

        $optionsHandleStyle = ''
            . 'position:absolute !important;'
            . 'right:4px !important;'
            . 'bottom:4px !important;'
            . 'width:34px !important;'
            . 'height:34px !important;'
            . 'line-height:34px !important;'
            . 'text-align:center !important;'
            . 'border-radius:50% !important;'
            . 'background:rgba(0,0,0,.22) !important;'
            . 'color:rgba(255,255,255,.92) !important;'
            . 'font-size:13px !important;'
            . 'font-weight:700 !important;'
            . 'cursor:pointer !important;'
            . 'z-index:99999 !important;'
            . 'user-select:none !important;'
            . 'touch-action:manipulation !important;';

        $toggleOptionsJs = "var ev=event||window.event;"
            . "ev.preventDefault();ev.stopPropagation();"
            . "var h=this;"
            . "var now=Date.now();"
            . "if(h.__ptToggleLockUntil && now<h.__ptToggleLockUntil){return false;}"
            . "h.__ptToggleLockUntil=now+900;"
            . "var root=(h.closest&&h.closest('.postitdesign-note-force'))||h;"
            . "var f=root.querySelector('.postitdesign-footer-force');"
            . "var st=root.querySelector('.postitdesign-status-force');"
            . "if(!f){return false;}"
            . "var isOpen=(f.getAttribute('data-open')==='1');"
            . "if(isOpen){"
            . "f.setAttribute('data-open','0');"
            . "f.style.setProperty('display','none','important');"
            . "f.style.setProperty('visibility','hidden','important');"
            . "f.style.setProperty('opacity','0','important');"
            . "if(st){st.style.setProperty('display','none','important');st.textContent='';}"
            . "}else{"
            . "f.setAttribute('data-open','1');"
            . "f.style.setProperty('display','flex','important');"
            . "f.style.setProperty('visibility','visible','important');"
            . "f.style.setProperty('opacity','1','important');"
            . "if(st){st.style.setProperty('display','block','important');st.textContent='Options du post-it';}"
            . "}"
            . "/* POSTITDESIGN_OPTIONS_NO_DOUBLE_CLOSE_V2 */"
            . "return false;";

        $dragJs = "var ev=event||window.event;"
            . "ev.preventDefault();ev.stopPropagation();"
            . "var handle=this;"
            . "var widget=handle.closest('.postitdesign-widget');"
            . "if(!widget){return false;}"
            . "var eqId=widget.getAttribute('data-eqLogic_id')||'';"
            . "var planId=(new URLSearchParams(window.location.search)).get('plan_id')||widget.getAttribute('data-target-planheader')||'';"
            . "function point(e){if(e.touches&&e.touches.length){return{x:e.touches[0].clientX,y:e.touches[0].clientY};}if(e.changedTouches&&e.changedTouches.length){return{x:e.changedTouches[0].clientX,y:e.changedTouches[0].clientY};}return{x:e.clientX||0,y:e.clientY||0};}"
            . "function designRect(){var sels=['#div_displayObject','.div_displayObject','#div_designDisplay','.div_designDisplay','#div_pageContainer','.planContainer','.plan-container'];for(var i=0;i<sels.length;i++){var el=document.querySelector(sels[i]);if(el){var r=el.getBoundingClientRect();if(r.width>80&&r.height>80){return r;}}}var parent=widget.parentElement;while(parent&&parent!==document.body){var pr=parent.getBoundingClientRect();if(pr.width>80&&pr.height>80&&pr.width<window.innerWidth*1.05&&pr.height<window.innerHeight*1.05){return pr;}parent=parent.parentElement;}return{left:0,top:0,right:window.innerWidth,bottom:window.innerHeight,width:window.innerWidth,height:window.innerHeight};}"
            . "function clampXY(x,y){var b=designRect();var wr=widget.getBoundingClientRect();var w=Math.max(40,wr.width||widget.offsetWidth||220);var h=Math.max(40,wr.height||widget.offsetHeight||160);var minX=Math.round(b.left);var minY=Math.round(b.top);var maxX=Math.round(b.right-w);var maxY=Math.round(b.bottom-h);if(maxX<minX){maxX=minX;}if(maxY<minY){maxY=minY;}x=Math.round(x);y=Math.round(y);if(x<minX){x=minX;}if(y<minY){y=minY;}if(x>maxX){x=maxX;}if(y>maxY){y=maxY;}return{x:x,y:y};}"
            . "var p0=point(ev);"
            . "var rect=widget.getBoundingClientRect();"
            . "var ox=p0.x-rect.left;"
            . "var oy=p0.y-rect.top;"
            . "function move(e){e.preventDefault();e.stopPropagation();var p=point(e);var c=clampXY(p.x-ox,p.y-oy);widget.style.setProperty('left',c.x+'px','important');widget.style.setProperty('top',c.y+'px','important');}"
            . "function up(e){e.preventDefault();e.stopPropagation();document.removeEventListener('pointermove',move,true);document.removeEventListener('pointerup',up,true);document.removeEventListener('touchmove',move,true);document.removeEventListener('touchend',up,true);var r=widget.getBoundingClientRect();var c=clampXY(r.left,r.top);widget.style.setProperty('left',c.x+'px','important');widget.style.setProperty('top',c.y+'px','important');if(eqId){var body=new URLSearchParams();body.append('action','savePositionFromDesign');body.append('eqLogic_id',eqId);body.append('planHeader_id',planId);body.append('x',String(c.x));body.append('y',String(c.y));fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()}).catch(function(){});}/* POSTITDESIGN_DESIGN_BOUNDS_V1 */return false;}"
            . "document.addEventListener('pointermove',move,true);document.addEventListener('pointerup',up,true);document.addEventListener('touchmove',move,{capture:true,passive:false});document.addEventListener('touchend',up,{capture:true,passive:false});"
            . "return false;";

        $lineClickJs = <<<'POSTITDESIGN_LINE_CLICK_JS'
event.preventDefault();
event.stopPropagation();

var msg=this;
var line=event.target&&event.target.closest?event.target.closest('.postitdesign-line-force'):null;
if(!line){return false;}

var widget=msg.closest('.postitdesign-widget');
if(!widget){return false;}

var eqId=widget.getAttribute('data-eqLogic_id');
var idx=line.getAttribute('data-line-index');
var struck=line.getAttribute('data-struck')==='1' ? 0 : 1;

line.setAttribute('data-struck', String(struck));
line.style.setProperty('text-decoration', struck ? 'line-through' : 'none', 'important');
line.style.setProperty('opacity', struck ? '.55' : '1', 'important');

var st=widget.querySelector('.postitdesign-status-force');
if(st){
  st.style.setProperty('display','block','important');
  st.textContent=struck ? 'Ligne barrée' : 'Ligne réactivée';
}

var body=new URLSearchParams();
body.append('action','toggleStrikeLineFromDesign');
body.append('eqLogic_id',eqId);
body.append('line_index',idx);
body.append('struck',struck ? '1' : '0');

fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{
  method:'POST',
  credentials:'same-origin',
  headers:{'Content-Type':'application/x-www-form-urlencoded'},
  body:body.toString()
}).catch(function(){});

return false;
POSTITDESIGN_LINE_CLICK_JS;

        $rotateJs = "var ev=event||window.event;"
            . "ev.preventDefault();ev.stopPropagation();"
            . "var btn=this;"
            . "var now=Date.now();"
            . "if(btn.__ptRotateLockUntil && now<btn.__ptRotateLockUntil){return false;}"
            . "btn.__ptRotateLockUntil=now+900;"
            . "var widget=btn.closest('.postitdesign-widget');"
            . "if(!widget){return false;}"
            . "var note=widget.querySelector('.postitdesign-note-force');"
            . "var f=widget.querySelector('.postitdesign-footer-force');"
            . "var st=widget.querySelector('.postitdesign-status-force');"
            . "var eqId=widget.getAttribute('data-eqLogic_id')||'';"
            . "var planId=(new URLSearchParams(window.location.search)).get('plan_id')||widget.getAttribute('data-target-planheader')||'0';"
            . "var key='postitdesign.rotate.v2.'+planId+'.'+eqId;"
            . "var angle=parseInt(widget.getAttribute('data-rotate')||'0',10);"
            . "if(isNaN(angle)){angle=0;}"
            . "var next=angle+3;"
            . "if(next>9){next=-9;}"
            . "widget.setAttribute('data-rotate',String(next));"
            . "try{localStorage.setItem(key,String(next));}catch(e){}"
            . "if(note){note.style.setProperty('transform','rotate('+next+'deg)','important');note.style.setProperty('transform-origin','center center','important');}"
            . "if(f){f.setAttribute('data-open','1');f.style.setProperty('display','flex','important');f.style.setProperty('visibility','visible','important');f.style.setProperty('opacity','1','important');}"
            . "if(st){st.style.setProperty('display','block','important');st.textContent='Rotation '+next+'°';}"
            . "if(eqId){var body=new URLSearchParams();body.append('action','saveRotationFromDesign');body.append('eqLogic_id',eqId);body.append('rotate',String(next));fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()}).catch(function(){});}"
            . "/* POSTITDESIGN_ROTATE_LOCAL_RENDER_V2 */"
            . "return false;";

        $completeJs = "var ev=event||window.event;ev.preventDefault();ev.stopPropagation();"
            . "var eqId='" . intval($this->getId()) . "';"
            . "var widget=document.querySelector('.postitdesign-widget[data-eqLogic_id=\"'+eqId+'\"]');"
            . "if(!widget){return false;}"
            . "var note=widget.querySelector('.postitdesign-note-force')||widget;"
            . "var oldBox=note.querySelector('.postitdesign-inline-edit-v2');"
            . "if(oldBox){oldBox.remove();return false;}"
            . "var msgEl=widget.querySelector('.postitdesign-message-force');"
            . "var oldText=msgEl?msgEl.innerText:'';"
            . "var box=document.createElement('div');"
            . "box.className='postitdesign-inline-edit-v2';"
            . "box.style.cssText='position:absolute;left:8px;right:8px;top:42px;bottom:42px;z-index:100002;background:rgba(255,255,255,.98);border:1px solid rgba(0,0,0,.28);border-radius:10px;padding:8px;box-shadow:0 4px 14px rgba(0,0,0,.25);display:flex;flex-direction:column;gap:6px;';"
            . "var ta=document.createElement('textarea');"
            . "ta.value=oldText;"
            . "ta.style.cssText='flex:1;width:100%;resize:none;border:1px solid #aaa;border-radius:8px;padding:8px;font-size:14px;line-height:1.3;color:#111;background:#fff;box-sizing:border-box;';"
            . "var row=document.createElement('div');"
            . "row.style.cssText='display:flex;gap:6px;justify-content:flex-end;';"
            . "var cancel=document.createElement('button');"
            . "cancel.type='button';cancel.textContent='Annuler';"
            . "cancel.style.cssText='padding:7px 10px;border-radius:8px;border:1px solid #999;background:#eee;color:#111;font-size:13px;';"
            . "var save=document.createElement('button');"
            . "save.type='button';save.textContent='Enregistrer';"
            . "save.style.cssText='padding:7px 10px;border-radius:8px;border:1px solid #444;background:#222;color:#fff;font-size:13px;';"
            . "row.appendChild(cancel);row.appendChild(save);box.appendChild(ta);box.appendChild(row);note.appendChild(box);"
            . "function stop(e){e.preventDefault();e.stopPropagation();if(e.stopImmediatePropagation){e.stopImmediatePropagation();}}"
            . "box.addEventListener('touchstart',function(e){e.stopPropagation();},true);box.addEventListener('click',function(e){e.stopPropagation();},false);ta.addEventListener('touchstart',function(e){e.stopPropagation();},true);ta.addEventListener('touchend',function(e){e.stopPropagation();},true);/* POSTITDESIGN_INLINE_EDIT_BUTTONS_FIX_V1 */ /* POSTITDESIGN_CANCEL_MOUSE_CLICK_FIX_V1 */"
            . "cancel.addEventListener('click',function(e){stop(e);box.remove();},true);"
            . "cancel.addEventListener('touchend',function(e){stop(e);box.remove();},true);"
            . "function esc(v){return String(v).replace(/[&<>]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;'}[c];});}"
            . "function renderText(txt){if(!msgEl){return;}var parts=String(txt).split(/\\r?\\n/);var html='';for(var i=0;i<parts.length;i++){html+='<div class=\"postitdesign-line-force\" data-line-index=\"'+i+'\" data-struck=\"0\" style=\"text-decoration:none !important;opacity:1 !important;\">'+(parts[i]===''?'&nbsp;':esc(parts[i]))+'</div>';}msgEl.innerHTML=html;}"
            . "function doSave(e){stop(e);var txt=ta.value;var body=new URLSearchParams();body.append('action','setMessageFromDesign');body.append('eqLogic_id',eqId);body.append('text',txt);fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()}).then(function(){renderText(txt);box.remove();}).catch(function(){box.remove();});}"
            . "save.addEventListener('click',doSave,true);"
            . "save.addEventListener('touchend',doSave,true);"
            . "setTimeout(function(){try{ta.focus();ta.setSelectionRange(ta.value.length,ta.value.length);}catch(e){}},120);"
            . "/* POSTITDESIGN_REAL_INLINE_EDIT_V2 */"
            . "return false;";

        $newJs = "var ev=event||window.event;ev.preventDefault();ev.stopPropagation();"
            . "var widget=this.closest('.postitdesign-widget');"
            . "if(!widget){return false;}"
            . "var parent=widget.parentNode;"
            . "var existing={};"
            . "document.querySelectorAll('.postitdesign-widget').forEach(function(w){var id=w.getAttribute('data-eqLogic_id');if(id){existing[id]=1;}});"
            . "var planId=(new URLSearchParams(window.location.search)).get('plan_id')||widget.getAttribute('data-target-planheader')||'';"
            . "var r=widget.getBoundingClientRect();"
            . "var body=new URLSearchParams();"
            . "body.append('action','createFromDesign');"
            . "body.append('planHeader_id',planId);"
            . "body.append('title','Nouveau post-it');"
            . "body.append('message','Nouveau post-it');"
            . "body.append('x',String(Math.round(r.left+35)));"
            . "body.append('y',String(Math.round(r.top+35)));"
            . "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()})"
            . ".then(function(){return fetch(window.location.href+(window.location.href.indexOf('?')>=0?'&':'?')+'_postit_refresh='+Date.now(),{credentials:'same-origin'});})"
            . ".then(function(resp){return resp.text();})"
            . ".then(function(html){"
            . "var doc=(new DOMParser()).parseFromString(html,'text/html');"
            . "var fresh=doc.querySelectorAll('.postitdesign-widget');"
            . "var found=null;"
            . "for(var i=0;i<fresh.length;i++){var id=fresh[i].getAttribute('data-eqLogic_id');if(id&&!existing[id]){found=fresh[i];break;}}"
            . "if(found&&parent){"
            . "var imported=document.importNode(found,true);"
            . "parent.appendChild(imported);"
            . "}else{"
            . "var st=widget.querySelector('.postitdesign-status-force');if(st){st.style.setProperty('display','block','important');st.textContent='Post-it créé';}"
            . "}"
            . "})"
            . ".catch(function(){var st=widget.querySelector('.postitdesign-status-force');if(st){st.style.setProperty('display','block','important');st.textContent='Post-it créé';}});"
            . "/* POSTITDESIGN_CREATE_NO_RELOAD_V2 */"
            . "return false;";

        $decollerJs = "var ev=event||window.event;ev.preventDefault();ev.stopPropagation();"
            . "var eqId='" . intval($this->getId()) . "';"
            . "var planId=(new URLSearchParams(window.location.search)).get('plan_id')||'" . intval($targetPlanHeaderId) . "';"
            . "var widget=document.querySelector('.postitdesign-widget[data-eqLogic_id=\"'+eqId+'\"]');"
            . "var body=new URLSearchParams();"
            . "body.append('action','removeFromDesign');"
            . "body.append('eqLogic_id',eqId);"
            . "body.append('planHeader_id',planId);"
            . "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()}).then(function(){if(widget){widget.remove();}}).catch(function(){});"
            . "/* POSTITDESIGN_REAL_REMOVE_NO_RELOAD_V2 */"
            . "return false;";

        $toggleOptionsJsAttr = htmlspecialchars($toggleOptionsJs, ENT_QUOTES, 'UTF-8');
        $dragJsAttr = htmlspecialchars($dragJs, ENT_QUOTES, 'UTF-8');
        $lineClickJsAttr = htmlspecialchars($lineClickJs, ENT_QUOTES, 'UTF-8');
        $rotateJsAttr = htmlspecialchars($rotateJs, ENT_QUOTES, 'UTF-8');
        $completeJsAttr = htmlspecialchars($completeJs, ENT_QUOTES, 'UTF-8');
        $newJsAttr = htmlspecialchars($newJs, ENT_QUOTES, 'UTF-8');
        $decollerJsAttr = htmlspecialchars($decollerJs, ENT_QUOTES, 'UTF-8');

        $html = '';
        $html .= '<div class="eqLogic postitdesign-widget postitdesign-saveplan-isolated" '; /* POSTITDESIGN_SAVEPLAN_ISOLATED_V1 */
        $html .= 'data-eqLogic_id="' . $this->getId() . '" ';
        $html .= 'data-target-planheader="' . $targetPlanHeaderId . '" ';
        $html .= 'data-rotate="' . $rotate . '" ';$html .= 'data-sync-rev="' . htmlspecialchars($syncRev, ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'data-eqLogic_uid="#uid#" ';
        $html .= 'data-version="' . $_version . '" ';
        $html .= 'style="' . $outerStyle . '">';

        $html .= '<div class="postitdesign-note-force" style="' . $noteStyle . '">';
        $html .= '<div class="postitdesign-drag-handle-force" onpointerdown="' . $dragJsAttr . '" style="' . $dragHandleStyle . '">↕</div>';
        $html .= '<div class="postitdesign-options-handle-force" onpointerdown="event.stopPropagation();" onmousedown="event.stopPropagation();" ontouchstart="event.stopPropagation();" ontouchend="' . $toggleOptionsJsAttr . '" onclick="' . $toggleOptionsJsAttr . '" style="' . $optionsHandleStyle . '">⋯</div>';
        $html .= '<div class="postitdesign-title-force" style="' . $titleStyle . '">' . $title . '</div>';
        $html .= '<div class="postitdesign-message-force" ontouchend="' . $lineClickJsAttr . '" onclick="' . $lineClickJsAttr . '" style="' . $messageStyle . '">' . $messageHtml . '</div>';
        $html .= '<div class="postitdesign-footer-force" data-open="0" onpointerdown="event.stopPropagation();" onmousedown="event.stopPropagation();" ontouchstart="event.stopPropagation();" onclick="event.preventDefault();event.stopPropagation();return false;" style="' . $footerStyle . '">';
        $html .= '<button type="button" ontouchstart="event.stopPropagation();" ontouchend="' . $newJsAttr . '" onclick="' . $newJsAttr . '" style="' . $newBtnStyle . '">+</button>';
        $html .= '<button type="button" ontouchstart="event.stopPropagation();" ontouchend="' . $completeJsAttr . '" onclick="' . $completeJsAttr . '" style="' . $btnStyle . '">✎</button>';
        $html .= '<button type="button" ontouchend="' . $rotateJsAttr . '" onclick="' . $rotateJsAttr . '" style="' . $rotateBtnStyle . '">⟳</button>';
        $html .= '<button type="button" ontouchstart="event.stopPropagation();" ontouchend="' . $decollerJsAttr . '" onclick="' . $decollerJsAttr . '" style="' . $deleteBtnStyle . '">✕</button>';
        /* POSTITDESIGN_TABLET_MINIMAL_DIRECT_V1 */
        /* POSTITDESIGN_REMOVE_TITLE_BUBBLES_V1 */
        $html .= '</div>';
        $html .= '<div class="postitdesign-status-force" style="display:none !important;font-size:10px !important;margin-top:5px !important;color:#555 !important;background:transparent !important;line-height:1.2 !important;word-break:break-word !important;"></div>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= <<<'POSTITDESIGN_ROTATION_APPLY_ON_RENDER_V2'
<script>
(function(){
  function pid(widget){
    try {
      var p = new URLSearchParams(window.location.search || '');
      return p.get('plan_id') || p.get('id') || p.get('planHeader_id') || widget.getAttribute('data-target-planheader') || '0';
    } catch(e) {
      return widget.getAttribute('data-target-planheader') || '0';
    }
  }

  var widgets = document.querySelectorAll('.postitdesign-widget');
  for (var i = 0; i < widgets.length; i++) {
    var widget = widgets[i];
    var eqId = widget.getAttribute('data-eqLogic_id') || '';
    var key = 'postitdesign.rotate.v2.' + pid(widget) + '.' + eqId;
    var raw = widget.getAttribute('data-rotate') || '0';
    try {
      var saved = localStorage.getItem(key);
      if (saved !== null && saved !== '') { raw = saved; }
    } catch(e) {}
    var angle = parseInt(raw, 10);
    if (isNaN(angle)) { angle = 0; }
    if (angle < -9) { angle = -9; }
    if (angle > 9) { angle = 9; }
    widget.setAttribute('data-rotate', String(angle));
    var note = widget.querySelector('.postitdesign-note-force');
    if (note) {
      note.style.setProperty('transform', 'rotate(' + angle + 'deg)', 'important');
      note.style.setProperty('transform-origin', 'center center', 'important');
    }
  }
  function postitdesignDesignBoundsV1(widget){
    function rectOk(r){ return r && r.width > 80 && r.height > 80; }
    var selectors = ['#div_displayObject','.div_displayObject','#div_designDisplay','.div_designDisplay','#div_pageContainer','.planContainer','.plan-container'];
    for (var i = 0; i < selectors.length; i++) {
      var el = document.querySelector(selectors[i]);
      if (el) {
        var r = el.getBoundingClientRect();
        if (rectOk(r)) { return r; }
      }
    }
    var parent = widget.parentElement;
    while (parent && parent !== document.body) {
      var pr = parent.getBoundingClientRect();
      if (rectOk(pr) && pr.width < window.innerWidth * 1.05 && pr.height < window.innerHeight * 1.05) {
        return pr;
      }
      parent = parent.parentElement;
    }
    return {left:0, top:0, right:window.innerWidth, bottom:window.innerHeight, width:window.innerWidth, height:window.innerHeight};
  }

  function postitdesignClampWidgetIntoDesignV1(widget){
    if (!widget) { return; }
    var b = postitdesignDesignBoundsV1(widget);
    var r = widget.getBoundingClientRect();
    var w = Math.max(40, r.width || widget.offsetWidth || 220);
    var h = Math.max(40, r.height || widget.offsetHeight || 160);

    var minX = Math.round(b.left);
    var minY = Math.round(b.top);
    var maxX = Math.round(b.right - w);
    var maxY = Math.round(b.bottom - h);

    if (maxX < minX) { maxX = minX; }
    if (maxY < minY) { maxY = minY; }

    var x = Math.round(r.left);
    var y = Math.round(r.top);

    if (x < minX) { x = minX; }
    if (y < minY) { y = minY; }
    if (x > maxX) { x = maxX; }
    if (y > maxY) { y = maxY; }

    widget.style.setProperty('left', x + 'px', 'important');
    widget.style.setProperty('top', y + 'px', 'important');
    widget.setAttribute('data-design-bounds', '1');
  }

  setTimeout(function(){
    var bounded = document.querySelectorAll('.postitdesign-widget');
    for (var b = 0; b < bounded.length; b++) {
      postitdesignClampWidgetIntoDesignV1(bounded[b]);
    }
    /* POSTITDESIGN_DESIGN_BOUNDS_V1 */
  }, 80);

  setTimeout(function(){
    var bounded = document.querySelectorAll('.postitdesign-widget');
    for (var b = 0; b < bounded.length; b++) {
      postitdesignClampWidgetIntoDesignV1(bounded[b]);
    }
  }, 600);

  window.postitdesignTabletRunClickV4 = window.postitdesignTabletRunClickV4 || function(btn, ev){
    if (!btn) { return false; }

    if (ev) {
      ev.preventDefault();
      ev.stopPropagation();
      if (ev.stopImmediatePropagation) { ev.stopImmediatePropagation(); }
    }

    var now = Date.now();
    var last = parseInt(btn.getAttribute('data-postit-touch-last') || '0', 10);
    if (now - last < 500) { return false; }
    btn.setAttribute('data-postit-touch-last', String(now));

    var code = btn.getAttribute('onclick') || '';
    if (!code) { return false; }

    try {
      var fakeEvent = ev || window.event || {
        preventDefault:function(){},
        stopPropagation:function(){},
        stopImmediatePropagation:function(){}
      };
      (new Function('event', code)).call(btn, fakeEvent);
    } catch (e) {
      if (window.console) { console.error('postitdesign tablet button error', e); }
    }

    return false;
  };
  /* POSTITDESIGN_TABLET_TOUCH_RUNNER_V4 */

  (function(){
    if (window.__postitdesignTabletCaptureFixV1) { return; }
    window.__postitdesignTabletCaptureFixV1 = true;

    function runInlineAction(el, ev) {
      if (!el) { return false; }

      if (ev) {
        ev.preventDefault();
        ev.stopPropagation();
        if (ev.stopImmediatePropagation) { ev.stopImmediatePropagation(); }
      }

      var now = Date.now();
      var last = parseInt(el.getAttribute('data-postit-touch-last') || '0', 10);
      if (now - last < 450) { return false; }
      el.setAttribute('data-postit-touch-last', String(now));

      var code = el.getAttribute('onclick') || '';
      if (!code) { return false; }

      try {
        var fakeEvent = ev || window.event || {
          preventDefault:function(){},
          stopPropagation:function(){},
          stopImmediatePropagation:function(){}
        };
        (new Function('event', code)).call(el, fakeEvent);
      } catch (e) {
        if (window.console) { console.error('postitdesign tablet action error', e); }
      }

      return false;
    }

    document.addEventListener('touchend', function(ev){
      var target = ev.target;
      if (!target || !target.closest) { return; }

      var btn = target.closest('.postitdesign-footer-force button');
      if (btn) {
        runInlineAction(btn, ev);
        return false;
      }

      var msg = target.closest('.postitdesign-message-force');
      if (msg) {
        runInlineAction(msg, ev);
        return false;
      }
    }, true);

    document.addEventListener('pointerup', function(ev){
      if (!ev || ev.pointerType !== 'touch') { return; }

      var target = ev.target;
      if (!target || !target.closest) { return; }

      var btn = target.closest('.postitdesign-footer-force button');
      if (btn) {
        runInlineAction(btn, ev);
        return false;
      }

      var msg = target.closest('.postitdesign-message-force');
      if (msg) {
        runInlineAction(msg, ev);
        return false;
      }
    }, true);

    /* POSTITDESIGN_TABLET_CAPTURE_FIX_V1 */
  })();

  /* POSTITDESIGN_ROTATION_APPLY_ON_RENDER_V2 */
})();
</script>
POSTITDESIGN_ROTATION_APPLY_ON_RENDER_V2;

        
$html .= <<<'POSTITDESIGN_SYNC_POLLING_POSTITS_V1'
<script>
(function(){
  if (window.__postitdesignSyncPollingPostitsV1) { return; }
  window.__postitdesignSyncPollingPostitsV1 = true;

  function buildLine(messageEl, lineText, index, struck) {
    var sp = document.createElement('span');
    sp.className = 'postitdesign-line-force';
    sp.setAttribute('data-line-index', String(index));
    sp.setAttribute('data-struck', struck ? '1' : '0');
    sp.style.setProperty('display', 'block', 'important');
    sp.style.setProperty('min-height', '18px', 'important');
    sp.style.setProperty('padding', '1px 2px', 'important');
    sp.style.setProperty('margin', '0', 'important');
    sp.style.setProperty('cursor', 'pointer', 'important');
    sp.style.setProperty('border-radius', '3px', 'important');
    sp.style.setProperty('text-decoration', struck ? 'line-through' : 'none', 'important');
    sp.style.setProperty('opacity', struck ? '.55' : '1', 'important');
    sp.textContent = lineText === '' ? '\u00a0' : lineText;
    messageEl.appendChild(sp);
  }

  function renderState(widget, data) {
    if (!widget || !data || !data.ok) { return; }
    if (widget.querySelector('.postitdesign-inline-edit-v2')) { return; }

    var msgEl = widget.querySelector('.postitdesign-message-force');
    if (msgEl && typeof data.message !== 'undefined') {
      var strikes = {};
      String(data.postit_strikes || '').split(',').forEach(function(v){
        v = String(v).trim();
        if (v !== '' && /^\d+$/.test(v)) { strikes[parseInt(v, 10)] = true; }
      });

      while (msgEl.firstChild) { msgEl.removeChild(msgEl.firstChild); }

      var lines = String(data.message || '').split(/\r?\n/);
      if (!lines.length) { lines = ['']; }

      for (var i = 0; i < lines.length; i++) {
        buildLine(msgEl, lines[i], i, !!strikes[i]);
      }
    }

    if (typeof data.rotate !== 'undefined') {
      var rotate = parseInt(data.rotate, 10);
      if (!isNaN(rotate)) {
        widget.setAttribute('data-rotate', String(rotate));
        var note = widget.querySelector('.postitdesign-note-force');
        if (note) {
          note.style.setProperty('transform', 'rotate(' + rotate + 'deg)', 'important');
          note.style.setProperty('transform-origin', 'center center', 'important');
        }
      }
    }

    if (data.rev) {
      widget.setAttribute('data-sync-rev', String(data.rev));
    }
  }

  function pollOne(widget) {
    if (!widget || widget.__postitdesignSyncBusy) { return; }
    if (widget.querySelector('.postitdesign-inline-edit-v2')) { return; }

    var eqId = widget.getAttribute('data-eqLogic_id');
    if (!eqId) { return; }

    widget.__postitdesignSyncBusy = true;

    var body = new URLSearchParams();
    body.append('action', 'getStateFromDesign');
    body.append('eqLogic_id', eqId);

    fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php', {
      method: 'POST',
      credentials: 'same-origin',
      cache: 'no-store',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: body.toString()
    })
    .then(function(resp){ return resp.json(); })
    .then(function(json){
      var data = json && typeof json.result !== 'undefined' ? json.result : json;
      if (!data || !data.ok) { return; }

      var currentRev = widget.getAttribute('data-sync-rev') || '';
      var incomingRev = String(data.rev || '');

      if (incomingRev !== '' && incomingRev !== currentRev) {
        renderState(widget, data);
      }
    })
    .catch(function(){})
    .then(function(){ widget.__postitdesignSyncBusy = false; }, function(){ widget.__postitdesignSyncBusy = false; });
  }

  function pollAll() {
    var widgets = document.querySelectorAll('.postitdesign-widget[data-eqLogic_id]');
    if (!widgets || !widgets.length) { return; }
    for (var i = 0; i < widgets.length; i++) {
      pollOne(widgets[i]);
    }
  }

  setTimeout(pollAll, 1200);
  setInterval(pollAll, 5000);
})();
</script>
POSTITDESIGN_SYNC_POLLING_POSTITS_V1;

return $html; } } class postitdesignCmd extends cmd
{
    /* POSTITDESIGN_CREATE_CMD_MINI_POSTIT_TOHTML_V1 */
    public function toHtml($_version = 'dashboard', $_options = '')
    {
        if ($this->getLogicalId() != 'create_postit') {
            return parent::toHtml($_version, $_options);
        }

        $cmdId = intval($this->getId());
        $uid = 'postitdesign_create_cmd_' . $cmdId . '_' . mt_rand(1000, 9999);

        $js = "event.preventDefault();event.stopPropagation();"
            . "if(window.jeedom&&window.jeedom.cmd&&window.jeedom.cmd.disableExecute){return false;}"
            . "var w=this;var s=w.querySelector('.postitdesign-create-mini-status');"
            . "if(s){s.textContent='Création...';}"
            . "if(window.jeedom&&window.jeedom.cmd&&typeof window.jeedom.cmd.execute==='function'){"
            . "window.jeedom.cmd.execute({id:" . $cmdId . ",success:function(){if(s){s.textContent='Créé';}setTimeout(function(){window.location.reload();},450);},error:function(){if(s){s.textContent='Erreur';}}});"
            . "}"
            . "return false;";

        $html = '<div class="cmd-widget cmd action postitdesign-create-mini-widget" ';
        $html .= 'data-cmd_id="' . $cmdId . '" ';
        $html .= 'data-cmd_uid="' . $uid . '" ';
        $html .= 'data-version="' . htmlspecialchars($_version, ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'onclick="' . htmlspecialchars($js, ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'style="width:92px !important;min-height:68px !important;cursor:pointer !important;box-sizing:border-box !important;padding:8px 7px 6px 7px !important;background:#fff475 !important;border:1px solid rgba(0,0,0,.14) !important;border-radius:3px !important;box-shadow:0 5px 10px rgba(0,0,0,.22) !important;transform:rotate(-2deg) !important;font-family:Arial,sans-serif !important;color:#2d2d2d !important;text-align:center !important;line-height:1.1 !important;">';
        $html .= '<div style="font-size:18px !important;font-weight:800 !important;line-height:18px !important;margin-bottom:3px !important;">+</div>';
        $html .= '<div style="font-size:12px !important;font-weight:700 !important;">Post-it</div>';
        $html .= '<div class="postitdesign-create-mini-status" style="font-size:9px !important;margin-top:5px !important;opacity:.70 !important;white-space:nowrap !important;">Créer</div>';
        $html .= '</div>';

        return $html;
    }


    public function execute($_options = array())
    {
        
        if ($this->getLogicalId() == 'create_postit') { /* POSTITDESIGN_NATIVE_CREATE_CMD_EXECUTE_V1 */
            $eq = $this->getEqLogic();
            if (!is_object($eq)) {
                throw new Exception('{{Equipement introuvable}}');
            }
            $planHeaderId = intval($eq->getConfiguration('target_planHeader_id', 0));
            $result = postitdesign::createPostitForPlan($planHeaderId);
            return __('Post-it créé', __FILE__) . ' #' . $result['eqLogic_id'];
        }
        return null;

    }
}
