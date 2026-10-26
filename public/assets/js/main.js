const $=s=>document.querySelector(s),$$=s=>[...document.querySelectorAll(s)];
const RM=false,FINE=true;
const root=document.documentElement,clamp=(v,a,b)=>Math.min(b,Math.max(a,v)),lerp=(a,b,t)=>a+(b-a)*t;
let hero=null,hi=null;
const baseUrl = window.CREATSIFY_BASE_URL || '';
const IM = {
  c: baseUrl + '/assets/images/cheetah-showcase.jpg',
  m: baseUrl + '/assets/images/viddmate-preview.jpg',
  h: baseUrl + '/assets/images/hero-artwork.jpg',
  r: baseUrl + '/assets/images/restromaster-preview.jpg',
  v: baseUrl + '/assets/images/viddmate-preview.jpg',
  z: baseUrl + '/assets/images/cheetah-showcase.jpg',
  k: baseUrl + '/assets/images/hero-artwork.jpg',
  s: baseUrl + '/assets/brand/creatsify-icon.png'
};
const _P_FALLBACK=[
["Cheetah Enterprise","p","Shipped · Flagship","Courier, 3PL warehouse and multi-branch logistics platform with 7 role portals, WMS and a 3D tracking globe.",["PHP 8.3","MySQL","Three.js","WooCommerce"],"c",[["Live demo","https://cheetah.ayan24.me"],["Buy on Gumroad","https://gumroad.com"]]],
["Cheetah Driver and Picker App","t","Shipped · Android and iOS","Flutter app for delivery riders and warehouse pickers, with offline mode, signature and photo proof of delivery, and barcode scanning.",["Flutter","Dart","Hive","PHP REST API"],"a",[["Buy on Gumroad","https://gumroad.com"],]],
["VisionLab and VisionCode AI","p","Shipped · Vision 2026 showcase","Browser-based coding platform: a Laravel 11 LMS, isolated cloud IDE workspaces, live collaboration, Jitsi video and a 3-mode AI agent.",["Laravel 11","Monaco","Docker","Laravel Reverb"],"v",[["View on GitHub","https://github.com/ayan2424/VisionLab"]]],
["ViddMate Browser","t","Shipped · v3.4 desktop","Creator browser for Windows, macOS and Linux with a stream sniffer, fast downloader, built-in FFmpeg converter and an AI panel.",["Electron","React","yt-dlp","FFmpeg"],"m",[["Visit viddmate.app","https://www.viddmate.app"]]],
["ViddMate Web","t","Shipped · Live","Web app to download video and audio from 1,000+ sites and convert 50+ file formats.",["Python","Celery","Redis","FFmpeg","React"],"m",[["Open converter","https://www.viddmate.app/converter"],["Website","https://www.viddmate.app"]]],
["Cheezify","s","Shipped · Live","Online store for artisanal and pizza cheese in Karachi, with cold-chain delivery zones and SEO-ready pages.",["Storefront","Cart drawer","SEO schema","Cloudflare"],"z",[["Visit store","https://cheezify.ayan24.me"]]],
["OrderKit","s","Shipped · Restaurant suite","Online ordering for restaurants and cloud kitchens: QR table menu, live kitchen display, thermal POS and rider dispatch.",["PHP","JavaScript","KDS","WhatsApp alerts"],"z",[["Ask for a demo","#contact"]]],
["Creatsify Direct Store","s","Shipped · White-label","Our catalog of PHP scripts, Flutter apps, and SaaS engines, sold directly via Gumroad with instant delivery and full technical support.",["Licensing tiers","Instant delivery","In-house support"],"k",[["Gumroad Store","https://gumroad.com"]]],
["SaaSForge","p","Shipped · SaaS launchpad","Multi-tenant SaaS starter kit with workspaces, role-based access, subscription billing and AI modules.",["Next.js","React 19","Tailwind","Stripe"],"k",[["Ask about it","#contact"]]],
["Sawi Store and Mindora","s","Shipped · Web and CMS","WordPress and WooCommerce store hardened with Wordfence and Rank Math SEO, plus the Mindora wellness and posture platform.",["WordPress","Elementor","WooCommerce","Rank Math"],"s",[["See portfolio","http://ayan24.me/Portfolio/"]]]
];
const P = (window.CREATSIFY_PROJECTS && window.CREATSIFY_PROJECTS.length > 0) ? window.CREATSIFY_PROJECTS : _P_FALLBACK;
const E=[["Edition 1","Cheetah Express Lite","A lighter, courier-only edition. No warehouse module and no picker role.","https://cheetah-lite.ayan24.me"],["Edition 2","Cheetah Enterprise","The full system: 7 user roles, warehouse management, store sync and the Flutter driver and picker app.","https://cheetah.ayan24.me"],["Edition 3","Cheetah Pro","A white-label cloud SaaS. Run your own courier platform for many companies, with Stripe, PayPal and Razorpay billing, custom domains and plans.","https://cheetah-pro.ayan24.me"]];
const F=["Admin and branch agent","Warehouse manager and picker","Merchant and customer portals","Driver app (Flutter)","Live rider tracking","WooCommerce and Shopify sync","Two-factor login, 19 security layers","Warehouse stock, orders and returns"];

const lk=a=>a.map(([t,u])=>`<a class="more" href="${u}"${u[0]=='h'?' target="_blank" rel="noopener noreferrer"':''}>${t} <span aria-hidden="true">→</span></a>`).join('');
/* build content */
const featuredP = P.slice(0, 4);
if($('#pjs')) {
  let cardsHtml = featuredP.map((p,i)=>`<article class="pj" style="--top:${86+i*14}px"><div class="pj-in" data-cur="View"><div class="pjt"><span class="pjn" aria-hidden="true">${String(i+1).padStart(2,'0')}</span><div class="pjw"><div class="st" style="--k:0"><i></i>${p[2]}</div><h3 style="--k:1">${p[0]}</h3><p style="--k:2">${p[3]}</p><div class="chips" style="--k:3">${p[4].map(x=>`<span>${x}</span>`).join('')}</div><div class="links" style="--k:4">${lk(p[6])}</div></div></div><div class="pjm"><img alt="${p[0]} preview" loading="lazy"><span class="fch a">${p[4][0]}</span>${p[4][1]?`<span class="fch b">${p[4][1]}</span>`:''}</div><div class="ov"></div></div></article>`).join('');
  cardsHtml += `<div class="portfolio-gateway-card rv" style="--d:.2s;"><div class="gateway-content"><span class="section-tag" style="margin-bottom:8px;">Catalog Archive</span><h3>Explore All 10+ Production Builds & Marketplace Scripts</h3><p>Browse our complete catalog of Courier platforms, AI cloud IDEs, Flutter apps, and WooCommerce stores with live demos and case studies.</p></div><a href="${baseUrl}/portfolio" class="btn p" style="padding: 13px 28px; font-weight:700;">Open Full Portfolio (${P.length}) &rarr;</a></div>`;
  $('#pjs').innerHTML = cardsHtml;
}
$$('.pj img').forEach((im,i)=>{ if(featuredP[i]) { const val = featuredP[i][5]; im.src = (val && (val.startsWith('/') || val.startsWith('http') || val.startsWith('data:'))) ? val : (IM[val] || IM.h); } });
if($('#eds')){
  E.forEach((e,i)=>{const w=document.createElement('div');w.className='rv f';w.style.setProperty('--d',i*.14+'s');
  w.innerHTML=`<article class="card" data-tilt="7"><div class="cb"><div class="st"><i></i>${e[0]}</div><h3>${e[1]}</h3><p>${e[2]}</p><div class="links">${lk([["Open live demo",e[3]]])}</div></div></article>`;$('#eds').appendChild(w)});
}
if($('#feat')) $('#feat').innerHTML=F.map((f,i)=>`<li class="rv l" style="--d:${i*.06}s">${f}</li>`).join('');
if($('#cheetahimg') && !$('#cheetahimg').getAttribute('src')) $('#cheetahimg').src=IM.c;
if($('#hm1') && !$('#hm1').getAttribute('src')) $('#hm1').src=IM.c;
if($('#hm2') && !$('#hm2').getAttribute('src')) $('#hm2').src=IM.m;
if($('#heroimg') && !$('#heroimg').getAttribute('src')) $('#heroimg').src=IM.h;
const femb=$('#femb');
if(femb){const em=femb.src;$$('[data-em]').forEach(i=>i.src=em);}
$$('[data-split]').forEach(el=>{el.innerHTML=el.textContent.trim().split(/\s+/).map((w,i)=>`<span class="w"><span class="ch" style="--i:${i}">${w}</span></span>`).join(' ')});

/* Enhanced theme toggle */
const themeBtn = $('#theme');
let th = localStorage.getItem('creatsify-theme') || (matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
root.dataset.theme = th;
if (themeBtn) {
  themeBtn.onclick = () => {
    th = th === 'dark' ? 'light' : 'dark';
    root.dataset.theme = th;
    try { localStorage.setItem('creatsify-theme', th); } catch(e){}
  };
}

/* preloader */
const pre=$('#pre'),preN=$('#preN');
const hasEntered = (() => {
  try { return sessionStorage.getItem('creatsify-entered') === '1'; } catch(e) { return false; }
})();
const go=()=>{
  if(!pre) return;
  pre.classList.add('done');
  setTimeout(()=>root.classList.add('ready'),180);
  setTimeout(()=>{ if(pre) pre.remove(); },600);
  try { sessionStorage.setItem('creatsify-entered', '1'); } catch(e) {}
};
if(RM || hasEntered || !pre){
  if(pre) pre.remove();
  root.classList.add('ready');
}else{
  let t0=performance.now(),ld=document.readyState=='complete';
  addEventListener('load',()=>ld=true);
  setTimeout(()=>ld=true,2500);
  const st=t=>{
    const k=Math.min(1,(t-t0)/1200),v=ld?k:Math.min(k,.9);
    if(pre) pre.style.setProperty('--p',v);
    if(preN) preN.textContent=String(Math.round(v*100)).padStart(2,'0');
    if(k>=1&&ld) go();
    else requestAnimationFrame(st);
  };
  requestAnimationFrame(st);
}
/* reveal */
const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('on');io.unobserve(e.target)}}),{threshold:.12,rootMargin:'0px 0px -6% 0px'});
$$('.rv,.sp,.pj').forEach(el=>io.observe(el));
/* count up */
const co=new IntersectionObserver(es=>es.forEach(e=>{if(!e.isIntersecting)return;co.unobserve(e.target);const el=e.target,n=+el.dataset.n,t0=performance.now();
const tk=t=>{const k=Math.min(1,(t-t0)/1400);el.textContent=Math.round(n*(1-Math.pow(1-k,3)));if(k<1)requestAnimationFrame(tk)};requestAnimationFrame(tk)}),{threshold:.6});
if(!RM)$$('[data-n]').forEach(el=>{el.textContent='0';co.observe(el)});
/* tracking demo */
const tl=$$('#trk li');let tkv=false,tki=0;
if(RM)tl.forEach(l=>l.classList.add('d'));else{new IntersectionObserver(es=>es.forEach(e=>tkv=e.isIntersecting),{threshold:.3}).observe($('#trk'));
setInterval(()=>{if(!tkv)return;tki=(tki+1)%7;tl.forEach((l,i)=>l.classList.toggle('d',i<tki))},1100)}
/* buttons ripple */
$$('.btn').forEach(b=>b.addEventListener('pointerdown',e=>{const r=b.getBoundingClientRect(),s=document.createElement('span');s.className='rp';s.style.left=e.clientX-r.left+'px';s.style.top=e.clientY-r.top+'px';b.appendChild(s);setTimeout(()=>s.remove(),700)}));
/* tilt, magnetic, cursor, spotlights */
if(FINE&&!RM){
$$('[data-tilt]').forEach(el=>{const mx=+el.dataset.tilt,lift=el.classList.contains('card')?6:0;
el.addEventListener('pointermove',e=>{const r=el.getBoundingClientRect(),px=(e.clientX-r.left)/r.width,py=(e.clientY-r.top)/r.height;
el.style.transform=`perspective(900px) rotateX(${((.5-py)*mx).toFixed(2)}deg) rotateY(${((px-.5)*mx).toFixed(2)}deg) translateY(${-lift}px)`;
el.style.setProperty('--gx',px*100+'%');el.style.setProperty('--gy',py*100+'%');el.style.setProperty('--mx',px*r.width+'px');el.style.setProperty('--my',py*r.height+'px')});
el.addEventListener('pointerleave',()=>{el.style.transform=''})});
$$('.btn').forEach(b=>{b.addEventListener('pointermove',e=>{const r=b.getBoundingClientRect();b.style.transform=`translate(${((e.clientX-r.left-r.width/2)*.2).toFixed(1)}px,${((e.clientY-r.top-r.height/2)*.3).toFixed(1)}px)`});b.addEventListener('pointerleave',()=>b.style.transform='')});
root.classList.add('cc');const ring=$('#cr'),dot=$('#cd');let x=innerWidth/2,y=innerHeight/2,rx=x,ry=y;
hero=$('#hero');hi=$('#heroimg');
addEventListener('pointermove',e=>{x=e.clientX;y=e.clientY;if(dot)dot.style.transform=`translate3d(${x}px,${y}px,0)`;root.classList.add('cv');
const inChat = !!e.target.closest('#creatsifyChatWidget');
if(ring&&dot){
  ring.classList.toggle('chat-cursor', inChat);
  dot.classList.toggle('chat-dot', inChat);
}
if(ring){
  ring.classList.toggle('h',!!e.target.closest('a,button,.card,[data-cur],input,select,textarea,label,.chat-chip,.chat-launcher-btn,.chat-close-btn,.chat-send-btn'));
  const c=e.target.closest('[data-cur]');
  ring.dataset.l=c?c.dataset.cur:(inChat&&e.target.closest('.chat-chip')?'Ask':(inChat&&e.target.closest('.chat-close-btn')?'Close':(inChat&&e.target.closest('.chat-send-btn')?'Send':'')));
}
const pj=e.target.closest('.pj-in');if(pj){const r=pj.getBoundingClientRect();pj.style.setProperty('--mx',e.clientX-r.left+'px');pj.style.setProperty('--my',e.clientY-r.top+'px')}
if(hero){const hr=hero.getBoundingClientRect();hero.style.setProperty('--sx',e.clientX-hr.left+'px');hero.style.setProperty('--sy',e.clientY-hr.top+'px');}});
document.addEventListener('mouseleave',()=>root.classList.remove('cv'));
(function L(){rx=lerp(rx,x,.17);ry=lerp(ry,y,.17);if(ring)ring.style.transform=`translate3d(${rx}px,${ry}px,0)`;requestAnimationFrame(L)})();}
/* project parallax + stacking */
const pjs=$$('.pj').map(el=>({el,inn:el.querySelector('.pj-in'),img:el.querySelector('.pjm img'),m:el.querySelector('.pjm'),w:el.querySelector('.pjw'),n:el.querySelector('.pjn'),f:[...el.querySelectorAll('.fch')]}));
const pjUpdate=()=>{const vh=innerHeight,stack=innerWidth>900;
pjs.forEach((o,i)=>{if(!o.el||!o.img||!o.m)return;const r=o.el.getBoundingClientRect();if(r.bottom<-300||r.top>vh+300)return;
const p=clamp((r.top+r.height/2-vh/2)/(vh/2+r.height/2),-1,1);let ov=0;const nx=pjs[i+1];
if(stack&&nx&&nx.el){const d=nx.el.getBoundingClientRect().top-r.top,full=r.height+56;ov=clamp((full-d)/(full-12),0,1)}
if(o.img&&o.m)o.img.style.setProperty('--iy',(p*o.m.offsetHeight*.11-ov*o.m.offsetHeight*.05).toFixed(1)+'px');
if(o.w)o.w.style.setProperty('--ty',(p*-16).toFixed(1)+'px');if(o.n)o.n.style.setProperty('--ny',(p*-70).toFixed(1)+'px');
if(o.f)o.f.forEach((f,k)=>f.style.setProperty('--cy',(p*(k?-34:26)).toFixed(1)+'px'));
if(o.inn){o.inn.style.setProperty('--sc',(1-ov*.05).toFixed(3));o.inn.style.setProperty('--ov',(ov*.5).toFixed(3))}})};
/* marquees, hero layers, scroll */
$$('.mq .mt').forEach(t => {
  if (t && !t.dataset.dup) {
    t.dataset.dup = 'true';
    t.innerHTML += t.innerHTML;
  }
});

const lys=$$('.ly');let sy=scrollY,vel=0,pcx=0,pcy=0,cx=0,cy=0,tkS=false;
addEventListener('pointermove',e=>{pcx=e.clientX/innerWidth-.5;pcy=e.clientY/innerHeight-.5},{passive:true});
const onScroll=()=>{tkS=false;const y=scrollY,h=root.scrollHeight-innerHeight;root.style.setProperty('--p',h>0?clamp(y/h,0,1):0);document.body.classList.toggle('scrolled',y>12);
if(y<1000){if(hi)hi.style.setProperty('--py',(y*.05)+'px');if(hero){hero.style.setProperty('--hy',(y*.14)+'px');hero.style.setProperty('--ho',clamp(1-y/(innerHeight*.9),0,1));}}
pjUpdate()};
addEventListener('scroll',()=>{if(!tkS){tkS=true;requestAnimationFrame(onScroll)}},{passive:true});addEventListener('resize',onScroll);addEventListener('load',onScroll);onScroll();
(function T(){const ny=scrollY;vel=lerp(vel,ny-sy,.1);sy=ny;
if(Math.abs(vel) > 0.05){
  $$('.mq.big').forEach(b => {
    b.style.transform = `skewX(${clamp(-vel*.08,-6,6).toFixed(2)}deg)`;
  });
} else {
  $$('.mq.big').forEach(b => { if(b.style.transform) b.style.transform = ''; });
}
cx=lerp(cx,pcx,.06);cy=lerp(cy,pcy,.06);lys.forEach(c=>{const d=+c.dataset.d;c.style.transform=`translate3d(${(-cx*d).toFixed(1)}px,${(-cy*d).toFixed(1)}px,0)`});
requestAnimationFrame(T)})();
/* nav highlight for in-page anchors */
const links=$$('#nav a');
const hashLinks=links.filter(a=>a.getAttribute('href')&&a.getAttribute('href').startsWith('#'));
if(hashLinks.length){
  const navIO=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)hashLinks.forEach(a=>a.classList.toggle('act',a.getAttribute('href')=='#'+e.target.id))}),{rootMargin:'-45% 0px -50% 0px'});
  $$('#work,#cheetah,#about,#contact').forEach(s=>navIO.observe(s));
}

/* portfolio category filter buttons */
const filterButtons=$$('#p-filters button');
if(filterButtons.length){
  filterButtons.forEach(btn=>{
    btn.onclick=()=>{
      filterButtons.forEach(b=>b.classList.remove('p'));
      btn.classList.add('p');
      const filter=btn.dataset.f;
      $$('.pj').forEach((card,idx)=>{
        const cat=P[idx]?P[idx][1]:'';
        if(filter==='all'||cat===filter){
          card.style.display='';
        } else {
          card.style.display='none';
        }
      });
    };
  });
}

/* rotating word */
const words=['courier companies','online stores','learning platforms','media brands','startups','restaurant chains'],rot=$('#rot');let wi=0;
if(rot&&!RM)setInterval(()=>{rot.classList.add('out');setTimeout(()=>{wi=(wi+1)%words.length;rot.textContent=words[wi];rot.classList.remove('out')},380)},2800);

/* footer wordmark hover highlight */
const bw=$('#bw');if(bw)addEventListener('pointermove',e=>bw.style.setProperty('--bp',(e.clientX/innerWidth).toFixed(2)),{passive:true});

/* Contact & Inquiry Form AJAX with PHP Backend & Mailto fallback */
const handleFormSubmit=async(e)=>{
  e.preventDefault();
  const form=e.target;
  const btn=form.querySelector('button[type="submit"]');
  const origText=btn?btn.textContent:'Send inquiry';
  if(btn){
    btn.disabled=true;
    btn.textContent='Sending inquiry...';
  }

  const fd=new FormData(form);
  const payload={
    name:fd.get('name')||fd.get('n')||'',
    email:fd.get('email')||fd.get('e')||'',
    phone:fd.get('phone')||fd.get('p')||'',
    project_type:fd.get('project_type')||fd.get('t')||'General Inquiry',
    budget:fd.get('budget')||'Not specified',
    message:fd.get('message')||fd.get('m')||'',
    website_url_hp:fd.get('website_url_hp')||'',
    _token:fd.get('_token')||window.CREATSIFY_CSRF_TOKEN||''
  };

  const apiUrl=window.CREATSIFY_API_URL||(window.CREATSIFY_BASE_URL?window.CREATSIFY_BASE_URL+'/api/contact':'/api/contact');

  let notice=form.querySelector('.form-notice');
  if(!notice){
    notice=document.createElement('div');
    notice.className='form-notice';
    form.appendChild(notice);
  }

  try{
    const res=await fetch(apiUrl,{
      method:'POST',
      headers:{'Content-Type':'application/json','Accept':'application/json'},
      body:JSON.stringify(payload)
    });
    const result=await res.json();
    if(result&&result.success){
      notice.className='form-notice success';
      notice.style.display='block';
      notice.textContent=result.message||'Thank you! Your inquiry has been sent to Creatsify Studio. We will get back to you within 24 hours.';
      form.reset();
    } else {
      throw new Error((result&&result.message)||'Server returned an error');
    }
  }catch(err){
    console.warn('API error, falling back to mailto:',err);
    const sub=encodeURIComponent('Project Inquiry: '+payload.project_type);
    const body=encodeURIComponent(payload.message+'\n\nFrom: '+payload.name+' ('+payload.email+')'+(payload.phone?'\nPhone: '+payload.phone:''));
    location.href='mailto:ayan@creatsify.tech?subject='+sub+'&body='+body;
    notice.className='form-notice info';
    notice.style.display='block';
    notice.textContent='Opening your email client to send message to ayan@creatsify.tech...';
  }finally{
    if(btn){
      btn.disabled=false;
      btn.textContent=origText;
    }
  }
};

$$('form').forEach(f=>f.onsubmit=handleFormSubmit);


/* ==========================================================================
   CREATSIFY MVC FRONTEND INTERACTION CONTROLLERS
   ========================================================================== */
document.addEventListener('DOMContentLoaded', function () {
    // 1. Theme Toggle Controller
    const themeBtn = document.getElementById('themeToggle');
    if (themeBtn) {
        themeBtn.addEventListener('click', function () {
            const current = document.documentElement.getAttribute('data-theme') || 'dark';
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('creatsify_theme', next);
        });
    }

    // 2. Mobile Drawer Toggle
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const drawer = document.getElementById('mobileDrawer');
    if (mobileBtn && drawer) {
        mobileBtn.addEventListener('click', function () {
            drawer.classList.toggle('open');
        });
    }

    // 3. Scroll Progress Bar
    const prog = document.getElementById('scrollProgressBar');
    if (prog) {
        window.addEventListener('scroll', function () {
            const h = document.documentElement.scrollHeight - window.innerHeight;
            const pct = h > 0 ? (window.scrollY / h) : 0;
            prog.style.transform = 'scaleX(' + pct + ')';
            prog.style.transformOrigin = '0 50%';
            prog.style.position = 'fixed';
            prog.style.top = '0';
            prog.style.left = '0';
            prog.style.width = '100%';
            prog.style.height = '3px';
            prog.style.background = 'linear-gradient(90deg, var(--accent), #ff4fa3, #3ecf8e)';
            prog.style.zIndex = '99999';
        }, { passive: true });
    }

    // 4. Back to Top Button
    const topBtn = document.getElementById('backToTop');
    if (topBtn) {
        topBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // 5. Portfolio Filter Tabs (Homepage & Work Archive)
    const tabs = document.querySelectorAll('.tab-btn[data-filter]');
    const cards = document.querySelectorAll('.project-card[data-category]');
    if (tabs.length > 0 && cards.length > 0) {
        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                const cat = this.getAttribute('data-filter');

                cards.forEach(card => {
                    if (cat === 'all' || card.getAttribute('data-category') === cat) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }

    // 6. Stats Counter Animation
    const counters = document.querySelectorAll('.counter[data-target]');
    if (counters.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const target = parseInt(el.getAttribute('data-target'), 10);
                    let current = 0;
                    const step = Math.max(1, Math.floor(target / 20));
                    const timer = setInterval(() => {
                        current += step;
                        if (current >= target) {
                            el.textContent = target;
                            clearInterval(timer);
                        } else {
                            el.textContent = current;
                        }
                    }, 40);
                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(c => observer.observe(c));
    }

    // 7. Mobile Navigation Drawer Controller
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const mobileDrawerBackdrop = document.getElementById('mobileDrawerBackdrop');
    const mobileDrawerClose = document.getElementById('mobileDrawerClose');

    const openDrawer = () => {
        if (!mobileDrawer) return;
        mobileDrawer.classList.add('open');
        mobileDrawer.setAttribute('aria-hidden', 'false');
        if (mobileDrawerBackdrop) mobileDrawerBackdrop.classList.add('active');
        if (mobileMenuBtn) {
            mobileMenuBtn.setAttribute('aria-expanded', 'true');
            const bars = mobileMenuBtn.querySelector('.icon-menu-bars');
            const cross = mobileMenuBtn.querySelector('.icon-menu-cross');
            if (bars) bars.style.display = 'none';
            if (cross) cross.style.display = 'block';
        }
        document.body.style.overflow = 'hidden';
    };

    const closeDrawer = () => {
        if (!mobileDrawer) return;
        mobileDrawer.classList.remove('open');
        mobileDrawer.setAttribute('aria-hidden', 'true');
        if (mobileDrawerBackdrop) mobileDrawerBackdrop.classList.remove('active');
        if (mobileMenuBtn) {
            mobileMenuBtn.setAttribute('aria-expanded', 'false');
            const bars = mobileMenuBtn.querySelector('.icon-menu-bars');
            const cross = mobileMenuBtn.querySelector('.icon-menu-cross');
            if (bars) bars.style.display = 'block';
            if (cross) cross.style.display = 'none';
        }
        document.body.style.overflow = '';
    };

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', () => {
            if (mobileDrawer && mobileDrawer.classList.contains('open')) {
                closeDrawer();
            } else {
                openDrawer();
            }
        });
    }

    if (mobileDrawerClose) {
        mobileDrawerClose.addEventListener('click', closeDrawer);
    }

    if (mobileDrawerBackdrop) {
        mobileDrawerBackdrop.addEventListener('click', closeDrawer);
    }

    // Close mobile drawer when any link is clicked or ESC is pressed
    document.querySelectorAll('.mobile-link').forEach(link => {
        link.addEventListener('click', closeDrawer);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mobileDrawer && mobileDrawer.classList.contains('open')) {
            closeDrawer();
        }
    });
});
