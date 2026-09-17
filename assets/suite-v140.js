/* Real Estate 3.8.11.40 - Events-parity scoped layout/runtime repair. */
(function(W,D){
  'use strict';
  var ROOT=D.documentElement;
  function q(sel,root){try{return(root||D).querySelector(sel);}catch(e){return null;}}
  function qa(sel,root){try{return Array.prototype.slice.call((root||D).querySelectorAll(sel));}catch(e){return[];}}
  function kids(el){return el?Array.prototype.slice.call(el.children||[]):[];}
  function unique(arr){return arr.filter(function(x,i,a){return x&&a.indexOf(x)===i;});}
  function topLevel(arr){return unique(arr).filter(function(item){return !arr.some(function(other){return other!==item&&other.contains&&other.contains(item);});});}

  /* Match the Events recovery: take the horizontal rail from the real header
   * instead of hard-coding a second, competing content width. */
  function measureGrid(){
    var candidates=[
      q('.wp-theme-header-main > .container'),
      q('.wp-theme-header-main .container'),
      q('.wp-theme-site-header .container'),
      q('.wp-theme-site-footer .container')
    ].filter(Boolean);
    var best=null;
    candidates.some(function(el){
      var r=el.getBoundingClientRect();
      if(r.width>320&&r.width<=W.innerWidth+2){best=r;return true;}
      return false;
    });
    if(!best)return;
    ROOT.style.setProperty('--wpbb-v140-left',Math.max(16,Math.round(best.left))+'px');
    ROOT.style.setProperty('--wpbb-v140-right',Math.max(16,Math.round(W.innerWidth-best.right))+'px');
  }

  function bestHost(scope,cards){
    cards=topLevel(cards||[]);
    if(!scope||cards.length<2)return null;
    var candidates=unique(cards.reduce(function(out,card){
      var node=card;
      for(var depth=0;node&&node!==scope&&depth<7;depth++,node=node.parentElement){
        if(node.matches&&node.matches('.row,.wpbb-row,.wpbb-v62-card-grid,.wpbb-sector-grid,.wpbb-v139-stat-grid,.wpbb-v139-gallery-grid'))out.push(node);
      }
      return out;
    },[]));
    var best=null;
    candidates.forEach(function(host){
      var direct=kids(host).filter(function(child){return cards.some(function(card){return child===card||child.contains(card);});});
      if(direct.length<2)return;
      if(!best||direct.length>best.items.length)best={host:host,items:direct};
    });
    if(best)return best;
    var parent=cards[0].parentElement;
    if(parent&&cards.every(function(card){return card.parentElement===parent;}))return{host:parent,items:cards.slice()};
    return null;
  }

  function markGrid(host,items,cols,extra){
    if(!host||!items||items.length<2)return;
    host.classList.remove('wpbb-v140-cols-2','wpbb-v140-cols-3','wpbb-v140-cols-4');
    host.classList.add('wpbb-v140-grid','wpbb-v140-cols-'+Math.max(2,Math.min(4,cols||items.length)));
    if(extra)host.classList.add(extra);
    items.forEach(function(item){item.classList.add('wpbb-v140-grid-cell');});
  }

  function markSectionCards(){
    var groups=[
      ['.wp-theme-services-section','.wp-theme-sector-card,.wpbb-icon-card',4],
      ['.wp-theme-industries-section','.wp-theme-sector-card,.wpbb-icon-card',4],
      ['.wp-theme-case-studies-section','.wp-theme-case-card,.wp-theme-sector-card,.wpbb-icon-card',3],
      ['.wp-theme-process-section','.wp-theme-process-card,.wp-theme-sector-card,.wpbb-icon-card',3],
      ['.estate-area-guides','.wpbb-v139-area-card,.wp-theme-sector-card',3]
    ];
    groups.forEach(function(group){
      qa('#wp-theme-main '+group[0]).forEach(function(section){
        var cards=topLevel(qa(group[1],section));
        var pick=bestHost(section,cards);
        if(pick)markGrid(pick.host,pick.items,group[2]);
      });
    });
  }

  function markStats(){
    qa('#wp-theme-main .wp-theme-home-stats,#wp-theme-main .wpbb-v139-stats-section').forEach(function(section){
      var cards=topLevel(qa('.wpbb-v139-stat-card,.wp-theme-sector-proof__item,.wpbb-fun-fact',section));
      var pick=bestHost(section,cards);
      if(pick)markGrid(pick.host,pick.items,4,'wpbb-v140-stats-grid');
    });
    qa('#wp-theme-main .wpbb-v139-proof-grid').forEach(function(host){
      var items=kids(host).filter(function(item){return q('.wpbb-v139-stat-card,.wp-theme-sector-proof__item,.wpbb-fun-fact',item)||item.matches('.wpbb-v139-stat-card,.wp-theme-sector-proof__item,.wpbb-fun-fact');});
      if(items.length>=2)markGrid(host,items,3,'wpbb-v140-proof-grid');
    });
  }

  function markGalleryAndBlog(){
    qa('#wp-theme-main .wpbb-v139-gallery-grid').forEach(function(host){
      var items=kids(host).filter(function(item){return q('.wpbb-v139-gallery-card',item)||item.matches('.wpbb-v139-gallery-card');});
      if(items.length>=2)markGrid(host,items,3,'wpbb-v140-gallery-grid');
    });
    qa('#wp-theme-main .wpbb-v139-blog-grid').forEach(function(host){
      var items=kids(host).filter(function(item){return item.matches('article,.wpbb-v139-blog-card')||q('article,.wpbb-v139-blog-card',item);});
      if(items.length>=2)markGrid(host,items,3,'wpbb-v140-blog-grid');
    });
  }

  function markProperties(){
    qa('#wp-theme-main .wp-theme-property-grid:not(.is-list)').forEach(function(host){
      var cards=kids(host).filter(function(item){return item.matches('.wp-theme-property-card')||q('.wp-theme-property-card',item);});
      if(cards.length<2){cards=topLevel(qa('.wp-theme-property-card',host));}
      if(cards.length>=2)markGrid(host,cards,3,'wpbb-v140-property-grid');
    });
  }

  function hydrateImage(img,eager){
    if(!img)return;
    var lazy=img.getAttribute('data-src')||img.getAttribute('data-lazy-src');
    var current=img.getAttribute('src')||'';
    if(lazy&&(!current||/placeholder|transparent|data:image/i.test(current)))img.setAttribute('src',lazy);
    var lazySet=img.getAttribute('data-srcset')||img.getAttribute('data-lazy-srcset');
    if(lazySet&&!img.getAttribute('srcset'))img.setAttribute('srcset',lazySet);
    img.setAttribute('decoding','async');
    if(eager)img.setAttribute('loading','eager');
  }

  function prepareImages(root){
    root=root||D;
    qa('.wpbb-v139-home-hero img,.wp-theme-property-card__image img',root).forEach(function(img,index){
      hydrateImage(img,true);
      if(index===0)img.setAttribute('fetchpriority','high');
    });
    qa('.wpbb-v139-gallery-image img,.wpbb-v139-blog-card__media img,.wpbb-v139-feature-image img',root).forEach(function(img){hydrateImage(img,false);});
  }

  /* Keep the desktop mega menu anchored to its trigger, copied from the
   * working Events v140 pattern. */
  function menuTrigger(menu){
    var li=menu&&menu.parentElement;if(!li)return null;
    try{return li.querySelector(':scope > a, :scope > button, :scope > .wp-theme-nav-link')||li;}
    catch(e){return li.querySelector('a,button')||li;}
  }
  function desktop(){return W.matchMedia('(min-width: 992px)').matches;}
  function positionMega(menu){
    if(!menu)return;
    if(!desktop()){menu.style.removeProperty('top');return;}
    var trigger=menuTrigger(menu);if(!trigger||!trigger.getBoundingClientRect)return;
    var r=trigger.getBoundingClientRect(),li=menu.parentElement,lr=li&&li.getBoundingClientRect?li.getBoundingClientRect():r;
    var bottom=Math.ceil(Math.max(r.bottom,lr.bottom)-6);
    if(bottom>0){menu.style.setProperty('top',bottom+'px','important');ROOT.style.setProperty('--wpbb-v140-mega-top',bottom+'px');}
  }
  function positionMegas(){qa('.wp-theme-primary-menu>li>.wp-theme-mega-menu').forEach(positionMega);}
  function bindMegas(){
    qa('.wp-theme-primary-menu>li>.wp-theme-mega-menu').forEach(function(menu){
      if(menu.dataset.wpbbV140Bound)return;
      menu.dataset.wpbbV140Bound='1';
      var li=menu.parentElement;
      if(li){
        li.addEventListener('pointerenter',function(){positionMega(menu);},{passive:true});
        li.addEventListener('focusin',function(){positionMega(menu);});
      }
      menu.addEventListener('pointerenter',function(){positionMega(menu);},{passive:true});
    });
    positionMegas();
  }

  /* Future-proof hero pager. v139 currently uses one hero slide, so this is
   * inactive unless the homepage is later expanded to multiple slides. */
  function heroBlocks(){return qa('#wp-theme-main .wpbb-swiper--hero');}
  function swiperEl(block){return block&&(block.matches&&block.matches('.swiper')?block:q('.swiper',block));}
  function liveSwiper(block,el){return(el&&el.swiper)||(block&&block.swiper)||null;}
  function uniqueSlides(el){
    if(!el)return[];
    var slides=qa('.swiper-wrapper > .swiper-slide',el);if(!slides.length)slides=qa('.swiper-slide',el);
    var seen={},out=[];
    slides.forEach(function(slide,index){
      if(slide.classList.contains('swiper-slide-duplicate'))return;
      var raw=slide.getAttribute('data-swiper-slide-index'),key=(raw===null||raw==='')?'dom-'+index:String(raw);
      if(seen[key])return;seen[key]=1;out.push(slide);
    });
    return out.length?out:slides;
  }
  function activeIndex(sw,count){
    if(!count)return 0;
    var n=sw&&typeof sw.realIndex==='number'?sw.realIndex:(sw&&typeof sw.activeIndex==='number'?sw.activeIndex:0);
    n=parseInt(n,10);if(!isFinite(n)||n<0)n=0;return n%count;
  }
  function paintPager(pager,sw,count){
    var active=activeIndex(sw,count);
    qa('.wpbb-v140-hero-pagination__bullet',pager).forEach(function(button,index){
      var on=index===active;button.classList.toggle('is-active',on);
      if(on)button.setAttribute('aria-current','true');else button.removeAttribute('aria-current');
    });
  }
  function ensurePager(block){
    var el=swiperEl(block);if(!el)return;
    var count=uniqueSlides(el).length;if(count<2)return;
    var pager=q(':scope > .wpbb-v140-hero-pagination',el);
    if(!pager){
      pager=D.createElement('div');pager.className='wpbb-v140-hero-pagination';pager.setAttribute('role','group');pager.setAttribute('aria-label','Hero slides');el.appendChild(pager);
    }
    if(pager.children.length!==count){
      pager.innerHTML='';
      for(var i=0;i<count;i++){
        var b=D.createElement('button');b.type='button';b.className='wpbb-v140-hero-pagination__bullet';b.setAttribute('data-wpbb-v140-slide',String(i));b.setAttribute('aria-label','Go to hero slide '+(i+1)+' of '+count);pager.appendChild(b);
      }
    }
    if(!pager.dataset.wpbbV140PagerBound){
      pager.dataset.wpbbV140PagerBound='1';
      pager.addEventListener('click',function(event){
        var button=event.target&&event.target.closest?event.target.closest('[data-wpbb-v140-slide]'):null;if(!button)return;
        var index=parseInt(button.getAttribute('data-wpbb-v140-slide'),10)||0,sw=liveSwiper(block,swiperEl(block));
        if(sw){try{if(typeof sw.slideToLoop==='function')sw.slideToLoop(index);else if(typeof sw.slideTo==='function')sw.slideTo(index);}catch(e){}}
        paintPager(pager,sw,count);
      });
    }
    var sw=liveSwiper(block,el);
    if(sw&&pager._wpbbV140Swiper!==sw){
      pager._wpbbV140Swiper=sw;
      if(typeof sw.on==='function'){
        var update=function(){paintPager(pager,sw,count);};
        try{sw.on('slideChange',update);sw.on('realIndexChange',update);sw.on('transitionEnd',update);}catch(e){}
      }
    }
    paintPager(pager,sw,count);
  }

  function run(){
    if(D.body)D.body.classList.add('wpbb-v140','wpbb-v140-theme-realestate');
    measureGrid();
    markSectionCards();
    markStats();
    markGalleryAndBlog();
    markProperties();
    prepareImages(D);
    bindMegas();
    heroBlocks().forEach(ensurePager);
  }

  var timer=0;
  function schedule(){clearTimeout(timer);timer=W.setTimeout(run,40);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',schedule,{once:true});else schedule();
  W.addEventListener('load',function(){run();W.setTimeout(run,300);W.setTimeout(run,1100);});
  W.addEventListener('resize',function(){clearTimeout(timer);timer=W.setTimeout(run,100);},{passive:true});
  W.addEventListener('scroll',function(){W.requestAnimationFrame(positionMegas);},{passive:true});
  if(W.MutationObserver){
    var queued=false,observer=new MutationObserver(function(ms){
      if(!ms.some(function(m){return m.addedNodes&&m.addedNodes.length;}))return;
      if(queued)return;queued=true;
      W.setTimeout(function(){queued=false;run();},55);
    });
    observer.observe(D.documentElement,{childList:true,subtree:true});
    W.setTimeout(function(){observer.disconnect();},6000);
  }
})(window,document);
