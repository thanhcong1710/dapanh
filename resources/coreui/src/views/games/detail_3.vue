<template>
  <div style="display: flex; flex-direction: column; justify-content: center;align-items: center ">
    <div style="padding: 50px">
      <transition appear
        :enter-active-class="enterClass">
        <div class="box" :key="show">
           Animate
        </div>
      </transition>
    </div>
    <div class="select-container">
    <div class="select-style">
      <select v-model="enterAnimation" @change="selectAnimate">
         <option v-for="(anim, index) in animationList" :value="anim" :key="index">{{ anim }}</option>
      </select>
    </div>
  </div>
    <h1>VueJS & Animate.css</h1>
  </div>
</template>
<script>
export default {
  name: "VueAnimate",
  data() {
    return {
      msg: "Welcome to Your Vue.js App",
      enterAnimation: "bounce",
      leaveAnimation: "bounceOutLeft",
      show: true,
      animationList: [
        "bounce",
        "flash",
        "flip",
        "headShake",
        "hinge",
        "jello",
        "pulse",
        "rubberBand",
        "shake",
        "swing",
        "tada",
        "wobble",
        "bounceIn",
        "bounceInDown",
        "bounceInLeft",
        "bounceInRight",
        "bounceInUp",
        "fadeIn",
        "fadeInDown",
        "fadeInDownBig",
        "fadeInLeft",
        "fadeInLeftBig",
        "fadeInRight",
        "fadeInRightBig",
        "fadeInUp",
        "fadeInUpBig",
        "flipInX",
        "flipInY",
        "lightSpeedIn",
        "rollIn",
        "rotateIn",
        "rotateInDownLeft",
        "rotateInDownRight",
        "rotateInUpLeft",
        "rotateInUpRight",
        "slideInDown",
        "slideInLeft",
        "slideInRight",
        "slideInUp",
        "zoomIn",
        "zoomInDown",
        "zoomInLeft",
        "zoomInRight",
        "zoomInUp",
        "bounceOut",
        "bounceOutDown",
        "bounceOutLeft",
        "bounceOutRight",
        "bounceOutUp",
        "fadeOut",
        "fadeOutDown",
        "fadeOutDownBig",
        "fadeOutLeft",
        "fadeOutLeftBig",
        "fadeOutRight",
        "fadeOutRightBig",
        "fadeOutUp",
        "fadeOutUpBig",
        "flipOutX",
        "flipOutY",
        "lightSpeedOut",
        "rollOut",
        "rotateOut",
        "rotateOutDownLeft",
        "rotateOutDownRight",
        "rotateOutUpLeft",
        "rotateOutUpRight",
        "slideOutDown",
        "slideOutLeft",
        "slideOutRight",
        "slideOutUp",
        "slideOutRight",
        "zoomOut",
        "zoomOutDown",
        "zoomOutLeft",
        "zoomOutRight",
        "zoomOutUp"
      ]
    };
  },
  methods: {
    selectAnimate() {
      this.show = !this.show;
    }
  },
  computed: {
    enterClass() {
      return `animated ${this.enterAnimation}`;
    }
  }
};
</script>
<style>
.box {
  width: 200px;
  height: 200px;
  background-color: #f00;
  text-align: center;
  font-size: 30px;
  color: #fff;
  line-height: 200px;
  margin: 10px;
  cursor: pointer;
  display: inline-block;
}
.select-container {
  display: flex;
  flex-direction: row;
  width: 100%;
  justify-content: center;
}
.select-style {
  border: 1px solid #ccc;
  width: 30%;
  margin: 10px;
  border-radius: 3px;
  overflow: hidden;
  background: #fafafa
    url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAM0lEQVRIiWNgGAWjYBQMf9DAwMDwnwBuoKUlFBuOzxKqGY7NEqobjmwJzQwfBaNgFJAJAA9IGO3rEoqTAAAAAElFTkSuQmCC")
    no-repeat 90% 50%;
}
.select-style select {
  padding: 5px 8px;
  width: 100%;
  border: none;
  box-shadow: none;
  background: transparent;
  background-image: none;
  -webkit-appearance: none;
}

.select-style select:focus {
  outline: none;
}
.animated {
  -webkit-animation-duration: 3s;
  -webkit-animation-delay: 0s;
}
</style>
