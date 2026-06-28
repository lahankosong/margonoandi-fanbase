<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>EMINOR — Ekosistem Musik Indie Indonesia</title>
<meta name="description" content="EMINOR adalah ekosistem musik indie Indonesia — tempat belajar, berkarya, bertemu musisi, dan tumbuh bersama. Gratis untuk semua musisi.">
<meta property="og:title" content="EMINOR — Ekosistem Musik Indie Indonesia">
<meta property="og:description" content="Profil musisi gratis, direktori personil, papan gig, dan 14 alat studio. Rumah pertama bagi musisi yang sedang tumbuh sendirian.">
<meta property="og:image" content="{{ asset('images/Margonoandi.jpeg') }}">
<meta property="og:url" content="{{ url('/') }}">
<meta name="twitter:card" content="summary_large_image">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;overflow-x:hidden}
body{background:#06080f;color:#e2e8f0;font-family:'Sora',system-ui,sans-serif;line-height:1.6;overflow-x:hidden}
::-webkit-scrollbar{width:3px}
::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{background:linear-gradient(#38A8CC,#5B6EF5);border-radius:2px}
a{text-decoration:none;color:inherit}

:root{
  --ac:#38A8CC;--ac2:#5B6EF5;--ac3:#8B5CF6;
  --text:#e2e8f0;--t2:#94a3b8;--t3:#4a5568;
  --card:rgba(14,20,40,.65);--border:rgba(56,168,204,.14);--border2:rgba(255,255,255,.06);
}

/* ── GRAIN OVERLAY ── */
#grain{position:fixed;inset:0;z-index:9997;pointer-events:none;opacity:.03;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.65' numOctaves='4' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='200' height='200' filter='url(%23n)'/%3E%3C/svg%3E");
  animation:grain .4s steps(1) infinite}
@keyframes grain{0%{background-position:0 0}25%{background-position:-40px 25px}50%{background-position:70px -15px}75%{background-position:-20px -50px}}

/* ── CURSOR GLOW ── */
#cglow{position:fixed;pointer-events:none;z-index:9996;width:360px;height:360px;border-radius:50%;
  background:radial-gradient(circle,rgba(56,168,204,.055) 0%,transparent 70%);
  transform:translate(-50%,-50%);mix-blend-mode:screen;
  transition:left .12s ease,top .12s ease}

/* ── AURORA ── */
.aurora{position:absolute;inset:0;overflow:hidden;pointer-events:none;z-index:0}
.aurora::before,.aurora::after{content:'';position:absolute;border-radius:50%;filter:blur(100px)}
.aurora::before{width:660px;height:660px;background:radial-gradient(circle,rgba(56,168,204,.11),transparent 70%);top:-200px;left:-120px;animation:a1 22s ease-in-out infinite alternate}
.aurora::after{width:560px;height:560px;background:radial-gradient(circle,rgba(91,110,245,.09),transparent 70%);bottom:-100px;right:-100px;animation:a2 28s ease-in-out infinite alternate}
.aurora-3{position:absolute;width:380px;height:380px;background:radial-gradient(circle,rgba(139,92,246,.07),transparent 70%);border-radius:50%;filter:blur(80px);top:40%;right:25%;animation:a2 18s ease-in-out infinite;pointer-events:none}
@keyframes a1{to{transform:translate(70px,60px) scale(1.2)}}
@keyframes a2{to{transform:translate(-60px,-50px) scale(1.15)}}

/* ── EQ BARS ── */
.eq{display:flex;align-items:flex-end;gap:3px;height:30px;flex-shrink:0}
.eq-b{width:3px;border-radius:3px;background:var(--ac);opacity:.65}
.eq-b:nth-child(1){animation:eqb .7s ease-in-out infinite alternate}
.eq-b:nth-child(2){animation:eqb .5s ease-in-out infinite alternate .1s}
.eq-b:nth-child(3){animation:eqb .9s ease-in-out infinite alternate .2s}
.eq-b:nth-child(4){animation:eqb .6s ease-in-out infinite alternate .05s}
.eq-b:nth-child(5){animation:eqb .8s ease-in-out infinite alternate .15s}
.eq-b:nth-child(6){animation:eqb .45s ease-in-out infinite alternate .25s}
.eq-b:nth-child(7){animation:eqb .7s ease-in-out infinite alternate .1s}
@keyframes eqb{from{height:4px}to{height:100%}}

/* ── INTRO ── */
#intro{position:fixed;inset:0;background:#020307;z-index:9999;display:flex;flex-direction:column;align-items:center;justify-content:center;transition:opacity .9s}
#intro.out{opacity:0;pointer-events:none}
.ibg{position:absolute;inset:0;overflow:hidden;pointer-events:none}
.iaur{position:absolute;left:50%;top:50%;width:600px;height:600px;margin:-300px 0 0 -300px;
  background:radial-gradient(circle,rgba(56,168,204,.07) 0%,rgba(91,110,245,.05) 40%,transparent 70%);
  border-radius:50%;filter:blur(70px);animation:iaur 7s ease-in-out infinite alternate}
.iaur2{position:absolute;left:30%;top:60%;width:350px;height:350px;margin:-175px 0 0 -175px;
  background:radial-gradient(circle,rgba(139,92,246,.05),transparent 70%);
  border-radius:50%;filter:blur(60px);animation:iaur 9s ease-in-out infinite alternate-reverse}
@keyframes iaur{0%{transform:scale(.85) translate(-25px,-15px)}100%{transform:scale(1.18) translate(20px,25px)}}

/* Vinyl disc */
.ivinyl{position:relative;width:96px;height:96px;border-radius:50%;margin-bottom:2rem;
  background:conic-gradient(from 0deg,#0e0e0e 0%,#1c1c1c 8%,#0a0a0a 16%,#141414 24%,#0d0d0d 32%,#1c1c1c 40%,#0a0a0a 48%,#161616 56%,#0a0a0a 64%,#1c1c1c 72%,#0d0d0d 80%,#141414 88%,#0a0a0a 100%);
  box-shadow:0 0 30px rgba(56,168,204,.12),0 0 60px rgba(56,168,204,.04);
  animation:vspin 4s linear infinite paused;opacity:0;transition:opacity .5s}
.ivinyl.go{animation-play-state:running;opacity:1}
.ivinyl::after{content:'';position:absolute;inset:36%;background:radial-gradient(circle,#38A8CC 30%,#1a3a4a 60%);border-radius:50%;box-shadow:0 0 14px rgba(56,168,204,.7)}
@keyframes vspin{to{transform:rotate(360deg)}}

/* Pulse rings */
.iring{position:absolute;left:50%;top:50%;border-radius:50%;border:1px solid rgba(56,168,204,.22);animation:iring 3.6s ease-out infinite}
.iring:nth-child(3){animation-delay:1.2s}
.iring:nth-child(4){animation-delay:2.4s}
@keyframes iring{0%{width:40px;height:40px;margin:-20px 0 0 -20px;opacity:.7;border-color:rgba(56,168,204,.45)}100%{width:600px;height:600px;margin:-300px 0 0 -300px;opacity:0;border-color:rgba(91,110,245,.04)}}

.iskip{position:absolute;top:1.25rem;right:1.25rem;font-size:11px;color:rgba(255,255,255,.3);letter-spacing:.1em;cursor:pointer;border:1px solid rgba(255,255,255,.1);padding:4px 12px;border-radius:20px;transition:.2s;background:none}
.iskip:hover{color:#fff;border-color:rgba(255,255,255,.4)}
.imetro{display:flex;gap:9px;margin-bottom:1.75rem;align-items:center;height:28px}
.idot{width:6px;height:6px;background:#38A8CC;border-radius:50%;opacity:0;transform:scale(0)}
@keyframes dpop{0%,100%{opacity:0;transform:scale(0)}30%,70%{opacity:1;transform:scale(1)}}
.itext{text-align:center;min-height:80px}
.iline{font-size:clamp(.88rem,2.5vw,1.1rem);font-weight:300;color:rgba(255,255,255,.88);letter-spacing:.03em;opacity:0;transition:opacity .7s}
.iline.s{opacity:1}
.ilogo{font-size:clamp(2.8rem,11vw,6rem);font-weight:800;letter-spacing:.18em;color:#fff;opacity:0;transform:scale(.88);transition:opacity .9s ease,transform .9s cubic-bezier(.22,1,.36,1);margin-top:1.5rem;line-height:1}
.ilogo span{color:#38A8CC}
.ilogo.s{opacity:1;transform:scale(1)}
.ilogo.s span{animation:aflash 1.8s ease-out .6s forwards}
@keyframes aflash{0%{color:#38A8CC}20%{color:#fff;text-shadow:0 0 40px #38A8CC,0 0 80px rgba(56,168,204,.4)}100%{color:#38A8CC;text-shadow:0 0 12px rgba(56,168,204,.2)}}
.itag{font-size:11.5px;color:rgba(255,255,255,.32);letter-spacing:.1em;opacity:0;transition:opacity 1s .4s;margin-top:.5rem;text-align:center}
.itag.s{opacity:1}

/* ── NAV ── */
nav{position:fixed;inset:0 0 auto;z-index:900;height:60px;padding:0 2rem;display:flex;align-items:center;justify-content:space-between;transition:background .4s,border .4s}
nav.on{background:rgba(6,8,15,.92);backdrop-filter:blur(20px);border-bottom:1px solid var(--border)}
.nlogo{font-size:1.1rem;font-weight:800;letter-spacing:.1em;color:#fff}.nlogo span{color:#38A8CC}
.nlinks{display:flex;gap:1.75rem;list-style:none}
.nlinks a{font-size:12.5px;color:var(--t2);letter-spacing:.04em;transition:.2s}.nlinks a:hover{color:#fff}
.ncta{background:linear-gradient(135deg,#38A8CC,#2186a8);color:#fff;font-size:12px;font-weight:700;padding:8px 20px;border-radius:50px;letter-spacing:.05em;transition:.25s;white-space:nowrap;box-shadow:0 4px 18px rgba(56,168,204,.22)}
.ncta:hover{transform:translateY(-1px);box-shadow:0 8px 28px rgba(56,168,204,.4)}
.nmob{display:none;background:none;border:none;color:#fff;font-size:1.3rem;cursor:pointer}
section{position:relative;overflow:hidden}

/* ── HERO ── */
#hero{min-height:100vh;display:flex;flex-direction:column;justify-content:flex-end;padding:0 3rem 5rem}
.hbg{position:absolute;inset:0}
.hslide{position:absolute;inset:0;opacity:0;transition:opacity 1.8s ease}
.hslide.on{opacity:1}
.hs1{background:linear-gradient(160deg,#050919,#0c1828,#080f1c)}
.hs2{background:linear-gradient(160deg,#08051a,#140826,#060512)}
.hs3{background:linear-gradient(160deg,#05131a,#041515,#06080f)}
.hov{position:absolute;inset:0;background:linear-gradient(to top,rgba(6,8,15,.97) 18%,rgba(6,8,15,.5) 60%,rgba(6,8,15,.15) 100%);z-index:1}

/* Floating notes */
.notes{position:absolute;inset:0;z-index:1;pointer-events:none;overflow:hidden}
.note{position:absolute;opacity:0;animation:nfloat var(--d) ease-in-out infinite var(--dl);color:rgba(56,168,204,.3);font-size:.95rem}
@keyframes nfloat{0%{opacity:0;transform:translateY(0) rotate(0deg)}10%{opacity:1}90%{opacity:.1}100%{opacity:0;transform:translateY(-110px) rotate(18deg)}}

.hcont{position:relative;z-index:2;max-width:760px}
.hftw{position:relative;min-height:180px;margin-bottom:1.75rem}
.hf{font-size:clamp(1.2rem,3.2vw,1.8rem);font-weight:300;letter-spacing:.02em;opacity:0;position:absolute;transition:opacity .8s ease}
.hf.s{opacity:1}
.hf.dim{color:var(--t3);font-size:clamp(.88rem,2vw,1.1rem);letter-spacing:.12em;text-transform:uppercase}
.hf.sm{font-size:clamp(1rem,2.2vw,1.35rem);color:var(--t2)}
.hf.big{font-size:clamp(1.5rem,3.8vw,2.5rem);font-weight:700;color:#fff;line-height:1.3}
.hf.big span{color:#38A8CC}

.hero-bar{display:flex;align-items:center;gap:1.5rem;margin-bottom:1.5rem;opacity:0;transition:.8s}
.hero-bar.s{opacity:1}
.hero-desc{font-size:clamp(.82rem,1.7vw,.96rem);color:var(--t2);line-height:1.85}

.hact{display:flex;gap:12px;flex-wrap:wrap;opacity:0;transition:opacity .8s}
.hact.s{opacity:1}
.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 30px;border-radius:50px;font-size:13.5px;font-weight:600;letter-spacing:.05em;cursor:pointer;transition:.25s;border:none;font-family:inherit}
.btn-p{background:linear-gradient(135deg,#38A8CC,#2186a8);color:#fff;box-shadow:0 6px 28px rgba(56,168,204,.3)}
.btn-p:hover{transform:translateY(-2px);box-shadow:0 12px 42px rgba(56,168,204,.5)}
.btn-g{background:rgba(255,255,255,.05);color:var(--t2);border:1px solid var(--border2);backdrop-filter:blur(8px)}
.btn-g:hover{background:rgba(255,255,255,.1);color:#fff}

.hscroll{position:absolute;bottom:1.75rem;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:7px;z-index:2}
.hscroll span{font-size:9px;letter-spacing:.2em;color:var(--t3);text-transform:uppercase}
.harr{width:1px;height:36px;background:linear-gradient(to bottom,transparent,#38A8CC);animation:sp 2s ease-in-out infinite}
@keyframes sp{0%,100%{opacity:.3;transform:scaleY(.8)}50%{opacity:1;transform:scaleY(1)}}

/* ── MARQUEE ── */
.mq-strip{background:rgba(56,168,204,.03);border-top:1px solid var(--border);border-bottom:1px solid var(--border);padding:.7rem 0;overflow:hidden;white-space:nowrap;position:relative;z-index:1}
.mq-strip::before,.mq-strip::after{content:'';position:absolute;top:0;bottom:0;width:90px;z-index:2;pointer-events:none}
.mq-strip::before{left:0;background:linear-gradient(to right,#06080f,transparent)}
.mq-strip::after{right:0;background:linear-gradient(to left,#06080f,transparent)}
.mq-track{display:inline-flex;animation:mq 40s linear infinite}
.mq-item{display:inline-flex;align-items:center;gap:1.25rem;padding:0 1.25rem;font-size:10.5px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--t3)}
.mq-dot{width:4px;height:4px;border-radius:50%;background:#38A8CC;opacity:.5;flex-shrink:0}
@keyframes mq{from{transform:translateX(0)}to{transform:translateX(-50%)}}

/* ── EXPLORE ── */
#s-exp{padding:5.5rem 2rem;background:#06080f}
.sec-ey{font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#38A8CC;margin-bottom:.65rem;display:flex;align-items:center;justify-content:center;gap:10px}
.sec-ey::before,.sec-ey::after{content:'';flex:0 0 28px;height:1px}
.sec-ey::before{background:linear-gradient(to right,transparent,#38A8CC)}
.sec-ey::after{background:linear-gradient(to left,transparent,#38A8CC)}
.sec-h{font-size:clamp(1.3rem,3vw,2rem);font-weight:700;color:#fff;margin-bottom:.45rem}
.sec-sub{font-size:13.5px;color:var(--t2)}
.exp-top{text-align:center;margin-bottom:2.75rem}

.cgrid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;max-width:960px;margin:0 auto 3rem}
.ci{background:var(--card);border:1px solid var(--border);border-radius:18px;padding:1.5rem 1.25rem;cursor:pointer;transition:.25s;position:relative;overflow:hidden}
.ci::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 50% 0%,rgba(56,168,204,.1),transparent 65%);opacity:0;transition:.4s}
.ci::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2px;background:linear-gradient(90deg,#38A8CC,#5B6EF5);transform:scaleX(0);transform-origin:left;transition:.4s}
.ci:hover{border-color:rgba(56,168,204,.4);transform:translateY(-6px);box-shadow:0 22px 50px -15px rgba(56,168,204,.2)}
.ci:hover::before{opacity:1}
.ci:hover::after{transform:scaleX(1)}
.ci-ic{font-size:2rem;margin-bottom:.9rem;display:block;transition:transform .3s}
.ci:hover .ci-ic{transform:scale(1.15)}
.ci-t{font-size:13px;font-weight:700;color:#fff;margin-bottom:.45rem}
.ci-div{width:24px;height:1.5px;background:#38A8CC;border-radius:2px;margin:.45rem 0;transition:width .3s}
.ci:hover .ci-div{width:36px}
.ci-tag{font-size:11px;color:var(--t2);line-height:1.8}

.stats-strip{display:flex;border:1px solid var(--border);border-radius:16px;overflow:hidden;max-width:640px;margin:0 auto;background:var(--card);backdrop-filter:blur(12px)}
.ss-item{flex:1;padding:1.25rem .75rem;text-align:center;border-right:1px solid var(--border)}
.ss-item:last-child{border-right:none}
.ss-num{font-size:1.65rem;font-weight:800;color:#fff;line-height:1}
.ss-suf{font-size:.8rem;color:#38A8CC;font-weight:700}
.ss-lab{font-size:9.5px;letter-spacing:.1em;text-transform:uppercase;color:var(--t3);margin-top:.3rem}

/* ── MANIFESTO + LIVE ── */
#s-mid{padding:5.5rem 2rem;background:#020307}
.mid-wrap{max-width:1020px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:4.5rem;align-items:start}

.mf-ey{font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:#38A8CC;margin-bottom:1.5rem;padding-left:1.6rem}
.mf-line{font-size:clamp(1rem,2.4vw,1.4rem);font-weight:300;color:rgba(255,255,255,.8);line-height:1.9;margin-bottom:.3rem}
.mf-line.ac{font-size:clamp(1.1rem,2.7vw,1.55rem);font-weight:600;color:#fff}
.mf-line.hl{color:#38A8CC}
.mf-gap{height:1.4rem}
.mf-bar-wrap{position:relative;padding-left:1.6rem}
.mf-bar{position:absolute;left:0;top:0;width:2px;height:0;border-radius:2px;background:linear-gradient(to bottom,#38A8CC,#5B6EF5 60%,#8B5CF6);transition:height 2.2s cubic-bezier(.22,1,.36,1);box-shadow:0 0 8px rgba(56,168,204,.3)}
.mf-bar-wrap.go .mf-bar{height:100%}
.brand-em{font-weight:800;letter-spacing:.06em;color:#fff}.brand-em span{color:#38A8CC}
.mf-card{position:relative;border-radius:22px;padding:2rem 2rem 2rem 0;overflow:hidden;background:rgba(8,14,30,.94);isolation:isolate}
.mf-card-glow{position:absolute;inset:0;pointer-events:none;z-index:0}
.mf-card-glow::before{content:'';position:absolute;inset:-160%;background:conic-gradient(from 0deg at 50% 50%,transparent 325deg,rgba(56,168,204,.8) 340deg,rgba(91,110,245,.5) 350deg,transparent 360deg);animation:mfsp 5s linear infinite}
.mf-card-glow::after{content:'';position:absolute;inset:1px;background:rgba(8,14,30,.95);border-radius:21px}
@keyframes mfsp{to{transform:rotate(360deg)}}
.mf-card>*:not(.mf-card-glow){position:relative;z-index:1}
.mr{opacity:0;transform:translateX(-16px);transition:opacity .55s ease,transform .55s cubic-bezier(.22,1,.36,1)}
.mr.on{opacity:1;transform:none}
.mf-line.hl.on{animation:mfglow 3s ease-out .3s forwards}
@keyframes mfglow{0%{text-shadow:none}30%{text-shadow:0 0 28px rgba(56,168,204,.8),0 0 60px rgba(56,168,204,.3)}100%{text-shadow:0 0 10px rgba(56,168,204,.2)}}

.live-ey{display:inline-flex;align-items:center;gap:6px;font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#38A8CC;background:rgba(56,168,204,.08);border:1px solid rgba(56,168,204,.22);padding:4px 12px;border-radius:20px;margin-bottom:.85rem}
.ldot{width:5px;height:5px;border-radius:50%;background:#38A8CC;animation:lp 1.4s ease-in-out infinite}
@keyframes lp{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.35;transform:scale(.65)}}
.live-h{font-size:.9rem;font-weight:700;color:#fff;margin-bottom:.7rem}
.lcard{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:.65rem .85rem;display:flex;gap:10px;align-items:center;margin-bottom:6px;transition:.2s}
.lcard:hover{border-color:rgba(56,168,204,.3);transform:translateX(4px)}
.lic{font-size:1.1rem;flex-shrink:0}
.lu{font-size:12px;font-weight:600;color:#fff}
.ld{font-size:10.5px;color:var(--t2);margin-top:1px;line-height:1.4}
.lt{font-size:9.5px;color:var(--t3);margin-top:1px}
.live-more{display:block;text-align:center;font-size:12px;color:#38A8CC;margin-top:.75rem;letter-spacing:.04em;transition:.2s}
.live-more:hover{letter-spacing:.07em;opacity:.8}

/* ── CARA KERJA ── */
#s-how{padding:5rem 2rem;background:#06080f}
.how-top{text-align:center;margin-bottom:3.5rem}
.how-steps{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;max-width:840px;margin:0 auto;background:var(--border);border-radius:20px;overflow:hidden}
.how-step{background:rgba(14,20,40,.7);padding:2.5rem 1.75rem;position:relative;transition:.25s}
.how-step:hover{background:rgba(14,20,40,.95)}
.step-num{font-size:4rem;font-weight:800;line-height:1;margin-bottom:.9rem;
  background:linear-gradient(135deg,rgba(56,168,204,.12),rgba(91,110,245,.06));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;letter-spacing:-.03em}
.step-ic{font-size:1.9rem;margin-bottom:.9rem;display:block}
.step-t{font-size:15px;font-weight:700;color:#fff;margin-bottom:.4rem}
.step-d{font-size:12.5px;color:var(--t2);line-height:1.75}
.step-arr{position:absolute;right:0;top:50%;transform:translateY(-50%);width:1px;height:40%;background:linear-gradient(to bottom,transparent,var(--ac),transparent);opacity:.4}
.how-step:last-child .step-arr{display:none}

/* ── FITUR GRID ── */
#s-feat{padding:5.5rem 2rem;background:#020307}
.feat-top{text-align:center;margin-bottom:3.25rem}
.fgrid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;max-width:880px;margin:0 auto 4rem}
.fcard{background:var(--card);border:1px solid var(--border);border-radius:18px;padding:1.75rem 1.5rem;transition:.25s;position:relative;overflow:hidden;cursor:pointer;will-change:transform}
.fcard::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 50% 0%,rgba(56,168,204,.08),transparent 60%);opacity:0;transition:.4s}
.fcard::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2px;background:linear-gradient(90deg,#38A8CC,#5B6EF5);transform:scaleX(0);transform-origin:left;transition:.4s}
.fcard:hover::before{opacity:1}
.fcard:hover::after{transform:scaleX(1)}
.fcard:hover{border-color:rgba(56,168,204,.3);box-shadow:0 24px 60px -20px rgba(56,168,204,.18)}
.fc-ic{font-size:1.8rem;margin-bottom:.9rem;display:block;transition:transform .3s}
.fcard:hover .fc-ic{transform:scale(1.1) rotate(-4deg)}
.fc-t{font-size:14.5px;font-weight:700;color:#fff;margin-bottom:.35rem}
.fc-d{font-size:12px;color:var(--t2);line-height:1.7}
.fc-link{display:inline-flex;align-items:center;gap:5px;font-size:11.5px;color:#38A8CC;margin-top:.8rem;font-weight:600;transition:.2s;position:relative;z-index:1}
.fc-link:hover{gap:9px}

/* Roadmap */
.rm-row{max-width:880px;margin:0 auto}
.rm-head{font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:var(--t3);font-weight:700;margin-bottom:.9rem;display:flex;align-items:center;gap:8px}
.rm-head::after{content:'';flex:1;height:1px;background:var(--border)}
.rm-pills{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:2rem}
.rp{display:inline-flex;align-items:center;gap:6px;font-size:12px;padding:6px 14px;border-radius:20px;transition:.2s}
.rp.done{background:rgba(56,168,204,.08);border:1px solid rgba(56,168,204,.22);color:#38A8CC}
.rp.done:hover{background:rgba(56,168,204,.14)}
.rp.soon{background:rgba(139,92,246,.07);border:1px dashed rgba(139,92,246,.3);color:#a78bfa}
.rp-dot{width:5px;height:5px;border-radius:50%;flex-shrink:0}
.rp.done .rp-dot{background:#38A8CC}
.rp.soon .rp-dot{background:#a78bfa;animation:lp 2s ease-in-out infinite}

/* CTA */
.cta-wrap{text-align:center;padding:5rem 1rem 0;border-top:1px solid var(--border);margin-top:3.5rem;position:relative}
.cta-h{font-size:clamp(1.4rem,3.5vw,2.25rem);font-weight:700;color:#fff;margin-bottom:.6rem;position:relative;z-index:1}
.cta-sub{font-size:14px;color:var(--t2);max-width:410px;margin:0 auto 2.25rem;line-height:1.85;position:relative;z-index:1}
.cta-btn{display:inline-flex;flex-direction:column;align-items:center;cursor:pointer;background:none;border:none;font-family:inherit;position:relative;z-index:1}
.cta-line{width:100%;height:2px;background:linear-gradient(90deg,transparent,#38A8CC,transparent);border-radius:2px;transition:.35s}
.cta-inner{display:flex;align-items:center;gap:10px;padding:20px 48px;background:rgba(56,168,204,.07);border:1px solid rgba(56,168,204,.28);border-top:none;border-bottom:none;font-size:clamp(13px,2vw,16px);font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#fff;transition:.3s}
.cta-btn:hover .cta-inner{background:rgba(56,168,204,.16);color:#38A8CC}
.cta-btn:hover .cta-line{background:linear-gradient(90deg,transparent,#38A8CC 30%,#5B6EF5 70%,transparent)}

/* ── FOOTER ── */
footer{background:#020307;padding:0 2rem 2.5rem;text-align:center;border-top:1px solid var(--border);position:relative;overflow:hidden}
.f-wave{width:100%;height:50px;margin-bottom:2.5rem;opacity:.2}
.flogo{font-size:1.4rem;font-weight:800;letter-spacing:.15em;color:#fff;margin-bottom:.35rem}.flogo span{color:#38A8CC}
.ftag{font-size:13px;color:var(--t2);margin-bottom:2rem;line-height:1.7}
.fdiv{width:40px;height:1px;background:linear-gradient(90deg,transparent,#38A8CC,transparent);margin:1.5rem auto}
.fpoem{font-size:12.5px;color:var(--t3);line-height:2.3;max-width:320px;margin:0 auto 1.75rem}
.fpoem em{color:var(--t2);font-style:normal}
.flinks{display:flex;gap:1.75rem;justify-content:center;flex-wrap:wrap;margin-bottom:1.5rem}
.flinks a{font-size:11.5px;color:var(--t3);letter-spacing:.05em;transition:.2s}
.flinks a:hover{color:#38A8CC}
.fcopy{font-size:10.5px;color:var(--t3)}

/* ── MODAL ── */
#mbg{position:fixed;inset:0;background:rgba(2,3,7,.9);backdrop-filter:blur(14px);z-index:9100;display:none;align-items:center;justify-content:center;padding:1rem}
#mbg.on{display:flex}
.mbox{background:linear-gradient(135deg,#0c1120,#080e1c);border:1px solid var(--border);border-radius:24px;padding:2.5rem 1.75rem;max-width:430px;width:100%;position:relative;animation:mi .35s cubic-bezier(.22,1,.36,1)}
@keyframes mi{from{opacity:0;transform:translateY(22px) scale(.96)}to{opacity:1;transform:none}}
.mclose{position:absolute;top:.9rem;right:1.1rem;background:none;border:none;color:var(--t3);font-size:1.2rem;cursor:pointer;transition:.2s;line-height:1}.mclose:hover{color:#fff}
.mh{font-size:1.25rem;font-weight:700;color:#fff;margin-bottom:.25rem}
.ms{font-size:13px;color:var(--t2);margin-bottom:1.5rem}
.mopts{display:flex;flex-direction:column;gap:7px;margin-bottom:1.5rem}
.mopt{display:flex;align-items:center;gap:11px;padding:10px 14px;border-radius:12px;border:1.5px solid var(--border);cursor:pointer;transition:.18s;font-size:13px;color:var(--t2);background:none;text-align:left;font-family:inherit}
.mopt:hover,.mopt.sel{border-color:#38A8CC;color:#fff;background:rgba(56,168,204,.07)}
.mopt input{accent-color:#38A8CC}
.mlogin{width:100%;padding:14px;border-radius:50px;background:linear-gradient(135deg,#38A8CC,#2186a8);color:#fff;font-size:13.5px;font-weight:700;letter-spacing:.05em;border:none;cursor:pointer;transition:.25s;display:flex;align-items:center;justify-content:center;gap:9px;font-family:inherit;box-shadow:0 6px 24px rgba(56,168,204,.3)}
.mlogin:hover{opacity:.92;transform:translateY(-1px);box-shadow:0 10px 32px rgba(56,168,204,.45)}

/* ── REVEAL ── */
.rv{opacity:0;transform:translateY(22px);transition:.7s ease}.rv.on{opacity:1;transform:none}

/* ── RESPONSIVE ── */
@media(max-width:900px){
  .mid-wrap{grid-template-columns:1fr;gap:2.5rem}
  .fgrid{grid-template-columns:1fr 1fr}
  .cgrid{grid-template-columns:repeat(3,1fr)}
  .how-steps{grid-template-columns:1fr}
  .step-arr{display:none!important}
}
@media(max-width:640px){
  #hero{padding:0 1.5rem 4.5rem}
  .nlinks,.ncta{display:none}.nmob{display:block}
  .cgrid{grid-template-columns:1fr 1fr}
  .fgrid{grid-template-columns:1fr}
  .stats-strip{flex-wrap:wrap}
  .ss-item{min-width:50%;border-right:none;border-bottom:1px solid var(--border)}
  .ss-item:last-child{border-bottom:none}
  .cta-inner{padding:16px 24px;font-size:13px}
  #s-exp,#s-mid,#s-how,#s-feat{padding-left:1.25rem;padding-right:1.25rem}
}
@media(max-width:420px){
  .cgrid{grid-template-columns:1fr}
  .hact{flex-direction:column}
  .btn{justify-content:center}
}
</style>
</head>
<body>

{{-- Grain + Cursor --}}
<div id="grain"></div>
<div id="cglow"></div>

{{-- ════ INTRO ════ --}}
<div id="intro">
  <div class="ibg">
    <div class="iaur"></div><div class="iaur2"></div>
    <div class="iring"></div><div class="iring"></div>
  </div>
  <button class="iskip" onclick="skipIntro()">SKIP ↗</button>
  <div class="ivinyl" id="ivinyl"></div>
  <div class="imetro">
    <div class="idot" id="d1"></div>
    <div class="idot" id="d2"></div>
    <div class="idot" id="d3"></div>
  </div>
  <div class="itext">
    <div class="iline" id="l1">Dulu...</div>
    <div class="iline" id="l2" style="color:var(--t2);font-size:.92rem">musisi membutuhkan label untuk didengar.</div>
    <div class="iline" id="l3" style="margin-top:1.1rem">Sekarang...</div>
    <div class="iline" id="l4" style="color:var(--t2);font-size:.92rem">yang dibutuhkan hanya tempat yang tepat.</div>
  </div>
  <div class="ilogo" id="ilogo">E<span>MINOR</span></div>
  <div class="itag" id="itag">Ekosistem Musik Indie Indonesia</div>
</div>

{{-- ════ NAVBAR ════ --}}
<nav id="nav">
  <a href="#" class="nlogo">E<span>MINOR</span></a>
  <ul class="nlinks">
    <li><a href="#s-mid">Visi</a></li>
    <li><a href="#s-how">Cara Kerja</a></li>
    <li><a href="#s-feat">Fitur</a></li>
    <li><a href="#s-feat">Roadmap</a></li>
  </ul>
  <a href="{{ route('google.login') }}" class="ncta">Mulai Perjalanan</a>
  <button class="nmob" onclick="toggleNav()">☰</button>
</nav>

{{-- ════ HERO ════ --}}
<section id="hero">
  <div class="aurora"></div>
  <div class="hbg">
    <div class="hslide hs1 on"></div>
    <div class="hslide hs2"></div>
    <div class="hslide hs3"></div>
  </div>
  <div class="hov"></div>

  <div class="notes">
    <span class="note" style="left:13%;bottom:28%;--d:8s;--dl:0s">♪</span>
    <span class="note" style="left:24%;bottom:18%;--d:11s;--dl:2s">♫</span>
    <span class="note" style="left:68%;bottom:22%;--d:9s;--dl:1s">♩</span>
    <span class="note" style="left:79%;bottom:38%;--d:13s;--dl:3s">♪</span>
    <span class="note" style="left:50%;bottom:14%;--d:10s;--dl:4s">♬</span>
    <span class="note" style="left:38%;bottom:32%;--d:7s;--dl:1.5s">♫</span>
    <span class="note" style="left:87%;bottom:18%;--d:12s;--dl:5s">♩</span>
    <span class="note" style="left:6%;bottom:42%;--d:9.5s;--dl:3.5s">♬</span>
  </div>

  <div class="hcont">
    <div class="hftw">
      <div class="hf dim" id="hf0">Dulu...</div>
      <div class="hf sm"  id="hf1">musisi membutuhkan label untuk didengar.</div>
      <div class="hf dim" id="hf2">Sekarang...</div>
      <div class="hf sm"  id="hf3">yang dibutuhkan hanya tempat yang tepat.</div>
      <div class="hf big" id="hf4">E<span>MINOR</span></div>
      <div class="hf sm"  id="hf5">Tidak semua musisi lahir di kota besar.</div>
      <div class="hf sm"  id="hf6">Tidak semua musisi punya studio.</div>
      <div class="hf sm"  id="hf7">Tidak semua musisi punya koneksi.</div>
      <div class="hf big" id="hf8">Tetapi semua musisi<br>pantas didengar.</div>
      <div class="hf big" id="hf9">E<span>MINOR</span> adalah Ekosistem Musisi Indie Indonesia<br>yang sedang tumbuh sendirian.</div>
    </div>

    <div class="hero-bar" id="hero-bar">
      <div class="eq">
        <div class="eq-b"></div><div class="eq-b"></div><div class="eq-b"></div>
        <div class="eq-b"></div><div class="eq-b"></div><div class="eq-b"></div><div class="eq-b"></div>
      </div>
      <p class="hero-desc">Profil musisi gratis · Direktori personil · Papan gig · 14 alat studio</p>
    </div>

    <div class="hact" id="hact">
      <a href="{{ route('google.login') }}" class="btn btn-p">🎵 Mulai Perjalanan Musik</a>
      <a href="#s-mid" class="btn btn-g">▶ Kisah Kami</a>
    </div>
  </div>

  <div class="hscroll">
    <span>Scroll</span>
    <div class="harr"></div>
  </div>
</section>

{{-- ════ MARQUEE ════ --}}
@php
$mqItems = ['Gitar','Drum','Bass','Vokal','Piano','Produser','Perkusi','Biola','Synth','DJ','Yogyakarta','Jakarta','Bandung','Surabaya','Bali','Medan','Malang','Pop','Rock','Indie','Folk','Jazz','Electronic','R&B','Metal','Akustik','Neo-Soul','Keroncong'];
@endphp
<div class="mq-strip">
  <div class="mq-track">
    @foreach(array_merge($mqItems, $mqItems) as $item)
    <span class="mq-item"><span class="mq-dot"></span>{{ $item }}</span>
    @endforeach
  </div>
</div>

{{-- ════ EXPLORE ════ --}}
<section id="s-exp">
  <div class="exp-top rv">
    <div class="sec-ey">Mulai dari sini</div>
    <h2 class="sec-h">Apa yang sedang kamu cari?</h2>
    <p class="sec-sub">Pilih jalurmu — dan EMINOR akan memandumu.</p>
  </div>
  <div class="cgrid rv">
    <div class="ci" onclick="openModal()">
      <span class="ci-ic">🎸</span>
      <div class="ci-t">Belajar Gitar</div>
      <div class="ci-div"></div>
      <div class="ci-tag">Chord · Tuner · BPM</div>
    </div>
    <div class="ci" onclick="openModal()">
      <span class="ci-ic">🎤</span>
      <div class="ci-t">Menulis Lagu</div>
      <div class="ci-div"></div>
      <div class="ci-tag">Notes · Inspirasi · AI</div>
    </div>
    <div class="ci" onclick="openModal()">
      <span class="ci-ic">🥁</span>
      <div class="ci-t">Mencari Band</div>
      <div class="ci-div"></div>
      <div class="ci-tag">Personil · Audisi · Kota</div>
    </div>
    <div class="ci" onclick="openModal()">
      <span class="ci-ic">❤️</span>
      <div class="ci-t">Bagikan Karya</div>
      <div class="ci-div"></div>
      <div class="ci-tag">Upload · Feedback · Fans</div>
    </div>
    <div class="ci" onclick="openModal()">
      <span class="ci-ic">🌎</span>
      <div class="ci-t">Komunitas</div>
      <div class="ci-div"></div>
      <div class="ci-tag">Chat · Gig · Diskusi</div>
    </div>
  </div>

  <div class="stats-strip rv">
    <div class="ss-item">
      <div class="ss-num"><span class="sc" data-t="100">100</span><span class="ss-suf">pertama</span></div>
      <div class="ss-lab">Musisi Aktif</div>
    </div>
    <div class="ss-item">
      <div class="ss-num"><span class="sc" data-t="14">14</span></div>
      <div class="ss-lab">Alat Gratis</div>
    </div>
    <div class="ss-item">
      <div class="ss-num"><span class="sc" data-t="31">31</span></div>
      <div class="ss-lab">Materi Musik</div>
    </div>
    <div class="ss-item">
      <div class="ss-num">24<span class="ss-suf">/7</span></div>
      <div class="ss-lab">Komunitas</div>
    </div>
  </div>
</section>

{{-- ════ MANIFESTO + LIVE ════ --}}
<section id="s-mid">
  <div class="aurora"></div>
  <div class="aurora-3"></div>
  <div class="mid-wrap">

    <div class="mf-card">
      <div class="mf-card-glow"></div>
      <div class="mf-ey mr" id="mf-ey">Kami Percaya</div>
      <div class="mf-bar-wrap" id="mf-bar-wrap">
        <div class="mf-bar"></div>
        <p class="mf-line mr">Bakat tidak memilih tempat lahir.</p>
        <p class="mf-line mr">Musik tidak memilih kota.</p>
        <div class="mf-gap"></div>
        <p class="mf-line ac mr">Lagu yang hebat bisa lahir</p>
        <p class="mf-line ac hl mr">di kamar berukuran 3×3 meter.</p>
        <div class="mf-gap"></div>
        <p class="mf-line mr" style="color:var(--t2)">Yang dibutuhkan hanyalah</p>
        <p class="mf-line mr" style="color:var(--t2)">tempat untuk bertemu.</p>
        <div class="mf-gap"></div>
        <p class="mf-line ac mr" style="color:#38A8CC">Dan <span class="brand-em">E<span>MINOR</span></span> ingin menjadi tempat itu.</p>
      </div>
    </div>

    <div class="rv">
      <div class="live-ey"><div class="ldot"></div> Live Terus</div>
      <div class="live-h">Hari ini di EMINOR</div>
      @forelse($liveActivity as $act)
      <div class="lcard">
        <div class="lic">{{ $act['icon'] }}</div>
        <div>
          <div class="lu">{{ $act['user'] }}</div>
          <div class="ld">{{ $act['text'] }}</div>
          <div class="lt">{{ $act['time'] }}</div>
        </div>
      </div>
      @empty
      <div class="lcard"><div class="lic">🎤</div><div><div class="lu">Fajar</div><div class="ld">Baru upload lagu perdana ke komunitas</div><div class="lt">2 menit lalu</div></div></div>
      <div class="lcard"><div class="lic">🥁</div><div><div class="lu">Rama</div><div class="ld">Sedang mencari drummer di Yogyakarta</div><div class="lt">5 menit lalu</div></div></div>
      <div class="lcard"><div class="lic">🎸</div><div><div class="lu">Rina</div><div class="ld">Baru belajar chord progression Em-C-G</div><div class="lt">7 menit lalu</div></div></div>
      <div class="lcard"><div class="lic">❤️</div><div><div class="lu">Lagu "Rindu"</div><div class="ld">Mendapat 56 like dari komunitas</div><div class="lt">12 menit lalu</div></div></div>
      <div class="lcard"><div class="lic">🎪</div><div><div class="lu">Audisi Baru</div><div class="ld">Bassist dibutuhkan untuk rekaman EP di Bandung</div><div class="lt">18 menit lalu</div></div></div>
      @endforelse
      <a href="{{ route('gig.board') }}" class="live-more">Lihat semua aktivitas →</a>
    </div>

  </div>
</section>

{{-- ════ CARA KERJA ════ --}}
<section id="s-how">
  <div class="how-top rv">
    <div class="sec-ey">Cara Kerja</div>
    <h2 class="sec-h">Tiga langkah. Itu saja.</h2>
    <p class="sec-sub">Dari kamar — ke komunitas. Semua gratis, semua terbuka.</p>
  </div>
  <div class="how-steps rv">
    <div class="how-step">
      <div class="step-num">01</div>
      <span class="step-ic">👤</span>
      <div class="step-t">Buat Profil Musisi</div>
      <div class="step-d">Isi instrumen, genre, kota, dan bio singkat. Gratis, selesai dalam 2 menit lewat Google — tidak perlu username atau password.</div>
      <div class="step-arr"></div>
    </div>
    <div class="how-step">
      <div class="step-num">02</div>
      <span class="step-ic">🔍</span>
      <div class="step-t">Temukan Kolaborator</div>
      <div class="step-d">Filter musisi by instrumen, kota, dan genre. Lihat profil lengkap, kirim pesan langsung, connect tanpa perantara.</div>
      <div class="step-arr"></div>
    </div>
    <div class="how-step">
      <div class="step-num">03</div>
      <span class="step-ic">🎶</span>
      <div class="step-t">Mulai Berkarya</div>
      <div class="step-d">Lamar gig, rekam bareng, atau rilis lagu pertama dengan 14 alat studio gratis — dari cover art sampai release planner.</div>
    </div>
  </div>
</section>

{{-- ════ FITUR + ROADMAP + CTA ════ --}}
<section id="s-feat">
  <div class="feat-top rv">
    <h2>Semua yang kamu butuhkan</h2>
    <p>Tools, komunitas, dan peluang — dalam satu platform. Gratis.</p>
  </div>

  <div class="fgrid rv">
    <a href="{{ route('tools.chord-builder') }}" class="fcard">
      <span class="fc-ic">🎸</span>
      <div class="fc-t">Chord Builder</div>
      <div class="fc-d">Visualisasi chord gitar, ukulele, piano & bass. Transposi otomatis, semua kunci.</div>
      <div class="fc-link">Coba sekarang →</div>
    </a>
    <a href="{{ route('tools.bpm-kalkulator') }}" class="fcard">
      <span class="fc-ic">🥁</span>
      <div class="fc-t">BPM Calculator</div>
      <div class="fc-d">Tap tempo untuk menemukan BPM lagu yang sedang kamu dengar. Plus metronome visual.</div>
      <div class="fc-link">Coba sekarang →</div>
    </a>
    <a href="{{ route('gig.board') }}" class="fcard">
      <span class="fc-ic">🎪</span>
      <div class="fc-t">Papan Gig</div>
      <div class="fc-d">Audisi, session player, open mic & rekaman — peluang dari seluruh Indonesia.</div>
      <div class="fc-link">Lihat gig →</div>
    </a>
    <a href="{{ route('tools.epk') }}" class="fcard">
      <span class="fc-ic">📄</span>
      <div class="fc-t">EPK Generator</div>
      <div class="fc-d">Buat Electronic Press Kit profesional untuk dikirim ke label, booker & promotor.</div>
      <div class="fc-link">Buat EPK →</div>
    </a>
    <a href="{{ route('library.materi') }}" class="fcard">
      <span class="fc-ic">📚</span>
      <div class="fc-t">Materi Musik</div>
      <div class="fc-d">31 artikel lengkap: teori, karir, produksi, rilis, dan bisnis musik indie.</div>
      <div class="fc-link">Baca materi →</div>
    </a>
    <a href="{{ route('google.login') }}" class="fcard">
      <span class="fc-ic">👥</span>
      <div class="fc-t">Direktori Musisi</div>
      <div class="fc-d">Temukan kolaborator, session player, dan personil band berdasarkan kota & genre.</div>
      <div class="fc-link">Cari musisi →</div>
    </a>
  </div>

  <div class="rm-row rv">
    <div class="rm-head">Yang sudah ada</div>
    <div class="rm-pills">
      @foreach(['Profil Musisi','Chat & Grup','Timeline','Follow','Chord Builder','Guitar Tuner','BPM','Kalkulator Royalti','Papan Gig','EPK Generator','Release Planner','31 Materi','Setlist Builder','Transpose Kunci','Rate Card','Cover Art Maker'] as $d)
      <span class="rp done"><span class="rp-dot"></span>{{ $d }}</span>
      @endforeach
    </div>
    <div class="rm-head">Coming Soon</div>
    <div class="rm-pills">
      @foreach(['Marketplace Session','Studio Finder','AI Chord dari Audio'] as $s)
      <span class="rp soon"><span class="rp-dot"></span>{{ $s }}</span>
      @endforeach
    </div>
  </div>

  <div class="cta-wrap rv">
    <div class="aurora" style="opacity:.45"></div>
    <h2 class="cta-h">Masih Berkarya Sendirian?</h2>
    <p class="cta-sub">Bergabunglah bersama musisi Indonesia yang percaya karya hebat tidak ditentukan oleh tempat lahir.</p>
    <button class="cta-btn" onclick="openModal()">
      <div class="cta-line"></div>
      <div class="cta-inner">🎵 &nbsp; MULAI PERJALANAN MUSIKMU</div>
      <div class="cta-line"></div>
    </button>
  </div>
</section>

{{-- ════ FOOTER ════ --}}
<footer>
  <svg class="f-wave" viewBox="0 0 1200 50" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M0,25 C150,6 300,44 450,25 C600,6 750,44 900,25 C1050,6 1150,38 1200,25" stroke="#38A8CC" stroke-width="1.5" fill="none"/>
    <path d="M0,30 C120,12 280,48 440,30 C600,12 760,48 920,30 C1080,12 1160,40 1200,30" stroke="#5B6EF5" stroke-width="1" fill="none" opacity=".45"/>
    <path d="M0,20 C200,35 400,8 600,22 C800,36 1000,10 1200,20" stroke="#8B5CF6" stroke-width=".8" fill="none" opacity=".3"/>
  </svg>
  <div class="flogo">E<span>MINOR</span></div>
  <p class="ftag">Ekosistem Musik Indie Indonesia<br>Rumah pertama bagi musisi yang sedang tumbuh sendirian.</p>
  <div class="fdiv"></div>
  <p class="fpoem">
    Karena setiap lagu...<br>
    <em>selalu dimulai</em><br>
    <em>oleh seseorang yang berani</em><br>
    <em>memainkan chord pertama.</em>
  </p>
  <div class="flinks">
    <a href="{{ route('tools.index') }}">Alat Musisi</a>
    <a href="{{ route('gig.board') }}">Papan Gig</a>
    <a href="{{ route('library.materi') }}">Materi</a>
    <a href="{{ route('library') }}">Diskografi</a>
    <a href="{{ route('google.login') }}">Masuk</a>
  </div>
  <p class="fcopy">© {{ date('Y') }} EMINOR — Ekosistem Musik Indie Indonesia · margonoandi.my.id</p>
</footer>

{{-- ════ MODAL ════ --}}
<div id="mbg" onclick="if(event.target===this)closeModal()">
  <div class="mbox">
    <button class="mclose" onclick="closeModal()">✕</button>
    <div class="mh">Selamat datang. 👋</div>
    <p class="ms">Apa yang sedang kamu cari?</p>
    <div class="mopts">
      @foreach(['Belajar gitar','Mencari band','Mencari personil','Menulis lagu','Membagikan karya','Bertemu musisi'] as $o)
      <label class="mopt"><input type="radio" name="intent" value="{{ $loop->index }}">{{ $o }}</label>
      @endforeach
    </div>
    <a href="{{ route('google.login') }}" class="mlogin">
      <svg width="17" height="17" viewBox="0 0 18 18" fill="none"><path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/><path d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/><path d="M3.964 10.71A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05"/><path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.958L3.964 6.29C4.672 4.163 6.656 3.58 9 3.58z" fill="#EA4335"/></svg>
      Lanjutkan dengan Google
    </a>
  </div>
</div>

<script>
// ── CURSOR GLOW ──
(function(){
  var g=document.getElementById('cglow');
  document.addEventListener('mousemove',function(e){g.style.left=e.clientX+'px';g.style.top=e.clientY+'px';});
})();

// ── INTRO ──
(function(){
  if(sessionStorage.getItem('eminor_i')){document.getElementById('intro').style.display='none';startHero();return;}
  var AC=window.AudioContext||window.webkitAudioContext;
  function tick(){
    if(!AC)return;
    var c=new AC(),o=c.createOscillator(),g=c.createGain();
    o.connect(g);g.connect(c.destination);
    o.frequency.value=880;o.type='sine';
    g.gain.setValueAtTime(.08,c.currentTime);
    g.gain.exponentialRampToValueAtTime(.001,c.currentTime+.08);
    o.start();o.stop(c.currentTime+.1);
    setTimeout(function(){c.close();},200);
  }
  var di=0,ds=[document.getElementById('d1'),document.getElementById('d2'),document.getElementById('d3')];
  function pd(){var d=ds[di%3];di++;d.style.animation='none';d.offsetHeight;d.style.animation='dpop .4s ease forwards';}
  var vinyl=document.getElementById('ivinyl');
  var seq=[
    [0,   function(){tick();pd();}],
    [480, function(){tick();pd();}],
    [960, function(){tick();pd();vinyl.classList.add('go');}],
    [1200,function(){sh('l1');}],
    [1700,function(){sh('l2');}],
    [2700,function(){hh('l1');hh('l2');sh('l3');}],
    [3200,function(){sh('l4');}],
    [4300,function(){hh('l3');hh('l4');document.getElementById('ilogo').classList.add('s');document.getElementById('itag').classList.add('s');}],
    [5500,function(){done();}],
  ];
  function sh(id){document.getElementById(id).classList.add('s');}
  function hh(id){document.getElementById(id).classList.remove('s');}
  function done(){
    var el=document.getElementById('intro');
    el.classList.add('out');
    setTimeout(function(){el.style.display='none';startHero();},900);
    sessionStorage.setItem('eminor_i','1');
  }
  seq.forEach(function(s){setTimeout(s[1],s[0]);});
})();

function skipIntro(){
  sessionStorage.setItem('eminor_i','1');
  var el=document.getElementById('intro');
  el.classList.add('out');
  setTimeout(function(){el.style.display='none';startHero();},500);
}

// ── HERO TEXT LOOP ──
function startHero(){
  var FADE=800,GAP=280;
  var items=[
    {id:'hf0',hold:700},{id:'hf1',hold:1600},
    {id:'hf2',hold:700},{id:'hf3',hold:1600},
    {id:'hf4',hold:1600},
    {id:'hf5',hold:1600},{id:'hf6',hold:1600},{id:'hf7',hold:2400},
    {id:'hf8',hold:3200},{id:'hf9',hold:3200},
  ];
  var idx=0,ctaShown=false;
  function showItem(){
    var item=items[idx],el=document.getElementById(item.id);
    if(!el)return next();
    el.classList.add('s');
    setTimeout(function(){
      el.classList.remove('s');
      setTimeout(function(){
        if(!ctaShown&&idx===items.length-1){
          ctaShown=true;
          var ha=document.getElementById('hact');
          var hb=document.getElementById('hero-bar');
          if(ha)ha.classList.add('s');
          if(hb)hb.classList.add('s');
        }
        next();
      },FADE+GAP);
    },FADE+item.hold);
  }
  function next(){idx=(idx+1)%items.length;showItem();}
  setTimeout(showItem,200);
  var sl=document.querySelectorAll('.hslide'),si=0;
  setInterval(function(){sl[si].classList.remove('on');si=(si+1)%sl.length;sl[si].classList.add('on');},4500);
}

// ── NAVBAR ──
window.addEventListener('scroll',function(){document.getElementById('nav').classList.toggle('on',scrollY>60);});

// ── SCROLL REVEAL ──
(function(){
  var obs=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting)e.target.classList.add('on');});},{threshold:.08});
  document.querySelectorAll('.rv').forEach(function(el){obs.observe(el);});
})();

// ── STAT COUNTERS ──
(function(){
  var done=false;
  new IntersectionObserver(function(es){
    if(es[0].isIntersecting&&!done){
      done=true;
      document.querySelectorAll('.sc').forEach(function(el){
        var t=parseInt(el.dataset.t),st=performance.now();
        (function loop(now){
          var p=Math.min((now-st)/1400,1),e=1-Math.pow(1-p,3);
          el.textContent=Math.floor(e*t);
          if(p<1)requestAnimationFrame(loop);else el.textContent=t;
        })(performance.now());
      });
    }
  },{threshold:.4}).observe(document.querySelector('.stats-strip')||document.body);
})();

// ── MANIFESTO STAGGER ──
(function(){
  var done=false;
  var obs=new IntersectionObserver(function(es){
    if(es[0].isIntersecting&&!done){
      done=true;
      var ey=document.getElementById('mf-ey');if(ey)ey.classList.add('on');
      var wrap=document.getElementById('mf-bar-wrap');
      if(wrap)setTimeout(function(){wrap.classList.add('go');},120);
      document.querySelectorAll('#mf-bar-wrap .mr').forEach(function(el,i){
        setTimeout(function(){el.classList.add('on');},180+i*110);
      });
      obs.disconnect();
    }
  },{threshold:.18});
  var sec=document.getElementById('s-mid');if(sec)obs.observe(sec);
})();

// ── 3D CARD TILT ──
document.querySelectorAll('.fcard').forEach(function(c){
  c.addEventListener('mousemove',function(e){
    var r=c.getBoundingClientRect(),x=(e.clientX-r.left)/r.width-.5,y=(e.clientY-r.top)/r.height-.5;
    c.style.transform='perspective(700px) rotateY('+(x*10)+'deg) rotateX('+(-y*10)+'deg) translateZ(8px)';
  });
  c.addEventListener('mouseleave',function(){
    c.style.transition='.45s ease';c.style.transform='';
    setTimeout(function(){c.style.transition='';},450);
  });
});

// ── MODAL ──
function openModal(){document.getElementById('mbg').classList.add('on');document.body.style.overflow='hidden';}
function closeModal(){document.getElementById('mbg').classList.remove('on');document.body.style.overflow='';}
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeModal();});

// ── MOBILE NAV ──
function toggleNav(){
  var l=document.querySelector('.nlinks'),c=document.querySelector('.ncta');
  if(!l)return;
  var open=l.style.display==='flex';
  l.style.cssText=open?'':'display:flex;flex-direction:column;position:fixed;top:60px;left:0;right:0;background:rgba(6,8,15,.97);backdrop-filter:blur(16px);padding:1.25rem 2rem;gap:1rem;border-bottom:1px solid var(--border);z-index:800';
  if(c)c.style.cssText=open?'':'display:block;margin:0 2rem 1.25rem;text-align:center';
}
</script>
</body>
</html>
