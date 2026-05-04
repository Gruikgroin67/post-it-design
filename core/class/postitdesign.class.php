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




    /* POSTITDESIGN_CMD_MENU_NO_RELOAD_V7 */
    public static function planHiddenConfigKey($_planHeaderId)
    {
        return 'hidden_plan_' . intval($_planHeaderId);
    }

    public static function arePostitsHiddenForPlan($_planHeaderId)
    {
        $_planHeaderId = intval($_planHeaderId);
        if ($_planHeaderId <= 0) {
            return false;
        }
        return intval(config::byKey(self::planHiddenConfigKey($_planHeaderId), 'postitdesign', 0)) === 1;
    }

    public static function setPostitsHiddenForPlan($_planHeaderId, $_hidden)
    {
        $_planHeaderId = intval($_planHeaderId);
        if ($_planHeaderId <= 0) {
            throw new Exception('{{Design invalide}}');
        }
        $hidden = intval($_hidden) === 1 ? 1 : 0;
        config::save(self::planHiddenConfigKey($_planHeaderId), $hidden, 'postitdesign');
        return $hidden;
    }

    public static function countPostitsForPlan($_planHeaderId)
    {
        $_planHeaderId = intval($_planHeaderId);
        if ($_planHeaderId <= 0) {
            return 0;
        }

        $count = 0;

        try {
            $rows = DB::Prepare(
                'SELECT link_id FROM plan WHERE planHeader_id = :planHeader_id AND link_type = :link_type',
                array(
                    'planHeader_id' => $_planHeaderId,
                    'link_type' => 'eqLogic'
                ),
                DB::FETCH_TYPE_ALL
            );

            if (is_array($rows)) {
                foreach ($rows as $row) {
                    $eqId = 0;

                    if (is_array($row) && isset($row['link_id'])) {
                        $eqId = intval($row['link_id']);
                    } elseif (is_object($row) && isset($row->link_id)) {
                        $eqId = intval($row->link_id);
                    }

                    if ($eqId <= 0) {
                        continue;
                    }

                    $eq = eqLogic::byId($eqId);
                    if (!is_object($eq) || $eq->getEqType_name() != 'postitdesign') {
                        continue;
                    }

                    if (intval($eq->getConfiguration('is_create_controller', 0)) === 1) {
                        continue;
                    }

                    $count++;
                }
            }
        } catch (Exception $e) {
            $count = 0;
        }

        return $count;
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

        $rotate = intval($this->cfg('postit_rotate', -1)); /* POSTITDESIGN_ROTATION_15_STEP_V1 */
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

        $postitPlanHidden = ($targetPlanHeaderId > 0 && self::arePostitsHiddenForPlan($targetPlanHeaderId)); /* POSTITDESIGN_CMD_MENU_NO_RELOAD_V7 */

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

        $outerStyle = ($postitPlanHidden ? 'display:none !important;' : '')
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

        $titleBtnStyle = $btnStyle . 'background:#6f42c1 !important;';
        $titleButtonJs = <<<'POSTITDESIGN_TITLE_DIRECT_BUTTON_JS'
var ev=event||window.event;
if(ev){
  if(ev.cancelable!==false){ev.preventDefault();}
  ev.stopPropagation();
  if(ev.stopImmediatePropagation){ev.stopImmediatePropagation();}
}

var h=this;
var now=Date.now();
if(h.__postitTitleInlineLockUntil && now < h.__postitTitleInlineLockUntil){
  return false;
}
h.__postitTitleInlineLockUntil = now + 500;

var widget=(h.closest&&h.closest('.postitdesign-widget'))||null;
if(!widget){
  return false;
}

var eqId=widget.getAttribute('data-eqLogic_id')||widget.getAttribute('data-eqlogic_id')||'';
var titleEl=widget.querySelector('.postitdesign-title-force');
var footer=(h.closest&&h.closest('.postitdesign-footer-force'))||widget.querySelector('.postitdesign-footer-force');
var st=widget.querySelector('.postitdesign-status-force');

if(!eqId || !titleEl || !footer){
  if(st){
    st.style.setProperty('display','block','important');
    st.textContent='Titre non disponible';
  }
  return false;
}

var existing=footer.querySelector('.postitdesign-title-inline-form');
if(existing){
  var isOpen=existing.getAttribute('data-open')==='1';
  existing.setAttribute('data-open',isOpen?'0':'1');
  existing.style.setProperty('display',isOpen?'none':'inline-flex','important');
  if(!isOpen){
    var inp=existing.querySelector('input');
    if(inp){
      inp.focus();
      try{inp.select();}catch(ex){}
    }
  }
  return false;
}

var box=document.createElement('span');
box.className='postitdesign-title-inline-form';
box.setAttribute('data-open','1');
box.style.cssText='display:inline-flex;align-items:center;gap:4px;flex-wrap:wrap;margin-left:2px;padding:4px;border-radius:6px;background:rgba(255,255,255,.72);border:1px solid rgba(0,0,0,.18);pointer-events:auto;touch-action:manipulation;';

var input=document.createElement('input');
input.type='text';
input.value=(titleEl.textContent||'').trim();
input.style.cssText='width:120px;max-width:150px;padding:6px 7px;border-radius:5px;border:1px solid rgba(0,0,0,.25);font-size:12px;font-weight:700;background:#fff;color:#222;pointer-events:auto;user-select:text;-webkit-user-select:text;touch-action:auto;';

var ok=document.createElement('button');
ok.type='button';
ok.textContent='OK';
ok.style.cssText='padding:7px 8px;border:0;border-radius:5px;background:#3cae45;color:#fff;font-size:11px;font-weight:800;cursor:pointer;touch-action:manipulation;-webkit-tap-highlight-color:transparent;';

var cancel=document.createElement('button');
cancel.type='button';
cancel.textContent='Annuler';
cancel.style.cssText='padding:7px 8px;border:0;border-radius:5px;background:#777;color:#fff;font-size:11px;font-weight:800;cursor:pointer;touch-action:manipulation;-webkit-tap-highlight-color:transparent;';

function stopTitleInline(e){
  if(e){
    if(e.cancelable!==false){e.preventDefault();}
    e.stopPropagation();
    if(e.stopImmediatePropagation){e.stopImmediatePropagation();}
  }
}

function stopOnly(e){
  if(e){
    e.stopPropagation();
    if(e.stopImmediatePropagation){e.stopImmediatePropagation();}
  }
}

function saveTitle(e){
  stopTitleInline(e);

  var nextTitle=(input.value||'').trim();
  if(nextTitle===''){
    if(st){
      st.style.setProperty('display','block','important');
      st.textContent='Titre vide ignoré';
    }
    return false;
  }

  var oldTitle=titleEl.textContent;
  titleEl.textContent=nextTitle;
  titleEl.setAttribute('title','Titre modifié depuis les options');

  if(st){
    st.style.setProperty('display','block','important');
    st.textContent='Titre enregistré';
  }

  box.setAttribute('data-open','0');
  box.style.setProperty('display','none','important');

  var body=new URLSearchParams();
  body.append('action','setTitleFromDesign');
  body.append('eqLogic_id',eqId);
  body.append('title',nextTitle);

  fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{
    method:'POST',
    credentials:'same-origin',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:body.toString()
  }).catch(function(){
    titleEl.textContent=oldTitle;
    if(st){
      st.style.setProperty('display','block','important');
      st.textContent='Erreur sauvegarde titre';
    }
  });

  return false;
}

function cancelTitle(e){
  stopTitleInline(e);
  box.setAttribute('data-open','0');
  box.style.setProperty('display','none','important');
  return false;
}

['mousedown','mouseup','pointerdown','pointerup','touchstart','touchend','click'].forEach(function(t){
  box.addEventListener(t,stopOnly,true);
  input.addEventListener(t,stopOnly,true);
});

ok.onclick=ok.ontouchend=ok.onpointerup=saveTitle;
cancel.onclick=cancel.ontouchend=cancel.onpointerup=cancelTitle;

ok.addEventListener('click',saveTitle,true);
ok.addEventListener('pointerup',saveTitle,true);
cancel.addEventListener('click',cancelTitle,true);
cancel.addEventListener('pointerup',cancelTitle,true);

try{
  ok.addEventListener('touchend',saveTitle,{capture:true,passive:false});
  cancel.addEventListener('touchend',cancelTitle,{capture:true,passive:false});
}catch(ex){
  ok.addEventListener('touchend',saveTitle,true);
  cancel.addEventListener('touchend',cancelTitle,true);
}

input.addEventListener('keydown',function(e){
  if(e.key==='Enter'){
    saveTitle(e);
  }else if(e.key==='Escape'){
    cancelTitle(e);
  }else{
    e.stopPropagation();
  }
},true);

box.appendChild(input);
box.appendChild(ok);
box.appendChild(cancel);
footer.appendChild(box);

setTimeout(function(){
  input.focus();
  try{input.select();}catch(ex){}
},50);

if(st){
  st.style.setProperty('display','block','important');
  st.textContent='Modifier le titre puis OK';
}

return false;
POSTITDESIGN_TITLE_DIRECT_BUTTON_JS;
        $titleButtonJsAttr = htmlspecialchars($titleButtonJs, ENT_QUOTES, 'UTF-8'); /* POSTITDESIGN_TITLE_EDIT_INLINE_FORM_V4 */

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
            . 'right:6px !important;'
            . 'top:6px !important;'
            . 'width:24px !important;'
            . 'height:24px !important;'
            . 'line-height:24px !important;'
            . 'text-align:center !important;'
            . 'border-radius:50% !important;'
            . 'background:rgba(0,0,0,.10) !important;'
            . 'color:rgba(255,255,255,.58) !important;'
            . 'font-size:10px !important;'
            . 'font-weight:700 !important;'
            . 'cursor:move !important;'
            . 'z-index:99999 !important;'
            . 'opacity:.45 !important;'
            . 'user-select:none !important;'
            . 'touch-action:none !important;';

        $optionsHandleStyle = ''
            . 'position:absolute !important;'
            . 'right:6px !important;'
            . 'bottom:6px !important;'
            . 'width:24px !important;'
            . 'height:24px !important;'
            . 'line-height:24px !important;'
            . 'text-align:center !important;'
            . 'border-radius:50% !important;'
            . 'background:rgba(0,0,0,.10) !important;'
            . 'color:rgba(255,255,255,.58) !important;'
            . 'font-size:10px !important;'
            . 'font-weight:700 !important;'
            . 'cursor:pointer !important;'
            . 'z-index:99999 !important;'
            . 'opacity:.45 !important;'
            . 'user-select:none !important;'
            . 'touch-action:manipulation !important;';

        /* POSTITDESIGN_DISCREET_CORNER_HANDLES_V1 */

        $visualStyleOptionsJs = <<<'POSTITDESIGN_VISUAL_STYLE_OPTIONS_JS'
(function(){
  var root=(h.closest&&h.closest('.postitdesign-note-force'))||h;
  var f=root.querySelector('.postitdesign-footer-force');
  var widget=(root.closest&&root.closest('.postitdesign-widget'))||null;
  if(!f||!widget){return;}

  if(!f.querySelector('.postitdesign-visual-style-row')){
    var row=document.createElement('span');
    row.className='postitdesign-visual-style-row';
    row.style.cssText='display:inline-flex;gap:4px;align-items:center;flex-wrap:wrap;margin-left:2px;touch-action:manipulation;';

    function applyStyle(style){
      var eqId=widget.getAttribute('data-eqLogic_id')||'';
      var note=widget.querySelector('.postitdesign-note-force');
      var st=widget.querySelector('.postitdesign-status-force');
      if(['classic','paper','tape'].indexOf(style)===-1){style='classic';}

      widget.setAttribute('data-visual-style',style);

      if(note){
        var color=window.getComputedStyle(note).backgroundColor || '#fff4a8';
        note.style.setProperty('background', color, 'important');
        note.style.setProperty('background-color', color, 'important');

        if(style==='paper'){
          note.style.setProperty('background-image','repeating-linear-gradient(to bottom, rgba(255,255,255,0) 0px, rgba(255,255,255,0) 22px, rgba(80,70,40,.18) 23px, rgba(80,70,40,.18) 24px)','important');
          note.style.setProperty('border-top','1px solid rgba(120,95,15,.18)','important');
          note.style.setProperty('border-radius','2px','important');
          note.style.setProperty('box-shadow','0 7px 16px rgba(0,0,0,.24)','important');
        }else if(style==='tape'){
          note.style.setProperty('background-image','linear-gradient(to bottom, rgba(255,255,255,.58) 0px, rgba(255,255,255,.25) 24px, rgba(255,255,255,0) 25px), linear-gradient(135deg, rgba(255,255,255,.22), rgba(0,0,0,.04))','important');
          note.style.setProperty('border-top','8px solid rgba(245,230,140,.70)','important');
          note.style.setProperty('border-radius','3px','important');
          note.style.setProperty('box-shadow','0 9px 18px rgba(0,0,0,.28)','important');
        }else{
          note.style.setProperty('background-image','radial-gradient(rgba(255,255,255,.28) .7px, transparent .9px), linear-gradient(160deg, rgba(255,255,255,.30), rgba(0,0,0,.04))','important');
          note.style.setProperty('background-size','7px 7px, 100% 100%','important');
          note.style.setProperty('border-top','1px solid rgba(120,95,15,.18)','important');
          note.style.setProperty('border-radius','4px','important');
          note.style.setProperty('box-shadow','0 6px 14px rgba(0,0,0,.22)','important');
        }
      }

      if(st){
        st.style.setProperty('display','block','important');
        st.textContent='Visuel '+style+' enregistré';
      }

      if(eqId){
        var body=new URLSearchParams();
        body.append('action','setVisualStyleFromDesign');
        body.append('eqLogic_id',eqId);
        body.append('visual_style',style);

        fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{
          method:'POST',
          credentials:'same-origin',
          headers:{'Content-Type':'application/x-www-form-urlencoded'},
          body:body.toString()
        }).catch(function(){
          if(st){
            st.style.setProperty('display','block','important');
            st.textContent='Erreur sauvegarde visuel';
          }
        });
      }
    }


    [['classic','Classic'],['paper','Paper'],['tape','Tape']].forEach(function(pair){
      var b=document.createElement('button');
      b.type='button';
      b.className='postitdesign-visual-style-btn';
      b.textContent=pair[1];
      b.setAttribute('data-visual-style',pair[0]);
      b.style.cssText='font-size:11px;font-weight:700;line-height:1;padding:7px 8px;border-radius:5px;border:0;cursor:pointer;background:rgba(35,35,35,.52);color:#fff;font-family:Arial,sans-serif;white-space:nowrap;touch-action:manipulation;-webkit-tap-highlight-color:transparent;';
      function runVisualStyleButton(e){
        if(e){
          if(e.cancelable !== false){
            e.preventDefault();
          }
          e.stopPropagation();
        }

        var now=Date.now();
        if(b.__postitVisualTouchLockUntil && now < b.__postitVisualTouchLockUntil){
          return false;
        }
        b.__postitVisualTouchLockUntil = now + 800;

        applyStyle(pair[0]);
        return false;
      }

      b.__postitVisualRun = runVisualStyleButton;

      b.setAttribute('onclick','return this.__postitVisualRun ? this.__postitVisualRun(event) : false;');
      b.setAttribute('ontouchend','return this.__postitVisualRun ? this.__postitVisualRun(event) : false;');
      b.setAttribute('onpointerup','return this.__postitVisualRun ? this.__postitVisualRun(event) : false;');

      b.onclick = runVisualStyleButton;
      b.ontouchend = runVisualStyleButton;
      b.onpointerup = runVisualStyleButton;

      b.addEventListener('pointerdown',function(e){
        e.stopPropagation();
      },true);

      try {
        b.addEventListener('touchstart',function(e){
          e.stopPropagation();
        },{capture:true,passive:false});
      } catch(ex) {
        b.addEventListener('touchstart',function(e){
          e.stopPropagation();
        },true);
      }

      b.addEventListener('click',runVisualStyleButton,true);
      b.addEventListener('pointerup',runVisualStyleButton,true);

      try {
        b.addEventListener('touchend',runVisualStyleButton,{capture:true,passive:false});
      } catch(ex) {
        b.addEventListener('touchend',runVisualStyleButton,true);
      }

      /* POSTITDESIGN_VISUAL_STYLE_TABLET_INLINE_TOUCH_V2 */
      row.appendChild(b);
    });

    f.appendChild(row);
  }
})();
/* POSTITDESIGN_VISUAL_STYLE_OPTIONS_FROM_DESIGN_V1 */
POSTITDESIGN_VISUAL_STYLE_OPTIONS_JS;

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
            . $visualStyleOptionsJs
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

        $titleEditJs = <<<'POSTITDESIGN_TITLE_EDIT_JS'
event.preventDefault();
event.stopPropagation();

var titleEl=this;
var widget=titleEl.closest('.postitdesign-widget');
if(!widget){return false;}

var eqId=widget.getAttribute('data-eqLogic_id')||'';
if(!eqId){return false;}

var oldTitle=(titleEl.textContent||'').trim();
var newTitle=window.prompt('Titre du post-it', oldTitle || 'Nouveau post-it');

if(newTitle===null){return false;}

newTitle=String(newTitle).trim();
if(newTitle===''){newTitle='Nouveau post-it';}
if(newTitle.length>120){newTitle=newTitle.substring(0,120);}

titleEl.textContent=newTitle;

var st=widget.querySelector('.postitdesign-status-force');
if(st){
  st.style.setProperty('display','block','important');
  st.textContent='Titre enregistré';
}

var body=new URLSearchParams();
body.append('action','setTitleFromDesign');
body.append('eqLogic_id',eqId);
body.append('title',newTitle);

fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{
  method:'POST',
  credentials:'same-origin',
  headers:{'Content-Type':'application/x-www-form-urlencoded'},
  body:body.toString()
}).catch(function(){
  if(st){
    st.style.setProperty('display','block','important');
    st.textContent='Erreur sauvegarde titre';
  }
});

/* POSTITDESIGN_TITLE_EDIT_FROM_DESIGN_V1 */
return false;
POSTITDESIGN_TITLE_EDIT_JS;

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
            . "var widget=btn.closest('.postitdesign-widget');"
            . "if(!widget){return false;}"
            . "var eqId=widget.getAttribute('data-eqLogic_id')||'';"
            . "var note=widget.querySelector('.postitdesign-note-force');"
            . "var cur=parseInt(widget.getAttribute('data-postit-rotate')||widget.getAttribute('data-rotate')||'0',10);"
            . "if(isNaN(cur)){cur=0;}"
            . "if(cur!==0&&cur!==15&&cur!==-15){cur=0;}"
            . "var next=0;"
            . "if(cur===0){next=15;}else if(cur===15){next=-15;}else{next=0;}"
            . "widget.setAttribute('data-postit-rotate',String(next));"
            . "widget.setAttribute('data-rotate',String(next));widget.setAttribute('data-postitdesign-local-rotate',String(next));widget.setAttribute('data-postitdesign-rotate-lock-until',String(Date.now()+2500));/* POSTITDESIGN_ROTATION_IMMEDIATE_SAFE_V1 */"
            . "if(note){note.style.setProperty('transform','rotate('+next+'deg)','important');note.style.setProperty('transform-origin','center center','important');}"
            . "var st=widget.querySelector('.postitdesign-status-force');"
            . "if(st){st.style.setProperty('display','block','important');st.textContent='Rotation '+next+'°';}"
            . "var body=new URLSearchParams();"
            . "body.append('action','saveRotationFromDesign');"
            . "body.append('eqLogic_id',eqId);"
            . "body.append('rotate',String(next));"
            . "body.append('rotation',String(next));"
            . "body.append('postit_rotate',String(next));"
            . "body.append('angle',String(next));"
            . "fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()}).catch(function(){});"
            . "/* POSTITDESIGN_ROTATE_REAL_BUTTON_CYCLE_0_15_MINUS15_V1 */"
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
        $titleEditJsAttr = htmlspecialchars($titleEditJs, ENT_QUOTES, 'UTF-8'); /* POSTITDESIGN_TITLE_EDIT_FROM_DESIGN_V1 */
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
        $html .= '<div class="postitdesign-title-force" ondblclick="' . $titleEditJsAttr . '" title="Titre modifiable depuis les options" style="' . $titleStyle . 'cursor:text !important;">' . $title . '</div>'; /* POSTITDESIGN_TITLE_EDIT_FROM_DESIGN_V1 */
        $html .= '<div class="postitdesign-message-force" ontouchend="' . $lineClickJsAttr . '" onclick="' . $lineClickJsAttr . '" style="' . $messageStyle . '">' . $messageHtml . '</div>';
        $html .= '<div class="postitdesign-footer-force" data-open="0" onpointerdown="event.stopPropagation();" onmousedown="event.stopPropagation();" ontouchstart="event.stopPropagation();" onclick="event.preventDefault();event.stopPropagation();return false;" style="' . $footerStyle . '">';
        $titleSidePanelJs = <<<'POSTITDESIGN_TITLE_SIDE_PANEL_INLINE_JS'
<script>
(function(){
  var script = document.currentScript;
  var btn = script ? script.previousElementSibling : null;
  if (!btn || !btn.classList || !btn.classList.contains("postitdesign-title-edit-btn")) return;
  if (btn.getAttribute("data-postit-title-side-panel-v3") === "ready") return;
  btn.setAttribute("data-postit-title-side-panel-v3", "ready");

  function stop(e){
    if (!e) return;
    try { if (e.cancelable !== false) e.preventDefault(); } catch(ex) {}
    try { e.stopPropagation(); } catch(ex) {}
    try { if (e.stopImmediatePropagation) e.stopImmediatePropagation(); } catch(ex) {}
  }

  function closest(el, selector){
    while (el && el !== document) {
      try { if (el.matches && el.matches(selector)) return el; } catch(ex) {}
      el = el.parentNode;
    }
    return null;
  }

  function cleanText(el){
    return ((el && (el.innerText || el.textContent)) || "").replace(/\\s+/g, " ").trim();
  }

  function getWidget(){
    return closest(btn, ".postitdesign-widget") ||
           closest(btn, "[data-eqlogic_id]") ||
           closest(btn, "[data-eqLogic_id]") ||
           closest(btn, "[data-eqlogic-id]") ||
           btn.parentNode;
  }

  function getEqId(widget){
    return btn.getAttribute("data-eqlogic-id") ||
           (widget && (
             widget.getAttribute("data-eqlogic_id") ||
             widget.getAttribute("data-eqLogic_id") ||
             widget.getAttribute("data-eqlogic-id") ||
             widget.getAttribute("data-id")
           )) ||
           "";
  }

  function getDesign(widget){
    return closest(widget, "#div_displayObject") ||
           closest(widget, ".div_displayObject") ||
           closest(widget, ".planDisplay") ||
           closest(widget, ".planContainer") ||
           closest(widget, ".div_plan") ||
           closest(widget, ".eqLogicZone") ||
           widget.offsetParent ||
           document.body;
  }

  function getTitle(widget){
    var selectors = [
      ".postitdesign-title-force",
      ".postitdesign-title",
      ".postitdesign-header",
      ".postit-title",
      "[data-postit-title]",
      "strong",
      "b",
      "h3",
      "h4"
    ];

    for (var i = 0; i < selectors.length; i++) {
      var el = widget.querySelector(selectors[i]);
      if (!el) continue;
      var t = cleanText(el);
      if (t && t !== "Titre") return t;
    }

    return "";
  }

  function setTitleDom(widget, title){
    var selectors = [
      ".postitdesign-title-force",
      ".postitdesign-title",
      ".postitdesign-header",
      ".postit-title",
      "[data-postit-title]",
      "strong",
      "b",
      "h3",
      "h4"
    ];

    for (var i = 0; i < selectors.length; i++) {
      var el = widget.querySelector(selectors[i]);
      if (!el) continue;
      el.textContent = title;
      return;
    }
  }

  function removePanels(){
    var panels = document.querySelectorAll(".postitdesign-title-side-panel-v3");
    for (var i = 0; i < panels.length; i++) {
      if (panels[i].parentNode) panels[i].parentNode.removeChild(panels[i]);
    }
  }

  function installCss(){
    if (document.getElementById("postitdesign-title-side-panel-v3-css")) return;

    var st = document.createElement("style");
    st.id = "postitdesign-title-side-panel-v3-css";
    st.textContent =
      ".postitdesign-title-side-panel-v3{position:absolute;width:280px;z-index:999999;font-family:Arial,sans-serif;box-sizing:border-box;touch-action:manipulation;}" +
      ".postitdesign-title-side-panel-v3 *{box-sizing:border-box;}" +
      ".postitdesign-title-side-panel-v3-card{background:#fff8a8;border:2px solid rgba(0,0,0,.25);border-radius:14px;box-shadow:0 8px 24px rgba(0,0,0,.32);padding:10px;}" +
      ".postitdesign-title-side-panel-v3-label{font-size:15px;font-weight:800;color:#333;margin-bottom:8px;}" +
      ".postitdesign-title-side-panel-v3-input{width:100%;height:42px;font-size:18px;border-radius:9px;border:1px solid rgba(0,0,0,.30);background:#fff;color:#111;padding:4px 8px;}" +
      ".postitdesign-title-side-panel-v3-actions{display:flex;gap:8px;margin-top:10px;}" +
      ".postitdesign-title-side-panel-v3-actions button{flex:1;height:42px;border:0;border-radius:9px;font-size:16px;font-weight:800;cursor:pointer;touch-action:manipulation;-webkit-tap-highlight-color:transparent;}" +
      ".postitdesign-title-side-panel-v3-ok{background:#22c55e;color:#fff;}" +
      ".postitdesign-title-side-panel-v3-cancel{background:#ef4444;color:#fff;}";

    document.head.appendChild(st);
  }

  function bindStrong(el, fn){
    var lock = false;
    var h = function(e){
      stop(e);
      if (lock) return false;
      lock = true;
      setTimeout(function(){ lock = false; }, 450);
      fn(e);
      return false;
    };

    ["touchstart","touchend","pointerdown","pointerup","mousedown","mouseup","click"].forEach(function(name){
      try { el.addEventListener(name, h, {capture:true, passive:false}); }
      catch(ex) { el.addEventListener(name, h, true); }
    });
  }

  function ajaxSetTitle(eqId, title, done){
    if (!eqId) {
      done(false, "eqLogic id absent");
      return;
    }

    if (window.$ && $.ajax) {
      $.ajax({
        type: "POST",
        url: "plugins/postitdesign/core/ajax/postitdesign.ajax.php",
        dataType: "json",
        global: false,
        data: {
          action: "setTitleFromDesign",
          id: eqId,
          eqLogic_id: eqId,
          title: title
        },
        success: function(){ done(true, "ok"); },
        error: function(xhr){
          done(false, xhr && xhr.responseText ? xhr.responseText : "erreur ajax");
        }
      });
      return;
    }

    var body = new URLSearchParams();
    body.set("action", "setTitleFromDesign");
    body.set("id", eqId);
    body.set("eqLogic_id", eqId);
    body.set("title", title);

    fetch("plugins/postitdesign/core/ajax/postitdesign.ajax.php", {
      method: "POST",
      credentials: "same-origin",
      headers: {"Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"},
      body: body.toString()
    }).then(function(){ done(true, "ok"); }).catch(function(err){
      done(false, err && err.message ? err.message : String(err));
    });
  }

  function openPanel(e){
    stop(e);
    installCss();
    removePanels();

    var widget = getWidget();
    if (!widget) return false;

    var design = getDesign(widget);
    if (!design) design = document.body;

    var cs = window.getComputedStyle(design);
    if (cs.position === "static") design.style.position = "relative";

    var designRect = design.getBoundingClientRect();
    var widgetRect = widget.getBoundingClientRect();

    var panel = document.createElement("div");
    panel.className = "postitdesign-title-side-panel-v3";
    panel.innerHTML =
      "<div class=\"postitdesign-title-side-panel-v3-card\">" +
        "<div class=\"postitdesign-title-side-panel-v3-label\">Modifier le titre</div>" +
        "<input class=\"postitdesign-title-side-panel-v3-input\" type=\"text\" autocomplete=\"off\">" +
        "<div class=\"postitdesign-title-side-panel-v3-actions\">" +
          "<button type=\"button\" class=\"postitdesign-title-side-panel-v3-ok\">OK</button>" +
          "<button type=\"button\" class=\"postitdesign-title-side-panel-v3-cancel\">Annuler</button>" +
        "</div>" +
      "</div>";

    design.appendChild(panel);

    var input = panel.querySelector(".postitdesign-title-side-panel-v3-input");
    var ok = panel.querySelector(".postitdesign-title-side-panel-v3-ok");
    var cancel = panel.querySelector(".postitdesign-title-side-panel-v3-cancel");

    input.value = getTitle(widget);

    var panelW = 280;
    var panelH = 148;
    var gap = 12;

    var designW = design.clientWidth || designRect.width || window.innerWidth;
    var designH = design.clientHeight || designRect.height || window.innerHeight;

    var leftInDesign = widgetRect.left - designRect.left + (design.scrollLeft || 0);
    var topInDesign = widgetRect.top - designRect.top + (design.scrollTop || 0);
    var rightInDesign = leftInDesign + widgetRect.width;

    var roomRight = designW - rightInDesign;
    var roomLeft = leftInDesign;

    var left = (roomRight >= panelW + gap || roomRight >= roomLeft)
      ? rightInDesign + gap
      : leftInDesign - panelW - gap;

    if (left < 8) left = 8;
    if (left + panelW > designW - 8) left = Math.max(8, designW - panelW - 8);

    var top = topInDesign;
    if (top + panelH > designH - 8) top = Math.max(8, designH - panelH - 8);
    if (top < 8) top = 8;

    panel.style.left = left + "px";
    panel.style.top = top + "px";

    ["touchstart","touchend","pointerdown","pointerup","mousedown","mouseup","click"].forEach(function(name){
      try { panel.addEventListener(name, stop, {capture:true, passive:false}); }
      catch(ex) { panel.addEventListener(name, stop, true); }

      try {
        input.addEventListener(name, function(ev){
          try { ev.stopPropagation(); } catch(x) {}
        }, {capture:true, passive:false});
      } catch(ex2) {}
    });

    function close(){
      removePanels();
    }

    function save(){
      var title = (input.value || "").trim();
      if (!title) title = "Post-it";

      ok.disabled = true;
      ok.textContent = "...";

      ajaxSetTitle(getEqId(widget), title, function(success, msg){
        ok.disabled = false;
        ok.textContent = "OK";

        if (!success) {
          alert("Erreur titre: " + msg);
          return;
        }

        setTitleDom(widget, title);
        close();
      });
    }

    bindStrong(ok, save);
    bindStrong(cancel, close);

    input.addEventListener("keydown", function(ev){
      try { ev.stopPropagation(); } catch(x) {}
      if (ev.key === "Enter") {
        stop(ev);
        save();
      }
      if (ev.key === "Escape") {
        stop(ev);
        close();
      }
    }, true);

    setTimeout(function(){
      try {
        input.focus({preventScroll:true});
        input.select();
      } catch(ex) {
        try { input.focus(); input.select(); } catch(ex2) {}
      }
    }, 120);

    return false;
  }

  ["touchstart","touchend","pointerdown","pointerup","mousedown","mouseup","click"].forEach(function(name){
    try { btn.addEventListener(name, openPanel, {capture:true, passive:false}); }
    catch(ex) { btn.addEventListener(name, openPanel, true); }
  });
})();
</script>
POSTITDESIGN_TITLE_SIDE_PANEL_INLINE_JS;

        $html .= '<button type="button" class="postitdesign-title-edit-btn" data-eqlogic-id="' . intval($this->getId()) . '" data-postit-title-side-panel-v3="1" onmousedown="event.stopPropagation();" onpointerdown="event.stopPropagation();" ontouchstart="event.stopPropagation();" style="' . $titleBtnStyle . '">Titre</button>'; /* POSTITDESIGN_TITLE_SIDE_PANEL_INLINE_PHP_V3 */
        $html .= $titleSidePanelJs; /* POSTITDESIGN_TITLE_SIDE_PANEL_INLINE_PHP_V3 */
        $html .= '<button type="button" ontouchstart="event.stopPropagation();" ontouchend="' . $newJsAttr . '" onclick="' . $newJsAttr . '" style="' . $newBtnStyle . '">+</button>';
        $html .= '<button type="button" ontouchstart="event.stopPropagation();" ontouchend="' . $completeJsAttr . '" onclick="' . $completeJsAttr . '" style="' . $btnStyle . '">✎</button>';
        $html .= '<button type="button" class="postitdesign-rotate-btn-force" ontouchend="' . $rotateJsAttr . '" onclick="' . $rotateJsAttr . '" style="' . $rotateBtnStyle . '">⟳</button>'; /* POSTITDESIGN_ROTATE_BUTTON_IMMEDIATE_CAPTURE_CLASS_V1 */
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
          if(parseInt(widget.getAttribute('data-postitdesign-rotate-lock-until')||'0',10)>Date.now()){var localRotate=widget.getAttribute('data-postitdesign-local-rotate')||String(rotate);note.style.setProperty('transform','rotate('+localRotate+'deg)','important');}else{note.style.setProperty('transform', 'rotate(' + rotate + 'deg)', 'important');}
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


        if ($this->getLogicalId() == 'create_postit') { /* POSTITDESIGN_CMD_MENU_NO_RELOAD_V7 */
            $eqLogic = $this->getEqLogic();
            if (is_object($eqLogic) && $eqLogic->getEqType_name() == 'postitdesign') {
                $planHeaderId = intval($eqLogic->getConfiguration('target_planHeader_id', 0));
                if ($planHeaderId > 0) {
                    $count = postitdesign::countPostitsForPlan($planHeaderId);
                    $hidden = postitdesign::arePostitsHiddenForPlan($planHeaderId) ? 1 : 0;
                    $toggleText = $hidden === 1 ? 'Réafficher les post-it' : 'Masquer les post-it';
                    $dotText = $count > 0 ? strval($count) : '⋯';

                    $dotId = 'postitdesign_inline_dot_' . intval($this->getId());
                    $menuId = 'postitdesign_inline_menu_' . intval($this->getId());

                    $stopJs = "var ev=event||window.event;if(ev){if(ev.cancelable!==false){ev.preventDefault();}ev.stopPropagation();if(ev.stopImmediatePropagation){ev.stopImmediatePropagation();}}return false;";

                    $openJs = ""
                        . "var ev=event||window.event;"
                        . "if(ev){if(ev.cancelable!==false){ev.preventDefault();}ev.stopPropagation();if(ev.stopImmediatePropagation){ev.stopImmediatePropagation();}}"
                        . "var b=this;"
                        . "var now=Date.now();"
                        . "if(b.__ptDotLock&&now<b.__ptDotLock){return false;}"
                        . "b.__ptDotLock=now+350;"
                        . "var m=document.getElementById('" . $menuId . "');"
                        . "if(!m){return false;}"
                        . "var r=b.getBoundingClientRect();"
                        . "var root=b.parentElement;"
                        . "if(!root){return false;}"
                        . "if(window.getComputedStyle(root).position==='static'){root.style.position='relative';}"
                        . "if(m.parentElement!==root){root.appendChild(m);}"
                        . "var rr=root.getBoundingClientRect();"
                        . "var w=180;var h=65;"
                        . "var l=Math.round((r.right-rr.left)-w+4);"
                        . "var t=Math.round((r.bottom-rr.top)+6);"
                        . "if(l<0){l=0;}"
                        . "if(t<0){t=0;}"
                        . "var maxL=Math.max(0,Math.round(rr.width-w));"
                        . "var maxT=Math.max(0,Math.round(rr.height-h));"
                        . "if(l>maxL){l=maxL;}"
                        . "if(t>maxT){t=Math.max(0,Math.round((r.top-rr.top)-h-8));}"
                        . "if(t<0){t=0;}"
                        . "m.style.left=l+'px';"
                        . "m.style.top=t+'px';"
                        . "var open=(m.getAttribute('data-open')==='1');"
                        . "m.setAttribute('data-open',open?'0':'1');"
                        . "m.style.display=open?'none':'block';"
                        . "return false;";

                    $toggleJs = <<<POSTITDESIGN_INLINE_DOT_TOGGLE_JS
var ev=event||window.event;
if(ev){
  if(ev.cancelable!==false){ev.preventDefault();}
  ev.stopPropagation();
  if(ev.stopImmediatePropagation){ev.stopImmediatePropagation();}
}

var b=this;
var now=Date.now();
if(b.__ptMenuLock&&now<b.__ptMenuLock){return false;}
b.__ptMenuLock=now+900;

var m=document.getElementById('$menuId');
var currentHidden=(m&&m.getAttribute('data-hidden'))?m.getAttribute('data-hidden'):'$hidden';
var nextHidden=(currentHidden==='1')?'0':'1';

var body=new URLSearchParams();
body.append('action','togglePlanPostitsHiddenInlineDot');
body.append('planHeader_id','$planHeaderId');
body.append('hidden',nextHidden);

fetch('/plugins/postitdesign/core/ajax/postitdesign.ajax.php',{
  method:'POST',
  credentials:'same-origin',
  headers:{'Content-Type':'application/x-www-form-urlencoded'},
  body:body.toString()
}).then(function(){
  var nodes=document.querySelectorAll('.postitdesign-widget[data-target-planheader="$planHeaderId"]');

  for(var i=0;i<nodes.length;i++){
    if(nextHidden==='1'){
      nodes[i].style.setProperty('display','none','important');
    }else{
      nodes[i].style.removeProperty('display');
      nodes[i].style.setProperty('display','block','important');
    }
  }

  if(m){
    m.setAttribute('data-hidden',nextHidden);
    m.setAttribute('data-open','0');
    m.style.display='none';
  }

  var d=document.getElementById('$dotId');
  if(d){
    d.style.background=(nextHidden==='1')?'#3cae45':'rgba(0,0,0,.52)';
  }

  var labelBtn=m?m.querySelector('button'):null;
  if(labelBtn){
    if(nextHidden==='1'){
      labelBtn.textContent='Réafficher les post-it';
      labelBtn.style.background='#3cae45';
      labelBtn.style.color='#fff';
    }else{
      labelBtn.textContent='Masquer les post-it';
      labelBtn.style.background='#f0ad4e';
      labelBtn.style.color='#2b2b2b';
    }
  }
}).catch(function(){
  var d=document.getElementById('$dotId');
  if(d&&d.parentElement){
    var st=document.createElement('div');
    st.textContent='Erreur sauvegarde état post-it';
    st.style.cssText='position:absolute;left:0;top:100%;background:#d9534f;color:#fff;padding:4px 6px;border-radius:5px;font-size:11px;z-index:2147483000;';
    d.parentElement.appendChild(st);
    setTimeout(function(){st.remove();},2500);
  }
});

return false;
POSTITDESIGN_INLINE_DOT_TOGGLE_JS;

                    $dotStyle = ''
                        . 'position:absolute;'
                        . 'right:-9px;'
                        . 'top:-9px;'
                        . 'min-width:23px;'
                        . 'height:23px;'
                        . 'line-height:22px;'
                        . 'padding:0 6px;'
                        . 'border:0;'
                        . 'border-radius:13px;'
                        . 'background:' . ($hidden === 1 ? '#3cae45' : 'rgba(0,0,0,.52)') . ';'
                        . 'color:#fff;'
                        . 'font-size:12px;'
                        . 'font-weight:900;'
                        . 'text-align:center;'
                        . 'cursor:pointer;'
                        . 'z-index:2147483000;'
                        . 'box-shadow:0 2px 8px rgba(0,0,0,.32);'
                        . 'touch-action:manipulation;'
                        . '-webkit-tap-highlight-color:transparent;'
                        . 'pointer-events:auto;';

                    $menuStyle = ''
                        . 'display:none;'
                        . 'position:absolute;'
                        . 'left:0;'
                        . 'top:0;'
                        . 'min-width:180px;'
                        . 'padding:6px;'
                        . 'border-radius:10px;'
                        . 'background:rgba(255,255,255,.98);'
                        . 'border:1px solid rgba(0,0,0,.18);'
                        . 'box-shadow:0 8px 22px rgba(0,0,0,.28);'
                        . 'z-index:2147482999;'
                        . 'font-family:Arial,sans-serif;'
                        . 'pointer-events:auto;';

                    $btnStyle = ''
                        . 'display:block;'
                        . 'width:100%;'
                        . 'margin:0;'
                        . 'padding:8px 9px;'
                        . 'border:0;'
                        . 'border-radius:7px;'
                        . 'background:' . ($hidden === 1 ? '#3cae45' : '#f0ad4e') . ';'
                        . 'color:' . ($hidden === 1 ? '#fff' : '#2b2b2b') . ';'
                        . 'font-size:12px;'
                        . 'font-weight:700;'
                        . 'text-align:left;'
                        . 'cursor:pointer;'
                        . 'touch-action:manipulation;'
                        . '-webkit-tap-highlight-color:transparent;';

                    $html .= '<button id="' . $dotId . '" type="button" title="Options des post-it" style="' . $dotStyle . '" onmousedown="' . htmlspecialchars($stopJs, ENT_QUOTES, 'UTF-8') . '" onpointerdown="' . htmlspecialchars($stopJs, ENT_QUOTES, 'UTF-8') . '" ontouchstart="' . htmlspecialchars($stopJs, ENT_QUOTES, 'UTF-8') . '" onclick="' . htmlspecialchars($openJs, ENT_QUOTES, 'UTF-8') . '" onpointerup="' . htmlspecialchars($openJs, ENT_QUOTES, 'UTF-8') . '" ontouchend="' . htmlspecialchars($openJs, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($dotText, ENT_QUOTES, 'UTF-8') . '</button>';
                    $html .= '<script>(function(){var d=document.getElementById("' . $dotId . '");if(d&&d.parentElement){d.parentElement.style.overflow="visible";d.parentElement.style.position=d.parentElement.style.position||"relative";}})();</script>'; /* POSTITDESIGN_CMD_MENU_NO_RELOAD_V7 */
                    $html .= '<div id="' . $menuId . '" data-open="0" data-hidden="' . $hidden . '" data-planheader-id="' . $planHeaderId . '" class="postitdesign-inline-dot-menu" style="' . $menuStyle . '">';
                    $html .= '<button type="button" style="' . $btnStyle . '" onmousedown="' . htmlspecialchars($stopJs, ENT_QUOTES, 'UTF-8') . '" onpointerdown="' . htmlspecialchars($stopJs, ENT_QUOTES, 'UTF-8') . '" ontouchstart="' . htmlspecialchars($stopJs, ENT_QUOTES, 'UTF-8') . '" onclick="' . htmlspecialchars($toggleJs, ENT_QUOTES, 'UTF-8') . '" onpointerup="' . htmlspecialchars($toggleJs, ENT_QUOTES, 'UTF-8') . '" ontouchend="' . htmlspecialchars($toggleJs, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($toggleText, ENT_QUOTES, 'UTF-8') . '</button>';
                    $html .= '</div>';
                }
            }
        }

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
