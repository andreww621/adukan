(()=>{
const el=document.getElementById('scene');if(!el||!window.THREE){if(el)el.hidden=true;return}
const T=THREE,R=new T.WebGLRenderer({antialias:true,alpha:true}),S=new T.Scene(),C=new T.PerspectiveCamera(38,1,.1,50);
R.setPixelRatio(Math.min(devicePixelRatio,2));el.append(R.domElement);C.position.set(0,0,10);
S.add(new T.AmbientLight(0xffffff,.75));const L=new T.DirectionalLight(0xffffff,1);L.position.set(4,5,6);S.add(L);
const mat=c=>new T.MeshStandardMaterial({color:c,roughness:.35,metalness:.15,side:T.DoubleSide}),red=mat(0xD91E26),dark=mat(0x1B1214);
const G=new T.Group();S.add(G);
const M=new T.Group();M.rotation.z=-Math.PI/2;G.add(M);
const add=(g,m,y,x=0)=>{const o=new T.Mesh(g,m);o.position.set(x,y,0);M.add(o);return o};
add(new T.CylinderGeometry(1.25,.45,2.2,48,1,true),red,0);
add(new T.TorusGeometry(1.25,.09,16,64),dark,1.1).rotation.x=Math.PI/2;
add(new T.CylinderGeometry(.5,.5,.5,32),dark,-1.3);
add(new T.BoxGeometry(1,.28,.28),dark,-.4,.75);
const ring=new T.Mesh(new T.TorusGeometry(2.3,.07,16,90),red);ring.rotation.x=1.1;G.add(ring);
const wv=[0,1,2].map(i=>{const w=new T.Mesh(new T.RingGeometry(1.2,1.3,48),new T.MeshBasicMaterial({color:0xD91E26,transparent:true,side:T.DoubleSide}));w.rotation.y=Math.PI/2;G.add(w);return w});
const cols=[0xD64545,0xB7791F,0x2E9E5B],tk=cols.map(c=>{const o=new T.Mesh(new T.IcosahedronGeometry(.4,0),mat(c));S.add(o);return o});
const fit=()=>{const w=el.clientWidth,h=el.clientHeight;R.setSize(w,h);C.aspect=w/h;C.updateProjectionMatrix()};new ResizeObserver(fit).observe(el);fit();
const rm=matchMedia('(prefers-reduced-motion:reduce)').matches;let sp=0,t=0;
(function loop(){requestAnimationFrame(loop);if(document.hidden)return;
const p=window.__p||0,i=window.__i||0;sp+=(p-sp)*.08;if(!rm)t+=.016;
G.rotation.y=-.6+sp*Math.PI*1.6;G.rotation.x=.12+Math.sin(t)*.05;G.position.y=Math.sin(t*1.2)*.15;C.position.z=10-sp*2;ring.rotation.z=t*.5+sp*4;
wv.forEach((w,k)=>{const f=(t*.5+k/3)%1;w.position.x=1.7+f*.6;w.scale.setScalar(.6+f*1.1);w.material.opacity=.7*(1-f)});
tk.forEach((o,k)=>{const a=k*2.094+t*.4+sp*3,s=k==i?1.5:.8;o.position.set(Math.cos(a)*3.4,Math.sin(a)*1.6,Math.sin(a)*1.5);o.rotation.x=o.rotation.y=t+k;o.scale.setScalar(o.scale.x+(s-o.scale.x)*.1)});
R.render(S,C)})();
})();
