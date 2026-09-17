(function(W,D){
  'use strict';
  function q(s,r){try{return (r||D).querySelector(s);}catch(e){return null;}}
  function qa(s,r){try{return Array.prototype.slice.call((r||D).querySelectorAll(s));}catch(e){return [];}}
  function kids(n){return n?Array.prototype.slice.call(n.children||[]):[];}
  function isHero(n){return !!(n&&n.closest&&n.closest('.wpbb-v136-hero-shell,.wpbb-v136-hero,.wp-theme-sector-hero,.wp-theme-hero'));}
  function mark(){if(D.body)D.body.classList.add('wpbb-v137','wpbb-v137-theme-realestate');}

  function markShells(){
    var main=q('#wp-theme-main');if(!main)return;
    var scopes=qa('section,.wp-theme-section-shell,.wp-theme-inner-hero,.wp-theme-home-cta,.wp-theme-gallery-section,.wp-theme-sector-media-text',main);
    scopes.forEach(function(scope){
      if(isHero(scope))return;
      kids(scope).forEach(function(child){
        if(child.matches&&child.matches('.container,.container-fluid,.wp-block-group__inner-container'))child.classList.add('wpbb-v137-shell');
      });
    });
    kids(main).forEach(function(child){
      if(isHero(child))return;
      if(child.matches&&child.matches('.container,.container-fluid,.wp-theme-sector-proof,.wp-theme-property-finder,.wp-theme-home-stats'))child.classList.add('wpbb-v137-shell');
    });
    qa('.wp-theme-section-heading.container,.wp-theme-sector-proof.container,.estate-proof.container',main).forEach(function(n){if(!isHero(n))n.classList.add('wpbb-v137-shell');});
  }

  function desiredCols(grid,count){
    if(grid.classList.contains('wp-theme-property-grid'))return 3;
    if(grid.classList.contains('wpbb-v136-stats-grid'))return Math.max(2,Math.min(4,count));
    if(count===4||count===8)return 4;
    if(count>=3)return 3;
    return 2;
  }
  function markGrid(grid){
    if(!grid||isHero(grid)||grid.closest('.swiper,.wpbb-swiper--hero')||grid.classList.contains('swiper-wrapper'))return;
    var cells=kids(grid).filter(function(c){
      return !c.classList.contains('wpbb-v136-hidden')&&!c.classList.contains('wpbb-v137-hidden')&&(String(c.textContent||'').trim()||q('img,article,.card',c));
    });
    if(cells.length<2||cells.length>18)return;
    var cols=desiredCols(grid,cells.length);
    grid.classList.remove('wpbb-v136-cols-2','wpbb-v136-cols-3','wpbb-v136-cols-4','wpbb-v137-cols-2','wpbb-v137-cols-3','wpbb-v137-cols-4');
    grid.classList.add('wpbb-v137-grid','wpbb-v137-cols-'+cols);
    cells.forEach(function(c){c.classList.add('wpbb-v137-grid-cell');});
  }
  function markGrids(){
    var main=q('#wp-theme-main');if(!main)return;
    var selectors=[
      '.wp-theme-property-grid:not(.is-list)',
      '.wpbb-v136-stats-grid',
      '.wp-theme-sector-services',
      '.wp-theme-sector-industries',
      '.wp-theme-case-grid',
      '.wp-theme-case-studies-grid',
      '.wp-theme-insights-grid',
      '.wp-theme-blog-grid',
      '.wp-theme-insights-section .wp-block-post-template',
      '.wp-theme-blog-preview .wp-block-post-template',
      '.wp-theme-sector-cards',
      '.wp-theme-feature-grid',
      '.wp-theme-card-grid',
      '.wp-theme-gallery-grid'
    ];
    qa(selectors.join(','),main).forEach(markGrid);
  }

  function sizesFor(img){
    var grid=img.closest('.wpbb-v137-grid');
    if(grid&&grid.classList.contains('wpbb-v137-cols-4'))return '(min-width: 1040px) 280px, (min-width: 720px) calc(50vw - 36px), calc(100vw - 32px)';
    if(grid&&grid.classList.contains('wpbb-v137-cols-2'))return '(min-width: 1040px) 570px, (min-width: 720px) calc(50vw - 36px), calc(100vw - 32px)';
    return '(min-width: 1040px) 380px, (min-width: 720px) calc(50vw - 36px), calc(100vw - 32px)';
  }
  function markMedia(){
    var main=q('#wp-theme-main');if(!main)return;
    var cardSelectors=[
      '.wp-theme-property-card__image img',
      '.wp-theme-blog-card .wp-block-post-featured-image img',
      '.wp-theme-blog-list-card img',
      '.wp-theme-case-card img',
      '.wp-theme-project-card img',
      '.wp-theme-gallery-card img',
      '.wp-theme-item-gallery-card__main img'
    ];
    qa(cardSelectors.join(','),main).forEach(function(img){
      if(isHero(img))return;
      img.classList.add('wpbb-v137-grid-image');
      if(!img.getAttribute('loading'))img.setAttribute('loading','lazy');
      img.setAttribute('decoding','async');
      img.setAttribute('sizes',sizesFor(img));
    });
    qa('.wp-theme-sector-media-text__media img,.wp-theme-sector-page-image img,.wp-theme-gallery-section .wpbb-swiper-slide__media img',main).forEach(function(img){
      if(isHero(img))return;
      img.classList.add('wpbb-v137-feature-image');
      if(!img.getAttribute('loading'))img.setAttribute('loading','lazy');
      img.setAttribute('decoding','async');
      img.setAttribute('sizes','(min-width: 1040px) 570px, calc(100vw - 32px)');
    });
  }

  function run(){mark();markShells();markGrids();markMedia();}
  var pending=false;
  function schedule(){if(pending)return;pending=true;W.setTimeout(function(){pending=false;run();},40);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',schedule,{once:true});else schedule();
  W.addEventListener('load',function(){run();W.setTimeout(run,250);W.setTimeout(run,800);});
  if(W.MutationObserver){
    new MutationObserver(function(ms){
      if(ms.some(function(m){return m.addedNodes&&m.addedNodes.length;}))schedule();
    }).observe(D.documentElement,{childList:true,subtree:true});
  }
})(window,document);
