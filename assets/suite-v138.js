(function(W,D){
  'use strict';
  var cfg=W.wpbbRealEstateV138||{};
  var heroUrls=Array.isArray(cfg.heroUrls)?cfg.heroUrls.filter(Boolean):[];
  function q(s,r){try{return (r||D).querySelector(s);}catch(e){return null;}}
  function qa(s,r){try{return Array.prototype.slice.call((r||D).querySelectorAll(s));}catch(e){return [];}}
  function kids(n){return n?Array.prototype.slice.call(n.children||[]):[];}
  function visible(n){return !!(n&&n.nodeType===1&&!n.classList.contains('wpbb-v138-duplicate-hero')&&!n.hidden);}
  function isHero(n){return !!(n&&n.closest&&n.closest('.wpbb-v138-hero-shell,.wpbb-v138-hero,.wp-theme-sector-hero,.wp-theme-hero'));}
  function markBody(){if(D.body)D.body.classList.add('wpbb-v138','wpbb-v138-theme-realestate');}

  function heroBlocks(){
    return qa('#wp-theme-main .wpbb-swiper--hero,#wp-theme-main .wp-theme-sector-hero .swiper,#wp-theme-main .wp-theme-hero .swiper').filter(function(b,i,a){
      return a.indexOf(b)===i&&(!b.closest('.wpbb-swiper--hero')||b.classList.contains('wpbb-swiper--hero'));
    });
  }
  function heroShell(block){return block&&block.closest('.wp-theme-sector-hero,.wp-theme-hero,.wp-theme-section-shell,section')||block&&block.parentElement||block;}
  function markHero(){
    var blocks=heroBlocks();
    blocks.forEach(function(block,i){
      var shell=heroShell(block);
      if(i>0){if(shell)shell.classList.add('wpbb-v138-duplicate-hero');return;}
      block.classList.add('wpbb-v138-hero');
      if(shell)shell.classList.add('wpbb-v138-hero-shell');
      qa('.swiper-slide,.wpbb-swiper-slide',block).filter(function(s){return !s.classList.contains('swiper-slide-duplicate');}).forEach(function(slide,idx){
        var media=q('.wpbb-swiper-slide__media,.wp-theme-hero__media,.wpbb-hero-media',slide);
        if(!media)return;
        var img=q('img',media);
        if(!img&&heroUrls[idx%Math.max(heroUrls.length,1)]){img=D.createElement('img');media.appendChild(img);}
        if(img&&heroUrls.length){
          img.src=heroUrls[idx%heroUrls.length];
          img.removeAttribute('srcset');img.removeAttribute('sizes');img.removeAttribute('width');img.removeAttribute('height');
          img.loading='eager';img.decoding='async';
          try{img.fetchPriority=idx===0?'high':'auto';}catch(e){}
        }
      });
    });
  }


  function markShells(){
    var main=q('#wp-theme-main');if(!main)return;
    var scopes=qa('section,.wp-theme-section-shell,.wp-theme-inner-hero,.wp-theme-home-cta,.wp-theme-gallery-section,.wp-theme-sector-media-text,.wp-theme-blog-preview,.wp-theme-insights-section,.wp-theme-case-studies-section,.wp-theme-faq-section,.wp-theme-property-finder',main);
    scopes.forEach(function(scope){
      if(isHero(scope))return;
      kids(scope).forEach(function(child){
        if(child.matches&&child.matches('.container,.container-fluid,.wp-block-group__inner-container'))child.classList.add('wpbb-v138-shell');
      });
    });
    kids(main).forEach(function(child){
      if(isHero(child))return;
      if(child.matches&&child.matches('.container,.container-fluid,.wp-theme-sector-proof,.estate-proof,.wp-theme-property-finder,.wp-theme-home-stats'))child.classList.add('wpbb-v138-shell');
    });
    qa('.wp-theme-section-heading.container,.wp-theme-sector-proof.container,.estate-proof.container,.wp-theme-property-finder.container',main).forEach(function(n){if(!isHero(n))n.classList.add('wpbb-v138-shell');});
  }

  function looksLikeCell(c){
    if(!visible(c))return false;
    if(c.matches&&c.matches('.wpbb-column,.wp-block-wpbb-column,[class*="col-"],li,article,.wp-block-post'))return true;
    return !!q('.wp-theme-sector-card,.wpbb-icon-card,.wpbb-card,.card,.wp-theme-property-card,.wp-theme-case-card,.wp-theme-blog-card,.wpbb-fun-fact,.product',c);
  }
  function markGrid(host,forced){
    if(!host||host.closest('.swiper,.wpbb-v138-hero')||host.classList.contains('swiper-wrapper'))return;
    var cells=kids(host).filter(visible);if(cells.length<2||cells.length>12)return;
    var good=cells.filter(looksLikeCell);if(good.length<2||good.length/cells.length<0.7)return;
    var count=good.length,cols=forced||((host.classList.contains('wp-theme-property-grid'))?3:(count>=4?4:(count===3?3:2)));
    cols=Math.max(2,Math.min(4,cols));
    host.classList.remove('wpbb-v136-grid','wpbb-v137-grid','wpbb-v138-cols-2','wpbb-v138-cols-3','wpbb-v138-cols-4');
    host.classList.add('wpbb-v138-grid','wpbb-v138-cols-'+cols);
    good.forEach(function(c){c.classList.add('wpbb-v138-grid-cell');});
  }
  function markGrids(){
    var main=q('#wp-theme-main');if(!main)return;
    qa('.wp-theme-property-grid:not(.is-list),.wp-theme-case-grid,.wp-theme-case-studies-grid,.wp-theme-blog-grid,.wp-theme-insights-grid,.wp-block-post-template',main).forEach(function(g){markGrid(g,g.classList.contains('wp-theme-property-grid')?3:0);});
    qa('.wp-theme-services-section,.wp-theme-industries-section,.wp-theme-process-section,.wp-theme-home-stats,.wp-theme-sector-proof,.estate-proof,.wp-theme-insights-section,.wp-theme-blog-preview,.wp-theme-case-studies-section',main).forEach(function(section){
      qa('.row,.wpbb-row',section).forEach(function(row){markGrid(row,0);});
    });
    qa('.wp-theme-sector-services,.wp-theme-sector-industries,.wp-theme-sector-cards,.wp-theme-feature-grid,.wp-theme-card-grid',main).forEach(function(g){markGrid(g,0);});
  }

  function markMedia(){
    var main=q('#wp-theme-main');if(!main)return;
    qa('.wp-theme-property-card__image img,.wp-theme-item-gallery-card__main img,.wp-theme-case-card img,.wp-theme-blog-card img,.wp-theme-blog-list-card img,.wp-theme-project-card img,.wp-theme-gallery-card img',main).forEach(function(img){
      if(img.closest('.wpbb-v138-hero'))return;
      img.classList.add('wpbb-v138-card-image');
      if(!img.getAttribute('loading'))img.setAttribute('loading','lazy');
      img.setAttribute('decoding','async');
      if(!img.getAttribute('sizes'))img.setAttribute('sizes','(min-width:1040px) 380px,(min-width:720px) calc(50vw - 36px),calc(100vw - 32px)');
    });
  }

  function neutraliseBadLegacyGrid(){
    qa('#wp-theme-main .wp-theme-home-product-catalogue,#wp-theme-main .wp-theme-services-section,#wp-theme-main .wp-theme-industries-section,#wp-theme-main .wp-theme-process-section,#wp-theme-main .wp-theme-gallery-section,#wp-theme-main .wp-theme-insights-section').forEach(function(s){
      s.classList.remove('wpbb-v136-grid','wpbb-v137-grid','wpbb-v136-cols-2','wpbb-v136-cols-3','wpbb-v136-cols-4','wpbb-v137-cols-2','wpbb-v137-cols-3','wpbb-v137-cols-4');
    });
  }

  function run(){markBody();neutraliseBadLegacyGrid();markHero();markShells();markGrids();markMedia();}
  var pending=false;
  function schedule(){if(pending)return;pending=true;W.setTimeout(function(){pending=false;run();},40);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',schedule,{once:true});else schedule();
  W.addEventListener('load',function(){run();W.setTimeout(run,250);W.setTimeout(run,900);});
  if(W.MutationObserver)new MutationObserver(function(ms){if(ms.some(function(m){return m.addedNodes&&m.addedNodes.length;}))schedule();}).observe(D.documentElement,{childList:true,subtree:true});
})(window,document);
