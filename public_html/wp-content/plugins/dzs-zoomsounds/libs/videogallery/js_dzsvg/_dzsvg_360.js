

exports.videoTexture = null;
exports.scene = null;
exports.cube = null;
exports.sphereMat = null;
exports.cubeGeometry = null;
exports.renderer = null;
exports.camera = null;
exports.controls = null
;

exports.resizePlayer = function (warg, harg) {

  if (this.renderer) {


    this.renderer.setSize(warg, harg);
    this.camera.aspect = warg / harg;
  }
}
exports.enableControls = function () {


  if (this.controls) {
    this.controls.enabled = true;
  }
}
exports.initPlayer = function (selfClass) {

  var o = selfClass.initOptions;
  var self = this;

  selfClass.cthis.addClass('is-360');
  selfClass.get_responsive_ratio({
    'called_from': '360'
  });
  if (selfClass.totalHeight == 0 && o.responsive_ratio) {
    selfClass.totalHeight = Number(o.responsive_ratio) * selfClass.totalWidth;
  }


  var ConstantsDzsvg = require('../configs/Constants').constants;

  jQuery.ajax({
    url: ConstantsDzsvg.THREEJS_LIB_URL,
    dataType: "script",
    success: function (arg) {



      jQuery.ajax({
        url: ConstantsDzsvg.THREEJS_LIB_ORBIT_URL,
        dataType: "script",
        success: function (arg) {





          self.renderer = new THREE.WebGLRenderer({antialias: true});
          self.renderer.setSize(selfClass.totalWidth, selfClass.totalHeight);
          self.renderer.alpha = true;
          jQuery(selfClass._videoElement).after(self.renderer.domElement);
          jQuery(selfClass._videoElement).next().addClass('dzsvg-360-canvas');




          self.scene = new THREE.Scene();


          selfClass._videoElement.setAttribute('crossorigin', 'anonymous');
          self.videoTexture = new THREE.Texture(selfClass._videoElement);
          self.videoTexture.minFilter = THREE.LinearFilter;
          self.videoTexture.magFilter = THREE.LinearFilter;
          self.videoTexture.format = THREE.RGBFormat;


          self.cubeGeometry = new THREE.SphereGeometry(500, 60, 40);
          self.sphereMat = new THREE.MeshBasicMaterial({map: self.videoTexture});
          self.sphereMat.side = THREE.BackSide;
          self.cube = new THREE.Mesh(self.cubeGeometry, self.sphereMat);
          self.scene.add(self.cube);


          self.camera = new THREE.PerspectiveCamera(45, selfClass.totalWidth / selfClass.totalHeight, 0.1, 10000);
          self.camera.position.y = 0;
          self.camera.position.z = 500;

          self.scene.add(self.camera);

          self.controls = new THREE.OrbitControls(self.camera);

          self.controls.enableDamping = false;
          self.controls.enableRotate = false;
          self.controls.dampingFactor = 0.25;

          self.controls.enableZoom = true;
          self.controls.maxDistance = 500;
          self.controls.minDistance = 500;
          self.controls.minDistance = 300;
          self.controls.maxDistance = 1000;

          self.controls.enabled = false;

          function render() {
            if (selfClass._videoElement.readyState === selfClass._videoElement.HAVE_ENOUGH_DATA) {
              self.videoTexture.needsUpdate = true;
            }
            self.controls.update();
            self.renderer.render(self.scene, self.camera);
            requestAnimationFrame(render);

          }


          render(selfClass);



        }
      });


    }
  });


}

exports.afterQualityChange = function(selfClass){

  selfClass._videoElement.setAttribute('crossorigin', 'anonymous');
  videoTexture = new THREE.Texture(video);
  videoTexture.minFilter = THREE.LinearFilter;
  videoTexture.magFilter = THREE.LinearFilter;
  videoTexture.format = THREE.RGBFormat;


  scene.remove(cube);

  cubeGeometry = new THREE.SphereGeometry(500, 60, 40);
  sphereMat = new THREE.MeshBasicMaterial({map: videoTexture});
  sphereMat.side = THREE.BackSide;
  cube = new THREE.Mesh(cubeGeometry, sphereMat);
  scene.add(cube);
}
exports.functionsInit = function (selfClass) {

  var $ = jQuery;


  selfClass.cthis.on('touchstart', function (e) {



    if (e.originalEvent && e.originalEvent.target && $(e.originalEvent.target).hasClass('video-overlay')) {
      selfClass.cthis.addClass('mouse-is-out');

    }

    if (controls) {
      controls.enabled = true;
    }
  })
  $(document).on('touchend', function (e) {


    selfClass.cthis.removeClass('mouse-is-out');
    if (controls) {

      controls.enabled = false;
    }
  })
}