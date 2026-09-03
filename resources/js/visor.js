import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import * as SkeletonUtils from 'three/examples/jsm/utils/SkeletonUtils.js';

const state = {
    container: null,
    renderer: null,
    scene: null,
    camera: null,
    orbit: null,
    model: null,
    pyramidGroup: null,
    imageGroup: null,
    mode: 'caja',
    pyramidTipo: 'glb',
    url: null,
    animationId: null,
};

function fitModel(obj) {
    const box = new THREE.Box3().setFromObject(obj);
    const size = box.getSize(new THREE.Vector3());
    const center = box.getCenter(new THREE.Vector3());
    const maxDim = Math.max(size.x, size.y, size.z) || 1;
    const scale = 2.4 / maxDim;
    obj.scale.set(scale, scale, scale);
    const newCenter = new THREE.Box3().setFromObject(obj).getCenter(new THREE.Vector3());
    obj.position.sub(newCenter);
}

function getModelBounds(obj) {
    const box = new THREE.Box3().setFromObject(obj);
    const size = box.getSize(new THREE.Vector3());
    return Math.max(size.x, size.y, size.z, 0.1);
}

/* ---------- pirámide GLB: 4 clones en cruz, espejados, top hacia el centro ---------- */
function buildPyramidClones(model) {
    clearPyramid();

    const group = new THREE.Group();
    const maxDim = getModelBounds(model);
    const dist = maxDim * 0.85 + 0.6;

    for (let i = 0; i < 4; i++) {
        const clone = SkeletonUtils.clone(model);
        clone.traverse((obj) => {
            if (obj.isMesh && obj.material) {
                obj.material = obj.material.clone();
            }
        });
        const pivot = new THREE.Group();
        pivot.rotation.y = (Math.PI / 2) * i;
        pivot.position.set(
            Math.sin((Math.PI / 2) * i) * dist,
            0,
            Math.cos((Math.PI / 2) * i) * dist
        );
        const wrap = new THREE.Group();
        wrap.add(clone);
        wrap.rotation.x = -Math.PI / 2;
        wrap.scale.x *= -1;
        pivot.add(wrap);
        group.add(pivot);
    }
    group.visible = false;
    state.scene.add(group);
    state.pyramidGroup = group;
}

function clearPyramid() {
    if (!state.pyramidGroup) return;
    disposeGroup(state.pyramidGroup);
    state.scene.remove(state.pyramidGroup);
    state.pyramidGroup = null;
}

/* ---------- pirámide imágenes: 4 planos estáticos en cruz ---------- */
function buildImagePyramid(rutas) {
    clearImageGroup();

    const group = new THREE.Group();
    const loader = new THREE.TextureLoader();
    let loaded = 0;
    const total = rutas.slice(0, 4).filter(Boolean).length;

    if (total === 0) return;

    const dist = 1.35;

    rutas.slice(0, 4).forEach((ruta, i) => {
        if (!ruta) return;
        const url = ruta.startsWith('http') ? ruta : window.location.origin + '/' + ruta;
        loader.load(url, (texture) => {
            texture.colorSpace = THREE.SRGBColorSpace;
            const geo = new THREE.PlaneGeometry(1.7, 1.7);
            const mat = new THREE.MeshBasicMaterial({ map: texture, side: THREE.DoubleSide });
            const mesh = new THREE.Mesh(geo, mat);

            const angle = (Math.PI / 2) * i;
            const pivot = new THREE.Group();
            pivot.rotation.y = angle;
            pivot.position.set(Math.sin(angle) * dist, 0, Math.cos(angle) * dist);
            pivot.add(mesh);
            mesh.rotation.x = -Math.PI / 2;
            pivot.scale.x = -1;

            group.add(pivot);
            loaded++;
            if (loaded >= total) applyMode(state.mode);
        }, undefined, () => {
            console.warn('No se pudo cargar imagen:', ruta);
            loaded++;
        });
    });

    group.visible = false;
    state.scene.add(group);
    state.imageGroup = group;
}

function clearImageGroup() {
    if (!state.imageGroup) return;
    disposeGroup(state.imageGroup);
    state.scene.remove(state.imageGroup);
    state.imageGroup = null;
}

function disposeGroup(group) {
    group.traverse((obj) => {
        if (obj.geometry) obj.geometry.dispose();
        if (obj.material) {
            const mats = Array.isArray(obj.material) ? obj.material : [obj.material];
            mats.forEach((m) => {
                if (m.map) m.map.dispose();
                m.dispose();
            });
        }
    });
}

/* ---------- cámara y modos ---------- */
function applyMode(mode) {
    state.mode = mode;

    const isPyramid = mode === 'piramide';
    const useImages = isPyramid && state.pyramidTipo === 'imagenes';

    if (state.model) state.model.visible = !isPyramid;
    if (state.pyramidGroup) state.pyramidGroup.visible = isPyramid && !useImages;
    if (state.imageGroup) state.imageGroup.visible = useImages;

    if (!state.orbit) return;

    if (isPyramid) {
        state.orbit.enableRotate = false;
        state.orbit.enableZoom = false;
        state.orbit.enablePan = false;
        state.orbit.autoRotate = false;
        const maxDim = state.model ? getModelBounds(state.model) : 2;
        const pyMax = state.pyramidGroup ? getModelBounds(state.pyramidGroup) : maxDim * 2 + 1.2;
        const imgMax = state.imageGroup ? getModelBounds(state.imageGroup) : pyMax;
        const radius = useImages ? imgMax : pyMax;
        state.camera.position.set(0, radius * 1.4 + 0.5, 0.001);
        state.camera.lookAt(0, 0, 0);
        state.orbit.target.set(0, 0, 0);
    } else {
        state.orbit.enableRotate = true;
        state.orbit.enableZoom = true;
        state.orbit.enablePan = false;
        state.orbit.autoRotate = true;
        state.camera.position.set(0, 3.2, 3.2);
        state.camera.lookAt(0, 0, 0);
        state.orbit.target.set(0, 0, 0);
    }
    state.orbit.update();
}

/* ---------- construcción base ---------- */
function buildView() {
    const container = state.container;
    const w = container.clientWidth || 420;
    const h = container.clientHeight || 420;

    state.scene = new THREE.Scene();
    state.scene.background = new THREE.Color(0x000000);

    state.camera = new THREE.PerspectiveCamera(45, w / h, 0.1, 1000);
    state.camera.position.set(0, 3.2, 3.2);
    state.camera.lookAt(0, 0, 0);

    if (!state.renderer) {
        state.renderer = new THREE.WebGLRenderer({ antialias: true });
        state.renderer.setPixelRatio(window.devicePixelRatio);
    }
    state.renderer.setSize(w, h);
    if (!state.renderer.domElement.parentNode) {
        container.appendChild(state.renderer.domElement);
    }

    state.scene.add(new THREE.AmbientLight(0xffffff, 0.6));
    const dir = new THREE.DirectionalLight(0xffffff, 1.1);
    dir.position.set(3, 5, 3);
    state.scene.add(dir);
    const fill = new THREE.DirectionalLight(0xffffff, 0.4);
    fill.position.set(-3, 2, -3);
    state.scene.add(fill);

    state.orbit = new OrbitControls(state.camera, state.renderer.domElement);
    state.orbit.target.set(0, 0, 0);
    state.orbit.enablePan = false;
    state.orbit.autoRotate = true;
    state.orbit.autoRotateSpeed = 2;

    function animate() {
        state.animationId = requestAnimationFrame(animate);
        state.orbit.update();
        state.renderer.render(state.scene, state.camera);
    }
    animate();
}

/* ---------- carga de contenido ---------- */
function loadModel(url) {
    console.log('[LeafLogVisor] cargando modelo:', url);
    const loader = new GLTFLoader();
    loader.load(url, (gltf) => {
        console.log('[LeafLogVisor] modelo cargado', gltf.scene);
        state.model = gltf.scene;
        state.scene.add(state.model);
        fitModel(state.model);
        buildPyramidClones(state.model);
        applyMode(state.mode);
        console.log('[LeafLogVisor] modelo agregado a escena');
    }, undefined, (err) => {
        console.error('[LeafLogVisor] error cargando modelo:', err);
        const p = document.createElement('p');
        p.style.cssText = 'color:#fff;text-align:center;padding:24px';
        p.textContent = 'No se pudo cargar el modelo 3D.';
        while (state.container.firstChild) state.container.removeChild(state.container.firstChild);
        state.container.appendChild(p);
    });
}

function loadImages(url) {
    let rutas;
    try {
        rutas = JSON.parse(url);
    } catch {
        rutas = null;
    }
    if (Array.isArray(rutas) && rutas.filter(Boolean).length > 0) {
        buildImagePyramid(rutas);
    }
}

function cargar(url, tipo) {
    state.url = url;

    if (tipo === 'imagenes') {
        loadImages(url);
    } else {
        if (!state.model) loadModel(url);
    }
}

/* ---------- limpieza ---------- */
function destroy() {
    if (state.animationId) {
        cancelAnimationFrame(state.animationId);
        state.animationId = null;
    }
    if (state.model) {
        disposeGroup(state.model);
        state.scene.remove(state.model);
        state.model = null;
    }
    clearPyramid();
    clearImageGroup();
    if (state.orbit) {
        state.orbit.dispose();
        state.orbit = null;
    }
    state.scene = null;
    state.camera = null;
}

function resize() {
    if (!state.renderer || !state.container || !state.camera) return;
    const w = state.container.clientWidth || 420;
    const h = state.container.clientHeight || 420;
    state.renderer.setSize(w, h);
    state.camera.aspect = w / h;
    state.camera.updateProjectionMatrix();
}

/* ---------- API pública ---------- */
window.LeafLogVisor = {
    init(containerId, url, mode = 'caja', pyramidTipo = 'glb') {
        console.log('[LeafLogVisor] init', containerId, url, mode, pyramidTipo);
        destroy();
        state.container = document.getElementById(containerId);
        if (!state.container) { console.warn('[LeafLogVisor] contenedor no encontrado'); return; }
        state.mode = mode;
        state.pyramidTipo = pyramidTipo;
        buildView();
        cargar(url, pyramidTipo);
    },
    cargar(url, pyramidTipo) {
        if (pyramidTipo !== undefined) state.pyramidTipo = pyramidTipo;
        cargar(url, pyramidTipo);
    },
    setMode(mode, opts = {}) {
        if (opts.pyramideTipo !== undefined) state.pyramidTipo = opts.pyramideTipo;
        applyMode(mode);
    },
    resize,
    destroy,
};

document.addEventListener('DOMContentLoaded', () => {
    const cfg = window.__LEAFLOG_VISOR__;
    if (!cfg || !cfg.container || !cfg.url) return;
    const canvas = document.getElementById(cfg.container);
    if (!canvas || canvas.clientWidth === 0) return;
    window.LeafLogVisor.init(cfg.container, cfg.url, cfg.mode || 'caja', cfg.pyramideTipo || 'glb');
});
