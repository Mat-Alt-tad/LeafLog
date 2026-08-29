import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';

const state = {
    container: null,
    renderer: null,
    scene: null,
    camera: null,
    model: null,
    mode: 'caja',
    clock: null,
    resizeHandler: null,
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
    obj.position.z = 0;
}

function buildView() {
    const container = state.container;
    const w = container.clientWidth || 420;
    const h = container.clientHeight || 420;

    state.scene = new THREE.Scene();
    state.scene.background = new THREE.Color(0x000000);

    state.camera = new THREE.PerspectiveCamera(45, w / h, 0.1, 1000);
    state.camera.position.set(0, 3.2, 3.2);
    state.camera.lookAt(0, 0, 0);

    state.renderer = new THREE.WebGLRenderer({ antialias: true });
    state.renderer.setSize(w, h);
    container.appendChild(state.renderer.domElement);

    const ambient = new THREE.AmbientLight(0xffffff, 0.6);
    state.scene.add(ambient);
    const dir = new THREE.DirectionalLight(0xffffff, 1.1);
    dir.position.set(3, 5, 3);
    state.scene.add(dir);
    const fill = new THREE.DirectionalLight(0xffffff, 0.4);
    fill.position.set(-3, 2, -3);
    state.scene.add(fill);

    const orbit = new OrbitControls(state.camera, state.renderer.domElement);
    orbit.target.set(0, 0, 0);
    orbit.enablePan = false;
    orbit.autoRotate = true;
    orbit.autoRotateSpeed = 2;

    state.clock = new THREE.Clock();

    function animate() {
        requestAnimationFrame(animate);
        orbit.update();
        state.renderer.render(state.scene, state.camera);
    }
    animate();
}

function applyMode(mode) {
    state.mode = mode;
    if (mode === 'piramide' && state.model) {
        state.model.traverse((obj) => {
            if (obj.isMesh) obj.scale.x = -Math.abs(obj.scale.x);
        });
    } else if (state.model) {
        state.model.traverse((obj) => {
            if (obj.isMesh) obj.scale.x = Math.abs(obj.scale.x);
        });
    }
}

function cargar(url) {
    destroy();
    buildView();
    const loader = new GLTFLoader();
    loader.load(url, (gltf) => {
        state.model = gltf.scene;
        state.scene.add(state.model);
        fitModel(state.model);
        applyMode(state.mode);
    }, undefined, () => {
        const el = state.container;
        el.innerHTML = '<p style="color:#fff;text-align:center;padding:24px">No se pudo cargar el modelo 3D.</p>';
    });
}

function destroy() {
    if (state.renderer) {
        if (state.renderer.domElement && state.renderer.domElement.parentNode) {
            state.renderer.domElement.parentNode.removeChild(state.renderer.domElement);
        }
        state.renderer.dispose();
    }
    state.renderer = null;
    state.scene = null;
    state.camera = null;
    state.model = null;
    state.clock = null;
}

window.LeafLogVisor = {
    init(containerId, url, mode = 'caja') {
        state.container = document.getElementById(containerId);
        if (!state.container) return;
        state.mode = mode;
        cargar(url);
    },
    cargar(url) {
        cargar(url);
    },
    setMode(mode) {
        applyMode(mode);
    },
    destroy,
};

document.addEventListener('DOMContentLoaded', () => {
    const cfg = window.__LEAFLOG_VISOR__;
    if (!cfg || !cfg.container || !cfg.url) return;
    const canvas = document.getElementById(cfg.container);
    if (!canvas || canvas.clientWidth === 0) return;
    window.LeafLogVisor.init(cfg.container, cfg.url, cfg.mode || 'caja');
});
